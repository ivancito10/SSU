<?php

namespace App\Livewire\Afiliacion\EstudiantesSsue;

use Livewire\Component;

use App\Models\Afiliacion\Estudiante;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\Beneficiario;
use App\Models\User;
use App\Models\Administracion\Persona;
use App\Models\Administracion\Residencia;
use App\Models\Administracion\Departamento;
use App\Models\Administracion\Provincia;
use App\Models\Administracion\Municipio;
use App\Models\Administracion\Zona;
use App\Models\Administracion\NotificacionUsuarios;
use App\Models\Administracion\RolUsuario;
use App\Models\Administracion\Role;

use App\Models\Administracion\EstadoCivil;
use App\Models\Afiliacion\Facultad;
use App\Models\Afiliacion\Carrera;
use App\Models\Afiliacion\DocumentosEstudiante;

use Carbon\Carbon;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;
use App\Mail\Afiliacion\AfiliacionEstudiantesMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Afiliacion\HistorialMatriculaEstudiante;
use App\Models\Vigencia\HistorialAsegurado;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Services\ValidarPersona;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Services\EstudiantesGehma2;

class EstudiantesCreate extends Component
{
    use WithFileUploads;
    #[Validate]
    public $foto;
    //datos personales
    #[Locked]
    public $id_persona;
    public $nombres, $p_apellido, $s_apellido, $ci, $complemento, $fecha_nacimiento, $fecha_nacimiento_literal, $edad;
    public $sin_doc_sangre = false;
    #[Validate]
    public $es_extranjero, $extranjeroTexto;
    #[Validate]
    public $estado_civil, $id_estado_civil, $estadosCiviles;
    #[Validate]
    #[Locked]
    public $id_expedido_ci;
    #[Validate]
    public $expedido_ci, $id_dep_nacimiento, $dep_nacimiento;
    #[Validate]
    public $id_pro_nacimiento, $pro_nacimiento;
    #[Validate]
    public $id_mun_nacimiento, $mun_nacimiento;
    #[Validate]
    public $departamento;
    public $provincias = [];
    public $municipios = [];
    public $id_tipo_asegurado = 11;
    #[Validate]
    public $matricula;
    #[Validate]
    public $sexo = false;

    //datos referenciales
    #[Locked]
    public $id_estudiante;
    public $estado_vigencia = true;
    #[Validate]
    public $telefono, $correo_institucional, $nombre_referencia, $telefono_referencia;
    public $alergias;
    public $observacion;
    #[Validate]
    public $grupo_sanguineo;
    #[Validate]
    public $fecha_solicitud, $fecha_solicitud_literal;
    public $fecha_afiliacion, $fecha_reafiliacion;
    public $afiliacion, $afiliacion_txt;
    #[Validate]
    public $direccion;
    #[Validate]
    public $zona;
    public $zonaId, $zonasSelect;
    #[Validate]
    public $nro_vivienda, $id_residencia;

    // datos universitarios 
    #[Validate]
    public $registro_universitario;
    //#[Validate]
    public $gestion_ingreso, $semestre_actual;
    #[Validate]
    public $facultad, $id_facultad;
    #[Validate]
    public $carrera, $id_carrera, $idCarrera, $file_tipo_sangre, $file_fotocopiaCarnet, $file_matricula, $file_boletaInscripcion, $file_certificadoCajas, $file_enteGestor;

    public $dep_nacimiento_nombre, $pro_nacimiento_nombre, $mun_nacimiento_nombre, $valor;

    //SWITHC PARA LA VALIDACION DE DATOS VACIOS O NULOS
    public $fecha_nacimientoSwitch;
    public $es_extranjeroSwitch;
    public $expedido_ciSwitch;
    public $matriculaSwitch;
    public $id_estado_civilSwitch;
    public $switch_ru, $swith_gestion;

    // para la ubicacion
    public $latitud;
    public $longitud;
    public $ubicacion_select;

    public $carreras = [];

    public $fechaNacimientoMaximoConvenio;

    public $personaExiste;

    public $extranjero_switch;
    public $estado_civil_switch;
    public $fecha_nacimiento_switch;
    public $matricula_switch;
    public $estado_asuss;
    public $mostrarFormulario;
    public $estadoBusqueda;
    public $municipioNacimiento;

    public $roles;
    public $roles_select;
    public $usuario;
    public $id_tipo_asegurado_switch, $yaTieneRolAsignado, $grupo_sanguineo_switch;

    protected $listeners = ['registrarEstudiante', 'recibirLonLat'];

    public function rules()
    {
        return [
            'nombres' => 'required',
            // 'p_apellido' => 'required',
            //  's_apellido' => 'required',
            'ci' => 'required',
            'expedido_ci' => 'required',
            'fecha_nacimiento' => 'required',
            'direccion' => 'required|min:5',
            'zona' => 'required',
            'nro_vivienda' => 'required|min:1',
            'telefono' => 'required|min:6|max:9',
            'correo_institucional' => 'required|email',
            'nombre_referencia' => 'required|min:2',
            'telefono_referencia' => 'required|min:6|max:9',
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'es_extranjero' => 'required',
            'id_estado_civil' => 'required',
            'matricula' => 'required',
            //'expedido_ci' => 'required',
            //  'mun_nacimiento' => 'required',
            // 'pro_nacimiento' => 'required',
            // 'dep_nacimiento' => 'required',
            // 'grupo_sanguineo' => 'required',
            'registro_universitario' => 'required|min:4|max:10',
            'gestion_ingreso' => 'required|integer|min:1900|max:' . date('Y'),
            'semestre_actual' => 'required',
            'id_carrera' => 'required',
            //'facultad' => 'required',
            // 'file_tipo_sangre' => 'required|mimes:pdf|max:51200',
            'file_fotocopiaCarnet' => 'required|mimes:pdf|max:51200',
            'file_matricula' => 'required|mimes:pdf|max:51200',
            'file_boletaInscripcion' => 'required|mimes:pdf|max:51200',
            'file_certificadoCajas' => 'required|mimes:pdf|max:51200',
            'file_enteGestor' => 'required|mimes:pdf|max:51200',
            //'file_digital'=> 'required',


        ];
    }

    public function render()
    {
        return view('livewire.Afiliacion.estudiantes-ssue.estudiantes-create');
    }

    public function mount()
    {
        //$this->esExtranjeroFunction();
        //$this->mostrarEstudiante();
        if ($this->fecha_nacimiento != null) {
            //dd($this->fecha_nacimiento);
            if ($this->sexo != null) {
                if ($this->sexo == 'F') {
                    $this->sexo = true;
                } elseif ($this->sexo == 'M') {
                    $this->sexo = false;
                }
            }
            $this->edad = Carbon::parse($this->fecha_nacimiento)->age;
            $this->GenerarMatricula();
        }
        $this->fecha_solicitud = Carbon::now()->format('Y-m-d');
        $this->fecha_solicitud_literal = Carbon::parse($this->fecha_solicitud)->format('d/m/Y');
        $this->zonasSelect = Zona::orderBy('nombre', 'asc')->get();
        $this->departamento = Departamento::all();
        $this->estadosCiviles = EstadoCivil::all();
        $this->carrera = Carrera::orderBy('nombre', 'asc')->get();
        $this->fechaNacimientoMaximoConvenio = Carbon::now()->subYears(38)->format('Y-m-d');
    }

    public $carnetBusqueda;
    public $complementoBusqueda;

    public function reiniciarVariablesBusqueda()
    {
        $this->personaExiste = false;
        $this->id_persona = null;
        $this->nombres = null;
        $this->p_apellido = null;
        $this->s_apellido = null;
        $this->ci = null;
        $this->complemento = null;
        $this->expedido_ci = null;
        $this->expedido_ciSwitch = false;
        $this->fecha_nacimiento = null;
        $this->fecha_nacimiento_switch = false;
        $this->sexo = false;
        $this->matricula = null;
        $this->matricula_switch = false;
        $this->es_extranjero = null;
        $this->extranjero_switch = null;
        $this->id_estado_civil = null;
        $this->estado_civil_switch = false;
        $this->mun_nacimiento = null;
        $this->pro_nacimiento = null;
        $this->dep_nacimiento = null;
        $this->municipioNacimiento = false;
        $this->grupo_sanguineo = null;
        $this->grupo_sanguineo_switch = false;
    }

    public function buscarPersona()
    {
        // dd($this->carnetBusqueda, $this->complementoBusqueda);
        try {
            $this->reiniciarVariablesBusqueda();

            $this->mostrarFormulario = true;
            if ($this->complementoBusqueda == '') {
                $this->complementoBusqueda = null;
            }

            $existePersona = Persona::where('ci', $this->carnetBusqueda)->where('complemento', $this->complementoBusqueda)
                ->where(function ($tipoAfiliado) {
                    $tipoAfiliado->whereIn('id_tipo_asegurado', [0, 11])
                        ->orWhere('afiliado', false);
                })
                ->first();
            if ($existePersona) {
                $this->personaExiste = true;
                $this->id_persona = $existePersona->id;
                $this->ci = $existePersona->ci;
                $this->complemento = $existePersona->complemento;
                $this->p_apellido = $existePersona->p_apellido;
                $this->s_apellido = $existePersona->s_apellido;
                $this->nombres = $existePersona->nombres;
                $this->fecha_nacimiento = $existePersona->fecha_nacimiento;
                $this->fecha_nacimiento_switch = $existePersona->fecha_nacimiento ? true : false;
                $this->matricula = $existePersona->matricula_seguro;
                $this->matricula_switch = $existePersona->matricula_seguro ? true : false;
                $this->grupo_sanguineo = $existePersona->grupo_sanguineo;
                if ($existePersona->grupo_sanguineo && $existePersona->grupo_sanguineo != 'NINGUNO') {
                    $this->grupo_sanguineo_switch = true;
                } else {
                    $this->grupo_sanguineo_switch = false;
                }
                //$this->grupo_sanguineo_switch = $existePersona->grupo_sanguineo ? true : false;

                /*$datosMun = Municipio::with(['provinciaMunicipio.departamentoProvincia'])->find($existePersona->id_nacimiento_municipio);
            dd($datosMun);  */

                if (is_null($existePersona->id_nacimiento_municipio)) {
                    $this->municipioNacimiento = false;
                } else {
                    $datosMun = Municipio::with(['provinciaMunicipio.departamentoProvincia'])->find($existePersona->id_nacimiento_municipio);
                    $this->mun_nacimiento = $datosMun->id;
                    $this->pro_nacimiento = $datosMun->provinciaMunicipio->id;
                    $this->dep_nacimiento = $datosMun->provinciaMunicipio->departamentoProvincia->id;
                    $this->provincias = Provincia::all();
                    $this->municipios = Municipio::all();
                    $this->municipioNacimiento = true;
                }
                //DATOS QUE PODRIAN SER NULOS 

                $this->expedido_ci = $existePersona->id_dept_exp;
                if (is_null($existePersona->id_dept_exp)) {
                    $this->expedido_ciSwitch = false;
                } else {
                    $this->expedido_ciSwitch = true;
                }

                $this->es_extranjero = $existePersona->es_extranjero;
                if (is_null($existePersona->es_extranjero)) {
                    $this->extranjero_switch = false;
                } else {
                    $this->extranjero_switch = true;
                }

                $this->id_estado_civil = $existePersona->id_estado_civil;
                $this->estado_civil_switch = $existePersona->id_estado_civil ? true : false;

                if ($existePersona->sexo == 'F') {
                    $this->sexo = true;
                } elseif ($existePersona->sexo == 'M') {
                    $this->sexo = false;
                } elseif ($existePersona->sexo == null) {
                    $this->sexo = false;
                } else {
                    $this->sexo = false;
                }

                $this->id_tipo_asegurado = $existePersona->id_tipo_asegurado;

                $this->estadoBusqueda = 2;
                $this->muestraTipoEstudiante($this->id_persona);
            } else {
                $this->afiliacion_txt = 'NUEVO';
                $this->personaExiste = false;
                //PARA QUE NO AGREGUE A ASEGURADOS

                $existeComoAfiliado = Persona::where('ci', $this->carnetBusqueda)->where('complemento', $this->complementoBusqueda)
                    ->where('afiliado', true)
                    ->whereNot('id_tipo_asegurado', 11)
                    ->first();

                if ($existeComoAfiliado) {
                    $this->estadoBusqueda = 5;
                    $this->mostrarFormulario = false;
                    $this->dispatch('personaAfiliada');
                } else {
                    $respuesta = new ValidarPersona();
                    $dato = $respuesta->buscarPersona($this->carnetBusqueda, $this->complementoBusqueda);

                    if (isset($dato['datosPersonaEnFormatoJson'])) {
                        //$this->personaExiste = true;
                        $this->estado_asuss = true;
                        $this->personaExiste = true;
                        $this->ci = $dato['datosPersonaEnFormatoJson']['numeroDocumento'];
                        $this->complemento = $dato['datosPersonaEnFormatoJson']['Complemento'];
                        $this->p_apellido = $dato['datosPersonaEnFormatoJson']['primerApellido'];
                        $this->s_apellido = $dato['datosPersonaEnFormatoJson']['segundoApellido'];
                        $this->nombres = $dato['datosPersonaEnFormatoJson']['nombres'];
                        $this->fecha_nacimiento = $dato['datosPersonaEnFormatoJson']['fechaNacimiento'];
                        // $this->grupo_sanguineo = $dato['datosPersonaEnFormatoJson']['grupo_sanguineo'];
                        $this->fecha_nacimiento_switch = true;
                        $this->estadoBusqueda = 1;
                        $this->sexo = false;
                        $this->matricula_switch = false;
                        $this->extranjero_switch = false;
                        $this->estado_civil_switch = false;
                        $this->id_tipo_asegurado_switch = false;
                    } else {

                        $personaAfiliada = Persona::where('ci', $this->carnetBusqueda)->where('complemento', $this->complementoBusqueda)
                            ->with(['tipoAseguradoPersona'])
                            ->first();

                        if ($personaAfiliada) {
                            if ($personaAfiliada->tipoAseguradoPersona->tipo_asegurado == 'TITULAR') {
                                $esTitular = Titular::where('id_persona', $personaAfiliada->id)->first();
                                if ($esTitular) {
                                    if ($esTitular->estado_vigencia == 'NO VIGENTE') {
                                        $this->personaExiste = true;
                                        $this->id_persona = $personaAfiliada->id;
                                        $this->ci = $personaAfiliada->ci;
                                        $this->complemento = $personaAfiliada->complemento;
                                        $this->p_apellido = $personaAfiliada->p_apellido;
                                        $this->s_apellido = $personaAfiliada->s_apellido;
                                        $this->nombres = $personaAfiliada->nombres;
                                        $this->fecha_nacimiento = $personaAfiliada->fecha_nacimiento;
                                        $this->fecha_nacimiento_switch = $personaAfiliada->fecha_nacimiento ? true : false;
                                        $this->matricula = $personaAfiliada->matricula_seguro;
                                        $this->grupo_sanguineo = $personaAfiliada->grupo_sanguineo;
                                        $this->matricula_switch = $personaAfiliada->matricula_seguro ? true : false;

                                        //DATOS QUE PODRIAN SER NULOS 
                                        $this->es_extranjero = $personaAfiliada->es_extranjero;
                                        if (is_null($personaAfiliada->es_extranjero)) {
                                            $this->extranjero_switch = false;
                                        } else {
                                            $this->extranjero_switch = true;
                                        }

                                        $this->id_estado_civil = $personaAfiliada->id_estado_civil;
                                        $this->estado_civil_switch = $personaAfiliada->id_estado_civil ? true : false;

                                        if ($personaAfiliada->sexo == 'F') {
                                            $this->sexo = true;
                                        } elseif ($personaAfiliada->sexo == 'M') {
                                            $this->sexo = false;
                                        } elseif ($personaAfiliada->sexo == null) {
                                            $this->sexo = false;
                                        } else {
                                            $this->sexo = false;
                                        }

                                        $this->id_tipo_asegurado = $personaAfiliada->id_tipo_asegurado;
                                        $this->estadoBusqueda = 2;
                                    } else {
                                        $this->estadoBusqueda = 5;
                                    }
                                } else {
                                    $this->personaExiste = false;
                                    $this->estadoBusqueda = 0;
                                    $this->nombres = null;
                                    $this->p_apellido = null;
                                    $this->s_apellido = null;
                                    $this->sexo = false;
                                    $this->fecha_nacimiento = null;
                                    $this->fecha_nacimiento_switch = false;
                                    $this->ci = $this->carnetBusqueda;
                                    $this->complemento = $this->complementoBusqueda;
                                    $this->matricula_switch = false;
                                    $this->extranjero_switch = false;
                                    $this->estado_civil_switch = false;
                                    $this->id_tipo_asegurado_switch = false;
                                }
                            } elseif (Str::contains($personaAfiliada->tipoAseguradoPersona->tipo_asegurado, 'BENEFICIARIO')) {
                                if ($personaAfiliada->tipoAseguradoPersona->tipo_asegurado != 'BENEFICIARIO DEL INTERIOR') {
                                    $esBeneficiario = Beneficiario::where('id_persona', $personaAfiliada->id)->first();
                                    if ($esBeneficiario) {
                                        if ($esBeneficiario->vigencia == 'NO VIGENTE') {
                                            $this->personaExiste = true;
                                            $this->id_persona = $personaAfiliada->id;
                                            $this->ci = $personaAfiliada->ci;
                                            $this->complemento = $personaAfiliada->complemento;
                                            $this->p_apellido = $personaAfiliada->p_apellido;
                                            $this->s_apellido = $personaAfiliada->s_apellido;
                                            $this->nombres = $personaAfiliada->nombres;
                                            $this->grupo_sanguineo = $personaAfiliada->grupo_sanguineo;
                                            $this->fecha_nacimiento = $personaAfiliada->fecha_nacimiento;
                                            $this->fecha_nacimiento_switch = $personaAfiliada->fecha_nacimiento ? true : false;
                                            $this->matricula = $personaAfiliada->matricula_seguro;
                                            $this->matricula_switch = $personaAfiliada->matricula_seguro ? true : false;

                                            //DATOS QUE PODRIAN SER NULOS 
                                            $this->es_extranjero = $personaAfiliada->es_extranjero;
                                            if (is_null($personaAfiliada->es_extranjero)) {
                                                $this->extranjero_switch = false;
                                            } else {
                                                $this->extranjero_switch = true;
                                            }

                                            $this->id_estado_civil = $personaAfiliada->id_estado_civil;
                                            $this->estado_civil_switch = $personaAfiliada->id_estado_civil ? true : false;

                                            if ($personaAfiliada->sexo == 'F') {
                                                $this->sexo = true;
                                            } elseif ($personaAfiliada->sexo == 'M') {
                                                $this->sexo = false;
                                            } elseif ($personaAfiliada->sexo == null) {
                                                $this->sexo = false;
                                            } else {
                                                $this->sexo = false;
                                            }

                                            $this->id_tipo_asegurado = $personaAfiliada->id_tipo_asegurado;
                                            $this->estadoBusqueda = 2;
                                        } else {
                                            $this->estadoBusqueda = 5;
                                        }
                                    } else {
                                        $this->personaExiste = false;
                                        $this->estadoBusqueda = 0;
                                        $this->nombres = null;
                                        $this->p_apellido = null;
                                        $this->s_apellido = null;
                                        $this->sexo = false;
                                        $this->fecha_nacimiento = null;
                                        $this->fecha_nacimiento_switch = false;
                                        $this->ci = $this->carnetBusqueda;
                                        $this->complemento = $this->complementoBusqueda;
                                        $this->matricula_switch = false;
                                        $this->extranjero_switch = false;
                                        $this->estado_civil_switch = false;
                                        $this->id_tipo_asegurado_switch = false;
                                    }
                                }
                            } elseif ($personaAfiliada->tipoAseguradoPersona->tipo_asegurado == 'ESTUDIANTE') {
                                $esEstudiante = Estudiante::where('id_persona', $personaAfiliada->id)->first();
                                if ($esEstudiante) {
                                    if ($esEstudiante->estado_vigencia == false) {
                                        $this->personaExiste = true;
                                        $this->id_persona = $personaAfiliada->id;
                                        $this->ci = $personaAfiliada->ci;
                                        $this->complemento = $personaAfiliada->complemento;
                                        $this->p_apellido = $personaAfiliada->p_apellido;
                                        $this->s_apellido = $personaAfiliada->s_apellido;
                                        $this->grupo_sanguineo = $personaAfiliada->grupo_sanguineo;
                                        $this->nombres = $personaAfiliada->nombres;
                                        $this->fecha_nacimiento = $personaAfiliada->fecha_nacimiento;
                                        $this->fecha_nacimiento_switch = $personaAfiliada->fecha_nacimiento ? true : false;
                                        $this->matricula = $personaAfiliada->matricula_seguro;
                                        $this->matricula_switch = $personaAfiliada->matricula_seguro ? true : false;

                                        //DATOS QUE PODRIAN SER NULOS 
                                        $this->es_extranjero = $personaAfiliada->es_extranjero;
                                        if (is_null($personaAfiliada->es_extranjero)) {
                                            $this->extranjero_switch = false;
                                        } else {
                                            $this->extranjero_switch = true;
                                        }

                                        $this->id_estado_civil = $personaAfiliada->id_estado_civil;
                                        $this->estado_civil_switch = $personaAfiliada->id_estado_civil ? true : false;

                                        if ($personaAfiliada->sexo == 'F') {
                                            $this->sexo = true;
                                        } elseif ($personaAfiliada->sexo == 'M') {
                                            $this->sexo = false;
                                        } elseif ($personaAfiliada->sexo == null) {
                                            $this->sexo = false;
                                        } else {
                                            $this->sexo = false;
                                        }

                                        $this->id_tipo_asegurado = $personaAfiliada->id_tipo_asegurado;
                                        $this->estadoBusqueda = 2;
                                    } else {
                                        $this->estadoBusqueda = 5;
                                    }
                                } else {
                                    $this->personaExiste = false;
                                    $this->estadoBusqueda = 0;
                                    $this->nombres = null;
                                    $this->p_apellido = null;
                                    $this->s_apellido = null;
                                    $this->sexo = false;
                                    $this->fecha_nacimiento = null;
                                    $this->fecha_nacimiento_switch = false;
                                    $this->ci = $this->carnetBusqueda;
                                    $this->complemento = $this->complementoBusqueda;
                                    $this->matricula_switch = false;
                                    $this->extranjero_switch = false;
                                    $this->estado_civil_switch = false;
                                    $this->id_tipo_asegurado_switch = false;
                                }
                            } else {
                                $this->personaExiste = false;
                                $this->estadoBusqueda = 0;
                                $this->nombres = null;
                                $this->p_apellido = null;
                                $this->s_apellido = null;
                                $this->sexo = false;
                                $this->fecha_nacimiento = null;
                                $this->fecha_nacimiento_switch = false;
                                $this->ci = $this->carnetBusqueda;
                                $this->complemento = $this->complementoBusqueda;
                                $this->matricula_switch = false;
                                $this->extranjero_switch = false;
                                $this->estado_civil_switch = false;
                                $this->id_tipo_asegurado_switch = false;
                            }
                        } else {
                            $this->personaExiste = false;
                            $this->estadoBusqueda = 0;
                            $this->nombres = null;
                            $this->p_apellido = null;
                            $this->s_apellido = null;
                            $this->sexo = false;
                            $this->fecha_nacimiento = null;
                            $this->fecha_nacimiento_switch = false;
                            $this->ci = $this->carnetBusqueda;
                            $this->complemento = $this->complementoBusqueda;
                            $this->matricula_switch = false;
                            $this->extranjero_switch = false;
                            $this->estado_civil_switch = false;
                            $this->id_tipo_asegurado_switch = false;
                        }
                    }
                }
            }

            if ($this->fecha_nacimiento != null) {
                //dd($this->id_tipo_asegurado);
                if ($this->sexo != null) {
                    if ($this->sexo == 'F') {
                        $this->sexo = true;
                    } elseif ($this->sexo == 'M') {
                        $this->sexo = false;
                    }
                } else {
                    $this->sexo = false;
                }
                $this->GenerarMatricula();
            }
        } catch (\Throwable $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error al buscar persona", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }

    public function esExtranjeroFunction()
    {
        $this->valor = $this->es_extranjero;
        //  $valor2 = $this->tipo_doc_1;

    }

    public function obtenerFacultad()
    {
        $carrera = Carrera::find($this->id_carrera);
        if ($carrera) {
            $this->id_facultad = $carrera->id_facultad;
            $facultadActual = Facultad::find($this->id_facultad);
            $this->facultad = $facultadActual->nombre;
        }
    }

    public function ObtenerProvincia()
    {
        $this->pro_nacimiento = null;
        $this->mun_nacimiento = null;
        $this->provincias = Provincia::where('id_departamento', '=', $this->dep_nacimiento)->get();
    }

    public function ObtenerMunicipio()
    {
        $this->mun_nacimiento = null;
        $this->municipios = Municipio::where('id_provincia', '=', $this->pro_nacimiento)->get();
    }

    // obtener matricula 
    public function GenerarMatricula()
    {
        $nombre = $this->nombres;
        $p_apellido = $this->p_apellido;
        $s_apellido = $this->s_apellido;
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

    public function recibirLonLat($d_lat, $d_lng)
    {
        $this->latitud = $d_lat;
        $this->longitud = $d_lng;
        $this->ubicacion_select = 1;
    }

    // para comprimir archivos con ghotsScript
    public function comprimirPDF($archivoTemporal, $nombreSalida)
    {
        try {
            // Guardar archivo original temporal
            $rutaOriginal = $archivoTemporal->storeAs('temp', uniqid() . '_original.pdf', 'local');
            $rutaCompletaOriginal = storage_path('app/' . $rutaOriginal);
            $rutaCompletaComprimido = storage_path('app/temp/' . $nombreSalida);
            // Verificar si el archivo original se guardó correctamente
            if (!file_exists($rutaCompletaOriginal)) {
                throw new \Exception("Afiliacion:El archivo original no se guardó: $rutaCompletaOriginal");
            }
            // Detectar sistema operativo
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                // Ruta Ghostscript en Windows
                $gsPath = '"C:\Program Files\gs\gs10.03.0\bin\gswin64c.exe"';
            } else {
                // Comando estándar en Linux (se asume que Ghostscript está en el PATH)
                $gsPath = 'gs';
            }
            // Comando Ghostscript con compresión moderada
            $comando = "$gsPath -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 " .
                "-dPDFSETTINGS=/ebook -dNOPAUSE -dQUIET -dBATCH " .
                "-sOutputFile=\"$rutaCompletaComprimido\" \"$rutaCompletaOriginal\"";
            exec($comando, $output, $return_var);
            // Validar ejecución
            if ($return_var !== 0) {
                throw new \Exception("Ghostscript falló. Código: $return_var\nSalida:\n" . implode("\n", $output));
            }
            // Validar compresión
            if (!file_exists($rutaCompletaComprimido)) {
                throw new \Exception("Afiliacion:No se pudo comprimir el archivo: $nombreSalida");
            }
            // Eliminar original
            Storage::delete($rutaOriginal);
            // Retornar ruta del archivo comprimido
            return $rutaCompletaComprimido;
        } catch (\Exception $e) {
            Log::error('Afiliacion:Error al comprimir PDF: ' . $e->getMessage());
            throw $e;
        }
    }

    //Aqui registra estudiante
    public function registrarEstudiante()
    {
        set_time_limit(12000);
        $this->validate();
        DB::beginTransaction();
        //  try {
        $persona = $this->crearPersonaSiNoExiste();
        $this->verificarRol($persona);
        if (!$this->yaTieneRolAsignado) {
            $residenciaId = $this->gestionarResidencia();
            $existeEstudiante = Estudiante::where('id_persona', $persona->id)->orderBy('id', 'desc')->first();
            if ($existeEstudiante) {
                $existeEstudiante->update(['estado' => false]);
                DocumentosEstudiante::where('id_estudiante', $existeEstudiante->id)
                    ->orderBy('id', 'desc')
                    ->first()?->update(['activo' => false, 'id_user_updated' => auth()->id()]);
            }

            $datosEstudiante = [
                'registro_universitario' => $this->registro_universitario,
                'gestion' => $this->gestion_ingreso,
                'semestre' => $this->semestre_actual,
                'id_carrera' => $this->id_carrera,
                'telefono_referencia' => $this->telefono_referencia,
                'nombre_referencia' => $this->nombre_referencia,
                'observacion' => $this->observacion,
                'id_persona' => $persona->id,
                'telefono' => $this->telefono,
                'correo_institucional' => $this->correo_institucional,
                'estado' => true,
                'reafiliacion' => false,
                'estado_validar' => 'AFILIADO',
                'estado_vigencia' => true,
                'id_residencia' => $residenciaId,
                'fecha_fin_vigencia' => Carbon::createFromDate(Carbon::now()->year + 1, 3, 31)->format('Y-m-d'),
                'id_user_created' => auth()->user()->id,
            ];
            //dd($existeEstudiante);
            if ($existeEstudiante) {
                $datosEstudiante['fecha_reafiliacion'] = $this->fecha_solicitud;
                $datosEstudiante['afiliacion'] = false;
                if ($existeEstudiante->fecha_afiliacion == null) {
                    $datosEstudiante['fecha_afiliacion'] = $this->fecha_solicitud;
                } else {
                    $datosEstudiante['fecha_afiliacion'] = $existeEstudiante->fecha_afiliacion;
                }
            } else {
                $datosEstudiante['fecha_afiliacion'] = $this->fecha_solicitud;
                $datosEstudiante['afiliacion'] = true;
            }
            $estudiante = Estudiante::create($datosEstudiante);

            $persona->update([
                'id_tipo_asegurado' => 11,
                'afiliado' => true,
                'grupo_sanguineo' => $this->grupo_sanguineo,
                'alergias' => $this->alergias,
                'id_nacimiento_municipio' => $this->mun_nacimiento,
                'id_dept_exp' => $this->expedido_ci,
                'es_extranjero' => $this->es_extranjero,
                'id_estado_civil' => $this->id_estado_civil,

            ]);
            $this->crearHistorialAsegurado($persona, $estudiante);
            $this->gestionarDocs($estudiante, $persona);
            $this->crearUsuario($persona);
            $this->enviarNotificacion($persona);
            //INSERSION GEHMA2
            $replica_estudiante = new EstudiantesGehma2();
            $respuesta = $replica_estudiante->insercionEstudiantesGehma2($estudiante->id);

            DB::commit();
            $this->dispatch('estudianteRegistrado');
            return redirect()->route('afiliacion.ssue.index');
        }
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     $this->dispatch('estudianteCancelado');
        //     $trace = collect($e->getTrace())
        //         ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));
        //     Log::error("Error al crear estudiante manualmente: ", [
        //         'USUARIO_ID' => auth()->user()->id ?? null,
        //         'ERROR'      => $e->getMessage() ?? '',
        //         'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
        //         'LINEA'      => $trace['line'] ?? $e->getLine(),
        //         'FECHA_HORA' => now(),
        //     ]);
        // }
    }

    private function gestionarResidencia()
    {
        try {
            $residencia = Residencia::create([
                'direccion' => $this->direccion,
                'nro_vivienda' => $this->nro_vivienda,
                'id_zona' => $this->zona,
                'id_user_created' => auth()->id(),
            ]);
            return $residencia->id;
        } catch (\Exception $e) {
            Log::error('Afiliacion:Error en crear residencia: ' . $e->getMessage());
            throw $e;
        }
    }
    private function crearPersonaSiNoExiste()
    {
        try {
            if (!$this->personaExiste || $this->estadoBusqueda === 1) {
                $clave_unica = $this->complemento ? $this->ci . $this->complemento : $this->ci;
                $persona = Persona::create([
                    'ci' => $this->ci,
                    'complemento' => $this->complemento,
                    'nombres' => $this->nombres,
                    'p_apellido' => $this->p_apellido,
                    's_apellido' => $this->s_apellido,
                    'sexo' => $this->sexo ? 'F' : 'M',
                    'id_estado_civil' => $this->id_estado_civil,
                    'id_nacimiento_municipio' => $this->mun_nacimiento,
                    'id_dept_exp' => $this->expedido_ci,
                    'fecha_nacimiento' => $this->fecha_nacimiento,
                    'matricula_seguro' => $this->matricula,
                    'id_tipo_asegurado' => 11,
                    'es_extranjero' => $this->es_extranjero,
                    'estado_assus' => $this->estado_asuss,
                    'afiliado' => true,
                    'id_user_created' => auth()->user()->id,
                    'clave_unica' => $clave_unica,
                    'grupo_sanguineo' => $this->grupo_sanguineo,
                    'alergias' => $this->alergias,
                ]);
                $this->id_persona = $persona->id;
            } else {
                $persona = Persona::find($this->id_persona);
            }
            return $persona;
        } catch (\Exception $e) {

            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Afiliacion:error e crear persona", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }
    private function gestionarDocs($estudiante, $personaActual)
    {
        try {
            // FOTO
            $nombre_foto = $estudiante->id . '-E.jpg';
            $imagePath = $this->foto->getRealPath();
            $imageData = file_get_contents($imagePath);
            $imageBase64_asuss = base64_encode($imageData);
            $imageBase64 = 'data:image/' . $this->foto->extension() . ';base64,' . base64_encode($imageData);
            $estudiante->update([
                'foto_nombre' => $nombre_foto,
            ]);
            $this->foto->storeAs('Afiliacion/SSUE/FOTOS', $nombre_foto, 'ftp');
            //ftp files
            $nombre_file_sangre = "Tipo_Sangre-" . $estudiante->id . '.pdf';
            if (!$this->grupo_sanguineo_switch) {
                if ($this->file_tipo_sangre) {
                    $rutaComprimida = $this->comprimirPDF($this->file_tipo_sangre, $nombre_file_sangre);
                    Storage::disk('ftp')->put('Afiliacion/SSUE/DOCUMENTOS/' . $nombre_file_sangre, file_get_contents($rutaComprimida));
                    unlink($rutaComprimida);
                    // Storage::disk('ftp')->put(
                    //     'Afiliacion/SSUE/DOCUMENTOS/' . $nombre_file_sangre,
                    //     file_get_contents($this->file_tipo_sangre->getRealPath())
                    // );
                } else {
                    $this->sin_doc_sangre = true;
                    $personaActual->update([
                        'grupo_sanguineo' => 'NINGUNO',
                    ]);
                    $file_sangre_por_defecto = public_path('docs/afiliacion/SIN_TIPO_SANGRE.pdf');
                    Storage::disk('ftp')->put(
                        'Afiliacion/SSUE/DOCUMENTOS/' . $nombre_file_sangre,
                        file_get_contents($file_sangre_por_defecto)
                    );
                }
            }


            $nombre_file_carnet = "Carnet-" . $estudiante->id . '.pdf';
            $rutaComprimida = $this->comprimirPDF($this->file_fotocopiaCarnet, $nombre_file_carnet);
            Storage::disk('ftp')->put('Afiliacion/SSUE/DOCUMENTOS/' . $nombre_file_carnet, file_get_contents($rutaComprimida));
            unlink($rutaComprimida);
            // Storage::disk('ftp')->put(
            //     'Afiliacion/SSUE/DOCUMENTOS/' . $nombre_file_carnet,
            //     file_get_contents($this->file_fotocopiaCarnet->getRealPath())
            // );

            $nombre_file_matricula = "Matricula-" . $estudiante->id . '.pdf';
            $rutaComprimida = $this->comprimirPDF($this->file_matricula, $nombre_file_matricula);
            Storage::disk('ftp')->put('Afiliacion/SSUE/DOCUMENTOS/' . $nombre_file_matricula, file_get_contents($rutaComprimida));
            unlink($rutaComprimida);
            // Storage::disk('ftp')->put(
            //     'Afiliacion/SSUE/DOCUMENTOS/' . $nombre_file_matricula,
            //     file_get_contents($this->file_matricula->getRealPath())
            // );

            $nombre_file_boleta = "Boleta_Inscripcion-" . $estudiante->id . '.pdf';
            $rutaComprimida = $this->comprimirPDF($this->file_boletaInscripcion, $nombre_file_boleta);
            Storage::disk('ftp')->put('Afiliacion/SSUE/DOCUMENTOS/' . $nombre_file_boleta, file_get_contents($rutaComprimida));
            unlink($rutaComprimida);
            //  Storage::disk('ftp')->put(
            //      'Afiliacion/SSUE/DOCUMENTOS/' . $nombre_file_boleta,
            //      file_get_contents($this->file_boletaInscripcion->getRealPath())
            //  );


            $nombre_file_cajas = "Certificado_Cajas-" . $estudiante->id . '.pdf';
            $rutaComprimida = $this->comprimirPDF($this->file_certificadoCajas, $nombre_file_cajas);
            Storage::disk('ftp')->put('Afiliacion/SSUE/DOCUMENTOS/' . $nombre_file_cajas, file_get_contents($rutaComprimida));
            unlink($rutaComprimida);

            // Storage::disk('ftp')->put(
            //     'Afiliacion/SSUE/DOCUMENTOS/' . $nombre_file_cajas,
            //     file_get_contents($this->file_certificadoCajas->getRealPath())
            // );

            $nombre_file_gestora = "Certificado_Gestora-" . $estudiante->id . '.pdf';
            $rutaComprimida = $this->comprimirPDF($this->file_enteGestor, $nombre_file_gestora);
            Storage::disk('ftp')->put('Afiliacion/SSUE/DOCUMENTOS/' . $nombre_file_gestora, file_get_contents($rutaComprimida));
            unlink($rutaComprimida);
            // Storage::disk('ftp')->put(
            //     'Afiliacion/SSUE/DOCUMENTOS/' . $nombre_file_gestora,
            //     file_get_contents($this->file_enteGestor->getRealPath())
            // );

            $documentos = DocumentosEstudiante::create([
                'id_estudiante' => $estudiante->id,
                'file_carnet' => $nombre_file_carnet,
                'estado_carnet' => 2, // 1 afiliado, 2 actualizado, 3 observado
                'file_matricula' => $nombre_file_matricula,
                'estado_matricula' => 2,
                'file_boleta' => $nombre_file_boleta,
                'estado_boleta' => 2,
                'file_certificado_gestora' => $nombre_file_gestora,
                'estado_certificado_gestora' => 2,
                'file_certificado_cajas' => $nombre_file_cajas,
                'estado_certificado_cajas' => 2,
                'file_tipo_sangre' => $nombre_file_sangre,
                'estado_tipo_sangre' => 2,
                'activo' => true,
                'id_user_created' => auth()->user()->id,
            ]);
            if ($this->sin_doc_sangre == true) {
                $documentos->update([
                    'estado_tipo_sangre' => 4,
                ]);
            }
        } catch (\Throwable $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Afiliacion:error en subir docs", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
            throw $e;
        }
    }
    private function verificarRol($personaActual)
    {
        $this->yaTieneRolAsignado = false;
        $es_titular = Titular::where('id_persona', $personaActual->id)
            ->whereIn('estado_vigencia', ['VIGENTE', 'CESANTE'])
            ->where('estado', true)
            ->orderBy('id', 'DESC')
            ->first();

        $es_estudiante = Estudiante::where('id_persona', $personaActual->id)
            ->where('estado_vigencia', true)
            ->where('estado_validar', 'AFILIADO')
            ->where('estado', true)
            ->orderBy('id', 'DESC')
            ->first();

        $es_beneficiario = Beneficiario::where('id_persona', $personaActual->id)
            ->whereIn('vigencia', ['VIGENTE', 'CESANTE'])
            ->where('estado', true)
            ->orderBy('id', 'DESC')
            ->first();

        if ($es_titular) {
            $this->dispatch('esTitular');
            $this->yaTieneRolAsignado = true;
        } elseif ($es_estudiante) {
            $this->dispatch('esEstudiante');
            $this->yaTieneRolAsignado = true;
        } elseif ($es_beneficiario) {
            $this->dispatch('esBeneficiario');
            $this->yaTieneRolAsignado = true;
        }
    }

    private function enviarNotificacion($persona)
    {
        try {
            $rol = Role::where('name', 'Auxiliar Afiliacion SSUE')->first();
            $usuarios = RolUsuario::where('role_id', $rol->id)->pluck('model_id');

            foreach ($usuarios as $id) {
                NotificacionUsuarios::create([
                    'estado' => 'NO LEIDO',
                    'prioridades' => 1,
                    'id_usu_emisor' => auth()->id(),
                    'id_usu_receptor' => $id,
                    'fecha_hora_enviada' => now(),
                    'asunto' => 'AFILIACION SSUE - PREREGISTRO',
                    'mensaje' => "El estudiante: {$persona->p_apellido} {$persona->s_apellido} {$persona->nombres} fue registrado exitosamente.",
                ]);
            }

            // try {
            //     Mail::to($this->correo_institucional)->send(new AfiliacionEstudiantesMail(
            //         'Sistema Sighov2 - Unidad de Afiliación',
            //         Estudiante::join('administracion.persona as per', 'per.id', '=', 'afiliacion.estudiante.id_persona')
            //             ->where('afiliacion.estudiante.id', $id)->first(),
            //         base64_encode(file_get_contents($this->foto->getRealPath()))
            //     ));
            // } catch (\Exception $e) {
            //     Log::error('No se pudo enviar el correo: ' . $e->getMessage());
            // }
        } catch (\Exception $e) {
            Log::error('Afiliacion:Error en envio de notificacion o correo : ' . $e->getMessage());
            throw $e;
        }
    }
    public function GenerarUsuario($persona_encontrada)
    {
        try {
            // Convertir a mayúsculas para asegurar consistencia
            $nombres = str_replace(' ', '', mb_strtolower($persona_encontrada->nombres));
            $p_apellido = str_replace(' ', '', mb_strtolower($persona_encontrada->p_apellido));
            $s_apellido = str_replace(' ', '', mb_strtolower($persona_encontrada->s_apellido));

            // Inicializar el email
            $email = '';

            if (!empty($p_apellido) == true && !empty($s_apellido) == true) {
                $email = substr($nombres, 0, 1) . $p_apellido . substr($s_apellido, 0, 1);
            }

            if (!empty($p_apellido) == false && !empty($s_apellido) == true) {
                $email = substr($nombres, 0, 1) . $s_apellido . substr($s_apellido, 0, 1);
            }

            if (!empty($p_apellido) == true && !empty($s_apellido) == false) {
                $email = substr($nombres, 0, 1) . $p_apellido . substr($p_apellido, 0, 1);
            }

            return $email;
        } catch (\Exception $e) {
            Log::error('Error generar usuario registro manul estudiantes: ' . $e->getMessage());
            throw $e;
        }
    }
    private function crearUsuario($persona)
    {
        try {
            $this->roles = Role::where('name', 'like', 'Estudiante SSUE')->get();
            $this->roles_select = $this->roles->pluck('name')->toArray();
            $usuario = User::where('id_persona', $this->id_persona)->count();
            $usuario_creado_nuevo = false;
            $this->usuario = $this->GenerarUsuario($persona);

            if ($usuario == 0) {
                $numeroInvertido = (int) strrev((string) $persona->id);
                $resultado = $numeroInvertido + 3;
                $user = User::create([
                    'name' => $this->nombres . ' ' . $this->p_apellido . ' ' . $this->s_apellido,
                    'email' => $this->usuario . '-est-' . $resultado,
                    'id_persona' => $persona->id,
                    'id_usu_create' => $persona->id,
                    'id_user_created' => auth()->user()->id,
                    'estado' => true,
                    'password' => Hash::make($this->ci),
                ]);
                $usuario_creado_nuevo = true;
            } else {
                $user = User::where('id_persona', $this->id_persona)->first();
            }
            // dd('aquii no entro', $user, $this->usuario, $usuario_creado_nuevo);
            // Subida de foto
            $nombre_foto = $user->id . '- U.jpg';
            $user->update(['imagen' => $nombre_foto]);
            if ($this->foto) {
                $this->foto->storeAs('Administracion/Usuarios/Perfil', $nombre_foto, 'ftp');
            } else {
                $this->foto = 'images/default_afiliacion.jpg';
            }


            // Asignar roles
            foreach ($this->roles_select as $rol) {
                $user->assignRole($rol);
            }

            if ($usuario_creado_nuevo === true) {

                $this->js('window.open("/Administracion/usuario/nuevo/' . $user->id . '", "_blank")');
                return redirect()->route('Afiliacion.Estudiantes.Usuario.Index')->with('evento', 'UsuarioCreado');
            }
            //else {
            //25/07/2025: agregar una nueva ruta para el estudiante asegurad que ya tenia su usuario y contraseña
            // $this->js('window.open("/Administracion/usuario/repetir/reafiliacion/' . $user->id . '", "_blank")');
            //  return redirect()->route('Afiliacion.Estudiantes.Usuario.Index')->with('evento', 'UsuarioCreado');
            //}
        } catch (\Exception $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error en crear usuario", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
            throw $e;
        }
    }
    private function crearHistorialAsegurado($persona, $estudiante)
    {
        $existeHistorialAsegurado = HistorialAsegurado::where('id_persona', $persona->id)->orderBy('id', 'desc')->first();
        if ($existeHistorialAsegurado) {
            $existeHistorialAsegurado->estado = false;
            $existeHistorialAsegurado->estado_vigencia = 'NO VIGENTE';
            if ($existeHistorialAsegurado->fecha_baja == null) {
                $existeHistorialAsegurado->fecha_baja = Carbon::now()->format('Y-m-d');
            }
            $existeHistorialAsegurado->id_user_updated = auth()->user()->id;
            $existeHistorialAsegurado->save();

            HistorialAsegurado::create([
                'estado' => true,
                'estado_vigencia' => 'VIGENTE',
                'id_tipo_asegurado' => 11,
                'fecha_inicio' => Carbon::now()->format('Y-m-d'),
                'id_persona' => $persona->id,
                'id_corresponde' => $estudiante->id,
                'id_user_created' => auth()->user()->id,
            ]);
        } else {
            HistorialAsegurado::create([
                'estado' => true,
                'estado_vigencia' => 'VIGENTE',
                'id_tipo_asegurado' => 11,
                'fecha_inicio' => Carbon::now()->format('Y-m-d'),
                'id_persona' => $persona->id,
                'id_corresponde' => $estudiante->id,
                'id_user_created' => auth()->user()->id,
            ]);
        }
    }


    public function cancelarEstudiante()
    {
        $this->dispatch('estudianteCancelado');
        return redirect()->route('afiliacion.ssue.index');
    }


    /*public function mostrarEstudiante()
    {
        $userActual = User::join('administracion.persona as p', 'p.id', '=', 'administracion.users.id_persona')
            ->where('administracion.users.id', '=', auth()->user()->id)
            ->first();
        $this->id_persona = $userActual->id_persona;

        $personaActual = Persona::find($this->id_persona);
        //ESTOS DATOS SI O SI SE TIENEN
        $this->nombres = $personaActual->nombres;
        $this->p_apellido = $personaActual->p_apellido;
        $this->s_apellido = $personaActual->s_apellido;
        $this->ci = $personaActual->ci;
        $this->complemento = $personaActual->complemento;

        //ESTOS DATOS POSIBLEMENTE SI, POSIBLMENTE NO. PARA SABER SI SI O SI NO ME HAGO SWITCHS
        $this->fecha_nacimientoSwitch = $personaActual->fecha_nacimiento ? true : false;
        $this->fecha_nacimiento = $personaActual->fecha_nacimiento;
        if ($personaActual->sexo == 'F') {
            $this->sexo = true;
        } elseif ($personaActual->sexo == 'M') {
            $this->sexo = false;
        } elseif ($personaActual->sexo == null) {
            $this->sexo = false;
        } else {
            $this->sexo = false;
        }
        //dd($this->sexoBen);
        //DATOS DEL FORMULARIO QUE PODRIAN O NO SER NULOS
        if (is_null($personaActual->es_extranjero)) {
            $this->es_extranjeroSwitch = false;
        } else {
            $this->es_extranjeroSwitch = true;
        }
        $this->es_extranjero = $personaActual->es_extranjero;
        $this->expedido_ciSwitch = $personaActual->id_dept_exp ? true : false;
        $this->expedido_ci = $personaActual->id_dept_exp;
        $this->matriculaSwitch = $personaActual->matricula_seguro ? true : false;
        $this->matricula = $personaActual->matricula_seguro;
        $this->grupo_sanguineo = $personaActual->grupo_sanguineo;
        $this->alergias = $personaActual->alergias;
        if ($personaActual->id_nacimiento_municipio) {
            $this->datosLugarNacimiento = true;
            $this->mun_nacimiento = $personaActual->id_nacimiento_municipio;
            $mun = Municipio::find($personaActual->id_nacimiento_municipio);
            $this->mun_nacimiento_nombre = $mun->nombre;
            $this->pro_nacimiento = $mun->id_provincia;
            $prov = Provincia::find($mun->id_provincia);
            $this->pro_nacimiento_nombre = $prov->nombre;
            $this->dep_nacimiento = $prov->id_departamento;
            $dept = Departamento::find($prov->id_departamento);
            $this->dep_nacimiento_nombre = $dept->nombre;
            //dd($this->mun_nacimiento, $this->pro_nacimiento, $this->dep_nacimiento);
        }

        $this->id_estado_civilSwitch = $personaActual->id_estado_civil ? true : false;
        $this->id_estado_civil = $personaActual->id_estado_civil;


        $estudianteAntiguo = Estudiante::where('id_persona', $this->id_persona)
            ->orderBy('id', 'desc')
            ->first();
        if ($estudianteAntiguo) {
            $this->registro_universitario = $estudianteAntiguo->registro_universitario;
            $this->gestion_ingreso = $estudianteAntiguo->gestion;
            $this->semestre_actual = $estudianteAntiguo->semestre;
            $this->id_carrera = $estudianteAntiguo->id_carrera;
            // $this->afiliacion = $estudianteAntiguo->afiliacion ? 'NUEVO' : 'ANTIGUO';
            $afili =  $estudianteAntiguo->afiliacion;
            if ($afili == null || $afili == true) {
                $this->afiliacion_txt = 'ANTIGUO';
            }
            // dd( $this->afiliacion_txt );
            $this->obtenerFacultad();
        } else {
            $this->afiliacion_txt = 'NUEVO';
        }

        $tiene_matriculas = HistorialMatriculaEstudiante::where('id_persona', $this->id_persona)
            ->first();
        if ($tiene_matriculas) {
            $this->registro_universitario = $tiene_matriculas->registro_universitario;
            $this->switch_ru = true;
            $this->gestion_ingreso = $tiene_matriculas->gestion_ingreso;
            $this->swith_gestion = true;
        } else {
            $this->switch_ru = false;
            $this->swith_gestion = false;
        }
        // dd($tiene_matriculas);
        //NECESITAMOS CALCULAR LA EDAD EN ESE PUNTO?
        //$this->edad=Carbon::parse($this->fecha_nacimiento)->age;
    }*/

    public function volver()
    {
        return redirect()->route('afiliacion.ssue.index');
    }

    public function muestraTipoEstudiante($id)
    {
        $estudianteAntiguo = Estudiante::where('id_persona', $id)
            ->orderBy('id', 'desc')
            ->first();
        if ($estudianteAntiguo) {
            $afili =  $estudianteAntiguo->afiliacion;
            if ($afili == null || $afili == true) {
                $this->afiliacion_txt = 'ANTIGUO';
            }
        } else {
            $this->afiliacion_txt = 'NUEVO';
        }
    }
}
