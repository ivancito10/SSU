<?php

use Illuminate\Support\Facades\Route;

// Route::prefix('atencion_medica')->group(function () {

//     Route::get('/MisRecetas/index', function () {
//         return view('AtencionMedica.MisRecetas.index');
//     })->name('atencion_medica.index');

// })->middleware(['auth', 'verified']);
Route::get('atencion_medica/MisRecetas/index', function () {
    return view('AtencionMedica.MisRecetas.index');
})->name('atencion_medica.index')->middleware(['auth', 'verified']);
