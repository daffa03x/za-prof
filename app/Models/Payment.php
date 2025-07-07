<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'payments';
    protected $fillable = [
        'name',
        'no_rek',
        'image',
        'status'
    ];

    public $timestamps = true;

    public function transaksi()
    {
        return $this->HasOne(Transaksi::class, 'id_payment');
    }
}
