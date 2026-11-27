<?php

namespace App\Livewire\Afiliacion\ExamenMedico;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use App\Services\ValidarPersona;
use App\Services\ConexionAsuss;
use Livewire\Attributes\Validate;
use App\Models\Afiliacion\Titular;
use App\Models\Administracion\Zona;
use App\Models\Administracion\Residencia;
use App\Models\Administracion\Persona;
use App\Models\Administracion\TipoAsegurado;
use App\Models\Afiliacion\ExMedicoBeneficiario;
use Carbon\Carbon;
use Livewire\Attributes\On;

class ExamenMedicoNuevo extends Component
{
    #[Locked]
    public $idTitular;
    public $estado_api;
    

    //ASUSS
    public $ci;
    public $complemento;
    public $nombre;
    public $p_apellido;
    public $s_apellido;
    public $fecha_nacimiento;
    public $matricula_seguro;
    public $sexo;
    
    // Atributos para Titular
    public $matricula_titular;
    public $estado_vigencia;
    public $estado_requisitos;
    public $titular;
    public $ci_titular;
    public $complemento_titular;
    public $telefono_titular;
    public $sangre_titular;
    public $direccion_titular;
    public $zona_titular;
    public $id_persona_titular;

    public $nro_tramite;
    public $procedencia;
    public $fecha_tramite;

    public $fecha_solicitud;
    public $fecha_fin;
    public $id_tipo_asegurado;
    public $tipo_asegurado;
    public $id_asegurado;

    protected $listeners = [ 'search_persona'];

    public function render()
    {
        $persona = Persona::all();
        $residencia = Residencia::all();
        $zonasSelect = Zona::all();
        $aseguradoSelect = TipoAsegurado::all();
        $datosTitular =$this->mostrarDatosTitular($this->idTitular);
        return view('livewire.Afiliacion.examen-medico.examen-medico-nuevo',['datosTitular' => $datosTitular, 'persona'=> $persona, 'residencia'=>$residencia, 'zonasSelect'=>$zonasSelect, 'aseguradoSelect'=>$aseguradoSelect]);
    }
    public function mount()
    {
        $this->fecha_solicitud = null;
        $this->fecha_fin = null;
    }

    public function emitirFechaFin()
    {
        $this->fecha_fin = Carbon::parse($this->fecha_solicitud)
            ->addDays(30)
            ->format('Y-m-d');
    }

    public function mostrarDatosTitular($idTitular)
    {
        
        if ($idTitular) {

            $titular = Titular::find($idTitular);
            
            $this->sangre_titular = $titular->grupo_sanguineo;
            $this->estado_vigencia = $titular->estado_vigencia;
            $this->estado_requisitos = $titular->estado_requisitos;
            $this->telefono_titular = $titular->telefono;

            $personaActual = Persona::find($titular->id_persona);
            $this->id_persona_titular = $personaActual->id;
            $this->nombre_titular = $personaActual->nombres;
            $this->ap_paterno = $personaActual->p_apellido;
            $this->ap_materno = $personaActual->s_apellido;
            $this->ci_titular = $personaActual->ci;
            $this->matricula_titular = $personaActual->matricula_seguro;
            $this->complemento_titular =$personaActual->complemento;
            $this->titular = $this->nombre_titular . ' ' . $this->ap_paterno . ' ' . $this->ap_materno;

            $residenciaActual = Residencia::find($titular->id_residencia);
            $this->direccion_titular = $residenciaActual->direccion;

            $zonaActual = Zona::find($residenciaActual->id_zona);
            $this->zona_titular = $zonaActual->nombre;

            $tipo_aseguradoActual = TipoAsegurado::find($personaActual->id_tipo_asegurado);
            $this->tipo_asegurado = $tipo_aseguradoActual->tipo_asegurado;
            $this->id_asegurado = $tipo_aseguradoActual->id;

            return $titular;

        }
    }
    public function search_persona()
    {

        $respuesta = new ValidarPersona();
        $dato = $respuesta->buscarPersona($this->ci,$this->complemento);

        if(isset($dato['datosPersonaEnFormatoJson'])){

            $this->ci=$dato['datosPersonaEnFormatoJson']['numeroDocumento'];
            $this->complemento=$dato['datosPersonaEnFormatoJson']['Complemento'];
            $this->p_apellido=$dato['datosPersonaEnFormatoJson']['primerApellido'];
            $this->s_apellido=$dato['datosPersonaEnFormatoJson']['segundoApellido'];
            $this->nombre=$dato['datosPersonaEnFormatoJson']['nombres'];
            $this->fecha_nacimiento=$dato['datosPersonaEnFormatoJson']['fechaNacimiento'];
            $this->estado_api = 1;
        } else {
            $this->estado_api = 0;
            $this->nombre = '';
            $this->p_apellido = '';
            $this->s_apellido = '';
            $this->fecha_nacimiento = '';
            $this->ci = '';
            $this->complemento_m = '';
        }

        $this->esperandoDatos = false;
    }
    public function GenerarMatricula()
    {

        $nombre=$this->nombre;
        $p_apellido=$this->p_apellido;
        $s_apellido=$this->s_apellido;
        $fecha_nacimiento=$this->fecha_nacimiento;
        $sexo=$this->sexo;
        $fechaNacimiento = Carbon::createFromFormat('d/m/Y', $fecha_nacimiento);
            //dd($nombre,$p_apellido,$s_apellido,$fecha_nacimiento,$sexo);
        $anioNacimiento = $fechaNacimiento->format('Y');
        $mesNacimiento = $fechaNacimiento->format('m');
        $diaNacimiento = $fechaNacimiento->format('d');
        $sexoEsMasculino = $sexo === false || $sexo === 'masculino';
        $sexoEsFemenino = $sexo === true || $sexo === 'femenino';

        if(strlen($p_apellido)==0)
        {
            $apellido1 = substr($s_apellido, 0, 1);
            $apellido2 = substr($s_apellido, 1, 1);
        }
        if(strlen($s_apellido)==0)
        {
            $apellido1 = substr($p_apellido, 0, 1);
            $apellido2 = substr($p_apellido, 1, 1); 
        }
        if(strlen($p_apellido)!=0 && strlen($s_apellido)!=0)
        {
            $apellido1 = substr($p_apellido, 0, 1);
            $apellido2 = substr($s_apellido, 0, 1);

        }
        $nombre1 = substr($nombre, 0, 1);
        $matricula = '';
        $matricula .= substr($anioNacimiento, -2);
        if ($sexoEsMasculino) {
            $matricula .= str_pad($mesNacimiento, 2, '0', STR_PAD_LEFT);
        } elseif ($sexoEsFemenino) {
            $matricula .= str_pad(50+$mesNacimiento, 2, '0', STR_PAD_LEFT);
        }
        $matricula .= $diaNacimiento;
        $matricula .= $apellido1;
        $matricula .= $apellido2;
        $matricula .= $nombre1;
        $id_persona=Persona::where('ci','=',$this->ci)->where('complemento','=',$this->complemento)->first();

        if($id_persona==null)
        {
            $id_pesona_e=0;
        }
        else
        {
            $id_pesona_e=$id_persona->id;
        }
        $dato_primera_afiliacion=Titular::where('id_persona','=',$id_pesona_e)->orderby('id','asc')->first();
        if($dato_primera_afiliacion!=null)
        {
            $this->fecha_ingreso_ssu=Carbon::parse($dato_primera_afiliacion->fecha_afiliacion)->format('Y-m-d');
            $this->fecha_reafiliacion=Carbon::now()->format('Y-m-d');

        }
        else{
            $this->fecha_ingreso_ssu=Carbon::now()->format('Y-m-d');
            $this->fecha_reafiliacion=Carbon::now()->format('Y-m-d');

        }
        // dd($dato_primera_afiliacion, $this->fecha_ingreso_ssu,$this->fecha_reafiliacion);
        $this->matricula_seguro=$matricula;
        
               
    }
    #[On('GuardarExamen')]
    public function GuardarExamen(){
        //dd($this->idTitular);

        $existePersona = Persona::where('ci', $this->ci)
             ->where('complemento', $this->complemento)
             ->where('nombres', $this->nombre)
             ->where('p_apellido', $this->p_apellido)
             ->where('s_apellido', $this->s_apellido)
             ->where('fecha_nacimiento', $this->fecha_nacimiento)
             ->first();
             if($existePersona){
                $existePersona->update(['sexo'=>$this->sexo,'matricula_seguro'=>$this->matricula_seguro,'id_tipo_asegurado'=>$this->id_asegurado, ]);
                 $personaId = $existePersona->id;
                // dd($existePersona);
             }
             else
            {
                if($this->complemento == null){
                    $clave_unica = $this->ci;
                } else {
                    $clave_unica = $this->ci.$this->complemento;
                }

                $persona = Persona::create([
                    'ci' => $this->ci,
                    'complemento' => $this->complemento,
                    'nombres' => $this->nombre,
                    'p_apellido' => $this->p_apellido,
                    's_apellido' => $this->s_apellido,
                    'fecha_nacimiento' => $this->fecha_nacimiento,
                    'estado_asuss' => 1,
                    'id_tipo_asegurado'=> $this->id_asegurado,
                    'sexo'=>$this->sexo,
                    'matricula_seguro'=>$this->matricula_seguro,
                    'clave_unica' => $clave_unica,
                    'id_user_created' => auth()->user()->id,
                ]);

                $personaId = $persona->id;
                
            }
             

        $titular = ExMedicoBeneficiario::create([
            'estado'=>0,
            'estado_solicitud'=> 'SOLICITADO',
            'fecha_solicitud'=>$this->fecha_solicitud,
            'fecha_fin'=>$this->fecha_fin,
            'nro_tramite'=>$this->nro_tramite,
            'fecha_liq'=>$this->fecha_tramite,
            'procedencia'=>$this->procedencia,
            
            'id_persona'=>$personaId,
            'id_titular'=>$this->idTitular,
            'id_user_created'=>auth()->user()->id,
        ]);
        
    }


}
