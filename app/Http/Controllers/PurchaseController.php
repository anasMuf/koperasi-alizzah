<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Ledger;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Category;
use App\Models\Purchase;
use App\Helpers\LogPretty;
use App\Models\YearPeriod;
use App\Models\MonthPeriod;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Models\PurchaseDetail;
use App\Models\PurchasePayment;
use Illuminate\Support\Facades\DB;
use App\Models\TransactionCategory;
use Illuminate\Support\Facades\Auth;
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
                $btn = '<a href="'.route('purchase.edit',['invoice'=>$row->invoice]).'" class="btn btn-info btn-sm"><i class="fa fa-eye"></i></a>';
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
        // $data['menu'] = 'pembelian baru';
        // if(!$ledger = Ledger::where([
        //     'type' => 'pemasukan',
        //     'refrence' => 'SALDO'
        // ])->first()){
        //     return view('errors.saldo-awal',$data);
        // }
        // $data['categories'] = Category::all();
        // $data['transaction_categories'] = TransactionCategory::where('type','pengeluaran')->get();
        // $data['vendors'] = Vendor::all();
        // $data['data'] = [];
        // return view('purchases.new-item',$data);
    }

    public function storeNewItem(Request $request){
        // // return $request;
        // DB::beginTransaction();
        // try {
        //     $rules = [
        //         'name_product' => 'required|unique:products,name',
        //         'transaction_category_id' => 'required',
        //         'category_id' => 'required'
        //     ];
        //     if($request->id){
        //         $rules['name_product'] = 'required';
        //     }
        //     if(!$request->is_variant){
        //         $rules['price'] = 'required';
        //         $rules['stock'] = 'required';
        //     }

        //     $attributes = [
        //         'name_product' => 'Nama Barang',
        //         'price' => 'Harga',
        //         'stock' => 'Stok',
        //         'transaction_category_id' => 'Kategori Transaksi',
        //         'category_id' => 'Kategori Barang',
        //     ];
        //     $messages = [
        //         'required' => 'Kolom :attribute harus terisi',
        //         'unique' => ':attribute sudah ada',
        //     ];
        //     $validator = Validator::make($request->all(), $rules, $messages, $attributes);

        //     if ($validator->fails()) {
        //         return response()->json([
        //             'success' => false,
        //             'message' => 'Validation failed',
        //             'errors' => $validator->getMessageBag()
        //         ],422);
        //     }

        //     $product = new Product;
        //     $product->name = $request->name_product;
        //     $product->category_id = $request->category_id;
        //     $product->save();


        //     $id_product_variant = null;#buat non variant

        //     if($request->is_variant){# variant
        //         $id_product_variant = [];#buat variant
        //         foreach($request->name_product_variant as $key => $name_product_variant){
        //             $price = str_replace('.','',$request->price[$key]);
        //             $productVariant = new ProductVariant;
        //             $productVariant->product_id = $product->id;
        //             $productVariant->name = $name_product_variant;
        //             $productVariant->stock = $request->stock[$key];
        //             $productVariant->purchase_price = $price;
        //             $productVariant->save();
        //             $id_product_variant[] = $productVariant->id;# variable(arr) variant
        //         }
        //     }else{# non variant
        //         $price = str_replace('.','',$request->price);
        //         $productVariant = new ProductVariant;
        //         $productVariant->product_id = $product->id;
        //         $productVariant->name = null;
        //         $productVariant->stock = $request->stock;
        //         $productVariant->purchase_price = $price;
        //         $productVariant->save();
        //         $id_product_variant = $productVariant->id;# variable(int) non variant
        //     }

        //     $total = str_replace('.','',$request->total);
        //     $terbayar = str_replace('.','',$request->terbayar);
        //     $invoice = Purchase::generateInvoice();//new item


        //     $purchase_date = date('Y-m-d', strtotime($request->purchase_at));
        //     $purchase_month = date('m', strtotime($request->purchase_at));

        //     $month_period = MonthPeriod::where('no_month', $purchase_month)->first();
        //     $month_period_id = $month_period ? $month_period->id : null;


        //     $year_period_id = null;
        //     $matching_year_period = YearPeriod::where(function($query) use ($purchase_date) {
        //         $query->where('start_date', '<=', $purchase_date)
        //             ->where('end_date', '>=', $purchase_date);
        //     })->first();

        //     if ($matching_year_period) {
        //         $year_period_id = $matching_year_period->id;
        //     }

        //     $purchase = new Purchase;
        //     $purchase->invoice = $invoice;
        //     $purchase->user_id = Auth::id();
        //     $purchase->vendor_id = $request->vendor_id;
        //     $purchase->total = $total;
        //     $purchase->terbayar = $terbayar;
        //     $purchase->purchase_at = date('Y-m-d',strtotime($request->purchase_at)).' '.date('H:i:s');

        //     $purchase->transaction_category_id = $request->transaction_category_id;
        //     $purchase->month_period_id = $month_period_id;
        //     $purchase->year_period_id = $year_period_id;

        //     $purchase->save();

        //     if($request->is_variant){
        //         foreach($id_product_variant as $key => $item){# karena variable(arr) maka ada looping
        //             $price = str_replace('.','',$request->price[$key]);
        //             $productVariant = new PurchaseDetail;
        //             $productVariant->invoice = $invoice;
        //             $productVariant->product_variant_id = $item;
        //             $productVariant->purchase_price = $price;
        //             $productVariant->qty = $request->stock[$key];
        //             $productVariant->subtotal = $request->stock[$key]*$price;
        //             $productVariant->save();
        //         }
        //     }else{
        //         $price = str_replace('.','',$request->price);
        //         $productVariant = new PurchaseDetail;
        //         $productVariant->invoice = $invoice;
        //         $productVariant->product_variant_id = $id_product_variant;#variable bukan arr maka langsung pakai
        //         $productVariant->purchase_price = $price;
        //         $productVariant->qty = $request->stock;
        //         $productVariant->subtotal = $request->stock*$price;
        //         $productVariant->save();
        //     }

        //     $nominalTotalAkhir = $total;
        //     if($terbayar < $total){
        //         $nominalTotalAkhir = $terbayar;
        //     }

        //     // $lastLedgerEntry = Ledger::latest()->first();
        //     // $current = $lastLedgerEntry ? $lastLedgerEntry->final : 0;

        //     $trx_date = date('Y-m-d',strtotime($request->purchase_at)).' '.date('H:i:s');

        //     $debit = 0;
        //     $credit = $nominalTotalAkhir;
        //     // $final = $current + $debit - $credit;
        //     $request->merge([
        //         'type' => 'pengeluaran',
        //         'description' => null,
        //         'refrence' => $invoice,
        //         // 'current' => $current,
        //         'trx_date' => $trx_date,
        //         'debit' => $debit,
        //         'credit' => $credit,
        //         // 'final' => $final,
        //         'transaction_category_id' => $request->transaction_category_id,
        //         'month_period_id' => $month_period_id,
        //         'year_period_id' => $year_period_id,
        //     ]);

        //     Ledger::store($request);

        //     DB::commit();
        //     return response()->json([
        //         'success' => true,
        //         'message' => 'Product stored successfully',
        //     ],200);
        // } catch (\Throwable $th) {
        //     DB::rollBack();
        //     LogPretty::error($th);
        //     return response()->json([
        //         'success'=> false,
        //         'message'=> 'Internal Server Error!',
        //     ],500);
        // }
    }

    public function edit(Request $request){
        $vendors = Vendor::all();
        $purchase = Purchase::with(['purchase_details.product_variant.product'])->where('invoice',$request->invoice)->first();
        $dataProduct = [];
        foreach($purchase->purchase_details as $key => $purchase_detail){
            $dataProduct[$key]['product_name'] = $purchase_detail->product_variant->product->name;
            $dataProduct[$key]['product_variant_id'] = (string)$purchase_detail->product_variant_id;
            $dataProduct[$key]['price'] = (int)$purchase_detail->product_variant->price;
            $dataProduct[$key]['product_variant_name'] = $purchase_detail->product_variant->name;
            $dataProduct[$key]['qty'] = $purchase_detail->qty;
        }
        $data['categories'] = Category::all();
        $data['transaction_categories'] = TransactionCategory::where('type','pengeluaran')->get();
        $data['menu'] = 'edit pembelian';
        $data['vendors'] = $vendors;
        $data['data'] = $purchase;
        $data['dataProduct'] = $dataProduct;
        $data['is_variant'] = count($purchase->purchase_details) > 1 ? true : false;

        $data['from'] = null;
        if(isset($request->from) && $request->from === 'jurnal'){
            $data['from'] = 'jurnal';
        }
        if(ctype_alpha(substr($request->invoice, 0, 1))){
            $prefix = substr($request->invoice, 0, 2);
            if($prefix === 'PN'){
                return view('purchases.edit-item',$data);
            }elseif($prefix === 'PS'){
                return view('purchases.restock.edit-restock',$data);
            }
        }
    }


    public function update(Request $request){
        // return $request;
        DB::beginTransaction();
        try {
            $rules = [
                'name_product' => 'required',
                'transaction_category_id' => 'required',
                'category_id' => 'required'
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
                'transaction_category_id' => 'Kategori Transaksi',
                'category_id' => 'Kategori Barang',
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

            $product = Product::find($request->product_id);
            // $product->name = $request->name_product;
            $product->category_id = $request->category_id;
            $product->save();


            $id_product_variant = null;#buat non variant

            if($request->is_variant){# variant
                $id_product_variants = [];#buat variant
                foreach($request->name_product_variant as $key => $name_product_variant){
                    $price = str_replace('.','',$request->price[$key]);
                    $productVariant = ProductVariant::find($request->product_variant_id[$key]);
                    // $productVariant->name = $name_product_variant;
                    // $productVariant->stock = $request->stock[$key];
                    // $productVariant->purchase_price = $price;
                    // $productVariant->save();
                    $id_product_variants[] = $productVariant->id;# variable(arr) variant
                }
            }else{# non variant
                $price = str_replace('.','',$request->price);
                $productVariant = ProductVariant::where('product_id',$product->id)->first();
                // $productVariant->stock = $request->stock;
                // $productVariant->purchase_price = $price;
                // $productVariant->save();
                $id_product_variant = $productVariant->id;# variable(int) non variant
            }

            $total = str_replace('.','',$request->total);
            $terbayar = str_replace('.','',$request->terbayar);


            $purchase_date = date('Y-m-d', strtotime($request->purchase_at));
            $purchase_month = date('m', strtotime($request->purchase_at));

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


            $purchase = Purchase::where('invoice',$request->invoice)->first();
            $purchase->user_id = Auth::id();
            $purchase->vendor_id = $request->vendor_id;
            $purchase->total = $total;
            $purchase->terbayar = $terbayar;
            $purchase->purchase_at = date('Y-m-d',strtotime($request->purchase_at)).' '.date('H:i:s');

            $purchase->transaction_category_id = $request->transaction_category_id;
            $purchase->month_period_id = $month_period_id;
            $purchase->year_period_id = $year_period_id;

            $purchase->save();

            if($request->is_variant){
                foreach($id_product_variants as $key => $item){# karena variable(arr) maka ada looping
                    $price = str_replace('.','',$request->price[$key]);
                    $productVariant = PurchaseDetail::find($request->purchase_detail_id[$key]);
                    $productVariant->product_variant_id = $item;
                    $productVariant->purchase_price = $price;
                    $productVariant->qty = $request->stock[$key];
                    $productVariant->subtotal = $request->stock[$key]*$price;
                    $productVariant->save();
                }
            }else{
                $price = str_replace('.','',$request->price);
                $productVariant = PurchaseDetail::where('invoice',$request->invoice)->first();
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

            $ledger = Ledger::where('refrence',$request->invoice)->first();

            $trx_date = date('Y-m-d',strtotime($request->purchase_at)).' '.date('H:i:s');

            $debit = 0;
            $credit = $nominalTotalAkhir;
            // $final = $current + $debit - $credit;
            $request->merge([
                'id_ledger' => $ledger->id,
                'type' => 'pengeluaran',
                'description' => null,
                'refrence' => $request->invoice,
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

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Purchase updated successfully',
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

    public function restock(Request $request){
        $data['menu'] = 'pembelian barang';
        if(!$ledger = Ledger::where([
            'type' => 'pemasukan',
            'refrence' => 'SALDO'
        ])->first()){
            return view('errors.saldo-awal',$data);
        }
        $data['transaction_categories'] = TransactionCategory::where('type','pengeluaran')->get();
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

            $purchase_date = date('Y-m-d', strtotime($request->purchase_at));
            $purchase_month = date('m', strtotime($request->purchase_at));

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



            $purchase = new Purchase;
            $purchase->invoice = $invoice;
            $purchase->user_id = Auth::getUser()->id;
            $purchase->vendor_id = $request->vendor_id;
            $purchase->total = 0;
            $purchase->terbayar = 0;
            $purchase->purchase_at = date('Y-m-d',strtotime($request->purchase_at)).' '.date('H:i:s');

            $purchase->transaction_category_id = $request->transaction_category_id;
            $purchase->month_period_id = $month_period_id;
            $purchase->year_period_id = $year_period_id;

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
                'transaction_category_id' => $request->transaction_category_id,
                'month_period_id' => $month_period_id,
                'year_period_id' => $year_period_id,
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

    public function delete(Request $request){
        DB::beginTransaction();
        try{
            $product = Product::findOrFail($request->id);
            $productVariants = ProductVariant::where('product_id', $request->id)->get();

            foreach ($productVariants as $variant) {
                $purchaseDetails = PurchaseDetail::where('product_variant_id', $variant->id)->get();
                foreach ($purchaseDetails as $detail) {
                    $purchase = Purchase::where('invoice', $detail->invoice)->first();
                    if ($purchase) {
                        Ledger::where('refrence', $detail->invoice)->delete();
                        // Hapus PurchasePayment
                        PurchasePayment::where('purchase_id', $purchase->id)->delete();

                        // Hapus Purchase
                        $purchase->delete();
                    }

                    // Hapus PurchaseDetail
                    $detail->delete();
                }


                // Hapus ProductVariant
                $variant->delete();
            }

            // Hapus Product
            $product->delete();


            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Purchase deleted successfully',
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
