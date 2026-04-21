<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellingProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'cashier_id',
        'items_id',
    ];

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'items_id');
    }
}
