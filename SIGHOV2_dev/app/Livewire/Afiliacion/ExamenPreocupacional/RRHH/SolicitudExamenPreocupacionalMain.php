<?php

namespace App\Livewire\Afiliacion\ExamenPreocupacional\RRHH;

use Livewire\Component;
use App\Models\Aportes\AporteInstitucional;
class SolicitudExamenPreocupacionalMain extends Component
{
    public function redirectToForm(){
       
        $this->redirectRoute('afiliacion.examen-preocupacional.instituciones.create');
    }


    public function render()
    {
        return view('livewire.Afiliacion.examen-preocupacional.r-r-h-h.solicitud-examen-preocupacional-main');
    }
}
