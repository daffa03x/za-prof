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
        'jenis_kelamin',
        'tanggal_lahir',
        'status_pembayaran',
        'tanggal_register',
        'tanggal_pembayaran',
        'id_payment'
    ];

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
