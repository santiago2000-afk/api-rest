<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'codCustomer',
        'address',
        'passport',
        'dui',
        'nit',
        'name',
        'email',
        'celphone',
        'phone',
    ];
}
