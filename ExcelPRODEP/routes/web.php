<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\DirectoresController;
use App\Http\Controllers\DocenciaController;
use App\Http\Controllers\TutoriasController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::get('/', function () {
    return view('welcome');
});

// Ruta General del Panel Administrativo
Route::get('/admin', [DashboardController::class, 'indexPage'])->name('index');

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



// Ruta para generar documento de Docencia
Route::get('/generate-doc/{id}', [DocenciaController::class, 'generateDoc'])->name('generate.doc');



