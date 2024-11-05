<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use Illuminate\Http\Request;
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
            $data = Order::with('order_details.product_variant.product')->
            when($dateRange, function($q) use ($dateRange){
                $q->whereBetween('created_at',$dateRange);
            })
            ->get();
            return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('tgl', function($row){
                return Carbon::parse($row->created_at)->isoFormat('DD MMMM YYYY');
            })
            ->addColumn('product', function($row){
                return $row->order_details[0]->product_variant->product->name;
            })
            ->addColumn('total_', function($row){
                return 'Rp '.number_format($row->total,0,',','.');
            })
            ->addColumn('action', function($row){
                $btn = '';
                return $btn;
            })
            ->rawColumns([
                'tgl',
                'product',
                'total_',
                'action'
            ])
            ->make(true);
        }
        return view('orders.main',$data);
    }
}
