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

use App\Models\Administracion\RolUsuario;
use App\Models\Administracion\NotificacionUsuarios;
use App\Models\Administracion\Role;

class BajasMedicasMain extends Component
{
    #[Locked]
    public $idBaja;
    public $fechaInicio, $fechaFin;

    protected $listeners = ['guardarValidacion', 'guardarAnulacion', 'guardarEntregaBajaMedica'];


    public function render()
    {
        return view('livewire.Vigencia.bajas-medicas.bajas-medicas-main');
    }

    public function mount()
    {
        // $bajas = new RegistroBajasMedicas();
        //  $bajas->registrarDatos();
    }

    //   validarBajaMedica
    #[On('validarBajaMedica')]
    public function validarBajaMedica($data)
    {
        $this->idBaja = $data;
        $this->mostrarBajaMedica($data);
        $this->dispatch('alertaValidar');
    }

    //   guaradarBajaMedica
    public function guardarValidacion()
    {
        //try {
        $baja = BajaMedica::find($this->idBaja);

        if ($baja) {
            $baja->estado_validacion = 'VALIDADO';
            $baja->fecha_validacion = Carbon::now();
            $baja->id_user_validado = auth()->user()->id;
            $baja->save();

            // notificaciones para notificar al gerente de salud
            $rol = Role::where('name', 'GERENTE GENERAL')->first();
            if ($rol) {
                $receptores = RolUsuario::where('role_id', $rol->id)->get();

                foreach ($receptores as $receptor) {
                    $nuevaNotificacion = NotificacionUsuarios::create([
                        'estado' => 'NO LEIDO',
                        'prioridades' => 1,
                        'id_usu_emisor' => auth()->user()->id,
                        'id_usu_receptor' => $receptor->model_id,
                        'fecha_hora_enviada' => Carbon::now(),
                        'asunto' => 'VIGENCIA DE DERECHOS - BAJA MÉDICA VÁLIDA',
                        'mensaje' => 'La baja médica se VALIDÓ para el Asegurado: '
                            . $this->nombreCompleto . ', por la Unidad de Vigencia de Derechos registrado con el Código: '
                            . $this->cite . '. En fecha, ' . Carbon::now()->toDateString() . ' .',
                    ]);
                }
            }

            $this->dispatch('pg:eventRefresh-BajaMedicaTable');
            $this->dispatch('guardado');
        }



        /* } catch (\Throwable $th) {
            $this->dispatch('noValidado');
        } */
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

        //try {
        $baja = BajaMedica::find($this->idBaja);

        if ($baja) {
            $baja->estado_validacion = 'ANULADO';
            $baja->observacion = $data;
            $baja->id_user_anulado = auth()->user()->id;
            $baja->fecha_anulado = Carbon::now();
            $baja->save();

            $this->dispatch('pg:eventRefresh-BajaMedicaTable');
            $this->dispatch('guardado');
        }
        //} catch (\Throwable $th) {
        //    $this->dispatch('noValidado');
        //}
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
        $nombreCompleto, $matricula, $complemento, $tipo_asegurado, $foto, $ci, $edadPersona, $sexo, $codigoInstitucional,
        $institucion, $id_persona_entregado, $id_user_created, $created_at, $estado_registro, $diasValidez, $id_persona_baja;

    public $user_validado, $fecha_validacion, $user_anulado, $fecha_anulado,
        $fecha_homologado, $user_entregado;

    public $switchHomologado = false;
    // mostrar datos de la baja medica 
    public function mostrarBajaMedica($id)
    {
        if ($id) {
            $baja = BajaMedica::find($id);

            $this->cite = $baja->cod_baja_medica;
            $this->fecha_recepcion = $baja->fecha_recepcion;
            $this->fecha_inicio = $baja->fecha_inicio;
            $this->fecha_fin = $baja->fecha_fin;
            $this->diasValidez = (Carbon::parse($baja->fecha_fin)->diffInDays(Carbon::parse($baja->fecha_inicio))) + 1;
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
                $this->switchHomologado = true;
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

            if ($baja->id_especialista != null) {
                $especialistaActual = Especialista::find($baja->id_especialista);
                $id_especialista = $especialistaActual->id_persona;

                $personaActual = Persona::find($id_especialista);
                $this->especialista = $personaActual->nombres . ' ' . $personaActual->p_apellido . ' ' . $personaActual->s_apellido;
            } else {
                $this->especialista = $baja->nombre_especialista;
            }

            if ($baja->id_persona != null) {
                $this->id_persona_baja = $baja->id_persona;
                $personaAfiliada = Persona::find($per_afiliada);
                if ($personaAfiliada->p_apellido == null) {
                    $this->nombreCompleto = $personaAfiliada->s_apellido . ' ' . $personaAfiliada->nombres;
                } elseif ($personaAfiliada->s_apellido == null) {
                    $this->nombreCompleto = $personaAfiliada->p_apellido . ' ' . $personaAfiliada->nombres;
                } else {
                    $this->nombreCompleto = $personaAfiliada->p_apellido . ' ' . $personaAfiliada->s_apellido . ' ' . $personaAfiliada->nombres;
                }

                $this->matricula = $personaAfiliada->matricula_seguro;
                if ($personaAfiliada->complemento == null) {
                    $this->ci = $personaAfiliada->ci;
                } else {
                    $this->ci = $personaAfiliada->ci . ' - ' . $personaAfiliada->complemento;
                }

                $this->edadPersona = Carbon::parse($personaAfiliada->fecha_nacimiento)->age;
                $this->fecha_nacimiento = $personaAfiliada->fecha_nacimiento;
                $this->sexo = $personaAfiliada->sexo == 'F' ? 'FEMENINO' : 'MASCULINO';

                $this->complemento = $personaAfiliada->complemento;

                $tipoAsegurado = TipoAsegurado::find($personaAfiliada->id_tipo_asegurado);
                $this->tipo_asegurado = $tipoAsegurado->tipo_asegurado;

                $esTitular = Titular::where('id_persona', $per_afiliada)->where('estado', true)->first();
                if ($esTitular) {
                    $this->estado_vigencia = $esTitular->estado_vigencia;

                    $this->foto = $this->obtenerImagenBase64($esTitular->foto_nombre);


                    $titularInstitucion = TitularInstitucion::where('id_titular', $esTitular->id)
                        ->where('estado', true)
                        ->with(['titularInstitucionConInstitucion', 'titularInstitucionConInstitucion.codigosInstitucion'])
                        ->first();
                    if ($titularInstitucion) {
                        $inst = Institucion::find($titularInstitucion->id_institucion);
                        $this->institucion = $inst->nombre;
                        $id_institucion = $inst->id;
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
                } else {
                    $esBeneficiario = Beneficiario::where('id_persona', $per_afiliada)->where('estado', true)->first();

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
                            $id_institucion = $inst->id;
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
                    } else {
                        $this->dispatch('noAfiliadoTitBen');
                    }
                }
            } else {
                $this->id_persona_baja = null;
                $this->nombreCompleto = $baja->nombre_persona;
                $this->matricula = $baja->matricula_persona;
                if ($baja->complemento_persona == null) {
                    $this->ci = $baja->ci_persona;
                } else {
                    $this->ci = $baja->ci_persona . ' - ' . $baja->complemento_persona;
                }

                $this->complemento = $baja->complemento_persona;
                $this->tipo_asegurado = '';
                $this->institucion = $baja->nombre_institucion;
                $this->foto = null;
                $this->edadPersona = Carbon::parse($baja->fecha_nacimiento_persona)->age;
                $this->fecha_nacimiento = $baja->fecha_nacimiento_persona;
                $this->sexo = $baja->genero_persona == 'F' ? 'FEMENINO' : 'MASCULINO';
            }
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
        $this->mostrarBajaMedica($data);
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

    public function search_persona()
    {
        if ($this->complemento_m == '') {
            $this->complemento_m = null;
        }
        $existePersona = Persona::where('ci', $this->carnet)->where('complemento', $this->complemento_m)->first();
        if ($existePersona) {
            $this->nombre = $existePersona->nombres;
            $this->ap_paterno = $existePersona->p_apellido;
            $this->ap_materno = $existePersona->s_apellido;
            $this->complemento_m = $existePersona->complemento;
            $this->carnet = $existePersona->ci;
            $this->fecha_nacimiento = $existePersona->fecha_nacimiento;
        } else {
            $respuesta_con = new ValidarPersona();
            $dato_con = $respuesta_con->buscarPersona('3434797', null);
            if (isset($dato_con['error'])) {
                $conexionAsuss_con = new ConexionAsuss;
                $logeo_con = $conexionAsuss_con->login();
            }
            $respuesta = new ValidarPersona();
            $dato = $respuesta->buscarPersona($this->carnet, $this->complemento_m);
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
        }
        $this->skipRender();
    }


    public $observaciones;
    // GUARDAR ENTREGAR BAJA MEDICA
    public function guardarEntregaBajaMedica()
    {

        $baja = BajaMedica::find($this->idBaja);

        if ($baja) {
            if ($this->complemento_m == '') {
                $this->complemento_m = null;
            }

            $existePersona = Persona::where('ci', $this->carnet)
                ->where('complemento', $this->complemento_m)
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
                    'id_user_created' => auth()->user()->id,
                ]);

                $personaId = $persona->id;
            }



            $baja->estado_validacion = 'HOMOLOGADO';
            $baja->id_user_entregado = auth()->user()->id;
            $baja->fecha_homologado = Carbon::now();
            $baja->id_persona_entregado = $personaId;
            $baja->observacion = $this->observaciones;

            $baja->save();
            // notificaciones  al gerente de salud
            $rol = Role::where('name', 'GERENTE GENERAL')->first();
            if ($rol) {
                $receptores = RolUsuario::where('role_id', $rol->id)->get();

                foreach ($receptores as $receptor) {
                    $nuevaNotificacion = NotificacionUsuarios::create([
                        'estado' => 'NO LEIDO',
                        'prioridades' => 1,
                        'id_usu_emisor' => auth()->user()->id,
                        'id_usu_receptor' => $receptor->model_id,
                        'fecha_hora_enviada' => Carbon::now(),
                        'asunto' => 'VIGENCIA DE DERECHOS - BAJA MÉDICA HOMÓLOGADO',
                        'mensaje' => 'La baja médica se HOMÓLOGO para el Asegurado: '
                            . $this->nombreCompleto . ', por la Unidad de Vigencia de Derechos registrado con el Código: '
                            . $this->cite . '. En fecha, ' . Carbon::now()->toDateString() . ' .',
                    ]);
                }
            }
            $this->reset('carnet', 'complemento_m', 'nombre', 'ap_paterno', 'ap_materno', 'fecha_nacimiento', 'observaciones');
            $this->dispatch('pg:eventRefresh-BajaMedicaTable');
            $this->dispatch('guardado');
        }



        /* } catch (\Throwable $th) {
            $this->dispatch('noValidado');
        } */
    }

    public function abrirLista()
    {
        $this->dispatch('modalListaPersonas');
        //return redirect()->route('vigencia.bajasmedicaslistaafiliados.lista');
    }

    public function generarPDF()
    {
        if ($this->fechaInicio != null && $this->fechaFin != null) {
            $this->js('window.open("/vigencia/BajasMedicas/' . $this->fechaInicio . '/' . $this->fechaFin . '")');
        } else {
            $this->dispatch('completeDatos');
            $this->fechaInicio = null;
            $this->fechaFin = null;
        }
        $this->skipRender();
    }
}
