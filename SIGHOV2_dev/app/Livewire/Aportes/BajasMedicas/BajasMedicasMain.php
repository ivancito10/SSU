<?php

namespace App\Livewire\Vigencia\BajasMedicas;

use Livewire\Component;
use Livewire\Attributes\On;
use Carbon\Carbon;

use App\Models\Vigencia\BajaMedica;
use App\Models\Administracion\Especialidad;
use App\Models\Plataforma\Especialista;
use App\Models\Administracion\Persona;
use App\Models\User;
use App\Models\Administracion\TipoAsegurado;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\TitularInstitucion;
use App\Models\Aportes\Institucion;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;


use App\Services\ValidarPersona;
use App\Services\RegistroBajasMedicas;
use App\Services\ConexionAsuss;
use Livewire\Attributes\Validate;

class BajasMedicasMain extends Component
{
    #[Locked]
    public $idBaja;

    protected $listeners = ['guardarValidacion', 'guardarAnulacion', 'guardarEntregaBajaMedica', 'search_persona'];


    #[Locked]
    public $fecha_hoy;
    public function render()
    {
        return view('livewire.Vigencia.bajas-medicas.bajas-medicas-main');
    }



    public function mount()
    {

        $this->fecha_hoy = Carbon::now()->format('d/m/Y');
    }

    //   validarBajaMedica
    #[On('validarBajaMedica')]
    public function validarBajaMedica($data)
    {
        $this->idBaja = $data;
        $this->dispatch('alertaValidar');
    }
    public function guardarValidacion()
    {
        try {
            $baja = BajaMedica::find($this->idBaja);

            if ($baja) {
                $baja->estado_validacion = 'VALIDADO';
                $baja->fecha_validacion = $this->fecha_hoy;
                $baja->id_user_validado = auth()->user()->id;
                $baja->save();

                $this->dispatch('pg:eventRefresh-BajaMedicaTable');
                $this->dispatch('pg:eventRefresh-ComprobanteAportesInstitucionales');
                $this->dispatch('guardado');
            }
        } catch (\Throwable $th) {
            $this->dispatch('noValidado');
        }
    }

    //   anularBajaMedica
    #[On('anularBajaMedica')]
    public function anularBajaMedica($data)
    {
        $this->idBaja = $data;
        $this->dispatch('alertaAnular');
    }
    public function guardarAnulacion($data)
    {

        try {
            $baja = BajaMedica::find($this->idBaja);

            if ($baja) {
                $baja->estado_validacion = 'ANULADO';
                $baja->observacion = $data;
                $baja->id_user_anulado = auth()->user()->id;
                $baja->fecha_anulado = $this->fecha_hoy;
                $baja->save();

                $this->dispatch('pg:eventRefresh-BajaMedicaTable');
                $this->dispatch('guardado');
            }
        } catch (\Throwable $th) {
            $this->dispatch('noValidado');
        }
    }

    // verBajaMedica
    #[On('verBajaMedica')]
    public function verBajaMedica($data)
    {
        $this->idBaja = $data;
        $this->mostrarBajaMedica($this->idBaja);
        $this->dispatch('modalVerBajaMedica');
    }



    public $estado_vigencia;

    #[Locked]
    public $cite, $fecha_recepcion, $fecha_inicio, $fecha_fin,
        $observacion, $diagnostico, $estado_validado, $especialidad, $especialista,
        $nombreCompleto, $matricula, $complemento, $tipo_asegurado, $foto, $ci,
        $institucion, $id_persona_entregado, $id_user_created, $created_at, $estado_registro;

    public $user_validado, $fecha_validacion, $user_anulado, $fecha_anulado,
        $fecha_homologado, $user_entregado;
    // mostrar datos de la baja medica 
    public function mostrarBajaMedica($id)
    {
        if ($id) {
            $baja = BajaMedica::find($id);

            $this->cite = $baja->cod_baja_medica;
            $this->fecha_recepcion = $baja->fecha_recepcion;
            $this->fecha_inicio = $baja->fecha_inicio;
            $this->fecha_fin = $baja->fecha_fin;
            $id_user_created = $baja->id_user_created;

            if ($id_user_created != null) {
                $usuario_crea = User::find($id_user_created);
                $id_persona_user = $usuario_crea->id_persona;
                $this->created_at = $baja->created_at;

                $userCreatedActual = Persona::find($id_persona_user);
                $this->id_user_created = $userCreatedActual->nombres . ' ' . $userCreatedActual->p_apellido . ' ' . $userCreatedActual->s_apellido;
            } else {

                $this->reset('id_user_created');
            }

            $this->observacion = $baja->observacion;
            $this->diagnostico = $baja->diagnostico;
            $this->estado_validado = $baja->estado_validacion;
            $per_afiliada = $baja->id_persona;

            // logica para mostrar los estados

            $this->fecha_validacion = $baja->fecha_validacion;
            $this->estado_registro = $baja->estado_registro;
            $id_user_validado = $baja->id_user_validado;



            if ($id_user_validado != null) {
                $uvActual = User::find($id_user_validado);
                $uv_persona = $uvActual->id_persona;

                $puvActual = Persona::find($uv_persona);
                $this->user_validado = $puvActual->nombres . ' ' . $puvActual->p_apellido . ' ' . $puvActual->s_apellido;
            } else {

                $this->reset('user_validado');
            }



            $this->fecha_anulado = $baja->fecha_anulado;
            $id_user_anulado = $baja->id_user_anulado;

            if ($id_user_anulado != null) {
                $uaActual = User::find($id_user_anulado);
                $ua_persona = $uaActual->id_persona;

                $puaActual = Persona::find($ua_persona);
                $this->user_anulado = $puaActual->nombres . ' ' . $puaActual->p_apellido . ' ' . $puaActual->s_apellido;
            } else {

                $this->reset('user_anulado');
            }



            $this->fecha_homologado = $baja->fecha_homologado;
            $id_persona_entregado = $baja->id_persona_entregado;


            if ($id_persona_entregado != null) {
                $personaEntregado = Persona::find($id_persona_entregado);
                $this->id_persona_entregado = $personaEntregado->nombres . ' ' . $personaEntregado->p_apellido . ' ' . $personaEntregado->s_apellido;
            } else {

                $this->reset('id_persona_entregado');
            }

            $id_user_entregado = $baja->id_user_entregado;

            if ($id_user_entregado != null) {
                $ueActual = User::find($id_user_entregado);
                $ue_persona = $ueActual->id_persona;

                $pueActual = Persona::find($ue_persona);
                $this->user_entregado = $pueActual->nombres . ' ' . $pueActual->p_apellido . ' ' . $pueActual->s_apellido;
            } else {

                $this->reset('user_entregado');
            }



            $especialidadActual = Especialidad::find($baja->id_especialidad);
            $this->especialidad = $especialidadActual->especialidad;


            $especialistaActual = Especialista::find($baja->id_especialista);
            $id_especialista = $especialistaActual->id_persona;

            $personaActual = Persona::find($id_especialista);
            $this->especialista = $personaActual->nombres . ' ' . $personaActual->p_apellido . ' ' . $personaActual->s_apellido;


            $personaAfiliada = Persona::find($per_afiliada);
            $this->nombreCompleto = $personaAfiliada->nombres . ' ' . $personaAfiliada->p_apellido . ' ' . $personaAfiliada->s_apellido;
            $this->matricula = $personaAfiliada->matricula_seguro;
            $this->ci = $personaAfiliada->ci;
            $this->complemento = $personaAfiliada->complemento;

            $tipoAsegurado = TipoAsegurado::find($personaAfiliada->id_tipo_asegurado);
            $this->tipo_asegurado = $tipoAsegurado->tipo_asegurado;

            $esTitular = Titular::where('id_persona', $per_afiliada)->where('estado', true)->first();
            if ($esTitular) {
                $this->estado_vigencia = $esTitular->estado_vigencia;

                $this->foto = $this->obtenerImagenBase64($esTitular->foto_nombre);


                $titularInstitucion = TitularInstitucion::where('id_titular', $esTitular->id)
                    ->where('estado', true)
                    ->first();
                if ($titularInstitucion) {
                    $inst = Institucion::find($titularInstitucion->id_institucion);
                    $this->institucion = $inst->nombre;
                    $id_institucion = $inst->id;
                } else {
                    $this->institucion = 'APORTANTE VOLUNTARIO';
                }
            } else {
                $esBeneficiario = Beneficiario::where('id_persona', $per_afiliada)->where('estado', true)->first();
                $this->foto = $this->obtenerImagenBase64($esBeneficiario->foto_nombre);
                $this->estado_vigencia = $esBeneficiario->vigencia;

                $titularBen = Titular::find($esBeneficiario->id_titular);
                $titularInstBen = TitularInstitucion::where('id_titular', $titularBen->id)
                    ->where('estado', true)
                    ->first();
                if ($titularInstBen) {
                    $inst = Institucion::find($titularInstBen->id_institucion);
                    $this->institucion = $inst->nombre;
                    $id_institucion = $inst->id;
                } else {
                    $this->institucion = 'APORTANTE VOLUNTARIO';
                }
            }

            return $baja;
        }
    }
    private function obtenerImagenBase64($idFoto)
    {
        try {
            if ($idFoto) {
                $path = 'Afiliacion/FOTOS/' . $idFoto;

                $imageData = Storage::disk('ftp')->get($path);
                //dd($imageData);
                $foto = base64_encode($imageData);
                return $foto;
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            return null;
        }
    }




    // entregarBajaMedica
    #[On('entregarBajaMedica')]
    public function entregarBajaMedica($data)
    {
        $this->idBaja = $data;
        $this->dispatch('modalEntregarBajaMedica');
    }
    //registro por ci
    public function rules()
    {
        return [

            'complemento_m' => 'required',
            'carnet' => 'required',
            'ap_paterno' => 'required',
            'ap_materno' => 'required',
            'nombre' => 'required',
            'fecha_nacimiento' => 'required',

        ];
    }

    public $estado_api;
    public $esperandoDatos = false;
    //ASUSS
    #[Validate]
    public $carnet;
    #[Validate]
    public $complemento_m;
    #[Validate]
    public $ap_paterno;
    #[Validate]
    public $ap_materno;
    #[Validate]
    public $nombre;
    #[Validate]
    public $fecha_nacimiento;
    public $alerta;

    public function search_persona($carnet, $complemento)
    {
        $respuesta_con = new ValidarPersona();
        $dato_con = $respuesta_con->buscarPersona('3434797', null);
        if (isset($dato_con['error'])) {
            $conexionAsuss_con = new ConexionAsuss;
            $logeo_con = $conexionAsuss_con->login();
        }

        $respuesta = new ValidarPersona();
        $dato = $respuesta->buscarPersona($carnet, $complemento);

        if (isset($dato['datosPersonaEnFormatoJson'])) {
            $this->estado_api = 1;
            $this->alerta = 1;

            $this->carnet = $dato['datosPersonaEnFormatoJson']['numeroDocumento'];
            $this->complemento_m = $dato['datosPersonaEnFormatoJson']['Complemento'];
            $this->ap_paterno = $dato['datosPersonaEnFormatoJson']['primerApellido'];
            $this->ap_materno = $dato['datosPersonaEnFormatoJson']['segundoApellido'];
            $this->nombre = $dato['datosPersonaEnFormatoJson']['nombres'];
            $this->fecha_nacimiento = $dato['datosPersonaEnFormatoJson']['fechaNacimiento'];
        } else {
            $this->estado_api = 0;
            $this->alerta = 0;
            $this->nombre = '';
            $this->ap_paterno = '';
            $this->ap_materno = '';
            $this->fecha_nacimiento = '';
            $this->carnet = '';
            $this->complemento_m = '';
        }

        $this->esperandoDatos = false;
        $this->skipRender();
    }


    public $observaciones;
    // GUARDAR ENTREGAR BAJA MEDICA
    public function guardarEntregaBajaMedica()
    {

        $baja = BajaMedica::find($this->idBaja);
        DB::beginTransaction();
        if ($baja) {
            //dd($this->carnet);
            $existePersona = Persona::where('ci', $this->carnet)
                ->where('complemento', $this->complemento_m)
                ->where('nombres', $this->nombre)
                ->where('p_apellido', $this->ap_paterno)
                ->where('s_apellido', $this->ap_materno)
                ->first();



            if ($existePersona) {
                $personaId = $existePersona->id;
            } else {

                if ($this->complemento_m == null) {
                    $clave_unica = $this->carnet;
                } else {
                    $clave_unica = $this->carnet . $this->complemento_m;
                }

                $persona = Persona::create([
                    'ci' => $this->carnet,
                    'complemento' => $this->complemento_m,
                    'nombres' => $this->nombre,
                    'p_apellido' => $this->ap_paterno,
                    's_apellido' => $this->ap_materno,
                    'fecha_nacimiento' => $this->fecha_nacimiento,
                    'estado_asuss' => 1,
                    'clave_unica' => $clave_unica,

                    /* 'afiliado' => 1,
                        'sexo'=>$this->sexo, */

                    'id_user_created' => auth()->user()->id,
                ]);
                if ($persona) {
                    DB::commit();
                    $personaId = $persona->id;
                } else {
                    DB::rollBack();
                }
            }



            $baja->estado_validacion = 'HOMOLOGADO';
            $baja->id_user_entregado = auth()->user()->id;
            $baja->fecha_homologado = $this->fecha_hoy;
            $baja->id_persona_entregado = $personaId;
            $baja->observacion = $this->observaciones;

            $baja->save();

            $this->dispatch('pg:eventRefresh-BajaMedicaTable');
            $this->dispatch('guardado');
        }



        /* } catch (\Throwable $th) {
            $this->dispatch('noValidado');
        } */
    }

    public function abrirLista()
    {
        return redirect()->route('vigencia.bajasmedicaslistaafiliados.lista');
    }

  
}
