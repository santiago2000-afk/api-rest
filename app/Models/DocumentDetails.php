<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentDetails extends Model
{
    protected $table = 'document_details';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    protected $fillable = [
        'uuid',
        'quantity',
        'product',
        'unitPrice',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class, 'uuid', 'uuid');
    }
}
