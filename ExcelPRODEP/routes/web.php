<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\DirectoresController;
use App\Http\Controllers\DocenciaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TutoriasController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

// Rutas Generales del Inicio de sesión y Registro de Usuarios
Route::middleware('guest')->group(function () {
    Route::get('/login', function() {
        if (Auth::check()) {
            return redirect()->route('admin.pages.dashboard.index');
        }
        return view('admin.pages.auth.login');
    })->name('login');

    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/registro', function() {
        if (Auth::check()) {
            return redirect()->route('admin.pages.dashboard.index');
        }
        return view('register'); // Cambia a la vista correcta
    });

    Route::post('/registro', [RegisterController::class, 'register']);
});

// Ruta General del Panel Administrativo
Route::middleware('auth')->group(function () {
    Route::get('/admin', [DashboardController::class, 'indexPage'])->name('admin.pages.dashboard.index');
    
    // Rutas de Tutorias
    Route::get('/admin/tutorias', [TutoriasController::class, 'form'])->name('import.tutorias');
    Route::post('/admin/tutorias/importar', [TutoriasController::class, 'import'])->name('import.excel.tutorias');
    Route::get('/admin/tutorias/export', [TutoriasController::class, 'export'])->name('export.tutorias');

    // Rutas de Docencia
    Route::get('/admin/docencia', [DocenciaController::class, 'form'])->name('import.docencia');
    Route::post('/admin/docencia/importar', [DocenciaController::class, 'import'])->name('import.excel.docencia');
    Route::get('/admin/docencia/export', [DocenciaController::class, 'export'])->name('export.docencia');

    // Rutas para directores
    Route::get('/admin/directores', [DirectoresController::class, 'form'])->name('directores');
    Route::post('/directores', [DirectoresController::class, 'store'])->name('directores.store');
    Route::get('/directores/{id}/edit', [DirectoresController::class, 'edit'])->name('directores.edit');
    Route::put('/directores/{id}', [DirectoresController::class, 'update'])->name('directores.update');
    Route::delete('/directores/{id}', [DirectoresController::class, 'destroy'])->name('directores.destroy');
    Route::get('/admin/directores/export', [DirectoresController::class, 'export'])->name('export.directores');

    // Otras rutas protegidas...
});
