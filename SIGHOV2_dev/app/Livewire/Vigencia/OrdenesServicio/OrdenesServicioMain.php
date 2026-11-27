<?php

namespace App\Livewire\Vigencia\OrdenesServicio;

use App\Models\Administracion\Persona;
use App\Models\Vigencia\OrdenServicio;
use App\Models\Vigencia\InstitucionConvenio;
use App\Models\Aportes\Institucion;
use App\Models\Administracion\TipoAsegurado;
use App\Models\Administracion\Especialidad;
use App\Models\Plataforma\Especialista;
use App\Models\Afiliacion\TitularInstitucion;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\Beneficiario;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use App\Services\ValidarPersona;
use Livewire\Component;
use Carbon\Carbon;
use Livewire\Attributes\On;

use App\Services\DatosAgenda;

use App\Models\Administracion\RolUsuario;
use App\Models\Administracion\NotificacionUsuarios;
use App\Models\Administracion\Role;
use App\Models\Vigencia\AutorizacionInterior;
use App\Models\Vigencia\Convenio;
use App\Services\Migracion\Plataforma\MigrarEspecialistas;

class OrdenesServicioMain extends Component
{
    public $idOrden, $idPersona;
    // datos de persona
    public $nombres, $p_apellido, $s_apellido, $ci, $matricula, $complemento, $nombreCompleto, $fecha_nacimiento, $edadPersona, $sexo, $codigoInstitucional;
    public $id_tipo_asegurado, $tipo_asegurado;
    public $foto;
    // datos de orden 
    public $nro_orden_servicio, $fecha_recepcion, $estudio, $today;
    public $id_orden_servicio_s, $id_persona_entregado, $nro_orden_servicio_anio;
    public $id_institucion_convenio, $institucion_convenio;
    public $id_especialidad, $especialidad;
    public $id_especialista, $especialista, $idPersonaEspecilista;
    public $institucion;
    // PARA ENTREGAR
    public $fecha_entregado, $observacion;
    public $idPerExt;
    public $estado;
    public $buscarCarnet;
    public $buscarComplemento;
    public $estado_asuss;
    public $busqueda;
    public $encontrado;
    public $personaId;
    public $ciExt, $complementoExt, $nombresExt, $p_apellidoExt, $s_apellidoExt, $carnetExt;
    //Para los usuarios 
    public $id_user_created, $created_at, $id_user_updated, $id_user_validado, $fecha_validacion, $id_user_anulado, $fecha_anulacion;
    public $nombreCreador, $nombreActualizar, $nombreValidar, $nombreAnular;
    public $creadoPor, $actualizadoPor, $validadoPor, $anuladoPor;
    public $estadoji;
    public $id_persona;
    public $estado_vigencia;
    public $fechaInicio, $fechaFin;
    public $esConvenioInteriorSwitch = false;
    public $esConvenioEmergenciaSwitch = false;
    // funciones
    protected $listeners = ['entregarOrdenServicio', 'guardarValidacionOrden', 'guardarAnulacionOrden'];

    public function render()
    {
        //$prueba = new DatosAgenda();
        //dd($prueba->extraerDatosAgenda(2));
        return view('livewire.Vigencia.ordenes-servicio.ordenes-servicio-main');
    }

    public function rules()
    {
        return [
            'fechaInicio' => ['required', 'date', 'before_or_equal:fechaFin'],
            'fechaFin' => ['required', 'date', 'after_or_equal:fechaInicio'],
        ];
    }

    public function messages()
    {
        return [
            'fechaInicio.required' => 'La fecha de inicio es obligatoria.',
            'fechaInicio.date' => 'La fecha de inicio debe ser una fecha válida.',
            'fechaInicio.before_or_equal' => 'La fecha de inicio no puede ser posterior a la fecha de fin.',

            'fechaFin.required' => 'La fecha de fin es obligatoria.',
            'fechaFin.date' => 'La fecha de fin debe ser una fecha válida.',
            'fechaFin.after_or_equal' => 'La fecha de fin no puede ser anterior a la fecha de inicio.',
        ];
    }

    public function mount()
    {
        //$migrarEsp = new MigrarEspecialistas();
        //$migrarEsp->migrarEspecialista();
        //$this->today = Carbon::now()->format('Y-m-d');
    }

    public function mostrarDatos($idOrden)
    {
        if ($this->idOrden) {
            $orden = OrdenServicio::find($idOrden);
            $this->nro_orden_servicio = $orden->nro_orden_servicio;
            $this->fecha_recepcion = Carbon::parse($orden->fecha_recepcion)->format('Y-m-d');
            $this->fecha_entregado = $orden->fecha_entregado;
            $this->observacion = $orden->observacion;
            $this->estado = $orden->estado;
            $this->estudio = $orden->estudio;
            $this->id_persona_entregado = $orden->id_persona_entregado;
            $personaEntregado = Persona::find($this->id_persona_entregado);
            if ($personaEntregado) {
                if ($personaEntregado->complemento == null) {
                    $this->ciExt = $personaEntregado->ci;
                } else {
                    $this->ciExt = $personaEntregado->ci . ' - ' . $personaEntregado->complemento;
                }
                $this->ciExt = $personaEntregado->ci;
                $this->complementoExt = $personaEntregado->complemento;
                if ($personaEntregado->p_apellido == null) {
                    $this->nombresExt = $personaEntregado->s_apellido . ' ' . $personaEntregado->nombres;
                } elseif ($personaEntregado->s_apellido == null) {
                    $this->nombresExt = $personaEntregado->p_apellido . ' ' . $personaEntregado->nombres;
                } else {
                    $this->nombresExt = $personaEntregado->p_apellido . ' ' . $personaEntregado->s_apellido . ' ' . $personaEntregado->nombres;
                }
                $this->p_apellidoExt = $personaEntregado->p_apellido;
                $this->s_apellidoExt = $personaEntregado->s_apellido;
            }

            $this->id_institucion_convenio = $orden->id_institucion_convenio;

            $institucionConvenio = InstitucionConvenio::find($this->id_institucion_convenio);
            if ($institucionConvenio) {
                $this->institucion_convenio = $institucionConvenio->nombre_institucion;
            }

            $this->id_especialidad = $orden->id_especialidad;

            $especialidadd = Especialidad::find($this->id_especialidad);
            if ($especialidadd) {
                $this->especialidad = $especialidadd->especialidad;
            }

            $this->id_especialista = $orden->id_especialista;
            $medico = Especialista::find($this->id_especialista);
            if ($medico) {
                $this->idPersonaEspecilista = $medico->id_persona;
                $medicoPersona = Persona::find($this->idPersonaEspecilista);
                $this->especialista = $medicoPersona->nombres . ' ' . $medicoPersona->s_apellido . ' ' . $medicoPersona->p_apellido;
            }


            $this->id_persona = $orden->id_persona;
            $afiliado = Persona::find($this->id_persona);
            $this->matricula = $afiliado->matricula_seguro;
            $this->ci = $afiliado->ci;
            $this->complemento = $afiliado->complemento;
            $this->nombres = $afiliado->nombres;
            $this->p_apellido = $afiliado->p_apellido;
            $this->s_apellido = $afiliado->s_apellido;
            $this->nombreCompleto = $afiliado->p_apellido . ' ' . $afiliado->s_apellido . ' ' . $afiliado->nombres;
            $tipoAsegurado = TipoAsegurado::find($afiliado->id_tipo_asegurado);
            $this->tipo_asegurado = $tipoAsegurado->tipo_asegurado;
            $this->fecha_nacimiento = $afiliado->fecha_nacimiento;
            $this->edadPersona = Carbon::parse($afiliado->fecha_nacimiento)->age;
            $this->sexo = $afiliado->sexo == 'F' ? 'FEMENINO' : 'MASCULINO';



            /*$esTitular = Titular::where('id_persona', $this->id_persona)->where('estado', true)->first();
            if ($esTitular) {
                $this->foto = $this->obtenerImagenBase64($esTitular->foto_nombre);
                $this->estado_vigencia = $esTitular->estado_vigencia;
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
                $esBeneficiario = Beneficiario::where('id_persona', $this->id_persona)->where('estado', true)->first();
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
            }*/

            $esTitular = Titular::where('id_persona', $this->id_persona)->where('estado_vigencia', 'VIGENTE')->first();
            if ($esTitular) {
                $this->foto = $this->obtenerImagenBase64($esTitular->foto_nombre);
                $this->estado_vigencia = $esTitular->estado_vigencia;
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
            }

            $esBeneficiario = Beneficiario::where('id_persona', $this->id_persona)->where('vigencia', 'VIGENTE')->first();
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

            $esConvenioInterior = AutorizacionInterior::where('id_persona_interior', $this->id_persona)->where('estado', true)
                ->with(['seguroInteriorAutorizacionInterior'])
                ->first();
            if ($esConvenioInterior) {
                $this->esConvenioInteriorSwitch = true;
                $this->foto = $this->obtenerImagenBase64Convenios($esConvenioInterior->foto_nombre);
                $this->estado_vigencia = $esConvenioInterior->estado ? 'VIGENTE' : 'NO VIGENTE';
                $this->institucion = $esConvenioInterior->seguroInteriorAutorizacionInterior->nombre_seguro;
                $this->codigoInstitucional = null;
            }

            $esConvenioEmergencia = Convenio::where('id_persona', $this->id_persona)->where('estado', true)
                ->with(['titularAutorizaConvenio.titularPersona'])
                ->first();
            if ($esConvenioEmergencia) {
                $this->esConvenioEmergenciaSwitch = true;
                $this->foto = $this->obtenerImagenBase64Convenios($esConvenioEmergencia->foto_nombre);
                $this->estado_vigencia = $esConvenioEmergencia->estado ? 'VIGENTE' : 'NO VIGENTE';
                $this->institucion = implode(' ', array_filter([
                    $esConvenioEmergencia->titularAutorizaConvenio->titularPersona->p_apellido,
                    $esConvenioEmergencia->titularAutorizaConvenio->titularPersona->s_apellido,
                    $esConvenioEmergencia->titularAutorizaConvenio->titularPersona->nombres,
                ]));
                $this->codigoInstitucional = null;
            }






            $this->id_user_created = $orden->id_user_created;
            $this->created_at = $orden->created_at;
            $this->id_user_updated = $orden->id_user_updated;
            $this->id_user_validado = $orden->id_user_validado;
            $this->fecha_validacion = $orden->fecha_validacion;
            $this->id_user_anulado = $orden->id_user_anulado;
            $this->fecha_anulacion = $orden->fecha_anulacion;
            // dd(  $this->id_user_created,$this->id_user_updated,$this->id_user_validado, $this->id_user_anulado);
            // PARA VER LOS USUARIOS 
            $this->creadoPor = User::find($this->id_user_created);
            $idPersonaCreado =  $this->creadoPor->id_persona;
            $personaCreador = Persona::find($idPersonaCreado);
            $this->nombreCreador = $personaCreador->p_apellido . ' ' . $personaCreador->s_apellido . ' ' . $personaCreador->nombres;

            $this->actualizadoPor = User::find($this->id_user_updated);
            if ($this->actualizadoPor) {
                $idPersonaActualizado = $this->actualizadoPor->id_persona;
                $personaActualizar = Persona::find($idPersonaActualizado);
                $this->nombreActualizar = $personaActualizar->p_apellido . ' ' . $personaActualizar->s_apellido . ' ' . $personaActualizar->nombres;
            } else {
                $this->nombreActualizar = '';
            }

            $this->validadoPor = User::find($this->id_user_validado);
            if ($this->validadoPor) {
                $idPersonaValidado = $this->validadoPor->id_persona;
                $personaValidar = Persona::find($idPersonaValidado);
                $this->nombreValidar = $personaValidar->p_apellido . ' ' . $personaValidar->s_apellido . ' ' . $personaValidar->nombres;
            } else {
                $this->nombreValidar = '';
            }

            $this->anuladoPor = User::find($this->id_user_anulado);
            if ($this->anuladoPor) {
                $idPersonaAnulado = $this->anuladoPor->id_persona;
                $personaAnular = Persona::find($idPersonaAnulado);
                $this->nombreAnular = $personaAnular->p_apellido . ' ' . $personaAnular->s_apellido . ' ' . $personaAnular->nombres;
            } else {
                $this->nombreAnular = '';
            }
            // dd( $this->creadoPor, $this->actualizadoPor, $this->validadoPor, $this->anuladoPor );
        }
    }
    // funciona para obtener imagen 
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

    private function obtenerImagenBase64Convenios($idFoto)
    {
        try {
            if ($idFoto) {
                $path = 'Vigencia/FOTOS/CONVENIO/' . $idFoto;

                $imageData = Storage::disk('ftp')->get($path);
                //dd($imageData);
                $this->foto = base64_encode($imageData);
                return $this->foto;
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            return null;
        }
    }
    //--------FUNCION PARA ENTREGAR ----
    // para buscar a la persona 
    public function buscarDatos()
    {
        if ($this->buscarComplemento == '') {
            $this->buscarComplemento = null;
        }

        $existePersona = Persona::where('ci', $this->buscarCarnet)->where('complemento', $this->buscarComplemento)->first();
        if ($existePersona) {
            $this->nombresExt = $existePersona->nombres;
            $this->p_apellidoExt = $existePersona->p_apellido;
            $this->s_apellidoExt = $existePersona->s_apellido;
            $this->complementoExt = $existePersona->complemento;
            $this->ciExt = $existePersona->ci;
            if ($existePersona->complemento == null) {
                $this->carnetExt = $existePersona->ci;
            } else {
                $this->carnetExt = $existePersona->ci . ' - ' . $existePersona->complemento;
            }
        } else {
            $validacionAsuss = new ValidarPersona();
            $datosPersona = $validacionAsuss->buscarPersona($this->buscarCarnet, $this->buscarComplemento);
            //dd($this->datosPersona);
            if (isset($datosPersona['datosPersonaEnFormatoJson'])) {
                $this->busqueda = true;
                $this->nombresExt = $datosPersona['datosPersonaEnFormatoJson']['Nombres'];
                $this->p_apellidoExt = $datosPersona['datosPersonaEnFormatoJson']['PrimerApellido'];
                $this->s_apellidoExt = $datosPersona['datosPersonaEnFormatoJson']['SegundoApellido'];
                $this->complementoExt = $datosPersona['datosPersonaEnFormatoJson']['complemento'];
                $this->ciExt = $datosPersona['datosPersonaEnFormatoJson']['numeroDocumento'];
                $this->estado_asuss = 1;
                $this->encontrado = 1;
            } else {
                $this->busqueda = false;
                $this->nombresExt = '';
                $this->p_apellidoExt = '';
                $this->s_apellidoExt = '';
                $this->ciExt = $this->buscarCarnet;
                $this->complementoExt = $this->buscarComplemento;
                $this->encontrado = 0;
            }
        }
    }

    // para entregar la orden de servicio 
    public function entregarOrdenServicio()
    {
        // para crear persona entregado
        $existePersona = Persona::where('ci', $this->ciExt)
            ->where('complemento', $this->complementoExt)
            ->first();
        if ($existePersona) {
            $this->personaId = $existePersona->id;
            $this->estadoji = 'existe';
        } else {
            if ($this->complementoExt == null) {
                $clave_unica = $this->ciExt;
            } else {
                $clave_unica = $this->ciExt . $this->complementoExt;
            }
            $this->estadoji = 'no existe';

            $personaEntre = Persona::create([
                'ci' => $this->ciExt,
                'complemento' => $this->complementoExt,
                'nombres' => $this->nombresExt,
                'p_apellido' => $this->p_apellidoExt,
                's_apellido' => $this->s_apellidoExt,
                'estado_asuss' => $this->estado_asuss,
                'clave_unica' => $clave_unica,
                'id_user_created' => auth()->user()->id,
            ]);
            $this->personaId = $personaEntre->id;
        }
        //  dd($existePersona, );
        $ordenServicio = OrdenServicio::find($this->idOrden);
        if ($ordenServicio) {
            $ordenServicio->observacion = $this->observacion;
            $ordenServicio->estado = 'HOMOLOGADO';
            $ordenServicio->fecha_entregado = Carbon::now();
            $ordenServicio->id_user_updated = auth()->user()->id;
            $ordenServicio->id_persona_entregado = $this->personaId;
            $ordenServicio->save();
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
                        'asunto' => 'VIGENCIA DE DERECHOS - ORDEN DE SERVICIO HOMÓLOGADO',
                        'mensaje' => 'La orden de servicio se HOMÓLOGO para el Asegurado: ' . $this->nombreCompleto .
                            ', por la Unidad de Vigencia de Derechos, registrado con el Código: ' . $this->nro_orden_servicio . '. En fecha, ' . Carbon::now()->toDateString() . ' .',
                    ]);
                }
            }


            $this->dispatch('pg:eventRefresh-OrdenservicioTable');
            $this->dispatch('ordenModificado');
        } else {
            $this->reset(
                'buscarCarnet',
                'buscarComplemento',
                'nombresExt',
                'p_apellidoExt',
                's_apellidoExt',
                'ciExt',
                'complementoExt',
                'observacion'
            );
        }
    }

    // escuchar ordenes
    // pra ver
    #[On('verOrden')]
    public function verOrden($data)
    {
        $this->idOrden = $data;
        $this->mostrarDatos($this->idOrden);
        $this->dispatch('modalVerOrdenServicio');
    }
    // para entregar
    #[On('entregarOrden')]
    public function entregarOrden($data)
    {
        $this->ciExt = null;
        $this->complementoExt = null;
        $this->nombresExt  = null;
        $this->p_apellidoExt  = null;
        $this->s_apellidoExt  = null;
        $this->idOrden = $data;
        $this->today = Carbon::now()->format('Y-m-d');
        $this->mostrarDatos($data);
        $this->dispatch('modalEntregarOrden');
    }
    // para validar
    #[On('validarOrden')]
    public function validarOrden($data)
    {
        $this->idOrden = $data;
        $this->dispatch('alertaValidarOrden');
    }
    public function guardarValidacionOrden()
    {
        try {
            $orden = OrdenServicio::find($this->idOrden);

            if ($orden) {
                $orden->estado = 'VALIDADO';
                $orden->fecha_validacion = Carbon::now();
                $orden->id_user_validado = auth()->user()->id;
                $orden->save();

                $this->dispatch('pg:eventRefresh-OrdenservicioTable');
                $this->dispatch('guardarOrden');
            }
        } catch (\Throwable $th) {
            $this->dispatch('noValidadoOrden');
        }
    }
    // para anularBajaMedica
    #[On('anularOrden')]
    public function anularOrden($data)
    {
        $this->idOrden = $data;
        $this->dispatch('alertaAnularOrden');
    }
    public function guardarAnulacionOrden($data)
    {
        //dd($data);
        // try {
        $orden = OrdenServicio::find($this->idOrden);

        if ($orden) {
            $orden->estado = 'ANULADO';
            $orden->fecha_anulacion = Carbon::now();
            $orden->observacion = $data;
            $orden->id_user_anulado = auth()->user()->id;
            $orden->save();

            $this->dispatch('pg:eventRefresh-OrdenservicioTable');
            $this->dispatch('guardarOrden');
        }
        //  } catch (\Throwable $th) {
        //      $this->dispatch('noValidadoOrden');
        //  }
    }

    public function generarPDF()
    {
        if ($this->fechaInicio != null && $this->fechaFin != null) {
            $this->js('window.open("/vigencia/OrdenesServicio/' . $this->fechaInicio . '/' . $this->fechaFin . '")');
        } else {
            $this->dispatch('completeDatos');
            $this->fechaInicio = null;
            $this->fechaFin = null;
        }
        $this->skipRender();
    }
}
