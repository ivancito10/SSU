<?php

namespace App\Http\Controllers\Reportes;

use App\Exports\Reportes\AlmacenMedicamentoFarmaciaSaldosExport;
use App\Exports\Reportes\AlmacenMedicamentosSaldosExport;
use App\Exports\Reportes\FarmaciaSaldosExport;
use App\Exports\Reportes\IngresoKardexContableExport;
use App\Exports\Reportes\MaterialesCuenta122Export;
use App\Exports\Reportes\MaterialesIngresosExport;
use App\Exports\Reportes\MaterialesSaldosExport;
use App\Exports\Reportes\MaterialesSalidaExport;
use App\Exports\Reportes\MedicamentoCuenta121Export;
use App\Exports\Reportes\MedicamentoSalidaExport;
use App\Exports\Reportes\MedicamentosIngresosExport;
use App\Http\Controllers\Controller;
use App\Models\Farmacia\DetalleIngreso;
use App\Services\ReportesContabilidad\EstadoCuentas121;
use App\Services\ReportesContabilidad\EstadoCuentas122Materiales;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class KardexContableController extends Controller
{
    public function pdfIngresos($fechaInicio, $fechaFin, $almacenConfirmado)
    {
        //dd($almacenConfirmado);
        $fechaInicio = Carbon::parse($fechaInicio)->startOfDay();
        $fechaFin    = Carbon::parse($fechaFin)->endOfDay();

        $resultado = collect();

        if ($almacenConfirmado === 'almacenGeneral') {
            $resultado = DB::table('almacen_general.ingreso_material')
                ->join('almacen_general.ingreso', 'almacen_general.ingreso.id', '=', 'almacen_general.ingreso_material.id_ingreso')
                ->join('almacen_general.material', 'almacen_general.material.id', '=', 'almacen_general.ingreso_material.id_material')
                ->leftJoin('farmacia.institucion_externa', 'farmacia.institucion_externa.id', '=', 'almacen_general.ingreso.id_institucion_externa')
                ->leftJoin('farmacia.tipo_proceso', 'farmacia.tipo_proceso.id', '=', 'almacen_general.ingreso.id_tipo_proceso')
                ->where('almacen_general.ingreso_material.estado', true)
                ->whereBetween('almacen_general.ingreso.fecha_ingreso', [$fechaInicio, $fechaFin])
                ->orderBy('almacen_general.ingreso.fecha_ingreso', 'asc')
                ->orderBy('almacen_general.ingreso_material.id', 'asc')
                ->select([
                    'almacen_general.ingreso_material.id',
                    'almacen_general.material.codigo as codigo',
                    'almacen_general.material.nombre_material as descripcion',
                    'almacen_general.material.unidad as detalle_extra', // o unidad de medida
                    'almacen_general.ingreso.fecha_ingreso',
                    'farmacia.tipo_proceso.nombre as modalidad_compra',
                    'almacen_general.ingreso.codigo_ingreso as numero_ingreso',
                    'farmacia.institucion_externa.razon_social as proveedor',
                    'almacen_general.ingreso_material.cantidad',
                    'almacen_general.ingreso_material.precio_unitario',
                    'almacen_general.ingreso_material.precio_total',
                    'almacen_general.material.nro_cuenta as cuenta_contable',
                ])
                ->get();
        } elseif ($almacenConfirmado === 'almacenMedicamento') {
            $resultado = DB::table('farmacia.detalle_ingreso')
                ->join('farmacia.ingreso', 'farmacia.ingreso.id', '=', 'farmacia.detalle_ingreso.id_ingreso')
                ->join('farmacia.medicamento', 'farmacia.medicamento.id', '=', 'farmacia.detalle_ingreso.id_medicamento')
                ->leftJoin('farmacia.institucion_externa', 'farmacia.institucion_externa.id', '=', 'farmacia.ingreso.id_institucion_externa')
                ->leftJoin('farmacia.tipo_proceso', 'farmacia.tipo_proceso.id', '=', 'farmacia.ingreso.id_tipo_proceso')
                ->where('farmacia.ingreso.estado', true)
                ->where('farmacia.ingreso.id_institucion_externa', '!=', 1)
                ->whereBetween('farmacia.ingreso.fecha_ingreso', [$fechaInicio, $fechaFin])
                ->orderBy('farmacia.ingreso.fecha_ingreso', 'asc')
                ->orderBy('farmacia.detalle_ingreso.id', 'asc')
                ->select([
                    'farmacia.detalle_ingreso.id',
                    'farmacia.medicamento.codigo_medicamento as codigo',
                    'farmacia.medicamento.nombre as descripcion',
                    'farmacia.medicamento.forma_farmaceutica as detalle_extra',
                    'farmacia.ingreso.fecha_ingreso',
                    'farmacia.tipo_proceso.nombre as modalidad_compra',
                    'farmacia.ingreso.cod_ingreso as numero_ingreso',
                    'farmacia.institucion_externa.razon_social as proveedor',
                    'farmacia.detalle_ingreso.cantidad',
                    'farmacia.detalle_ingreso.precio_unitario',
                    'farmacia.detalle_ingreso.precio_total',
                    'farmacia.medicamento.cuenta_contable',
                ])
                ->get();
        }


        $nombreUsuario = auth()->user()->name;
        $fechaActual = Carbon::now()->format('d/m/Y');

        $vista = ($almacenConfirmado === 'almacenMedicamento')
            ? 'KardexContable.pdfIngresosMedicamentos'
            : 'KardexContable.pdfIngresosMateriales';

        $pdf = Pdf::loadView($vista, [
            'detalles'       => $resultado,
            'fechaInicio'        => Carbon::parse($fechaInicio)->format('d/m/Y'),
            'fechaFin'           => Carbon::parse($fechaFin)->format('d/m/Y'),
            'fechaActual'        => $fechaActual,
            'nombreUsuario'           => $nombreUsuario,
        ])->setPaper('letter', 'landscape');


        $dompdf = $pdf->getDomPDF();
        $dompdf->render();
        $canvas = $dompdf->getCanvas();
        $font = $dompdf->getFontMetrics()->getFont("DejaVu Sans", "normal");

        //Mostrar numeracion de pagina
        $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) use ($font) {
            $text = "Página $pageNumber de $pageCount";
            $size = 7;
            $width = $fontMetrics->getTextWidth($text, $font, $size);
            $x = $canvas->get_width() - $width - 20;
            $y = $canvas->get_height() - 60;

            $canvas->text($x, $y, $text, $font, $size, [0, 0, 0]);
        });


        $fileName = 'ReporteIngresos.pdf';

        return $pdf->stream($fileName, [
            'Attachment' => false,
        ]);
    }

    public function excelIngresos($fechaInicio, $fechaFin, $almacenConfirmado)
    {
        $fechaInicioParsed = Carbon::parse($fechaInicio)->startOfDay();
        $fechaFinParsed    = Carbon::parse($fechaFin)->endOfDay();

        $info_general = [
            'fecha_actual' => Carbon::now()->format('d/m/Y H:i'),
            'user_name'    => auth()->user()->name,
        ];

        switch ($almacenConfirmado) {
            case 'almacenMedicamento':
            case 'farmacia':
                $export = new MedicamentosIngresosExport($fechaInicioParsed, $fechaFinParsed, $info_general);
                $nombreArchivo = "Kardex_Ingresos_Medicamentos_{$fechaInicio}_al_{$fechaFin}.xlsx";
                break;

            case 'almacenGeneral':
                $export = new MaterialesIngresosExport($fechaInicioParsed, $fechaFinParsed, $info_general);
                $nombreArchivo = "Kardex_Ingresos_Almacen_General_{$fechaInicio}_al_{$fechaFin}.xlsx";
                break;

            default:
                abort(404, 'Tipo de almacén no válido.');
        }

        return Excel::download($export, $nombreArchivo);
    }
    /*
    public function excelIngreso($fechaInicio, $fechaFin, $almacenConfirmado)
    {
        $fechaInicioParsed = Carbon::parse($fechaInicio)->startOfDay();
        $fechaFinParsed    = Carbon::parse($fechaFin)->endOfDay();

        if ($almacenConfirmado === 'almacenGeneral') {
            $resultado = DB::table('almacen_general.ingreso_material')
                ->join('almacen_general.ingreso', 'almacen_general.ingreso.id', '=', 'almacen_general.ingreso_material.id_ingreso')
                ->join('almacen_general.material', 'almacen_general.material.id', '=', 'almacen_general.ingreso_material.id_material')
                ->leftJoin('farmacia.institucion_externa', 'farmacia.institucion_externa.id', '=', 'almacen_general.ingreso.id_institucion_externa')
                ->leftJoin('farmacia.tipo_proceso', 'farmacia.tipo_proceso.id', '=', 'almacen_general.ingreso.id_tipo_proceso')
                ->where('almacen_general.ingreso_material.estado', true)
                ->whereBetween('almacen_general.ingreso.fecha_ingreso', [$fechaInicio, $fechaFin])
                ->orderBy('almacen_general.ingreso.fecha_ingreso', 'asc')
                ->orderBy('almacen_general.ingreso_material.id', 'asc')
                ->select([
                    'almacen_general.ingreso_material.id',
                    'almacen_general.material.codigo as codigo',
                    'almacen_general.material.nombre_material as descripcion',
                    'almacen_general.material.unidad as detalle_extra', // o unidad de medida
                    'almacen_general.ingreso.fecha_ingreso',
                    'farmacia.tipo_proceso.nombre as modalidad_compra',
                    'almacen_general.ingreso.codigo_ingreso as numero_ingreso',
                    'farmacia.institucion_externa.razon_social as proveedor',
                    'almacen_general.ingreso_material.cantidad',
                    'almacen_general.ingreso_material.precio_unitario',
                    'almacen_general.ingreso_material.precio_total',
                    'almacen_general.material.nro_cuenta as cuenta_contable',
                ])
                ->get();
        } else {
            $resultado = DB::table('farmacia.detalle_ingreso')
                ->join('farmacia.ingreso', 'farmacia.ingreso.id', '=', 'farmacia.detalle_ingreso.id_ingreso')
                ->join('farmacia.medicamento', 'farmacia.medicamento.id', '=', 'farmacia.detalle_ingreso.id_medicamento')
                ->leftJoin('farmacia.institucion_externa', 'farmacia.institucion_externa.id', '=', 'farmacia.ingreso.id_institucion_externa')
                ->leftJoin('farmacia.tipo_proceso', 'farmacia.tipo_proceso.id', '=', 'farmacia.ingreso.id_tipo_proceso')
                ->where('farmacia.ingreso.estado', true)
                //Esta condicion quitar cuando los de almacen registren sus reingresos en la tabla y no en devoluciones
                ->where('farmacia.ingreso.id_institucion_externa', '!=', 1)
                ->whereBetween('farmacia.ingreso.fecha_ingreso', [$fechaInicioParsed, $fechaFinParsed])
                ->orderBy('farmacia.ingreso.fecha_ingreso', 'asc')
                ->orderBy('farmacia.detalle_ingreso.id', 'asc')
                ->select([
                    'farmacia.detalle_ingreso.id',
                    'farmacia.medicamento.codigo_medicamento as codigo',
                    'farmacia.medicamento.nombre as descripcion',
                    'farmacia.medicamento.forma_farmaceutica as detalle_extra',
                    'farmacia.ingreso.fecha_ingreso',
                    'farmacia.tipo_proceso.nombre as modalidad_compra',
                    'farmacia.ingreso.cod_ingreso as numero_ingreso',
                    'farmacia.institucion_externa.razon_social as proveedor',
                    'farmacia.detalle_ingreso.cantidad',
                    'farmacia.detalle_ingreso.precio_unitario',
                    'farmacia.detalle_ingreso.precio_total',
                    'farmacia.medicamento.cuenta_contable',
                ])
                ->get();
        }

        $datos = $resultado->map(function ($det, $index) {
            $modalidad = $det->modalidad_compra ?? '';
            if (!empty($det->numero_ingreso)) {
                $modalidad .= ' - N° ' . $det->numero_ingreso;
            }

            return [
                $index + 1,
                $det->codigo ?? '-',
                $det->descripcion ?? '-',
                $det->detalle_extra ?? '-',
                !empty($det->fecha_ingreso) ? date('d/m/Y', strtotime($det->fecha_ingreso)) : '-',
                $modalidad ?: '-',
                $det->proveedor ?? '-',
                (float) ($det->cantidad ?? 0),
                (float) ($det->precio_unitario ?? 0),
                (float) ($det->precio_total ?? 0),
                $det->cuenta_contable ?? '-',
            ];
        })->toArray();

        $info_general = [
            'fecha_inicio'    => $fechaInicioParsed->format('d/m/Y'),
            'fecha_fin'       => $fechaFinParsed->format('d/m/Y'),
            'fecha_actual'    => Carbon::now()->format('d/m/Y H:i'),
            'user_name'       => auth()->user()->name,
            'total_registros' => count($datos),
            'tipo_almacen'    => $almacenConfirmado === 'almacenGeneral' ? 'ALMACÉN GENERAL' : 'ALMACEN DE MEDICAMENTOS'
        ];

        $fileName = 'Kardex_Ingresos_' . $almacenConfirmado . '_' . $fechaInicioParsed->format('Ymd') . '.xlsx';

        return Excel::download(new IngresoKardexContableExport($datos, $info_general), $fileName);
    }
    
    public function excelIngresos($fechaInicio, $fechaFin, $almacenConfirmado)
    {
        $fecha_inicio_parsed = Carbon::parse($fechaInicio)->startOfDay();
        $fecha_fin_parsed    = Carbon::parse($fechaFin)->endOfDay();

        $detallesIngreso = DetalleIngreso::with([
            'ingresoDetalleIngreso.institucionExternaIngreso',
            'ingresoDetalleIngreso.procesoIngreso',
            'medicamentoDetalleIngreso'
        ])
            ->join('farmacia.ingreso', 'farmacia.ingreso.id', '=', 'farmacia.detalle_ingreso.id_ingreso')
            ->where('farmacia.ingreso.estado', true)
            ->whereBetween('farmacia.ingreso.fecha_ingreso', [$fecha_inicio_parsed, $fecha_fin_parsed])
            ->orderBy('farmacia.ingreso.fecha_ingreso', 'asc')
            ->orderBy('farmacia.detalle_ingreso.id', 'asc')
            ->select('farmacia.detalle_ingreso.*')
            ->get();

        // Mapeo exactamente alineado con las 11 columnas de tu vista Blade
        $datos = $detallesIngreso->map(function ($det, $index) {
            $ingreso     = $det->ingresoDetalleIngreso;
            $medicamento = $det->medicamentoDetalleIngreso;

            $precioUnitario = $det->precio_unitario ?? 0;
            $cantidad       = $det->cantidad ?? 0;
            $precioTotal    = $det->precio_total ?? ($precioUnitario * $cantidad);

            return [
                $index + 1,
                $medicamento->codigo_medicamento ?? '-',
                $medicamento->nombre ?? '-',
                $medicamento->forma_farmaceutica ?? '-',
                $ingreso && $ingreso->fecha_ingreso ? Carbon::parse($ingreso->fecha_ingreso)->format('d/m/Y') : '-',
                $ingreso->procesoIngreso->nombre ?? $ingreso->procesoIngreso->sigla ?? '-',
                $ingreso->institucionExternaIngreso->razon_social ?? '-',
                $cantidad,
                number_format((float)$precioUnitario, 2, '.', ''),
                number_format((float)$precioTotal, 2, '.', ''),
                $det->cuenta_contable ?? $medicamento->cuenta_contable ?? '-'
            ];
        })->toArray();

        $info_general = [
            'fecha_inicio'    => $fecha_inicio_parsed->format('d/m/Y'),
            'fecha_fin'       => $fecha_fin_parsed->format('d/m/Y'),
            'fecha_actual'    => Carbon::now()->format('d/m/Y H:i'),
            'user_name'       => auth()->user()->name,
            'total_registros' => count($datos)
        ];

        $fileName = 'Reporte_Detalle_Ingresos_' . $fecha_inicio_parsed->format('Ymd') . '.xlsx';

        return Excel::download(new IngresoKardexContableExport($datos, $info_general), $fileName);
    }*/

    public function excelSalida($fechaInicio, $fechaFin, $almacenConfirmado)
    {
        $fechaInicioParsed = Carbon::parse($fechaInicio)->startOfDay();
        $fechaFinParsed    = Carbon::parse($fechaFin)->endOfDay();

        $info_general = [
            'fecha_actual' => Carbon::now()->format('d/m/Y H:i'),
            'user_name'    => auth()->user()->name,
        ];

        switch ($almacenConfirmado) {
            case 'almacenMedicamento':
            case 'farmacia':
                $export = new MedicamentoSalidaExport($fechaInicioParsed, $fechaFinParsed, $info_general);
                $nombreArchivo = "reporte_salidas_medicamento_{$fechaInicio}_al_{$fechaFin}.xlsx";
                break;

            case 'almacenGeneral':
                $export = new MaterialesSalidaExport($fechaInicioParsed, $fechaFinParsed, $info_general);
                $nombreArchivo = "reporte_salidas_almacen_general_{$fechaInicio}_al_{$fechaFin}.xlsx";
                break;

            default:
                abort(404, 'Tipo de almacén no válido.');
        }

        return Excel::download($export, $nombreArchivo);
    }

    public function excelSaldos($fechaInicio, $fechaFin, $almacenConfirmado)
    {
        //dd($fechaInicio, $fechaFin, $almacenConfirmado);
        $fechaInicioParsed = Carbon::parse($fechaInicio)->startOfDay();
        $fechaFinParsed    = Carbon::parse($fechaFin)->endOfDay();

        $info_general = [
            'fecha_actual' => Carbon::now()->format('d/m/Y H:i'),
            'user_name'    => auth()->user()->name,
        ];

        switch ($almacenConfirmado) {

            case 'almacenMedicamento':
                $export = new AlmacenMedicamentosSaldosExport(
                    $fechaInicioParsed,
                    $fechaFinParsed,
                    $info_general
                );

                $nombreArchivo = "reporte_saldos_almacen_medicamento_{$fechaInicio}_al_{$fechaFin}.xlsx";
                break;

            case 'farmacia':
                $export = new FarmaciaSaldosExport(
                    $fechaInicioParsed,
                    $fechaFinParsed,
                    $info_general
                );

                $nombreArchivo = "reporte_saldos_farmacia_{$fechaInicio}_al_{$fechaFin}.xlsx";
                break;

            case 'almacenYfarmacia':
                $export = new AlmacenMedicamentoFarmaciaSaldosExport(
                    $fechaInicioParsed,
                    $fechaFinParsed,
                    $info_general
                );

                $nombreArchivo = "reporte_saldos_almacen_y_farmacia_{$fechaInicio}_al_{$fechaFin}.xlsx";
                break;

            case 'almacenGeneral':
                $export = new MaterialesSaldosExport(
                    $fechaInicioParsed,
                    $fechaFinParsed,
                    $info_general
                );

                $nombreArchivo = "reporte_saldos_almacen_general_{$fechaInicio}_al_{$fechaFin}.xlsx";
                break;

            default:
                abort(404, 'Tipo de almacén no válido.');
        }

        return Excel::download($export, $nombreArchivo);
    }



    public function excelCuenta121($almacenConfirmado)
    {
        //dd("Llega");
        $info_general = [
            'fecha_actual' => Carbon::now()->format('d/m/Y H:i'),
            'user_name'    => auth()->user()->name,
        ];

        $reporteCuenta121 = new EstadoCuentas121();
        $reporte121 = $reporteCuenta121->generarEstadoCuenta(2026);

        $reporteCuenta122 = new EstadoCuentas122Materiales();
        $reporte122 = $reporteCuenta122->generarEstadoCuenta(2026);

        switch ($almacenConfirmado) {
            case 'almacenMedicamento':
                $export = new MedicamentoCuenta121Export($reporte121, $info_general);
                $nombreArchivo = "reporte_cuentas121.xlsx";
                break;

            case 'almacenGeneral':
                $export = new MaterialesCuenta122Export($reporte122, $info_general);
                $nombreArchivo = "reporte_cuentas122.xlsx";
                break;

            default:
                abort(404, 'Tipo de almacén no válido.');
        }

        return Excel::download($export, $nombreArchivo);
    }
}
