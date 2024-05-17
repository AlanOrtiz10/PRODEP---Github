<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('docencias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_profesor');
            $table->string('nombre_carrera');
            $table->string('nombre_director_carrera');
            $table->string('cuatrimestre')->nullable(); // Cambiado a nullable
            $table->string('grupo');
            $table->string('asignatura');
            $table->integer('numero_alumnos');
            $table->integer('asesorias_mes');
            $table->integer('horas_extras_mes')->nullable(); // Cambiado a nullable
            $table->integer('horas_semanales_curso');
            $table->string('periodo_escolar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('docencias');
    }
};
