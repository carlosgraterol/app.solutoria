<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/indicador/store', [App\Http\Controllers\HomeController::class, 'store'])->name('store');
Route::post('/indicador/borrar', [App\Http\Controllers\HomeController::class, 'borrar'])->name('borrar');
Route::post('/indicador/filtro', [App\Http\Controllers\HomeController::class, 'filtro'])->name('filtro');
Route::post('/tareas/guardar', [App\Http\Controllers\TareaController::class, 'guardar']);
Route::resource('/tareas', App\Http\Controllers\TareaController::class);
