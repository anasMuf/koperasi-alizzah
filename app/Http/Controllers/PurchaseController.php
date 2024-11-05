<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Product;
use App\Models\Purchase;
use App\Helpers\LogPretty;
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
        $data['vendors'] = Vendor::all();
        $data['data'] = [];
        return view('purchases.restock',$data);
    }

    public function storeRestock(Request $request){
        return $request;
        DB::beginTransaction();
        try{

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
