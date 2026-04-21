<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'glass_id',
        'quantity',
    ];

    public function glass()
    {
        return $this->belongsTo(Glass::class);
    }
}
