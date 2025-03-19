<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\ViewLedger;
use Illuminate\Http\Request;
use App\Exports\CashFlowExport;
use App\Models\Ledger;
use App\Models\YearPeriod;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    protected $menu = 'laporan keuangan';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){
        $data['menu'] = $this->menu;
        $data['year_periods'] = YearPeriod::all();
        if($request->ajax()){
            // $reports = ViewLedger::cashFlow($request);
            $reports = Ledger::report($request);
            $content = view('reports.data',$reports)->render();
            return response()->json(['content'=>$content],200);
        }
        return view('reports.main',$data);
    }

    public function export(Request $request){

        $periode = Carbon::parse($request->start_date)->isoFormat('MMMM YYYY').' - '.Carbon::parse($request->end_date)->isoFormat('MMMM YYYY');
        $data['data'] = ViewLedger::cashFlow($request);
        $data['periode'] = $periode;
        $namaFile = "Laporan_Arus_Kas_Periode_$periode.xlsx";
        if($request->export == 'excel'){
            return Excel::download(new CashFlowExport($data),$namaFile); #'maintenance';
        }elseif($request->export == 'print'){
            $data['title'] = $namaFile;
            return view('reports.print',$data);
        }else{
            return response()->json("Invalid Request",404);
        }
    }
}
