<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Docencia extends Model
{
    use HasFactory;

    protected $fillable = [
     'nombre_profesor',
     'nombre_carrera', 
     'nombre_director_carrera', 
     'cuatrimestre',
     'grupo',
     'asignatura',
     'numero_alumnos',
     'asesorias_mes',
     'horas_extras_mes',
     'horas_semanales_curso',
     'periodo_escolar',
     'timestamps',
    ];

}
