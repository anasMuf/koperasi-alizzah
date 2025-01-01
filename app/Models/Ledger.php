<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ledger extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'description',
        'refrence',
        'current',
        'debit',
        'credit',
        'final'
    ];

    public static function store($request){
        $data = ($request->id_ledger) ? Ledger::find($request->id_ledger) : new Ledger;
        $data->type = $request->type;
        $data->description = $request->description;
        $data->refrence = $request->refrence;
        $data->current = $request->current;
        $data->debit = $request->debit;
        $data->credit = $request->credit;
        $data->final = $request->final;
        $data->save();

        return $data?:false;
    }

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

        $saldoAwalPeriode = Ledger::
        where('created_at','<',$dateRange[0])->
        orderBy('created_at','desc')->
        first()->final ?? 0;

        $result = Ledger::selectRaw('SUM(debit) as total_pemasukan, SUM(credit) as total_pengeluaran')
        ->whereBetween('created_at', $dateRange)
        ->where(function($q) {
            $q->whereNotIN('description',['bayar hutang','bayar piutang'])
            ->orWhereNull('description');
        })
        ->first();

        $hutang = Ledger::selectRaw('SUM(credit) as total_hutang')
        ->whereBetween('created_at', $dateRange)
        ->where('description','bayar hutang')
        ->first()->total_hutang;
        $piutang = Ledger::selectRaw('SUM(debit) as total_piutang')
        ->whereBetween('created_at', $dateRange)
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
