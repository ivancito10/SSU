<?php

namespace App\Livewire\Vigencia\Convenios\Interior;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Locked;

use App\Models\User;
use App\Models\Administracion\Persona;
use App\Models\Administracion\TipoAsegurado;
use App\Models\Administracion\EstadoCivil;
use App\Models\Administracion\Especialidad;
use App\Models\Administracion\Servicio;
use App\Models\Vigencia\AutorizacionInterior;
use App\Models\Vigencia\AutorizacionIntEspecialidad;
use App\Models\Vigencia\AutorizacionIntServicio;
use App\Models\Vigencia\SeguroInterior;
use App\Services\ConveniosGehma2;
use Livewire\Attributes\Validate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ConvenioInteriorEdit extends Component
{
    public $idAuto;
    //DATOS DE LA AUTORIZACIONs
    #[Validate]
    public $cod_interior,
        $codInte,
        $fecha_inicio,
        $fecha_fin,
        $nombre_seguro,
        $otras_limitaciones,
        $id_persona_interior,
        $id_persona_titular,
        $id_personal_ssu,
        $foto_nombre,
        $observacion,
        $medicamentos_liname,
        $medicamentos_extraliname,
        $estado,
        $nro_hoja_de_ruta,
        $fecha_solicitud_hr,
        $cod_cite_int,
        $estado_validacion,
        $fecha_validacion,
        $id_user_created,
        $created_at,
        $fecha_anulacion,
        $motivo_anulacion,
      
        //DATOS DE LA PERSONA SOLICITANTE
        $ci,
        $nombres,
        $p_apellido,
        $s_apellido,
        $nombreCompleto,
        $complemento,
        $matricula_seguro,
        $fecha_nacimiento,
        $sexo,
        $id_tipo_asegurado,
        $tipo_asegurado,
        $id_estado_civil,
        $estado_civil,
        $es_extranjero,
        $edadPersona;

        public $id_seguro_interior;  
        public $especialidades = [];
        public $servicios = [];
        public $seguros;
        public $especialidadesSelect = [];
        public $serviciosSelect = [];

        public $usuarioNombre;
        public $personaVal;
        public $personaAnulado;

        protected $listeners = ['editarConvenioInterior'];

    public function rules()
    {
        return [
        'nro_hoja_de_ruta' => 'required',
        'fecha_solicitud_hr' => 'required|date',
        'cod_cite_int' => 'required',
        'codInte' => 'required',

        
        ];
    }
    public function render()
    {
        return view('livewire.Vigencia.convenios.interior.convenio-interior-edit');
    }

    

    public function mount(){
        $this->seguros = SeguroInterior::where('estado', true)->get();
        $this->especialidadesSelect = Especialidad::orderBy('especialidad','asc')->get();
        $this->serviciosSelect = Servicio::orderBy('servicio','asc')->get();
        $this->mostrarAutorizacion($this->idAuto);
    }

    public function mostrarAutorizacion($idAutorizacion)
    {
        if ($idAutorizacion) {
            $autorizacion = AutorizacionInterior::find($idAutorizacion);
            $this->cod_interior = $autorizacion->cod_interior;
            $this->codInte = $this->cod_interior . '/' . Carbon::parse($autorizacion->created_at)->format('Y');
            $this->fecha_inicio = $autorizacion->fecha_inicio;
            $this->fecha_fin = $autorizacion->fecha_fin;
            $seguroInterior = SeguroInterior::find($autorizacion->id_seguro_interior);
            $this->id_seguro_interior = $seguroInterior->id;
            $this->nombre_seguro = $seguroInterior->nombre_seguro;
            $this->otras_limitaciones = $autorizacion->otras_limitaciones;
            //DATOS DE LA PERSONA SOLICITANTE
            $personaInterior = Persona::find($autorizacion->id_persona_interior);
            //$this->ci = $personaInterior->ci;
            $this->complemento = $personaInterior->complemento;
            if($personaInterior->complemento == null){
                $this->ci = $personaInterior->ci;
            } else {
                $this->ci = $personaInterior->ci . ' - ' . $personaInterior->complemento;
            }
            $this->nombres = $personaInterior->nombres;
            $this->p_apellido = $personaInterior->p_apellido;
            $this->s_apellido = $personaInterior->s_apellido;
            $this->matricula_seguro = $personaInterior->matricula_seguro;
            $this->fecha_nacimiento = $personaInterior->fecha_nacimiento;
            if ($personaInterior->sexo == 'F') {
                $this->sexo = 'FEMENINO';
            } else {
                $this->sexo = 'MASCULINO';
            }
            $this->edadPersona = Carbon::parse($personaInterior->fecha_nacimiento)->age;
            $this->nombreCompleto = $this->p_apellido . ' ' . $this->s_apellido . ' ' . $this->nombres;
            $tipoAsegurado = TipoAsegurado::find($personaInterior->id_tipo_asegurado);
            $this->tipo_asegurado = $tipoAsegurado->tipo_asegurado;
            $estadoCivil = EstadoCivil::find($personaInterior->id_estado_civil);
            $this->estado_civil = $estadoCivil->nombre;
            if ($personaInterior->es_extranjero) {
                $this->es_extranjero = 'EXTRANJERO';
            } else {
                $this->es_extranjero = 'NACIONAL';
            }

            //SI TIENE TITULAR, DATOS DEL TITULAR
            $personaTitularInterior = Persona::find($autorizacion->id_persona_titular);
            $this->foto_nombre = $this->obtenerImagenBase64($autorizacion->foto_nombre);
            $this->observacion = $autorizacion->observacion;
            $this->medicamentos_liname = $autorizacion->medicamentos_liname;
            $this->medicamentos_extraliname = $autorizacion->medicamentos_extraliname;
            $this->estado = $autorizacion->estado;
            $this->nro_hoja_de_ruta = $autorizacion->nro_hoja_de_ruta;
            $this->fecha_solicitud_hr = $autorizacion->fecha_solicitud_hr;
            $this->cod_cite_int = $autorizacion->cod_cite_int;
            $this->estado_validacion = $autorizacion->estado_validacion;
            $this->fecha_validacion = $autorizacion->fecha_validacion;
            $this->id_user_created = $autorizacion->id_user_created;
            $this->created_at = $autorizacion->created_at;
            $this->fecha_anulacion = $autorizacion->fecha_anulacion;
            $this->motivo_anulacion = $autorizacion->motivo_anulacion;

            //PARA LAS ESPECIALIDADES Y SERVICIOS
            $esps = AutorizacionIntEspecialidad::where('id_autorizacion_interior', $autorizacion->id)->get();
            if (!$esps->isEmpty()) {
                foreach ($esps as $key => $esp) {
                    $this->especialidades[] = $esp->id_especialidad;
                }  
            } 


            //dd($this->especialidades);
            $servs = AutorizacionIntServicio::where('id_autorizacion_interior', $autorizacion->id)->get();

            if (!$servs->isEmpty()) {
                foreach ($servs as $key => $serv) {
                    $this->servicios[] = $serv->id_servicio;
                }                
            } 

            $usuarioReg = User::find($autorizacion->id_user_created);
            $usuarioDatos = Persona::find($usuarioReg->id_persona);
            $this->usuarioNombre = $usuarioDatos->p_apellido . ' ' . $usuarioDatos->s_apellido . ' ' . $usuarioDatos->nombres;

            if ($autorizacion->id_user_validado != null) {
                $usuarioValidado = User::find($autorizacion->id_user_validado);
                $personaValidado = Persona::find($usuarioValidado->id_persona);
                $this->personaVal = $personaValidado->p_apellido . ' ' . $personaValidado->s_apellido . ' ' . $personaValidado->nombres;
            } else {
                $this->personaVal = null;
            }

            //ANULACION
            //dd($solicitud->id_user_anulado);
            if ($autorizacion->id_user_anulado != null) {
                $usuarioAnula = User::find($autorizacion->id_user_anulado);
                $personaAnulado = Persona::find($usuarioAnula->id_persona);
                $this->personaAnulado = $personaAnulado->p_apellido . ' ' . $personaAnulado->s_apellido . ' ' . $personaAnulado->nombres;
            } else {
                $this->personaAnulado = null;
            }
        }
    }

    public function editarConvenioInterior($otraslimitaciones, $observaciones)
    {   
        $this->otras_limitaciones = $otraslimitaciones;
        $this->observacion = $observaciones;
        //dd('entra',$this->otras_limitaciones,$this->observacion);
        DB::beginTransaction();
        $this->validate();
        try {
            $autorizacion = AutorizacionInterior::find($this->idAuto);
            if ($autorizacion) {
                $autorizacion->cod_cite_int = $this->cod_cite_int;
                $autorizacion->fecha_inicio = $this->fecha_inicio;
                $autorizacion->fecha_fin = $this->fecha_fin;
                $autorizacion->id_seguro_interior = $this->id_seguro_interior;
                $autorizacion->otras_limitaciones = $this->otras_limitaciones;
                $autorizacion->observacion = $this->observacion;
                $autorizacion->medicamentos_liname = $this->medicamentos_liname;
                $autorizacion->medicamentos_extraliname = $this->medicamentos_extraliname;
                $autorizacion->nro_hoja_de_ruta = $this->nro_hoja_de_ruta;
                $autorizacion->fecha_solicitud_hr = $this->fecha_solicitud_hr;
                $autorizacion->id_user_updated = auth()->user()->id;
                $autorizacion->save();
            }

            AutorizacionIntEspecialidad::where('id_autorizacion_interior', $this->idAuto)->delete();
            if(!empty($this->especialidades)){
                foreach ($this->especialidades as $especialidad) { 
                    AutorizacionIntEspecialidad::create([
                        'id_autorizacion_interior' => $this->idAuto,
                        'id_especialidad' => $especialidad,
                        'id_user_created' => auth()->user()->id,
                        'id_user_updated' => auth()->user()->id,
                    ]);
                }
            }
            
            AutorizacionIntServicio::where('id_autorizacion_interior', $this->idAuto)->delete();
            if(!empty($this->servicios)){
                foreach ($this->servicios as $servicio) { 
                    AutorizacionIntServicio::create([
                        'id_autorizacion_interior' => $this->idAuto,
                        'id_servicio' => $servicio,
                        'id_user_created' => auth()->user()->id,
                        'id_user_updated' => auth()->user()->id,
                    ]);
                }
            }

            DB::commit();
            $insercion = new ConveniosGehma2();
            $res = $insercion->insercionConveniosGehma2($this->idAuto);
            $this->dispatch('actualizado');
            return redirect()->route('vigencia.convenios.interior.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('Noactualizado');
            return redirect()->route('vigencia.convenios.interior.index');
        }
    }   

    private function obtenerImagenBase64($idFoto)
    {
        try {
            if ($idFoto) {
                $path = 'Vigencia/FOTOS/CONVENIO/' . $idFoto;
                $imageData = Storage::disk('ftp')->get($path);
                $foto = base64_encode($imageData);
                return $foto;
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            return null;
        }
        
    }

    public function cancelarEdicionAutorizacion()
    {
        $this->dispatch('AutorizacionNoEditada');
        return redirect()->route('vigencia.convenios.interior.index');
    }

    public function atras()
    {
        return redirect()->route('vigencia.convenios.interior.index');
    }
}
