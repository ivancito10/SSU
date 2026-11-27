<?php

namespace App\Livewire\Aportes\Externos\Institucion;

use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Aportes\Institucion;
use App\Models\Administracion\Departamento;
use App\Models\Administracion\Persona;
use App\Models\Administracion\Role;
use App\Models\Administracion\RolUsuario;
use App\Models\Administracion\NotificacionUsuarios;
use Livewire\Attributes\Validate;
use App\Services\ValidarPersona;
use App\Services\ConexionAsuss;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;

class InstitucionEditRepresentante extends Component
{
    use WithFileUploads;
    #[Validate]
    public $file_digital_ci;

    public $id_institucion,  $departamento;
    #[Validate]
    public $nombres;
    public $p_apellido;
    public $s_apellido;
    #[Validate]
    public $ci;
    #[Validate]
    public $fecha_nacimiento;
    public $complemento;
    public $complemento_b;
    public $ci_b;
    #[Validate]
    public $tipo_doc;
    public $expedido_ci_nombre;
    public $expedido_ci,  $ci_anterior;
    public $estado_asuss, $estado_resp;
    public $institucion, $nit;
    public $idPersonaMostrar;
    public $idPersonaBusqueda;
    public $validadoSegip = false;
    public $existePersona = false;
    public $estado_encontrado;


    public $nombres_m, $p_apellido_m, $s_apellido_m, $ci_m, $complemento_m, $fecha_nacimiento_m;



    public function render()
    {
        return view('livewire.Aportes.Externos.institucion.institucion-edit-representante');
    }


    public function mount()
    {

        $this->mostrarDatos($this->id_institucion);
        $this->departamento = Departamento::all();
    }

    public function rules()
    {
        return [
            'nombres' => 'required',
            'ci' => 'required|numeric',
            'fecha_nacimiento' => 'required',
            'tipo_doc' => 'required',
            'expedido_ci' => 'nullable|integer',
            'file_digital_ci' => 'required|mimes:pdf|max:1024',
        ];
    }

    public function mostrarDatos($idInstitucion)
    {
        $institucion = Institucion::with(['personaRepresentanteInstitucion.departamentoPersona'])->find($idInstitucion);
        $this->idPersonaMostrar = $institucion->personaRepresentanteInstitucion->id;
        $this->institucion = $institucion->nombre;
        $this->nit = $institucion->nit;
        $this->nombres_m = $institucion->personaRepresentanteInstitucion->nombres;
        $this->p_apellido_m = $institucion->personaRepresentanteInstitucion->p_apellido;
        $this->s_apellido_m = $institucion->personaRepresentanteInstitucion->s_apellido;
        $this->ci_m = $institucion->personaRepresentanteInstitucion->ci;
        $this->complemento_m = $institucion->personaRepresentanteInstitucion->complemento;
        $this->fecha_nacimiento_m = Carbon::parse($institucion->personaRepresentanteInstitucion->fecha_nacimiento)->format('d/m/Y') ;
        //$this->tipo_doc = $institucion->personaRepresentanteInstitucion->es_extranjero == false ? 0 : 1;
        //$this->expedido_ci = $institucion->personaRepresentanteInstitucion->id_dept_exp;
        
        $this->expedido_ci_nombre = $institucion->personaRepresentanteInstitucion->departamentoPersona ?
            $institucion->personaRepresentanteInstitucion->departamentoPersona->nombre :
            null;

        $this->existePersona = true;
    }

    public function buscarRepresentante()
    {

        $persona = Persona::where('ci', $this->ci_b)->where('complemento', $this->complemento_b)->first();
        if ($persona) {
            $this->idPersonaBusqueda = $persona->id;
            $this->nombres = $persona->nombres;
            $this->p_apellido = $persona->p_apellido;
            $this->s_apellido = $persona->s_apellido;
            $this->ci = $persona->ci;
            $this->complemento = $persona->complemento;
            $this->fecha_nacimiento = $persona->fecha_nacimiento;
            $this->tipo_doc = $persona->es_extranjero == false ? 0 : 1;
            $this->expedido_ci = $persona->id_dept_exp;
            
            $this->validadoSegip = $persona->estado_asuss;
            $this->existePersona = true;

            $this->estado_encontrado =1;
        } else {
            $respuesta = new ValidarPersona();
            $dato = $respuesta->buscarPersona($this->ci_b, $this->complemento_b);
            if (isset($dato['datosPersonaEnFormatoJson'])) {
                $this->ci = $dato['datosPersonaEnFormatoJson']['numeroDocumento'];
                $this->complemento = $dato['datosPersonaEnFormatoJson']['Complemento'];
                $this->p_apellido = $dato['datosPersonaEnFormatoJson']['primerApellido'];
                $this->s_apellido = $dato['datosPersonaEnFormatoJson']['segundoApellido'];
                $this->nombres = $dato['datosPersonaEnFormatoJson']['nombres'];
                $this->fecha_nacimiento = $dato['datosPersonaEnFormatoJson']['fechaNacimiento'];
                $this->tipo_doc = null;
                $this->expedido_ci = null;
                $this->validadoSegip = true;
                $this->existePersona = false;

                $this->estado_encontrado =1;
            } else {
                $this->ci = $this->ci_b;
                $this->complemento = $this->complemento_b;
                $this->nombres = '';
                $this->p_apellido = '';
                $this->s_apellido = '';
                $this->fecha_nacimiento = '';
                $this->tipo_doc = null;
                $this->expedido_ci = null;
                $this->validadoSegip = false;
                $this->existePersona = false;

                $this->estado_encontrado =0;
            }
        }
    }

    #[On('actualizarRepLegal')]
    public function actualizarRepLegal()
    {
        $this->validate();
        if ($this->existePersona == false) {
            $this->validate([
                'file_digital_ci' => 'required|mimes:pdf',
            ]);
        } else {
            if ($this->idPersonaBusqueda != $this->idPersonaMostrar) {
                $this->validate([
                    'file_digital_ci' => 'required|mimes:pdf',
                ]);
            }
        }

        DB::beginTransaction();
        try {
            
            if ($this->existePersona == false) {

                if ($this->complemento == null) {
                    $clave_unica = $this->ci;
                } else {
                    $clave_unica = $this->ci . $this->complemento;
                }
                if(empty($this->expedido_ci)){
                    $this->expedido_ci = null;
                }

                $personaNueva = Persona::create([
                    'p_apellido' => $this->p_apellido,
                    's_apellido' => $this->s_apellido,
                    'nombres' => $this->nombres,
                    'ci' => $this->ci,
                    'complemento' => $this->complemento,
                    'fecha_nacimiento' => $this->fecha_nacimiento,
                    'es_extranjero' => $this->tipo_doc,
                    'id_dept_exp' => $this->expedido_ci,
                    'clave_unica' => $clave_unica,
                    'estado_asuss' => $this->validadoSegip,
                    'id_user_created' => auth()->user()->id,
                ]);

                $idPersonaRegistrar = $personaNueva->id;
                $this->validate([
                    'file_digital_ci' => 'required|mimes:pdf',
                ]);
            } else {


                $clave = $this->ci.$this->complemento;
                
                $existe = Persona::where('clave_unica', $clave)
                ->first();

                if($existe == null){

                    if ($this->complemento == null || $this->complemento == "") {
                        $clave_unica = $this->ci;
                    } else {
                        $clave_unica = $this->ci . $this->complemento;
                    }
                    if(empty($this->expedido_ci)){
                        $this->expedido_ci = null;
                    }
                    $personaNueva = Persona::create([
                        'p_apellido' => $this->p_apellido,
                        's_apellido' => $this->s_apellido,
                        'nombres' => $this->nombres,
                        'ci' => $this->ci,
                        'complemento' => $this->complemento,
                        'fecha_nacimiento' => $this->fecha_nacimiento,
                        'es_extranjero' => $this->tipo_doc,
                        'id_dept_exp' => $this->expedido_ci,
                        'clave_unica' => $clave_unica,
                        'estado_asuss' => $this->validadoSegip,
                        'id_user_created' => auth()->user()->id,
                    ]);
    
                    $idPersonaRegistrar = $personaNueva->id;

                }elseif($existe != null){
                    if($existe->id_dept_exp == null  ||$existe->es_extranjero == null || $existe->fecha_nacimiento  ){
                       $existe->id_dept_exp = $this->expedido_ci;
                        $existe->es_extranjero = $this->tipo_doc;
                        $existe->fecha_nacimiento = $this->fecha_nacimiento; 
                        $existe->id_user_updated = auth()->user()->id;
                        $existe->save();
                        $idPersonaRegistrar = $existe->id;
                    }else{
                        $idPersonaRegistrar = $existe->id;
                    }

                }else{
                    if ($this->idPersonaBusqueda != $this->idPersonaMostrar) {
                    $idPersonaRegistrar = $this->idPersonaBusqueda;
                    } else {
                        $idPersonaRegistrar = $this->idPersonaMostrar;
                    }
                }

            }

            $institucion = Institucion::find($this->id_institucion);

            if ($institucion) {
                $institucion->id_persona = $idPersonaRegistrar;
                $institucion->id_user_updated = auth()->user()->id;
                $institucion->save();

                if ($this->file_digital_ci) {
                    //guardamos en el FT CI
                    $nombre_file_ci = $institucion->id . '_ci' . '.pdf';
                    $this->file_digital_ci->storeAs('Aportes/Institucion/RepresentanteLegal', $nombre_file_ci, 'ftp');
                }


                // para envio de notificaciones
                $rol = Role::where('name', 'Encargado Aportes')->first();
                //dd($rol);
                $receptores = RolUsuario::where('role_id', $rol->id)->get();

                foreach ($receptores as $receptor) {
                    $nuevaNotificacion = NotificacionUsuarios::create([
                        'estado' => 'NO LEIDO',
                        'prioridades' => 1,
                        'id_usu_emisor' => auth()->user()->id,
                        'id_usu_receptor' => $receptor->model_id, 
                        'fecha_hora_enviada' => Carbon::now(),
                        'asunto' => 'APORTES - EDICIÓN DE REPRESENTANTE LEGAL',
                        'mensaje' => ' Se editó el representante legal de la institución ' . $institucion->nombre,
                    ]);
                }
            }
            DB::commit();
            $this->dispatch('RepresenanteModificada');
            return redirect()->route('aportes.institucionesExternos.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('error');
        }
    }

    public function atras()
    {
        return redirect()->route('aportes.institucionesExternos.index');
    }
}
