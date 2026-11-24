<?php

namespace App\Livewire\KardexContable;

use Livewire\Component;

class ReporteSaldosFinales extends Component
{
    public $fechaInicio = '';
    public $fechaFin = '';
    public $seleccionarAlmacen = '';
    public $almacenConfirmado = '';

    public $rangoSeleccionado = '';

    public function rules(): array
    {
        return [
            'rangoSeleccionado' => ['required', 'in:opcion1,opcion2,opcion3,opcion4,opcion5'],
            'fechaInicio' => ['required', 'date'],
            'fechaFin'    => ['required', 'date', 'after_or_equal:fechaInicio'],
            'seleccionarAlmacen' => 'required|in:almacenMedicamento,almacenGeneral,farmacia,almacenYfarmacia',
        ];
    }


    public function messages(): array
    {
        return [
            'rangoSeleccionado.required'  => 'Debe seleccionar un rango de fechas.',
            'rangoSeleccionado.in'        => 'El rango de fechas seleccionado no es válido.',
            'fechaInicio.required'    => 'Seleccione la fecha inicial.',
            'fechaInicio.date'        => 'Ingrese una fecha inicial válida.',
            'fechaFin.required'       => 'Seleccione la fecha final.',
            'fechaFin.date'           => 'Ingrese una fecha final válida.',
            'fechaFin.after_or_equal' => 'La fecha fin debe ser mayor o igual a la fecha inicial.',
            'seleccionarAlmacen.required' => 'Debe seleccionar un tipo de almacén.',
            'seleccionarAlmacen.in'       => 'El tipo de almacén seleccionado no es válido.',
        ];
    }

    public function updatedRangoSeleccionado($value): void
    {

        switch ($value) {
            case 'opcion1':
                $this->fechaInicio = '2025-12-27';
                $this->fechaFin    = '2025-12-31';
                break;

            case 'opcion2':
                $this->fechaInicio = '2026-03-14';
                $this->fechaFin    = '2026-03-14';
                break;

            case 'opcion3':
                $this->fechaInicio = '2026-08-27';
                $this->fechaFin    = '2026-08-29';
                break;

            case 'opcion4':
                $this->fechaInicio = '2026-09-14';
                $this->fechaFin    = '2026-09-14';
                break;

            case 'opcion5':
                $this->fechaInicio = '2026-10-22';
                $this->fechaFin    = '2026-10-23';
                break;

            default:
                $this->fechaInicio = '';
                $this->fechaFin    = '';
                break;
        }
        //Validacion en tiempo real
        $this->validateOnly('rangoSeleccionado');
        $this->validateOnly('fechaInicio');
        $this->validateOnly('fechaFin');
    }

    public function generarReporte(): void
    {
        //dd("llega",$this->seleccionarAlmacen);
        $this->validate();
        $this->almacenConfirmado = $this->seleccionarAlmacen;
        $this->dispatch('resetearPaginacion');
    }

    public function render()
    {
        return view('livewire.kardex-contable.reporte-saldos-finales');
    }

    public function reporteExcel()
    {
        //dd("Aqui es excel");
        $this->validate();
        $this->almacenConfirmado = $this->seleccionarAlmacen;
        if (empty($this->fechaInicio) && empty($this->fechaFin)) {
            $this->dispatch('definirFechas');
        } else {
            $url = route('admin.reportes.kardex-contable.saldos.excel', ['fechaInicio' => $this->fechaInicio, 'fechaFin' => $this->fechaFin, 'almacenConfirmado' => $this->almacenConfirmado]);
            $this->js("window.open('{$url}', '_blank');");
        }
    }
}
