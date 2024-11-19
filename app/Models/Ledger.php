<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ledger extends Model
{
    use HasFactory;

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
}
