<?php

namespace App\Livewire\Aportes\Externos\Institucion;

use Livewire\Component;
use App\Models\Administracion\Zona;
use App\Models\Aportes\ClaseAportante;
use App\Models\Afiliacion\CodigoInstitucionSsu;
use App\Models\Administracion\NotificacionUsuarios;
use Illuminate\Support\Facades\Http;
use App\Models\Aportes\Institucion;
use App\Models\Administracion\Persona;
use App\Models\Administracion\Residencia;
use App\Models\Administracion\Departamento;
use App\Services\ValidarPersona;
use App\Services\ConexionAsuss;
use Livewire\Attributes\Validate;
use App\Models\Administracion\RolUsuario;
use App\Models\Administracion\Role;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class InstitucionRegisterExt extends Component
{

    use WithFileUploads;
    #[Validate]
    public $file_digital_nit;
    public $file_digital_ci;

    public $busquedaCarnet;
    public $busquedaComplemento;

    public $carnet;
    public $estado_api;
    public $complemento;
    // variables para la funcion
    #[Validate]
    public $institucion;
    #[Validate]
    public $nit;
    #[Validate]
    public $acronimo;
    #[Validate]
    public $estado = true;
    #[Validate]
    public $nro_empleador;
    #[Validate]
    public $clase_aportante_id, $clase_aportantes;
    #[Validate]
    public $tgn = false;
    #[Validate]
    public $direccion;
    #[Validate]
    public $numero;
    #[Validate]
    public $zona;
    public $correo;
    #[Validate]
    public $telefono;
    #[Validate]
    public $fecha_afiliacion;
    #[Validate]
    public $ci;
    public $complemento_m;
    public $ap_paterno;
    public $ap_materno;
    #[Validate]
    public $nombre;
    #[Validate]
    public $fecha_nacimiento;
    #[Validate]
    public $actividad_empleador;
    public $publica = true;
    #[Validate]
    public $tipo_doc;
    public $expedido_ci;
    public $latitud = '';
    public $longitud = '';
    public $ubicacion_select = 0;
    public $existePersona, $tipo_comercio, $tipo_institucion = true, $direccion_referencia, $telefono_dos;
    public $departamento, $registraRepresentante = false;
    public $zonas = [];
    public $cod_instituciones;


    protected $listeners = ['guardarInsti', 'recibirLonLat'];
    public $esperandoDatos = false;

    public function rules()
    {
        return [
            'institucion' => 'required|min:4',
            'nit' => 'required|numeric',
            'nro_empleador' => 'required',
            'clase_aportante_id' => 'required',
            'actividad_empleador' => 'required',
            'direccion' => 'required',
            'numero' => 'required',
            'acronimo' => 'required',
            'zona' => 'required',
            //'correo' => 'required|email',
            'telefono' => 'required',
            'ci' => 'required',
            'nombre' => 'required',
            'fecha_afiliacion' => 'required',
            'fecha_nacimiento' => 'required',
            // 'expedido_ci' => 'required',
            'tipo_doc' => 'required',
            'tipo_comercio' => 'required',
            'tipo_institucion' => 'required',
            'direccion_referencia' => 'required',
            'file_digital_nit' => 'required|mimes:pdf|max:1024',
            'file_digital_ci' => 'required|mimes:pdf|max:1024',

        ];
    }

    public function messages()
    {
        return [
            'file_digital_nit.required' => 'El archivo NIT en PDF es obligatorio.',
            'file_digital_nit.mimes' => 'El archivo debe estar en formato PDF.',
            'file_digital_nit.max' => 'El archivo NIT debe ser menor a 1MB (1024KB).',

            'file_digital_ci.required' => 'El archivo CI en PDF es obligatorio.',
            'file_digital_ci.mimes' => 'El archivo debe estar en formato PDF.',
            'file_digital_ci.max' => 'El archivo CI debe ser menor a 1MB (1024KB).',
        ];
    }

    public function render()
    {
        return view('livewire.Aportes.Externos.institucion.institucion-register-ext');
    }

    public function mount()
    {
        $this->clase_aportantes = ClaseAportante::get();
        $this->departamento = Departamento::get();
        $this->zonas = Zona::orderBy('nombre', 'asc')->get();
        $this->cod_instituciones = CodigoInstitucionSsu::get();
    }

    public function search_persona()
    {
        // $response_login = Http::withDigestAuth('yanaram@ssulapaz.org', '123456789')
        // ->post('http://localhost:8080/api/sighov2/public/api/login');
        // $dato=$response_login;
        //   dd($dato);
        $this->registraRepresentante = true;
        if ($this->busquedaComplemento == '') {
            $this->busquedaComplemento = null;
        }

        $this->existePersona = Persona::where('ci', $this->busquedaCarnet)
            ->where('complemento', $this->busquedaComplemento)
            ->orderBy('id', 'desc')
            ->first();
        if ($this->existePersona != null) {
            $this->estado_api = 1;
            $this->nombre = $this->existePersona->nombres;
            $this->ap_paterno = $this->existePersona->p_apellido;
            $this->ap_materno = $this->existePersona->s_apellido;
            $this->fecha_nacimiento = $this->existePersona->fecha_nacimiento;
            $this->ci = $this->existePersona->ci;
            $this->complemento_m = $this->existePersona->complemento;
        } else {
            $respuesta = new ValidarPersona();
            $dato = $respuesta->buscarPersona($this->busquedaCarnet, $this->busquedaComplemento);

            if (isset($dato['datosPersonaEnFormatoJson'])) {
                $this->ci = $dato['datosPersonaEnFormatoJson']['numeroDocumento'];
                $this->complemento_m = $dato['datosPersonaEnFormatoJson']['Complemento'];
                $this->ap_paterno = $dato['datosPersonaEnFormatoJson']['primerApellido'];
                $this->ap_materno = $dato['datosPersonaEnFormatoJson']['segundoApellido'];
                $this->nombre = $dato['datosPersonaEnFormatoJson']['nombres'];
                $this->fecha_nacimiento = $dato['datosPersonaEnFormatoJson']['fechaNacimiento'];
                $this->estado_api = 1;
            } else {
                $this->estado_api = 0;
                $this->nombre = '';
                $this->ap_paterno = '';
                $this->ap_materno = '';
                $this->fecha_nacimiento = '';
                $this->ci = $this->busquedaCarnet;
                $this->complemento_m = $this->busquedaComplemento;
            }
        }

        $this->esperandoDatos = false;
    }
    //FUNCION PARA LO DE LA UBICACION
    public function recibirLonLat($d_lat, $d_lng)
    {
        $this->latitud = $d_lat;
        $this->longitud = $d_lng;
        $this->ubicacion_select = 1;
    }

    public function guardarInsti()
    {
        $this->validate();
        DB::beginTransaction();
        try {
            if ($this->registraRepresentante == false) {
                dd('ingresa representan chovo');
            }
            if ($this->existePersona != null) {
                $existeResidencia = Residencia::where('direccion', $this->direccion)
                    ->where('nro_vivienda', $this->numero)
                    ->where('id_zona', $this->zona)
                    ->first();
                if ($existeResidencia != null) {
                    $existeResidencia->update([
                        'latitud' => $this->latitud,
                        'longitud' => $this->longitud,
                        'id_user_updated' => auth()->user()->id,
                    ]);
                    $residenciaId = $existeResidencia->id;
                } else {
                    $residencia = Residencia::create([
                        'direccion' => $this->direccion,
                        'nro_vivienda' => $this->numero,
                        'id_zona' => $this->zona,
                        'latitud' => $this->latitud,
                        'longitud' => $this->longitud,
                        'id_user_created' => auth()->user()->id,
                    ]);

                    $residenciaId = $residencia->id;
                }
                $intitucion = Institucion::create([
                    'nit' => $this->nit,
                    'nro_empleador' => $this->nro_empleador,
                    'actividad_empleador' => $this->actividad_empleador,
                    'id_clase_aportante' => $this->clase_aportante_id,
                    'acronimo' => $this->acronimo,
                    'fecha_afiliacion' => $this->fecha_afiliacion,
                    'correo' => $this->correo,
                    'publica' => $this->publica,
                    'telefono' => $this->telefono,
                    'id_persona' => $this->existePersona->id,
                    'id_residencia' => $residenciaId,
                    'cod_aportante' => 1,
                    'nombre' => $this->institucion,
                    'estado' => true,
                    'tgn' => $this->tgn,
                    'estado_validado' => false,
                    'codigo_comercio' => $this->tipo_comercio,
                    'tipo_empresa_institucion' => $this->tipo_institucion,
                    'referencia_direccion' => $this->direccion_referencia,
                    'telefono_dos' => $this->telefono_dos,
                    'id_usuario_responsable' => auth()->user()->id,
                    'id_user_created' => auth()->user()->id,
                ]);
                //dd('si es difernte de nullo', $intitucion);

                if ($intitucion) {
                    if ($this->file_digital_nit) {
                        //guardamos en el FTP NIT 
                        $nombre_file_nit = $intitucion->id . '_nit' . '.pdf';
                        $this->file_digital_nit->storeAs('Aportes/Institucion/Nit', $nombre_file_nit, 'ftp');
                    }
                    if ($this->file_digital_ci) {
                        //guardamos en el FT CI
                        $nombre_file_ci = $intitucion->id . '_ci' . '.pdf';
                        $this->file_digital_ci->storeAs('Aportes/Institucion/RepresentanteLegal', $nombre_file_ci, 'ftp');
                    }
                    // para envio de notificaciones
                    $rol = Role::where('name', 'Encargado Aportes')->first();
                    //dd($rol);
                    $receptores = RolUsuario::where('role_id', $rol->id)->get();
                    foreach ($receptores as $receptor) {
                        $nuevaNotificacion = NotificacionUsuarios::create([
                            'estado' => 'NO LEIDO',
                            'prioridades' => 1,
                            'id_usu_emisor' => auth()->user()->id,
                            'id_usu_receptor' => $receptor->model_id, // para que sea a los encargados de aportes
                            'fecha_hora_enviada' => Carbon::now(),
                            'asunto' => 'APORTES - REGISTRO DE INSTITUCIÓN',
                            'mensaje' => ' Se añadió una nueva institucion, llamada ' . $intitucion->nombre . ' Att. El administrador',
                        ]);
                    }
                    DB::commit();
                    $this->dispatch('pg:eventRefresh-InstitucionesRegistroExterno');
                    $this->dispatch('institucionCreada');
                    return redirect()->route('aportes.institucionesExternos.index')->with('evento', 'institucionCreada');
                }
            } else {
                $existePersona = Persona::where('ci', $this->ci)
                    ->where('complemento', $this->complemento_m)
                    ->first();

                if ($existePersona != null) {

                    $personaId = $existePersona->id;
                } else {
                    if ($this->complemento_m == null) {
                        $clave_unica = $this->ci;
                    } else {
                        $clave_unica = $this->ci . $this->complemento_m;
                    }
                    $persona = Persona::create([
                        'p_apellido' => $this->ap_paterno,
                        's_apellido' => $this->ap_materno,
                        'nombres' => $this->nombre,
                        'ci' => $this->ci,
                        'complemento' => $this->complemento_m,
                        'fecha_nacimiento' => $this->fecha_nacimiento,
                        'afiliado' => false,
                        'id_tipo_asegurado' => 0,
                        'es_extranjero' => $this->tipo_doc,
                        'id_dept_exp' => $this->expedido_ci,
                        'clave_unica' => $clave_unica,
                        'id_user_created' => auth()->user()->id,
                    ]);
                    $personaId = $persona->id;
                }

                $existeResidencia = Residencia::where('direccion', $this->direccion)
                    ->where('nro_vivienda', $this->numero)
                    ->where('id_zona', $this->zona)
                    ->first();
                if ($existeResidencia != null) {
                    $existeResidencia->update([
                        'latitud' => $this->latitud,
                        'longitud' => $this->longitud,
                        'id_user_updated' => auth()->user()->id,
                    ]);
                    $residenciaId = $existeResidencia->id;
                } else {
                    $residencia = Residencia::create([
                        'direccion' => $this->direccion,
                        'nro_vivienda' => $this->numero,
                        'id_zona' => $this->zona,
                        'latitud' => $this->latitud,
                        'longitud' => $this->longitud,
                        'id_user_created' => auth()->user()->id,
                    ]);

                    $residenciaId = $residencia->id;
                }

                $intitucion = Institucion::create([
                    'nit' => $this->nit,
                    'nro_empleador' => $this->nro_empleador,
                    'actividad_empleador' => $this->actividad_empleador,
                    'id_clase_aportante' => $this->clase_aportante_id,
                    'acronimo' => $this->acronimo,
                    'fecha_afiliacion' => $this->fecha_afiliacion,
                    'correo' => $this->correo,
                    'telefono' => $this->telefono,
                    'id_persona' => $personaId,
                    'id_residencia' => $residenciaId,
                    'cod_aportante' => 1,
                    'nombre' => $this->institucion,
                    'estado' => true,
                    'tgn' => $this->tgn,
                    'publica' => $this->publica,
                    'estado_validado' => false,
                    'codigo_comercio' => $this->tipo_comercio,
                    'tipo_empresa_institucion' => $this->tipo_institucion,
                    'referencia_direccion' => $this->direccion_referencia,
                    'telefono_dos' => $this->telefono_dos,
                    'id_usuario_responsable' => auth()->user()->id,
                    'id_user_created' => auth()->user()->id,
                ]);
                //dd('creamos persona', $intitucion);
                if ($intitucion) {
                    if ($this->file_digital_nit) {
                        //guardamos en el FTP NIT 
                        $nombre_file_nit = $intitucion->id . '_nit' . '.pdf';
                        $this->file_digital_nit->storeAs('Aportes/Institucion/Nit', $nombre_file_nit, 'ftp');
                    }
                    if ($this->file_digital_ci) {
                        //guardamos en el FT CI
                        $nombre_file_ci = $intitucion->id . '_ci' . '.pdf';
                        $this->file_digital_ci->storeAs('Aportes/Institucion/RepresentanteLegal', $nombre_file_ci, 'ftp');
                    }
                    // para envio de notificaciones
                    $rol = Role::where('name', 'Encargado Aportes')->first();
                    //dd($rol);
                    $receptores = RolUsuario::where('role_id', $rol->id)->get();
                    foreach ($receptores as $receptor) {
                        $nuevaNotificacion = NotificacionUsuarios::create([
                            'estado' => 'NO LEIDO',
                            'prioridades' => 1,
                            'id_usu_emisor' => auth()->user()->id,
                            'id_usu_receptor' => $receptor->model_id, // para que sea a los encargados de aportes
                            'fecha_hora_enviada' => Carbon::now(),
                            'asunto' => 'APORTES - REGISTRO DE INSTITUCIÓN',
                            'mensaje' => ' Se añadió una nueva institucion, llamada ' . $intitucion->nombre . ' Att. El administrador',
                        ]);
                    }
                    // DB::commit();
                    $this->dispatch('pg:eventRefresh-InstitucionesRegistroExterno');
                    $this->dispatch('institucionCreada');
                    return redirect()->route('aportes.institucionesExternos.index')->with('evento', 'institucionCreada');
                }
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('institucionCancelado');
            return redirect()->route('aportes.institucionesExternos.index')->with('evento', 'institucionCancelada');
        }
    }


    public function cancel()
    {
        // Emitir el evento
        $this->dispatch('institucionCancelado');

        // Redirigir a la ruta deseada
        return redirect()->route('aportes.institucionesExternos.index')->with('evento', 'institucionCancelada');
    }
    public function atras()
    {
        // Redirigir a la ruta deseada
        return redirect()->route('aportes.institucionesExternos.index');
    }
}
