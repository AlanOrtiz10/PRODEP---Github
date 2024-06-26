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


Route::get('/admin', [DashboardController::class, 'indexPage'])->name('index');

Route::get('/admin/tutorias', [TutoriasController::class, 'form'])->name('import.tutorias');
Route::post('/admin/tutorias/importar', [TutoriasController::class, 'import'])->name('import.excel.tutorias');

Route::get('/admin/docencia', [DocenciaController::class, 'form'])->name('import.docencia');
Route::post('/admin/docencia/importar', [DocenciaController::class, 'import'])->name('import.excel.docencia');


// Rutas para directores

Route::get('/admin/directores', [DirectoresController::class, 'form'])->name('directores');
Route::post('/directores', [DirectoresController::class, 'store'])->name('directores.store');
Route::get('/directores/{id}/edit', [DirectoresController::class, 'edit'])->name('directores.edit');
Route::put('/directores/{id}', [DirectoresController::class, 'update'])->name('directores.update');
Route::delete('/directores/{id}', [DirectoresController::class, 'destroy'])->name('directores.destroy');





