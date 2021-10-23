<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cabai extends Model
{
    use HasFactory;
    protected $table = 'cabai';
    protected $fillable = [
        'tanggal', 'harga'
    ];
}
