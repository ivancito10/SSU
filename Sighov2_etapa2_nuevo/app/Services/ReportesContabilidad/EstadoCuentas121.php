<?php

namespace App\Services\ReportesContabilidad;

use App\Models\Farmacia\CierreGestion;
use App\Models\Farmacia\DetalleIngreso;
use App\Models\Farmacia\Ingreso;
use App\Models\Farmacia\Medicamento;
use App\Models\Farmacia\MedicamentoSolicitud;
use App\Models\Farmacia\MedicamentoStock;
use App\Models\Farmacia\Movimiento;
use App\Models\Farmacia\Reingreso;
use App\Models\Farmacia\Solicitud;
use Carbon\Carbon;

class EstadoCuentas121
{
    public function generarEstadoCuenta(int $gestion)
    {
        set_time_limit(200000);
        $estadoFinalArray = [];

        $fechas = $this->calcularFechas($gestion);

        $medicamentos = Medicamento::orderBy('codigo_medicamento', 'asc')
            //->limit(100)
            //->where('id', 120)
            //->whereNotIn('id', [728, 729, 1108, 1107])
            ->get();

        //dd($medicamentos);

        foreach ($medicamentos as $key => $medicamento) {
            //--------------------------------------------------------------------------------------------- SALDOS INICIALES ----------------------------

            $arraySaldosIniciales = $this->obtenerSaldosIniciales($medicamento, $fechas);

            //--------------------------------------------------------------------------------------------- FIN SALDOS INICIALES ----------------------------

            //--------------------------------------------------------------------------------------------- INGRESOS ----------------------------

            $arrayIngresos = $this->obtenerIngresos($medicamento, $fechas);

            //--------------------------------------------------------------------------------------------- FIN INGRESOS ----------------------------

            //--------------------------------------------------------------------------------------------- REINGRESOS ----------------------------

            $arrayReingresos = $this->obtenerReingresos($medicamento, $fechas);

            //--------------------------------------------------------------------------------------------- FIN REINGRESOS ----------------------------

            //--------------------------------------------------------------------------------------------- REINGRESOS FARMACIA ----------------------------

            $arrayReingresosFarmacia = $this->obtenerReingresosFarmacia($medicamento, $fechas);

            //--------------------------------------------------------------------------------------------- FIN REINGRESOS FARMACIA ----------------------------

            //--------------------------------------------------------------------------------------------- SALIDAS ----------------------------

            $arraySalidas = $this->obtenerSalidas($medicamento, $fechas);

            //--------------------------------------------------------------------------------------------- FIN SALIDAS  ----------------------------

            //--------------------------------------------------------------------------------------------- BAJAS ----------------------------

            $arrayBajas = $this->obtenerBajas($medicamento, $fechas);

            //--------------------------------------------------------------------------------------------- FIN BAJAS  ----------------------------

            //--------------------------------------------------------------------------------------------- ARMAR ARRAY ----------------------------

            $saldoFinalCantidad = ($arraySaldosIniciales['saldo_inicial_cantidad'] + $arrayIngresos['ingresos_cantidad']
                + $arrayReingresos['reingresos_cantidad'] + $arrayReingresosFarmacia['reingresos_farmacia_cantidad']) -
                ($arraySalidas['salidas_cantidad'] + $arrayBajas['bajas_cantidad']);

            $saldoFinalMonto = ($arraySaldosIniciales['saldo_inicial_cantidad'] * $arraySaldosIniciales['saldo_inicial_monto'] +
                $arrayIngresos['ingresos_cantidad'] * $arrayIngresos['ingresos_monto'] +
                $arrayReingresos['reingresos_cantidad'] * $arrayReingresos['reingresos_monto'] +
                $arrayReingresosFarmacia['reingresos_farmacia_cantidad'] * $arrayReingresosFarmacia['reingresos_farmacia_monto']) -
                ($arraySalidas['salidas_cantidad'] * $arraySalidas['salidas_monto'] + $arrayBajas['bajas_cantidad'] * $arrayBajas['bajas_monto']);

            $estadoFinalArray[] = [
                'id_medicamento' => $medicamento->id,
                'cuenta_contable' => $medicamento->cuenta_contable,
                'codigo_medicamento' => $medicamento->codigo_medicamento,
                'nombre_medicamento' => $medicamento->nombre,
                'forma_farmaceutica_medicamento' => $medicamento->forma_farmaceutica,
                'concentracion_medicamento' => $medicamento->concentracion,
                'saldo_inicial_cantidad' => $arraySaldosIniciales['saldo_inicial_cantidad'],
                'saldo_inicial_monto' => $arraySaldosIniciales['saldo_inicial_cantidad'] * $arraySaldosIniciales['saldo_inicial_monto'],
                'ingresos_cantidad' => $arrayIngresos['ingresos_cantidad'],
                'ingresos_monto' => $arrayIngresos['ingresos_cantidad'] * $arrayIngresos['ingresos_monto'],
                'reingresos_cantidad' => $arrayReingresos['reingresos_cantidad'],
                'reingresos_monto' => $arrayReingresos['reingresos_cantidad'] * $arrayReingresos['reingresos_monto'],
                'reingresos_farmacia_cantidad' => $arrayReingresosFarmacia['reingresos_farmacia_cantidad'],
                'reingresos_farmacia_monto' => $arrayReingresosFarmacia['reingresos_farmacia_cantidad'] * $arrayReingresosFarmacia['reingresos_farmacia_monto'],
                'salidas_cantidad' => $arraySalidas['salidas_cantidad'],
                'salidas_monto' => $arraySalidas['salidas_cantidad'] * $arraySalidas['salidas_monto'],
                'bajas_cantidad' => $arrayBajas['bajas_cantidad'],
                'bajas_monto' => $arrayBajas['bajas_cantidad'] * $arrayBajas['bajas_monto'],
                'saldo_final_cantidad' => $saldoFinalCantidad,
                'saldo_final_monto' => $saldoFinalMonto,
            ];

            /*$medicamentosRaritosNegativos = collect($estadoFinalArray)->filter(function ($item) {
                return $item['saldo_inicial_cantidad'] < 0
                    || $item['saldo_inicial_monto'] < 0
                    || $item['ingresos_cantidad'] < 0
                    || $item['ingresos_monto'] < 0
                    || $item['reingresos_cantidad'] < 0
                    || $item['reingresos_monto'] < 0
                    || $item['reingresos_farmacia_cantidad'] < 0
                    || $item['reingresos_farmacia_monto'] < 0
                    || $item['salidas_cantidad'] < 0
                    || $item['salidas_monto'] < 0
                    || $item['bajas_cantidad'] < 0
                    || $item['bajas_monto'] < 0
                    || $item['saldo_final_cantidad'] < 0
                    || $item['saldo_final_monto'] < 0;
            });

            if ($medicamentosRaritosNegativos->isNotEmpty()) {
                dd($medicamentosRaritosNegativos);
            }*/
        }

        //dd($estadoFinalArray);

        return $estadoFinalArray;
    }

    public function calcularFechas(int $gestion)
    {

        // POR EL MOMENTO LA FECHA DE INICIO VA A SER EL PRIMERO DE ENERO DE EL AÑO QUE SE ESTA SOLICITANDO EL REPORTE Y EL 31 DE DICIEMBRE

        $fechas = [
            'fecha_inicio' => Carbon::createFromDate($gestion, 1, 1)->startOfYear(),
            //'fecha_inicio' => '2026-04-01 23:59:59',
            'fecha_fin' => Carbon::createFromDate($gestion, 1, 1)->endOfYear(),
        ];

        return $fechas;
    }

    public function obtenerSaldosIniciales(Medicamento $medicamento, array $fechas)
    {
        $saldoInicialCantidad = 0;
        $precioUnitarioSaldosIniciales = 0.0;

        $saldoInicialCantidadAlmacen = 0;
        $precioUnitarioSaldosInicialesAlmacen = 0.0;

        $saldoInicialCantidadFarmacia = 0;
        $precioUnitarioSaldosInicialesFarmacia = 0.0;


        // ALMACEN

        $cierreGestionMedicamentoSaldosInicialesAlmacen = CierreGestion::where('id_medicamento', $medicamento->id)
            ->where('id_unidad', 89)
            ->where('fecha_registro', '<', $fechas['fecha_inicio'])
            ->orderBy('fecha_registro', 'desc')
            ->first();



        if ($cierreGestionMedicamentoSaldosInicialesAlmacen) {


            $saldoInicialCantidadAlmacen = $cierreGestionMedicamentoSaldosInicialesAlmacen->cantidad_fisica;
            if ($cierreGestionMedicamentoSaldosInicialesAlmacen->diferencia !== 0) {
                $movimientoCierreNroLoteSaldosInicialesAlmacen = Movimiento::select('nro_lote')
                    ->where('id_identificador_tabla', 4)
                    ->where('id_corresponde', $cierreGestionMedicamentoSaldosInicialesAlmacen->id)
                    ->where('id_medicamento', $medicamento->id)
                    ->where('id_unidad', 89)
                    ->orderBy('id', 'desc')
                    ->first()->nro_lote;

                $medicamentoStockCierreSaldosInicialesAlmacen = MedicamentoStock::select('precio_unitario')
                    ->where('nro_lote', $movimientoCierreNroLoteSaldosInicialesAlmacen)
                    ->where('id_unidad', 89)
                    ->where('id_medicamento', $medicamento->id)
                    ->first()->precio_unitario;

                $precioUnitarioSaldosInicialesAlmacen = $medicamentoStockCierreSaldosInicialesAlmacen;
            } else {
                if ($cierreGestionMedicamentoSaldosInicialesAlmacen->cantidad_fisica !== 0 && $cierreGestionMedicamentoSaldosInicialesAlmacen->cantidad_sistema !== 0) {
                    $movimientoCierreNroLoteSaldosInicialesAlmacen = Movimiento::select('nro_lote')
                        ->where('saldo', $cierreGestionMedicamentoSaldosInicialesAlmacen->cantidad_fisica)
                        ->where('observacion', 'like', '%CIERRE DE GESTIÓN CORRESPONDIENTE%')
                        ->where('cierre_gestion', true)
                        ->where('id_medicamento', $medicamento->id)
                        ->where('id_unidad', 89)
                        ->orderBy('id', 'desc')
                        ->first()->nro_lote;

                    //dd($movimientoCierreNroLoteSaldosInicialesAlmacen);

                    $medicamentoStockCierreSaldosInicialesAlmacen = MedicamentoStock::select('precio_unitario')
                        ->where('id_medicamento', $medicamento->id)
                        ->where('nro_lote', $movimientoCierreNroLoteSaldosInicialesAlmacen)
                        ->where('id_unidad', 76)
                        ->first()->precio_unitario;

                    $precioUnitarioSaldosInicialesAlmacen = $medicamentoStockCierreSaldosInicialesAlmacen;
                }
            }
        }

        //PARA FARMACIA 

        $cierreGestionMedicamentoSaldosInicialesFarmacia = CierreGestion::where('id_medicamento', $medicamento->id)
            ->where('id_unidad', 76)
            ->where('fecha_registro', '<', $fechas['fecha_inicio'])
            ->orderBy('fecha_registro', 'desc')
            ->first();

        //dd($cierreGestionMedicamentoSaldosInicialesFarmacia);

        if ($cierreGestionMedicamentoSaldosInicialesFarmacia) {
            $saldoInicialCantidadFarmacia = $cierreGestionMedicamentoSaldosInicialesFarmacia->cantidad_fisica;

            if ($cierreGestionMedicamentoSaldosInicialesFarmacia->diferencia !== 0) {
                $movimientoCierreNroLoteSaldosInicialesFarmacia = Movimiento::select('nro_lote')
                    ->where('id_identificador_tabla', 4)
                    ->where('id_corresponde', $cierreGestionMedicamentoSaldosInicialesFarmacia->id)
                    ->where('id_medicamento', $medicamento->id)
                    ->where('id_unidad', 76)
                    ->orderBy('id', 'desc')
                    ->first()->nro_lote;

                $medicamentoStockCierreSaldosInicialesFarmacia = MedicamentoStock::select('precio_unitario')
                    ->where('id_medicamento', $medicamento->id)
                    ->where('nro_lote', $movimientoCierreNroLoteSaldosInicialesFarmacia)
                    ->where('id_unidad', 76)
                    ->first()->precio_unitario;

                $precioUnitarioSaldosInicialesFarmacia = $medicamentoStockCierreSaldosInicialesFarmacia;
                //dd($precioUnitarioSaldosInicialesFarmacia, $medicamentoStockCierreSaldosInicialesFarmacia);
            } else {

                if ($cierreGestionMedicamentoSaldosInicialesFarmacia->cantidad_fisica !== 0 && $cierreGestionMedicamentoSaldosInicialesFarmacia->cantidad_sistema !== 0) {
                    $movimientoCierreNroLoteSaldosInicialesFarmacia = Movimiento::select('nro_lote')
                        ->where('saldo', $cierreGestionMedicamentoSaldosInicialesFarmacia->cantidad_fisica)
                        ->where('observacion', 'like', '%CIERRE DE GESTIÓN CORRESPONDIENTE%')
                        ->where('cierre_gestion', true)
                        ->where('id_medicamento', $medicamento->id)
                        ->where('id_unidad', 76)
                        ->orderBy('id', 'desc')
                        ->first()->nro_lote;

                    $medicamentoStockCierreSaldosInicialesFarmacia = MedicamentoStock::select('precio_unitario')
                        ->where('id_medicamento', $medicamento->id)
                        ->where('nro_lote', $movimientoCierreNroLoteSaldosInicialesFarmacia)
                        ->where('id_unidad', 76)
                        ->first()->precio_unitario;

                    $precioUnitarioSaldosInicialesFarmacia = $medicamentoStockCierreSaldosInicialesFarmacia;
                }
            }
        }

        $precioUnitarioSaldosIniciales = (float) $precioUnitarioSaldosInicialesAlmacen;

        //dd($precioUnitarioSaldosInicialesAlmacen);

        //dd($precioUnitarioSaldosIniciales, (float) $precioUnitarioSaldosInicialesAlmacen);

        if ((float) $precioUnitarioSaldosInicialesAlmacen === 0.0) {
            $precioUnitarioSaldosIniciales = (float) $precioUnitarioSaldosInicialesFarmacia;
        }

        $saldoInicialCantidad = $saldoInicialCantidadAlmacen + $saldoInicialCantidadFarmacia;

        return [
            'saldo_inicial_cantidad' => $saldoInicialCantidad,
            'saldo_inicial_monto' => round($precioUnitarioSaldosIniciales, 2)
        ];
    }

    public function obtenerIngresos(Medicamento $medicamento, array $fechas)
    {
        $idMedicamento = $medicamento->id;
        $idInstitucionExternaDevoluciones = 1;

        $ingresos = Ingreso::whereBetween('fecha_recepcion', [$fechas['fecha_inicio'], $fechas['fecha_fin']])
            ->where('id_institucion_externa', '!=', $idInstitucionExternaDevoluciones)
            ->where('estado_validacion', 'VALIDADO')
            ->whereHas('detalleIngresoIngreso', function ($query) use ($idMedicamento) {
                $query->where('id_medicamento', $idMedicamento);
            })
            ->with(['detalleIngresoIngreso' => function ($query) use ($idMedicamento) {
                $query->where('id_medicamento', $idMedicamento);
            }])
            ->orderBy('fecha_recepcion', 'desc')
            ->get();

        $totalCantidad = $ingresos->sum(function ($ingreso) {
            return $ingreso->detalleIngresoIngreso->sum('cantidad');
        });

        $ultimoIngreso = $ingresos->first();

        $precioUnitarioUltimo = $ultimoIngreso && $ultimoIngreso->detalleIngresoIngreso->isNotEmpty()
            ? (float) $ultimoIngreso->detalleIngresoIngreso->first()->precio_unitario
            : 0;

        return [
            'ingresos_cantidad' => $totalCantidad,
            'ingresos_monto' => round($precioUnitarioUltimo, 2),
        ];
    }

    public function obtenerReingresos(Medicamento $medicamento, array $fechas)
    {
        $idMedicamento = $medicamento->id;
        $idInstitucionExternaDevoluciones = 1;

        $totalCantidadIngresos = DetalleIngreso::join('farmacia.ingreso', 'farmacia.detalle_ingreso.id_ingreso', '=', 'farmacia.ingreso.id')
            ->whereBetween('farmacia.ingreso.fecha_recepcion', [$fechas['fecha_inicio'], $fechas['fecha_fin']])
            ->where('farmacia.ingreso.id_institucion_externa', $idInstitucionExternaDevoluciones)
            ->where('farmacia.ingreso.estado_validacion', 'VALIDADO')
            ->where('farmacia.detalle_ingreso.id_medicamento', $idMedicamento)
            ->sum('farmacia.detalle_ingreso.cantidad');

        $ultimoIngreso = Ingreso::select('id', 'fecha_recepcion')
            ->whereBetween('fecha_recepcion', [$fechas['fecha_inicio'], $fechas['fecha_fin']])
            ->where('id_institucion_externa', $idInstitucionExternaDevoluciones)
            ->where('estado_validacion', 'VALIDADO')
            ->whereHas('detalleIngresoIngreso', function ($query) use ($idMedicamento) {
                $query->where('id_medicamento', $idMedicamento);
            })
            ->with(['detalleIngresoIngreso' => function ($query) use ($idMedicamento) {
                $query->where('id_medicamento', $idMedicamento)->select('id', 'id_ingreso', 'precio_unitario');
            }])
            ->orderBy('fecha_recepcion', 'desc')
            ->first();

        $precioUnitarioUltimo = 0.0;
        if ($ultimoIngreso && $ultimoIngreso->detalleIngresoIngreso->isNotEmpty()) {
            $precioUnitarioUltimo = (float) $ultimoIngreso->detalleIngresoIngreso->first()->precio_unitario;
        }

        $reingresosReingresos = Reingreso::select('id', 'fecha_ingreso')
            ->whereBetween('fecha_ingreso', [$fechas['fecha_inicio'], $fechas['fecha_fin']])
            ->where('id_unidad', 89)
            ->whereHas('detallesMedicamento', function ($query) use ($idMedicamento) {
                $query->where('id_medicamento', $idMedicamento);
            })
            ->with(['detallesMedicamento' => function ($query) use ($idMedicamento) {
                $query->where('id_medicamento', $idMedicamento);
            }])
            ->orderBy('fecha_ingreso', 'desc')
            ->get();

        $totalCantidadReingresos = 0;
        $precioUnitarioUltimoReingresos = 0.0;
        $ultimoReingreso = null;

        if ($reingresosReingresos->isNotEmpty()) {
            $totalCantidadReingresos = $reingresosReingresos->sum(function ($reingreso) {
                return $reingreso->detallesMedicamento->sum('cantidad_ingreso');
            });

            $ultimoReingreso = $reingresosReingresos->first();

            if ($ultimoReingreso && $ultimoReingreso->detallesMedicamento->isNotEmpty()) {

                $movimiento = Movimiento::select('nro_lote')
                    ->where('id_medicamento', $idMedicamento)
                    ->where('id_corresponde', $ultimoReingreso->id)
                    ->where('id_identificador_tabla', 5)
                    ->where('id_unidad', 89)
                    ->first();

                if ($movimiento?->nro_lote) {
                    $medicamentoStock = MedicamentoStock::select('precio_unitario')
                        ->where('nro_lote', $movimiento->nro_lote)
                        ->where('id_unidad', 89)
                        ->where('id_medicamento', $idMedicamento)
                        ->first();

                    if ($medicamentoStock) {
                        $precioUnitarioUltimoReingresos = (float) $medicamentoStock->precio_unitario;
                    }
                }
            }
        }

        $cantidadTotalReingresos = $totalCantidadIngresos + $totalCantidadReingresos;
        $precioUnitarioFinalReingresos = 0.0;
        $fechaRecepcionUltima = $ultimoIngreso?->fecha_recepcion;
        $fechaIngresoUltima = $ultimoReingreso?->fecha_ingreso;

        if ($fechaRecepcionUltima && $fechaIngresoUltima) {
            $precioUnitarioFinalReingresos = Carbon::parse($fechaRecepcionUltima)->gte($fechaIngresoUltima)
                ? $precioUnitarioUltimo
                : $precioUnitarioUltimoReingresos;
        } elseif ($fechaRecepcionUltima) {
            $precioUnitarioFinalReingresos = $precioUnitarioUltimo;
        } elseif ($fechaIngresoUltima) {
            $precioUnitarioFinalReingresos = $precioUnitarioUltimoReingresos;
        }

        return [
            'reingresos_cantidad' => $cantidadTotalReingresos,
            'reingresos_monto' => round($precioUnitarioFinalReingresos, 2),
        ];
    }

    public function obtenerReingresosFarmacia(Medicamento $medicamento, array $fechas)
    {
        $movimientoFarmacia = Movimiento::where('id_medicamento', $medicamento->id)
            ->where('id_unidad', 76)
            ->where('fecha', '<', $fechas['fecha_fin'])
            ->orderBy('fecha', 'desc')
            ->first();

        $cantidadReingresoFarmacia = 0;
        $montoReingresoFarmacia = 0.0;

        if ($movimientoFarmacia) {
            $cantidadReingresoFarmacia = $movimientoFarmacia->saldo;

            $medicamentoStockFarmacia = MedicamentoStock::select('precio_unitario')
                ->where('id_medicamento', $medicamento->id)
                ->where('id_unidad', 76)
                ->where('nro_lote', $movimientoFarmacia->nro_lote)
                ->first()
                ->precio_unitario;

            if ($medicamentoStockFarmacia) {
                $montoReingresoFarmacia = (float) $medicamentoStockFarmacia;
            }
        }

        return [
            'reingresos_farmacia_cantidad' => $cantidadReingresoFarmacia,
            'reingresos_farmacia_monto' => round($montoReingresoFarmacia, 2)
        ];
    }

    public function obtenerSalidas(Medicamento $medicamento, array $fechas)
    {


        $idMedicamento = $medicamento->id;

        $totalCantidad = 0;
        $precioUnitario = 0.0;

        $salidas = Solicitud::whereBetween('fecha_entregado', [$fechas['fecha_inicio'], $fechas['fecha_fin']])
            ->where('id_unidad_recepciona', 89)
            ->where('estado_validacion', 'ENTREGADO')
            ->whereHas('medicamentoSolicitudSolicitud', function ($query) use ($idMedicamento) {
                $query->where('id_medicamento', $idMedicamento)
                    ->where('cantidad_entregada', '>', 0);
            })
            ->with(['medicamentoSolicitudSolicitud' => function ($query) use ($idMedicamento) {
                $query->where('id_medicamento', $idMedicamento)
                    ->where('cantidad_entregada', '>', 0);
            }])
            ->orderBy('fecha_entregado', 'desc')
            ->get();

        if ($salidas->isNotEmpty()) {
            $totalCantidad = $salidas->sum(function ($salida) {
                return $salida->medicamentoSolicitudSolicitud->sum('cantidad_entregada');
            });

            $ultimaSalida = $salidas->first();

            $movimientoNroLote = Movimiento::where('id_medicamento', $idMedicamento)
                ->where('id_identificador_tabla', 2)
                ->where('id_corresponde', $ultimaSalida->id)
                ->where('id_unidad', 89)
                ->first();

            if ($movimientoNroLote) {
                $medicamentoStockPrecioUnitario = MedicamentoStock::select('precio_unitario')
                    ->where('id_medicamento', $idMedicamento)
                    ->where('id_unidad', 89)
                    ->where('nro_lote', $movimientoNroLote->nro_lote)
                    ->first()
                    ->precio_unitario;

                $precioUnitario = (float) $medicamentoStockPrecioUnitario;
            } else {
                $medicamentoStockPrecioUnitario = MedicamentoStock::select('precio_unitario')
                    ->where('id_medicamento', $idMedicamento)
                    ->where('id_unidad', 89)
                    ->orderBy('nro_lote', 'desc')
                    ->first()
                    ->precio_unitario;
            }
        }

        return [
            'salidas_cantidad' => $totalCantidad,
            'salidas_monto' => round($precioUnitario, 2),
        ];
    }

    public function obtenerBajas(Medicamento $medicamento, array $fechas)
    {
        return [
            'bajas_cantidad' => 0,
            'bajas_monto' => 0.0
        ];
    }
}
