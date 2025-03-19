<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Ledger;
use App\Models\Product;
use App\Helpers\LogPretty;
use App\Models\YearPeriod;
use App\Models\MonthPeriod;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Models\SIAKAD\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CashierController extends Controller
{
    protected $menu = "kasir";

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){
        $data['menu'] = $this->menu;
        $data['students'] = Student::all();
        return view('cashiers.main',$data);
    }

    public function getProduct(Request $request){
        try {
            $products = Product::getProduct($request);
            return response()->json([
                'success' => true,
                'data' => $products
            ],200);
        } catch (\Throwable $th) {
            LogPretty::error($th);
            return response()->json([
                'success'=> false,
                'message'=> 'Internal Server Error!',
            ],500);
        }
    }

    public function productVariant(Request $request){
        try {
            $data['product'] = Product::getProductById($request);
            $content = view('cashiers.product-variant',$data)->render();

            return response()->json([
                'success' => true,
                'content' => $content
            ],200);
        } catch (\Throwable $th) {
            LogPretty::error($th);
            return response()->json([
                'success'=> false,
                'message'=> 'Internal Server Error!',
            ],500);
        }
    }

    public function store(Request $request){
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
            $invoice = Order::generateInvoice();
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

            $order = new Order;
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

                $orderDetail = new OrderDetail;
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

            $trx_date = date('Y-m-d',strtotime($request->order_at)).' '.date('H:i:s');

            $debit = $nominalTotalAkhir;
            $credit = 0;
            // $final = $current + $debit - $credit;
            $request->merge([
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
                'message' => 'Order berhasil dibuat',
            ],200);
        } catch (\Throwable $th) {
            LogPretty::error($th);
            return response()->json([
                'success'=> false,
                'message'=> 'Internal Server Error!',
            ],500);
        }
    }
}
