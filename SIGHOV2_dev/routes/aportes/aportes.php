<?php

use Illuminate\Support\Facades\Route;
use App\Models\Aportes\Ufv;
use App\Models\User;
use App\Models\Afiliacion\CodigoInstitucionSsu;
use App\Http\Controllers\Aportes\InstitucionController;
use Illuminate\Http\Request;
use App\Http\Controllers\AportesPruebasController;
use App\Http\Controllers\pdfController;
use App\Http\Controllers\pdfCopyController;
use App\Http\Controllers\Aportes\LiquidacionAsussMinisterioController;
use App\Http\Controllers\Aportes\ExtractosAportantesController;
use App\Http\Controllers\Aportes\ExtractosInstitucionesController;
use App\Http\Controllers\Aportes\BajasMedicasController;
use App\Http\Controllers\Aportes\CertificadoController;
use App\Http\Controllers\Aportes\planillaExternosPdf;
use App\Http\Controllers\Aportes\AvisoBajaController;
use App\Http\Controllers\Aportes\ReporteAvisoBaja;

//aqui deberia estar
Route::get('aportes/Ufv/index', function () {
    return view('Aportes.Ufv.index');
})->name('aportes.ufv.index')->middleware(['auth']);;
Route::get('aportes/TasaInteres/index', function () {
    return view('Aportes.TasaInteres.index');
})->name('aportes.tasainteres.index')->middleware(['auth', 'verified']);
Route::get('aportes/Instituciones/index', function () {
    return view('Aportes.Institucion.index');
})->name('aportes.instituciones.index')->middleware(['auth', 'verified']);

Route::get('aportes/Instituciones/register', function () {
    return view('Aportes.Institucion.create');
})->name('aportes.institucion.register');

Route::get('aportes/AportantesVoluntarios/index', function () {

    return view('Aportes.AportantesVoluntarios.index');
})->name('aportes.aportantesinstitucionales.index')->middleware(['auth', 'verified']);

Route::get('aportes/AportesVoluntarios/index', function () {

    return view('Aportes.AportesVoluntarios.index');
})->name('aportes.aportevoluntario.index')->middleware(['auth', 'verified']);

Route::get('aportes/AportesVoluntarios/create/{id}', function ($id) {

    return view('Aportes.AportesVoluntarios.create', compact('id'));
})->name('aportes.aportevoluntario.create')->middleware(['auth', 'verified']);


Route::get(
    'aportes/AportesInstitucionales/index',
    function () {

        return view('Aportes.AportesInstitucionales.index');
    }

)
    ->name('aportes.aportesinstitucionales.index')->middleware(['auth', 'verified']);

Route::get('aportes/AportesInstitucionales/Comprobante/PreLiquidacion/{id}', function ($id) {
    $id = $id;
    return view('Aportes.AportesInstitucionales.Comprobante.PreLiquidacion', compact('id'));
})->name('aportes.aportesinstitucionales.comprobante.preliquidacion')->middleware(['auth', 'verified']);

Route::get('aportes/Instituciones/pruebas', function () {
    $ci = '0323201599';
    $zonas = Zona::get();
    $clase_aportantes = ClaseAportante::get();
    $cod_institucioness = CodigoInstitucionSsu::get();
    dd($zonas, $clase_aportantes, $cod_institucioness);

    return view('Aportes.Institucion.index');
})->name('aportes.instituciones.pruebas')->middleware(['auth', 'verified']);

Route::get('/pruebas/aportes', [AportesPruebasController::class, 'index']);
//externos
Route::get('aportes/Instituciones/Externos/index', function () {
    return view('Aportes.Externos.Instituciones.index');
})->name('aportes.institucionesExternos.index')->middleware(['auth', 'verified']);

Route::get('aportes/Instituciones/Externos/create', function () {
    return view('Aportes.Externos.Instituciones.create');
})->name('aportes.institucionesExternos.create')->middleware(['auth', 'verified']);

Route::get('aportes/AportesInstituciones/Externos/index', function () {
    return view('Aportes.Externos.Aportes.index');
})->name('aportes.aportesinstitucionalesExternos.index')->middleware(['auth', 'verified']);

//bajas medicas
Route::get('aportes/AportesInstituciones/BajasMedicas/index/{id}', function ($id) {
    return view('Aportes.BajasMedicas.create', compact('id'));
})->name('aportes.BajasMedicas.index')->middleware(['auth', 'verified']);

//pdf comprob instituciones
Route::get('aportes/pdf/{rowId}', [pdfController::class, 'generatePDF'])->name('pdf.generate');
Route::get('aportes/pdfcopia/{rowId}', [pdfCopyController::class, 'generatecopiaPDF'])->name('pdf.generatecopia')->middleware(['auth', 'verified']);
//pdf comprob voluntarios
Route::get('aportes/pdf/voluntarios/{rowId}', [pdfController::class, 'generatePDFVoluntario'])->name('pdf.generate.voluntario');
Route::get('aportes/pdfcopia/voluntarios/{rowId}', [pdfCopyController::class, 'generatecopiaPDFVoluntario'])->name('pdf.generatecopia.voluntario')->middleware(['auth', 'verified']);

//  pdf certificadon afiliacion / no afiliacion
Route::get('aportes/pdf_certificado/{id}', [CertificadoController::class, 'pdf_certificado'])->name('pdf.generate.contrato');
Route::get('aportes/pdf_certificado_vista/{id}', [CertificadoController::class, 'pdf_certificado_vista'])->name('pdf.generatevista.contrato')->middleware(['auth', 'verified']);


Route::get('aportes/pdfcopiaExt/{rowId}', [pdfCopyController::class, 'generatecopiaPDF'])->name('pdf.generatecopiaext.descargar')->middleware(['auth', 'verified']);

// liquidaciones
Route::get('aportes/Liquidaciones/Asuss/index', function () {
    return view('Aportes.Liquidaciones.Asuss.index');
})->name('aportes.liquidacion.asuss')->middleware(['auth', 'verified']);

Route::get('aportes/Liquidaciones/Ministerio/index', function () {
    return view('Aportes.Liquidaciones.Ministerio.index');
})->name('aportes.liquidacion.ministerio')->middleware(['auth', 'verified']);

Route::get('aportes/Liquidaciones/Ministerio/Pdf/{id}/{mes}/{gestion}/{fechaLiquidacion}', [LiquidacionAsussMinisterioController::class, 'LquidacionPDFMinisterio'])->name('aportes.liquidacion.ministerio.pdf')->middleware(['auth', 'verified']);

Route::get('aportes/Liquidaciones/Asuss/Pdf/{id}/{mes}/{gestion}/{fechaLiquidacion}', [LiquidacionAsussMinisterioController::class, 'LquidacionPDFAsuss'])->name('aportes.liquidacion.asuss.pdf')->middleware(['auth', 'verified']);

//Extracto
Route::get('aportes/Extractos/Personas/index', function () {
    return view('Aportes.Extractos.Personas.index');
})->name('aportes.extractos.personas.index')->middleware(['auth', 'verified']);

Route::get('aportes/Extractos/Instituciones/index', function () {
    return view('Aportes.Extractos.Instituciones.index');
})->name('aportes.extractos.Instituciones.index')->middleware(['auth', 'verified']);

Route::get('aportes/ExtractoAportantes/Pdf/{id}/{fecha_inicial}/{fecha_final}', [ExtractosAportantesController::class, 'ExtractoAportante'])->name('aportes.extractoaportantes.pdf')->middleware(['auth', 'verified']);
Route::get('aportes/ExtractoInstituciones/Pdf/{id}/{fecha_inicial}/{fecha_final}', [ExtractosInstitucionesController::class, 'ExtractoInstitucion'])->name('aportes.extractoinstituciones.pdf')->middleware(['auth', 'verified']);
Route::get('aportes/BajasMedicas/Pdf/{id}', [BajasMedicasController::class, 'PDfBajasMedicas'])->name('aportes.bajasmedicas.pdf')->middleware(['auth', 'verified']);

Route::get('aportes/Instituciones/Certificados/index', function () {
    return view('Aportes.Institucion.Certificados.index');
})->name('aportes.instituciones.certificados.index')->middleware(['auth', 'verified']);

//------ ruta para editar institucion
Route::get('aportes/Institucion/edit/{id}', function ($id) {
    $id = $id;
    return view('Aportes.Institucion.edit', compact('id'));
})
    ->name('aportes.institucion.edit-institucion')->middleware(['auth', 'verified']);

//------ ruta para editar institucion externa
Route::get('aportes/Externos/Institucion/edit/{id}', function ($id) {
    $id = $id;
    return view('Aportes.Externos.Instituciones.edit', compact('id'));
})
    ->name('aportes.externos.institucion.edit-externo')->middleware(['auth', 'verified']);

//------ ruta para editar institucion externa
Route::get('aportes/Externos/Institucion/edit_rep_legal/{id}', function ($id) {
    $id = $id;

    return view('Aportes.Externos.Instituciones.edit_rep_legal', compact('id'));
})
    ->name('aportes.externos.institucion.edit-rep-legal-externo')->middleware(['auth', 'verified']);

//------ ruta para editar representante externo
Route::get('aportes/institucion/edit_rep_legal/{id}', function ($id) {
    $id = $id;
    return view('Aportes.Institucion.edit_rep_legal', compact('id'));
})
    ->name('aportes.institucion.edit-rep-legal')->middleware(['auth', 'verified']);

// ruta para la planilla Excel en pdf
Route::get('aportes/AportesInstituciones/Externos/aportes/pdfplanilla/{rowId}', [planillaExternosPdf::class, 'generarPlanillasPdf'])
    ->name('pdf.planillaGenerate')
    ->middleware(['auth', 'verified']);

// pra la planilla de no externos
Route::get('aportes/AportesInstitucionales/Externos/aportes/pdfplanilla/{rowId}', [planillaExternosPdf::class, 'generarPlanillasPdf'])
    ->name('pdf.planillaGenerate_2')
    ->middleware(['auth', 'verified']);

// ruta para preliquidacion externos
Route::get('aportes/externos/preliquidacion/{id}', function ($id) {
    $id = $id;
    return view('Aportes.Externos.Aportes.preliquidacion', compact('id'));
})
    ->name('aportes.externos.aportes.preliquidacion')->middleware(['auth', 'verified']);

// reporte excel externos aviso baja
Route::get(
    'aportes/externos/excel_aviso_baja/{fecha_inicio}/{fecha_fin}',
    [ReporteAvisoBaja::class, 'generarReporteAvisoBaja']
)->name('aportes.aviso_baja.excel')->middleware(['auth']);

// ruta para manuales de usuario
Route::get('aportes/Manuales/Usuarios/index', function () {
    return view('Aportes.Manuales.index');
})->name('aportes.manuales.usuario.index')->middleware(['auth', 'verified']);

// RUTA DE AVISO DE BAJA
Route::get('aportes/AvisoBajaInstitucional/index', function () {
    return view('Aportes.AvisoBajaInstitucional.index');
})->name('aportes.aviso-baja-institucional.index')->middleware(['auth', 'verified']);

// RUTA PDF AVISO DE BAJA MEDICA 
//pdf comprob instituciones
Route::get('aportes/externos/pdf_aviso_baja/{rowId}', [AvisoBajaController::class, 'pdf_aviso_baja'])->name('pdf.generate_aviso_baja');
//------ ruta para registrar forma de pago externo
Route::get('aportes/aportes/forma_pago_ext/{id}', function ($id) {
    $id = $id;
    return view('Aportes.Externos.Aportes.formapago', compact('id'));
})
    ->name('aportes.externos.forma-pago-ext')->middleware(['auth', 'verified']);

//------ ruta para editar contrato voluntario
Route::get('aportes/contrato_voluntario/edit_contrato/{id}', function ($id) {
    $id = $id;
    return view('Aportes.AportantesVoluntarios.edit', compact('id'));
})
    ->name('aportes.contrato_voluntario.edit-contrato')->middleware(['auth', 'verified']);

//------ ruta para aportes principal pre liquidacion
Route::get('aportes/aporte_institucional/preliquidacion/{id}', function ($id) {
    $id = $id;
    return view('Aportes.AportesInstitucionales.Comprobante.PreLiquidacion', compact('id'));
})
    ->name('aportes.aporte_institucional.preliquidacion')->middleware(['auth', 'verified']);


//------ ruta para aportes principal forma de pago
Route::get('aportes/aporte_institucional/forma_pago/{id}', function ($id) {
    $id = $id;
    return view('Aportes.AportesInstitucionales.Comprobante.FormaPago', compact('id'));
})
    ->name('aportes.aporte_institucional.forma_pago')->middleware(['auth', 'verified']);

// -------------- Ruta para manuales de usuario -----
//Route::get('aportes/Manuales/Usuarios/index', function () {
//    return view('Aportes.Manuales.index');
//})->name('Afiliacion.manuales.usuario.index')->middleware(['auth', 'verified']);

//EXCEL DE GENERAR REPORTE DE AFILIADOS DESDE INSTITUCION
Route::get('aportes/instituciones/reporte_excel/{id_institucion}/{id_mes}/{gestion}', [InstitucionController::class, 'exportarExcelAfiliadosInstitucion'])
    ->name('aportes.afiliadosPorInstitucion.excel')
    ->middleware(['auth', 'verified']);
