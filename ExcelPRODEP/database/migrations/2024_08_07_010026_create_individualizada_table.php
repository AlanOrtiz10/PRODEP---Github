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
        Schema::create('individualizadas', function (Blueprint $table) {
            $table->id();
            $table->string('carrera');
            $table->string('asesor_academico');
            $table->string('periodo_escolar', 255);
            $table->string('matricula', 255);
            $table->string('alumno_nombre');
            $table->string('nombre_estadia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('individualizadas');
    }
};
