
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Vigencia\PrestacionMedicaPdf;
use App\Http\Controllers\Vigencia\ConveniosInteriorCarnet;
use App\Http\Controllers\Vigencia\ConveniosEmergenciaPdf;
use App\Http\Controllers\Vigencia\DescargarSolicitud;
use App\Http\Controllers\Afiliacion\Carnetizacion;
use App\Http\Controllers\Vigencia\convenioPDF;
use App\Http\Controllers\Vigencia\ReportesVigenciaPDF;
use App\Http\Controllers\Vigencia\PdfReporteController;
use App\Http\Controllers\Vigencia\PreafiliacionPdf;
use App\Http\Controllers\Vigencia\TapaHistoriaClinica;
use Illuminate\Http\Request;

//aqui deberia estar


//BAJAS INSTITUCIONALES

Route::get('vigencia/BajasInstitucionales/index', function () {
  return view('Vigencia.BajasInstitucionales.index');
})
  ->name('vigencia.bajasinstitucionales.index')->middleware(['auth', 'verified']);

Route::get('vigencia/BajasInstitucionales/create', function () {
  return view('Vigencia.BajasInstitucionales.create');
})
  ->name('vigencia.bajasinstitucionales.create')->middleware(['auth', 'verified']);

//Reporte PDF baja institucional
Route::get('/vigencia/BajasInstitucionales/{fechaInicio}/{fechaFin}', [pdfReporteController::class, 'generarPdfBajaInstitucional'])
  ->name('baja.institucional.reporte');

//AVISO DE BAJA INSTITUCIONAL

Route::get('vigencia/AvisosBajaInstitucional/index', function () {
  return view('Vigencia.AvisosBajaInstitucional.index');
})
  ->name('vigencia.avisosbajainstitucional.index')->middleware(['auth', 'verified']);

//Instituciones Convenio

Route::get('vigencia/institucion/index', function () {
  return view('Vigencia.Instituciones.index');
})
  ->name('vigencia.institucion.index')->middleware(['auth', 'verified']);

Route::get('vigencia/institucion/create', function () {
  return view('Vigencia.Instituciones.create');
})
  ->name('vigencia.institucion.create')->middleware(['auth', 'verified']);

Route::get('vigencia/institucion/edit/{id}', function ($id) {
  $id = $id;
  return view('Vigencia.Instituciones.edit', compact('id'));
})
  ->name('vigencia.institucion.edit')->middleware(['auth', 'verified']);

//SEGUROS DEL INTERIOR

Route::get('vigencia/SegurosInterior/index', function () {
  return view('Vigencia.SegurosInterior.index');
})
  ->name('vigencia.segurosinterior.index')->middleware(['auth', 'verified']);

Route::get('vigencia/SegurosInterior/edit/{id}', function ($id) {
  $id = $id;
  return view('Vigencia.SegurosInterior.edit', compact('id'));
})
  ->name('vigencia.segurosinterior.edit')->middleware(['auth', 'verified']);

//SOLICITUDES DE PRESTACION MEDICA

Route::get('vigencia/SolPrestacionesMedicas/index', function () {
  return view('Vigencia.SolicitudesPrestacionesMedicas.index');
})
  ->name('vigencia.solprestacionesmedicas.index')->middleware(['auth', 'verified']);

Route::get('vigencia/SolPrestacionesMedicas/create/{id}', function ($id) {
  $id = $id;
  return view('Vigencia.SolicitudesPrestacionesMedicas.create', compact('id'));
})
  ->name('vigencia.SolicitudesPrestacionesMedicas.create')->middleware(['auth', 'verified']);


Route::get('vigencia/SolPrestacionesMedicas/edit/{id}', function ($id) {
  $id = $id;
  return view('Vigencia.SolicitudesPrestacionesMedicas.edit', compact('id'));
})
  ->name('vigencia.SolicitudesPrestacionesMedicas.edit')->middleware(['auth', 'verified']);

Route::get('vigencia/SolPrestacionesMedicas/prestacion_medicaPdf/{row}', [PrestacionMedicaPdf::class, 'prestracion_medica_pdf'])
  ->name('pdf.generatePrestacionMedicaPdf');


Route::get('vigencia/SolPrestacionesMedicas/firma/{id}', function ($id) {
  $id = $id;
  return view('Vigencia.SolicitudesPrestacionesMedicas.firma', compact('id'));
})
  ->name('vigencia.SolicitudesPrestacionesMedicas.firma')->middleware(['auth', 'verified']);

//Reporte PDF prestacion medica
Route::get('/vigencia/PrestacionMedica/{fechaInicio}/{fechaFin}', [pdfReporteController::class, 'generarPdfPrestacionMedica'])
  ->name('prestacion.medica.reporte');

//PARA DESCARGAR EL PDF DESDE EL FTP
Route::get('/descargar-archivo/{nombrePdf}/{codCite}/{gestionCreado}', [DescargarSolicitud::class, 'descargarArchivo'])
  ->name('archivo.descargar');


// BAJAS MEDICAS
Route::get('vigencia/BajasMedicas/index', function () {
  return view('Vigencia.BajasMedicas.index');
})
  ->name('vigencia.bajasmedicas.index')->middleware(['auth', 'verified']);

//Route::get('vigencia/BajasMedicasListaAfiliados/lista',function(){return view('Vigencia.BajasMedicas.lista');})
//->name('vigencia.bajasmedicaslistaafiliados.lista')->middleware(['auth', 'verified']);

Route::get('vigencia/BajasMedicasListaAfiliados/lista/{id}', function ($id) {
  $id = $id;
  return view('Vigencia.BajasMedicas.lista', compact('id'));
})
  ->name('vigencia.BajasMedicas.lista')->middleware(['auth', 'verified']);

//Reporte PDF prestacion medica
Route::get('/vigencia/BajasMedicas/{fechaInicio}/{fechaFin}', [pdfReporteController::class, 'generarPdfBajaMedica'])
  ->name('bajas.medicas.reporte');

//ORDENES DE SERVICIO
Route::get('vigencia/OrdenesServicio/index', function () {
  return view('Vigencia.OrdenesServicio.index');
})
  ->name('vigencia.ordenesservicio.index')->middleware(['auth', 'verified']);

Route::get('vigencia/OrdenesServicio/create/{id}', function ($id) {
  $id = $id;
  return view('Vigencia.OrdenesServicio.create', compact('id'));
})
  ->name('vigencia.OrdenesServicio.create')->middleware(['auth', 'verified']);

//Reporte PDF orden de servicio
Route::get('/vigencia/OrdenesServicio/{fechaInicio}/{fechaFin}', [pdfReporteController::class, 'generarPDFOrdenServicio'])
  ->name('ordenServicio.reporte');


//convenios
Route::get('vigencia/Convenios/Interior/index', function () {
  return view('Vigencia.Convenios.Interior.index');
})
  ->name('vigencia.convenios.interior.index')->middleware(['auth', 'verified']);

Route::get('vigencia/Convenios/Interior/create', function () {
  return view('Vigencia.Convenios.Interior.create');
})
  ->name('vigencia.convenios.interior.create')->middleware(['auth', 'verified']);

Route::get('vigencia/Convenios/Interior/edit/{id}', function ($id) {
  $id = $id;

  return view('Vigencia.Convenios.Interior.edit', compact('id'));
})
  ->name('vigencia.convenios.interior.editar')->middleware(['auth', 'verified']);

//Reporte PDF convenio interior
Route::get('/vigencia/ConvenioInterior/{fechaInicio}/{fechaFin}', [PdfReporteController::class, 'generarPdfConvenioInterior'])
  ->name('convenio.interior.reporte');


Route::get('vigencia/Convenios/Emergencias/index', function () {
  return view('Vigencia.Convenios.Emergencias.index');
})
  ->name('vigencia.convenios.emergencias.index')->middleware(['auth', 'verified']);

Route::get('vigencia/Convenios/Emergencias/create', function () {
  return view('Vigencia.Convenios.Emergencias.create');
})->name('vigencia.convenios.emergencias.create');

Route::get(
  'vigencia/Convenios/Emergencias/pdfConvenioEmergencia/{row}',
  [ConveniosEmergenciaPdf::class, 'ConvenioEmergenciaPdf']
)->name('pdf.generateConvenioEmergenciaPdf');

//Reporte PDF convenio emergencia
Route::get('/vigencia/ConvenioEmergencia/{fechaInicio}/{fechaFin}', [pdfReporteController::class, 'generarPdfConvenioEmergencia'])
  ->name('convenio.emergencia.reporte');

//PARA LA POBLACION ASEGURADA
Route::get('vigencia/PoblacionAsegurada/index', function () {
  return view('Vigencia.PoblacionAsegurada.index');
})
  ->name('vigencia.poblacionasegurada.index')->middleware(['auth', 'verified']);


//PARA LA POBLACION ASEGURADA 

Route::get('vigencia/PoblacionAsegurada/busqueda', function () {
  return view('Vigencia.PoblacionAsegurada.busqueda');
})
  ->name('vigencia.poblacionasegurada.busqueda')->middleware(['auth', 'verified']);


//EXTERNOS

Route::get('vigencia/SolPrestacionesMedicas/Externos/index', function () {
  return view('Vigencia.Externos.SolicitudPrestacionMedica.index');
})
  ->name('vigencia.externos.solicitudprestacionmedica.index')->middleware(['auth', 'verified']);

//CARNETS
Route::get('vigencia/Convenios/Interior/carnetInteriorPdf/{row}', [Carnetizacion::class, 'carnet_convenio_interior_pdf'])
  ->name('pdf.generateConvenioInterior');

//PDF
Route::get('vigencia/Convenios/Interior/convenioPdf/{row}', [convenioPDF::class, 'pdf_convenio'])
  ->name('pdf.generateConvenioInterior.doc');
// REPORTES PDF
// vigencia/PoblacionAsegurada/reportesPdf
Route::get('vigencia/PoblacionAsegurada/reportesPdf/{row}', [ReportesVigenciaPDF::class, 'reportesvigenciapdf'])
  ->name('pdf.generatePoblacionAsegurada.doc');

//PREAFILIACION
Route::get('vigencia/Preafiliacion/index', function () {
  return view('Vigencia.Preafiliacion.index');
})
  ->name('vigencia.preafiliacion.index')->middleware(['auth', 'verified']);

Route::get('vigencia/Preafiliacion/create', function () {
  return view('Vigencia.Preafiliacion.create');
})
  ->name('vigencia.preafiliacion.create')->middleware(['auth', 'verified']);

Route::get(
  'vigencia/Preafiliacion/pdfPreafiliacion/{row}',
  [PreafiliacionPdf::class, 'preafiliacionPdf']
)->name('pdf.generatePreafiliacionPdf');

//RUTA PDF TAPA HISTORIA CLÍNICA
Route::get('/vigencia/pdf_tapa_hc/{id_persona}', [TapaHistoriaClinica::class, 'pdfTapaHistoriaClinica'])
  ->name('vigencia.pdf_tapa_hc.reporte')
  ->middleware('signed');
