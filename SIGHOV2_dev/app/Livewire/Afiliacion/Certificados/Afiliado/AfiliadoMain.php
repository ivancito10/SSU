<?php

namespace App\Livewire\Afiliacion\Certificados\Afiliado;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Afiliacion\CertificadoAfiliacion;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\Beneficiario;

class AfiliadoMain extends Component
{
    public function render()
    {
        return view('livewire.Afiliacion.certificados.afiliado.afiliado-main');
    }
    public function abrir(){
        return redirect()->route('afiliacion.certificados.afiliado.create');
    }

    #[On('pdf_copia')]
    public function pdf_copia($data)
    {
        $certificadoActual = CertificadoAfiliacion::find($data);
        $id_persona = $certificadoActual->id_persona;


        $titular = Titular::where('id_persona', $id_persona)->first();
        $beneficiario = Beneficiario::where('id_persona', $id_persona)->first();
         // Validar si es titular 
        if($titular != null){
            $this->js('window.open("/afiliacion/certificados/afiliacion_titular/'.$data . '", "_blank")');
            
        }elseif($beneficiario != null){
             // Validar si es beneficiario
            $this->js('window.open("/afiliacion/certificados/afiliacion_beneficiario/'.$data . '", "_blank")');
        }else{
            // Si es una persona que  nones titular ni beneficiario
            $this->dispatch('no_registra');
        }
        
        
    }


}
