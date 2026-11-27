<?php

namespace App\Livewire\Afiliacion\ExamenMedico;

use Livewire\Component;
use Livewire\Attributes\On; 
use App\Models\Afiliacion\ExMedicoBeneficiario;
use App\Models\Administracion\Persona;
use App\Models\Afiliacion\Titular;

class ExamenMedicoMain extends Component
{
    #[Locked]
    public $idMedico;

    // atributos titular
    public $id_titular;
    public $persona_tit;
    public $matricula_seguro_titular;
    public $ci_titular;
    public $complemento_titular;
    public $nombre_titular;
    public $p_apellido_titular;
    public $s_apellido_titular;
    public $fecha_nacimiento_titular;
    public $sexo_titular;
    public $estado_vigencia;

    //atributos beneficiario
    public $id_beneficiario;
    public $matricula_seguro_ben;
    public $sexo_ben;

    public $ci_ben;
    public $complemento_ben;
    public $nombre_ben;
    public $p_apellido_ben;
    public $s_apellido_ben;
    public $fecha_nacimiento_ben;

    public function render()
    {
        $datosTitular =$this->mostrarTitular($this->idMedico);
        $datosBen =$this->mostrarBeneficiario($this->idMedico);
        return view('livewire.Afiliacion.examen-medico.examen-medico-main', ['datosTitular' => $datosTitular, 'datosBen' => $datosBen ]);
    }

    public function mostrarTitular($idMedico){
        if ($idMedico) {

            $examen = ExMedicoBeneficiario::find($idMedico);
            $this->id_titular = $examen->id_titular;
            

            $titularActual = Titular::find($this->id_titular);
            $this->persona_tit = $titularActual->id_persona;
            $this->estado_vigencia = $titularActual->estado_vigencia;

            $personaActual = Persona::find($this->persona_tit);
            $this->matricula_seguro_titular = $personaActual->matricula_seguro;
            $this->ci_titular = $personaActual->ci;
            $this->complemento_titular = $personaActual->complemento;
            $this->nombre_titular = $personaActual->nombres;
            $this->p_apellido_titular = $personaActual->p_apellido;
            $this->s_apellido_titular = $personaActual->s_apellido;
            $this->fecha_nacimiento_titular = $personaActual->fecha_nacimiento;
            $this->sexo_titular = $personaActual->sexo;

            return $examen;

        }
    }

    public function mostrarBeneficiario($idMedico){
        if ($idMedico) {

            $beneficiario = ExMedicoBeneficiario::find($idMedico);
            $this->id_beneficiario = $beneficiario->id_persona;

            $personaActual = Persona::find($this->id_beneficiario);
            $this->matricula_seguro_ben = $personaActual->matricula_seguro;
            $this->sexo_ben = $personaActual ->sexo;
            $this->ci_ben = $personaActual->ci;
            $this->complemento_ben = $personaActual->complemento;
            $this->nombre_ben = $personaActual->nombres;
            $this->p_apellido_ben = $personaActual->p_apellido;
            $this->s_apellido_ben = $personaActual->s_apellido;
            $this->fecha_nacimiento_ben = $personaActual->fecha_nacimiento;

            

            return $beneficiario;
        }
    }

    #[On('modalVerAgendado')]
    public function modalVerAgendado($data){
        
        $this->idMedico = $data;
        $this->dispatch('modalVerAgendado');
    }
    
}
