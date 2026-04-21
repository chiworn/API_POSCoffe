<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'total_qty',
        'total_amount',
        'payment_method',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function sellingProducts()
    {
        return $this->hasMany(SellingProduct::class);
    }
}
