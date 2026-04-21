<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlassUse extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'glass_id',
        'quantity_used',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function glass()
    {
        return $this->belongsTo(Glass::class);
    }
}
