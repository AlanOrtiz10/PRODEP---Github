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
        'cuatrimestre',
        'grupo',
        'asignatura',
        'numero_alumnos',
        'asesorias_mes',
        'horas_extras_mes',
        'horas_semanales_curso',
        'periodo_escolar',
        'director_id', 
        'timestamps',
    ];

    // Definir la relación de muchos a uno con el modelo Director
    public function director()
    {
        return $this->belongsTo(Directores::class, 'director_id');
    }
}
