<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'uuid',
        'codCustomer',
        'date',
        'totalSale',
        'status',
        'webUser',
    ];
}
