<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'no_rek',
        'image',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function transaksis(): HasMany
    {
        return $this->hasMany(Transaksi::class, 'payment_id');
    }
}
