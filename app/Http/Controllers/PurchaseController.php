<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Product;
use App\Models\Purchase;
use App\Helpers\LogPretty;
use App\Models\Ledger;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Models\PurchaseDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class PurchaseController extends Controller
{
    protected $menu = "pembelian";

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
            $data = Purchase::with('purchase_details.product_variant.product')->
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
                return $row->purchase_details[0]->product_variant->product->name;
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
        return view('purchases.main',$data);
    }

    public function newItem(Request $request){
        $data['menu'] = 'pembelian baru';
        if(!$ledger = Ledger::where([
            'type' => 'pemasukan',
            'refrence' => 'SALDO'
        ])->first()){
            return view('errors.saldo-awal',$data);
        }
        $data['vendors'] = Vendor::all();
        $data['data'] = [];
        return view('purchases.new-item',$data);
    }

    public function storeNewItem(Request $request){
        // return $request;
        DB::beginTransaction();
        try {
            $rules = [
                'name_product' => 'required|unique:products,name',
            ];
            if($request->id){
                $rules['name_product'] = 'required';
            }
            if(!$request->is_variant){
                $rules['price'] = 'required';
                $rules['stock'] = 'required';
            }

            $attributes = [
                'name_product' => 'Nama Barang',
                'price' => 'Harga',
                'stock' => 'Stok',
            ];
            $messages = [
                'required' => 'Kolom :attribute harus terisi',
                'unique' => ':attribute sudah ada',
            ];
            $validator = Validator::make($request->all(), $rules, $messages, $attributes);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->getMessageBag()
                ],422);
            }

            $product = new Product;
            $product->name = $request->name_product;
            $product->save();


            $id_product_variant = null;#buat non variant

            if($request->is_variant){# variant
                $id_product_variant = [];#buat variant
                foreach($request->name_product_variant as $key => $name_product_variant){
                    $price = str_replace('.','',$request->price[$key]);
                    $productVariant = new ProductVariant;
                    $productVariant->product_id = $product->id;
                    $productVariant->name = $name_product_variant;
                    $productVariant->stock = $request->stock[$key];
                    $productVariant->purchase_price = $price;
                    $productVariant->save();
                    $id_product_variant[] = $productVariant->id;# variable(arr) variant
                }
            }else{# non variant
                $price = str_replace('.','',$request->price);
                $productVariant = new ProductVariant;
                $productVariant->product_id = $product->id;
                $productVariant->name = null;
                $productVariant->stock = $request->stock;
                $productVariant->purchase_price = $price;
                $productVariant->save();
                $id_product_variant = $productVariant->id;# variable(int) non variant
            }

            $total = str_replace('.','',$request->total);
            $terbayar = str_replace('.','',$request->terbayar);
            $invoice = Purchase::generateInvoice();//new item

            $purchase = new Purchase;
            $purchase->invoice = $invoice;
            $purchase->user_id = Auth::id();
            $purchase->vendor_id = $request->vendor_id;
            $purchase->total = $total;
            $purchase->terbayar = $terbayar;
            $purchase->purchase_at = $request->purchase_at;
            $purchase->save();

            if($request->is_variant){
                foreach($id_product_variant as $key => $item){# karena variable(arr) maka ada looping
                    $price = str_replace('.','',$request->price[$key]);
                    $productVariant = new PurchaseDetail;
                    $productVariant->invoice = $invoice;
                    $productVariant->product_variant_id = $item;
                    $productVariant->purchase_price = $price;
                    $productVariant->qty = $request->stock[$key];
                    $productVariant->subtotal = $request->stock[$key]*$price;
                    $productVariant->save();
                }
            }else{
                $price = str_replace('.','',$request->price);
                $productVariant = new PurchaseDetail;
                $productVariant->invoice = $invoice;
                $productVariant->product_variant_id = $id_product_variant;#variable bukan arr maka langsung pakai
                $productVariant->purchase_price = $price;
                $productVariant->qty = $request->stock;
                $productVariant->subtotal = $request->stock*$price;
                $productVariant->save();
            }

            $nominalTotalAkhir = $total;
            if($terbayar < $total){
                $nominalTotalAkhir = $terbayar;
            }

            // $lastLedgerEntry = Ledger::latest()->first();
            // $current = $lastLedgerEntry ? $lastLedgerEntry->final : 0;

            $trx_date = date('Y-m-d H:i:s',strtotime($request->purchase_at));

            $debit = 0;
            $credit = $nominalTotalAkhir;
            // $final = $current + $debit - $credit;
            $request->merge([
                'type' => 'pengeluaran',
                'description' => null,
                'refrence' => $invoice,
                // 'current' => $current,
                'trx_date' => $trx_date,
                'debit' => $debit,
                'credit' => $credit,
                // 'final' => $final,
            ]);

            Ledger::store($request);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Product stored successfully',
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

    public function restock(Request $request){
        $data['menu'] = 'penambahan stok';
        if(!$ledger = Ledger::where([
            'type' => 'pemasukan',
            'refrence' => 'SALDO'
        ])->first()){
            return view('errors.saldo-awal',$data);
        }
        $data['vendors'] = Vendor::all();
        $data['data'] = [];
        return view('purchases.restock.main',$data);
    }

    public function productVariant(Request $request){
        try {
            $data['product'] = Product::getProductById($request);
            $content = view('purchases.restock.product-variant',$data)->render();

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

    public function storeRestock(Request $request){
        if(!isset($request->products)){
            return response()->json([
                'success' => false,
                'message' => 'Product belum dipilih',
            ],422);
        }
        if(empty($request->dibayar)){
            return response()->json([
                'success' => false,
                'message' => 'Nominal dibayar belum tertulis',
            ],422);
        }
        DB::beginTransaction();
        try{
            $invoice = Purchase::generateInvoice(false);
            $total = 0;

            $purchase = new Purchase;
            $purchase->invoice = $invoice;
            $purchase->user_id = Auth::getUser()->id;
            $purchase->vendor_id = $request->vendor_id;
            $purchase->total = 0;
            $purchase->terbayar = 0;
            $purchase->purchase_at = $request->purchase_at;
            $purchase->save();

            foreach($request->products as $product){
                $purchaseDetail = new PurchaseDetail();
                $purchaseDetail->invoice = $invoice;
                $purchaseDetail->product_variant_id = $product['product_variant_id'];
                $purchaseDetail->purchase_price = (int)$product['price'];
                $purchaseDetail->qty = $product['qty'];
                $purchaseDetail->subtotal = (int)$product['qty']*(int)$product['price'];
                $purchaseDetail->save();

                //update stok
                $productVariant = ProductVariant::find($product['product_variant_id']);
                $productVariant->stock = (int)$productVariant->stock+(int)$product['qty'];
                $productVariant->purchase_price = (int)$product['price'];
                $productVariant->save();


                $total += $purchaseDetail->subtotal;
            }

            $terbayar = str_replace('.','',$request->dibayar);

            $purchase->total = (int)$total;
            $purchase->terbayar = (int)str_replace('.','',$request->dibayar);
            $purchase->save();

            $nominalTotalAkhir = $total;
            if($terbayar < $total){
                $nominalTotalAkhir = $terbayar;
            }

            // $lastLedgerEntry = Ledger::latest()->first();
            // $current = $lastLedgerEntry ? $lastLedgerEntry->final : 0;

            $trx_date = date('Y-m-d H:i:s',strtotime($request->purchase_at));

            $debit = 0;
            $credit = $nominalTotalAkhir;
            // $final = $current + $debit - $credit;
            $request->merge([
                'type' => 'pengeluaran',
                'description' => null,
                'refrence' => $invoice,
                // 'current' => $current,
                'trx_date' => $trx_date,
                'debit' => $debit,
                'credit' => $credit,
                // 'final' => $final,
            ]);

            Ledger::store($request);

            DB::commit();

            return response()->json([
                'success'=> true,
                'message' => 'Penambahan Stok berhasil dibuat',
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
