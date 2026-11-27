<?php

namespace App\Livewire\Afiliacion\Titulares;

use App\Jobs\ProcesarAfiliacionTitularAsussJob;
use Livewire\Component;
use App\Models\Aportes\AporteInstitucional;
use App\Models\Aportes\ContratoVoluntario;
use App\Models\Administracion\Persona;
use App\Models\Administracion\Zona;
use App\Models\Administracion\Residencia;
use Livewire\Attributes\On;
use App\Models\Aportes\Institucion;
use Illuminate\Support\Facades\Http;
use App\Services\ValidarPersona;
use App\Services\ConexionAsuss;
use App\Services\InsersionAsuss;
use App\Services\TitularesAsuss;
use App\Services\TitularesGehma2;
use Livewire\Attributes\Validate;
use App\Models\Afiliacion\CodigoInstitucionSsu;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\Estudiante;
use App\Models\Afiliacion\TitularInstitucion;
use Livewire\WithFileUploads;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Administracion\EstadoCivil;
use App\Mail\Afiliacion\AfiliacionTitularesMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Administracion\Departamento;
use App\Models\Administracion\Provincia;
use App\Models\Administracion\Municipio;
use App\Models\Vigencia\HistorialAsegurado;
use Livewire\Attributes\Locked;
use App\Models\Administracion\NotificacionUsuarios;
use App\Models\User;
use App\Services\InteroperabilidadAsuss\AfiliacionTitularAsussService;
use App\Services\InteroperabilidadAsuss\LoginAsussService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TitularesNuevo extends Component
{
    use WithFileUploads;
    #[Validate]
    public $foto;
    public $file_digital;
    public $estado_api;
    public $carnet;
    public $tipo_asegurado = false; // 1 true para 'VOLUNTARIO' o 0 false para 'INSTITUCIONAL'
    public $tipo = 0;
    public $fecha_ingreso_ssu;
    public $fecha_afiliacion;
    public $fecha_afiliacion_real;
    public $fecha_reafiliacion;

    public $departamento;
    public $provincias = [];
    public $municipios = [];

    public $idPersona;

    public $instituciones = [];
    public $institucion = '';
    public $codigo;
    public $fecha_ingreso;
    public $cargo, $salario, $item, $carga_horaria, $titularidad, $id_institucion_seleccionada;
    public $lista_instituciones;
    public $codigo_titular_ver;
    public $codigo_beneficiario_ver;
    public $codigo_ver;
    public $nombre_institucion;
    public $encontrado;

    public $fecha_nacimientoSwitch;
    public $tipo_docSwitch;
    public $expedido_ciSwitch;
    public $matriculaSwitch;
    public $estado_civilSwitch;
    public $datosLugarNacimiento = false;
    public $permanente = false;

    #[Validate]
    public $direccion, $zona, $nro_vivienda;
    #[Validate]
    public $telefono;
    #[Validate]
    public $correo;
    #[Validate]
    public $nombre_referencia;
    #[Validate]
    public $telefono_referencia, $ocultarDepartamento;
    #[Validate]
    public $alergias;
    public $observacion, $grupo_sanguineo;
    public $tieneSangre = false;
    public $res_ins = [];
    public $res_vol = [];
    // mostrar datos de la persona seleccionada en lista

    public $matricula;
    public $sexo;
    public $id_estado_civil;
    #[Validate]
    public $estado_civil;
    public $edad;
    public $titularExiste;
    //ASUSS
    #[Validate]
    public $ci;
    #[Validate]
    public $complemento;
    #[Validate]
    public $ap_paterno;
    #[Validate]
    public $ap_materno;
    #[Validate]
    public $nombre;
    #[Validate]
    public $fecha_nacimiento;
    public $zonasSelect;

    public $tipo_doc = 'false', $expedido_ci;
    #[Validate]
    public $dep_nacimiento, $pro_nacimiento, $mun_nacimiento;
    public $dep_nacimiento_nombre, $pro_nacimiento_nombre, $mun_nacimiento_nombre;
    public $latitud = '';
    public $longitud = '';
    public $ubicacion_select = 0;
    public $esRentista = false;
    public $fecha_minima_nacimiento;
    public $tipo_rentista, $today;
    //Para la busqueda
    public $carnetBusqueda;
    public $complementoBusqueda;
    public $cargando = 0;
    public $usuario;
    public $tiene_institucion = false, $valor;
    public $respuestaGehma2 = 'NADA';
    protected $listeners = ['agregarNuevo'];
    public $tipo_doc_1;

    public $servicioAsussTitular;

    public function rules()
    {
        return [
            'direccion' => 'required|min:5',
            'zona' => 'required',
            'nro_vivienda' => 'required|min:2',
            'telefono' => 'required|min:6',
            'nombre_referencia' => 'required|min:2',
            'telefono_referencia' => 'required|min:6',
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'file_digital' => 'required|mimes:pdf|max:51200',
            // 'tipo_doc' => 'required',
            //  'tipo_doc_1' => 'required',
            'fecha_nacimiento' => 'required|date|after_or_equal:1900-01-01|before_or_equal:today',
            'estado_civil'  => 'required',
        ];
    }
    public function render()
    {
        //dd($this->idPersona);
        return view('livewire.Afiliacion.titulares.titulares-nuevo');
    }

    public function mount()
    {
        // $validar = new TitularesAsuss();
        // $datos = $validar->validarTitularAsuss('1544454', NULL, '1971-06-09');
        // dd($datos);
        $this->esExtranjeroFunction();
        $this->mostrarDatosPersona($this->idPersona);
        if ($this->fecha_nacimiento != null) {
            if ($this->sexo != null) {
                if ($this->sexo = 'F') {
                    $this->sexo = true;
                } elseif ($this->sexo = 'M') {
                    $this->sexo = false;
                }
            }
            $this->GenerarMatricula();
        }
        $this->zonasSelect = Zona::orderBy('nombre', 'asc')->get();
        $this->departamento = Departamento::all();
        $this->id_estado_civil = EstadoCivil::all();
        // $this->lista_instituciones = Institucion::where('estado', true)
        //     ->whereNot('nombre', 'UNIVERSIDAD MAYOR DE SAN ANDRES')
        //     ->whereNot('nombre', 'UNIVERSIDAD CATOLICA BOLIVIANA SAN PABLO -%')
        //     ->with(['codigosInstitucion', 'claseAportanteInstitucion', 'institucionResidencia'])
        //     ->orderBy('nombre', 'asc')->get();

        $this->lista_instituciones = Institucion::where('estado', true)
            ->where('nombre', '!=', 'UNIVERSIDAD MAYOR DE SAN ANDRES')
            ->where(function ($q) {
                $q->where(function ($sub) {
                    $sub->where('nombre', 'like', 'UNIVERSIDAD CATOLICA BOLIVIANA SAN PABLO%')
                        ->where('nombre', 'not like', '%-%');
                })
                    ->orWhere('nombre', 'not like', 'UNIVERSIDAD CATOLICA BOLIVIANA SAN PABLO%');
            })
            ->with(['codigosInstitucion', 'claseAportanteInstitucion', 'institucionResidencia'])
            ->orderBy('nombre', 'asc')
            ->get();

        $this->fecha_afiliacion = Carbon::now()->format('d/m/Y');
        $this->fecha_afiliacion_real = Carbon::now();
        //dd($this->valor);

    }
    public function atras()
    {
        return redirect()->route('afiliacion.titulares.index');
    }
    public function ObtenerProvincia()
    {
        $this->provincias = Provincia::where('id_departamento', '=', $this->dep_nacimiento)->get();
    }

    public function ObtenerMunicipio()
    {
        $this->municipios = Municipio::where('id_provincia', '=', $this->pro_nacimiento)->get();
    }

    public function validarFechaNacimiento()
    {
        $hoy = Carbon::now();

        if ($this->fecha_nacimiento  > $hoy->toDateString()) {
            $this->dispatch('FechaNacimientoInvalidaManana');
            $this->fecha_nacimiento = null;
            return;
        }

        $this->GenerarMatricula();
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

        /*$id_persona = Persona::where('ci', '=', $this->ci)
            ->where('complemento', '=', $this->complemento_m)
            ->first();

        if ($id_persona == null) {
            $id_pesona_e = 0;
        } else {
            $id_pesona_e = $id_persona->id;
        }
        
        $dato_primera_afiliacion = Titular::where('id_persona', '=', $id_pesona_e)->orderby('id', 'asc')->first();
        if ($dato_primera_afiliacion != null) {
            $this->fecha_ingreso_ssu = Carbon::parse($dato_primera_afiliacion->fecha_afiliacion)->format('Y-m-d');
            $this->fecha_reafiliacion = Carbon::now()->format('Y-m-d');
        } else {
            $this->fecha_ingreso_ssu = Carbon::now()->format('Y-m-d');
            $this->fecha_reafiliacion = Carbon::now()->format('Y-m-d');
        }
        // dd($dato_primera_afiliacion, $this->fecha_ingreso_ssu,$this->fecha_reafiliacion);*/
        $this->matricula = $matricula;
        //$this->skipRender();
    }

    public function VerCodigos()
    {
        $registro = CodigoInstitucionSsu::where('id_institucion', $this->institucion)->first();
        if ($registro) {
            $this->codigo_titular_ver = $registro->codigo .  $registro->tipo_titular;
            $this->codigo_beneficiario_ver = $registro->codigo . $registro->tipo_beneficiario;
        } else {
            $this->codigo_titular_ver = '';
            $this->codigo_beneficiario_ver = '';
        }
    }

    public function abrirModalAddInstitucion()
    {
        $this->dispatch('modalAddInstitucion');
    }

    #[On('AddInstitucionNuevo')]
    public function AddInstitucionNuevo()
    {
        try {
            $nombre_institucion = Institucion::join('aportes.clase_aportante as cap', 'cap.id', '=', 'aportes.institucion.id_clase_aportante')
                ->select('aportes.institucion.nombre', 'cap.tipo', 'aportes.institucion.id')
                ->where('aportes.institucion.id', $this->institucion)
                ->first();
            $this->id_institucion_seleccionada = $nombre_institucion->id;
            if ($this->tipo_rentista == null) {
                if ($nombre_institucion->tipo == 'RENTISTA JUBILADO') {
                    $this->esRentista = true;
                } elseif ($nombre_institucion->tipo == 'RENTISTA POR SORTEO') {
                    $this->esRentista = true;
                }
            }
            if (!empty($this->institucion) && !empty($this->fecha_ingreso) && !empty($this->cargo) && !empty($this->salario)  && !empty($this->carga_horaria) && !empty($this->titularidad)) {
                $idYaExiste = collect($this->instituciones)->contains('id_institucion', $this->institucion);
                if ($idYaExiste) {
                    $this->dispatch('errorIdInstitucionDuplicado');
                    return;
                }
                $this->instituciones[] = [
                    'id_institucion' => $this->institucion,
                    'institucion' => $nombre_institucion->nombre,
                    'tipo_institucion' => $nombre_institucion->tipo,
                    'codigo_titular_ver' => $this->codigo_titular_ver,
                    'codigo_beneficiario_ver' => $this->codigo_beneficiario_ver,
                    'fecha_ingreso' => $this->fecha_ingreso,
                    'cargo' => $this->cargo,
                    'salario' => $this->salario,
                    'item' => $this->item,
                    'carga_horaria' => $this->carga_horaria,
                    'titularidad' => $this->titularidad,
                ];
                $this->resetearVarInstituciones();
            } else {
                $this->dispatch('errorAddInstNuevo');
            }
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->dispatch('errorNuevo');
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error en agregar institucion", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }

    public function resetearVarInstituciones()
    {
        $this->reset(
            'institucion',
            'codigo_titular_ver',
            'codigo_beneficiario_ver',
            'fecha_ingreso',
            'cargo',
            'salario',
            'item',
            'carga_horaria',
            'titularidad'
        );
    }
    #[On('recibirLonLat')]
    public function recibirLonLat($d_lat, $d_lng)
    {
        $this->latitud = $d_lat;
        $this->longitud = $d_lng;
        $this->ubicacion_select = 1;
    }

    public function esExtranjeroFunction()
    {
        $this->valor = $this->tipo_doc;
        //  $valor2 = $this->tipo_doc_1;
        if ($this->valor == 'false') {
            $this->ocultarDepartamento = '1';
        } else {
            $this->ocultarDepartamento = '2';
        }
    }
    //  AQUI GUARDA TITULAR
    #[On('GuardarInstitucion')]
    public function GuardarInstitucion()
    {
        $this->validate();
        //-- validacion con tipo de gestora y sea menor de edad
        $this->edad = $this->fecha_nacimiento ? Carbon::parse($this->fecha_nacimiento)->age : null;

        if ($this->edad === null) {
            $this->dispatch('fechaNacimientoInvalida');
            return;
        }

        if (!$this->esRentista && $this->edad <= 18) {
            $this->dispatch('menorNoGestora');
            return;
        }
        //--  fin 
        $this->cargando = 1;
        DB::beginTransaction();
        try {
            $existePersona = Persona::where('ci', $this->ci)
                ->where('complemento', $this->complemento)
                ->first();

            if ($this->sexo == true) {
                $this->sexo = 'F';
            } else {
                $this->sexo = 'M';
            }


            $this->ci = preg_replace('/\s+/', '', trim($this->ci));
            $this->complemento = preg_replace('/\s+/', '', trim($this->complemento));
            $this->complemento = $this->complemento ?: null;

            if ($existePersona != null) {
                if ($this->complemento == null) {
                    $clave_unica = $this->ci;
                } else {
                    $clave_unica = $this->ci . $this->complemento;
                }
                $existePersona->update([
                    'sexo' => $this->sexo,
                    'afiliado' => true,
                    'matricula_seguro' => $this->matricula,
                    'id_nacimiento_municipio' => $this->mun_nacimiento,
                    'fecha_nacimiento' => $this->fecha_nacimiento,
                    'id_tipo_asegurado' => 1,
                    'es_extranjero' => $this->tipo_doc_1,
                    'id_estado_civil' => $this->estado_civil,
                    'id_dept_exp' => $this->expedido_ci,
                    'clave_unica' => $clave_unica,
                    'alergias' => $this->alergias,
                    'grupo_sanguineo' => $this->grupo_sanguineo,
                    'id_user_updated' => auth()->user()->id,
                ]);
                if ($existePersona->grupo_sanguineo) {
                    $this->tieneSangre = true;
                }
                $personaId = $existePersona->id;
            } elseif ($existePersona == null) {
                if ($this->complemento == null) {
                    $clave_unica = $this->ci;
                } else {
                    $clave_unica = $this->ci . $this->complemento;
                }

                // dd($this->ci, $this->complemento);
                $persona = Persona::create([
                    'ci' => $this->ci,
                    'complemento' => $this->complemento,
                    'nombres' => $this->nombre,
                    'p_apellido' => $this->ap_paterno,
                    's_apellido' => $this->ap_materno,
                    'fecha_nacimiento' => $this->fecha_nacimiento,
                    'estado_asuss' => 1,
                    'afiliado' => 1,
                    'sexo' => $this->sexo,
                    'id_nacimiento_municipio' => $this->mun_nacimiento,
                    'id_tipo_asegurado' => 1,
                    'es_extranjero' => $this->tipo_doc,
                    'matricula_seguro' => $this->matricula,
                    'id_estado_civil' => $this->estado_civil,
                    'id_dept_exp' => $this->expedido_ci,
                    'clave_unica' => $clave_unica,
                    'alergias' => $this->alergias,
                    'grupo_sanguineo' => $this->grupo_sanguineo,
                    'id_user_created' => auth()->user()->id,
                ]);
                if ($persona->grupo_sanguineo) {
                    $this->tieneSangre = true;
                }
                $personaId = $persona->id;
            }

            $existeResidencia = Residencia::where('direccion', $this->direccion)
                ->where('nro_vivienda', $this->nro_vivienda)
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
                    'nro_vivienda' => $this->nro_vivienda,
                    'id_zona' => $this->zona,
                    'latitud' => $this->latitud,
                    'longitud' => $this->longitud,
                    'id_user_created' => auth()->user()->id,
                ]);
                $residenciaId = $residencia->id;
            }

            $validacion_titular = Titular::where('id_persona', $personaId)->whereIn('estado_vigencia', ['VIGENTE', 'CESANTE'])->where('estado', true)->first();
            if ($validacion_titular == null) {
                $titular = Titular::create([
                    'estado_requisitos' => $this->tieneSangre,
                    'telefono_referencia' => $this->telefono_referencia,
                    'nombre_referencia' => $this->nombre_referencia,
                    'observacion' => $this->observacion,
                    'fecha_afiliacion' => $this->fecha_afiliacion_real,
                    'id_persona' => $personaId,
                    'telefono' => $this->telefono,
                    'correo' => $this->correo,
                    'estado' => 1,
                    'permanente' => $this->permanente,
                    'id_residencia' => $residenciaId,
                    'tipo_rentista' => $this->tipo_rentista,
                    'id_user_created' => auth()->user()->id,
                ]);
                $nombre_foto = $titular->id . '-T.png';
                $id_titular_b = $titular->id;
                if ($this->foto) {
                    $url = config('services.rembg.url');
                    $imagePath = $this->foto->getRealPath();
                    $imageName = 'foto_' . uniqid() . '.png'; // nombre único

                    try {
                        // Enviamos la imagen al contenedor rembg
                        $response = Http::timeout(90)->attach(
                            'image',
                            file_get_contents($imagePath),
                            basename($imagePath)
                        )->post($url);
                        if ($response->successful() && strlen($response->body()) > 1000) {

                            // Si rembg funcionó, usamos la imagen sin fondo
                            $imageWithoutBg = $response->body();
                        } else {
                            // Si falla, usamos la imagen original
                            throw new \Exception('Respuesta vacía o errónea desde rembg');
                        }
                    } catch (\Throwable $e) {
                        DB::rollBack();
                        $trace = collect($e->getTrace())
                            ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

                        Log::error("Error en foto del titular", [
                            'USUARIO_ID' => auth()->user()->id ?? null,
                            'ERROR'      => $e->getMessage() ?? '',
                            'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                            'LINEA'      => $trace['line'] ?? $e->getLine(),
                            'FECHA_HORA' => now(),
                        ]);
                        // Si rembg falla, usamos la imagen original
                        $imageWithoutBg = file_get_contents($imagePath);
                    }

                    // Codificamos para base64 si necesitas usarlo en otro lugar
                    $imageBase64_asuss = base64_encode($imageWithoutBg);
                    $imageBase64 = 'data:image/' . $this->foto->extension() . ';base64,' . $imageBase64_asuss;

                    // Guardamos en FTP
                    Storage::disk('ftp')->put('Afiliacion/FOTOS/' . $nombre_foto, $imageWithoutBg);

                    // Actualizamos la BD
                    $titular->update([
                        'foto_nombre' => $nombre_foto,
                    ]);
                }


                $nombre_file = $titular->id . '.pdf';
                $this->file_digital->storeAs('Afiliacion/FILES_PERSONALES/TITULARES', $nombre_file, 'ftp');

                $titular->update([
                    'foto_nombre' => $nombre_foto,
                ]);
                // logica para agregar institucion
                if ($this->tipo_asegurado == false) {
                    $this->tiene_institucion = true;
                } else {
                    $this->tiene_institucion = false;
                }
                if ($this->tiene_institucion == true && empty($this->instituciones)) {
                    DB::rollBack();
                    $this->dispatch('institucionNoIngresada');
                    return; // Detener ejecución
                }
                if ($this->instituciones) {
                    foreach ($this->instituciones as $key => $institucion) {
                        $institucion_titular = TitularInstitucion::create([
                            'id_titular' => $titular->id,
                            'id_institucion' => $institucion['id_institucion'],
                            'estado' => 1,
                            'fecha_ingreso' => $institucion['fecha_ingreso'],
                            'cargo_actual' => $institucion['cargo'],
                            'salario' => $institucion['salario'],
                            'tipo_institucion' => $institucion['tipo_institucion'],
                            'carga_horaria' => $institucion['carga_horaria'],
                            'item' => $institucion['item'],
                            'titularidad' => $institucion['titularidad'],
                            'id_user_created' => auth()->user()->id,
                        ]);
                    }
                    $titular->update([
                        'tipo_titular' => 1,
                    ]);
                } else {
                    $titular->update([
                        'tipo_titular' => 0,
                    ]);
                }

                //PARA LA TABLA DE HISTORIAL ASEGURADO
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
                        'id_tipo_asegurado' => 1,
                        'fecha_inicio' => Carbon::now()->format('Y-m-d'),
                        'id_persona' => $personaId,
                        'id_corresponde' => $titular->id,
                        'id_user_created' => auth()->user()->id,
                    ]);
                } else {
                    HistorialAsegurado::create([
                        'estado' => true,
                        'estado_vigencia' => 'VIGENTE',
                        'id_tipo_asegurado' => 1,
                        'fecha_inicio' => Carbon::now()->format('Y-m-d'),
                        'id_persona' => $personaId,
                        'id_corresponde' => $titular->id,
                        'id_user_created' => auth()->user()->id,
                    ]);
                }

                // FIN TABLA DE HISTORIAL ASEGURADO


                // ASUSS - INSERCIÓN DE DATOS 
                // $insercion = new TitularesAsuss();
                // $insercionAsuss = $insercion->insercionTitularAsuss($id_titular_b, $imageBase64);

                // $titularCodigoAsuss = Titular::find($id_titular_b);
                // //dd($insercionAsuss);

                // if ($insercionAsuss != []) {
                //    //dd($insercionAsuss, 'if');
                //    $titularCodigoAsuss->codigo_respuesta_asuss = $insercionAsuss[0]['estado_almacenamiento']['codigo'];
                // } else {
                //    //dd($insercionAsuss, 'else');
                //    $titularCodigoAsuss->codigo_respuesta_asuss = 504; //Error, el Asuss devuelve un array vacio
                // }
                // $titularCodigoAsuss->save();

                //------------------------------------------------------- NUEVA INSERCION DE DATOS ASUSS -------------------------------------------------------  

                /*$servicioLoginAsuss = new LoginAsussService();

                $servicioAsussTitular = new AfiliacionTitularAsussService($servicioLoginAsuss);*/

                $arrayDatosOriginales = [
                    'codigo_entidad' => '',
                    'fecha_ingreso_trabajo' => $this->tipo_asegurado ? null : $this->instituciones[0]['fecha_ingreso'],
                    'puesto_empleado' => $this->tipo_asegurado ? null : $this->instituciones[0]['cargo'],
                    'salario' => $this->tipo_asegurado ? null : $this->instituciones[0]['salario'],
                    'departamento' => 'LA PAZ',
                    'municipio' => 'NUESTRA SEÑORA DE LA PAZ',
                    'fecha_inicio' => Carbon::parse($this->fecha_afiliacion_real)->format('Y-m-d'),
                    'tipo_asegurado' => 'TRABAJADOR',
                    'matricula' => $this->matricula,
                    'tipo_documento' => 'CI',
                    'numero_documento' => implode('-', array_filter([$this->ci, $this->complemento])),
                    'fecha_nacimiento' => $this->fecha_nacimiento,
                    'genero' => $this->sexo,
                    'estado_civil' => $this->estado_civil,
                    'foto_persona' => $imageBase64 ?? '',
                    'id_municipio_nacimiento_persona' => $this->mun_nacimiento,
                    'id_persona' => $personaId,
                    'id_tipo_asegurado' => 1,
                    'id_corresponde' => $titular->id,
                    'id_titular' => null,
                    'id_institucion' => $this->tipo_asegurado ? null : $this->instituciones[0]['id_institucion'],
                    'id_user_created' => $titular->id_user_created,
                ];

                //dd($arrayDatosOriginales);

                //$servicioAsussTitular->afiliarTitularAsuss($arrayDatosOriginales);

                //ProcesarAfiliacionTitularAsussJob::dispatch($arrayDatosOriginales);  ---------------------------------------------- DESCOMENTAR PARA QUE FUNCIONE EL ASUSS

                //------------------------------------------------------- FIN NUEVA INSERCION DE DATOS ASUSS -------------------------------------------------------  



                // creacion de usuario
                $usuario = User::where('id_persona', $personaId)->count();
                $persona_encontrada = Persona::find($personaId);
                //dd($persona_encontrada);
                //
                $this->usuario = $this->GenerarUsuario($persona_encontrada);
                //   dd($this->usuario,$usuario);
                if ($usuario == 0) {
                    $numeroInvertido = (int)strrev((string)$personaId);
                    $resultado = $numeroInvertido + 3;
                    $user = User::create([
                        'name' => $persona_encontrada->nombres . ' ' . $persona_encontrada->p_apellido . ' ' . $persona_encontrada->s_apellido,
                        'email' => $this->usuario . '-' . $resultado,
                        'id_persona' => $personaId,
                        'id_user_created' => auth()->user()->id,
                        'estado' => true,
                        'password' => Hash::make($persona_encontrada->ci),
                    ]);
                } else {
                    $user = User::where('id_persona', $personaId)->first();
                }
                $nombre_foto = $user->id . '-U.png';
                $user->update([
                    'imagen' => $nombre_foto
                ]);
                //asignacion de rol
                $user->assignRole('Asegurado Afiliado');
                $user->syncRoles(['Asegurado Afiliado']);
                //
                $imagePath = $this->foto->getRealPath();
                $imageData = file_get_contents($imagePath);
                $this->foto->storeAs('Administracion/Usuarios/Perfil', $nombre_foto, 'ftp');

                $this->js('window.open("/Administracion/usuario/nuevo_afiliado/' . $id_titular_b . '", "_blank")');
                // fin creacion de usuarios
                //  aqui empieza notificacion 
                $nuevaNotificacion = NotificacionUsuarios::create([
                    'estado' => 'NO LEIDO',
                    'prioridades' => 3,
                    'id_usu_emisor' => auth()->user()->id,
                    'id_usu_receptor' => $user->id, // para que sea a los encargados de aportes
                    'fecha_hora_enviada' => Carbon::now(),
                    'asunto' => 'AFILIACIÓN - REGISTRO DE ASEGURADO',
                    'mensaje' => 'Bienvenido al Sistema del Seguro Social Universitario, su registro fue realizado con éxito',
                ]);
                //  fin de  notificacion 
                // ! integracion del servicio de replica en el gehma2
                // dd($resputestaReplica);
                // $this->dispatch('pruebaPasarDatos', 'datos que vienen desde titulares nuevo');
                // dd($resputestaReplica);
                // $this->respuestaGehma2 = $insercionGehma;
                DB::commit();
                // TODO: comentado por errores de afiliacion (bladimir) 24/03/2025
                $insercionGehma = new TitularesGehma2();
                $resputestaReplica = $insercionGehma->insercionTitularGehma2($titular->id);
                $this->dispatch('pg:eventRefresh-TitularesInstitucionalesVoluntarios');
                $this->dispatch('TitularRegistradoExito');

                return redirect()->route('afiliacion.titulares.index');
            } else {
                DB::rollBack();
                return redirect()->route('afiliacion.titulares.index')->with('evento', 'TitularDuplicado');
            }

            //else {
            // dd("duplicidad");
            //throw new \Exception('Error duplicidad.');
            //}
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->cargando = 0;


            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error en Guardar Institucion", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);

            return redirect()
                ->route('afiliacion.titulares.index')
                ->with('evento', 'TitularNoRegistrado');
        }
    }

    public function GenerarUsuario($persona_encontrada)
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

    public function mostrarDatosPersona($idPersona)
    {
        if ($idPersona) {
            $this->titularExiste = 0;
            //dd('entra aqui pana?');
            $persona = Persona::find($idPersona);
            //dd($persona);
            $this->nombre = $persona->nombres;
            $this->ap_paterno = $persona->p_apellido;
            $this->ap_materno = $persona->s_apellido;
            $this->ci = $persona->ci;
            $this->complemento = $persona->complemento;
            $this->fecha_nacimiento = $persona->fecha_nacimiento;
            $this->sexo = $persona->sexo;
            if ($persona->es_extranjero) {
                $this->tipo_doc = 'true';
                // $this->tipo_doc_1 = 'true';
            } else {
                $this->tipo_doc = 'false';
                //$this->tipo_doc_1 = 'false';
            }

            //  $this->tipo_doc = $persona->es_extranjero;
            $this->estado_api = 2;
            //dd($persona);
        }
    }

    #[On('search_persona')]
    public function search_persona()
    {
        // aqui elimino espacio en caso de poner ej: 1 1 1 
        $this->carnetBusqueda = preg_replace('/\s+/', '', $this->carnetBusqueda);
        if (!$this->validarCarnet($this->carnetBusqueda)) {
            $this->dispatch('ciInvalido');
            return;
        }

        //public $titularExiste;
        try {
            if ($this->complementoBusqueda == '') {
                $this->complementoBusqueda = null;
            }
            if ($this->complementoBusqueda !== null) {
                $datosPersonaBd = Persona::where('ci', $this->carnetBusqueda)
                    ->where('complemento', $this->complementoBusqueda)
                    ->first();
                //dd($datosPersonaBd);
                //dd('entra');
            } else {
                //dd(' o aqui entra hay mas');
                $personas = Persona::where('ci', $this->carnetBusqueda)->get();
                // dd($personas);
                if (count($personas) == 1) {
                    $datosPersonaBd = $personas->first();
                    //dd($datosPersonaBd);
                } else {
                    $personaSinComplemento = $personas->whereNull('complemento');
                    // dd($personaSinComplemento);
                    //dd('es mas de 1');
                    $datosPersonaBd = $personaSinComplemento->first();
                    // dd($datosPersonaBd);
                }
            }

            if ($datosPersonaBd) {
                //if ($datosPersonaBd->estado_asuss == true) {
                $existeComoTitularNoVigente = Titular::where('id_persona', '=', $datosPersonaBd->id)
                    ->where('estado_vigencia', 'NO VIGENTE')
                    ->where('estado', true)
                    ->first();
                if (!$existeComoTitularNoVigente) {
                    $existeComoTitular = Titular::where('id_persona', '=', $datosPersonaBd->id)
                        ->whereIn('estado_vigencia', ['VIGENTE', 'CESANTE'])
                        ->where('estado', true)
                        ->first();
                    if (!$existeComoTitular) {
                        $existeComoBeneficiario = Beneficiario::where('id_persona', $datosPersonaBd->id)
                            ->whereIn('vigencia', ['VIGENTE', 'CESANTE'])
                            ->where('estado', true)
                            ->first();

                        if (!$existeComoBeneficiario) {
                            $existeComoEstudiante = Estudiante::where('id_persona', $datosPersonaBd->id)
                                ->where('estado_vigencia', true)
                                ->first();

                            if (!$existeComoEstudiante) {
                                $this->estado_api = 2;
                                $this->encontrado = true;
                                $this->ci = $datosPersonaBd->ci;
                                $this->complemento = $datosPersonaBd->complemento;
                                $this->ap_paterno = $datosPersonaBd->p_apellido;
                                $this->ap_materno = $datosPersonaBd->s_apellido;
                                $this->nombre = $datosPersonaBd->nombres;

                                //para verificar si tenia o no antes
                                $this->fecha_nacimientoSwitch = $datosPersonaBd->fecha_nacimiento ? true : false;
                                $this->fecha_nacimiento = $datosPersonaBd->fecha_nacimiento;

                                if ($datosPersonaBd->sexo == 'F') {
                                    $this->sexo = true;
                                } elseif ($datosPersonaBd->sexo == 'M') {
                                    $this->sexo = false;
                                } elseif ($datosPersonaBd->sexo == null) {
                                    $this->sexo = false;
                                } else {
                                    $this->sexo = false;
                                }
                                //dd($this->sexoBen);
                                //DATOS DEL FORMULARIO QUE PODRIAN O NO SER NULOS
                                if (is_null($datosPersonaBd->es_extranjero)) {
                                    $this->tipo_docSwitch = false;
                                } else {
                                    $this->tipo_docSwitch = true;
                                }
                                if ($datosPersonaBd->es_extranjero) {
                                    $this->tipo_doc = 'true';
                                } else {
                                    $this->tipo_doc = 'false';
                                }
                                // $this->tipo_doc = $datosPersonaBd->es_extranjero;
                                $this->expedido_ciSwitch = $datosPersonaBd->id_dept_exp ? true : false;
                                $this->expedido_ci = $datosPersonaBd->id_dept_exp;
                                $this->matriculaSwitch = $datosPersonaBd->matricula_seguro ? true : false;
                                $this->matricula = $datosPersonaBd->matricula_seguro;
                                if ($datosPersonaBd->id_nacimiento_municipio) {
                                    $this->datosLugarNacimiento = true;
                                    $this->mun_nacimiento = $datosPersonaBd->id_nacimiento_municipio;
                                    $mun = Municipio::find($datosPersonaBd->id_nacimiento_municipio);
                                    $this->mun_nacimiento_nombre = $mun->nombre;
                                    $this->pro_nacimiento = $mun->id_provincia;
                                    $prov = Provincia::find($mun->id_provincia);
                                    $this->pro_nacimiento_nombre = $prov->nombre;
                                    $this->dep_nacimiento = $prov->id_departamento;
                                    $dept = Departamento::find($prov->id_departamento);
                                    $this->dep_nacimiento_nombre = $dept->nombre;
                                    //dd($this->mun_nacimiento, $this->pro_nacimiento, $this->dep_nacimiento);
                                }
                                $this->estado_civilSwitch = $datosPersonaBd->id_estado_civil ? true : false;
                                $this->estado_civil = $datosPersonaBd->id_estado_civil;
                            } else {
                                $this->estado_api = 3; // es estudiante
                            }
                        } else {
                            $this->estado_api = 4; // es beneficiario
                        }
                    } else {
                        $this->estado_api = 5; // es titular
                    }
                } else {
                    $this->estado_api = 6; // es titular no vigente
                }
                // } else {
                //     $this->encontrarSegip();
                // }
            } else {
                //$this->titularExiste = 0;
                $this->encontrarSegip();
            }
            $this->dispatch('recibirSelectDos');
        } catch (\Exception $e) {
            $this->encontrado = false;
            $this->estado_api = 0;
            $this->nombre = null;
            $this->ap_paterno = null;
            $this->ap_materno = null;
            $this->fecha_nacimiento = null;
            $this->ci = null;
            $this->complemento = null;
            $this->fecha_nacimientoSwitch = false;
            $this->tipo_docSwitch = false;
            $this->expedido_ciSwitch = false;
            $this->matriculaSwitch = false;
            $this->estado_civilSwitch = false;
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error buscar persona titular", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
        //$this->esperandoDatos = false;
        //  dd($this->tipo_doc);
    }

    public function encontrarSegip()
    {
        $respuesta = new ValidarPersona();
        $dato = $respuesta->buscarPersona($this->carnetBusqueda, $this->complementoBusqueda);

        if (isset($dato['datosPersonaEnFormatoJson'])) {
            //$this->titularExiste = 0;
            $this->estado_api = 1;
            $this->encontrado = true;
            $this->ci = $dato['datosPersonaEnFormatoJson']['numeroDocumento'];
            $this->complemento = $dato['datosPersonaEnFormatoJson']['Complemento'];
            $this->ap_paterno = $dato['datosPersonaEnFormatoJson']['primerApellido'];
            $this->ap_materno = $dato['datosPersonaEnFormatoJson']['segundoApellido'];
            $this->nombre = $dato['datosPersonaEnFormatoJson']['nombres'];
            $this->fecha_nacimiento = $dato['datosPersonaEnFormatoJson']['fechaNacimiento'];
            $this->fecha_nacimientoSwitch = true;
            $this->tipo_docSwitch = false;
            $this->expedido_ciSwitch = false;
            $this->matriculaSwitch = false;
            $this->estado_civilSwitch = false;
        } else {
            //$this->titularExiste = 0;
            $this->encontrado = false;
            $this->estado_api = 0;
            $this->nombre = null;
            $this->ap_paterno = null;
            $this->ap_materno = null;
            $this->fecha_nacimiento = null;
            $this->ci = $this->carnetBusqueda;
            $this->complemento = $this->complementoBusqueda;
            $this->fecha_nacimientoSwitch = false;
            $this->tipo_docSwitch = false;
            $this->expedido_ciSwitch = false;
            $this->matriculaSwitch = false;
            $this->matricula = null;
            $this->estado_civilSwitch = false;
            $this->datosLugarNacimiento = false;
            $this->dep_nacimiento = null;
        }
    }

    private function validarCarnet($carnet)
    {
        // Eliminar espacios en blanco al inicio y al final
        $carnet = trim($carnet);
        if (
            empty($carnet) || // 1. No debe estar vacío.
            preg_match('/^0+$/', $carnet) ||  // 2. No debe contener solo ceros.
            !preg_match('/^\d+$/', $carnet) || // 3. Debe ser completamente numérico.
            strlen($carnet) < 4 // 4. Debe tener al menos 4 caracteres.
        ) {
            return false; // No válido
        }
        return true; // Válido
    }


    public function OcultarAgregar()
    {
        //dd($this->tipo_asegurado);
        if ($this->tipo_asegurado == true) {
            $this->tipo = 1;
        } else {
            $this->tipo = 0;
        }
        //return $this->tipo_asegurado;
    }
    // esto es para ver los aportes en funcion
    public function verAportesIns()
    {

        $this->dispatch('setPersonaId', $this->idPersona);
        $this->dispatch('modalVerAportesIns');
        //$this->skipRender();
    }

    public function verAportesVol()
    {
        $this->dispatch('setPersonaId', $this->idPersona);
        $this->dispatch('modalVerAportesVol');
        //$this->skipRender();
    }

    // boton cancelar
    public function cancelarTitular()
    {
        $this->dispatch('titularCancelado');
        return redirect()->route('afiliacion.titulares.index');
    }
    public function pasar_replicas()
    {
        $this->dispatch('pruebaPasarDatos', 'datos que vienen desde titulares nuevo');
    }
    public function verRespuestas()
    {

        dd($this->respuestaGehma2);
    }
}
