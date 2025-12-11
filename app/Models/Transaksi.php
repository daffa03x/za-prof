<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Transaksi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'transaksis';

    protected $fillable = [
        'event_id',
        'invoice',
        'jumlah_tiket',
        'total_pembayaran',
        'name',
        'email',
        'telepon',
        'status_pembayaran',
        'tanggal_register',
        'tanggal_pembayaran',
        'payment_id',
        'voucher_id',
    ];

    protected $casts = [
        'tanggal_register' => 'datetime',
        'tanggal_pembayaran' => 'datetime',
        'jumlah_tiket' => 'integer',
        'total_pembayaran' => 'integer',
    ];

    public function getRouteKeyName(): string
    {
        return 'invoice';
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(KodeVoucher::class, 'voucher_id');
    }

    public function volunteers(): BelongsToMany
    {
        return $this->belongsToMany(Volunteer::class, 'transaksi_volunteers', 'transaksi_id', 'volunteer_id')
                    ->withTimestamps();
    }
}
