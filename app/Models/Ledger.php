<?php

namespace App\Models;

use App\Models\ViewLedger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ledger extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'description',
        'refrence',
        // 'current',
        'trx_date',
        'debit',
        'credit',
        // 'final'
    ];

    public static function store($request){
        $data = ($request->id_ledger) ? Ledger::find($request->id_ledger) : new Ledger;
        $data->type = $request->type;
        $data->description = $request->description;
        $data->refrence = $request->refrence;
        $data->trx_date = $request->trx_date;
        // $data->current = $request->current;
        $data->debit = $request->debit;
        $data->credit = $request->credit;
        // $data->final = $request->final;
        $data->save();

        return $data?:false;
    }
}
