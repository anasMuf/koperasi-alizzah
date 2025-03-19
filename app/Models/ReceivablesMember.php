<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceivablesMember extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function member(){
        return $this->belongsTo(Member::class);
    }
    public function receivables_member_payments(){
        return $this->hasMany(ReceivablesMemberPayment::class);
    }
}
