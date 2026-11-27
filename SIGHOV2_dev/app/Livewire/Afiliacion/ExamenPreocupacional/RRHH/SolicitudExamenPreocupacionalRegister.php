<?php

namespace App\Livewire\Afiliacion\ExamenPreocupacional\RRHH;

use Livewire\Component;
use App\Services\ValidarPersona;
use App\Services\ConexionAsuss;
use Carbon\Carbon;
use Livewire\Attributes\Locked;
use App\Models\Afiliacion\Preocupacional;
use App\Models\Administracion\Persona;
class SolicitudExamenPreocupacionalRegister extends Component
{

    public $ci_b;
    public $complemento_b;
    #[locked]
    public $estado_api;
    #[locked]
    public $nombre;
    #[locked]
    public $ap_paterno;
    #[locked]
    public $ap_materno;
    #[locked]
    public $fecha_nacimiento;
    #[locked]
    public $ci;
    #[locked]
    public $complemento_m;
    public $esperandoDatos;
    #[locked]
    public $matricula;
    public $sexo;

    //datos formulario
 
    public $fecha_solicitud;
    #[locked]
    public $fecha_fin;
    public $fecha_inicio_contrato;
    public $cargo;
    public $observacion;
    public function search_persona()
    {
        $respuesta = new ValidarPersona();
        $dato = $respuesta->buscarPersona($this->ci_b,$this->complemento_b);
        if(isset($dato['datosPersonaEnFormatoJson'])){
            $this->ci=$dato['datosPersonaEnFormatoJson']['numeroDocumento'];
            $this->complemento_m=$dato['datosPersonaEnFormatoJson']['Complemento'];
            $this->ap_paterno=$dato['datosPersonaEnFormatoJson']['primerApellido'];
            $this->ap_materno=$dato['datosPersonaEnFormatoJson']['segundoApellido'];
            $this->nombre=$dato['datosPersonaEnFormatoJson']['nombres'];
            $this->fecha_nacimiento=$dato['datosPersonaEnFormatoJson']['fechaNacimiento'];
            $this->estado_api = 1;
        } else {
            $this->estado_api = 0;
            $this->nombre = '';
            $this->ap_paterno = '';
            $this->ap_materno = '';
            $this->fecha_nacimiento = '';
            $this->ci = '';
            $this->complemento_m = '';
        }
        $this->esperandoDatos = false;
    }
    public function GenerarMatricula()
    {
      
                $nombre=$this->nombre;
                $p_apellido=$this->ap_paterno;
                $s_apellido=$this->ap_materno;
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

                // dd($dato_primera_afiliacion, $this->fecha_ingreso_ssu,$this->fecha_reafiliacion);
                $this->matricula=$matricula;
                $this->skipRender();
               
    }
    public function calcularFechaFin(){

        $this->fecha_fin=Carbon::parse($this->fecha_solicitud)->addDays(30)->format('Y-m-d');

    }
    public function guardarExamenPreocupacional(){
        if($this->sexo==true){
            $this->sexo="F";
        }
        else
        {
            $this->sexo="M";
        }
        try {
            $existePersona = Persona::where('ci', $this->ci)
            ->where('complemento', $this->complemento_m)
            ->where('nombres', $this->nombre)
            ->where('p_apellido', $this->ap_paterno)
            ->where('s_apellido', $this->ap_materno)
            ->where('fecha_nacimiento', $this->fecha_nacimiento)
            ->first();
            if($existePersona){
                $personaId = $existePersona->id;
            }
            else
            {
                if($this->complemento_m == null){
                    $clave_unica = $this->ci;
                } else {
                    $clave_unica = $this->ci.$this->complemento_m;
                }
    

                $persona = Persona::create([
                    'ci' => $this->ci,
                    'complemento' => $this->complemento_m,
                    'nombres' => $this->nombre,
                    'p_apellido' => $this->ap_paterno,
                    's_apellido' => $this->ap_materno,
                    'fecha_nacimiento' => $this->fecha_nacimiento,
                    'estado_asuss' => 1,
                    'sexo' => $this->sexo,
                    'matricula' => $this->matricula,
                    'id_tipo_asegurado' =>0,
                    'clave_unica' => $clave_unica,
                    'id_user_created' => auth()->user()->id,
                ]);
    
                $personaId = $persona->id;
            }
            $solicitud_examen = Preocupacional::create([
                'fecha_contrato'=>$this->fecha_inicio_contrato,
                'cargo'=>$this->cargo,
                'fecha_solicitud'=>$this->fecha_solicitud,
                'fecha_fin'=>$this->fecha_fin,
                'id_estado_solicitud'=>5,
                'observaciones'=>$this->observacion,
                'estado'=>0,
                'id_persona'=>$personaId,
                'id_personal_rrhh_ext'=>auth()->user()->id,
                'id_user_created' => auth()->user()->id,
            ]);
            return redirect()->route('afiliacion.examen-preocupacional.instituciones.index')->with('evento', 'ExamenRegistrado');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('afiliacion.examen-preocupacional.instituciones.index')->with('evento', 'examenNoRegistrado');
        }

       
       
    }
    public function render()
    {
        return view('livewire.Afiliacion.examen-preocupacional.r-r-h-h.solicitud-examen-preocupacional-register');
    }
}
