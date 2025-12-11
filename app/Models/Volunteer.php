<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Volunteer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'telepon',
    ];

    public function transaksis(): BelongsToMany
    {
        return $this->belongsToMany(Transaksi::class, 'transaksi_volunteers', 'volunteer_id', 'transaksi_id')
                    ->withTimestamps();
    }
}
