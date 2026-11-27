<?php

namespace App\Livewire\Vigencia\SolicitudPrestacionMedica;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Administracion\Persona;
use App\Models\Administracion\TipoAsegurado;
use App\Models\Administracion\Especialidad;
use App\Models\Administracion\Servicio;
use App\Models\Aportes\Institucion;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\TitularInstitucion;
use App\Models\Vigencia\SolicitudPMedica;
use App\Models\Vigencia\SeguroInterior;
use App\Models\Vigencia\SolMedicaEspecialidad;
use App\Models\Vigencia\SolMedicaServicio;
use App\Models\Vigencia\AseguradoSolicitante;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Carbon\Carbon;



class SolicitudPrestacionMedicaNuevo extends Component
{

    //DATOS DE LA PERSONA
    public $idAfiliado;

    public $matricula;
    public $ci;
    public $complemento;
    public $nombreCompleto;
    public $nombres;
    public $p_apellido;
    public $s_apellido;
    public $tipo_asegurado;
    public $institucion;
    public $foto;
    public $estado_vigencia;
    //PARA EL REGISTRO
    public $cite;
    public $cod_cite;
    #[Validate]
    public $fecha_inicio;
    #[Validate]
    public $fecha_fin;
    #[Validate]
    public $especialidades;
    #[Validate]
    public $servicios;

    public $otras_limitaciones;
    #[Validate]
    public $id_seguro_interior;
    public $medicamentos_liname = false;
    public $medicamentos_extraliname = false;
    #[Validate]
    public $nro_hoja_de_ruta;
    #[Validate]
    public $fecha_solicitud_hr;
    //PARA EL ASEGURADO SOLICITANTE
    public $id_tit_agregar;
    public $id_persona_solicitante;
    public $datos_persona_grupo_familiar = [];
    public $solicitantesAgregados = [];
    public $seguros = [];
    public $especialidadesSelect = [];
    public $serviciosSelect = [];
    public $observacion;
    public $diasVigencia = 0;
    public $edadPersona;
    public $codigoInstitucional;
    public $sexo;
    public $fecha_nacimiento;

    protected $listeners = ['registrarSolicitud', 'agregarSolicitante', 'quitarSolicitante'];

    public function rules()
    {
        return [
            'nro_hoja_de_ruta' => 'required',
            'fecha_solicitud_hr' => 'date',
            'id_seguro_interior' => 'required',
            //'id_seguro_interior' =>'required|exists:seguros,id',
            'fecha_inicio' => 'date',
            'fecha_fin' => 'date',
            //'especialidades' => 'required',
            //'servicios' => 'required',
        ];
    }

    public function render()
    {
        return view('livewire.Vigencia.solicitud-prestacion-medica.solicitud-prestacion-medica-nuevo');
    }

    public function mount()
    {
        $this->fecha_solicitud_hr = Carbon::now()->format('Y-m-d');
        $this->seguros = SeguroInterior::where('estado', true)->get();
        $this->especialidadesSelect = Especialidad::where('estado', true)->get();
        $this->serviciosSelect = Servicio::where('estado', true)->get();
        $this->mostrarDatosPersona($this->idAfiliado);
        $this->nuevoNumeroCite();
    }

    public function calcularDiasVigencia()
    {
        try {
            if ($this->fecha_fin != null) {
                $this->diasVigencia = (Carbon::parse($this->fecha_fin)->diffInDays($this->fecha_inicio)) + 1;
            } else {
                $this->diasVigencia = 0;
            }
        } catch (\Throwable $th) {
            $this->diasVigencia = 0;
        }
    }

    public function mostrarDatosPersona($idPersona)
    {
        $afiliado = Persona::find($idPersona);
        $this->matricula = $afiliado->matricula_seguro;
        if ($afiliado->complemento == null) {
            $this->ci = $afiliado->ci;
        } else {
            $this->ci = $afiliado->ci . ' - ' . $afiliado->complemento;
        }

        $this->complemento = $afiliado->complemento;
        $this->nombres = $afiliado->nombres;
        $this->p_apellido = $afiliado->p_apellido;
        $this->s_apellido = $afiliado->s_apellido;
        $this->nombreCompleto = $afiliado->p_apellido . ' ' . $afiliado->s_apellido . ' ' . $afiliado->nombres;
        $tipoAsegurado = TipoAsegurado::find($afiliado->id_tipo_asegurado);
        $this->tipo_asegurado = $tipoAsegurado->tipo_asegurado;
        $this->edadPersona = Carbon::parse($afiliado->fecha_nacimiento)->age;
        $this->fecha_nacimiento = $afiliado->fecha_nacimiento;
        $this->sexo = $afiliado->sexo == 'F' ? 'FEMENINO' : 'MASCULINO';

        $esTitular = Titular::where('id_persona', $idPersona)->whereIn('estado_vigencia', ['VIGENTE', 'CESANTE'])->first();
        if ($esTitular) {
            $this->foto = $this->obtenerImagenBase64($esTitular->foto_nombre);
            $this->estado_vigencia = $esTitular->estado_vigencia;

            //PARA EL GRUPO FAMILIAR

            $grupo_familiar = Beneficiario::where('id_titular', $esTitular->id)
                ->whereIn('vigencia', ['VIGENTE', 'CESANTE'])
                ->get();
            if ($grupo_familiar) {
                $this->id_tit_agregar = $esTitular->id_persona;
                foreach ($grupo_familiar as $key => $familiar) {
                    $personaBen = Persona::join('administracion.tipo_asegurado as ta', 'ta.id', '=', 'persona.id_tipo_asegurado')
                        ->select('persona.*', 'ta.tipo_asegurado as tipoAsegurado')
                        ->where('persona.id', $familiar->id_persona)
                        ->first();
                    $this->datos_persona_grupo_familiar[] = $personaBen;
                }
            }
            // FIN GRUPO FAMILIAR

            $titularInstitucion = TitularInstitucion::where('id_titular', $esTitular->id)
                ->where('estado', true)
                ->with(['titularInstitucionConInstitucion', 'titularInstitucionConInstitucion.codigosInstitucion'])
                ->first();
            if ($titularInstitucion) {
                $inst = Institucion::find($titularInstitucion->id_institucion);
                $this->institucion = $inst->nombre;
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
            $esBeneficiario = Beneficiario::where('id_persona', $idPersona)->where('estado', true)->first();
            $this->foto = $this->obtenerImagenBase64($esBeneficiario->foto_nombre);
            $this->estado_vigencia = $esBeneficiario->vigencia;

            //PARA EL GRUPO FAMILIAR

            $grupo_familiar = Beneficiario::where('id_titular', $esBeneficiario->id_titular)
                ->where('estado', true)
                ->whereNot('id', $esBeneficiario->id)
                ->get();
            if ($grupo_familiar) {
                foreach ($grupo_familiar as $key => $familiar) {
                    $personaBen = Persona::join('administracion.tipo_asegurado as ta', 'ta.id', '=', 'persona.id_tipo_asegurado')
                        ->select('persona.*', 'ta.tipo_asegurado as tipoAsegurado')
                        ->where('persona.id', $familiar->id_persona)
                        ->first();
                    $this->datos_persona_grupo_familiar[] = $personaBen;
                }
            }

            // FIN GRUPO FAMILIAR

            $titularBen = Titular::find($esBeneficiario->id_titular);
            $this->id_tit_agregar = $titularBen->id_persona;
            $titularInstBen = TitularInstitucion::where('id_titular', $titularBen->id)
                ->where('estado', true)
                ->first();
            if ($titularInstBen) {
                $inst = Institucion::find($titularInstBen->id_institucion);
                $this->institucion = $inst->nombre;
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

    public function agregarSolicitante($idPersona)
    {
        if (!in_array($idPersona, array_column($this->solicitantesAgregados, 'id'))) {
            $datosPersona = Persona::join('administracion.tipo_asegurado as ta', 'ta.id', '=', 'administracion.persona.id_tipo_asegurado')
                ->select('administracion.persona.*', 'ta.tipo_asegurado')
                ->where('administracion.persona.id', $idPersona)
                ->first();

            $this->solicitantesAgregados[] = [
                'id' => $datosPersona->id,
                'nombres' => $datosPersona->nombres,
                'p_apellido' => $datosPersona->p_apellido,
                's_apellido' => $datosPersona->s_apellido,
                'ci' => $datosPersona->ci,
                'complemento' => $datosPersona->complemento,
                'matricula_seguro' => $datosPersona->matricula_seguro,
                'tipo_asegurado' => $datosPersona->tipo_asegurado,
            ];
        }
    }

    public function quitarSolicitante($idPersona)
    {
        try {
            $nuevoArray = [];
            foreach ($this->solicitantesAgregados as $sol) {
                if ($sol['id'] != $idPersona) {
                    $nuevoArray[] = $sol;
                }
            }
            $this->solicitantesAgregados = $nuevoArray;
            $this->dispatch('solRetirado');
        } catch (\Throwable $th) {
            $this->dispatch('solNoRetirado');
        }
    }

    public function registrarSolicitud($otraslimitaciones, $observaciones)
    {
        if ($this->especialidades != [] || $this->servicios != []) {
            $this->otras_limitaciones = $otraslimitaciones;
            $this->observacion = $observaciones;
            //dd($this->otras_limitaciones,$this->observacion);
            $this->validate();
            //dd($this->solicitantesAgregados);
            DB::beginTransaction();
            try {

                /*$existeCodCite = SolicitudPMedica::where('cod_cite', $this->cod_cite)->first();
                
                if(!$existeCodCite){*/
                $this->nuevoNumeroCite();

                $nuevaSolicitud = SolicitudPMedica::create([
                    'cod_cite' => $this->cod_cite,
                    'fecha_inicio' => $this->fecha_inicio,
                    'fecha_fin' => $this->fecha_fin,
                    'otras_limitaciones' => $this->otras_limitaciones,
                    'id_seguro_interior' => $this->id_seguro_interior,
                    'observacion' => $this->observacion,
                    'medicamentos_liname' => $this->medicamentos_liname,
                    'medicamentos_extraliname' => $this->medicamentos_extraliname,
                    'totalidad_esp_serv' => false,
                    'estado' => true,
                    'nro_hoja_de_ruta' => $this->nro_hoja_de_ruta,
                    'fecha_solicitud_hr' => $this->fecha_solicitud_hr,
                    'estado_validacion' => 'PENDIENTE',
                    'id_user_created' => auth()->user()->id,
                ]);

                if ($nuevaSolicitud) {
                    $solicitudId = $nuevaSolicitud->id;

                    if ($this->especialidades != [] && $this->servicios != []) {
                        foreach ($this->especialidades as $especialidad) {
                            SolMedicaEspecialidad::create([
                                'id_solicitud_p_medica' => $solicitudId,
                                'id_especialidad' => $especialidad,
                                'id_user_created' => auth()->user()->id,
                            ]);
                        }
                        foreach ($this->servicios as $servicio) {
                            SolMedicaServicio::create([
                                'id_solicitud_p_medica' => $solicitudId,
                                'id_servicio' => $servicio,
                                'id_user_created' => auth()->user()->id,
                            ]);
                        }
                        AseguradoSolicitante::create([
                            'id_solicitud_p_medica' => $solicitudId,
                            'id_asegurado_solicitante' => $this->idAfiliado,
                            'id_asegurado_titular' => $this->id_tit_agregar,
                            'id_user_created' => auth()->user()->id,
                        ]);

                        if ($this->solicitantesAgregados != []) {
                            foreach ($this->solicitantesAgregados as $solicitante) {
                                AseguradoSolicitante::create([
                                    'id_solicitud_p_medica' => $solicitudId,
                                    'id_asegurado_solicitante' => $solicitante['id'],
                                    'id_asegurado_titular' => $this->id_tit_agregar,
                                    'id_user_created' => auth()->user()->id,
                                ]);
                            }
                        }

                        DB::commit();
                        return redirect()->route('vigencia.solprestacionesmedicas.index')->with('evento', 'SolicitudRegistrada');
                    } elseif ($this->especialidades != [] && $this->servicios == []) {
                        foreach ($this->especialidades as $especialidad) {
                            SolMedicaEspecialidad::create([
                                'id_solicitud_p_medica' => $solicitudId,
                                'id_especialidad' => $especialidad,
                                'id_user_created' => auth()->user()->id,
                            ]);
                        }

                        AseguradoSolicitante::create([
                            'id_solicitud_p_medica' => $solicitudId,
                            'id_asegurado_solicitante' => $this->idAfiliado,
                            'id_asegurado_titular' => $this->id_tit_agregar,
                            'id_user_created' => auth()->user()->id,
                        ]);

                        if ($this->solicitantesAgregados != []) {
                            foreach ($this->solicitantesAgregados as $solicitante) {
                                AseguradoSolicitante::create([
                                    'id_solicitud_p_medica' => $solicitudId,
                                    'id_asegurado_solicitante' => $solicitante['id'],
                                    'id_asegurado_titular' => $this->id_tit_agregar,
                                    'id_user_created' => auth()->user()->id,
                                ]);
                            }
                        }
                        DB::commit();
                        $this->dispatch('guardado');
                        return redirect()->route('vigencia.solprestacionesmedicas.index');
                        //return redirect()->route('vigencia.solprestacionesmedicas.index')->with('evento', 'SolicitudRegistrada');

                    } elseif ($this->especialidades == [] && $this->servicios != []) {
                        foreach ($this->servicios as $servicio) {
                            SolMedicaServicio::create([
                                'id_solicitud_p_medica' => $solicitudId,
                                'id_servicio' => $servicio,
                                'id_user_created' => auth()->user()->id,
                            ]);
                        }

                        AseguradoSolicitante::create([
                            'id_solicitud_p_medica' => $solicitudId,
                            'id_asegurado_solicitante' => $this->idAfiliado,
                            'id_asegurado_titular' => $this->id_tit_agregar,
                            'id_user_created' => auth()->user()->id,
                        ]);

                        if ($this->solicitantesAgregados != []) {
                            foreach ($this->solicitantesAgregados as $solicitante) {
                                AseguradoSolicitante::create([
                                    'id_solicitud_p_medica' => $solicitudId,
                                    'id_asegurado_solicitante' => $solicitante['id'],
                                    'id_asegurado_titular' => $this->id_tit_agregar,
                                    'id_user_created' => auth()->user()->id,
                                ]);
                            }
                        }

                        DB::commit();
                        $this->dispatch('guardado');
                        return redirect()->route('vigencia.solprestacionesmedicas.index');
                    } else {
                        DB::rollBack();
                        $this->dispatch('Noguardado');
                        return redirect()->route('vigencia.solprestacionesmedicas.index');
                    }
                } else {
                    DB::rollBack();
                    $this->dispatch('Noguardado');
                    return redirect()->route('vigencia.solprestacionesmedicas.index');
                }
                /*} else {
                    DB::rollBack();
                    $this->dispatch('repetido');
                }*/
            } catch (\Throwable $th) {
                DB::rollBack();
                $this->dispatch('Noguardado');
                return redirect()->route('vigencia.solprestacionesmedicas.index');
            }
        } else {
            $this->dispatch('sinEspNiServ');
        }
    }

    public function cancelarSolicitud()
    {
        $this->dispatch('Noguardado');
        return redirect()->route('vigencia.solprestacionesmedicas.index');
    }

    public function nuevoNumeroCite()
    {
        $solicitudAnterior = SolicitudPMedica::orderBy('id', 'desc')->first();
        if ($solicitudAnterior) {
            $ultimaGestion = Carbon::parse($solicitudAnterior->created_at)->format('Y');
            if ($ultimaGestion == Carbon::now()->format('Y')) {
                $this->cod_cite = $solicitudAnterior->cod_cite + 1;
                $this->cite = $this->cod_cite . '/' . $ultimaGestion;
                //dd($this->cite);
            } else {
                $ultimaGestion = Carbon::now()->format('Y');
                $this->cod_cite = 1;
                $this->cite = $this->cod_cite . '/' . $ultimaGestion;
                //dd($this->cite);
            }
        } else {
            $ultimaGestion = Carbon::now()->format('Y');
            $this->cod_cite = 1;
            $this->cite = $this->cod_cite . '/' . $ultimaGestion;
            //dd($this->cite);
        }
    }

    public function atras()
    {
        return redirect()->route('vigencia.solprestacionesmedicas.index')->with('evento', 'SolicitudNoRegistrada');
    }
}
