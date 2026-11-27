<?php

namespace App\Livewire\Afiliacion\ExamenMedico;

use Livewire\Component;
use Livewire\Attributes\On; 

class ExamenMedicoRegister extends Component
{

    public $idTit;

    
    public function render()
    {
        return view('livewire.Afiliacion.examen-medico.examen-medico-register');
    }

    
}
