<?php

use App\Http\Controllers\Reportes\KardexContableController;
use Illuminate\Support\Facades\Route;



Route::get('admin/kardex_contable/index', function () {
    return view('KardexContable.index');
})->name('administracion.kardex_contable.index')->middleware(['auth']);


Route::get('admin/reportes/kardex-contable/ingresos/pdf/{fechaInicio}/{fechaFin}/{almacenConfirmado}', [KardexContableController::class, 'pdfIngresos'])
    ->name('admin.reportes.kardex-contable.ingresos.pdf')
    ->middleware(['auth', 'verified']);

Route::get('admin/reportes/kardex-contable/ingresos/excel/{fechaInicio}/{fechaFin}/{almacenConfirmado}', [KardexContableController::class, 'excelIngresos'])
    ->name('admin.reportes.kardex-contable.ingresos.excel')
    ->middleware(['auth', 'verified']);

//Excel para salidas
Route::get('admin/reportes/kardex-contable/salidas/excel/{fechaInicio}/{fechaFin}/{almacenConfirmado}', [KardexContableController::class, 'excelSalida'])
    ->name('admin.reportes.kardex-contable.salidas.excel')
    ->middleware(['auth', 'verified']);

//Excel para saldos
Route::get('admin/reportes/kardex-contable/saldos/excel/{fechaInicio}/{fechaFin}/{almacenConfirmado}', [KardexContableController::class, 'excelSaldos'])
    ->name('admin.reportes.kardex-contable.saldos.excel')
    ->middleware(['auth', 'verified']);

//Excel para cuenta 121
Route::get('admin/reportes/kardex-contable/cuenta121/excel/{almacenConfirmado}', [KardexContableController::class, 'excelCuenta121'])
    ->name('admin.reportes.kardex-contable.cuenta121.excel')
    ->middleware(['auth', 'verified']);
