<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use HasFactory,SoftDeletes;

    public function order_detail() {
        return $this->hasOne(OrderDetail::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
