<?php

namespace App\Http\Controllers;

use App\Models\ViewLedger;
use Illuminate\Http\Request;
use App\Exports\CashFlowExport;
use Maatwebsite\Excel\Facades\Excel;

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
            $reports = ViewLedger::cashFlow($request);
            return response()->json($reports,200);
        }
        return view('reports.main',$data);
    }

    public function export(Request $request){
        $data['data'] = ViewLedger::cashFlow($request);
        $namaFile = "Laporan_Arus_Kas_Periode_$request->dates.xlsx";
        if($request->export == 'excel'){
            return Excel::download(new CashFlowExport($data),$namaFile);
        }elseif($request->export == 'print'){
            $data['title'] = $namaFile;
            return view('reports.print',$data);
        }else{
            return response()->json("Invalid Request",404);
        }
    }
}
