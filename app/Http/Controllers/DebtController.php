<?php

namespace App\Http\Controllers;

use App\Models\Ledger;
use App\Models\Vendor;
use App\Models\Purchase;
use App\Helpers\LogPretty;
use Illuminate\Http\Request;
use App\Models\PurchasePayment;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class DebtController extends Controller
{
    private $menu = 'hutang';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){
        $data['menu'] = $this->menu;
        if($request->ajax()){
            $data = Purchase::with(['vendor'])->whereColumn('terbayar', '<', 'total')->get();

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
        // $data['notifHutang'] = count($data);
        return view('debts.main',$data);
    }

    public function form(Request $request){
        $data['data'] = Purchase::with('vendor','purchase_details.product_variant.product')->find($request->id);
        $content = view('debts.form',$data)->render();

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

            $purchase = Purchase::findOrFail($request->id);

            // Cek jika sudah lunas
            $remainingDebt = $purchase->total - $purchase->terbayar;
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

            // Catat pembayaran di tabel purchase_payments

            $payment = new PurchasePayment;
            $payment->purchase_id = $request->id;
            $payment->amount = $amount;
            $payment->paid_at = date('Y-m-d',strtotime($request->paid_at)) ?? now();
            $payment->save();

            // Update kolom `terbayar` di tabel purchases
            $purchase->terbayar += $amount;
            $purchase->save();

            //ledger
            $lastLedgerEntry = Ledger::latest()->first();
            $current = $lastLedgerEntry ? $lastLedgerEntry->final : 0;
            $debit = 0;
            $credit = $amount;
            $final = $current + $debit - $credit;
            $request->merge([
                'type' => 'pengeluaran',
                'description' => 'bayar hutang',
                'refrence' => $payment->id,
                'current' => $current,
                'debit' => $debit,
                'credit' => $credit,
                'final' => $final,
            ]);

            Ledger::store($request);

            DB::commit();

            return response()->json([
                'success'=> true,
                'message' => 'Pembayaran Hutang berhasil dibuat',
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

}
