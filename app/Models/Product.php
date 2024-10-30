<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    public function product_variants() {
        return $this->hasMany(ProductVariant::class);
    }


    public static function getProduct($request){
        $kw = $request->search_product;
        return Product::when($kw, function($q) use ($kw){
            $q->whereRaw("lower(name) LIKE '%$kw%'");
        })
        ->get();
    }

    public static function getProductById($request){
        return Product::with('product_variants')->find($request->id);
    }
}
