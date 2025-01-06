<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewLedger extends Model
{
    use HasFactory;

    protected $table = "view_ledgers";

    public static function cashFlow($request){
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

        // $saldoAwalPeriode = ViewLedger::
        // where('trx_date','<',$dateRange[0])->
        // orderBy('trx_date','desc')->
        // first()->final ?? 0;

        $saldoAwalPeriode = ViewLedger::select('current')
        ->whereBetween('trx_date', $dateRange)
        // ->where(function($q) {
        //     $q->whereNotIN('description',['bayar hutang','bayar piutang'])
        //     ->orWhereNull('description');
        // })->
        ->orderBy('trx_date','asc')
        ->first()->current ?? 0;

        $result = ViewLedger::selectRaw('SUM(debit) as total_pemasukan, SUM(credit) as total_pengeluaran')
        ->whereBetween('trx_date', $dateRange)
        ->where(function($q) {
            $q->whereNotIN('description',['bayar hutang','bayar piutang'])
            ->orWhereNull('description');
        })
        ->first();

        // $saldoAwalPeriode =

        $hutang = ViewLedger::selectRaw('SUM(credit) as total_hutang')
        ->whereBetween('trx_date', $dateRange)
        ->where('description','bayar hutang')
        ->first()->total_hutang;
        $piutang = ViewLedger::selectRaw('SUM(debit) as total_piutang')
        ->whereBetween('trx_date', $dateRange)
        ->where('description','bayar piutang')
        ->first()->total_piutang;

        $pergerakanKas = ($result->total_pemasukan+$piutang) - ($result->total_pengeluaran+$hutang);
        // $pergerakanKas = $result->total_pemasukan - $result->total_pengeluaran;

        $saldoAkhirPeriode = $saldoAwalPeriode + $pergerakanKas;

        $reports['periode'] = $request->dates;

        $reports['saldo_awal_periode'] = (int)$saldoAwalPeriode;

        $reports['arus_kas_operasional']['penerimaan'] = (int)$result->total_pemasukan;
        $reports['arus_kas_operasional']['piutang'] = (int)$piutang;
        $reports['arus_kas_operasional']['pengeluaran'] = (int)$result->total_pengeluaran;
        $reports['arus_kas_operasional']['hutang'] = (int)$hutang;
        $reports['arus_kas_operasional']['total_operasional'] = $pergerakanKas;

        $reports['pergerakan_kas'] = $pergerakanKas;
        $reports['saldo_akhir_periode'] = $saldoAkhirPeriode;

        return $reports?:false;
    }
}
