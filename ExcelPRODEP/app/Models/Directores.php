<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Directores extends Model
{
    use HasFactory;

    protected $fillable = [
        'carrera',
        'nivel',
        'director',
    ];
}
