<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'type',
        'name',
    ];

    public function receivables_member(){
        return $this->hasMany(ReceivablesMember::class);
    }
}
