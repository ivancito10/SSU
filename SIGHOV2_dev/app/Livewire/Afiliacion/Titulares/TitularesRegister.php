<?php

namespace App\Livewire\Afiliacion\Titulares;

use Livewire\Component;

class TitularesRegister extends Component
{

    protected $listeners = ['agregarNuevo'];

    public function render()
    {
        
        return view('livewire.Afiliacion.titulares.titulares-register');
    }

    public function agregarNuevo()
    {
        return redirect()->route('afiliacion.titulares.create', ['id' => 0]);
    }
}
