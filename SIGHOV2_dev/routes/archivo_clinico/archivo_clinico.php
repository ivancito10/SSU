<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;
use App\Http\Controllers\Plataforma\AgendasCitasController;
use App\Http\Controllers\Plataforma\FichaAgendaController;

//IMPRECION DE BOUCHERS
Route::get('ArchivoClinico/ArchivoClinico/index', function () {
  return view('ArchivoClinico.AgendasDiarias.index');
});

Route::get('ArchivoClinico/ListaAgenda/index', function () {
  return view('ArchivoClinico.Agendas.index');
});

