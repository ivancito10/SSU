<?php

namespace App\Livewire\Vigencia\Convenios\Interior;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Locked;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

use App\Services\ValidarPersona;

use App\Models\Administracion\Persona;
use App\Models\Administracion\TipoAsegurado;
use App\Models\Administracion\EstadoCivil;
use App\Models\Administracion\Especialidad;
use App\Models\Administracion\Servicio;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\Estudiante;
use App\Models\User;
use App\Models\Vigencia\AutorizacionInterior;
use App\Models\Vigencia\AutorizacionIntEspecialidad;
use App\Models\Vigencia\AutorizacionIntServicio;
use App\Models\Vigencia\SeguroInterior;
use App\Models\Vigencia\HistorialAsegurado;
use App\Services\ConveniosGehma2;
use App\Services\RespuestaApisService;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;

use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class ConvenioInteriorRegister extends Component
{
    #[Locked]
    public $idPersona;
    use WithFileUploads;
    #[Validate]
    public $foto_asegurado;
    #[Validate]
    public $cod_interior,
        $cod_cite_int,
        $fecha_inicio,
        $fecha_fin;
    #[Validate]
    public $id_seguro_interior;
    public $otras_limitaciones,
        $id_persona_interior,
        $id_persona_titular,
        $id_personal_ssu,
        $foto_nombre,
        $observacion,
        $medicamentos_liname = false,
        $medicamentos_extraliname = false,
        $estado;
    #[Validate]
    public $nro_hoja_de_ruta;
    #[Validate]
    public $fecha_solicitud_hr,
        $especialidades,
        $servicios,
        $cod_interior_m,
        //Para la busqueda
        $estadoBusqueda;
    //Para los datos de la persona
    #[Validate]
    public $ci,
        $complemento,
        $p_apellido,
        $s_apellido,
        $nombre,
        $fecha_nacimiento,
        $matricula,
        $id_estado_civil,
        $estado_civil,
        $es_extranjero,
        $sexo,
        $tipo_asegurado,
        $personaExiste;
    public $estadoCivil, $seguros, $especialidadesSelect, $serviciosSelect;
    //para validaciones
    public $fecha_nacimiento_switch, $matricula_switch, $extranjero_switch, $estado_civil_switch, $id_tipo_asegurado_switch;
    public $titularYaSeleccionado = false;
    public $id_tipo_asegurado;

    public $carnetBusqueda, $complementoBusqueda;
    public $grupo_sanguineo;
    public $alergias;
    public $telefono_referencia;
    public $direccion_referencia;
    public $datos_referencia;
    public $telefono;

    public $mostrarFormulario;

    public $diasVigencia;

    protected $listeners = ['search_persona', 'registrarConvenioInterior', 'titularSelected', 'quitarTitularConvenioInterior'];


    public function rules()
    {
        return [
            'nro_hoja_de_ruta' => 'required',
            'fecha_solicitud_hr' => 'required|date',
            //'cod_cite_int' => 'required',
            'cod_interior_m' => 'required',
            'id_seguro_interior' => 'required',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date',
            //'especialidades' => 'required',
            //'servicios' => 'required',
            'ci' => 'required',
            'es_extranjero' => 'required',
            'nombre' => 'required',
            //'p_apellido' => 'required',
            //'s_apellido' => 'required',
            'fecha_nacimiento' => 'required',
            'matricula' => 'required',
            'id_tipo_asegurado' => 'required',
            'id_estado_civil' => 'required',
        ];
    }

    public function render()
    {
        return view('livewire.Vigencia.convenios.interior.convenio-interior-register');
    }


    public function mount()
    {
        $this->diasVigencia = 0;
        $this->mostrarFormulario = false;
        $this->nuevoNumeroCite();
        $this->estadoCivil = EstadoCivil::all();
        $this->seguros = SeguroInterior::where('estado', true)->get();
        $this->especialidadesSelect = Especialidad::orderBy('especialidad', 'asc')->get();
        $this->serviciosSelect = Servicio::all();
    }

    public function calcularDiasVigencia()
    {
        if ($this->fecha_inicio !== null && $this->fecha_fin !== null) {
            $this->diasVigencia = Carbon::parse($this->fecha_fin)->diffInDays(Carbon::parse($this->fecha_inicio)) + 1;
        }
    }

    public function search_persona()
    {
        $this->mostrarFormulario = true;
        if ($this->complementoBusqueda == '') {
            $this->complementoBusqueda = null;
        }

        $existePersona = Persona::where('ci', $this->carnetBusqueda)->where('complemento', $this->complementoBusqueda)
            ->where(function ($convenioInterior) {
                $convenioInterior->whereIn('id_tipo_asegurado', [8, 9, 10])
                    ->orWhere('afiliado', false);
            })
            ->first();

        if ($existePersona) {
            $this->personaExiste = true;
            $this->idPersona = $existePersona->id;
            $this->ci = $existePersona->ci;
            $this->complemento = $existePersona->complemento;
            $this->p_apellido = $existePersona->p_apellido;
            $this->s_apellido = $existePersona->s_apellido;
            $this->nombre = $existePersona->nombres;
            $this->fecha_nacimiento = $existePersona->fecha_nacimiento;
            $this->fecha_nacimiento_switch = $existePersona->fecha_nacimiento ? true : false;
            $this->matricula = $existePersona->matricula_seguro;
            $this->matricula_switch = $existePersona->matricula_seguro ? true : false;

            //DATOS QUE PODRIAN SER NULOS 
            $this->es_extranjero = $existePersona->es_extranjero;
            if (is_null($existePersona->es_extranjero)) {
                $this->extranjero_switch = false;
            } else {
                $this->extranjero_switch = true;
            }

            $this->id_estado_civil = $existePersona->id_estado_civil;
            $this->estado_civil_switch = $existePersona->id_estado_civil ? true :  false;

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

            if ($existePersona->id_tipo_asegurado === 0) {
                $this->id_tipo_asegurado = null;
            }

            $this->estadoBusqueda = 2;
        } else {
            $this->personaExiste = false;
            //PARA QUE NO AGREGUE A ASEGURADOS

            $existeComoAfiliado = Persona::where('ci', $this->carnetBusqueda)->where('complemento', $this->complementoBusqueda)
                ->where('afiliado', true)
                ->whereNotIn('id_tipo_asegurado', [8, 9, 10])
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
                    $this->ci = $dato['datosPersonaEnFormatoJson']['numeroDocumento'];
                    $this->complemento = $dato['datosPersonaEnFormatoJson']['Complemento'];
                    $this->p_apellido = $dato['datosPersonaEnFormatoJson']['primerApellido'];
                    $this->s_apellido = $dato['datosPersonaEnFormatoJson']['segundoApellido'];
                    $this->nombre = $dato['datosPersonaEnFormatoJson']['nombres'];
                    $this->fecha_nacimiento = $dato['datosPersonaEnFormatoJson']['fechaNacimiento'];
                    $this->fecha_nacimiento_switch = true;
                    $this->estadoBusqueda = 1;
                    $this->sexo = false;
                    $this->matricula_switch  = false;
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
                                    $this->idPersona = $personaAfiliada->id;
                                    $this->ci = $personaAfiliada->ci;
                                    $this->complemento = $personaAfiliada->complemento;
                                    $this->p_apellido = $personaAfiliada->p_apellido;
                                    $this->s_apellido = $personaAfiliada->s_apellido;
                                    $this->nombre = $personaAfiliada->nombres;
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
                                    $this->estado_civil_switch = $personaAfiliada->id_estado_civil ? true :  false;

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
                                $this->nombre = null;
                                $this->p_apellido = null;
                                $this->s_apellido = null;
                                $this->sexo = false;
                                $this->fecha_nacimiento = null;
                                $this->fecha_nacimiento_switch = false;
                                $this->ci = $this->carnetBusqueda;
                                $this->complemento = $this->complementoBusqueda;
                                $this->matricula_switch  = false;
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
                                        $this->idPersona = $personaAfiliada->id;
                                        $this->ci = $personaAfiliada->ci;
                                        $this->complemento = $personaAfiliada->complemento;
                                        $this->p_apellido = $personaAfiliada->p_apellido;
                                        $this->s_apellido = $personaAfiliada->s_apellido;
                                        $this->nombre = $personaAfiliada->nombres;
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
                                        $this->estado_civil_switch = $personaAfiliada->id_estado_civil ? true :  false;

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
                                    $this->nombre = null;
                                    $this->p_apellido = null;
                                    $this->s_apellido = null;
                                    $this->sexo = false;
                                    $this->fecha_nacimiento = null;
                                    $this->fecha_nacimiento_switch = false;
                                    $this->ci = $this->carnetBusqueda;
                                    $this->complemento = $this->complementoBusqueda;
                                    $this->matricula_switch  = false;
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
                                    $this->idPersona = $personaAfiliada->id;
                                    $this->ci = $personaAfiliada->ci;
                                    $this->complemento = $personaAfiliada->complemento;
                                    $this->p_apellido = $personaAfiliada->p_apellido;
                                    $this->s_apellido = $personaAfiliada->s_apellido;
                                    $this->nombre = $personaAfiliada->nombres;
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
                                    $this->estado_civil_switch = $personaAfiliada->id_estado_civil ? true :  false;

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
                                $this->nombre = null;
                                $this->p_apellido = null;
                                $this->s_apellido = null;
                                $this->sexo = false;
                                $this->fecha_nacimiento = null;
                                $this->fecha_nacimiento_switch = false;
                                $this->ci = $this->carnetBusqueda;
                                $this->complemento = $this->complementoBusqueda;
                                $this->matricula_switch  = false;
                                $this->extranjero_switch = false;
                                $this->estado_civil_switch = false;
                                $this->id_tipo_asegurado_switch = false;
                            }
                        } else {
                            $this->personaExiste = false;
                            $this->estadoBusqueda = 0;
                            $this->nombre = null;
                            $this->p_apellido = null;
                            $this->s_apellido = null;
                            $this->sexo = false;
                            $this->fecha_nacimiento = null;
                            $this->fecha_nacimiento_switch = false;
                            $this->ci = $this->carnetBusqueda;
                            $this->complemento = $this->complementoBusqueda;
                            $this->matricula_switch  = false;
                            $this->extranjero_switch = false;
                            $this->estado_civil_switch = false;
                            $this->id_tipo_asegurado_switch = false;
                        }
                    } else {
                        $this->personaExiste = false;
                        $this->estadoBusqueda = 0;
                        $this->nombre = null;
                        $this->p_apellido = null;
                        $this->s_apellido = null;
                        $this->sexo = false;
                        $this->fecha_nacimiento = null;
                        $this->fecha_nacimiento_switch = false;
                        $this->ci = $this->carnetBusqueda;
                        $this->complemento = $this->complementoBusqueda;
                        $this->matricula_switch  = false;
                        $this->extranjero_switch = false;
                        $this->estado_civil_switch = false;
                        $this->id_tipo_asegurado_switch = false;
                    }
                }
            }

            //FIN


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
    }

    public function nuevoNumeroCite()
    {
        $autorizacionAnterior = AutorizacionInterior::orderBy('id', 'desc')->first();
        if ($autorizacionAnterior) {
            $ultimaGestion = Carbon::parse($autorizacionAnterior->created_at)->format('Y');
            if ($ultimaGestion == Carbon::now()->format('Y')) {
                $this->cod_interior = $autorizacionAnterior->cod_interior + 1;
                $this->cod_interior_m = 'C/IN/' . $this->cod_interior . '/' . $ultimaGestion;
                //dd($this->cite);
            } else {
                $ultimaGestion = Carbon::now()->format('Y');
                $this->cod_interior = 1;
                $this->cod_interior_m = 'C/IN/' . $this->cod_interior . '/' . $ultimaGestion;
                //dd($this->cite);
            }
        } else {
            $this->cod_interior = 1;
            $this->cod_interior_m = 'C/IN/' . $this->cod_interior . '/' . Carbon::now()->format('Y');
        }
    }

    public function GenerarMatricula()
    {
        $nombre = $this->nombre;
        $p_apellido = $this->p_apellido;
        $s_apellido = $this->s_apellido;
        //dd($this->fecha_nacimiento);
        $fecha_nacimiento = Carbon::parse($this->fecha_nacimiento);
        // $fecha_nacimiento = Carbon::createFromFormat('d/m/Y', $this->fecha_nacimiento)->format('Y-m-d');
        $sexo = $this->sexo ? $this->sexo : false;
        //dd($fecha_nacimiento);
        $fechaNacimiento = $fecha_nacimiento;
        //$fechaNacimiento = Carbon::parse($fecha_nacimiento);
        //dd($nombre,$p_apellido,$s_apellido,$fecha_nacimiento,$sexo);
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

    public function registrarConvenioInterior($otraslimitaciones, $observaciones)
    {
        $this->otras_limitaciones = $otraslimitaciones;
        $this->observacion = $observaciones;
        //Validacion del sexo
        if ($this->sexo == false) {
            $this->sexo = 'M';
        } else {
            $this->sexo = 'F';
        }

        $this->validate();
        DB::beginTransaction();
        //dd($this->personaExiste, $this->idPersona);

        if (!$this->personaExiste) {
            if ($this->complemento == null) {
                $clave_unica = $this->ci;
            } else {
                $clave_unica = $this->ci . $this->complemento;
            }

            $nuevaPersona = Persona::create([
                'ci' => $this->ci,
                'complemento' => $this->complemento,
                'nombres' => $this->nombre,
                'p_apellido' => $this->p_apellido,
                's_apellido' => $this->s_apellido,
                'fecha_nacimiento' => $this->fecha_nacimiento,
                'matricula_seguro' => $this->matricula,
                'id_estado_civil' => $this->id_estado_civil,
                'id_tipo_asegurado' => $this->id_tipo_asegurado,
                'es_extranjero' => $this->es_extranjero,
                'sexo' => $this->sexo,
                'grupo_sanguineo' => $this->grupo_sanguineo,
                'alergias' => $this->alergias,
                'afiliado' => true,
                'clave_unica' => $clave_unica,
                'id_user_created' => auth()->user()->id,
            ]);

            $this->idPersona = $nuevaPersona->id;
        } else {
            $editarPersona = Persona::find($this->idPersona);

            //dd($this->idPersona);

            if (!$this->fecha_nacimiento_switch) {
                $editarPersona->fecha_nacimiento = $this->fecha_nacimiento;
                $editarPersona->save();
            }

            if (!$this->matricula_switch) {
                $editarPersona->matricula_seguro = $this->matricula;
                $editarPersona->sexo = $this->sexo;
                $editarPersona->save();
            }

            if (!$this->extranjero_switch) {
                $editarPersona->es_extranjero = $this->es_extranjero;
                $editarPersona->save();
            }

            if (!$this->estado_civil_switch) {
                $editarPersona->id_estado_civil = $this->id_estado_civil;
                $editarPersona->save();
            }

            if ($editarPersona->afiliado == false) {
                $editarPersona->afiliado = true;
                $editarPersona->save();
            }

            //if ($editarPersona->id_tipo_asegurado === 0) {
            $editarPersona->id_tipo_asegurado = $this->id_tipo_asegurado;
            $editarPersona->save();
            //}



            $this->idPersona = $editarPersona->id;
        }

        if ($this->id_tipo_asegurado == 9) {
            $this->id_persona_titular = $this->idPersona;
        }

        $convenioInterior = AutorizacionInterior::create([
            'cod_interior' => $this->cod_interior,
            'cod_cite_int' => $this->cod_cite_int,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'id_persona_interior' => $this->idPersona,
            'id_persona_titular' => $this->id_persona_titular,
            'id_seguro_interior' => $this->id_seguro_interior,
            'otras_limitaciones' => $this->otras_limitaciones,
            'observacion' => $this->observacion,
            'medicamentos_liname' => $this->medicamentos_liname,
            'medicamentos_extraliname' => $this->medicamentos_extraliname,
            'direccion_referencia' => $this->direccion_referencia,
            'telefono_referencia' => $this->telefono_referencia,
            'datos_referencia' => $this->datos_referencia,
            'telefono' => $this->telefono,
            'estado' => true,
            'estado_validacion' => 'PENDIENTE',
            //'estado_validacion' => 'VALIDADO',
            //'fecha_validacion' => Carbon::now(),
            'nro_hoja_de_ruta' => $this->nro_hoja_de_ruta,
            'fecha_solicitud_hr' => $this->fecha_solicitud_hr,
            //'id_user_validado' => auth()->user()->id,
            'id_user_created' => auth()->user()->id,
        ]);


        if ($convenioInterior) {

            // esto por la foto
            if ($this->foto_asegurado) {
                $nombreFoto = $convenioInterior->id . '-CI.jpg';
                $this->foto_asegurado->storeAs('Vigencia/FOTOS/CONVENIO/', $nombreFoto, 'ftp');
                $convenioInterior->update([
                    'foto_nombre' => $nombreFoto,
                ]);

                //PARA USUARIOS

                $idUsuario = $this->usuarioNuevo($this->idPersona);
                $nombreFotoUsuario = $idUsuario . '-U.png';
                $this->foto_asegurado->storeAs('Administracion/Usuarios/Perfil/', $nombreFotoUsuario, 'ftp');
            }


            //PARA LA TABLA DE HISTORIAL ASEGURADO

            $existeHistorialAsegurado = HistorialAsegurado::where('id_persona', $this->idPersona)->orderBy('id', 'desc')->first();

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
                    'id_tipo_asegurado' => $this->id_tipo_asegurado,
                    'fecha_inicio' => Carbon::now()->format('Y-m-d'),
                    'id_corresponde' => $convenioInterior->id,
                    'id_persona' => $this->idPersona,
                    'id_user_created' => auth()->user()->id,
                ]);
            } else {
                HistorialAsegurado::create([
                    'estado' => true,
                    'estado_vigencia' => 'VIGENTE',
                    'id_tipo_asegurado' => $this->id_tipo_asegurado,
                    'fecha_inicio' => Carbon::now()->format('Y-m-d'),
                    'id_corresponde' => $convenioInterior->id,
                    'id_persona' => $this->idPersona,
                    'id_user_created' => auth()->user()->id,
                ]);
            }

            // FIN TABLA DE HISTORIAL ASEGURADO



            $convenioId = $convenioInterior->id;

            if ($this->especialidades != [] && $this->servicios != []) {
                foreach ($this->especialidades as $especialidad) {
                    AutorizacionIntEspecialidad::create([
                        'id_autorizacion_interior' => $convenioId,
                        'id_especialidad' => $especialidad,
                        'id_user_created' => auth()->user()->id,
                    ]);
                }

                foreach ($this->servicios as $servicio) {
                    AutorizacionIntServicio::create([
                        'id_autorizacion_interior' => $convenioId,
                        'id_servicio' => $servicio,
                        'id_user_created' => auth()->user()->id,
                    ]);
                }

                DB::commit();
                $insercion = new ConveniosGehma2();
                $res = $insercion->insercionConveniosGehma2($convenioId);
                // dd($res['status']);

                $this->dispatch('AutorizacionRegistrada');
                return redirect()->route('vigencia.convenios.interior.index');
            } elseif ($this->especialidades != [] && $this->servicios == []) {
                foreach ($this->especialidades as $especialidad) {
                    AutorizacionIntEspecialidad::create([
                        'id_autorizacion_interior' => $convenioId,
                        'id_especialidad' => $especialidad,
                        'id_user_created' => auth()->user()->id,
                    ]);
                }
                DB::commit();
                $insercion = new ConveniosGehma2();
                $res = $insercion->insercionConveniosGehma2($convenioId);
                // dd($res['status']);

                $this->dispatch('AutorizacionRegistrada');
                return redirect()->route('vigencia.convenios.interior.index');
            } elseif ($this->especialidades == [] && $this->servicios != []) {
                foreach ($this->servicios as $servicio) {
                    AutorizacionIntServicio::create([
                        'id_autorizacion_interior' => $convenioId,
                        'id_servicio' => $servicio,
                        'id_user_created' => auth()->user()->id,
                    ]);
                }
                DB::commit();
                $insercion = new ConveniosGehma2();
                $res = $insercion->insercionConveniosGehma2($convenioId);
                

                $this->dispatch('AutorizacionRegistrada');
                return redirect()->route('vigencia.convenios.interior.index');
            } else {
                DB::commit();
                $insercion = new ConveniosGehma2();
                $res = $insercion->insercionConveniosGehma2($convenioId);
                
                $this->dispatch('AutorizacionRegistrada');
                return redirect()->route('vigencia.convenios.interior.index');
            }
        } else {
            DB::rollBack();
            $this->dispatch('AutorizacionNoRegistrada');
            return redirect()->route('vigencia.convenios.interior.index');
        }
    }

    public function usuarioNuevo($idPersona)
    {
        // creacion de usuario

        $existeUsuario = User::with('personaUser')->where('id_persona', $idPersona)->first();

        if (!$existeUsuario) {
            $persona_encontrada = Persona::where('id', $idPersona)->first();
            $nuevoUsuario = $this->generarUsuario($persona_encontrada);
            $numeroInvertido = (int)strrev((string)$idPersona);
            $resultado = $numeroInvertido + 3;

            $user = User::create([
                'name' => $persona_encontrada->nombres . ' ' . $persona_encontrada->p_apellido . ' ' . $persona_encontrada->s_apellido,
                'email' => $nuevoUsuario . '-conv-' . $resultado,
                'id_persona' => $idPersona,
                'id_user_created' => auth()->user()->id,
                'estado' => true,
                'password' => Hash::make($persona_encontrada->ci),
            ]);
        } else {
            $user = $existeUsuario;
        }

        $nombre_foto = $user->id . '-U.png';
        $user->update([
            'imagen' => $nombre_foto
        ]);

        //asignacion de rol
        $user->assignRole('Asegurado Afiliado');
        $user->syncRoles(['Asegurado Afiliado']);
        //

        return $user->id;
    }

    public function generarUsuario($persona_encontrada)
    {

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
    }

    public function cancelarAutorizacion()
    {
        return redirect()->route('vigencia.convenios.interior.index')->with('evento', 'AutorizacionNoRegistrada');
    }

    public function atras()
    {
        return redirect()->route('vigencia.convenios.interior.index');
    }

    public function abrirTablaTitularesInterior()
    {
        $this->dispatch('modalTablaTitularInterior');
        $this->skipRender();
    }

    public $nombreCompletoTitularInterior;
    public $matriculaTitularInterior;
    public $carnetTitularInterior;
    public $parentescoTitularInterior;

    #[On('titularSeleccionado')]
    public function titularSeleccionado($data)
    {
        //dd($data);
        $this->id_persona_titular = $data;
        $personaTitularInterior = Persona::where('id', $data)->with(['tipoAseguradoPersona'])->first();
        if ($personaTitularInterior) {
            if ($personaTitularInterior->p_apellido == null) {
                $this->nombreCompletoTitularInterior = $personaTitularInterior->s_apellido . ' ' . $personaTitularInterior->nombres;
            } elseif ($personaTitularInterior->s_apellido == null) {
                $this->nombreCompletoTitularInterior = $personaTitularInterior->p_apellido . ' ' . $personaTitularInterior->nombres;
            } else {
                $this->nombreCompletoTitularInterior = $personaTitularInterior->p_apellido . ' ' . $personaTitularInterior->s_apellido . ' ' . $personaTitularInterior->nombres;
            }

            $this->matriculaTitularInterior = $personaTitularInterior->matricula_seguro;
            if ($personaTitularInterior->complemento == null) {
                $this->carnetTitularInterior = $personaTitularInterior->ci;
            } else {
                $this->carnetTitularInterior = $personaTitularInterior->ci . ' - ' . $personaTitularInterior->complemento;
            }
            $this->parentescoTitularInterior = $personaTitularInterior->tipoAseguradoPersona->tipo_asegurado;
        }

        $this->dispatch('titularSel');
        $this->skipRender();
    }



    public function titularSelected()
    {
        $this->titularYaSeleccionado = true;
    }

    public function quitarTitularConvenioInterior()
    {
        try {
            $this->titularYaSeleccionado = false;
            $this->id_persona_titular = null;
            $this->dispatch('solRetirado');
        } catch (\Throwable $th) {
            $this->dispatch('solNoRetirado');
        }
    }

    /*public function abrirCrearTitularInterior(){
        //dd('entra');
        $this->dispatch('crear-titular')->to(CrearTitularInterior::class);
         // Para que nos e cierre primer modal
        $this->skipRender();
    }*/
}
