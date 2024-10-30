<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Helpers\LogPretty;
use Illuminate\Http\Request;

class CashierController extends Controller
{
    protected $menu = "kasir";

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){
        $data['menu'] = $this->menu;
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
        return $request;
    }
}
