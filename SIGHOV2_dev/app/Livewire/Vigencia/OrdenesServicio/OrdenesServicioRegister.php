<?php

namespace App\Livewire\Vigencia\OrdenesServicio;

use App\Models\Administracion\Persona;
use App\Models\Vigencia\OrdenServicio;
use App\Models\Vigencia\InstitucionConvenio;
use App\Models\Aportes\Institucion;
use App\Models\Administracion\TipoAsegurado;
use App\Models\Administracion\Especialidad;
use App\Models\Plataforma\Especialista;
use App\Models\Afiliacion\TitularInstitucion;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Vigencia\AutorizacionInterior;
use App\Models\Vigencia\Convenio;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Carbon\Carbon;
use Livewire\Component;

class OrdenesServicioRegister extends Component
{
    public $idPersona;
    // datos de persona
    public $nombres, $p_apellido, $s_apellido, $ci, $matricula, $complemento, $nombreCompleto, $edadPersona, $sexo, $fecha_nacimiento, $codigoInstitucional;
    public $id_tipo_asegurado, $tipo_asegurado;
    public $foto;
    public $estado_vigencia;
    // datos de orden 
    #[Validate]
    public $nro_orden_servicio, $fecha_recepcion, $fecha_entregado, $observacion;
    #[Validate]
    public $estudio;
    public $id_orden_servicio_s, $id_persona_entregado, $nro_orden_servicio_anio;
    #[Validate]
    public $id_institucion_convenio;
    public $institucion;
    #[Validate]
    public $id_especialidad;
    public $id_esp;
    public $medicos = [];
    #[Validate]
    public $id_especialista;
    public $instiSelect;
    public $especialidades;
    public $carnet;
    public $esConvenioInteriorSwitch = false;
    public $esConvenioEmergenciaSwitch = false;
    protected $listeners = ['registrarOrden'];


    public function rules()
    {
        return [
            'nro_orden_servicio' => 'required|min:1',
            'fecha_recepcion' => 'required|date',
            'id_institucion_convenio' => 'required',
            'id_especialidad' => 'required',
            'id_especialista' => 'required',
            'estudio' => 'required',

        ];
    }

    public function render()
    {
        return view('livewire.Vigencia.ordenes-servicio.ordenes-servicio-register');
    }

    public function mount()
    {
        $this->especialidades = Especialidad::orderBy('especialidad', 'asc')->get();
        $this->instiSelect = InstitucionConvenio::all();
        $this->fecha_recepcion = Carbon::now()->format('Y-m-d');
        $this->mostrarDatos($this->idPersona);
    }

    public  $especialistaSelect = [];
    public function buscarEspecialista($id)
    {
        $this->id_esp = $id;
        $this->especialistaSelect = Especialista::where('id_especialidad', $id)->with('personaEspecialista')->get();
    }

    public function mostrarDatos($idPersona)
    {
        $afiliado = Persona::find($idPersona);
        $this->matricula = $afiliado->matricula_seguro;
        if ($afiliado->complemento == null) {
            $this->carnet = $afiliado->ci;
        } else {
            $this->carnet = $afiliado->ci . ' - ' . $afiliado->complemento;
        }

        $this->ci = $afiliado->ci;
        $this->complemento = $afiliado->complemento;
        $this->nombres = $afiliado->nombres;
        $this->p_apellido = $afiliado->p_apellido;
        $this->s_apellido = $afiliado->s_apellido;
        $this->nombreCompleto = $afiliado->p_apellido . ' ' . $afiliado->s_apellido . ' ' . $afiliado->nombres;
        $tipoAsegurado = TipoAsegurado::find($afiliado->id_tipo_asegurado);
        $this->tipo_asegurado = $tipoAsegurado->tipo_asegurado;
        $this->fecha_nacimiento = $afiliado->fecha_nacimiento;
        $this->edadPersona = Carbon::parse($afiliado->fecha_nacimiento)->age;
        $this->sexo = $afiliado->sexo == 'F' ? 'FEMENINO' : 'MASCULINO';

        $esTitular = Titular::where('id_persona', $idPersona)->where('estado_vigencia', 'VIGENTE')->first();
        if ($esTitular) {
            $this->foto = $this->obtenerImagenBase64($esTitular->foto_nombre);
            $this->estado_vigencia = $esTitular->estado_vigencia;
            $titularInstitucion = TitularInstitucion::where('id_titular', $esTitular->id)
                ->where('estado', true)
                ->with(['titularInstitucionConInstitucion', 'titularInstitucionConInstitucion.codigosInstitucion'])
                ->first();
            if ($titularInstitucion) {
                $inst = Institucion::find($titularInstitucion->id_institucion);
                $this->institucion = $inst->nombre;
                if ($titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion != null) {
                    $this->codigoInstitucional = $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                        $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->tipo_titular;
                } else {
                    $this->codigoInstitucional = null;
                }
            } else {
                $this->institucion = 'APORTANTE VOLUNTARIO';
                $this->codigoInstitucional = null;
            }
        }

        $esBeneficiario = Beneficiario::where('id_persona', $idPersona)->where('vigencia', 'VIGENTE')->first();
        if ($esBeneficiario) {
            $this->foto = $this->obtenerImagenBase64($esBeneficiario->foto_nombre);
            $this->estado_vigencia = $esBeneficiario->vigencia;
            $titularBen = Titular::find($esBeneficiario->id_titular);
            $titularInstBen = TitularInstitucion::where('id_titular', $titularBen->id)
                ->where('estado', true)
                ->with(['titularInstitucionConInstitucion', 'titularInstitucionConInstitucion.codigosInstitucion'])
                ->first();
            if ($titularInstBen) {
                $inst = Institucion::find($titularInstBen->id_institucion);
                $this->institucion = $inst->nombre;
                if ($titularInstBen->titularInstitucionConInstitucion->codigosInstitucion != null) {
                    $this->codigoInstitucional = $titularInstBen->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                        $titularInstBen->titularInstitucionConInstitucion->codigosInstitucion->tipo_beneficiario;
                } else {
                    $this->codigoInstitucional = null;
                }
            } else {
                $this->institucion = 'APORTANTE VOLUNTARIO';
                $this->codigoInstitucional = null;
            }
        }

        $esConvenioInterior = AutorizacionInterior::where('id_persona_interior', $idPersona)->where('estado', true)
            ->with(['seguroInteriorAutorizacionInterior'])
            ->first();
        if ($esConvenioInterior) {
            $this->esConvenioInteriorSwitch = true;
            $this->foto = $this->obtenerImagenBase64Convenios($esConvenioInterior->foto_nombre);
            $this->estado_vigencia = $esConvenioInterior->estado ? 'VIGENTE' : 'NO VIGENTE';
            $this->institucion = $esConvenioInterior->seguroInteriorAutorizacionInterior->nombre_seguro;
            $this->codigoInstitucional = null;
        }

        $esConvenioEmergencia = Convenio::where('id_persona', $idPersona)->where('estado', true)
            ->with(['titularAutorizaConvenio.titularPersona'])
            ->first();
        if ($esConvenioEmergencia) {
            $this->esConvenioEmergenciaSwitch = true;
            $this->foto = $this->obtenerImagenBase64Convenios($esConvenioEmergencia->foto_nombre);
            $this->estado_vigencia = $esConvenioEmergencia->estado ? 'VIGENTE' : 'NO VIGENTE';
            $this->institucion = implode(' ', array_filter([
                $esConvenioEmergencia->titularAutorizaConvenio->titularPersona->p_apellido,
                $esConvenioEmergencia->titularAutorizaConvenio->titularPersona->s_apellido,
                $esConvenioEmergencia->titularAutorizaConvenio->titularPersona->nombres,
            ]));
            $this->codigoInstitucional = null;
        }



        //return $afiliado;
    }
    // funciona para obtener imagen 
    private function obtenerImagenBase64($idFoto)
    {
        try {
            if ($idFoto) {
                $path = 'Afiliacion/FOTOS/' . $idFoto;

                $imageData = Storage::disk('ftp')->get($path);
                //dd($imageData);
                $this->foto = base64_encode($imageData);
                return $this->foto;
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            return null;
        }
    }

    private function obtenerImagenBase64Convenios($idFoto)
    {
        try {
            if ($idFoto) {
                $path = 'Vigencia/FOTOS/CONVENIO/' . $idFoto;

                $imageData = Storage::disk('ftp')->get($path);
                //dd($imageData);
                $this->foto = base64_encode($imageData);
                return $this->foto;
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            return null;
        }
    }

    // Crear la orden de servicio 
    public function registrarOrden($estudio)
    {
        $this->estudio = $estudio;
        //dd($this->estudio);
        $this->validate();
        $orden = OrdenServicio::create([
            'nro_orden_servicio' => $this->nro_orden_servicio,
            'fecha_recepcion' => Carbon::now(),
            'fecha_entregado' => $this->fecha_entregado,
            'estudio' => $this->estudio,
            'id_institucion_convenio' => $this->id_institucion_convenio,
            'id_especialidad' => $this->id_especialidad,
            'id_especialista' => $this->id_especialista,
            'id_persona' => $this->idPersona,
            'estado' => 'PENDIENTE',
            'id_user_created' => auth()->user()->id,
        ]);
        $this->dispatch('ordenCreada');
        return redirect()->route('vigencia.ordenesservicio.index');
    }


    public function cancelarOrden()
    {
        $this->dispatch('ordenCancelado');
        return redirect()->route('vigencia.ordenesservicio.index');
    }

    public function atras()
    {
        return redirect()->route('vigencia.ordenesservicio.index');
    }
}
