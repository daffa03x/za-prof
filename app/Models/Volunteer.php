<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Volunteer extends Model
{
    use HasFactory;
    protected $table = "volunteers";

    protected $fillable = ['name', 'email', 'telepon'];

    public function transaksis()
    {
        return $this->belongsToMany(Transaksi::class, 'transaksi_volunteers', 'id_volunteer', 'id_transaksi')
                    ->withTimestamps();
    }

}
