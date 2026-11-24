<?php

namespace App\Livewire\KardexContable;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class KardexContableMain extends Component
{

    public function render()
    {
        return view('livewire.kardex-contable.kardex-contable-main');
    }

    #[On('abrirModalIngresos')]
    public function abrirModalIngresos()
    {
        //$this->mostrarDatosAgenda();
        $this->dispatch('abrirModalVerIngresos');
    }

    #[On('abrirModalSalidas')]
    public function abrirModalSalidas()
    {
        //$this->mostrarDatosAgenda();
        $this->dispatch('abrirModalVerSalidas');
    }

    #[On('abrirModalCuenta121')]
    public function abrirModalCuenta121()
    {
        //dd("Llega");
        $this->dispatch('abrirModalVerCuenta121');
    }

    #[On('abrirModalSaldosFinales')]
    public function abrirModalSaldosFinales()
    {
        //dd("Llega");
        $this->dispatch('abrirModalVerSaldosFinales');
    }
}
