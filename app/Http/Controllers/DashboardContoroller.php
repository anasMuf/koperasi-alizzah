<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardContoroller extends Controller
{
    protected $menu = "dashboard";

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        $data['menu'] = $this->menu;
        return view('dashboard.main',$data);
    }
}
