<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDocenciasTableAddDirectorFk extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('docencias', function (Blueprint $table) {
            $table->dropColumn('nombre_director_carrera');

            $table->unsignedBigInteger('director_id')->nullable();

            $table->foreign('director_id')->references('id')->on('directores')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('docencias', function (Blueprint $table) {
            // Revertir los cambios
            $table->dropForeign(['director_id']);
            $table->dropColumn('director_id');
            $table->string('nombre_director_carrera');
        });
    }
}
