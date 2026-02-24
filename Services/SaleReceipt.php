<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleReceipt extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'sale_transaction_id','path','original_name','mime','size_bytes','created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function transaction()
    {
        return $this->belongsTo(SaleTransaction::class, 'sale_transaction_id');
    }
}
