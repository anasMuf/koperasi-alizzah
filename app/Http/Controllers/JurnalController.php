<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Ledger;
use App\Models\ViewLedger;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use App\Models\PurchaseDetail;
use App\Models\PurchasePayment;
use App\Models\ReceivablesMember;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\ReceivablesMemberPayment;
use Yajra\DataTables\Facades\DataTables;

class JurnalController extends Controller
{
    protected $menu = 'jurnal';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){
        $data['menu'] = $this->menu;
        if($request->ajax()){

            $data = Ledger::select([
                'id',
                'type',
                'description',
                'refrence',
                DB::raw('(
                    SELECT COALESCE(SUM(l2.debit - l2.credit), 0)
                    FROM ledgers l2
                    WHERE l2.trx_date < ledgers.trx_date
                    AND l2.deleted_at IS NULL
                ) AS current'),
                'debit',
                'credit',
                DB::raw('(
                    SELECT COALESCE(SUM(l2.debit - l2.credit), 0)
                    FROM ledgers l2
                    WHERE l2.trx_date < ledgers.trx_date
                    AND l2.deleted_at IS NULL
                ) + ledgers.debit - ledgers.credit AS final'),
                'trx_date',
                'updated_at',
                'deleted_at',
            ])->
            when(!empty($request->type_transaksi), function($q) use ($request){
                $q->where('type',$request->type_transaksi);
            })->
            when($request->start_date && $request->end_date, function($q) use ($request){
                $q->whereBetween('trx_date',[
                    Carbon::parse($request->start_date)->startOfMonth(),
                    Carbon::parse($request->end_date)->endOfMonth()
                ]);
            })->
            orderBy('trx_date', 'ASC')->
            get();

            return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('trx_date_', function($row){
                return Carbon::parse($row->trx_date)->isoFormat('DD MMMM YYYY');
            })
            ->addColumn('keterangan', function($row){
                if($row->refrence === 'transaksi umum'){
                    return $row->description;
                }elseif($row->refrence === 'SALDO'){
                    return 'Penambahan Saldo';
                }else{
                    if(ctype_alpha(substr($row->refrence, 0, 1))){
                        $prefix = substr($row->refrence, 0, 2);
                        if($prefix === 'PN' || $prefix === 'PS'){
                            $textArr = [];
                            foreach (PurchaseDetail::where('invoice',$row->refrence)->with('product_variant.product')->get() as $value) {
                                $textArr[] = $value->product_variant->product->name.' - '.$value->product_variant->name;
                            }
                            return 'Pembelian: '.implode(',',$textArr);
                        }elseif($prefix === 'OR'){
                            $textArr = [];
                            foreach (OrderDetail::where('invoice',$row->refrence)->with('product_variant.product')->get() as $value) {
                                $textArr[] = $value->product_variant->product->name.' - '.$value->product_variant->name;
                            }
                            return 'Penjualan: '.implode(',',$textArr);
                        }
                    }else{
                        if($row->description === 'bayar hutang'){
                            $textArr = [];
                            $invoice = '';
                            foreach (PurchasePayment::where('id',$row->refrence)->with(['purchase.vendor'])->get() as $value) {
                                $textArr[] = $value->purchase->vendor->name;
                                if(!empty($invoice)){
                                    continue;
                                }else{
                                    $invoice = ', ref: '.$value->purchase->invoice;
                                }
                            }
                            return 'Bayar hutang ke: '.implode(',',$textArr).''.$invoice;
                        }elseif($row->description === 'piutang anggota'){
                            $textArr = [];
                            $invoice = '';
                            foreach (ReceivablesMember::where('id',$row->refrence)->with(['member'])->get() as $value) {
                                $textArr[] = $value->member->name;
                                if(!empty($invoice)){
                                    continue;
                                }else{
                                    $invoice = ', ref: '.$value->purchase->invoice;
                                }
                            }
                            return 'Piutang Anggota: '.implode(',',$textArr).''.$invoice;
                        }elseif($row->description === 'bayar piutang'){
                            $textArr = [];
                            $invoice = '';
                            foreach (ReceivablesMemberPayment::where('id',$row->refrence)->with(['receivables_member.member'])->get() as $value) {
                                $textArr[] = $value->receivables_member->member->name;
                                if(!empty($invoice)){
                                    continue;
                                }else{
                                    $invoice = ', ref: '.$value->purchase->invoice;
                                }
                            }
                            return 'Bayar Piutang: '.implode(',',$textArr).''.$invoice;
                        }
                    }
                }
            })
            ->addColumn('refrence_', function($row){
                if($row->refrence === 'transaksi umum'){
                    return '<a href="javascript:void(0);">'.$row->refrence.'</a>';
                }elseif($row->refrence === 'SALDO'){
                    return '<a href="javascript:void(0);">'.$row->refrence.'</a>';
                }else{
                    if(ctype_alpha(substr($row->refrence, 0, 1))){
                        $prefix = substr($row->refrence, 0, 2);
                        if($prefix === 'PN' || $prefix === 'PS'){
                            return '<a href="'.route('purchase.edit',['from' => 'jurnal','invoice' => $row->refrence]).'">'.$row->refrence.'</a>';
                        }elseif($prefix === 'OR'){
                            return '<a href="'.route('order.edit',['from' => 'jurnal','invoice' => $row->refrence]).'">'.$row->refrence.'</a>';
                        }
                    }else{
                        if($row->description === 'bayar hutang'){
                            return '<a href="javascript:void(0);">'.$row->refrence.'</a>';
                        }elseif($row->description === 'piutang anggota'){
                            return '<a href="javascript:void(0);">'.$row->refrence.'</a>';
                        }elseif($row->description === 'bayar piutang'){
                            return '<a href="javascript:void(0);">'.$row->refrence.'</a>';
                        }
                    }
                }
            })
            ->addColumn('debit', function($row){
                $nominal = $row->debit > 0 ? number_format($row->debit,0,',','.') : '';
                return $nominal;
            })
            ->addColumn('credit', function($row){
                $nominal = $row->credit > 0 ? number_format($row->credit,0,',','.') : '';
                return $nominal;
            })
            ->addColumn('final', function($row){
                $nominal = number_format($row->final,0,',','.');
                return $nominal;
            })
            ->rawColumns([
                'keterangan',
                'refrence_',
                'debit',
                'credit',
                'final'
            ])
            ->make(true);
        }
        return view('jurnals.main',$data);
    }

    public function export(Request $request){
        $data['data'] = Ledger::select([
            'type',
            'description',
            'refrence',
            DB::raw('(
                SELECT COALESCE(SUM(l2.debit - l2.credit), 0)
                FROM ledgers l2
                WHERE l2.trx_date < ledgers.trx_date
                AND l2.deleted_at IS NULL
            ) AS current'),
            'debit',
            'credit',
            DB::raw('(
                SELECT COALESCE(SUM(l2.debit - l2.credit), 0)
                FROM ledgers l2
                WHERE l2.trx_date < ledgers.trx_date
                AND l2.deleted_at IS NULL
            ) + ledgers.debit - ledgers.credit AS final'),
            'trx_date',
            'updated_at',
            'deleted_at',
        ])->
        when(!empty($request->type_transaksi), function($q) use ($request){
            $q->where('type',$request->type_transaksi);
        })->
        when($request->start_date && $request->end_date, function($q) use ($request){
            $q->whereBetween('trx_date',[
                Carbon::parse($request->start_date)->startOfMonth(),
                Carbon::parse($request->end_date)->endOfMonth()
            ]);
        })->
        orderBy('trx_date', 'ASC')->
        get();
        $periode = Carbon::parse($request->start_date)->isoFormat('MMMM YYYY').' - '.Carbon::parse($request->start_date)->isoFormat('MMMM YYYY');
        $data['periode'] = $periode;
        $namaFile = "Jurnal_Periode_$periode.xlsx";
        if($request->export == 'excel'){
            return 'maintenance'; #Excel::download(,$namaFile);
        }elseif($request->export == 'print'){
            $data['title'] = $namaFile;
            return view('jurnals.print',$data);
        }else{
            return response()->json("Invalid Request",404);
        }
    }
}
