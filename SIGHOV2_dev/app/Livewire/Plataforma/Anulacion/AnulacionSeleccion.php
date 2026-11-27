<?php

namespace App\Livewire\Plataforma\Anulacion;

use Livewire\Component;

class AnulacionSeleccion extends Component
{
    public function render()
    {
        return view('livewire.Plataforma.anulacion.anulacion-seleccion');
    }
//hola
    public function volver()
    {
        return redirect()->route('plataforma.anulaciones.index');
    }
}
