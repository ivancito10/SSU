<?php

namespace App\Livewire\Vigencia\Convenios\Emergencias;

use Livewire\Component;
use App\Services\ValidarPersona;
use Illuminate\Support\Str;

use App\Models\Administracion\Especialidad;
use App\Models\Administracion\Servicio;
use App\Models\Administracion\Persona;
use App\Models\Administracion\Departamento;
use App\Models\Administracion\EstadoCivil;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\TitularInstitucion;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\Estudiante;
use App\Models\Administracion\TipoAsegurado;
use App\Models\Vigencia\Convenio;
use App\Models\Vigencia\ConvenioEspecialidad;
use App\Models\Vigencia\ConvenioServicio;
use App\Models\Vigencia\HistorialAsegurado;
use App\Models\Administracion\PersonalSsu;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Carbon\Carbon;
use Livewire\WithFileUploads;

use App\Models\Administracion\RolUsuario;
use App\Models\Administracion\NotificacionUsuarios;
use App\Models\Administracion\Role;
use App\Services\ConveniosGehma2;

class ConvenioEmerRegister extends Component
{
    use WithFileUploads;
    #[Validate]
    public $foto_asegurado;
    public $estado_api;
    public $idPersona;
    #[Validate]
    public $es_extranjero;
    public $ci, $complemento_m, $ap_paterno, $ap_materno, $nombre, $fecha_nacimiento;
    public $dias = 0;
    #[Validate]
    public $autorizado, $nombre_autoriza, $carnet_autoriza, $complemento_autoriza;
    #[Locked]
    public $alerta_titular,
        $carnet_titular,
        $complemento_titular,
        $nombre_titular,
        $paterno_titular,
        $materno_titular,
        $fecha_titular,
        $matricula_titular,
        $sexo_titular,
        $estado_civil_titular,
        $estado_vigente,
        $foto,
        $id_titular;
    #[Validate]
    public $fecha_inicio, $fecha_fin, $otras_limitaciones, $foto_nombre, $observacion;
    #[Validate]
    public $medicamentos_extraliname = false, $medicamentos_liname = false;
    #[Validate]
    public $estado, $hoja_ruta, $fecha_solicitud, $estado_validez;
    #[Validate]
    public $id_persona, $id_tipo_convenio, $id_persona_autoriza;
    #[Validate]
    public $especialidades, $servicios, $id_personal_ssu;
    //atributos persona asegurada
    public $estado_civil;

    #[Validate]
    public $encontrado = 1;
    #[Validate]
    public $sexo = false;
    public $matricula, $expedido_ci, $depa;
    public $estado_asuss;

    public $id_estado_civil;
    public $personaExiste, $estados_civil;
    public $fecha_nacimiento_switch, $matricula_switch, $extranjero_switch, $estado_civil_switch, $id_tipo_asegurado_switch, $expedido_ci_switch;
    public $personal_ssu = [];
    public $especialidadesSelect = [];
    public $serviciosSelect = [];

    public $carnetBusqueda;
    public $complementoBusqueda;
    public $carnetTitularBusqueda;
    public $complementoTitularBusqueda;

    public $nro_documento_respaldo;
    public $cod_convenio;
    public $nro_cod_convenio;

    public $id_user_titular;
    public $nombre_institucion;

    public $mostrarFormulario;

    public function rules()
    {
        return [
            //'nro_emergencia' => 'required',
            'fecha_inicio' => 'required',
            'fecha_fin' => 'required',
            //'foto_asegurado' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'id_personal_ssu' => 'required',
            //'hoja_ruta' => 'required',
            'fecha_solicitud' => 'required',
            'ci' => 'required',
            'nombre' => 'required',
            //'ap_paterno' => 'required',
            //'ap_materno' => 'required',
            'fecha_nacimiento' => 'required',
            //'matricula' => 'required',
            'id_estado_civil' => 'required',
            'expedido_ci' => 'required',
            'es_extranjero' => 'required',

            'nombre_autoriza' => 'required',
            'carnet_autoriza' => 'required'

        ];
    }

    protected $listeners = ['search_persona', 'titular_persona', 'save'];

    public function render()
    {
        return view('livewire.Vigencia.convenios.emergencias.convenio-emer-register');
    }

    public function mount()
    {
        $this->mostrarFormulario = false;
        $this->nuevoNumeroCite();
        $this->personal_ssu = PersonalSsu::all();
        $this->especialidadesSelect = Especialidad::orderBy('especialidad', 'asc')->get();
        $this->serviciosSelect = Servicio::all();
        $this->depa = Departamento::get();
        $this->estados_civil = EstadoCivil::get();
    }

    // 1. Agregar asegurado
    public function search_persona()
    {
        $this->mostrarFormulario = true;
        try {
            if ($this->complementoBusqueda == '') {
                $this->complementoBusqueda = null;
            }

            $existePersona = Persona::where('ci', $this->carnetBusqueda)->where('complemento', $this->complementoBusqueda)
                ->where(function ($convenioInterior) {
                    $convenioInterior->whereIn('id_tipo_asegurado', [0, 8])
                        ->orWhere('afiliado', false);
                })
                ->first();

            if ($existePersona) {
                $this->estado_api = 2;
                $this->personaExiste = true;
                $this->idPersona = $existePersona->id;
                $this->ci = $existePersona->ci;
                $this->complemento_m = $existePersona->complemento;
                $this->ap_paterno = $existePersona->p_apellido;
                $this->ap_materno = $existePersona->s_apellido;
                $this->nombre = $existePersona->nombres;
                $this->estado_asuss = $existePersona->estado_asuss;
                $this->fecha_nacimiento = $existePersona->fecha_nacimiento;
                $this->fecha_nacimiento_switch = $this->fecha_nacimiento ? true : false;
                $this->matricula = $existePersona->matricula_seguro;
                $this->matricula_switch = $existePersona->matricula_seguro ? true : false;
                $this->id_estado_civil = $existePersona->id_estado_civil;
                $this->estado_civil_switch = $existePersona->id_estado_civil ? true :  false;
                $this->expedido_ci = $existePersona->id_dept_exp;
                $this->expedido_ci_switch = $existePersona->id_dept_exp ? true :  false;


                //DATOS QUE PODRIAN SER NULOS 
                $this->es_extranjero = $existePersona->es_extranjero;
                if (is_null($existePersona->es_extranjero)) {
                    $this->extranjero_switch = false;
                } else {
                    $this->extranjero_switch = true;
                }

                //dd($this->es_extranjero,$this->extranjero_switch) ;
                if ($existePersona->sexo == 'F') {
                    $this->sexo = true;
                } elseif ($existePersona->sexo == 'M') {
                    $this->sexo = false;
                } elseif ($existePersona->sexo == null) {
                    $this->sexo = false;
                } else {
                    $this->sexo = false;
                }
            } else {
                $this->personaExiste = false;

                //PARA QUE NO AGREGUE A ASEGURADOS

                $existeComoAfiliado = Persona::where('ci', $this->carnetBusqueda)->where('complemento', $this->complementoBusqueda)
                    ->where('afiliado', true)
                    ->whereNotIn('id_tipo_asegurado', [8, 9, 10])
                    ->first();

                if ($existeComoAfiliado) {
                    $this->estado_api = 3;
                    $this->mostrarFormulario = false;
                    $this->dispatch('personaAfiliada');
                } else {
                    $respuesta = new ValidarPersona();
                    $dato = $respuesta->buscarPersona($this->carnetBusqueda, $this->complementoBusqueda);

                    if (isset($dato['datosPersonaEnFormatoJson'])) {
                        $this->personaExiste = true;
                        $this->ci = $dato['datosPersonaEnFormatoJson']['numeroDocumento'];
                        $this->complemento_m = $dato['datosPersonaEnFormatoJson']['Complemento'];
                        $this->ap_paterno = $dato['datosPersonaEnFormatoJson']['primerApellido'];
                        $this->ap_materno = $dato['datosPersonaEnFormatoJson']['segundoApellido'];
                        $this->nombre = $dato['datosPersonaEnFormatoJson']['nombres'];
                        $this->fecha_nacimiento = $dato['datosPersonaEnFormatoJson']['fechaNacimiento'];
                        $this->fecha_nacimiento_switch = true;
                        $this->estado_api = 1;
                        $this->estado_asuss = true;
                        $this->encontrado = 1;
                        $this->sexo = false;
                        $this->estado_civil_switch = false;
                        $this->extranjero_switch = false;
                        $this->expedido_ci_switch = false;
                    } else {
                        $personaAfiliada = Persona::where('ci', $this->carnetBusqueda)->where('complemento', $this->complementoBusqueda)
                            ->with(['tipoAseguradoPersona'])
                            ->first();

                        if ($personaAfiliada) {
                            if ($personaAfiliada->tipoAseguradoPersona->tipo_asegurado == 'TITULAR') {
                                $esTitular = Titular::where('id_persona', $personaAfiliada->id)->first();
                                if ($esTitular) {
                                    if ($esTitular->estado_vigencia == 'NO VIGENTE') {
                                        $this->estado_api = 2;
                                        $this->personaExiste = true;
                                        $this->idPersona = $existePersona->id;
                                        $this->ci = $existePersona->ci;
                                        $this->complemento_m = $existePersona->complemento;
                                        $this->ap_paterno = $existePersona->p_apellido;
                                        $this->ap_materno = $existePersona->s_apellido;
                                        $this->nombre = $existePersona->nombres;
                                        $this->estado_asuss = $existePersona->estado_asuss;
                                        $this->fecha_nacimiento = $existePersona->fecha_nacimiento;
                                        $this->fecha_nacimiento_switch = $this->fecha_nacimiento ? true : false;
                                        $this->matricula = $existePersona->matricula_seguro;
                                        $this->matricula_switch = $existePersona->matricula_seguro ? true : false;
                                        $this->id_estado_civil = $existePersona->id_estado_civil;
                                        $this->estado_civil_switch = $existePersona->id_estado_civil ? true :  false;
                                        $this->expedido_ci = $existePersona->id_dept_exp;
                                        $this->expedido_ci_switch = $existePersona->id_dept_exp ? true :  false;


                                        //DATOS QUE PODRIAN SER NULOS 
                                        $this->es_extranjero = $existePersona->es_extranjero;
                                        if (is_null($existePersona->es_extranjero)) {
                                            $this->extranjero_switch = false;
                                        } else {
                                            $this->extranjero_switch = true;
                                        }

                                        //dd($this->es_extranjero,$this->extranjero_switch) ;
                                        if ($existePersona->sexo == 'F') {
                                            $this->sexo = true;
                                        } elseif ($existePersona->sexo == 'M') {
                                            $this->sexo = false;
                                        } elseif ($existePersona->sexo == null) {
                                            $this->sexo = false;
                                        } else {
                                            $this->sexo = false;
                                        }
                                    } else {
                                        $this->estado_api = 3;
                                    }
                                } else {
                                    $this->personaExiste = false;
                                    $this->estado_api = 0;
                                    $this->nombre = '';
                                    $this->ap_paterno = '';
                                    $this->ap_materno = '';
                                    $this->fecha_nacimiento = '';
                                    $this->ci = $this->carnetBusqueda;
                                    $this->complemento_m = $this->complementoBusqueda;
                                    $this->encontrado = 0;
                                    $this->sexo = false;
                                    $this->estado_civil_switch = false;
                                    $this->extranjero_switch = false;
                                    $this->fecha_nacimiento_switch = false;
                                    $this->expedido_ci_switch = false;
                                    $this->estado_asuss = false;
                                }
                            } elseif (Str::contains($personaAfiliada->tipoAseguradoPersona->tipo_asegurado, 'BENEFICIARIO')) {
                                if ($personaAfiliada->tipoAseguradoPersona->tipo_asegurado != 'BENEFICIARIO DEL INTERIOR') {
                                    $esBeneficiario = Beneficiario::where('id_persona', $personaAfiliada->id)->first();
                                    if ($esBeneficiario) {
                                        if ($esBeneficiario->vigencia == 'NO VIGENTE') {
                                            $this->estado_api = 2;
                                            $this->personaExiste = true;
                                            $this->idPersona = $existePersona->id;
                                            $this->ci = $existePersona->ci;
                                            $this->complemento_m = $existePersona->complemento;
                                            $this->ap_paterno = $existePersona->p_apellido;
                                            $this->ap_materno = $existePersona->s_apellido;
                                            $this->nombre = $existePersona->nombres;
                                            $this->estado_asuss = $existePersona->estado_asuss;
                                            $this->fecha_nacimiento = $existePersona->fecha_nacimiento;
                                            $this->fecha_nacimiento_switch = $this->fecha_nacimiento ? true : false;
                                            $this->matricula = $existePersona->matricula_seguro;
                                            $this->matricula_switch = $existePersona->matricula_seguro ? true : false;
                                            $this->id_estado_civil = $existePersona->id_estado_civil;
                                            $this->estado_civil_switch = $existePersona->id_estado_civil ? true :  false;
                                            $this->expedido_ci = $existePersona->id_dept_exp;
                                            $this->expedido_ci_switch = $existePersona->id_dept_exp ? true :  false;


                                            //DATOS QUE PODRIAN SER NULOS 
                                            $this->es_extranjero = $existePersona->es_extranjero;
                                            if (is_null($existePersona->es_extranjero)) {
                                                $this->extranjero_switch = false;
                                            } else {
                                                $this->extranjero_switch = true;
                                            }

                                            //dd($this->es_extranjero,$this->extranjero_switch) ;
                                            if ($existePersona->sexo == 'F') {
                                                $this->sexo = true;
                                            } elseif ($existePersona->sexo == 'M') {
                                                $this->sexo = false;
                                            } elseif ($existePersona->sexo == null) {
                                                $this->sexo = false;
                                            } else {
                                                $this->sexo = false;
                                            }
                                        } else {
                                            $this->estado_api = 3;
                                        }
                                    } else {
                                        $this->personaExiste = false;
                                        $this->estado_api = 0;
                                        $this->nombre = '';
                                        $this->ap_paterno = '';
                                        $this->ap_materno = '';
                                        $this->fecha_nacimiento = '';
                                        $this->ci = $this->carnetBusqueda;
                                        $this->complemento_m = $this->complementoBusqueda;
                                        $this->encontrado = 0;
                                        $this->sexo = false;
                                        $this->estado_civil_switch = false;
                                        $this->extranjero_switch = false;
                                        $this->fecha_nacimiento_switch = false;
                                        $this->expedido_ci_switch = false;
                                        $this->estado_asuss = false;
                                    }
                                } else {
                                    $this->estado_api = 3;
                                }
                            } else {
                                $this->personaExiste = false;
                                $this->estado_api = 0;
                                $this->nombre = '';
                                $this->ap_paterno = '';
                                $this->ap_materno = '';
                                $this->fecha_nacimiento = '';
                                $this->ci = $this->carnetBusqueda;
                                $this->complemento_m = $this->complementoBusqueda;
                                $this->encontrado = 0;
                                $this->sexo = false;
                                $this->estado_civil_switch = false;
                                $this->extranjero_switch = false;
                                $this->fecha_nacimiento_switch = false;
                                $this->expedido_ci_switch = false;
                                $this->estado_asuss = false;
                            }
                        } else {
                            $this->personaExiste = false;
                            $this->estado_api = 0;
                            $this->nombre = '';
                            $this->ap_paterno = '';
                            $this->ap_materno = '';
                            $this->fecha_nacimiento = '';
                            $this->ci = $this->carnetBusqueda;
                            $this->complemento_m = $this->complementoBusqueda;
                            $this->encontrado = 0;
                            $this->sexo = false;
                            $this->estado_civil_switch = false;
                            $this->extranjero_switch = false;
                            $this->fecha_nacimiento_switch = false;
                            $this->expedido_ci_switch = false;
                            $this->estado_asuss = false;
                        }
                    }
                }
            }

            if ($this->fecha_nacimiento != null) {
                //dd($this->id_tipo_asegurado);
                if ($this->sexo != null) {
                    if ($this->sexo = 'F') {
                        $this->sexo = true;
                    } elseif ($this->sexo = 'M') {
                        $this->sexo = false;
                    }
                } else {
                    $this->sexo = false;
                }
                $this->GenerarMatricula();
            }
        } catch (\Throwable $th) {
            $this->personaExiste = false;
            $this->estado_api = 0;
            $this->nombre = '';
            $this->ap_paterno = '';
            $this->ap_materno = '';
            $this->fecha_nacimiento = '';
            $this->ci = $this->carnetBusqueda;
            $this->complemento_m = $this->complementoBusqueda;
            $this->encontrado = 0;
            $this->sexo = false;
            $this->estado_civil_switch = false;
            $this->extranjero_switch = false;
            $this->fecha_nacimiento_switch = false;
            $this->expedido_ci_switch = false;
            $this->estado_asuss = false;
        }
    }

    public function nuevoNumeroCite()
    {
        $convenioEmergencia = Convenio::orderBy('id', 'desc')->first();
        if ($convenioEmergencia) {
            $ultimaGestion = Carbon::parse($convenioEmergencia->created_at)->format('Y');
            if ($ultimaGestion == Carbon::now()->format('Y')) {
                $cod_convenio_suma = $convenioEmergencia->cod_convenio + 1;
                $this->nro_cod_convenio = $cod_convenio_suma;
                $this->cod_convenio = 'C/EM/' . $cod_convenio_suma . '/' . $ultimaGestion;
                //dd($this->cite);
            } else {
                $ultimaGestion = Carbon::now()->format('Y');
                $cod_convenio_suma = 1;
                $this->nro_cod_convenio = $cod_convenio_suma;
                $this->cod_convenio = 'C/EM/' . $cod_convenio_suma . '/' . $ultimaGestion;
                //dd($this->cite);
            }
        } else {
            $cod_convenio_suma = 1;
            $this->nro_cod_convenio = $cod_convenio_suma;
            $this->cod_convenio = 'C/EM/' . $cod_convenio_suma . '/' . Carbon::now()->format('Y');
        }
    }

    // 2. registro de convenio
    public function dias_diferencia()
    {
        $start = Carbon::parse($this->fecha_inicio);
        $end = Carbon::parse($this->fecha_fin);

        // Calcular la diferencia en días
        $this->dias = ($end->diffInDays($start)) + 1;
    }

    public function autorizado_por()
    {
        $personal = PersonalSsu::find($this->id_personal_ssu);
        //dd($personal);
        $id_autoriza = $personal->id_persona;

        $personaAutoridad = Persona::find($id_autoriza);
        if ($personaAutoridad->p_apellido == null) {
            $this->nombre_autoriza = $personaAutoridad->nombres . ' ' . $personaAutoridad->s_apellido;
        } elseif ($personaAutoridad->s_apellido == null) {

            $this->nombre_autoriza = $personaAutoridad->nombres . ' ' . $personaAutoridad->p_apellido;
        } else {
            $this->nombre_autoriza = $personaAutoridad->nombres . ' ' . $personaAutoridad->p_apellido . ' ' . $personaAutoridad->s_apellido;
        }

        if ($personaAutoridad->complemento == null) {
            $this->carnet_autoriza = $personaAutoridad->ci;
        } else {
            $this->carnet_autoriza = $personaAutoridad->ci . ' - ' . $personaAutoridad->complemento;
        }

        $this->complemento_autoriza = $personaAutoridad->complemento;
    }
    // 3. Agregar titular

    public function titular_persona()
    {
        if ($this->complementoTitularBusqueda == '') {
            $this->complementoTitularBusqueda = null;
        }

        $personaTitular = Persona::where('ci', $this->carnetTitularBusqueda)->where('complemento', $this->complementoTitularBusqueda)
            ->where('afiliado', true)
            //->where('id_tipo_asegurado', 1)
            ->first();

        if ($personaTitular != null) {
            $idPersona = $personaTitular->id;
            $id_tipo_asegurado = $personaTitular->id_tipo_asegurado;

            $idusertitular = User::where('id_persona', $idPersona)->first();
            $this->id_user_titular = $idusertitular->id;
            if ($id_tipo_asegurado == 1) {
                //dd('est titular');
                $this->alerta_titular = 1;
                if ($this->complementoTitularBusqueda == null) {
                    $this->carnet_titular = $this->carnetTitularBusqueda;
                } else {
                    $this->carnet_titular = $this->carnetTitularBusqueda . ' - ' . $this->complementoTitularBusqueda;
                }
                $this->carnet_titular = $this->carnetTitularBusqueda;
                $this->complemento_titular = $this->complementoTitularBusqueda;
                if ($personaTitular->p_apellido == null) {
                    $this->nombre_titular = $personaTitular->s_apellido . ' ' . $personaTitular->nombres;
                } elseif ($personaTitular->s_apellido == null) {
                    $this->nombre_titular = $personaTitular->p_apellido . ' ' . $personaTitular->nombres;
                } else {
                    $this->nombre_titular = $personaTitular->p_apellido . ' ' . $personaTitular->s_apellido . ' ' . $personaTitular->nombres;
                }
                $this->paterno_titular = $personaTitular->p_apellido;
                $this->materno_titular = $personaTitular->s_apellido;
                $this->fecha_titular = $personaTitular->fecha_nacimiento;
                $this->matricula_titular = $personaTitular->matricula_seguro;
                $this->sexo_titular = $personaTitular->sexo == 'F' ? 'FEMENINO' : 'MASCULINO';

                $id_estado_civil = $personaTitular->id_estado_civil;

                $titular_estado_civil = EstadoCivil::find($id_estado_civil);
                $this->estado_civil_titular = $titular_estado_civil->nombre;

                $titular = Titular::where('id_persona', $idPersona)->first();
                $this->estado_vigente = $titular->estado_vigencia;
                $this->foto = $this->obtenerImagenBase64($titular->foto_nombre);
                $this->id_titular = $titular->id;

                //PARA LA INSTITUCION
                $titularInstitucion = TitularInstitucion::where('id_titular', $this->id_titular)->with(['titularInstitucionConInstitucion', 'titularInstitucionConInstitucion.codigosInstitucion'])
                    ->orderBy('id', 'asc')->first();
                if ($titularInstitucion) {
                    $this->nombre_institucion = $titularInstitucion->titularInstitucionConInstitucion->nombre;
                    if (!empty($titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo)) {
                        $this->matricula_titular = $personaTitular->matricula_seguro . ' - ' . $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo
                            .  $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->tipo_titular;
                    }
                } else {
                    $this->nombre_institucion = 'APORTANTE VOLUNTARIO';
                }


                //$this->complemento_titular = $personaTitular->complemento. ' ' . $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo


            } else {
                //dd('no es titular');
                $this->alerta_titular = 2;
            }
        } else {
            //dd('no se encontro persona');
            $this->alerta_titular = 3;
        }
    }

    private function obtenerImagenBase64($idFoto)
    {
        try {
            if ($idFoto) {
                $path = 'Afiliacion/FOTOS/' . $idFoto;

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

    // Botonces para cancelar y guardar
    // boton cerrar
    public function cancel()
    {
        return redirect()->route('vigencia.convenios.emergencias.index')->with('evento', 'ConvenioNoRegistrado');
    }

    public function GenerarMatricula()
    {
        $nombre = $this->nombre;
        $p_apellido = $this->ap_paterno;
        $s_apellido = $this->ap_materno;
        $fecha_nacimiento = Carbon::parse($this->fecha_nacimiento);
        $sexo = $this->sexo ? $this->sexo : false;
        $fechaNacimiento = $fecha_nacimiento;
        $anioNacimiento = $fechaNacimiento->format('Y');
        $mesNacimiento = $fechaNacimiento->format('m');
        $diaNacimiento = $fechaNacimiento->format('d');
        $sexoEsMasculino = $sexo === false || $sexo === 'M';
        $sexoEsFemenino = $sexo === true || $sexo === 'F';

        if (strlen($p_apellido) == 0) {
            $apellido1 = substr($s_apellido, 0, 1);
            $apellido2 = substr($s_apellido, 1, 1);
        }
        if (strlen($s_apellido) == 0) {
            $apellido1 = substr($p_apellido, 0, 1);
            $apellido2 = substr($p_apellido, 1, 1);
        }
        if (strlen($p_apellido) != 0 && strlen($s_apellido) != 0) {
            $apellido1 = substr($p_apellido, 0, 1);
            $apellido2 = substr($s_apellido, 0, 1);
        }
        $nombre1 = substr($nombre, 0, 1);
        $matricula = '';
        $matricula .= substr($anioNacimiento, -2);
        if ($sexoEsMasculino) {
            $matricula .= str_pad($mesNacimiento, 2, '0', STR_PAD_LEFT);
        } elseif ($sexoEsFemenino) {
            $matricula .= str_pad(50 + $mesNacimiento, 2, '0', STR_PAD_LEFT);
        }
        $matricula .= $diaNacimiento;
        $matricula .= $apellido1;
        $matricula .= $apellido2;
        $matricula .= $nombre1;

        $this->matricula = $matricula;
    }

    // Atributos para registrar convenio de emergencia
    public function save($otraslimitaciones, $observaciones)
    {
        $this->validate();
        
        $this->otras_limitaciones = $otraslimitaciones;
        $this->observacion = $observaciones;

        if ($this->sexo == true) {
            $this->sexo = 'F';
        } else {
            $this->sexo = 'M';
        }

        if ($this->id_titular != null) {

            DB::beginTransaction();
            
            $existePersona = Persona::where('ci', $this->ci)
                ->where('complemento', $this->complemento_m)
                ->first();

            if ($existePersona != null) {

                if ($existePersona->afiliado == false) {
                    $existePersona->afiliado = true;
                    $existePersona->save();
                }

                if (!$this->fecha_nacimiento_switch) {
                    $existePersona->fecha_nacimiento = $this->fecha_nacimiento;
                    $existePersona->save();
                }

                if (!$this->matricula_switch) {
                    $existePersona->matricula_seguro = $this->matricula;
                    $existePersona->sexo = $this->sexo;
                    $existePersona->save();
                }

                if (!$this->extranjero_switch) {
                    $existePersona->es_extranjero = $this->es_extranjero;
                    $existePersona->save();
                }

                if (!$this->estado_civil_switch) {
                    $existePersona->id_estado_civil = $this->id_estado_civil;
                    $existePersona->save();
                }

                if (!$this->expedido_ci_switch) {
                    $existePersona->id_dept_exp = $this->expedido_ci;
                    $existePersona->save();
                }

                $existePersona->id_tipo_asegurado = 8;
                $existePersona->save();

                $personaId = $existePersona->id;
            } else {

                if ($this->complemento_m == null) {
                    $clave_unica = $this->ci;
                } else {
                    $clave_unica = $this->ci . $this->complemento_m;
                }

                //dd($this->sexo);

                $persona = Persona::create([
                    'ci' => $this->ci,
                    'complemento' => $this->complemento_m,
                    'nombres' => $this->nombre,
                    'p_apellido' => $this->ap_paterno == '' ? null : $this->ap_paterno,
                    's_apellido' => $this->ap_materno == '' ? null : $this->ap_materno,
                    'fecha_nacimiento' => $this->fecha_nacimiento,
                    'estado_asuss' => $this->estado_asuss,
                    'id_tipo_asegurado' => 8,
                    'afiliado' => true,
                    'sexo' => $this->sexo,
                    'matricula_seguro' => $this->matricula,
                    'id_estado_civil' => $this->id_estado_civil,
                    'id_dept_exp' => $this->expedido_ci,
                    'es_extranjero' => $this->es_extranjero,
                    'clave_unica' => $clave_unica,
                    'id_user_created' => auth()->user()->id,
                ]);

                $personaId = $persona->id;
            }



            $nuevoConvenio = Convenio::create([
                'cod_convenio' => $this->nro_cod_convenio,
                'nro_documento_respaldo' => $this->nro_documento_respaldo,
                'fecha_inicio' => $this->fecha_inicio,
                'fecha_fin' => $this->fecha_fin,
                'id_persona' => $personaId,
                'id_titular_autoriza' => $this->id_titular,
                'id_tipo_convenio' => 1,
                'id_personal_ssu' => $this->id_personal_ssu,

                'medicamentos_extraliname' => $this->medicamentos_extraliname,
                'medicamentos_liname' => $this->medicamentos_liname,
                'estado' => true,
                'nro_hoja_de_ruta' => $this->hoja_ruta,
                'fecha_solicitud_hr' => $this->fecha_solicitud,
                'observacion' => $this->observacion,
                'otras_limitaciones' => $this->otras_limitaciones,
                'estado_validez' => 'VIGENTE',

                'id_user_created' => auth()->user()->id,
            ]);

            if ($nuevoConvenio) {

                //PARA LA TABLA DE HISTORIAL ASEGURADO

                $existeHistorialAsegurado = HistorialAsegurado::where('id_persona', $personaId)->orderBy('id', 'desc')->first();

                if ($existeHistorialAsegurado) {
                    $existeHistorialAsegurado->estado = false;
                    //$existeHistorialAsegurado->estado_vigencia = 'NO VIGENTE';
                    if ($existeHistorialAsegurado->fecha_baja == null) {
                        $existeHistorialAsegurado->fecha_baja = Carbon::now()->format('Y-m-d');
                    }
                    $existeHistorialAsegurado->id_user_updated = auth()->user()->id;
                    $existeHistorialAsegurado->save();

                    HistorialAsegurado::create([
                        'estado' => true,
                        'estado_vigencia' => 'VIGENTE',
                        'id_tipo_asegurado' => 8,
                        'fecha_inicio' => $this->fecha_inicio,
                        'fecha_baja' => $this->fecha_fin,
                        'id_persona' => $personaId,
                        'id_corresponde' => $nuevoConvenio->id,
                        'id_user_created' => auth()->user()->id,
                    ]);
                } else {
                    HistorialAsegurado::create([
                        'estado' => true,
                        'estado_vigencia' => 'VIGENTE',
                        'id_tipo_asegurado' => 8,
                        'fecha_inicio' => $this->fecha_inicio,
                        'fecha_baja' => $this->fecha_fin,
                        'id_persona' => $personaId,
                        'id_corresponde' => $nuevoConvenio->id,
                        'id_user_created' => auth()->user()->id,
                    ]);
                }

                // FIN TABLA DE HISTORIAL ASEGURADO

                if ($this->foto_asegurado) {
                    $nombreFoto = $nuevoConvenio->id . '-CE.jpg';
                    $this->foto_asegurado->storeAs('Vigencia/FOTOS/CONVENIO', $nombreFoto, 'ftp');
                    $nuevoConvenio->update([
                        'foto_nombre' => $nombreFoto,
                    ]);
                }

                $convenioId = $nuevoConvenio->id;
                if ($this->especialidades != [] && $this->servicios != []) {
                    foreach ($this->especialidades as $especialidad) {
                        ConvenioEspecialidad::create([
                            'id_convenio' => $convenioId,
                            'id_especialidad' => $especialidad,
                            'id_user_created' => auth()->user()->id,
                        ]);
                    }

                    foreach ($this->servicios as $servicio) {
                        ConvenioServicio::create([
                            'id_convenio' => $convenioId,
                            'id_servicio' => $servicio,
                            'id_user_created' => auth()->user()->id,
                        ]);
                    }


                    // notificaciones  al gerente de salud por registro de convenio emergencia
                    $rol = Role::where('name', 'Super Admin')->first();
                    if ($rol) {
                        $receptores = RolUsuario::where('role_id', $rol->id)->get();

                        foreach ($receptores as $receptor) {
                            $nuevaNotificacion = NotificacionUsuarios::create([
                                'estado' => 'NO LEIDO',
                                'prioridades' => 1,
                                'id_usu_emisor' => auth()->user()->id,
                                'id_usu_receptor' => $receptor->model_id,
                                'fecha_hora_enviada' => Carbon::now(),
                                'asunto' => 'VIGENCIA DE DERECHOS -  CONVENIO DE EMERGENCIA VALIDADO',
                                'mensaje' => 'El convenio de emergencia se validó satisfactoriamente para el Asegurado: '
                                    . $this->nombre . ' ' . $this->ap_paterno . ' ' . $this->ap_materno .
                                    ', por el titular: ' . $this->nombre_titular . ' En fecha, ' . Carbon::now()->format('d/m/Y') . '.',
                            ]);
                        }
                    }

                    // notificaciones  para titular 

                    $nuevaNotificacion = NotificacionUsuarios::create([
                        'estado' => 'NO LEIDO',
                        'prioridades' => 1,
                        'id_usu_emisor' => auth()->user()->id,
                        'id_usu_receptor' => $this->id_user_titular,
                        'fecha_hora_enviada' => Carbon::now(),
                        'asunto' => 'VIGENCIA DE DERECHOS -  CONVENIO DE EMERGENCIA VALIDADO',
                        'mensaje' => 'El convenio de emergencia se validó satisfactoriamente para el Asegurado: '
                            . $this->nombre . ' ' . $this->ap_paterno . ' ' . $this->ap_materno . ' En fecha, ' . Carbon::now()->format('d/m/Y') . '.',
                    ]);

                    DB::commit();
                    $insercion = new ConveniosGehma2();
                    $res = $insercion->insercionConveniosEmergenciaGehma2($convenioId);
                    $this->dispatch('ConvenioRegistrado');
                    return redirect()->route('vigencia.convenios.emergencias.index');
                } elseif ($this->especialidades != [] && $this->servicios == []) {
                    foreach ($this->especialidades as $especialidad) {
                        ConvenioEspecialidad::create([
                            'id_convenio' => $convenioId,
                            'id_especialidad' => $especialidad,
                            'id_user_created' => auth()->user()->id,
                        ]);
                    }

                    // notificaciones  al gerente de salud por registro de convenio emergencia
                    $rol = Role::where('name', 'Super Admin')->first();
                    if ($rol) {
                        $receptores = RolUsuario::where('role_id', $rol->id)->get();

                        foreach ($receptores as $receptor) {
                            $nuevaNotificacion = NotificacionUsuarios::create([
                                'estado' => 'NO LEIDO',
                                'prioridades' => 1,
                                'id_usu_emisor' => auth()->user()->id,
                                'id_usu_receptor' => $receptor->model_id,
                                'fecha_hora_enviada' => Carbon::now(),
                                'asunto' => 'VIGENCIA DE DERECHOS -  CONVENIO DE EMERGENCIA VALIDADO',
                                'mensaje' => 'El convenio de emergencia se validó satisfactoriamente para el Asegurado: '
                                    . $this->nombre . ' ' . $this->ap_paterno . ' ' . $this->ap_materno .
                                    ', por el titular: ' . $this->paterno_titular . ' ' . $this->materno_titular . ' ' . $this->nombre_titular . ', en fecha, ' . Carbon::now()->format('d/m/Y') . '.',
                            ]);
                        }
                    }

                    // notificaciones  para titular 

                    $nuevaNotificacion = NotificacionUsuarios::create([
                        'estado' => 'NO LEIDO',
                        'prioridades' => 1,
                        'id_usu_emisor' => auth()->user()->id,
                        'id_usu_receptor' => $this->id_user_titular,
                        'fecha_hora_enviada' => Carbon::now(),
                        'asunto' => 'VIGENCIA DE DERECHOS -  CONVENIO DE EMERGENCIA VALIDADO',
                        'mensaje' => 'El convenio de emergencia se validó satisfactoriamente para el Asegurado: '
                            . $this->nombre . ' ' . $this->ap_paterno . ' ' . $this->ap_materno . ' En fecha, ' . Carbon::now()->toDateString() . ' .',
                    ]);
                    // $insercion = new ConveniosGehma2();
                    // $res = $insercion->insercionConveniosEmergenciaGehma2($convenioId);
                    DB::commit();
                    $insercion = new ConveniosGehma2();
                    $res = $insercion->insercionConveniosEmergenciaGehma2($convenioId);
                    $this->dispatch('ConvenioRegistrado');
                    return redirect()->route('vigencia.convenios.emergencias.index');
                } elseif ($this->especialidades == [] && $this->servicios != []) {
                    foreach ($this->servicios as $servicio) {
                        ConvenioServicio::create([
                            'id_convenio' => $convenioId,
                            'id_servicio' => $servicio,
                            'id_user_created' => auth()->user()->id,
                        ]);
                    }

                    // notificaciones  al gerente de salud por registro de convenio emergencia
                    $rol = Role::where('name', 'Super Admin')->first();
                    if ($rol) {
                        $receptores = RolUsuario::where('role_id', $rol->id)->get();

                        foreach ($receptores as $receptor) {
                            $nuevaNotificacion = NotificacionUsuarios::create([
                                'estado' => 'NO LEIDO',
                                'prioridades' => 1,
                                'id_usu_emisor' => auth()->user()->id,
                                'id_usu_receptor' => $receptor->model_id,
                                'fecha_hora_enviada' => Carbon::now(),
                                'asunto' => 'VIGENCIA DE DERECHOS -  CONVENIO DE EMERGENCIA VALIDADO',
                                'mensaje' => 'El convenio de emergencia se validó satisfactoriamente para el Asegurado: '
                                    . $this->nombre . ' ' . $this->ap_paterno . ' ' . $this->ap_materno .
                                    ', por el titular: ' . $this->nombre_titular . ' ' . $this->materno_titular . ' ' . $this->paterno_titular . '. En fecha, ' . Carbon::now()->toDateString() . ' .',
                            ]);
                        }
                    }

                    // notificaciones  para titular 

                    $nuevaNotificacion = NotificacionUsuarios::create([
                        'estado' => 'NO LEIDO',
                        'prioridades' => 1,
                        'id_usu_emisor' => auth()->user()->id,
                        'id_usu_receptor' => $this->id_user_titular,
                        'fecha_hora_enviada' => Carbon::now(),
                        'asunto' => 'VIGENCIA DE DERECHOS -  CONVENIO DE EMERGENCIA VALIDADO',
                        'mensaje' => 'El convenio de emergencia se validó satisfactoriamente para el Asegurado: '
                            . $this->nombre . ' ' . $this->ap_paterno . ' ' . $this->ap_materno . ' En fecha, ' . Carbon::now()->toDateString() . ' .',
                    ]);

                    DB::commit();
                    $insercion = new ConveniosGehma2();
                    $res = $insercion->insercionConveniosEmergenciaGehma2($convenioId);
                    $this->dispatch('ConvenioRegistrado');
                    return redirect()->route('vigencia.convenios.emergencias.index');
                } else {
                    
                    $nuevaNotificacion = NotificacionUsuarios::create([
                        'estado' => 'NO LEIDO',
                        'prioridades' => 1,
                        'id_usu_emisor' => auth()->user()->id,
                        'id_usu_receptor' => $this->id_user_titular,
                        'fecha_hora_enviada' => Carbon::now(),
                        'asunto' => 'VIGENCIA DE DERECHOS -  CONVENIO DE EMERGENCIA VALIDADO',
                        'mensaje' => 'El convenio de emergencia se validó satisfactoriamente para el Asegurado: '
                            . $this->nombre . ' ' . $this->ap_paterno . ' ' . $this->ap_materno . ' En fecha, ' . Carbon::now()->toDateString() . ' .',
                    ]);

                    DB::commit();
                    $insercion = new ConveniosGehma2();
                    $res = $insercion->insercionConveniosEmergenciaGehma2($convenioId);
                    $this->dispatch('ConvenioRegistrado');
                    return redirect()->route('vigencia.convenios.emergencias.index');
                }
            } else {
                DB::rollBack();
                $this->dispatch('ConvenioNoRegistrado');
            }
        } else {
            $this->dispatch('seleccioneTitular');
        }


        /* boriiiiiiiiiis VAS A LIMPIAR LOS DATOS REGISTRADOS PORFAVOOOOOOR NO TE LO HICE JIJI
         SI NO LO LIMPIAS LA SEÑITO SE ENOJARA Y REGISTRARA MAL , PREGUNTAME A QUE ME REFIERO SINO  */
        //$this->dispatch('guardado');
        //return redirect()->route('vigencia.convenios.emergencias.index')->with('evento', 'guardado');
    }

    public function atras()
    {
        return redirect()->route('vigencia.convenios.emergencias.index');
    }
}
