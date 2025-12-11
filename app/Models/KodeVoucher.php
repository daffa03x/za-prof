<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KodeVoucher extends Model
{
    use HasFactory;

    protected $table = 'kode_vouchers';

    protected $fillable = [
        'event_id',
        'name_voucher',
        'kode',
        'nilai_diskon',
        'kuota',
        'digunakan',
        'tanggal_kadaluarsa',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'tanggal_kadaluarsa' => 'date',
        'nilai_diskon' => 'integer',
        'kuota' => 'integer',
        'digunakan' => 'integer',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function transaksis(): HasMany
    {
        return $this->hasMany(Transaksi::class, 'voucher_id');
    }
}
