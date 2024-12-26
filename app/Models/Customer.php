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

    public function documents()
    {
        return $this->hasMany(Document::class, 'codCustomer', 'codCustomer');
    }
}
