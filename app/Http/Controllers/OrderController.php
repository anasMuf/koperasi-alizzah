<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Ledger;
use App\Helpers\LogPretty;
use App\Models\YearPeriod;
use App\Models\MonthPeriod;
use App\Models\OrderDetail;
use App\Models\OrderPayment;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Models\SIAKAD\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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
                $btn = '
                <a href="'.route('order.edit',['invoice' => $row->invoice]).'" class="btn btn-info btn-sm"><i class="fa fa-eye"></i></a>
                ';
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

    public function edit(Request $request){
        $students = Student::all();
        $order = Order::with(['order_details.product_variant.product'])->where('invoice',$request->invoice)->first();
        $dataProduct = [];
        foreach($order->order_details as $key => $order_detail){
            $dataProduct[$key]['product_name'] = $order_detail->product_variant->product->name;
            $dataProduct[$key]['product_variant_id'] = (string)$order_detail->product_variant_id;
            $dataProduct[$key]['price'] = (int)$order_detail->product_variant->price;
            $dataProduct[$key]['product_variant_name'] = $order_detail->product_variant->name;
            $dataProduct[$key]['qty'] = $order_detail->qty;
        }
        $data['menu'] = 'edit penjualan';
        $data['students'] = $students;
        $data['data'] = $order;
        $data['dataProduct'] = $dataProduct;

        $data['from'] = null;
        if(isset($request->from) && $request->from === 'jurnal'){
            $data['from'] = 'jurnal';
        }
        return view('orders.edits.main',$data);
    }

    public function update(Request $request){
        if(!isset($request->products)){
            return response()->json([
                'success' => false,
                'message' => 'Product belum dipilih',
            ],422);
        }
        if($request->dibayar == ''){
            return response()->json([
                'success' => false,
                'message' => 'Nominal dibayar belum tertulis',
            ],422);
        }
        DB::beginTransaction();
        try {
            $invoice = $request->invoice;
            $total = 0;

            $purchase_date = date('Y-m-d', strtotime($request->order_at));
            $purchase_month = date('m', strtotime($request->order_at));

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

            $order = Order::where('invoice',$invoice)->first();
            $order->invoice = $invoice;
            $order->student_id = $request->student_id;
            $order->user_id = Auth::getUser()->id;
            $order->total = 0;
            $order->terbayar = 0;
            $order->order_at = date('Y-m-d',strtotime($request->order_at)).' '.date('H:i:s');

            $order->transaction_category_id = 2;
            $order->month_period_id = $month_period_id;
            $order->year_period_id = $year_period_id;

            $order->save();

            foreach($request->products as $product){
                $productVariant = ProductVariant::find($product['product_variant_id']);
                if((int)$productVariant->stock < (int)$product['qty']){
                    DB::rollBack();
                    $productName = ($product['product_variant_name']) ? $product['product_name'].'('.$productVariant->name.')' : $product['product_name'];
                    return response()->json([
                        'success' => false,
                        'message' => 'Stok '.$productName.' tidak mencukupi',
                    ],422);
                }

                $orderDetail = OrderDetail::where([
                    'invoice' => $invoice,
                    'product_variant_id' => $product['product_variant_id']
                ])->first();


                // mengembalikan stok
                $productVariant->stock = (int)$productVariant->stock+(int)$orderDetail->qty;
                $productVariant->save();

                // new stok
                $orderDetail->invoice = $invoice;
                $orderDetail->product_variant_id = $product['product_variant_id'];
                $orderDetail->qty = $product['qty'];
                $orderDetail->subtotal = (int)$product['qty']*(int)$product['price'];
                $orderDetail->save();

                //update stok
                $productVariant->stock = (int)$productVariant->stock-(int)$product['qty'];
                $productVariant->save();


                $total += $orderDetail->subtotal;
            }

            $terbayar = str_replace('.','',$request->dibayar);

            $order->total = (int)$total;
            $order->terbayar = (int)$terbayar;
            $order->save();


            $nominalTotalAkhir = $total;
            if($terbayar < $total){
                $nominalTotalAkhir = $terbayar;
            }

            // $lastLedgerEntry = Ledger::latest()->first();
            // $current = $lastLedgerEntry ? $lastLedgerEntry->final : 0;

            $ledger = Ledger::where('refrence',$invoice)->first();

            $trx_date = date('Y-m-d',strtotime($request->order_at)).' '.date('H:i:s');

            $debit = $nominalTotalAkhir;
            $credit = 0;
            // $final = $current + $debit - $credit;
            $request->merge([
                'id_ledger' => $ledger->id,
                'type' => 'pemasukan',
                'description' => null,
                'refrence' => $invoice,
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
                'message' => 'Order berhasil diubah',
            ],200);
        } catch (\Throwable $th) {
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
            $orderDetails = OrderDetail::where('invoice', $request->invoice)->get();
            foreach ($orderDetails as $detail) {
                $productVariant = ProductVariant::find($detail->product_variant_id);

                if($productVariant){
                    // Ubah stok ProductVariant
                    $productVariant->stock += $detail->qty;
                    $productVariant->save();
                }

                // Hapus OrderDetail
                $detail->delete();
            }

            $order = Order::where('invoice', $request->invoice)->first();
            if ($order) {
                // Hapus OrderPayment
                OrderPayment::where('order_id', $order->id)->delete();

                // Hapus Order
                $order->delete();
            }

            Ledger::where('refrence',$request->invoice)->delete();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Order deleted successfully',
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
