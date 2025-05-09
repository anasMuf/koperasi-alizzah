<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    public function purchases(){
        return $this->hasMany(Purchase::class);
    }
    public function purchase(){
        return $this->hasOne(Purchase::class);
    }
}
