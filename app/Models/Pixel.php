<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pixel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pixels';
    protected $fillable = [
        'name',
        'type',
        'id_event',
        'status'
    ];

    public $timestamps = true;
}
