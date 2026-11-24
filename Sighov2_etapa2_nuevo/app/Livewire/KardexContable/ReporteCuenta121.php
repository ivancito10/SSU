<?php

namespace App\Livewire\KardexContable;

use Livewire\Component;

class ReporteCuenta121 extends Component
{
    public $fechaInicio = '';
    public $fechaFin = '';
    public $seleccionarAlmacen = '';
    public $almacenConfirmado = '';
    public $periodo = '';


    public function rules(): array
    {
        return [
            //'fechaInicio' => ['required', 'date'],
            //'fechaFin'    => ['required', 'date', 'after_or_equal:fechaInicio'],
            'seleccionarAlmacen' => 'required|in:almacenMedicamento,almacenGeneral',
            'periodo' => 'required|in:opcion1',
        ];
    }


    public function messages(): array
    {
        return [
            //'fechaInicio.required'    => 'Seleccione la fecha inicial.',
            //'fechaInicio.date'        => 'Ingrese una fecha inicial válida.',
            //'fechaFin.required'       => 'Seleccione la fecha final.',
            //'fechaFin.date'           => 'Ingrese una fecha final válida.',
            //'fechaFin.after_or_equal' => 'La fecha fin debe ser mayor o igual a la fecha inicial.',
            'seleccionarAlmacen.required' => 'Debe seleccionar un tipo de almacén.',
            'seleccionarAlmacen.in'       => 'El tipo de almacén seleccionado no es válido.',
            'periodo.required' => 'Debe seleccionar un periodo.',
        ];
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
        return view('livewire.kardex-contable.reporte-cuenta121');
    }

    public function reporteExcel()
    {
        //dd("Aqui es excel");
        $this->validate();
        $this->almacenConfirmado = $this->seleccionarAlmacen;
        if (empty($this->almacenConfirmado)) {
            $this->dispatch('definirFechas');
        } else {
            $url = route('admin.reportes.kardex-contable.cuenta121.excel', ['almacenConfirmado' => $this->almacenConfirmado]);
            $this->js("window.open('{$url}', '_blank');");
        }
    }
}
