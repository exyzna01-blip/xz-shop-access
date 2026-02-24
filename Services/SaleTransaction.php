<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_item_id','admin_id','admin_username',
        'service','duration','category','devices',
        'price','capital_cost',
        'status','owner_review_status','owner_notes',
        'weekly_bucket','approved_at',
    ];

    protected $casts = [
        'weekly_bucket' => 'date',
        'approved_at' => 'datetime',
    ];

    public function stockItem()
    {
        return $this->belongsTo(StockItem::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function receipts()
    {
        return $this->hasMany(SaleReceipt::class);
    }
}
