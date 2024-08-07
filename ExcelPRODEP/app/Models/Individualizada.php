<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Individualizada extends Model
{
    use HasFactory;

    protected $fillable = [
        'carrera',
        'asesor_academico',
        'periodo_escolar',
        'matricula',
        'alumno_nombre',
        'nombre_estadia',
    ];
}
