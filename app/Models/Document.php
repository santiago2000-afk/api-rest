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

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'codCustomer', 'codCustomer');
    }

    public function documentDetails()
    {
        return $this->hasMany(DocumentDetail::class, 'uuid', 'uuid');
    }
}
