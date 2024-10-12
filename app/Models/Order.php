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
}
