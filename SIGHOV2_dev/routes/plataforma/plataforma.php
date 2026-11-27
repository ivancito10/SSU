<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;
use App\Http\Controllers\Plataforma\AgendasCitasController;
use App\Http\Controllers\Plataforma\AgendasCitasEcografiaController;
use App\Http\Controllers\Plataforma\FichaAgendaController;
use App\Http\Controllers\Plataforma\FichaAgendaEcografiaController;
use App\Http\Controllers\Plataforma\ReportesAgendasController;

//aqui deberia estar


// especialidades
Route::get('plataforma/Especialidades/index', function () {
  return view('Plataforma.Especialidades.index');
})
  ->name('plataforma.especialidades.index')->middleware(['auth', 'verified']);

Route::get('plataforma/Especialidades/asignar/{id}', function ($id) {
  $id = $id;
  return view('Plataforma.Especialidades.asignar', compact('id'));
})
  ->name('plataforma.especialidades.asignar')->middleware(['auth', 'verified']);

//horarios
Route::get('plataforma/Horarios/index', function () {
  return view('Plataforma.Horarios.index');
})
  ->name('plataforma.horarios.index')->middleware(['auth', 'verified']);

Route::get('plataforma/Consultorios/index', function () {
  return view('Plataforma.Consultorios.index');
})
  ->name('plataforma.consultorios.index')->middleware(['auth', 'verified']);
// especialistas
Route::get('plataforma/Especialistas/index', function () {
  return view('Plataforma.Especialistas.index');
})
  ->name('plataforma.especialistas.index')->middleware(['auth', 'verified']);

Route::get('plataforma/Especialistas/create', function () {
  return view('Plataforma.Especialistas.create');
})->name('plataforma.especialistas.create');

Route::get('plataforma/Especialistas/editar/{id}', function ($id) {
  $id = $id;
  return view('Plataforma.Especialistas.edit', compact('id'));
})
  ->name('plataforma.especialistas.editar')->middleware(['auth', 'verified']);

// para habilitar servicio de especialistas
Route::get('plataforma/Especialistas/servicios/{id}', function ($id) {
  $id = $id;
  return view('Plataforma.Especialistas.servicios', compact('id'));
})
  ->name('plataforma.especialistas.servicios')->middleware(['auth', 'verified']);

// para asignar especialista
//ordenes de servicio 
Route::get('plataforma/Especialistas/asignar/{id}', function ($id) {
  $id = $id;
  return view('Plataforma.Especialistas.asignar', compact('id'));
})
  ->name('plataforma.especialistas.asignar')->middleware(['auth', 'verified']);


// vacaciones especialistas
Route::get('plataforma/Especialistas/suspension', function () {
  return view('Plataforma.Especialistas.suspension');
})
  ->name('plataforma.especialistas.suspension')->middleware(['auth', 'verified']);


// Agendamiento 
Route::get('plataforma/Agendamiento/index', function () {
  return view('Plataforma.Agendamiento.index');
})
  ->name('plataforma.agendamiento.index')->middleware(['auth', 'verified']);

Route::get('plataforma/Agendamiento/Plataforma/index', function () {
  return view('Plataforma.Agendamiento.Plataforma.index');
})
  ->name('plataforma.agendamiento.plataforma.index')->middleware(['auth', 'verified']);

Route::get('plataforma/Agendamiento/Plataforma/create', function () {
  return view('Plataforma.Agendamiento.Plataforma.create');
})
  ->name('plataforma.agendamiento.plataforma.create')->middleware(['auth', 'verified']);

//suspensionHorarios

Route::get('plataforma/SuspensionHorario/index', function () {
  return view('Plataforma.SuspensionHorario.index');
})
  ->name('plataforma.suspension-horario.index')->middleware(['auth', 'verified']);

Route::get('plataforma/SuspensionHorario/create/{id}', function ($id) {
  $id = $id;
  return view('Plataforma.SuspensionHorario.create', compact('id'));
})
  ->name('plataforma.SuspensionHorario.create')->middleware(['auth', 'verified']);

Route::get('plataforma/SuspensionHorario/masivo', function () {
  return view('Plataforma.SuspensionHorario.masivo');
})
  ->name('plataforma.suspension-horario.masivo')->middleware(['auth', 'verified']);

// para agenda especialista 
Route::get('plataforma/AgendaEspecialista/index', function () {
  return view('Plataforma.AgendaEspecialista.index');
})
  ->name('plataforma.agenda-especialista.index')->middleware(['auth', 'verified']);

Route::get('plataforma/AgendaEspecialista/calendario/{id}', function ($id) {
  $id = $id;
  return view('Plataforma.AgendaEspecialista.calendario', compact('id'));
})
  ->name('plataforma.AgendaEspecialista.calendario')->middleware(['auth', 'verified']);

Route::get('plataforma/AgendaEspecialistaEcografia/calendario/{id}', function ($id) {
  $id = $id;
  return view('Plataforma.AgendaEspecialista.calendarioEcografia', compact('id'));
})
  ->name('plataforma.AgendaEspecialista.calendarioEcografia')->middleware(['auth', 'verified']);

// para agenda web 
Route::get('plataforma/Agendamiento/Web/index', function () {
  return view('Plataforma.Agendamiento.Web.index');
})
  ->name('plataforma.agenda-web.index')->middleware(['auth', 'verified']);

//ruta anulaciones de fichas
Route::get('plataforma/Anulaciones/index', function () {
  return view('Plataforma.Anulaciones.index');
})
  ->name('plataforma.anulaciones.index')->middleware(['auth', 'verified']);

Route::get('plataforma/Anulaciones/create/{id}', function ($id) {
  return view('Plataforma.Anulaciones.create', compact('id'));
})
  ->name('plataforma.anulaciones.create')->middleware(['auth', 'verified']);

Route::get('plataforma/Anulaciones/selection', function () {
  return view('Plataforma.Anulaciones.selection');
})
  ->name('plataforma.anulaciones.selection')->middleware(['auth', 'verified']);

// Ruta para imprimir la Agenda del especialista con ID y Fecha
Route::get('plataforma/pdf_agenda_especialista/{idEsp}/{fecha}/{turno}', [AgendasCitasController::class, 'generarPdfAgendaEspecialista'])->name('pdf.generate.agenda');

// Ruta para imprimir la Agenda del especialista con ID y Fecha ECOGRAFIA----------------
Route::get('plataforma/pdf_agenda_especialista_ecografia/{idEsp}/{fecha}/{turno}', [AgendasCitasEcografiaController::class, 'generarPdfAgendaEspecialista'])->name('pdf.generate.agenda-ecografia');

//RUTA PARA IMPRIMIR LA AGENDA DEL ASEGURADO
Route::get('plataforma/ficha_agenda/{idAgenda}', [FichaAgendaController::class, 'generarPdfFichaAgenda'])->name('pdf.generar.fichaagenda');


// para administracion de horarios 
Route::get('plataforma/AdministracionFeriados/index', function () {
  return view('Plataforma.AdministracionFeriados.index');
})
  ->name('plataforma.feriados.index')->middleware(['auth', 'verified']);


// para comunicados
Route::get('plataforma/Comunicados/index', function () {
  return view('Plataforma.Comunicados.index');
})
  ->name('plataforma.comunicados.index')->middleware(['auth', 'verified']);

Route::get('plataforma/Comunicados/edit/{id}', function ($id) {
  $id = $id;
  return view('Plataforma.Comunicados.edit', compact('id'));
})
  ->name('plataforma.comunicados.edit-comunicados')->middleware(['auth', 'verified']);

// para gestionar la agenda
Route::get('plataforma/GestionarAgenda/index', function () {
  return view('Plataforma.GestionarAgenda.index');
})
  ->name('plataforma.gestionar_agenda.index')->middleware(['auth', 'verified']);

Route::get('plataforma/GestionarAgenda/create', function () {
  return view('Plataforma.GestionarAgenda.create');
})
  ->name('plataforma.gestionar_agenda.create')->middleware(['auth', 'verified']);

Route::get('plataforma/GestionarAgenda/edit/{id}', function ($id) {
  return view('Plataforma.GestionarAgenda.edit', compact('id'));
})
  ->name('plataforma.gestionar_agenda.edit')->middleware(['auth', 'verified']);


// para  el control de horarios
Route::get('plataforma/ControlHorarios/index', function () {
  return view('Plataforma.ControlHorario.index');
})
  ->name('plataforma.control_horario.index')->middleware(['auth', 'verified']);


//HISTORIAL DE CITAS
Route::get('plataforma/HistorialCitas/index', function () {
  return view('Plataforma.HistorialCitas.index');
})
  ->name('plataforma.historial-citas.index')->middleware(['auth', 'verified']);

Route::get('plataforma/boucher_agenda/{idEsp}/{fecha}', [AgendasCitasController::class, 'generarBoucher'])->name('pdf.boucher.agenda');

Route::get('plataforma/boucher_agenda_ecografia/{idEsp}/{fecha}', [AgendasCitasEcografiaController::class, 'generarBoucher'])->name('pdf.boucher.agenda-ecografia');

//------------------------------------------------------------------------------------------------------------ ECOGRAFIA INICIO
// Agendamiento Ecografia
Route::get('plataforma/Agendamiento/Ecografia/AgendaEcografia/index', function () {
  return view('Plataforma.Agendamiento.Ecografia.AgendaEcografia.index');
})
  ->name('plataforma.agendamiento.ecografia.agenda-ecografia.index')->middleware(['auth', 'verified']);


Route::get('plataforma/Agendamiento/Ecografia/AgendaEcografia/create', function () {
  return view('Plataforma.Agendamiento.Ecografia.AgendaEcografia.create');
})
  ->name('plataforma.agendamiento.ecografia.agenda-ecografia.create')->middleware(['auth', 'verified']);

//RUTA PARA IMPRIMIR LA AGENDA DEL ASEGURADO
Route::get('plataforma/ecografia/ficha_agenda/{idAgenda}', [FichaAgendaEcografiaController::class, 'generarPdfFichaAgenda'])->name('pdf.generar.fichaagendaecografia')->middleware(['auth', 'verified']);

//Anulaciones para Ecografía
Route::get('plataforma/Agendamiento/Ecografia/AnulacionEcografia/index', function () {
  return view('Plataforma.Agendamiento.Ecografia.AnulacionEcografia.index');
})
  ->name('plataforma.agendamiento.ecografia.anulacion-ecografia.index')->middleware(['auth', 'verified']);

Route::get('plataforma/Agendamiento/Ecografia/AnulacionEcografia/create/{id}', function ($id) {
  return view('Plataforma.Agendamiento.Ecografia.AnulacionEcografia.create', compact('id'));
})
  ->name('plataforma.agendamiento.ecografia.anulacion-ecografia.create')->middleware(['auth', 'verified']);

Route::get('plataforma/Agendamiento/Ecografia/AnulacionEcografia/seleccion', function () {
  return view('Plataforma.Agendamiento.Ecografia.AnulacionEcografia.seleccion');
})
  ->name('plataforma.agendamiento.ecografia.anulacion-ecografia.selection')->middleware(['auth', 'verified']);

//Suspensiones para Ecografia

Route::get('plataforma/Agendamiento/Ecografia/SuspensionHorarioEcografia/index', function () {
  return view('Plataforma.Agendamiento.Ecografia.SuspensionHorarioEcografia.index');
})
  ->name('plataforma.suspension-horario-ecografia.index')->middleware(['auth', 'verified']);

Route::get('plataforma/Agendamiento/Ecografia/SuspensionHorarioEcografia/create/{id}', function ($id) {
  $id = $id;
  return view('Plataforma.Agendamiento.Ecografia.SuspensionHorarioEcografia.create', compact('id'));
})
  ->name('plataforma.suspension-horario-ecografia.create')->middleware(['auth', 'verified']);

Route::get('plataforma/Agendamiento/Ecografia/SuspensionHorarioEcografia/masivo', function () {
  return view('Plataforma.Agendamiento.Ecografia.SuspensionHorarioEcografia.masivo');
})
  ->name('plataforma.suspension-horario-ecografia.masivo')->middleware(['auth', 'verified']);


//------------------------------------------------------------------------------------------------------------ ECOGRAFIA FIN

// ---- REPORTE DE AGENDAS
Route::get(
  'plataforma/reportes/{tipo_asegurado}/{id_institucion}/{fecha_inicio}/{fecha_fin}',
  [ReportesAgendasController::class, 'reportesAgendasPlataforma']
)->name('pdf.generateReporteAgendas')->middleware(['auth', 'verified']);
