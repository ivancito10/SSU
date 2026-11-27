<?php

namespace App\Livewire\Aportes\Manuales;

use Livewire\Component;

class ManualesMain extends Component
{
    public function render()
    {
        return view('livewire.Aportes.manuales.manuales-main');
    }
    public function manualLlenadoExcel()
    {
        $this->dispatch('mostrarManualExcel');
        //$this->mostrarArchivoPdf();
    }
}
