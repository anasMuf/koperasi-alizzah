<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Ledger;
use App\Helpers\LogPretty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    protected $menu = "penjualan";

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
            $data = Order::with(['student','order_details.product_variant.product'])->
            when($dateRange, function($q) use ($dateRange){
                $q->whereBetween('created_at',$dateRange);
            })
            ->get();
            return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('tgl', function($row){
                return Carbon::parse($row->created_at)->isoFormat('DD MMMM YYYY');
            })
            // ->addColumn('product', function($row){
            //     return $row->order_details[0]->product_variant->product->name;
            // })
            ->addColumn('total_', function($row){
                $nominalAkhir = $row->total;
                if($row->terbayar < $row->total){
                    $nominalAkhir = $row->total - $row->terbayar;
                }
                return 'Rp '.number_format($nominalAkhir,0,',','.');
            })
            ->addColumn('action', function($row){
                $btn = '';
                return $btn;
            })
            ->rawColumns([
                'tgl',
                // 'product',
                'total_',
                'action'
            ])
            ->make(true);
        }
        return view('orders.main',$data);
    }

    public function addSaldo(Request $request){
        $data['menu'] = 'saldo awal';
        $data['saldo_awal'] = Ledger::orderBy('id','asc')->first();
        return view('saldo-awal.main',$data);
    }

    public function storeSaldo(Request $request){
        try {
            $saldo_awal = str_replace('.','',$request->saldo_awal);
            $request->merge([
                'type' => 'pemasukan',
                'description' => null,
                'refrence' => 'SALDO',
                'current' => 0,
                'debit' => $saldo_awal,
                'credit' => 0,
                'final' => $saldo_awal,
            ]);

            Ledger::store($request);

            DB::commit();

            return response()->json([
                'success'=> true,
                'message' => 'Penambahan saldo awal berhasil disimpan',
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
