<?php

namespace App\Livewire\Vigencia\Preafiliacion;

use App\Models\Administracion\Departamento;
use App\Models\Administracion\EstadoCivil;
use App\Models\Administracion\Persona;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\TitularInstitucion;
use App\Models\Aportes\Institucion;
use App\Models\Vigencia\HistorialAsegurado;
use App\Models\Vigencia\Preafiliacion;
use App\Services\ReplicaPreafiliacion;
use App\Services\ValidarPersona;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Illuminate\Support\Str;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;

class PreafiliacionRegister extends Component
{
    #[Validate]
    public $estado_api;
    public $idPersona;
    #[Validate]
    public $es_extranjero;
    public $ci, $complemento_m, $ap_paterno, $ap_materno, $nombre, $fecha_nacimiento;
    public $dias;
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
    public $fecha_inicio, $fecha_fin, $observacion;

    #[Validate]
    public $id_persona, $id_tipo_convenio;
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

    public $carnetBusqueda;
    public $complementoBusqueda;
    public $carnetTitularBusqueda;
    public $complementoTitularBusqueda;

    #[Validate]
    public $nro_documento_respaldo;
    public $cod_convenio;
    public $nro_cod_convenio;

    public $nombre_institucion;

    public $mostrarFormulario;

    public $tipoPreafiliado;
    public $switchTipoAfiliado;
    #[Validate]
    public $idInstitucion;
    public $nombreInstitucion;
    public $instituciones = [];
    public $idTitular;
    #[Validate]
    public $nroPreafiliacion;
    public $switchTitularSeleccionado;

    //SI TIENE TITULAR, DATOS DEL TITULAR
    public $nombreTitular;
    public $matriculaTitular;
    public $institucionTitular;

    public $switchTipoAportante;
    public $tipoAportante;

    public $telefonoPreafiliado;

    public function render()
    {
        return view('livewire.Vigencia.preafiliacion.preafiliacion-register');
    }

    public function mount()
    {
        $this->switchTipoAportante = false;
        $this->tipoAportante = false;
        $this->mostrarFormulario = false;
        $this->depa = Departamento::all();
        $this->estados_civil = EstadoCivil::all();
        $this->fecha_inicio = Carbon::now()->format('Y-m-d');
        $this->fecha_fin = Carbon::now()->addDays(30)->format('Y-m-d');
        $this->dias = 30;
        $this->switchTitularSeleccionado = false;
        $this->generarNumero();
    }

    public function rules()
    {
        $rules = [
            'ci' => 'required',
            'nombre' => 'required',
            'fecha_nacimiento' => 'required|date',
            'id_estado_civil' => 'required',
            'expedido_ci' => 'required',
            'es_extranjero' => 'required',
            'nroPreafiliacion' => 'required',
            'nro_documento_respaldo' => 'required',
            'tipoPreafiliado' => 'required',
        ];

        if (($this->tipoPreafiliado === 'PREAFILIACION TITULAR') && !$this->tipoAportante) {
            $rules['idInstitucion'] = 'required';
        }

        return $rules;
    }

    public function search_persona()
    {
        if ($this->carnetBusqueda === null) {
            $this->dispatch('ciInvalidoPreaf');
            return;
        }

        $this->mostrarFormulario = true;
        try {
            if ($this->complementoBusqueda == '') {
                $this->complementoBusqueda = null;
            }

            $existePersona = Persona::where('ci', $this->carnetBusqueda)->where('complemento', $this->complementoBusqueda)
                ->where(function ($preafiliacion) {
                    $preafiliacion->where('id_tipo_asegurado', 0)
                        ->orWhere('afiliado', false);
                })
                ->first();

            if ($existePersona) {

                if (Preafiliacion::where('id_persona', $existePersona->id)
                    ->where('estado', true)
                    ->exists()
                ) {
                    $this->mostrarFormulario = false;
                    $this->dispatch('preafiliacionExistente');
                    return;
                }


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

    public function dias_diferencia()
    {
        $start = Carbon::parse($this->fecha_inicio);
        $end = Carbon::parse($this->fecha_fin);

        // Calcular la diferencia en días
        $this->dias = ($end->diffInDays($start)) + 1;
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

    public function tipoPreafiliacionSeleccionado()
    {
        $this->idInstitucion = null;
        $this->switchTitularSeleccionado = false;
        $this->nombreInstitucion = null;
        $this->idTitular = null;
        $this->nombreTitular = null;
        $this->matriculaTitular = null;
        $this->institucionTitular = null;

        if ($this->tipoPreafiliado === "PREAFILIACION TITULAR") {
            $this->switchTipoAportante = true;
            $this->switchTipoAfiliado = 1;
            $this->instituciones = Institucion::where('estado', true)->orderBy('nombre', 'asc')->get();
        }

        if ($this->tipoPreafiliado === "PREAFILIACION BENEFICIARIO") {
            $this->switchTipoAportante = false;
            $this->switchTipoAfiliado = 2;
        }
    }

    public function mostrarInstituciones()
    {
        if (!$this->tipoAportante) {
            $this->switchTipoAfiliado = 1;
            $this->instituciones = Institucion::where('estado', true)->orderBy('nombre', 'asc')->get();
        } else {
            $this->switchTipoAfiliado = 3;
            $this->instituciones = ['APORTANTE VOLUNTARIO'];
        }
    }

    /*public function verificarInstitucionSeleccionada(){
        if ($this->tipoPreafiliado === 'PREAFILIACION TITULAR') {
            if ($this->idInstitucion === null){

            }
        }
    }*/

    #[On('titularSeleccionado')]
    public function titularSeleccionado($data)
    {
        $personaTitular = Titular::where('id_persona', $data)
            ->where('estado_vigencia', 'VIGENTE')
            ->where('estado', true)
            ->with(['titularPersona'])
            ->first();

        if ($personaTitular) {
            $this->idTitular = $personaTitular->id;
            $this->nombreTitular = implode(' ', array_filter([$personaTitular->titularPersona->p_apellido, $personaTitular->titularPersona->s_apellido, $personaTitular->titularPersona->nombres]));
            $this->matriculaTitular = $personaTitular->titularPersona->matricula_seguro;
            $this->institucionTitular = 'APORTANTE VOLUNTARIO';

            if ($personaTitular->tipo_titular) {
                $institucionTitular = TitularInstitucion::where('id_titular', $personaTitular->id)
                    ->where('estado', true)
                    ->with(['titularInstitucionConInstitucion.codigosInstitucion'])
                    ->orderBy('id', 'asc')
                    ->first();

                $this->idInstitucion = $institucionTitular->id_institucion;
                $this->institucionTitular = $institucionTitular->titularInstitucionConInstitucion->nombre;
                $codigoInstitucional = $institucionTitular->titularInstitucionConInstitucion->codigosInstitucion ? $institucionTitular->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                    $institucionTitular->titularInstitucionConInstitucion->codigosInstitucion->tipo_titular : null;
                $this->matriculaTitular = implode(' - ', array_filter([$this->matriculaTitular, $codigoInstitucional]));
            }

            $this->switchTitularSeleccionado = true;
            $this->dispatch('titularAgregado');
        }
    }

    public function generarNumero()
    {
        $preafiliacionAnterior = Preafiliacion::orderBy('id', 'desc')->first();
        if ($preafiliacionAnterior) {
            $this->nroPreafiliacion = $preafiliacionAnterior->nro_preafiliacion + 1;
        } else {
            $this->nroPreafiliacion = 1;
        }
    }

    public function sinTitular()
    {
        if (($this->tipoPreafiliado === 'PREAFILIACION BENEFICIARIO'))
        //&& ($this->id_titular === null)) 
        {
            //dd($this->idTitular ? 'TIENE' : 'NO TIENE');
            if (!$this->idTitular) {
                //dd('entra');
                $this->dispatch('sinTitularSeleccionado');
                return;
            }
        }
    }
    

    #[On('registrarPreafiliado')]
    public function registrarPreafiliado($observaciones)
    {
        $this->validate();

        if (($this->tipoPreafiliado === 'PREAFILIACION BENEFICIARIO')) {
            if (!$this->idTitular) {
                $this->dispatch('sinTitularSeleccionado');
                return;
            }
        }
        //try {
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
                $existePersona->sexo = $this->sexo ? 'F' : 'M';
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

            $existePersona->id_tipo_asegurado = $this->tipoPreafiliado === 'PREAFILIACION TITULAR' ? 14 : ($this->tipoPreafiliado === 'PREAFILIACION BENEFICIARIO' ? 15 : 14);
            $existePersona->afiliado = true;
            $existePersona->save();

            $personaId = $existePersona->id;
        } else {
            if ($this->complemento_m == null) {
                $clave_unica = $this->ci;
            } else {
                $clave_unica = $this->ci . $this->complemento_m;
            }

            //dd($this->sexo === 'false' ? 'si loco' : 'true');

            $persona = Persona::create([
                'ci' => $this->ci,
                'complemento' => $this->complemento_m,
                'nombres' => $this->nombre,
                'p_apellido' => $this->ap_paterno == '' ? null : $this->ap_paterno,
                's_apellido' => $this->ap_materno == '' ? null : $this->ap_materno,
                'fecha_nacimiento' => $this->fecha_nacimiento,
                'estado_asuss' => $this->estado_asuss,
                'id_tipo_asegurado' => $this->tipoPreafiliado === 'PREAFILIACION TITULAR' ? 14 : 15,
                'afiliado' => true,
                'sexo' => $this->sexo ? 'F' : 'M',
                'matricula_seguro' => $this->matricula,
                'id_estado_civil' => $this->id_estado_civil,
                'id_dept_exp' => $this->expedido_ci,
                'es_extranjero' => $this->es_extranjero,
                'clave_unica' => $clave_unica,
                'id_user_created' => auth()->user()->id,
            ]);

            $personaId = $persona->id;
        }

        $nuevaPreafiliacion = Preafiliacion::create([
            'nro_preafiliacion' => $this->nroPreafiliacion,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'id_tipo_convenio' => 2,
            'id_persona' => $personaId,
            'observaciones' => $observaciones,
            'tipo_preafiliacion' => $this->tipoPreafiliado,
            'id_titular' => $this->idTitular,
            'id_institucion' => $this->idInstitucion,
            'nro_doc_respaldo' => $this->nro_documento_respaldo,
            'estado' => true,
            'id_user_created' => auth()->user()->id,
        ]);

        if ($nuevaPreafiliacion) {

            //REGISTRAR EN HISTORIAL ASEGURADO

            $existeHistorialAsegurado = HistorialAsegurado::where('id_persona', $personaId)->orderBy('id', 'desc')->first();

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
                    'id_tipo_asegurado' => $this->tipoPreafiliado === 'PREAFILIACION TITULAR' ? 14 : 15,
                    'fecha_inicio' => Carbon::now()->format('Y-m-d'),
                    'id_persona' => $personaId,
                    'id_corresponde' => $nuevaPreafiliacion->id,
                    'id_user_created' => auth()->user()->id,
                ]);
            } else {
                HistorialAsegurado::create([
                    'estado' => true,
                    'estado_vigencia' => 'VIGENTE',
                    'id_tipo_asegurado' => $this->tipoPreafiliado === 'PREAFILIACION TITULAR' ? 14 : 15,
                    'fecha_inicio' => Carbon::now()->format('Y-m-d'),
                    'id_persona' => $personaId,
                    'id_corresponde' => $nuevaPreafiliacion->id,
                    'id_user_created' => auth()->user()->id,
                ]);
            }

            //FIN REGISTRO HISTORIAL ASEGURADO


            $this->dispatch('preafiliacionGuardada');
            $replicaReafiliacion = new ReplicaPreafiliacion();
            $replicaReafiliacion->recopilarDatosPreafiliacion($nuevaPreafiliacion->id);
            return redirect()->route('vigencia.preafiliacion.index');
        } else {
            $this->dispatch('errorPreafiliacion');
        }
        //} catch (\Throwable $th) {
        //    $this->dispatch('errorPreafiliacion');
        //}
    }

    public function atras()
    {
        return redirect()->route('vigencia.preafiliacion.index');
    }

    public function cancel()
    {
        $this->dispatch('');
        $this->atras();
    }
}
