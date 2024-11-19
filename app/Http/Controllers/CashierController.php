<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Ledger;
use App\Models\Product;
use App\Helpers\LogPretty;
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

            $order = new Order;
            $order->invoice = $invoice;
            $order->student_id = $request->student_id;
            $order->user_id = Auth::getUser()->id;
            $order->total = 0;
            $order->terbayar = 0;
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

            $lastLedgerEntry = Ledger::latest()->first();
            $current = $lastLedgerEntry ? $lastLedgerEntry->final : 0;
            $debit = $nominalTotalAkhir;
            $credit = 0;
            $final = $current + $debit - $credit;
            $request->merge([
                'type' => 'pemasukan',
                'description' => null,
                'refrence' => $invoice,
                'current' => $current,
                'debit' => $debit,
                'credit' => $credit,
                'final' => $final,
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
