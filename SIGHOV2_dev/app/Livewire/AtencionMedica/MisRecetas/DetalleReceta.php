<?php

namespace App\Livewire\AtencionMedica\MisRecetas;

use Livewire\Component;

class DetalleReceta extends Component
{
    public $detalle;
    public function render()
    {
        // $this->detalle=null;
        return view('livewire.atencion-medica.mis-recetas.detalle-receta');
    }
}
