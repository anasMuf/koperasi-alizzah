<?php

namespace App\Models\SIAKAD;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    // protected $connection = 'alizzah';

    public function orders(){
        return $this->hasMany(Order::class);
    }
}
