<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Purchase;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $menu = 'laporan arus kas';

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
                $time = ' 00:00:00';
                foreach($dates as $key => $date){
                    if($key === 1){
                        $time = ' 23:59:59';
                    }
                    $dateRange[] = date('Y-m-d H:i:s',strtotime($date.$time));
                }
            }

            $reports['periode'] = $request->dates;
            $reports['penerimaan'][0]['keterangan'] = 'Penerimaan dari siswa';
            $reports['penerimaan'][0]['jumlah'] = Order::selectRaw("sum(total) as jumlah")->whereNotNull('student_id')->whereBetween('created_at',$dateRange)->first()->jumlah;
            $reports['penerimaan'][1]['keterangan'] = 'Penerimaan dari non-siswa';
            $reports['penerimaan'][1]['jumlah'] = Order::selectRaw("sum(total) as jumlah")->whereNull('student_id')->whereBetween('created_at',$dateRange)->first()->jumlah;
            $reports['pengeluaran'][0]['keterangan'] = 'Pembelian Barang';
            $reports['pengeluaran'][0]['jumlah'] = Purchase::selectRaw("sum(total) as jumlah")->whereBetween('created_at',$dateRange)->first()->jumlah;

            return response()->json($reports,200);
        }
        return view('reports.main',$data);
    }
}
