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

    // Definir la relación de uno a muchos con el modelo Docencia
    public function docencias()
    {
        return $this->hasMany(Docencia::class, 'director_id');
    }
}
