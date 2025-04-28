<?php

namespace App\Http\Controllers;

use App\Models\Ledger;
use App\Models\Product;
use App\Models\Category;
use App\Models\Purchase;
use App\Helpers\LogPretty;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Models\PurchaseDetail;
use App\Models\PurchasePayment;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    protected $menu = "barang";

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
            $data = Product::with('product_variants')
            ->when($dateRange, function($q) use ($dateRange){
                $q->whereBetween('created_at',$dateRange);
            })
            ->get();
            return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('variant', function($row){
                $variant = '';
                $name = [];
                foreach($row->product_variants as $product_variant){
                    $name[] = $product_variant->name;
                }
                $variant = implode(', ',$name);
                return $variant;
            })
            ->addColumn('stock', function($row){
                $stock = 0;
                foreach($row->product_variants as $key => $product_variant){
                    $stock += $product_variant->stock;
                }
                return number_format($stock,0,',','.');
            })
            ->addColumn('price', function($row){
                $price = '';
                $arrPrice = [];
                foreach($row->product_variants as $key => $product_variant){
                    if($key == 0 || $key+1 == count($row->product_variants)){
                        $arrPrice[] = 'Rp '.number_format($product_variant->price,0,',','.');
                    }
                }
                $arrPrice = array_unique($arrPrice);
                sort($arrPrice);
                $price = implode(' - ',$arrPrice);
                return $price;
            })
            ->addColumn('action', function($row){
                $btn = '
                <button type="button" class="btn btn-warning btn-sm" onclick="openData('.$row->id.')" title="edit"><i class="fa fa-edit"></i></button>
                <button type="button" class="btn btn-danger btn-sm" onclick="deleteData('.$row->id.')" title="hapus"><i class="fa fa-trash"></i></button>
                ';
                return $btn;
            })
            ->rawColumns([
                'variant',
                'stock',
                'price',
                'action'
            ])
            ->make(true);
        }
        return view('products.main',$data);
    }

    public function search(Request $request){
        $key = $request->name;
        $products = Product::whereRaw("name LIKE '%$key%'")->get();
        return response()->json([
            'success' => true,
            'message' => 'Product found',
            'data' => $products,
        ],200);
    }

    public function selected(Request $request){
        $is_variants = false;
        if(
            Product::with('product_variants')
            ->whereHas('product_variants',fn($q)=> $q->whereNotNull('product_variants.name'))
            ->find($request->id)
        ){
            $is_variants = true;
        }
        $data['is_variants'] = $is_variants;
        $data['product'] = Product::with('product_variants')->find($request->id);
        return response()->json([
            'success' => true,
            'message' => 'Product selected',
            'data' => $data,
        ],200);
    }

    public function form(Request $request){
        $is_variants = false;
        if(
            Product::with('product_variants')
            ->whereHas('product_variants',fn($q)=> $q->whereNotNull('product_variants.name'))
            ->find($request->id)
        ){
            $is_variants = true;
        }
        $data['categories'] = Category::all();
        $data['is_variants'] = $is_variants;
        if($request->id){
            $data['data'] = Product::with('product_variants')->find($request->id);
            $content = view('products.form',$data)->render();
            return response()->json(['message' => 'oke', 'content' => $content],200);
        }else{
            $data['data'] = [];
            $content = view('products.new-item',$data)->render();
            return response()->json(['message' => 'oke', 'content' => $content],200);
        }
    }

    public function store(Request $request){
        DB::beginTransaction();
        try {
            $rules = [
                'name_product' => 'required|unique:products,name',
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

            $product = ($request->id) ? Product::find($request->id) : new Product;
            $product->name = $request->name_product;
            $product->category_id = $request->category_id;
            $product->save();

            if($request->is_variant){
                foreach($request->name_product_variant as $key => $name_product_variant){
                    $price = isset($request->price) ? str_replace('.','',$request->price[$key]) : 0;
                    $purchase_price = isset($request->purchase_price) ? str_replace('.','',$request->purchase_price[$key]) : 0;
                    $productVariant = isset($request->product_variant_id) ? ProductVariant::find($request->product_variant_id[$key]) : new ProductVariant;
                    $productVariant->product_id = $product->id;
                    $productVariant->name = $name_product_variant;
                    $productVariant->price = (int)$price;
                    $productVariant->purchase_price = (int)$purchase_price;
                    $productVariant->stock = (int)$request->stock[$key];
                    $productVariant->save();
                }
            }else{
                $price = isset($request->price) ? str_replace('.','',$request->price) : 0;
                $purchase_price = isset($request->purchase_price) ? str_replace('.','',$request->purchase_price) : 0;
                $productVariant = !empty($request->product_variant_id) ? ProductVariant::find($request->product_variant_id) : new ProductVariant;
                $productVariant->product_id = $product->id;
                $productVariant->name = ($request->is_variant) ? $request->name_product_variant : null ;
                $productVariant->price = (int)$price;
                $productVariant->purchase_price = (int)$purchase_price;
                $productVariant->stock = (int)$request->stock;
                $productVariant->save();
            }

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
                'message' => 'Product deleted successfully',
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
