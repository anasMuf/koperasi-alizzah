<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Ledger;
use App\Models\Member;
use App\Models\Teacher;
use App\Helpers\LogPretty;
use App\Models\YearPeriod;
use App\Models\MonthPeriod;
use App\Models\OrderPayment;
use Illuminate\Http\Request;
use App\Models\ReceivablesMember;
use Illuminate\Support\Facades\DB;
use App\Models\ReceivablesMemberPayment;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class ReceivablesController extends Controller
{
    private $menu = 'piutang';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){
        $data['menu'] = $this->menu;
        if($request->ajax()){
            $data = Order::with(['student'])
            ->whereColumn('terbayar', '<', 'total')
            ->get();

            return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('invoice_', function($row){
                return '<a href="#">'.$row->invoice.'</a>';
            })
            ->addColumn('total_', function($row){
                return 'Rp '.number_format($row->total,0,',','.');
            })
            ->addColumn('terbayar_', function($row){
                return 'Rp '.number_format($row->terbayar,0,',','.');
            })
            ->addColumn('sisa_', function($row){
                return 'Rp '.number_format($row->total - $row->terbayar,0,',','.');
            })
            ->addColumn('status', function($row){
                if($row->total == $row->terbayar){
                    return '<span style="border-radius: 6px; padding: 5px; background-color: green; color: #fff;">Lunas</span>';
                }else{
                    return '<span style="border-radius: 6px; padding: 5px; background-color: red; color: #fff;">Belum Lunas</span>';
                }
            })
            ->addColumn('action', function($row){
                $btn = '
                <button type="button" class="btn btn-primary btn-xs" onclick="openData('.$row->id.')">Buat Pembayaran</button>
                ';
                return $btn;
            })
            ->rawColumns([
                'invoice_',
                'total_',
                'terbayar_',
                'status',
                'action'
            ])
            ->make(true);
        }
        // $data['notifPiutang'] = count($data);
        return view('receivables.main',$data);
    }

    public function form(Request $request){
        $data['data'] = Order::with('student','order_details.product_variant.product')->find($request->id);
        $content = view('receivables.form',$data)->render();

        return response()->json([
            'success' => true,
            'content' => $content
        ],200);
    }

    public function store(Request $request){
        DB::beginTransaction();
        try {
            $rules = [
                'amount' => 'required|min:1',
                'paid_at' => 'nullable|date',
            ];

            $attributes = [
                'amount' => 'Nominal Dibayar',
                'paid_at' => 'Tanggal Bayar',
            ];
            $messages = [
                'required' => 'Kolom :attribute harus terisi',
                'min' => 'Kolom :attribute harus lebih dari 1 karakter',
                'date' => 'Kolom :attribute harus berupa tanggal'
            ];
            $validator = Validator::make($request->all(), $rules, $messages, $attributes);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->getMessageBag()
                ],422);
            }

            $order = Order::findOrFail($request->id);

            // Cek jika sudah lunas
            $remainingDebt = $order->total - $order->terbayar;
            if ($remainingDebt <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hutang sudah lunas.',
                    'errors' => []
                ], 422);
            }

            $amount = str_replace('.','',$request->amount);

            // Cek jika pembayaran melebihi hutang
            if ($amount > $remainingDebt) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah pembayaran melebihi hutang.',
                    'errors' => []
                ], 422);
            }

            // Catat pembayaran di tabel order_payments

            $purchase_date = date('Y-m-d', strtotime($request->paid_at));
            $purchase_month = date('m', strtotime($request->paid_at));

            $month_period = MonthPeriod::where('no_month', $purchase_month)->first();
            $month_period_id = $month_period ? $month_period->id : null;


            $year_period_id = null;
            $matching_year_period = YearPeriod::where(function($query) use ($purchase_date) {
                $query->where('start_date', '<=', $purchase_date)
                    ->where('end_date', '>=', $purchase_date);
            })->first();

            if ($matching_year_period) {
                $year_period_id = $matching_year_period->id;
            }

            $payment = new OrderPayment;
            $payment->order_id = $request->id;
            $payment->amount = $amount;
            $payment->paid_at = date('Y-m-d',strtotime($request->paid_at)) ?? date('Y-m-d');

            $order->transaction_category_id = 2;#harusnya piutang murid/walimurid
            $order->month_period_id = $month_period_id;
            $order->year_period_id = $year_period_id;

            $payment->save();

            // Update kolom `terbayar` di tabel orders
            $order->terbayar += $amount;
            $order->save();

            //ledger
            // $lastLedgerEntry = Ledger::latest()->first();
            // $current = $lastLedgerEntry ? $lastLedgerEntry->final : 0;

            $trx_date = date('Y-m-d H:i:s',strtotime($request->paid_at));

            $debit = $amount;
            $credit = 0;
            // $final = $current + $debit - $credit;
            $request->merge([
                'type' => 'pemasukan',
                'description' => 'bayar piutang',
                'refrence' => $payment->id,
                // 'current' => $current,
                'trx_date' => $trx_date,
                'debit' => $debit,
                'credit' => $credit,
                // 'final' => $final,
                'transaction_category_id' => 2,
                'month_period_id' => $month_period_id,
                'year_period_id' => $year_period_id,
            ]);

            Ledger::store($request);

            DB::commit();

            return response()->json([
                'success'=> true,
                'message' => 'Pembayaran Piutang berhasil dibuat',
            ],200);
        } catch (\Throwable $th) {
            DB::rollBack();
            LogPretty::error($th);
            return response()->json([
                'success'=> false,
                'message'=> 'Internal Server Error!',
            ],500);
        }
    }

    /**
     * Piutang member
     */

    public function member(Request $request){
        $data = ReceivablesMember::with(['member'])->get();

        return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('total_', function($row){
            return 'Rp '.number_format($row->total,0,',','.');
        })
        ->addColumn('terbayar_', function($row){
            return 'Rp '.number_format($row->terbayar,0,',','.');
        })
        ->addColumn('sisa_', function($row){
            return 'Rp '.number_format($row->total - $row->terbayar,0,',','.');
        })
        ->addColumn('status', function($row){
            if($row->total == $row->terbayar){
                return '<span style="border-radius: 6px; padding: 5px; background-color: green; color: #fff;">Lunas</span>';
            }else{
                return '<span style="border-radius: 6px; padding: 5px; background-color: red; color: #fff;">Belum Lunas</span>';
            }
        })
        ->addColumn('action', function($row){
            $btn = '
            <button type="button" class="btn btn-primary btn-xs" onclick="openDataOther('.$row->id.')">Buat Pembayaran</button>
            ';
            return $btn;
        })
        ->rawColumns([
            'total_',
            'terbayar_',
            'status',
            'action'
        ])
        ->make(true);
    }

    public function createMember(Request $request){
        $data['members'] = Member::all();
        $content = view('receivables.new',$data)->render();

        return response()->json([
            'success' => true,
            'content' => $content
        ],200);
    }
    public function newReceivables(Request $request){
        DB::beginTransaction();
        try {
            $rules = [
                'amount' => 'required|min:1',
                'date' => 'nullable|date',
                'member' => 'required',
            ];

            $attributes = [
                'amount' => 'Nominal Piutang',
                'date' => 'Tanggal',
                'member' => 'Anggota',
            ];
            $messages = [
                'required' => 'Kolom :attribute harus terisi',
                'min' => 'Kolom :attribute harus lebih dari 1 karakter',
                'date' => 'Kolom :attribute harus berupa tanggal'
            ];
            $validator = Validator::make($request->all(), $rules, $messages, $attributes);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->getMessageBag()
                ],422);
            }

            //receivales member

            $purchase_date = date('Y-m-d', strtotime($request->paid_at));
            $purchase_month = date('m', strtotime($request->paid_at));

            $month_period = MonthPeriod::where('no_month', $purchase_month)->first();
            $month_period_id = $month_period ? $month_period->id : null;


            $year_period_id = null;
            $matching_year_period = YearPeriod::where(function($query) use ($purchase_date) {
                $query->where('start_date', '<=', $purchase_date)
                    ->where('end_date', '>=', $purchase_date);
            })->first();

            if ($matching_year_period) {
                $year_period_id = $matching_year_period->id;
            }

            $amount = str_replace('.','',$request->amount);
            $receivablesMember = new ReceivablesMember;
            $receivablesMember->member_id = $request->member;
            $receivablesMember->description = $request->description;
            $receivablesMember->total = $amount;
            $receivablesMember->terbayar = 0;
            $receivablesMember->status = 'BELUM LUNAS';

            $receivablesMember->transaction_category_id = 0;
            $receivablesMember->month_period_id = $month_period_id;
            $receivablesMember->year_period_id = $year_period_id;

            $receivablesMember->save();

            //ledger
            // $lastLedgerEntry = Ledger::latest()->first();
            // $current = $lastLedgerEntry ? $lastLedgerEntry->final : 0;

            $trx_date = date('Y-m-d H:i:s',strtotime($request->date));

            $debit = 0;
            $credit = $amount;
            // $final = $current + $debit - $credit;
            $request->merge([
                'type' => 'pengeluaran',
                'description' => 'piutang anggota',
                'refrence' => $receivablesMember->id,
                // 'current' => $current,
                'trx_date' => $trx_date,
                'debit' => $debit,
                'credit' => $credit,
                // 'final' => $final,
                'transaction_category_id' => 0,
                'month_period_id' => $month_period_id,
                'year_period_id' => $year_period_id,
            ]);

            Ledger::store($request);

            DB::commit();

            return response()->json([
                'success'=> true,
                'message' => 'Piutang Anggota berhasil dibuat',
            ],200);
        } catch (\Throwable $th) {
            DB::rollBack();
            LogPretty::error($th,$request->all());
            return response()->json([
                'success'=> false,
                'message'=> 'Internal Server Error!',
            ],500);
        }
    }

    public function paymentMember(Request $request){
        $data['data'] = ReceivablesMember::with(['member','receivables_member_payments'])->find($request->id);
        $content = view('receivables.payment',$data)->render();

        return response()->json([
            'success' => true,
            'content' => $content
        ],200);
    }
    public function payReceivables(Request $request){
        DB::beginTransaction();
        try {
            $rules = [
                'amount' => 'required|min:1',
                'paid_at' => 'nullable|date',
            ];

            $attributes = [
                'amount' => 'Nominal Dibayar',
                'paid_at' => 'Tanggal Bayar',
            ];
            $messages = [
                'required' => 'Kolom :attribute harus terisi',
                'min' => 'Kolom :attribute harus lebih dari 1 karakter',
                'date' => 'Kolom :attribute harus berupa tanggal'
            ];
            $validator = Validator::make($request->all(), $rules, $messages, $attributes);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->getMessageBag()
                ],422);
            }

            $receivablesMember = ReceivablesMember::findOrFail($request->id);

            // Cek jika sudah lunas
            $remainingDebt = $receivablesMember->total - $receivablesMember->terbayar;
            if ($remainingDebt <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hutang sudah lunas.',
                    'errors' => []
                ], 422);
            }

            $amount = str_replace('.','',$request->amount);

            // Cek jika pembayaran melebihi hutang
            if ($amount > $remainingDebt) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah pembayaran melebihi hutang.',
                    'errors' => []
                ], 422);
            }

            // Catat pembayaran di tabel receivables_member_payments

            $purchase_date = date('Y-m-d', strtotime($request->paid_at));
            $purchase_month = date('m', strtotime($request->paid_at));

            $month_period = MonthPeriod::where('no_month', $purchase_month)->first();
            $month_period_id = $month_period ? $month_period->id : null;


            $year_period_id = null;
            $matching_year_period = YearPeriod::where(function($query) use ($purchase_date) {
                $query->where('start_date', '<=', $purchase_date)
                    ->where('end_date', '>=', $purchase_date);
            })->first();

            if ($matching_year_period) {
                $year_period_id = $matching_year_period->id;
            }

            $payment = new ReceivablesMemberPayment;
            $payment->receivables_member_id = $request->id;
            $payment->amount = $amount;
            $payment->paid_at = date('Y-m-d',strtotime($request->paid_at)) ?? date('Y-m-d');

            $payment->transaction_category_id = 4;
            $payment->month_period_id = $month_period_id;
            $payment->year_period_id = $year_period_id;

            $payment->save();

            // Update kolom `terbayar` di tabel receivables_members
            $receivablesMember->terbayar += $amount;
            $receivablesMember->save();

            //ledger
            // $lastLedgerEntry = Ledger::latest()->first();
            // $current = $lastLedgerEntry ? $lastLedgerEntry->final : 0;

            $trx_date = date('Y-m-d H:i:s',strtotime($request->paid_at));

            $debit = $amount;
            $credit = 0;
            // $final = $current + $debit - $credit;
            $request->merge([
                'type' => 'pemasukan',
                'description' => 'bayar piutang',
                'refrence' => $payment->id,
                // 'current' => $current,
                'trx_date' => $trx_date,
                'debit' => $debit,
                'credit' => $credit,
                // 'final' => $final,
                'transaction_category_id' => 4,
                'month_period_id' => $month_period_id,
                'year_period_id' => $year_period_id,
            ]);

            Ledger::store($request);

            DB::commit();

            return response()->json([
                'success'=> true,
                'message' => 'Pembayaran Piutang berhasil dibuat',
            ],200);
        } catch (\Throwable $th) {
            DB::rollBack();
            LogPretty::error($th,$request->all());
            return response()->json([
                'success'=> false,
                'message'=> 'Internal Server Error!',
            ],500);
        }
    }

}
