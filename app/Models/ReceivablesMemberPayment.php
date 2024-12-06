<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceivablesMemberPayment extends Model
{
    use HasFactory;

    public function receivables_member(){
        return $this->belongsTo(ReceivablesMember::class);
    }
}
