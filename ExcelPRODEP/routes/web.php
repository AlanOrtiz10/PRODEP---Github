<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\DirectoresController;
use App\Http\Controllers\DocenciaController;
use App\Http\Controllers\IndividualizadaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TutoriasController;
use App\Http\Controllers\UsersController;
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

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


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
    Route::post('/admin/docencia/actualizar', [DocenciaController::class, 'updateImportedData'])->name('update.imported.data');
    Route::post('/admin/docencia/cancel-update', [DocenciaController::class, 'cancelUpdate'])->name('cancel.update.imported.data');
    Route::get('/admin/docencia/export', [DocenciaController::class, 'export'])->name('export.docencia');
    Route::get('/generate-doc/{id}', [DocenciaController::class, 'generateDoc'])->name('generate.doc');

    // Rutas de Usuarios
    Route::get('/admin/usuarios', [UsersController::class, 'form'])->name('index.usuarios');

    // Rutas de Constancia de Individualizada
    Route::get('/admin/individualizada', [IndividualizadaController::class, 'form'])->name('index.individualizada');
    Route::post('/admin/individualizada/importar', [IndividualizadaController::class, 'import'])->name('import.excel.individualizada');


    // Rutas para directores
    Route::get('/admin/directores', [DirectoresController::class, 'form'])->name('directores');
    Route::post('/directores', [DirectoresController::class, 'store'])->name('directores.store');
    Route::get('/directores/{id}/edit', [DirectoresController::class, 'edit'])->name('directores.edit');
    Route::put('/directores/{id}', [DirectoresController::class, 'update'])->name('directores.update');
    Route::delete('/admin/directores/{id}', [DirectoresController::class, 'destroy'])->name('directores.destroy');
    Route::get('/admin/directores/export', [DirectoresController::class, 'export'])->name('export.directores');

});
