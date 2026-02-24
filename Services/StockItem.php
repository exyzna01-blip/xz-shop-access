<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'service','duration','category','devices',
        'email','password','label',
        'capital_cost','status','created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function saleTransactions()
    {
        return $this->hasMany(SaleTransaction::class);
    }
}
