<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Ledger;
use App\Helpers\LogPretty;
use App\Models\YearPeriod;
use App\Models\MonthPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TransactionCategory;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    private $menu = 'transaksi umum';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){
        $data['menu'] = $this->menu;
        if($request->ajax()){
            $dateRange = [];
            if(isset($request->tanggal) && $request->tanggal != ''){
                $dates = explode(' - ',$request->tanggal);
                $time = ' 00:00:00';
                foreach($dates as $key => $date){
                    if($key === 1){
                        $time = ' 23:59:59';
                    }
                    $modifyDate = str_replace('/','-',$date);
                    $dateRange[] = date('Y-m-d H:i:s',strtotime($modifyDate.$time));
                }
            }

            $data = Ledger::
            when(!empty($request->type_transaksi), function($q) use ($request){
                $q->where('type',$request->type_transaksi);
            })->
            when($dateRange, function($q) use ($dateRange){
                $q->whereBetween('trx_date',$dateRange);
            })->
            where('refrence','transaksi umum')->
            orderBy('trx_date','asc')->
            get();

            return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('trx_date_', function($row){
                return Carbon::parse($row->trx_date)->isoFormat('DD MMMM YYYY');
            })
            ->addColumn('nominal', function($row){
                $nominal = 0;
                if($row->type == 'pemasukan'){
                    $nominal = number_format($row->debit,0,',','.');
                }elseif($row->type == 'pengeluaran'){
                    $nominal = '('.number_format($row->credit,0,',','.').')';
                }
                return $nominal;
            })
            ->addColumn('action', function($row){
                $btn = '
                <button type="button" class="btn btn-warning btn-xs" onclick="openData('.$row->id.')" title="Edit"><i class="fa fa-edit"></i></button>
                <button type="button" class="btn btn-danger btn-xs" onclick="deleteData('.$row->id.')"><i class="fa fa-trash"></i></button>
                ';
                return $btn;
            })
            ->rawColumns([
                'nominal',
                'action'
            ])
            ->make(true);
        }

        return view('transactions.main',$data);
    }

    public function form(Request $request){
        $data['transaction_categories'] = TransactionCategory::orderBy('type')->get();
        $data['data'] = Ledger::find($request->id);
        $content = view('transactions.form',$data)->render();

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
                'trx_date' => 'nullable|date',
                'type' => 'required',
                'description' => 'nullable|string',
            ];

            $attributes = [
                'amount' => 'Nominal Dibayar',
                'trx_date' => 'Tanggal Bayar',
                'type' => 'Tipe Transaksi',
                'description' => 'Deskripsi',
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

            $amount = str_replace('.','',$request->amount);
            $trx_date = date('Y-m-d H:i:s',strtotime($request->trx_date));

            $purchase_date = date('Y-m-d', strtotime($request->trx_date));
            $purchase_month = date('m', strtotime($request->trx_date));

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

            //ledger
            if($request->id){
                $request->merge([
                    'id_ledger' => $request->id,
                ]);
            //     $lastLedgerEntry = Ledger::find($request->id);
            }
            // else{
            //     $lastLedgerEntry = Ledger::latest()->first();
            // }
            // $current = $lastLedgerEntry ? $lastLedgerEntry->final : 0;
            $debit = $request->type == 'pemasukan' ? $amount : 0;
            $credit = $request->type == 'pengeluaran' ? $amount : 0;
            // $final = $current + $debit - $credit;
            $request->merge([
                'refrence' => 'transaksi umum',
                // 'current' => $current,
                'trx_date' => $trx_date,
                'debit' => $debit,
                'credit' => $credit,
                // 'final' => $final,
                'transaction_category_id' => $request->transaction_category_id,
                'month_period_id' => $month_period_id,
                'year_period_id' => $year_period_id,
            ]);

            Ledger::store($request);

            //recalculate jika edit
            // if($request->id){
            //     $previousFinal = $final;
            //     $ledgersLatestEdit = Ledger::where('created_at','>', $lastLedgerEntry->created_at)->get();
            //     foreach($ledgersLatestEdit as $ledger){
            //         $currentNew = $previousFinal;
            //         $finalNew = $currentNew + $ledger->debit - $ledger->credit;

            //         // Update record
            //         Ledger::where('id', $ledger->id)
            //             ->update([
            //                 'current' => $currentNew,
            //                 'final' => $finalNew,
            //             ]);

            //         $previousFinal = $finalNew;
            //     }
            // }

            DB::commit();

            return response()->json([
                'success'=> true,
                'message' => 'Transaksi Umum berhasil dibuat',
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

    public function delete(Request $request){
        DB::beginTransaction();
        try{
            $ledger = Ledger::findOrFail($request->id);
            $ledger->delete();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Transaction deleted successfully',
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


    public function export(Request $request){
        $dateRange = [];
        if(isset($request->tanggal) && $request->tanggal != ''){
            $dates = explode(' - ',$request->tanggal);
            $time = ' 00:00:00';
            foreach($dates as $key => $date){
                if($key === 1){
                    $time = ' 23:59:59';
                }
                $modifyDate = str_replace('/','-',$date);
                $dateRange[] = date('Y-m-d H:i:s',strtotime($modifyDate.$time));
            }
        }

        $data['data'] = Ledger::
        when($dateRange, function($q) use ($dateRange){
            $q->whereBetween('trx_date',$dateRange);
        })->
        when(!empty($request->type_transaksi), function($q) use ($request){
            $q->where('type',$request->type_transaksi);
        })->
        where('refrence','transaksi umum')->
        orderBy('trx_date','asc')->
        get();

        $data['periode'] = $request->tanggal;

        $namaFile = 'data Transaksi periode'.$request->tanggal;

        if($request->export == 'excel'){
            return;// Excel::download(new Export($data),$namaFile);
        }elseif($request->export == 'print'){
            $data['title'] = $namaFile;
            return view('transactions.print',$data);
        }else{
            return response()->json("Invalid Request",404);
        }
    }
}
