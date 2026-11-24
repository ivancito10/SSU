<?php

namespace App\Services\ReportesContabilidad;

use App\Models\AlmacenGeneral\CierreGestion;
use App\Models\AlmacenGeneral\Ingreso;
use App\Models\AlmacenGeneral\Material;
use App\Models\AlmacenGeneral\MaterialStock;
use App\Models\AlmacenGeneral\Movimiento;
use App\Models\AlmacenGeneral\Reingreso;
use App\Models\AlmacenGeneral\Solicitud;
use Carbon\Carbon;

class EstadoCuentas122Materiales
{

    public function generarEstadoCuenta(int $gestion)
    {
        set_time_limit(200000);
        $estadoFinalArray = [];

        $fechas = $this->calcularFechas($gestion);

        $materiales = Material::orderBy('codigo', 'asc')
            //->limit(100)
            //->where('id', 4)
            //->whereNotIn('id', [728, 729, 1108, 1107])
            ->get();

        //dd($materiales);

        foreach ($materiales as $key => $material) {
            //--------------------------------------------------------------------------------------------- SALDOS INICIALES ----------------------------

            $arraySaldosIniciales = $this->obtenerSaldosIniciales($material, $fechas);

            //--------------------------------------------------------------------------------------------- FIN SALDOS INICIALES ----------------------------

            //--------------------------------------------------------------------------------------------- INGRESOS ----------------------------

            $arrayIngresos = $this->obtenerIngresos($material, $fechas);

            //--------------------------------------------------------------------------------------------- FIN INGRESOS ----------------------------

            //--------------------------------------------------------------------------------------------- REINGRESOS ----------------------------

            $arrayReingresos = $this->obtenerReingresos($material, $fechas);

            //--------------------------------------------------------------------------------------------- FIN REINGRESOS ----------------------------

            //--------------------------------------------------------------------------------------------- SALIDAS ----------------------------

            $arraySalidas = $this->obtenerSalidas($material, $fechas);

            //--------------------------------------------------------------------------------------------- FIN SALIDAS  ----------------------------

            //--------------------------------------------------------------------------------------------- BAJAS ----------------------------

            $arrayBajas = $this->obtenerBajas($material, $fechas);

            //--------------------------------------------------------------------------------------------- FIN BAJAS  ----------------------------

            //--------------------------------------------------------------------------------------------- ARMAR ARRAY ----------------------------

            $saldoFinalCantidad = ($arraySaldosIniciales['saldo_inicial_cantidad'] + $arrayIngresos['ingresos_cantidad']
                + $arrayReingresos['reingresos_cantidad']) -
                ($arraySalidas['salidas_cantidad'] + $arrayBajas['bajas_cantidad']);

            $saldoFinalMonto = ($arraySaldosIniciales['saldo_inicial_cantidad'] * $arraySaldosIniciales['saldo_inicial_monto'] +
                $arrayIngresos['ingresos_cantidad'] * $arrayIngresos['ingresos_monto'] +
                $arrayReingresos['reingresos_cantidad'] * $arrayReingresos['reingresos_monto']) -
                ($arraySalidas['salidas_cantidad'] * $arraySalidas['salidas_monto'] + $arrayBajas['bajas_cantidad'] * $arrayBajas['bajas_monto']);

            $estadoFinalArray[] = [
                'id_material' => $material->id,
                'cuenta_contable' => $material->nro_cuenta,
                'codigo_material' => $material->codigo,
                'nombre_material' => $material->nombre_material,
                'unidad_material' => $material->unidad,
                'saldo_inicial_cantidad' => $arraySaldosIniciales['saldo_inicial_cantidad'],
                'saldo_inicial_monto' => $arraySaldosIniciales['saldo_inicial_cantidad'] * $arraySaldosIniciales['saldo_inicial_monto'],
                'ingresos_cantidad' => $arrayIngresos['ingresos_cantidad'],
                'ingresos_monto' => $arrayIngresos['ingresos_cantidad'] * $arrayIngresos['ingresos_monto'],
                'reingresos_cantidad' => $arrayReingresos['reingresos_cantidad'],
                'reingresos_monto' => $arrayReingresos['reingresos_cantidad'] * $arrayReingresos['reingresos_monto'],
                'salidas_cantidad' => $arraySalidas['salidas_cantidad'],
                'salidas_monto' => $arraySalidas['salidas_cantidad'] * $arraySalidas['salidas_monto'],
                'bajas_cantidad' => $arrayBajas['bajas_cantidad'],
                'bajas_monto' => $arrayBajas['bajas_cantidad'] * $arrayBajas['bajas_monto'],
                'saldo_final_cantidad' => $saldoFinalCantidad,
                'saldo_final_monto' => $saldoFinalMonto,
            ];
        }

        //dd($estadoFinalArray);

        return $estadoFinalArray;
    }

    public function calcularFechas(int $gestion)
    {

        // POR EL MOMENTO LA FECHA DE INICIO VA A SER EL PRIMERO DE ENERO DE EL AÑO QUE SE ESTA SOLICITANDO EL REPORTE Y EL 31 DE DICIEMBRE

        $fechas = [
            'fecha_inicio_solo_cierres' => '2026-09-15 00:00:00',
            'fecha_inicio' =>  Carbon::createFromDate($gestion, 1, 1)->startOfYear(),
            'fecha_fin' => Carbon::createFromDate($gestion, 1, 1)->endOfYear(),
        ];

        return $fechas;
    }

    public function obtenerSaldosIniciales(Material $material, array $fechas)
    {
        $saldoInicialCantidad = 0;
        $precioUnitarioSaldosIniciales = 0.0;
        $idMaterial = $material->id;

        $cierreGestionMaterialesSaldosIniciales = CierreGestion::select('id', 'cantidad_fisica', 'cantidad_sistema', 'diferencia')
            ->where('id_material', $idMaterial)
            ->where('fecha_registro', '<', $fechas['fecha_inicio_solo_cierres'])
            ->orderBy('fecha_registro', 'desc')
            ->first();

        if ($cierreGestionMaterialesSaldosIniciales) {
            $saldoInicialCantidad = $cierreGestionMaterialesSaldosIniciales->cantidad_fisica;
            $nroLote = null;

            if ($cierreGestionMaterialesSaldosIniciales->diferencia !== 0) {
                $movimiento = Movimiento::select('nro_lote')
                    ->where('id_identificador_tabla', 4)
                    ->where('id_corresponde', $cierreGestionMaterialesSaldosIniciales->id)
                    ->where('id_material', $idMaterial)
                    ->orderBy('id', 'desc')
                    ->first();

                $nroLote = $movimiento?->nro_lote;
            } elseif ($cierreGestionMaterialesSaldosIniciales->cantidad_fisica !== 0 && $cierreGestionMaterialesSaldosIniciales->cantidad_sistema !== 0) {
                $movimiento = Movimiento::select('nro_lote')
                    ->where('saldo', $cierreGestionMaterialesSaldosIniciales->cantidad_fisica)
                    ->where('observacion', 'like', '%CIERRE DE GESTIÓN CORRESPONDIENTE%')
                    ->where('cierre_gestion', true)
                    ->where('id_material', $idMaterial)
                    ->orderBy('id', 'desc')
                    ->first();

                $nroLote = $movimiento?->nro_lote;
            }

            if ($nroLote) {
                $materialStock = MaterialStock::select('precio_unitario')
                    ->where('nro_lote', $nroLote)
                    ->where('id_material', $idMaterial)
                    ->first();

                if ($materialStock) {
                    $precioUnitarioSaldosIniciales = (float) $materialStock->precio_unitario;
                }
            }
        }

        return [
            'saldo_inicial_cantidad' => $saldoInicialCantidad,
            'saldo_inicial_monto' => round($precioUnitarioSaldosIniciales, 2)
        ];
    }

    public function obtenerIngresos(Material $material, array $fechas)
    {
        $idMaterial = $material->id;

        $ingresos = Ingreso::select('id', 'fecha_recepcion', 'estado_validacion')
            ->whereBetween('fecha_recepcion', [$fechas['fecha_inicio'], $fechas['fecha_fin']])
            ->where('estado_validacion', 'VALIDADO')
            ->whereHas('ingresoMaterialIngreso', function ($query) use ($idMaterial) {
                $query->where('id_material', $idMaterial);
            })
            ->with(['ingresoMaterialIngreso' => function ($query) use ($idMaterial) {
                $query->select('id', 'id_ingreso', 'id_material', 'cantidad', 'precio_unitario')
                    ->where('id_material', $idMaterial);
            }])
            ->orderBy('fecha_recepcion', 'desc')
            ->get();

        $totalCantidad = $ingresos->sum(function ($ingreso) {
            return $ingreso->ingresoMaterialIngreso->sum('cantidad');
        });

        $ultimoIngreso = $ingresos->first();

        $precioUnitarioUltimo = 0.0;
        if ($ultimoIngreso && $ultimoIngreso->ingresoMaterialIngreso->isNotEmpty()) {
            $primerDetalle = $ultimoIngreso->ingresoMaterialIngreso->first();
            if ($primerDetalle) {
                $precioUnitarioUltimo = (float) $primerDetalle->precio_unitario;
            }
        }

        return [
            'ingresos_cantidad' => $totalCantidad,
            'ingresos_monto' => round($precioUnitarioUltimo, 2),
        ];
    }

    public function obtenerReingresos(Material $material, array $fechas)
    {
        $idMaterial = $material->id;

        $reingresos = Reingreso::select('id', 'fecha_ingreso')
            ->whereBetween('fecha_ingreso', [$fechas['fecha_inicio'], $fechas['fecha_fin']])
            ->whereHas('detallesMaterial', function ($query) use ($idMaterial) {
                $query->where('id_material', $idMaterial);
            })
            ->with(['detallesMaterial' => function ($query) use ($idMaterial) {
                $query->select('id', 'id_reingreso', 'id_material', 'cantidad_ingreso')
                    ->where('id_material', $idMaterial);
            }])
            ->orderBy('fecha_ingreso', 'desc')
            ->get();

        $totalCantidadReingresos = 0;
        $precioUnitarioUltimoReingresos = 0.0;
        $ultimoReingreso = null;

        if ($reingresos->isNotEmpty()) {
            $totalCantidadReingresos = $reingresos->sum(function ($reingreso) {
                return $reingreso->detallesMaterial->sum('cantidad_ingreso');
            });

            $ultimoReingreso = $reingresos->first();

            if ($ultimoReingreso && $ultimoReingreso->detallesMaterial->isNotEmpty()) {

                $movimiento = Movimiento::select('nro_lote')
                    ->where('id_material', $idMaterial)
                    ->where('id_corresponde', $ultimoReingreso->id)
                    ->where('id_identificador_tabla', 5)
                    ->first();

                if ($movimiento?->nro_lote) {
                    $materialStock = MaterialStock::select('precio_unitario')
                        ->where('nro_lote', $movimiento->nro_lote)
                        ->where('id_material', $idMaterial)
                        ->first();

                    if ($materialStock) {
                        $precioUnitarioUltimoReingresos = (float) $materialStock->precio_unitario;
                    }
                }
            }
        }

        return [
            'reingresos_cantidad' => $totalCantidadReingresos,
            'reingresos_monto' => round($precioUnitarioUltimoReingresos, 2),
        ];
    }

    public function obtenerSalidas(Material $material, array $fechas)
    {
        $idMaterial = $material->id;

        $totalCantidad = 0;
        $precioUnitario = 0.0;

        $salidas = Solicitud::select('id', 'fecha_entregado', 'estado_validacion')
            ->whereBetween('fecha_entregado', [$fechas['fecha_inicio'], $fechas['fecha_fin']])
            ->where('estado_validacion', 'ENTREGADO')
            ->whereHas('materialSolicitudSolicitud', function ($query) use ($idMaterial) {
                $query->where('id_material', $idMaterial)
                    ->where('cantidad_entregada', '>', 0);
            })
            ->with(['materialSolicitudSolicitud' => function ($query) use ($idMaterial) {
                $query->select('id', 'id_solicitud', 'id_material', 'cantidad_entregada')
                    ->where('id_material', $idMaterial)
                    ->where('cantidad_entregada', '>', 0);
            }])
            ->orderBy('fecha_entregado', 'desc')
            ->get();

        if ($salidas->isNotEmpty()) {
            $totalCantidad = $salidas->sum(function ($salida) {
                return $salida->materialSolicitudSolicitud->sum('cantidad_entregada');
            });

            $ultimaSalida = $salidas->first();

            $movimientoNroLote = Movimiento::select('nro_lote')
                ->where('id_material', $idMaterial)
                ->where('id_identificador_tabla', 2)
                ->where('id_corresponde', $ultimaSalida->id)
                ->first();

            if ($movimientoNroLote?->nro_lote) {
                $materialStockPrecioUnitario = MaterialStock::select('precio_unitario')
                    ->where('id_material', $idMaterial)
                    ->where('nro_lote', $movimientoNroLote->nro_lote)
                    ->first();

                if ($materialStockPrecioUnitario) {
                    $precioUnitario = (float) $materialStockPrecioUnitario->precio_unitario;
                }
            } else {
                $fallbackStock = MaterialStock::select('precio_unitario')
                    ->where('id_material', $idMaterial)
                    ->orderBy('nro_lote', 'desc')
                    ->first();

                if ($fallbackStock) {
                    $precioUnitario = (float) $fallbackStock->precio_unitario;
                }
            }
        }

        return [
            'salidas_cantidad' => $totalCantidad,
            'salidas_monto' => round($precioUnitario, 2),
        ];
    }

    public function obtenerBajas(Material $material, array $fechas)
    {
        return [
            'bajas_cantidad' => 0,
            'bajas_monto' => 0.0
        ];
    }
}
