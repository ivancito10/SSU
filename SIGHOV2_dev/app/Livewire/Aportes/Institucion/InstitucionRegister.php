<?php

namespace App\Livewire\Aportes\Institucion;

use Livewire\Component;
use App\Models\Administracion\Zona;
use App\Models\Aportes\ClaseAportante;
use App\Models\Afiliacion\CodigoInstitucionSsu;
use App\Models\Administracion\Residencia;
use Illuminate\Support\Facades\Http;
use App\Models\Aportes\Institucion;
use App\Models\Administracion\Persona;
use App\Models\Administracion\Departamento;
use App\Services\ValidarPersona;
use App\Services\ConexionAsuss;
use App\Services\ReplicarInstitucionGehma2;
use Livewire\Attributes\Validate;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class InstitucionRegister extends Component
{
    use WithFileUploads;
    #[Validate]
    public $file_digital_nit;
    public $file_digital_ci;

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
    public $clase_aportante_id;
    #[Validate]
    public $tgn = false;
    #[Validate]
    public $direccion;
    #[Validate]
    public $numero;
    #[Validate]
    public $zona;
    #[Validate]
    public $correo;
    #[Validate]
    public $telefono;
    #[Validate]
    public $fecha_afiliacion;
    #[Validate]
    public $ci;
    #[Validate]
    public $complemento_m;
    #[Validate]
    public $ap_paterno;
    #[Validate]
    public $ap_materno;
    #[Validate]
    public $nombre;

    public $fecha_nacimiento;
    public $fecha_nacimiento_real;
    public $publica = true;
    #[Validate]
    public $actividad_empleador;
    #[Validate]
    public $latitud = '';
    public $longitud = '';
    public $ubicacion_select = 0;
    public $existePersona, $tipo_comercio, $tipo_institucion = true, $direccion_referencia, $telefono_dos;
    #[Validate]
    public $tipo_doc;
    #[Validate]
    public $expedido_ci;

    protected $listeners = ['search_persona', 'guardarInsti', 'recibirLonLat', 'cancel'];
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
            'zona' => 'required',
            'acronimo' => 'required',
            'ci' => 'required',
            'nombre' => 'required',
            'fecha_nacimiento_real' => 'required',
            'publica' => 'required',
            'expedido_ci' => 'required',
            'tipo_doc' => 'required',
            'fecha_afiliacion' => 'required',
            'tipo_comercio' => 'required',
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
        return view('livewire.Aportes.institucion.institucion-register');
    }
    public $departamento;
    public $clase_aportantes;
    public $cod_instituciones;
    public $zonas;

    public function mount()
    {
        $this->clase_aportantes = ClaseAportante::all();
        $this->cod_instituciones = CodigoInstitucionSsu::all();
        $this->zonas = Zona::orderBy('nombre', 'asc')->get();
        $this->departamento = Departamento::all();
    }

    public function search_persona()
    {
        // $response_login = Http::withDigestAuth('yanaram@ssulapaz.org', '123456789')
        // ->post('http://localhost:8080/api/sighov2/public/api/login');
        // $dato=$response_login;
        //   dd($dato);
        /*$respuesta_con = new ValidarPersona();
        $dato_con = $respuesta_con->buscarPersona('3434797',null);
        if(isset($dato_con['error'])){
            $conexionAsuss_con = new ConexionAsuss;
            $logeo_con = $conexionAsuss_con->login();
        }*/
        if ($this->complemento == '') {
            $this->complemento = null;
        }

        try {
            $this->existePersona = Persona::where('ci', $this->carnet)
                ->where('complemento', $this->complemento)
                ->orderBy('id', 'desc')
                ->first();

            if ($this->existePersona != null) {
                $this->estado_api = 1;
                $this->nombre = $this->existePersona->nombres;
                $this->ap_paterno = $this->existePersona->p_apellido;
                $this->ap_materno = $this->existePersona->s_apellido;
                $this->fecha_nacimiento_real = $this->existePersona->fecha_nacimiento;
                $this->fecha_nacimiento = mb_strtoupper(Carbon::parse($this->existePersona->fecha_nacimiento)->translatedFormat('d \d\e F \d\e Y'));
                $this->ci = $this->existePersona->ci;
                $this->complemento_m = $this->existePersona->complemento;
                // Suponiendo que $this->existePersona->fecha_nacimiento es una fecha válida
                $fechaNacimiento = Carbon::parse($this->fecha_nacimiento_real);
                // Calcula la edad
                $edad = $fechaNacimiento->age;
                if ($edad < 18) {
                    $this->dispatch('menorEdad');
                }
            } else {
                $respuesta = new ValidarPersona();
                $dato = $respuesta->buscarPersona($this->carnet, $this->complemento);
                if (isset($dato['datosPersonaEnFormatoJson'])) {
                    $this->ci = $dato['datosPersonaEnFormatoJson']['numeroDocumento'];
                    $this->complemento_m = $dato['datosPersonaEnFormatoJson']['Complemento'];
                    $this->ap_paterno = $dato['datosPersonaEnFormatoJson']['primerApellido'];
                    $this->ap_materno = $dato['datosPersonaEnFormatoJson']['segundoApellido'];
                    $this->nombre = $dato['datosPersonaEnFormatoJson']['nombres'];
                    $this->fecha_nacimiento_real = $dato['datosPersonaEnFormatoJson']['fechaNacimiento'];
                    $this->fecha_nacimiento = mb_strtoupper(Carbon::parse($dato['datosPersonaEnFormatoJson']['fechaNacimiento'])->translatedFormat('d \d\e F \d\e Y'));
                    $this->estado_api = 1;
                    // Suponiendo que $this->existePersona->fecha_nacimiento es una fecha válida
                    $fechaNacimiento = Carbon::parse($this->fecha_nacimiento_real);
                    // Calcula la edad
                    $edad = $fechaNacimiento->age;
                    if ($edad < 18) {
                        $this->dispatch('menorEdad');
                    }
                } else {
                    $this->estado_api = 0;
                    $this->nombre = '';
                    $this->ap_paterno = '';
                    $this->ap_materno = '';
                    $this->fecha_nacimiento = '';
                    $this->fecha_nacimiento_real = '';
                    $this->ci = '';
                    $this->complemento_m = '';
                }
            }


            $this->esperandoDatos = false;
        } catch (\Throwable $th) {
            $this->dispatch('noExiste');
        }
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

        $existeInstitucionActiva = Institucion::where('nombre', $this->institucion)
            ->where('nit', $this->nit)->where('estado', true)
            ->first();
        $existeInstitucionInactiva = Institucion::where('nombre', $this->institucion)
            ->where('nit', $this->nit)->where('estado', false)
            ->first();

        if ($existeInstitucionActiva) {
            $this->dispatch('existeActiva');
        } elseif ($existeInstitucionInactiva) {
            $this->dispatch('existeInactiva');
        }
        if ($this->tipo_institucion == null) {
            $this->tipo_institucion = false;
        }

        DB::beginTransaction();

        try {
            $existePersona = Persona::where('ci', $this->ci)
                ->where('complemento', $this->complemento_m)
                ->first();

            if ($existePersona != null) {

                $existePersona->es_extranjero = $this->tipo_doc;
                $existePersona->id_dept_exp = $this->expedido_ci;
                $existePersona->save();

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
                    'cod_aportante' => 1,
                    'nombre' => $this->institucion,
                    'estado' => true,
                    'tgn' => $this->tgn,
                    'estado_validado' => false,
                    'codigo_comercio' => $this->tipo_comercio,
                    'tipo_empresa_institucion' => $this->tipo_institucion,
                    'referencia_direccion' => $this->direccion_referencia,
                    'telefono_dos' => $this->telefono_dos,
                    'id_residencia' => $residenciaId,
                    'id_user_created' => auth()->user()->id,
                ]);
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
                    DB::commit();

                    //replicar institucion
                    // $gehma2Service = new ReplicarInstitucionGehma2();
                    // $gehma2Service->replicarInstitucionGehma2($intitucion);
                    $this->dispatch('pg:eventRefresh-InstitucionesRegistro');
                    return redirect()->route('aportes.instituciones.index')->with('evento', 'institucionCreada');
                }
            } else {

                $existePersona = Persona::where('ci', $this->ci)
                    ->where('complemento', $this->complemento_m)
                    ->first();


                if ($existePersona != null) {
                    $existePersona->es_extranjero = $this->tipo_doc;
                    $existePersona->id_dept_exp = $this->expedido_ci;
                    $existePersona->save();

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
                        'fecha_nacimiento' => $this->fecha_nacimiento_real,
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
                    'publica' => $this->publica,
                    'nombre' => $this->institucion,
                    'estado' => true,
                    'tgn' => $this->tgn,
                    'estado_validado' => false,
                    'codigo_comercio' => $this->tipo_comercio,
                    'tipo_empresa_institucion' => $this->tipo_institucion,
                    'referencia_direccion' => $this->direccion_referencia,
                    'telefono_dos' => $this->telefono_dos,
                    'id_residencia' => $residenciaId,
                    'id_user_created' => auth()->user()->id,
                ]);
                //


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
                    //replicar institucion
                    // $gehma2Service = new ReplicarInstitucionGehma2();
                    // $gehma2Service->replicarInstitucionGehma2($intitucion);
                    DB::commit();
                    $this->dispatch('pg:eventRefresh-InstitucionesRegistro');
                    return redirect()->route('aportes.instituciones.index')->with('evento', 'institucionCreada');
                }
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('institucionCancelado');
            return redirect()->route('aportes.instituciones.index');
        }
    }


    public function cancel()
    {

        // Redirigir a la ruta deseada
        return redirect()->route('aportes.instituciones.index')->with('evento', 'institucionCancelado');
    }
    public function atras()
    {
        return redirect()->route('aportes.instituciones.index');
    }
}
