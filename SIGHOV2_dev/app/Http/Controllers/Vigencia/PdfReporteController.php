<?php

namespace App\Http\Controllers\Vigencia;

use App\Http\Controllers\Controller;
use App\Models\Vigencia\OrdenServicio;
use App\Services\ReportesVigencia;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PdfReporteController extends Controller
{
    public function generarPDFOrdenServicio($fechaInicio, $fechaFin)
    {
        $ordenes = [];
        if ($fechaInicio != null && $fechaFin != null) {
            $datos = new ReportesVigencia();
            $ordenes = $datos->reporteOrdenes($fechaInicio, $fechaFin);
        }

        $fecha_registro = Carbon::now()->format('d/m/Y');
        $nombre_usuario = auth()->user()->name;
        $fecha_inicio = Carbon::createFromDate($fechaInicio)->format('d/m/Y');
        $fecha_fin = Carbon::createFromDate($fechaFin)->format('d/m/Y');
        //dd($ordenes);


        $pdf = PDF::loadView(
            'Vigencia.OrdenesServicio.pdfReporteOrdenServicio',
            compact(
                'ordenes',
                'fecha_registro',
                'nombre_usuario',
                'fecha_inicio',
                'fecha_fin',
            )
        )
            ->setPaper('letter', 'landscape');


        $nombre_pdf = 'ORDEN DE SERVICIO - VVDD';
        // Para abrir pdf stream
        return $pdf->stream($nombre_pdf . '.pdf', [
            'Attachment' => false,
            'Content-Disposition' => 'inline'
        ]);
    }

    public function generarPdfConvenioInterior($fechaInicio, $fechaFin)
    {
        $convenioInterior = [];
        if ($fechaInicio != null && $fechaFin != null) {
            $datos = new ReportesVigencia();
            $conveniosInterior = $datos->reporteConveniosInterior($fechaInicio, $fechaFin);
        }

        $fecha_registro = Carbon::now()->format('d/m/Y');
        $nombre_usuario = auth()->user()->name;
        $fecha_inicio = Carbon::createFromDate($fechaInicio)->format('d/m/Y');
        $fecha_fin = Carbon::createFromDate($fechaFin)->format('d/m/Y');

        $pdf = PDF::loadView(
            'Vigencia.Convenios.Interior.pdfReporteConvenioInterior',
            compact(
                'conveniosInterior',
                'fecha_registro',
                'nombre_usuario',
                'fecha_inicio',
                'fecha_fin'
            )
        )
            ->setPaper('letter', 'landscape');


        $nombre_pdf = 'CONVENIOS DEL INTERIOR - VVDD';
        // Para abrir pdf stream
        return $pdf->stream($nombre_pdf . '.pdf', [
            'Attachment' => false,
            'Content-Disposition' => 'inline'
        ]);
    }

    public function generarPdfConvenioEmergencia($fechaInicio, $fechaFin)
    {
        $conveniosEmergencia = [];
        if ($fechaInicio != null && $fechaFin != null) {
            $datos = new ReportesVigencia();
            $conveniosEmergencia = $datos->reporteConveniosEmergencia($fechaInicio, $fechaFin);
        }

        $fecha_registro = Carbon::now()->format('d/m/Y');
        $nombre_usuario = auth()->user()->name;
        $fecha_inicio = Carbon::createFromDate($fechaInicio)->format('d/m/Y');
        $fecha_fin = Carbon::createFromDate($fechaFin)->format('d/m/Y');

        $pdf = PDF::loadView(
            'Vigencia.Convenios.Emergencias.pdfReporteConvenioEmergencia',
            compact(
                'conveniosEmergencia',
                'fecha_registro',
                'nombre_usuario',
                'fecha_inicio',
                'fecha_fin'
            )
        )
            ->setPaper('letter', 'landscape');


        $nombre_pdf = 'CONVENIOS DE EMERGENCIA - VVDD';
        // Para abrir pdf stream
        return $pdf->stream($nombre_pdf . '.pdf', [
            'Attachment' => false,
            'Content-Disposition' => 'inline'
        ]);
    }
    
    public function generarPdfBajaInstitucional($fechaInicio, $fechaFin)
    {
        $bajasInstitucionales = [];
        if ($fechaInicio != null && $fechaFin != null) {
            $datos = new ReportesVigencia();
            $bajasInstitucionales = $datos->reporteBajasInstitucionales($fechaInicio, $fechaFin);
        }

        $fecha_inicio = Carbon::createFromDate($fechaInicio)->format('d/m/Y');
        $fecha_fin = Carbon::createFromDate($fechaFin)->format('d/m/Y');
        $fecha_registro = Carbon::now()->format('d/m/Y');
        $nombre_usuario = auth()->user()->name;

        $pdf = PDF::loadView(
            'Vigencia.BajasInstitucionales.pdfReporteBajaInstitucional',
            compact(
                'bajasInstitucionales',
                'fecha_registro',
                'nombre_usuario',
                'fecha_inicio',
                'fecha_fin'
            )
        )
            ->setPaper('letter', 'landscape');


        $nombre_pdf = 'BAJAS INSTITUCIONALES - VVDD';
        // Para abrir pdf stream
        return $pdf->stream($nombre_pdf . '.pdf', [
            'Attachment' => false,
            'Content-Disposition' => 'inline'
        ]);
    }

    public function generarPdfPrestacionMedica($fechaInicio, $fechaFin)
    {

        $prestacionesInterior = [];
        if ($fechaInicio != null && $fechaFin != null) {
            $datos = new ReportesVigencia();
            $prestacionesInterior = $datos->reportePrestacionesInterior($fechaInicio, $fechaFin);
        }

        $fecha_registro = Carbon::now()->format('d/m/Y');
        $nombre_usuario = auth()->user()->name;
        $fecha_inicio = Carbon::createFromDate($fechaInicio)->format('d/m/Y');
        $fecha_fin = Carbon::createFromDate($fechaFin)->format('d/m/Y');

        $pdf = PDF::loadView(
            'Vigencia.SolicitudesPrestacionesMedicas.pdfReportePrestacionMedica',
            compact(
                'prestacionesInterior',
                'fecha_registro',
                'nombre_usuario',
                'fecha_inicio',
                'fecha_fin'
            )
        )
            ->setPaper('letter', 'landscape');


        $nombre_pdf = 'PRESTACIONES INTERIOR - VVDD';
        // Para abrir pdf stream
        return $pdf->stream($nombre_pdf . '.pdf', [
            'Attachment' => false,
            'Content-Disposition' => 'inline'
        ]);
    }

    public function generarPdfBajaMedica($fechaInicio, $fechaFin)
    {
        $bajasMedicas = [];
        if ($fechaInicio != null && $fechaFin != null) {
            $datos = new ReportesVigencia();
            $bajasMedicas = $datos->reporteBajasMedicas($fechaInicio, $fechaFin);
        }

        //dd($bajasMedicas);
        $fecha_registro = Carbon::now()->format('d/m/Y');
        $nombre_usuario = auth()->user()->name;
        $fecha_inicio = Carbon::createFromDate($fechaInicio)->format('d/m/Y');
        $fecha_fin = Carbon::createFromDate($fechaFin)->format('d/m/Y');

        $pdf = PDF::loadView(
            'Vigencia.BajasMedicas.pdfReporteBajaMedica',
            compact(
                'bajasMedicas',
                'fecha_registro',
                'nombre_usuario',
                'fecha_inicio',
                'fecha_fin'
            )
        )
            ->setPaper('letter', 'landscape');


        $nombre_pdf = 'BAJAS MEDICAS - VVDD';
        // Para abrir pdf stream
        return $pdf->stream($nombre_pdf . '.pdf', [
            'Attachment' => false,
            'Content-Disposition' => 'inline'
        ]);
    }
}
