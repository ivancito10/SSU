<?php

namespace App\Livewire\KardexContable;

use Livewire\Component;

class MostrarReporteSalidas extends Component
{

    public $fechaInicio = '';
    public $fechaFin = '';
    public $seleccionarAlmacen = '';
    public $almacenConfirmado = '';


    public function rules(): array
    {
        return [
            'fechaInicio' => ['required', 'date'],
            'fechaFin'    => ['required', 'date', 'after_or_equal:fechaInicio'],
            'seleccionarAlmacen' => 'required|in:almacenMedicamento,almacenGeneral,farmacia',
        ];
    }


    public function messages(): array
    {
        return [
            'fechaInicio.required'    => 'Seleccione la fecha inicial.',
            'fechaInicio.date'        => 'Ingrese una fecha inicial válida.',
            'fechaFin.required'       => 'Seleccione la fecha final.',
            'fechaFin.date'           => 'Ingrese una fecha final válida.',
            'fechaFin.after_or_equal' => 'La fecha fin debe ser mayor o igual a la fecha inicial.',
            'seleccionarAlmacen.required' => 'Debe seleccionar un tipo de almacén.',
            'seleccionarAlmacen.in'       => 'El tipo de almacén seleccionado no es válido.',
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
        return view('livewire.kardex-contable.mostrar-reporte-salidas');
    }
    /*
    public function reportePdf()
    {
        //dd("Aqui es PDF");
        $this->validate();
        if (empty($this->fecha_inicio) && empty($this->fecha_fin)) {
            $this->dispatch('definirFechas');
        } else {
            $url = route('admin.reportes.kardex-contable.pdf', ['fecha_inicio' => $this->fecha_inicio, 'fecha_fin' => $this->fecha_fin]);
            $this->js("window.open('{$url}', '_blank');");
        }
    }*/

    public function reporteExcel()
    {
        //dd("Aqui es excel");
        $this->validate();
        $this->almacenConfirmado = $this->seleccionarAlmacen;
        if (empty($this->fechaInicio) && empty($this->fechaFin)) {
            $this->dispatch('definirFechas');
        } else {
            $url = route('admin.reportes.kardex-contable.salidas.excel', ['fechaInicio' => $this->fechaInicio, 'fechaFin' => $this->fechaFin, 'almacenConfirmado' => $this->almacenConfirmado]);
            $this->js("window.open('{$url}', '_blank');");
        }
    }
}
