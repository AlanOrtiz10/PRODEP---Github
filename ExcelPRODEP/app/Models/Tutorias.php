<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tutorias extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha_registro',
        'tutor',
        'tipo_tutoria',
        'grupo',
        'alumno',
        'estatus',
        'motivo',
        'periodo',
    ];
}
