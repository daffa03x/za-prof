<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'transaksis';
    protected $fillable = [
        'id_event',
        'invoice',
        'jumlah_tiket',
        'total_pembayaran',
        'name',
        'email',
        'telepon',
        'status_pembayaran',
        'tanggal_register',
        'tanggal_pembayaran',
        'id_payment'
    ];

    protected $casts = [
        'tanggal_register' => 'datetime',
    ];

    public function getRouteKeyName()
    {
        return 'invoice'; // Laravel akan mencari Transaksi berdasarkan kolom 'invoice'
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'id_event');
    }
    
    public function payment()
    {
        return $this->belongsTo(Payment::class, 'id_payment'); 
    }

    public $timestamps = true;
}
