<?php

namespace App\Livewire\Afiliacion\Ssue;

use App\Models\Administracion\Persona;
use App\Models\Afiliacion\Estudiante;
use App\Models\Afiliacion\Beneficiario;
use App\Models\User;
use App\Models\Administracion\TipoAsegurado;
use App\Models\Administracion\Departamento;
use App\Models\Administracion\EstadoCivil;
use Carbon\Carbon;

use Livewire\Component;

class SsueNoSeguimiento extends Component
{
    public $id_persona;
    public $foto;
    public $fotito;
    //datos personales
    public $nombreCompleto, $nombres, $p_apellido, $s_apellido;
    public $ci, $complemento;
    public $tipo, $fecha_afiliacion;

    public function render()
    {
        return view('livewire.Afiliacion.ssue.ssue-no-seguimiento');
    }

    public function mount()
    {
        $this->mostrarEstudiante($this->id_persona);
    }

    public function mostrarEstudiante($id_persona)
    {
        $userActual = User::join('administracion.persona as p', 'p.id', '=', 'administracion.users.id_persona')
            ->where('administracion.users.id', '=', auth()->user()->id)
            ->first();
        //dd($userActual);
        $this->id_persona = $userActual->id_persona;
        $personaActual = Persona::find($this->id_persona);
        $this->nombres = $personaActual->nombres;
        $this->p_apellido = $personaActual->p_apellido;
        $this->s_apellido = $personaActual->s_apellido;
        $this->nombreCompleto = $personaActual->nombres . ' ' . $personaActual->p_apellido . ' ' . $personaActual->s_apellido;
        $complemento = $personaActual->complemento;
        if ($complemento) {
            $this->ci = $personaActual->ci . ' - ' . $personaActual->complemento;
        } else {
            $this->ci = $personaActual->ci;
        }

        $estudiante = Estudiante::where('id_persona', $this->id_persona)
           // ->where('estado', true)
            //->where('estado_vigencia', true)
            ->first();
        //  dd($estudiante);
        if ($estudiante) {
            $this->tipo = 'ESTUDIANTE';
            // $this->fecha_solicitud = $estudiante->fecha_solicitud_afiliacion;


        } else {
            $beneficiario = Beneficiario::where('id_persona', $this->id_persona)
                ->where('estado', true)
                ->where('vigencia', '=', 'VIGENTE')
                ->first();

            if ($beneficiario) {
                $this->tipo = 'BENEFICIARIO';
                $this->fecha_afiliacion = Carbon::parse($this->fecha_afiliacion)->Format('d /m/ Y ');
            }
        }
    }


    public function seguimiento()
    {
        return redirect()->route('afiliacion.ssue.afiliacion');
    }
    public function atras()
    {
        return redirect()->route('dashboard');
    }
}
