<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Ledger;
use App\Helpers\LogPretty;
use App\Models\ViewLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SaldoController extends Controller
{
    protected $menu = "saldo";

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){
        $data['menu'] = $this->menu;
        if($request->ajax()){
            $dateRange = [];
            if(isset($request->dates) && $request->dates != ''){
                $dates = explode(' - ',$request->dates);
                foreach($dates as &$date){
                    $dateRange[] = date('Y-m-d',strtotime($date));
                }
            }
            $data = ViewLedger::where('refrence','SALDO')->
            when($dateRange, function($q) use ($dateRange){
                $q->whereBetween('trx_date',$dateRange);
            })
            ->get();
            return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('tgl', function($row){
                return Carbon::parse($row->trx_date)->isoFormat('DD MMMM YYYY');
            })
            ->addColumn('saldo_awal', function($row){
                $nominalAkhir = $row->current;
                return 'Rp '.number_format($nominalAkhir,0,',','.');
            })
            ->addColumn('tambah_saldo', function($row){
                $nominalAkhir = $row->debit;
                return 'Rp '.number_format($nominalAkhir,0,',','.');
            })
            ->addColumn('saldo_akhir', function($row){
                $nominalAkhir = $row->final;
                return 'Rp '.number_format($nominalAkhir,0,',','.');
            })
            ->addColumn('action', function($row){
                $btn = '';
                return $btn;
            })
            ->rawColumns([
                'tgl',
                'saldo_awal',
                'tambah_saldo',
                'saldo_akhir',
                'action'
            ])
            ->make(true);
        }
        return view('saldo.main',$data);
    }

    public function form(Request $request){
        $data['menu'] = 'saldo';
        $data['data'] = [];
        $content = view('saldo.form',$data)->render();
        return response()->json(['message' => 'oke', 'content' => $content],200);
    }

    public function store(Request $request){
        try {
            $trx_date = date('Y-m-d',strtotime($request->trx_date)).' '.date('H:i:s');
            // $lastLedgerEntry = Ledger::latest()->first();
            // $current = $lastLedgerEntry ? $lastLedgerEntry->final : 0;
            $tambah_saldo = str_replace('.','',$request->tambah_saldo);
            $debit = $tambah_saldo;
            $credit = 0;
            $request->merge([
                'type' => 'pemasukan',
                'description' => null,
                'refrence' => 'SALDO',
                // 'current' => $current,
                'trx_date' => $trx_date,
                'debit' => $debit,
                'credit' => $credit,
                // 'final' => $current + $debit - $credit,
            ]);

            Ledger::store($request);

            DB::commit();

            return response()->json([
                'success'=> true,
                'message' => 'Penambahan saldo berhasil disimpan',
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
