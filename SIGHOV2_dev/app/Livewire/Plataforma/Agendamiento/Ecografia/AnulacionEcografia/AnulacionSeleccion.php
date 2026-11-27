<?php

namespace App\Livewire\Plataforma\Agendamiento\Ecografia\AnulacionEcografia;

use Livewire\Component;

class AnulacionSeleccion extends Component
{
    public function render()
    {
        return view('livewire.Plataforma.agendamiento.ecografia.anulacion-ecografia.anulacion-seleccion');
    }

      public function volver()
    {
        return redirect()->route('plataforma.agendamiento.ecografia.anulacion-ecografia.index');
    }
}
