<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'events';
    protected $fillable = [
        'name',
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

    public $timestamps = true;
}
