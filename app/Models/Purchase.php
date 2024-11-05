<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use HasFactory, SoftDeletes;

    public function purchase_details(){
        return $this->hasMany(PurchaseDetail::class,'invoice','invoice');
    }
    public static function generateInvoice($newItem=true){
        $lastKode = Purchase::select('invoice')->orderBy('id','desc')->first();
        if($lastKode){
            $lastNumber = (int) substr($lastKode->invoice, -5);
            $newKode = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        }else{
            $newKode = '00001';
        }
        $invoice = 'PN'.$newKode;
        if(!$newItem){
            $invoice = 'PS'.$newKode;
        }
        return $invoice;
    }
}
