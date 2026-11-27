<?php

namespace App\Livewire\Aportes\Institucion;

use Livewire\Component;
use App\Models\Aportes\Institucion;
use App\Models\Aportes\AporteInstitucional;
use App\Models\Aportes\Mes;
use Livewire\Attributes\On;
use App\Models\Administracion\Zona;
use App\Models\Administracion\Persona;
use App\Models\Administracion\Residencia;
use App\Models\Administracion\NotificacionUsuarios;
use App\Models\Aportes\ClaseAportante;
use App\Services\ValidarPersona;
use Carbon\Carbon;
use App\Services\ConexionAsuss;
use App\Services\TitularesGehma2;
use App\Services\BeneficiariosGehma2;
use Illuminate\Support\Facades\Auth;
use App\Mail\Aportes\ActualizacionRepresentanteLegalMailable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Validate;

use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class InstitucionMain extends Component
{
    use WithFileUploads;
    #[Validate]
    public $file_digital_nit;
    public $file_digital_ci;


    public $instId;
    public $modal;
    public $institucion;
    public $institucionId;
    //Variables mostrar y editar
    public $direccion;
    public $numero;
    public $zona;
    public $zonaId;
    public $correo;
    public $telefono;
    public $estado;
    public $tgn;

    public $nombre;
    public $acronimo;
    public $num_empleador;
    public $nit;
    public $tipoclaseaportante;
    public $id_inst;
    public $publica;

    public $nombres;
    public $p_apellido;
    public $s_apellido;
    public $ci;
    public $fecha_nacimiento;
    public $complemento;
    public $nom_completo;

    public $estado_resp = 3;
    public $complemento_b;
    public $ci_b;

    public $latitud;
    public $longitud;
    public $latitudRegistered;
    public $longitudRegistered;
    public $ubicacion_select = 0;
    public $ubicacionTexto = 'NO SELECCIONADO';
    public $d_latitud, $d_longitud;
    public $actividad_empleador, $tipo_comercio, $fecha_afiliacion, $tipo_institucion,
        $direccion_referencia, $telefono_dos, $fecha_nacimiento_m;
    public $estado_asuss;
    public $mensaje;

    protected $listeners = ['actualizar', 'cambiarEstado', 'recibirLonLat', 'asignarUsuario', 'editar_rep'];

    public function redirectToForm()
    {
        return redirect()->route('aportes.institucion.register');
    }

    public function render()
    {
        $zonasSelect = Zona::all();

        return view('livewire.Aportes.institucion.institucion-main', ['zonasSelect' => $zonasSelect]);
    }

    //SOLO PARA PROBAR LA INSERCION EN EL GEHMA 1
    public function mount()
    {
        //dd(Institucion::all());
        //$ins = new TitularesGehma2();
        //$insertar = $ins->insercionTitularGehma2(83);
        //dd($insertar);

        //$ins = new BeneficiariosGehma2();
        //$insertar = $ins->insercionBeneficiarioGehma2(66);
        //dd($insertar);


    }

    //Logica para mostrar una sola institucion por ID
    public function mostrarInstitucion($instId)
    {
        if ($instId) {
            $institucion = Institucion::find($instId);
            $residenciaActual = Residencia::find($institucion->id_residencia);
            $this->direccion = $residenciaActual->direccion;
            $this->numero = $residenciaActual->nro_vivienda;
            $this->d_latitud = $residenciaActual->latitud;
            $this->d_longitud = $residenciaActual->longitud;
            /*  $zonaActual = Zona::find($institucion->id_zona);*/
            $zonaActual = Zona::find($residenciaActual->id_zona);
            $this->zona = $zonaActual->nombre;
            $this->zonaId = $zonaActual->id;

            $this->correo = $institucion->correo;
            $this->telefono = $institucion->telefono;
            $this->estado = $institucion->estado;
            $this->tgn = $institucion->tgn;
            $this->publica = $institucion->publica;
            $id_usuario_responsable = $institucion->id_usuario_responsable;
            $id_usuario_aviso_baja = $institucion->id_usuario_aviso_baja;

            $this->nombre = $institucion->nombre;
            $this->acronimo = $institucion->acronimo;
            $this->num_empleador = $institucion->nro_empleador;
            $this->nit = $institucion->nit;
            $this->actividad_empleador = $institucion->actividad_empleador;
            $this->tipo_comercio = $institucion->codigo_comercio;
            $this->fecha_afiliacion = Carbon::parse($institucion->fecha_afiliacion)->format('d/m/Y');
            $this->tipo_institucion = $institucion->tipo_empresa_institucion;
            $this->direccion_referencia = $institucion->referencia_direccion;
            $this->telefono_dos = $institucion->telefono_dos;

            $tipoAportante = ClaseAportante::find($institucion->id_clase_aportante);
            $this->tipoclaseaportante = $tipoAportante->tipo;
            $this->id_inst = $institucion->id;

            if ($id_usuario_responsable != null) {
                $usuario = User::find($id_usuario_responsable);
                $id_persona_usuario = $usuario->id_persona;
                $this->usuarioResponsable = $usuario->email;
                $usuario_responsable = Persona::find($id_persona_usuario);
                $this->nombreCompletoResponsable = $usuario_responsable->nombres . ' ' . $usuario_responsable->p_apellido . ' ' . $usuario_responsable->s_apellido;
                $this->carnetResponsable = $usuario_responsable->ci;
            }

            if ($id_usuario_aviso_baja != null) {
                $usuario_baja = User::find($id_usuario_aviso_baja);
                $id_persona_usuario = $usuario_baja->id_persona;
                $this->usuarioAsignadoBaja = $usuario_baja->email;
                $usuario_aviso_baja = Persona::find($id_persona_usuario);
                $this->nombreCompletoAsignadoBaja = $usuario_aviso_baja->nombres . ' ' . $usuario_aviso_baja->p_apellido . ' ' . $usuario_aviso_baja->s_apellido;
                $this->carnetAsignadoBaja = $usuario_aviso_baja->ci;
            }



            $representante = Persona::find($institucion->id_persona);
            $this->nombres = $representante->nombres;
            $this->p_apellido = $representante->p_apellido;
            $this->s_apellido = $representante->s_apellido;
            $this->ci = $representante->ci;
            $this->fecha_nacimiento = $representante->fecha_nacimiento;
            $this->fecha_nacimiento_m = Carbon::parse($representante->fecha_nacimiento)->format('d/m/Y');
            $this->complemento = $representante->complemento;
            $this->nom_completo =  $this->nombres . ' ' . $this->p_apellido . ' ' . $this->s_apellido;


            return $institucion;
        }
    }
    //#[On('BuscarPersonaRep')] 
    public function nuevoRep()
    {

        if ($this->complemento_b == '') {
            $this->complemento_b = null;
        }
        try {
            $existePersona = Persona::where('ci', $this->ci_b)
                ->where('complemento', $this->complemento_b)
                ->orderBy('id', 'desc')
                ->first();

            if ($existePersona != null) {
                $this->ci = $existePersona->ci;
                $this->complemento = $existePersona->complemento;
                $this->p_apellido = $existePersona->p_apellido;
                $this->s_apellido = $existePersona->s_apellido;
                $this->nombres = $existePersona->nombres;
                $this->fecha_nacimiento = $existePersona->fecha_nacimiento;
                //$this->fecha_nacimiento = Carbon::parse($existePersona->fecha_nacimiento)->translatedFormat('d \d\e F \d\e Y'); //Probar esto
                $this->estado_asuss = $existePersona->estado_asuss;
                $this->estado_resp = 1;
                $estadoBusqueda = 2;

                // Suponiendo que $this->existePersona->fecha_nacimiento es una fecha válida
                $fechaNacimiento = Carbon::parse($this->fecha_nacimiento);
                // Calcula la edad
                $edad = $fechaNacimiento->age;
                if ($edad < 18) {
                    $this->dispatch('menorEdad');
                }
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
                    $this->estado_resp = 1;

                    // Suponiendo que $this->existePersona->fecha_nacimiento es una fecha válida
                    $fechaNacimiento = Carbon::parse($this->fecha_nacimiento);
                    // Calcula la edad
                    $edad = $fechaNacimiento->age;
                    if ($edad < 18) {
                        $this->dispatch('menorEdad');
                    }
                } else {
                    $this->estado_resp = 0;
                    $this->nombres = '';
                    $this->p_apellido = '';
                    $this->s_apellido = '';
                    $this->fecha_nacimiento = '';
                    $this->ci = $this->ci_b;
                    $this->complemento = $this->complemento_b;
                    $this->mensaje = 'No se encontró a la persona';
                }
            }
        } catch (\Throwable $th) {
            $this->dispatch('noExisteUsuario');
        }

        $this->skipRender();
    }

    // Logica para recibir el id de la institucion desde la tabla
    #[On('edicion')]
    public function recibirInstitucion($data)
    {
        $this->instId = $data['institucionId'];
        $this->modal = $data['modalEstado'];
        $this->dispatch('openModal');
    }

    #[On('habilitar')]
    public function habilitar($rowId)
    {
        $this->modal = false;
        try {
            $institucion = Institucion::find($rowId);
            if ($institucion) {
                $estadoActual = $institucion->estado;

                $institucion->estado = !$estadoActual;
                $institucion->save();
                $this->dispatch('pg:eventRefresh-InstitucionesRegistro');
                $this->dispatch('InstitucionHabilitada');

                // NOTIFICACION 
                $nuevaNotificacion = NotificacionUsuarios::create([
                    'estado' => 'NO LEIDO',
                    'prioridades' => 2,
                    'id_usu_emisor' => auth()->user()->id,
                    'id_usu_receptor' => $institucion->id_usuario_responsable,
                    'fecha_hora_enviada' => Carbon::now(),
                    'asunto' => 'APORTES - INSTITUCIÓN HABILITADA',
                    'mensaje' => ' La institución : ' . $institucion->nombre . ' fue habilitada.
                    Bienvenido al Seguro Social Universitario. ',
                ]);
            }
        } catch (\Throwable $th) {
            $this->dispatch('noHayUsuario');
        }
    }

    #[On('deshabilitar')]
    public function deshabilitar($rowId)
    {
        $this->modal = false;
        try {
            $institucion = Institucion::find($rowId);
            if ($institucion) {
                $estadoActual = $institucion->estado;
                $institucion->estado = !$estadoActual;
                $institucion->save();
                $this->dispatch('pg:eventRefresh-InstitucionesRegistro');
                $this->dispatch('InstitucionDeshabilitada');
                // NOTIFICACION 
                $nuevaNotificacion = NotificacionUsuarios::create([
                    'estado' => 'NO LEIDO',
                    'prioridades' => 2,
                    'id_usu_emisor' => auth()->user()->id,
                    'id_usu_receptor' => $institucion->id_usuario_responsable,
                    'fecha_hora_enviada' => Carbon::now(),
                    'asunto' => 'APORTES - INSTITUCIÓN HABILITADA',
                    'mensaje' => ' La institución : ' . $institucion->nombre . ' fue deshabilitada. 
                    Atte. El administrador',
                ]);
            }
        } catch (\Throwable $th) {
            $this->dispatch('noHayUsuario');
        }
    }

    #[On('ValidaInstitucionConfirmacion')]
    public function ValidaInstitucionConfirmacion($data)
    {
        $this->instId = $data;
        $this->dispatch('ConfirmacionValidacionInstitucion');
    }

    #[On('ValidarRegistroInstitucion')]
    public function ValidarRegistroInstitucion()
    {
        try {
            $institucion = Institucion::find($this->instId);
            if ($institucion) {

                $institucion->estado_validado = 1;
                $institucion->estado = 1;
                $institucion->save();
                $this->dispatch('pg:eventRefresh-InstitucionesRegistro');
                $this->dispatch('InstitucionValidada');

                // para envio de notificaciones
                $nuevaNotificacion = NotificacionUsuarios::create([
                    'estado' => 'NO LEIDO',
                    'prioridades' => 1,
                    'id_usu_emisor' => auth()->user()->id,
                    'id_usu_receptor' => $institucion->id_usuario_responsable,
                    'fecha_hora_enviada' => Carbon::now(),
                    'asunto' => 'APORTES - INSTITUCIÓN VALIDADA',
                    'mensaje' => ' La institución : ' . $institucion->nombre . ' fue validada. 
                Bienvenido al Seguro Social Universitario. Atte. El administrador'
                ]);
            }
        } catch (\Throwable $th) {
            $this->dispatch('noHayUsuario');
        }
    }

    // editarInstitucion
    #[On('editarInstitucion')]
    public function editarInstitucion($data)
    {
        $this->instId = $data;
        $this->mostrarInstitucion($this->instId);
        //dd($this->mostrarInstitucion($this->instId));
        $this->dispatch('modalEditarInstitucion');
    }
    // para la ubicacion
    public function recibirLonLat($d_lat, $d_lng)
    {

        $this->latitudRegistered = $d_lat;
        $this->longitudRegistered = $d_lng;
        $this->ubicacion_select = 1;
        $this->ubicacionTexto = 'SELECCIONADO';
    }
    // actualizar institucion
    public function actualizar()
    {
        $this->validate([
            'correo' => 'email|required',
        ]);

        try {
            $institucion = Institucion::find($this->id_inst);
            if ($institucion) {

                $institucion->correo = $this->correo;
                $institucion->telefono = $this->telefono;
                $institucion->estado = $this->estado;
                $institucion->tgn = $this->tgn;

                $residenciaId = $institucion->id_residencia;

                $nueva_residencia = Residencia::find($residenciaId);
                if ($nueva_residencia) {
                    $nueva_residencia->direccion = $this->direccion;
                    $nueva_residencia->nro_vivienda = $this->numero;
                    $nueva_residencia->id_zona = $this->zonaId;
                    $nueva_residencia->latitud = $this->latitudRegistered;
                    $nueva_residencia->longitud = $this->longitudRegistered;
                    $nueva_residencia->id_user_updated = auth()->user()->id;

                    $nueva_residencia->save();
                }


                $institucion->save();
                $this->dispatch('institucionModificada');
            }
            $this->modal = false;
        } catch (\Throwable $th) {
            $this->modal = false;
            $this->dispatch('error');
        }
    }

    // editarRepresentante
    #[On('editarRepresentante')]
    public function editarRepresentante($data)
    {
        $this->instId = $data;
        $this->mostrarInstitucion($this->instId);
        $this->dispatch('modalEditarRepresentante');
    }
    #[On('GuardarPerRep')]
    public function editar_rep()
    {
        DB::beginTransaction();
        try {
            $persona = Persona::where('ci', '=', $this->ci)->first();

            if ($persona == null) {

                if ($this->complemento == null) {
                    $clave_unica = $this->ci;
                } else {
                    $clave_unica = $this->ci . $this->complemento;
                }

                $persona = Persona::create([
                    'p_apellido' => $this->p_apellido,
                    's_apellido' => $this->s_apellido,
                    'nombres' => $this->nombres,
                    'ci' => $this->ci,
                    'complemento' => $this->complemento,
                    'fecha_nacimiento' => $this->fecha_nacimiento,
                    'clave_unica' => $clave_unica,
                    'es_extranjero' => false,
                    'id_user_created' => auth()->user()->id,
                ]);
            }
            $edit_rep = Persona::find($persona->id);
            $institucion = Institucion::find($this->instId);
            if ($institucion) {
                $institucion->id_persona = $edit_rep->id;
                $institucion->save();

                $this->dispatch('RepresenanteModificada');
                $this->dispatch('pg:eventRefresh-InstitucionesRegistro');
            }
            $this->modal = false;
            // mail
            $asunto = 'Sistema Sighov2 - Unidad de Aportes';
            $email = $institucion->correo;
            $correo = new ActualizacionRepresentanteLegalMailable($asunto, $institucion, $edit_rep);
            try {
                Mail::to($email)->send($correo);
            } catch (\Exception $e) {
                // Log del error (opcional)
                Log::error('No se pudo enviar el correo: ' . $e->getMessage());
            }
            //
            // parte de la notificacion
            if ($institucion->id_usuario_responsable != null) {
                $nuevaNotificacion = NotificacionUsuarios::create([
                    'estado' => 'NO LEIDO',
                    'prioridades' => 1,
                    'id_usu_emisor' => auth()->user()->id,
                    'id_usu_receptor' => $institucion->id_usuario_responsable,
                    'fecha_hora_enviada' => Carbon::now(),
                    'asunto' => 'APORTES - EDICIÓN REPRESENTANTE LEGAL',
                    'mensaje' => ' Se editó el representante legal para la institución: ' . $institucion->nombre,
                ]);
            }


            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('error');
        }
    }

    // abrirInstitucion
    #[On('abrirInstitucion')]
    public function abrirInstitucion($data)
    {
        $this->instId = $data;
        $this->mostrarInstitucion($this->instId);
        $this->mostrar_pdf_nit($this->instId);
        $this->mostrar_pdf_ci($this->instId);
        $this->dispatch('modalAbrirInstitucion');
    }
    // editarUsuarioEncargado
    #[On('editarUsuarioEncargado')]
    public function editarUsuarioEncargado($data)
    {
        $this->instId = $data;
        $this->usuarioResponsable = "";
        $this->nombreCompletoResponsable = "";
        $this->carnetResponsable = "";
        $this->complementoUsuarioBusqueda = "";
        $this->ciUsuarioBusqueda = "";
        $this->mostrarInstitucion($this->instId);
        $this->dispatch('modalEditarUsuarioEncargado');
    }

    public $ciUsuarioBusqueda, $complementoUsuarioBusqueda,
        $idUsuarioSeleccionado, $nombreCompletoResponsable, $usuarioResponsable, $carnetResponsable;
    public function searchPersonaUsuario()
    {
        if ($this->complementoUsuarioBusqueda == '') {
            $this->complementoUsuarioBusqueda = null;
        }
        try {
            $buscarUsuario = User::with(['personaUser'])
                ->whereHas('personaUser', function ($personaUsuario) {
                    $personaUsuario->where('ci', $this->ciUsuarioBusqueda)
                        ->where('complemento', $this->complementoUsuarioBusqueda);
                })
                ->where('estado', true)
                ->first();

            if ($buscarUsuario) {
                $this->idUsuarioSeleccionado = $buscarUsuario->id;
                $this->usuarioResponsable = $buscarUsuario->email;

                if ($buscarUsuario->personaUser->complemento == null) {
                    $this->carnetResponsable = $buscarUsuario->personaUser->ci;
                } else {
                    $this->carnetResponsable = $buscarUsuario->personaUser->ci . ' - ' . $buscarUsuario->personaUser->complemento;
                }

                if ($buscarUsuario->personaUser->p_apellido == null) {
                    $this->nombreCompletoResponsable = $buscarUsuario->personaUser->s_apellido . ' ' . $buscarUsuario->personaUser->nombres;
                } elseif ($buscarUsuario->personaUser->s_apellido == null) {
                    $this->nombreCompletoResponsable = $buscarUsuario->personaUser->p_apellido . ' ' . $buscarUsuario->personaUser->nombres;
                } else {
                    $this->nombreCompletoResponsable = $buscarUsuario->personaUser->p_apellido . ' ' . $buscarUsuario->personaUser->s_apellido . ' ' . $buscarUsuario->personaUser->nombres;
                }
            } else {
                $this->usuarioResponsable = '';
                $this->carnetResponsable = '';
                $this->nombreCompletoResponsable = '';
                $this->dispatch('noExisteUsuario');
            }
        } catch (\Throwable $th) {
            $this->dispatch('noExisteUsuario');
        }


        $this->skipRender();
    }

    public function asignarUsuario()
    {
        DB::beginTransaction();
        try {
            if ($this->idUsuarioSeleccionado) {
                $asignar = Institucion::find($this->instId);
                $asignar->update([
                    'id_usuario_responsable' => $this->idUsuarioSeleccionado,
                    'id_user_updated' => auth()->user()->id,
                ]);

                DB::commit();
                $this->ciUsuarioBusqueda = '';
                $this->complementoUsuarioBusqueda = '';
                $this->dispatch('asignacionGuardado');
            } else {
                DB::rollBack();
                $this->ciUsuarioBusqueda = '';
                $this->complementoUsuarioBusqueda = '';
                $this->dispatch('noExisteElUsuario');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->ciUsuarioBusqueda = '';
            $this->complementoUsuarioBusqueda = '';
            $this->dispatch('Error');
        }
    }


    public function AbrirModalUbicacionInstitucion()
    {
        $this->mostrarInstitucion($this->instId);
        $this->d_latitud = $this->latitudRegistered;
        $this->d_longitud = $this->longitudRegistered;
        $this->dispatch('ModalAbrirInstitucion', title: $this->d_latitud);
        //dd($this->latitudRegistered,$this->longitudRegistered);
        // $this->dispatch('inputUpdated', [$this->latitudRegistered, $this->longitudRegistered]);
        $this->skipRender();
    }


    public function abrir_file_nit()
    {
        $this->dispatch('modal_ver_nit');
        $this->skipRender();
    }

    public $pdf_vista;
    public function mostrar_pdf_nit($data)
    {
        if ($data) {
            $path = 'Aportes/Institucion/Nit/' . $data . '_nit' . '.pdf';
            $fileContents = Storage::disk('ftp')->get($path);
            $pdfBase64 = base64_encode($fileContents);
            $this->pdf_vista = $pdfBase64;
        }
    }
    public function abrir_carnet()
    {
        $this->dispatch('modal_ver_carnet');

        $this->skipRender();
    }
    public $pdf_vista_ci;
    public function mostrar_pdf_ci($id)
    {
        if ($id) {
            $path = 'Aportes/Institucion/RepresentanteLegal/' . $id . '_ci' . '.pdf';

            $fileContents = Storage::disk('ftp')->get($path);
            $pdfBase64 = base64_encode($fileContents);
            $this->pdf_vista_ci = $pdfBase64;
        }
    }


    // asignarUsuarioAvisoBaja
    #[On('asignarUsuarioAvisoBaja')]
    public function asignarUsuarioAvisoBaja($data)
    {
        $this->instId = $data;
        $this->usuarioAsignadoBaja = "";
        $this->nombreCompletoAsignadoBaja = "";
        $this->carnetAsignadoBaja = "";
        $this->complementoUsuarioBusqueda = "";
        $this->ciUsuarioBusqueda = "";
        $this->mostrarInstitucion($this->instId);
        $this->dispatch('modalAsignarUsuarioAvisoBaja');
    }

    public    $nombreCompletoAsignadoBaja, $usuarioAsignadoBaja, $carnetAsignadoBaja;
    public function searchPersonaUsuarioBaja()
    {
        if ($this->complementoUsuarioBusqueda == '') {
            $this->complementoUsuarioBusqueda = null;
        }
        try {
            $buscarUsuario = User::with(['personaUser'])
                ->whereHas('personaUser', function ($personaUsuario) {
                    $personaUsuario->where('ci', $this->ciUsuarioBusqueda)
                        ->where('complemento', $this->complementoUsuarioBusqueda);
                })
                ->where('estado', true)
                ->first();

            if ($buscarUsuario) {
                $this->idUsuarioSeleccionado = $buscarUsuario->id;
                $this->usuarioAsignadoBaja = $buscarUsuario->email;

                if ($buscarUsuario->personaUser->complemento == null) {
                    $this->carnetAsignadoBaja = $buscarUsuario->personaUser->ci;
                } else {
                    $this->carnetAsignadoBaja = $buscarUsuario->personaUser->ci . ' - ' . $buscarUsuario->personaUser->complemento;
                }

                if ($buscarUsuario->personaUser->p_apellido == null) {
                    $this->nombreCompletoAsignadoBaja = $buscarUsuario->personaUser->s_apellido . ' ' . $buscarUsuario->personaUser->nombres;
                } elseif ($buscarUsuario->personaUser->s_apellido == null) {
                    $this->nombreCompletoAsignadoBaja = $buscarUsuario->personaUser->p_apellido . ' ' . $buscarUsuario->personaUser->nombres;
                } else {
                    $this->nombreCompletoAsignadoBaja = $buscarUsuario->personaUser->p_apellido . ' ' . $buscarUsuario->personaUser->s_apellido . ' ' . $buscarUsuario->personaUser->nombres;
                }
            } else {
                $this->usuarioAsignadoBaja = '';
                $this->carnetAsignadoBaja = '';
                $this->nombreCompletoAsignadoBaja = '';
                $this->dispatch('noExisteUsuario');
            }
        } catch (\Throwable $th) {
            $this->dispatch('noExisteUsuario');
        }


        $this->skipRender();
    }

    // guardarUsuarioAvisoBaja
    #[On('guardarUsuarioAvisoBaja')]
    public function guardarUsuarioAvisoBaja()
    {
        DB::beginTransaction();
        try {
            if ($this->idUsuarioSeleccionado) {
                $asignar = Institucion::find($this->instId);
                $asignar->update([
                    'id_usuario_aviso_baja' => $this->idUsuarioSeleccionado,
                    'id_user_updated' => auth()->user()->id,
                ]);

                DB::commit();
                $this->ciUsuarioBusqueda = '';
                $this->complementoUsuarioBusqueda = '';
                $this->dispatch('asignacionGuardado');
            } else {
                DB::rollBack();
                $this->ciUsuarioBusqueda = '';
                $this->complementoUsuarioBusqueda = '';
                $this->dispatch('noExisteElUsuario');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->ciUsuarioBusqueda = '';
            $this->complementoUsuarioBusqueda = '';
            $this->dispatch('Error');
        }
    }
}
