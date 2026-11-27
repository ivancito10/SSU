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
use App\Models\Vigencia\HCarnetInterior;
use App\Services\RegistroBajasMedicas;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

use App\Models\Administracion\RolUsuario;
use App\Models\Administracion\NotificacionUsuarios;
use App\Models\Administracion\Role;
use App\Models\Vigencia\Convenio;
use App\Models\Vigencia\HistorialAsegurado;
use App\Services\ConveniosGehma2;
use App\Services\Migracion\Vigencia\MigrarConvenioInterior;
use App\Services\VerificarDarBaja;

class ConvenioInteriorMain extends Component
{
    #[Locked]
    public $idAut;

    protected $listeners = ['guardarValidacion', 'guardarAnulacion'];
    public $especialidades, $servicios, $personaVal, $personaAnulado, $usuarioNombre;

    #[Locked]
    //DATOS DE LA AUTORIZACIONs
    public $cod_interior,
        $codInte,
        $fecha_inicio,
        $fecha_fin,
        $id_seguro_interior,
        $nombre_seguro,
        $otras_limitaciones,
        $id_persona_interior,
        $id_persona_titular,
        $id_personal_ssu,
        $foto_nombre,
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
        $edadPersona,
        $p_apellido,
        $s_apellido,
        $nombreCompleto,
        $complemento,
        $matricula_seguro,
        $fecha_nacimiento,
        $sexo,
        $id_tipo_asegurado,
        $tipo_asegurado,
        $observacion,
        $observacion_ai,
        $limitaciones_ai;
    public $fecha_emision_r, $fecha_vencimiento_r, $fecha_emision, $fecha_vencimiento;
    public $fechaInicio, $fechaFin;
    public $diasVigencia;


    public $direccion_referencia;
    public $telefono_referencia;
    public $datos_referencia;
    public $telefono;
    public $grupo_sanguineo;
    public $alergias;

    public function render()
    {
        //$regis = new RegistroBajasMedicas();
        //$regis->registrarDatos();
        //dd($r); 
        return view('livewire.Vigencia.convenios.interior.convenio-interior-main');
    }

    public function mount()
    {
        //$migrarDatitos = new MigrarConvenioInterior();
        //$migrarDatitos->migrarConvenioInterior(); 

        $bajas = new VerificarDarBaja();
        $bajas->verificarDarBaja();
        $bajas->verificarConvenios();
        $bajas->bajaMayores25();
        $bajas->bajaMayores19();
    }

    public function redirectToForm()
    {

        return redirect()->route('vigencia.convenios.interior.create');
    }

    public function mostrarAutorizacion($idAutorizacion)
    {
        if ($idAutorizacion) {
            $autorizacion = AutorizacionInterior::find($idAutorizacion);
            $this->foto_nombre = $this->obtenerImagenBase64($autorizacion->foto_nombre);
            $this->cod_interior = $autorizacion->cod_interior;
            $this->codInte = $this->cod_interior . '/' . Carbon::parse($autorizacion->created_at)->format('Y');
            $this->fecha_inicio = $autorizacion->fecha_inicio;
            $this->fecha_fin = $autorizacion->fecha_fin;
            $this->diasVigencia = Carbon::parse($autorizacion->fecha_fin)->diffInDays(Carbon::parse($autorizacion->fecha_inicio)) + 1;
            $this->medicamentos_liname = $autorizacion->medicamentos_liname;
            $this->medicamentos_extraliname = $autorizacion->medicamentos_extraliname;
            $this->observacion_ai = $autorizacion->observacion;
            $this->limitaciones_ai = $autorizacion->otras_limitaciones;

            $seguroInterior = SeguroInterior::find($autorizacion->id_seguro_interior);
            $this->nombre_seguro = $seguroInterior->nombre_seguro;
            $this->otras_limitaciones = $autorizacion->otras_limitaciones;
            //DATOS DE LA PERSONA SOLICITANTE
            $personaInterior = Persona::find($autorizacion->id_persona_interior);
            if ($personaInterior->complemento == null) {
                $this->ci = $personaInterior->ci;
            } else {
                $this->ci = $personaInterior->ci . ' - ' . $personaInterior->complemento;
            }
            $this->complemento = $personaInterior->complemento;
            $this->nombres = $personaInterior->nombres;
            $this->p_apellido = $personaInterior->p_apellido;
            $this->s_apellido = $personaInterior->s_apellido;
            $this->matricula_seguro = $personaInterior->matricula_seguro;
            $this->fecha_nacimiento = $personaInterior->fecha_nacimiento;

            $this->grupo_sanguineo = $personaInterior->grupo_sanguineo;
            $this->alergias = $personaInterior->alergias;

            $this->edadPersona = Carbon::parse($personaInterior->fecha_nacimiento)->age;
            if ($personaInterior->sexo == 'F') {
                $this->sexo = 'FEMENINO';
            } else {
                $this->sexo = 'MASCULINO';
            }
            if ($personaInterior->p_apellido == null) {
                $this->nombreCompleto = $personaInterior->s_apellido . ' ' . $personaInterior->nombres;
            } elseif ($personaInterior->s_apellido == null) {
                $this->nombreCompleto = $personaInterior->p_apellido . ' ' . $personaInterior->nombres;
            } else {
                $this->nombreCompleto = $personaInterior->p_apellido . ' ' . $personaInterior->s_apellido . ' ' . $personaInterior->nombres;
            }
            $this->nombreCompleto = $this->p_apellido . ' ' . $this->s_apellido . ' ' . $this->nombres;
            $tipoAsegurado = TipoAsegurado::find($personaInterior->id_tipo_asegurado);
            $this->tipo_asegurado = $tipoAsegurado->tipo_asegurado;
            //SI TIENE TITULAR, DATOS DEL TITULAR
            $personaTitularInterior = Persona::find($autorizacion->id_persona_titular);
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

            $this->direccion_referencia = $autorizacion->direccion_referencia;
            $this->telefono_referencia = $autorizacion->telefono_referencia;
            $this->datos_referencia = $autorizacion->datos_referencia;
            $this->telefono = $autorizacion->telefono;

            //PARA LAS ESPECIALIDADES Y SERVICIOS
            $esp = AutorizacionIntEspecialidad::join('administracion.especialidad', 'autorizacionint_especialidad.id_especialidad', '=', 'especialidad.id')
                ->where('id_autorizacion_interior', $autorizacion->id)
                ->get();
            if (isset($esp->toArray()[0])) {
                $this->especialidades = $esp;
            } else {
                $this->especialidades = null;
            }

            //dd($this->especialidades);
            $serv = AutorizacionIntServicio::join('administracion.servicio', 'autorizacionint_servicio.id_servicio', '=', 'servicio.id')
                ->where('id_autorizacion_interior', $autorizacion->id)
                ->get();

            if (isset($serv->toArray()[0])) {
                $this->servicios = $serv;
            } else {
                $this->servicios = null;
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


    #[On('verAutorizacion')]
    public function verAutorizacion($data)
    {
        $this->idAut = $data;
        $this->mostrarAutorizacion($this->idAut);
        $this->dispatch('modalVerAut');
    }

    #[On('validarAutorizacion')]
    public function validarAutorizacion($data)
    {
        $this->idAut = $data;
        $this->mostrarAutorizacion($data);
        $this->dispatch('alertaValidar');
    }

    #[On('anularAutorizacion')]
    public function anularAutorizacion($data)
    {
        $this->idAut = $data;
        $this->dispatch('alertaAnular');
    }

    #[On('editarAutorizacion')]
    public function editarAutorizacion($data)
    {
        $this->idAut = $data;
        $this->mostrarSolicitud($this->idAut);
        $this->dispatch('editarSolicitudModal');
    }

    public function guardarValidacion()
    {
        //dd($this->idBaja);
        try {
            $autorizacion = AutorizacionInterior::find($this->idAut);

            if ($autorizacion) {
                $autorizacion->estado_validacion = 'VALIDADO';
                $autorizacion->fecha_validacion = Carbon::now();
                $autorizacion->id_user_validado = auth()->user()->id;
                $autorizacion->save();

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
                            'asunto' => 'VIGENCIA DE DERECHOS - CONVENIO DEL INTERIOR VALIDADO',
                            'mensaje' => 'El convenio del interior ha sido VALIDADO para el Asegurado: ' . $this->nombreCompleto .
                                ', cuyo seguro de origen es; ' . $this->nombre_seguro . '. En fecha, ' . Carbon::now()->toDateString() . ' .',
                        ]);
                    }
                }
                $this->dispatch('pg:eventRefresh-AutorizacionesTable');
                $this->dispatch('guardado');
            }
        } catch (\Throwable $th) {
            $this->dispatch('noValidado');
        }
    }

    public function guardarAnulacion($data)
    {
        //dd($data);
        try {
            $autorizacion = AutorizacionInterior::with(['personaInteriorAutorizacionInterior'])->find($this->idAut);

            if ($autorizacion) {
                $autorizacion->estado = false;
                $autorizacion->estado_validacion = 'ANULADO';
                $autorizacion->motivo_anulacion = $data;
                $autorizacion->id_user_anulado = auth()->user()->id;
                $autorizacion->fecha_anulacion = Carbon::now();
                $autorizacion->save();

                /*PARA LA TABLA HISTORIAL ASEGURADO Y PARA LA TABLA PERSONA*/

                $idPersona = $autorizacion->id_persona_interior;

                $historialAsegurado = HistorialAsegurado::where('id_persona', $idPersona)
                    ->where('estado', true)
                    ->where('id_corresponde', $autorizacion->id)
                    ->orderBy('id', 'desc')
                    ->first();

                if ($historialAsegurado) {
                    $historialAsegurado->estado_vigencia = 'NO VIGENTE';
                    if ($historialAsegurado->fecha_baja == null) {
                        $historialAsegurado->fecha_baja = Carbon::now();
                    }
                    $historialAsegurado->id_user_updated = auth()->user()->id;
                    $historialAsegurado->save();
                }

                $aunTieneConvenio = AutorizacionInterior::where('id_persona_interior', $idPersona)
                    ->where('fecha_fin', '>=', Carbon::now()->format('Y-m-d'))
                    ->where('estado', true)
                    ->first();

                $aunTieneConvenioEmergencia = Convenio::where('id_persona', $idPersona)
                    ->where('fecha_fin', '>=', Carbon::now()->format('Y-m-d'))
                    ->where('estado', true)
                    ->first();

                if ($aunTieneConvenio == null && $aunTieneConvenioEmergencia == null) {
                    $personaInterior = Persona::find($idPersona);
                    $personaInterior->afiliado = false;
                    $personaInterior->id_tipo_asegurado = 0;
                    $personaInterior->id_user_updated = auth()->user()->id;
                    $personaInterior->save();
                }

                /*EL FIN JAJAJA*/

                $this->dispatch('pg:eventRefresh-AutorizacionesTable');
                $this->dispatch('guardado');
            }
        } catch (\Throwable $th) {
            $this->dispatch('noValidado');
        }
    }


    public function recarnetizacion()
    {
        $this->js('window.open("carnetInteriorPdf/' . $this->idAut . '", "_blank")');
        $this->dispatch('RegistroCarnetCorrecto');
    }
    // Para el carnet 
    #[On('carnetizacion')]
    public function carnetizacion($data)
    {
        // dd($data);
        $foto_afiliado_verificar = $this->verificarFotoConvenioInterior($data);

        if ($foto_afiliado_verificar != null) {
            $this->mostrarAutorizacion($data);
            $carnet = HCarnetInterior::where('id_autorizacion_interior', $this->idAut)->orderby('id', 'desc')->first();
            //$fecha_inicio,
            //$fecha_fin,
            $this->fecha_emision = null;
            $this->fecha_vencimiento = null;
            $this->fecha_emision_r = $this->fecha_inicio;
            $this->fecha_vencimiento_r = $this->fecha_fin;
            if ($carnet) {
                $this->fecha_emision = $carnet->fecha_emision;
                $this->fecha_vencimiento = $carnet->fecha_vencimiento;
            }

            $this->idAut = $data;
            // dd($this->idAut);
            $this->dispatch('modalCarnetizacionConvenioInterior');
        } else {
            $this->dispatch('sinFotoAfiliado');
        }
        // $this->js('window.open("carnetInteriorPdf/'.$data . '", "_blank")');
    }
    public function verificarFotoConvenioInterior($data)
    {
        // dd($this->foto_nombre);
        $id_conv_int = $data;
        $conv_interior = AutorizacionInterior::where('id', $id_conv_int)->get();
        // dd($conv_interior[0]->foto_nombre);
        $foto_afiliado = null;
        if ($conv_interior) {
            $nombre_foto_afiliado = $conv_interior[0]->foto_nombre;
            $foto_afiliado = $this->obtenerImagenBase64($nombre_foto_afiliado);
        }
        return $foto_afiliado;
    }
    public function crear_h_carnet()
    {
        // dd('entre');
        // dd($this->idPersona);
        $r_carnet = HCarnetInterior::create([

            'fecha_emision' => $this->fecha_emision_r,
            'fecha_vencimiento' => $this->fecha_vencimiento_r,
            'id_autorizacion_interior' => $this->idAut,
            // 'id_convenio'=>$this->id_convenio,
            // 'id_persona'=>$this->id_persona_interior,
            'id_user_created' => auth()->user()->id,
        ]);

        $this->js('window.open("carnetInteriorPdf/' . $this->idAut . '", "_blank")');
        $this->dispatch('RegistroCarnetCorrecto');
    }
    #[On('convenioPdf')]
    public function convenioPdf($data)
    {
        $this->js('window.open("convenioPdf/' . $data . '", "_blank")');
    }

    public function generarPDF()
    {
        if ($this->fechaInicio != null && $this->fechaFin != null) {
            $this->js('window.open("/vigencia/ConvenioInterior/' . $this->fechaInicio . '/' . $this->fechaFin . '")');
        } else {
            $this->dispatch('completeDatos');
            $this->fechaInicio = null;
            $this->fechaFin = null;
        }
        $this->skipRender();
    }
}
