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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('apellido_paterno'); // Nuevo campo
            $table->string('apellido_materno'); // Nuevo campo
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable(); // Eliminado
            $table->string('password');
            $table->string('genero'); // Nuevo campo
            $table->date('fecha_nacimiento'); // Nuevo campo
            $table->string('curp'); // Nuevo campo
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
