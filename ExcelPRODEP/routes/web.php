<?php

use App\Http\Controllers\DocenciaController;
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
