<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataPasien extends Model
{
    use HasFactory;
    public $table = 'datapasien';
    public $fillable = [
        'nama',
        'alamat',
        'jenis_kelamin',
        'umur',
        'penyakit',
    ];
}
