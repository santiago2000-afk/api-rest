<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentDetails extends Model
{
    protected $table = 'documentdetails';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    protected $fillable = [
        'uuid',
        'quantity',
        'product',
        'unitPrice',
    ];
}
