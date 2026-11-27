<?php

namespace App\Livewire\Vigencia\Preafiliacion;

use App\Models\Administracion\Persona;
use App\Models\Afiliacion\TitularInstitucion;
use App\Models\Aportes\Institucion;
use App\Models\Vigencia\HistorialAsegurado;
use App\Models\Vigencia\Preafiliacion;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\On;

class PreafiliacionMain extends Component
{
    public $idPreafiliacion;
    public $nombreCompleto;
    public $matricula;
    public $carnet;
    public $fechaNacimiento;
    public $sexo;
    public $edadPersona;

    public $nroPreafiliacion;
    public $fechaInicio;
    public $fechaFin;
    public $observaciones;
    public $tipoPreafiliacion;
    public $nroDocRespaldo;
    public $dias;
    public $fechaRegistro;

    public $nombreInstitucion;
    public $nombreTitular;
    public $matriculaTitular;

    public function render()
    {
        return view('livewire.Vigencia.preafiliacion.preafiliacion-main');
    }

    public function registrarPreafiliacion()
    {
        return redirect()->route('vigencia.preafiliacion.create');
    }

    #[On('verPreafiliacion')]
    public function verPreafiliacion($data)
    {
        $this->idPreafiliacion = $data;
        $this->mostrarPreafiliacion($this->idPreafiliacion);
        $this->dispatch('verModalPreafiliacion');
    }

    public function mostrarPreafiliacion($idPreafiliacion)
    {
        //DATOS PERSONA
        $preafiliacion = Preafiliacion::with(['personaPreafiliacion.estadoCivilPersona', 'titularPreafiliacion.titularPersona', 'institucionPreafiliacion'])
            ->find($idPreafiliacion);

        $this->nombreCompleto = implode(' ', array_filter([
            $preafiliacion->personaPreafiliacion->p_apellido,
            $preafiliacion->personaPreafiliacion->s_apellido,
            $preafiliacion->personaPreafiliacion->nombres
        ]));

        $this->matricula = $preafiliacion->personaPreafiliacion->matricula_seguro;
        $this->fechaNacimiento = $preafiliacion->personaPreafiliacion->fecha_nacimiento;
        $this->carnet = implode(' - ', array_filter([$preafiliacion->personaPreafiliacion->ci, $preafiliacion->personaPreafiliacion->complemento]));
        $this->sexo = $preafiliacion->personaPreafiliacion->sexo === 'F' ? 'FEMENINO' : 'MASCULINO';
        $this->edadPersona = Carbon::parse($this->fechaNacimiento)->age;

        //DATOS PREAFILIACION

        $this->nroPreafiliacion = $preafiliacion->nro_preafiliacion;
        $this->fechaInicio = $preafiliacion->fecha_inicio;
        $this->fechaFin = $preafiliacion->fecha_fin;
        $this->dias = Carbon::parse($this->fechaFin)->diffInDays(Carbon::parse($this->fechaInicio));
        $this->observaciones = $preafiliacion->observaciones;
        $this->tipoPreafiliacion = $preafiliacion->tipo_preafiliacion;
        $this->nroDocRespaldo = $preafiliacion->nro_doc_respaldo;
        $this->fechaRegistro = Carbon::parse($preafiliacion->created_at)->format('Y-m-d');
        $this->tipoPreafiliacion = $preafiliacion->tipo_preafiliacion;

        //DATOS INSTITUCION 

        $this->nombreInstitucion = 'APORTANTE VOLUNTARIO';

        if ($preafiliacion->id_institucion !== null) {
            $nombreInst = Institucion::find($preafiliacion->id_institucion);
            $this->nombreInstitucion = $nombreInst->nombre;
        }

        //DATOS TITULAR

        if ($preafiliacion->id_titular !== null) {
            $this->nombreTitular = implode(' ', array_filter([
                $preafiliacion->titularPreafiliacion->titularPersona->p_apellido,
                $preafiliacion->titularPreafiliacion->titularPersona->s_apellido,
                $preafiliacion->titularPreafiliacion->titularPersona->nombres
            ]));

            $this->matriculaTitular = $preafiliacion->titularPreafiliacion->titularPersona->matricula_seguro;
            $this->nombreInstitucion = 'APORTANTE VOLUNTARIO';

            if ($preafiliacion->titularPreafiliacion->tipo_titular) {
                $institucionTitular = TitularInstitucion::with(['titularInstitucionConInstitucion.codigosInstitucion'])
                    ->where('id_titular', $preafiliacion->id_titular)
                    ->where('estado', true)
                    ->orderBy('id', 'asc')
                    ->first();

                if ($institucionTitular->titularInstitucionConInstitucion->codigosInstitucion) {
                    $this->matriculaTitular = $this->matriculaTitular . ' - ' . $institucionTitular->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                        $institucionTitular->titularInstitucionConInstitucion->codigosInstitucion->tipo_titular;
                }

                $this->nombreInstitucion = $institucionTitular->titularInstitucionConInstitucion->nombre;
            }
        }
    }

    #[On('preafiliacionPdf')]
    public function preafiliacionPdf($data)
    {
        $this->js('window.open("pdfPreafiliacion/' . $data . '", "_blank")');
    }

    #[On('anularPreafiliacion')]
    public function anularConvenioEmer($data)
    {
        $this->idPreafiliacion = $data;
        $this->dispatch('alertaAnularPreafiliacion');
    }

    #[On('guardarAnulacionPreafiliacion')]
    public function guardarAnulacionPreafiliacion()
    {
        try {
            $preafiliacionAnulada = Preafiliacion::find($this->idPreafiliacion);

            if ($preafiliacionAnulada && $preafiliacionAnulada->estado) {
                $preafiliacionAnulada->estado = false;
                $preafiliacionAnulada->id_user_updated = auth()->user()->id;
                $preafiliacionAnulada->save();

                $personaActualizar = Persona::where('id', $preafiliacionAnulada->id_persona)
                    ->whereIn('id_tipo_asegurado', [14, 15])
                    ->first();

                if ($personaActualizar) {
                    $personaActualizar->id_tipo_asegurado = 0;
                    $personaActualizar->afiliado = false;
                    $personaActualizar->id_user_updated = auth()->user()->id;
                    $personaActualizar->save();
                }

                $existeHistorialAsegurado = HistorialAsegurado::where('id_persona', $preafiliacionAnulada->id_persona)
                    ->where('id_corresponde', $preafiliacionAnulada->id)
                    ->whereIn('id_tipo_asegurado', [14, 15])
                    ->orderBy('id', 'desc')
                    ->first();

                if ($existeHistorialAsegurado) {
                    $existeHistorialAsegurado->estado_vigencia = 'NO VIGENTE';
                    if ($existeHistorialAsegurado->fecha_baja == null) {
                        $existeHistorialAsegurado->fecha_baja = Carbon::now()->format('Y-m-d');
                    }
                    $existeHistorialAsegurado->id_user_updated = auth()->user()->id;
                    $existeHistorialAsegurado->save();
                }
            }
            
            $this->dispatch('pg:eventRefresh-PreafiliacionesTable');
            $this->dispatch('anuladoCorrectamente');
        } catch (\Throwable $th) {
            $this->dispatch('errorEnAnulacion');
        }
    }
}
