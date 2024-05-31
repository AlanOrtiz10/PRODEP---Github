<?php

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


Route::get('docencia-importar', [DocenciaController::class, 'form'])->name('import.form');
Route::post('docencia-importar', [DocenciaController::class, 'import'])->name('import.excel');

Route::get('tutorias-importar', [TutoriasController::class, 'form'])->name('import.form');
Route::post('tutorias-importar', [TutoriasController::class, 'import'])->name('import.excel');


