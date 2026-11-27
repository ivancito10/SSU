<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\pdfController;
use App\Http\Controllers\Afiliacion\PDFCertificadoAfiliacion;
use App\Http\Controllers\Afiliacion\DescargaController;
use App\Http\Controllers\Afiliacion\AfiliadoHistorialPdf;
use App\Http\Controllers\Afiliacion\Carnetizacion;
use App\Http\Controllers\Afiliacion\DescargarDocumentosAfiliacion;
use App\Http\Controllers\Afiliacion\ReportesPdfController;
use App\Models\Afiliacion\Estudiante;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\Titular;
use Illuminate\Http\Request;
use App\Http\Controllers\Afiliacion\SsueCarnet;
use App\Http\Controllers\Afiliacion\DocumentosSSUEController;
use App\Http\Controllers\Afiliacion\ReportesEstudiantesController;

//aqui deberia estar


Route::get('afiliacion/AsignacionCodigo/index', function () {
    return view('Afiliacion.AsignacionCodigo.index');
})
    ->name('afiliacion.asignacioncodigo.index')->middleware(['auth', 'verified']);

// ------TITULARES ----------
Route::get('afiliacion/Titulares/index', function () {
    return view('Afiliacion.Titulares.index');
})
    ->name('afiliacion.titulares.index')->middleware(['auth', 'verified']);

Route::get('afiliacion/Titulares/create/{id}', function ($id) {
    $id = $id;
    return view('Afiliacion.Titulares.create', compact('id'));
})
    ->name('afiliacion.titulares.create')->middleware(['auth', 'verified']);


Route::get('afiliacion/Titulares/Reafiliacion/create/{id}', function ($id) {
    $id = $id;
    return view('Afiliacion.Titulares.createReafiliacion', compact('id'));
})
    ->name('afiliacion.titulares.create.reafiliacion')->middleware(['auth', 'verified']);


Route::get('afiliacion/prueba/qr', [pdfController::class, 'qrcode'])
    ->name('afiliacion.prueba.pdf')->middleware(['auth', 'verified']);

// ------BENEFICIARIOS ----------
Route::get('afiliacion/Beneficiarios/index', function () {
    return view('Afiliacion.Beneficiarios.index');
})
    ->name('afiliacion.beneficiario.index')->middleware(['auth', 'verified']);

Route::get('afiliacion/Beneficiarios/create/{id}', function ($id) {
    $id = $id;
    return view('Afiliacion.Beneficiarios.create', compact('id'));
})
    ->name('afiliacion.beneficiario.create-beneficiario')->middleware(['auth', 'verified']);

Route::get('afiliacion/Beneficiarios/Reafiliacion/create/{id}', function ($id) {
    $id = $id;
    return view('Afiliacion.Beneficiarios.createReafiliacionBen', compact('id'));
})
    ->name('afiliacion.beneficiarios.create.reafiliacion')->middleware(['auth', 'verified']);


Route::get('afiliacion/Beneficiarios/edit/{id}', function ($id) {
    $id = $id;
    return view('Afiliacion.Beneficiarios.edit', compact('id'));
})
    ->name('afiliacion.beneficiarios.edit-afiliacion')->middleware(['auth', 'verified']);

// ------AFILIACION PDF CERTIFICADOS POR TITULAR / GRUPO FAMILIAR ----------
// vista
Route::get('afiliacion/certificados/afiliacion_titular/{row}', [PDFCertificadoAfiliacion::class, 'pdf_certificado_titular'])->name('pdf.generateAfiliacionTitPdf')->middleware(['auth', 'verified']);
// descarga
Route::get('afiliacion/certificados/titular_download/{row}', [PDFCertificadoAfiliacion::class, 'pdf_titular_download'])->name('pdf.generateTitPdfDownload')
    ->middleware(['auth', 'verified']);


// ------AFILIACION PDF CERTIFICADOS POR BENEFICIARIO / GRUPO FAMILIAR ----------
// vista
Route::get('afiliacion/certificados/afiliacion_beneficiario/{row}', [PDFCertificadoAfiliacion::class, 'pdf_certificado_beneficiario'])->name('pdf.generateAfiliacionBenPdf')->middleware(['auth', 'verified']);
// descarga
Route::get('afiliacion/certificados/beneficiario_download/{row}', [PDFCertificadoAfiliacion::class, 'pdf_beneficiario_download'])->name('pdf.generateBenPdfDownload')->middleware(['auth', 'verified']);


// ------CERTIFICADOS AFILIACION  ----------

Route::get('afiliacion/Certificados/AfiliacionSSU/index', function () {
    return view('Afiliacion.Certificados.AfiliacionSSU.index');
})
    ->name('afiliacion.certificados.afiliacion-ssu.index')->middleware(['auth', 'verified']);

Route::get('afiliacion/Certificados/Afiliado/index', function () {
    return view('Afiliacion.Certificados.Afiliado.index');
})
    ->name('afiliacion.certificados.afiliado.index')->middleware(['auth', 'verified']);


//Pdf certificado de afiliacion
Route::get('afiliacion/certificados/afiliacionssu/{rowId}', [PDFCertificadoAfiliacion::class, 'generarPDFCertificado'])->name('pdf.generateCertificado')->middleware(['auth', 'verified']);

// ------EXAMEN PREOCUPACIONAL ----------

Route::get('afiliacion/ExamenPreocupacional/Instituciones/index', function () {
    return view('Afiliacion.ExamenPreocupacional.Instituciones.index');
})
    ->name('afiliacion.examen-preocupacional.instituciones.index')->middleware(['auth', 'verified']);

Route::get('afiliacion/ExamenPreocupacional/Instituciones/create', function () {
    return view('Afiliacion.ExamenPreocupacional.Instituciones.create');
})
    ->name('afiliacion.examen-preocupacional.instituciones.create')->middleware(['auth', 'verified']);

// ------EXAMEN MEDICO ----------
Route::get('afiliacion/ExamenMedico/preafiliacion/index', function () {
    return view('Afiliacion.ExamenMedico.index');
})
    ->name('afiliacion.examen-medico.index')->middleware(['auth', 'verified']);

Route::get('afiliacion/ExamenMedico/create/{id}', function ($id) {
    $id = $id;
    return view('Afiliacion.ExamenMedico.create', compact('id'));
})
    ->name('afiliacion.ExamenMedico.create-examenMedico')->middleware(['auth', 'verified']);


// ------AFILIACION SSUE----------
Route::get('afiliacion/SSUE/afiliacion', function () {
    $id_persona = auth()->user()->id_persona;

    $estudiante = Estudiante::where('id_persona', $id_persona)
        ->where('estado', true)
        ->orderby('id', 'desc')
        ->first();

    $beneficiario = Beneficiario::where('id_persona', $id_persona)
        //->where('vigencia','=','VIGENTE')
        ->orderby('id', 'desc')
        ->first();

    return view('Afiliacion.Ssue.afiliacion', compact('estudiante', 'beneficiario'));
})
    ->name('afiliacion.ssue.afiliacion')->middleware(['auth', 'verified']);

// ------SEGUIMIENTO SSUE----------
Route::get('afiliacion/SSUE/seguimiento', function () {
    $id_persona = auth()->user()->id_persona;
    $estudiante = Estudiante::where('id_persona', $id_persona)
        ->where('estado', true)
        ->orderby('id', 'desc')
        ->first();
    $beneficiario = Beneficiario::where('id_persona', $id_persona)
        ->where('vigencia', 'VIGENTE')
        ->where('estado', true)
        ->orderby('id', 'desc')
        ->first();
    $titular = Titular::where('id_persona', $id_persona)
        ->where('estado_vigencia', 'VIGENTE')
        ->where('estado', true)
        ->orderby('id', 'desc')
        ->first();

    return view('Afiliacion.Ssue.seguimiento', compact('estudiante', 'beneficiario', 'titular'));
})
    ->name('afiliacion.ssue.seguimiento')->middleware(['auth', 'verified']);

// ------SEGUIMIENTO SSUE----------
Route::get('afiliacion/Estudiantes/index', function () {
    return view('Afiliacion.Ssue.index');
})
    ->name('afiliacion.ssue.index')->middleware(['auth', 'verified']);

// -------REGISTRO MANUAL SSUE -----------

Route::get('afiliacion/Estudiantes/create', function () {
    return view('Afiliacion.Ssue.create');
})
    ->name('afiliacion.ssue.create')->middleware(['auth', 'verified']);

//------lista de afiliados------
Route::get('afiliacion/afiliacion/index', function () {
    return view('Afiliacion.Afiliacion.index');
})
    ->name('afiliacion.afiliacion.index')->middleware(['auth', 'verified']);

//------ ruta para editar titular
Route::get('afiliacion/Titulares/afiliacion/edit/{id}', function ($id) {
    $id = $id;
    return view('Afiliacion.Titulares.edit', compact('id'));
})
    ->name('afiliacion.titulares.edit-afiliacion')->middleware(['auth', 'verified']);

// ruta para certificado register 
Route::get('afiliacion/Certificados/afiliado/create', function () {
    return view('Afiliacion.Certificados.Afiliado.create');
})
    ->name('afiliacion.certificados.afiliado.create')->middleware(['auth', 'verified']);


// ------AFILIADO HISTORIAL PDF ----------
// vista
Route::get('afiliacion/afiliado/afiliado_historial_pdf/{row}', [AfiliadoHistorialPdf::class, 'afiliado_historial_pdf'])
    ->name('pdf.generateAfiliadoHistorial')->middleware(['auth', 'verified']);

// ------FICHA AFILIACION PDF ----------
// vista
Route::get('afiliacion/afiliado/ficha_afiliacion_pdf/{row}', [AfiliadoHistorialPdf::class, 'ficha_afiliacion_pdf'])
    ->name('pdf.generateFichaAfiliacion')->middleware(['auth', 'verified']);

// ------AFILIADO REPORTES PDF ----------
Route::get('afiliacion/afiliado/reporte_titular/{fecha_inicio}/{fecha_fin}/{tipo_reporte}', [ReportesPdfController::class, 'reporteTitular'])
    ->name('pdf.generateReporteTitular')->middleware(['auth', 'verified']);

// ------------ CARNETS ------------------
Route::get('afiliacion/titular/carnet_pdf/{row}', [Carnetizacion::class, 'carnet_titular_pdf'])->name('pdf.generateCarnetTitular')
    ->middleware(['auth', 'verified']);
Route::get('afiliacion/beneficiario/carnet_pdf/{row}', [Carnetizacion::class, 'carnet_beneficiario_pdf'])->name('pdf.generateCarnetBeneficiario')
    ->middleware(['auth', 'verified']);
Route::get('afiliacion/estudiante/carnet_pdf/{row}', [Carnetizacion::class, 'carnet_estudiante_pdf'])->name('pdf.generateCarnetEstudiante')
    ->middleware(['auth', 'verified']);

// -------------- Ruta para manuales de usuario -----
Route::get('afiliacion/Manuales/Usuarios/index', function () {
    return view('Afiliacion.Manuales.index');
})->name('Afiliacion.manuales.usuario.index')->middleware(['auth', 'verified']);

// -------------- Ruta para credenciales de usuarios -----
Route::get('afiliacion/Estudiantes/Usuarios/index', function () {
    return view('Afiliacion.Ssue.usuarios');
})->name('Afiliacion.Estudiantes.Usuario.Index')->middleware(['auth', 'verified']);

// -------------- Ruta para registrar estudiantes -----
Route::get('afiliacion/Estudiantes/Usuarios/register', function () {
    return view('Afiliacion.Ssue.usuarios_register');
})->name('Afiliacion.Estudiantes.Usuario.register')->middleware(['auth', 'verified']);

//------ ruta para visualizar el carnet figital  del estudiante
Route::get('afiliacion/generar_carnet_ssue/umsa/{id}', function ($id) {
    $id = $id;
    return view('Afiliacion.Ssue.ssue_carnet', compact('id'));
})
    ->name('afiliacion.generar_carnet_ssue.umsa')->middleware(['auth', 'verified']);



Route::get('afiliacion/documentos/formulario101/{id}', [DocumentosSSUEController::class, 'obtenerFormulario101'])->name('pdf.documento101')
    ->middleware(['auth', 'verified']);



//RUTAS PARA LA DESCARGA DE LOS ARCHIVOS SUBIDOS AL FTP
//PARA DESCARGAR EL PDF DESDE EL FTP
Route::get('/descargar-archivo-ci/{idEstudiante}/{nombreEstudiante}/{carnetEstudiante}', [DescargarDocumentosAfiliacion::class, 'descargarArchivoCi'])
    ->name('archivo.descargarci');

Route::get('/descargar-archivo-matricula/{idEstudiante}/{nombreEstudiante}/{carnetEstudiante}', [DescargarDocumentosAfiliacion::class, 'descargarArchivoMatricula'])
    ->name('archivo.descargarmatricula');

Route::get('/descargar-archivo-boleta/{idEstudiante}/{nombreEstudiante}/{carnetEstudiante}', [DescargarDocumentosAfiliacion::class, 'descargarArchivoBoleta'])
    ->name('archivo.descargarboleta');

Route::get('/descargar-archivo-formulario/{idEstudiante}/{nombreEstudiante}/{carnetEstudiante}', [DescargarDocumentosAfiliacion::class, 'descargarArchivoFormularioNoAfiliacion'])
    ->name('archivo.descargarformnoafiliacion');

Route::get('/descargar-archivo-certificado-cajas/{idEstudiante}/{nombreEstudiante}/{carnetEstudiante}', [DescargarDocumentosAfiliacion::class, 'descargarArchivoCertificadoCajas'])
    ->name('archivo.descargarcertificadocajas');

Route::get('/descargar-archivo-tipo-sangre/{idEstudiante}/{nombreEstudiante}/{carnetEstudiante}', [DescargarDocumentosAfiliacion::class, 'descargarArchivoTipoSangre'])
    ->name('archivo.descargartiposangre');
// prueba para ver archivos en un iframe sin base64 jji 07/05
//Route::get('/ver-pdf/{nombre}', [DescargarDocumentosAfiliacion::class, 'ver'])
//    ->name('ver.archivos');

//================== PARA EL APARTADO DE LOS REPORTES ==================----
// ---- titulares
Route::get(
    'afiliacion/reportes/{tipo_reporte}/{id_institucion}/{fecha_inicio}/{fecha_fin}',
    [ReportesPdfController::class, 'reporteTitular']
)
    ->name('pdf.reportesTitular')
    ->middleware(['auth', 'verified']);
// ----- beneficiarios
Route::get(
    'afiliacion/reportes/{tipo_reporte}/{id_institucion}/{id_tipo_asegurado}/{fecha_inicio}/{fecha_fin}',
    [ReportesPdfController::class, 'reporteBeneficiario']
)
    ->name('pdf.reportesBeneficiarios')
    ->middleware(['auth', 'verified']);
// --- reportes de estudiantes -----
Route::get(
    'afiliacion/afiliado/reporte_estudiante',
    [ReportesEstudiantesController::class, 'reporteMensualUmsa']
)->name('pdf.generateReporteEstudianteUmsa')->middleware(['auth', 'verified']);

Route::get(
    'afiliacion/afiliado/reporte_estudiante/{fecha_inicio}/{fecha_fin}/{tipo_reporte}/{estado}',
    [ReportesEstudiantesController::class, 'reportesEstudiantes']
)->name('reporte.generateReporteEstudiantes')->middleware(['auth', 'verified']);

//--- apartir de aqui no se usara, borrar jiji

Route::get(
    'afiliacion/afiliado/reporte_estudiante/{fecha_inicio}/{fecha_fin}/{tipo_reporte}',
    [ReportesEstudiantesController::class, 'reporteCarnetEstudiate']
)->name('pdf.generateReporteEstudianteCarnet')->middleware(['auth', 'verified']);


Route::get(
    'afiliacion/afiliado/reporte_estudiante_excel',
    [ReportesEstudiantesController::class, 'reporteMensualUmsaExcel']
)
    ->name('pdf.generateReporteEstudianteUmsaExcel')->middleware(['auth', 'verified']);
// ===============  RUTAS GENERALES ========================
// ------DESCAGARGA DE ARCHIVOS----------
Route::get('afiliacion/downloadFoto/{foto}',  [DescargaController::class, 'downloadFoto'])
    ->name('descarga.downloadFoto')
    ->middleware(['auth', 'verified']);

Route::get('afiliacion/{ruta}/downloadFile/{row}',  [DescargaController::class, 'downloadFilePersonal'])
    ->name('descarga.downloadFile')
    ->middleware(['auth', 'verified']);
// -----  VER FILE DIGITAL ------
Route::get('afiliacion/{ruta}/verFile/{row}',  [DescargaController::class, 'verFilePersonal'])
    ->name('ver.File')
    ->middleware(['auth', 'verified']);
