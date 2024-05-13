<?php

use App\Http\Controllers\AnimalController;
use App\Http\Controllers\PastagemController;
use App\Http\Controllers\PlanoRotacaoController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/simular_rotacao', [PlanoRotacaoController::class, 'simularRotacao'])->name('simular_rotacao');

//!ROTA ANIMAIS
Route::get('/lista_animais', [AnimalController::class, 'index'])->name('lista_animais');

Route::post('/animais', [AnimalController::class, 'store'])->name('store_animal');

Route::get('/animais/{animal}', [AnimalController::class, 'show'])->name('show_animal');

Route::get('/animais/{animal}/edit', [AnimalController::class, 'edit'])->name('edit_animal');

Route::put('/animais/{animal}', [AnimalController::class, 'update'])->name('update_animal');

Route::delete('/animais/{animal}', [AnimalController::class, 'destroy'])->name('delete_animal');


//!ROTA PASTAGEM
Route::get('/lista_pastagens', [PastagemController::class, 'index'])->name('lista_pastagens');

Route::post('/pastagens', [PastagemController::class, 'store'])->name('store_pastagem');

Route::get('/pastagens/{pastagem}', [PastagemController::class, 'show'])->name('show_pastagem');

Route::get('/pastagens/{pastagem}/edit', [PastagemController::class, 'edit'])->name('edit_pastagem');

Route::put('/pastagens/{pastagem}', [PastagemController::class, 'update'])->name('update_pastagem');

Route::delete('/pastagens/{pastagem}', [PastagemController::class, 'destroy'])->name('delete_pastagem');
