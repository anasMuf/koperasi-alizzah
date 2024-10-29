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
}
