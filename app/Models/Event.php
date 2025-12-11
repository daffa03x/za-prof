<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'mitra',
        'website',
        'deskripsi',
        'status',
        'waktu_mulai',
        'waktu_berakhir',
        'nama_tempat',
        'alamat',
        'kota',
        'jumlah_tiket',
        'harga',
        'image',
    ];

    protected $casts = [
        'status' => 'boolean',
        'waktu_mulai' => 'datetime',
        'waktu_berakhir' => 'datetime',
        'jumlah_tiket' => 'integer',
        'harga' => 'integer',
    ];

    public function transaksis(): HasMany
    {
        return $this->hasMany(Transaksi::class, 'event_id');
    }

    public function kodeVouchers(): HasMany
    {
        return $this->hasMany(KodeVoucher::class, 'event_id');
    }

    public function pixels(): HasMany
    {
        return $this->hasMany(Pixel::class, 'event_id');
    }
}
