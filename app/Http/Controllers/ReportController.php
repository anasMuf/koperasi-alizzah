<?php

namespace App\Http\Controllers;

use App\Models\Ledger;
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

            $saldoAwalPeriode = Ledger::
            where('created_at','<',$dateRange[0])->
            orderBy('created_at','desc')->
            first()->final ?? 0;

            $result = Ledger::selectRaw('SUM(debit) as total_pemasukan, SUM(credit) as total_pengeluaran')
            ->whereBetween('created_at', $dateRange)
            ->first();

            $pergerakanKas = $result->total_pemasukan - $result->total_pengeluaran;

            $saldoAkhirPeriode = $saldoAwalPeriode + $pergerakanKas;

            $reports['periode'] = $request->dates;

            $reports['saldo_awal_periode'] = (int)$saldoAwalPeriode;

            $reports['arus_kas_operasional']['penerimaan'] = (int)$result->total_pemasukan;
            $reports['arus_kas_operasional']['pengeluaran'] = (int)$result->total_pengeluaran;
            $reports['arus_kas_operasional']['total_operasional'] = $pergerakanKas;

            $reports['pergerakan_kas'] = $pergerakanKas;
            $reports['saldo_akhir_periode'] = $saldoAkhirPeriode;


            return response()->json($reports,200);
        }
        return view('reports.main',$data);
    }
}
