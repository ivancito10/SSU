<?php

namespace App\Services\PruebasGenerales;

use App\Models\Farmacia\Medicamento;
use App\Models\Farmacia\MedicamentoSolicitud;
use App\Models\Farmacia\MedicamentoStock;
use App\Models\Farmacia\Movimiento;

class CorreccionesFarmaciaService
{
    public function resultados(int $idMedicamento)
    {
        //$this->verificarSolicitud();
        //$this->verificarSiTodosSonDobles();

        //$idMedicamento = 53;

        $this->recalcularMovimientosPorMedicamentoFarmacia($idMedicamento);

        $this->recalcularMovimientosPorMedicamentoAlmacen($idMedicamento);
    }

    public function verificarSolicitud()
    {
        $medicamentosSolicitud = MedicamentoSolicitud::where('id_solicitud', 1041)
            ->where('cantidad_entregada', '>', 0)
            ->get();

        $quienesTienen = [];

        foreach ($medicamentosSolicitud as $key => $medicamentoSolicitud) {
            $medicamentoStock = MedicamentoStock::where('id_unidad', 76)
                ->where('id_medicamento', $medicamentoSolicitud->id_medicamento)
                ->where('cantidad_inicial', '>=', $medicamentoSolicitud->cantidad_entregada)
                ->where('stock_actualizado', '>=', $medicamentoSolicitud->cantidad_entregada)
                ->exists();

            $quienesTienen[] = [
                'id_medicamento' => $medicamentoSolicitud->id_medicamento,
                'codigo_medicamento' => Medicamento::find($medicamentoSolicitud->id_medicamento)->codigo_medicamento,
                'se_puede_borrar' => $medicamentoStock ? 'SI' : 'NO',
            ];
        }

        dd($quienesTienen);
    }

    public function verificarSiTodosSonDobles()
    {
        $medicamentosSolicitud = MedicamentoSolicitud::where('id_solicitud', 1041)
            ->where('cantidad_entregada', '>', 0)
            ->get();

        $quienesTienen = [];

        foreach ($medicamentosSolicitud as $key => $medicamentoSolicitud) {
            $movimientos = Movimiento::where('id_corresponde', 1041)
                ->where('id_identificador_tabla', 2)
                ->where('id_medicamento', $medicamentoSolicitud->id_medicamento)
                ->count();

            if ($movimientos !== 4) {

                $quienesTienen[] = [
                    'id_medicamento' => $medicamentoSolicitud->id_medicamento,
                    'codigo_medicamento' => Medicamento::find($medicamentoSolicitud->id_medicamento)->codigo_medicamento,
                    'tiene_cuatro_registros' => $movimientos === 4 ? 'SI' : 'NO',
                ];
            }
        }

        dd($quienesTienen);
    }

    public function recalcularMovimientosPorMedicamentoFarmacia(int $idMedicamento)
    {
        set_time_limit(20000);
        $movimientos = Movimiento::where('id_medicamento', $idMedicamento)
            ->where('id_unidad', 76)
            ->orderBy('id', 'asc')
            ->get();

        $saldoAcumulado = 0;

        foreach ($movimientos as $key => $movimiento) {
            $ingreso = $movimiento->cantidad_ingreso ?? 0;
            $salida = $movimiento->cantidad_salida ?? 0;

            $saldoAcumulado = $saldoAcumulado + $ingreso - $salida;

            //dd($ingreso, $salida, $saldoAcumulado);   

            $movimiento->saldo = $saldoAcumulado;
            $movimiento->save();
        }
        
    }

    public function recalcularMovimientosPorMedicamentoAlmacen(int $idMedicamento)
    {
        set_time_limit(20000);
        $movimientos = Movimiento::where('id_medicamento', $idMedicamento)
            ->where('id_unidad', 89)
            ->orderBy('id', 'asc')
            ->get();

        $saldoAcumulado = 0;

        foreach ($movimientos as $key => $movimiento) {
            $ingreso = $movimiento->cantidad_ingreso ?? 0;
            $salida = $movimiento->cantidad_salida ?? 0;

            $saldoAcumulado = $saldoAcumulado + $ingreso - $salida;

            $movimiento->saldo = $saldoAcumulado;
            $movimiento->save();
        }
        
    }
}
