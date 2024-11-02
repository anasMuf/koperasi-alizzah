<?php

namespace App\Models;

use App\Models\SIAKAD\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    public function student(){
        return $this->belongsTo(Student::class);
    }

    public function order_details(){
        return $this->hasMany(OrderDetail::class,'invoice','invoice');
    }

    public static function generateInvoice(){
        $lastKode = Order::select('invoice')->orderBy('id','desc')->first();
        if($lastKode){
            $lastNumber = (int) substr($lastKode->invoice, -5);
            $newKode = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        }else{
            $newKode = '00001';
        }
        $invoice = 'OR'.$newKode;
        return $invoice;
    }
}
