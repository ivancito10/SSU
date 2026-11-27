<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\pdfController;
use App\Http\Controllers\Afiliacion\PDFCertificadoAfiliacion;
use App\Http\Controllers\Afiliacion\DescargaController;
use App\Http\Controllers\Afiliacion\AfiliadoHistorialPdf;
use App\Http\Controllers\Afiliacion\Carnetizacion;
use App\Http\Controllers\Afiliacion\ReportesPdfController;
use App\Http\Controllers\Plataforma\PrintController;
use App\Http\Controllers\Publicos\CertificadoNoAfiliacionController;
use App\Models\Afiliacion\Estudiante;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\Titular;
use Illuminate\Http\Request;
use App\Http\Controllers\Publicos\UsuarioPDFController;
use App\Livewire\Publicos\Certificados\CertificadoNoAfiliacion;
use App\Models\Aportes\Certificado;

//aqui deberia estar


Route::get('ObtenerUsuario/index', function () {
    return view('Publicos.ObtenerUsuario.index');
})
    ->name('obtener.usuario.index')
    ->middleware(['guest', 'throttle:5,1']); // 5 intentos por minuto

Route::get('ObtenerUsuario/pdf/{id}', [UsuarioPDFController::class, 'crear_usuario'])
    ->name('pdf.usuario')
    ->middleware(['guest', 'throttle:5,1']); // 5 intentos por minuto

Route::get('CertificadoNoAfiliacion/index', function () {
    return view('Publicos.CertificadoNoAfiliacion.index');
})
    ->name('obtenerCertificadoNoAfiliacion.usuario.index')
    ->middleware([
        'guest',
        'throttle:certificado-no-afiliacion'
    ]);



Route::get('CertificadoNoAfiliacion/pdf', [CertificadoNoAfiliacionController::class, 'pdf_certificado_no_afiliacion'])
    ->name('pdf.certi')
    ->middleware(['guest', 'throttle:15,10']); // 5 intentos por minuto




//Ruta kiosquito 
Route::get('kiosco', function () {
    return view('Kiosko.index');
})
    ->name('kiosko.index')
    ->middleware(['guest']);

//RUTA PARA LA IMPRESION
Route::get('kiosco-print/agenda/{id}', [PrintController::class, 'impresionAgendaCiosko'])
    ->name('kiosko.impresion')
    ->middleware(['guest']);
