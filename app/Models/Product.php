<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'product_namekhmer',
        'product_nameenglish',
        'price',
        'stock',
        'status',
        'image',
        'image_public_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function glassUses()
    {
        return $this->hasMany(GlassUse::class);
    }
}
