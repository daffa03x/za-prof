<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'campaigns';
    protected $fillable = [
        'name',
        'mitra',
        'website',
        'deskripsi',
        'image',
    ];

    public $timestamps = true;
}
