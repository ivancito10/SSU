<?php

namespace App\Livewire\KardexContable;

use Livewire\Component;

use function PHPUnit\Framework\isEmpty;

class MostrarReportes extends Component
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
            'seleccionarAlmacen' => 'required|in:almacenMedicamento,almacenGeneral',
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
        //dd("Llega");
        $this->validate();
        $this->almacenConfirmado = $this->seleccionarAlmacen;
        //$this->dispatch('resetearPaginacion');
    }

    public function render()
    {
        return view('livewire.kardex-contable.mostrar-reportes');
    }

    public function reportePdf()
    {
        //dd("Aqui es PDF");
        $this->validate();
        $this->almacenConfirmado = $this->seleccionarAlmacen;
        if (empty($this->fechaInicio) && empty($this->fechaFin)) {
            $this->dispatch('definirFechas');
        } else {
            $url = route('admin.reportes.kardex-contable.ingresos.pdf', ['fechaInicio' => $this->fechaInicio, 'fechaFin' => $this->fechaFin, 'almacenConfirmado' => $this->almacenConfirmado]);
            $this->js("window.open('{$url}', '_blank');");
        }
    }

    public function reporteExcel()
    {
        //dd("Aqui es excel");
        $this->validate();
        $this->almacenConfirmado = $this->seleccionarAlmacen;
        if (empty($this->fechaInicio) && empty($this->fechaFin)) {
            $this->dispatch('definirFechas');
        } else {
            $url = route('admin.reportes.kardex-contable.ingresos.excel', ['fechaInicio' => $this->fechaInicio, 'fechaFin' => $this->fechaFin, 'almacenConfirmado' => $this->almacenConfirmado]);
            $this->js("window.open('{$url}', '_blank');");
        }
    }
}
