<?php

namespace App\Livewire\Afiliacion\Beneficiarios;

use App\Jobs\ProcesarAfiliacionBeneficiarioAsussJob;
use Livewire\Component;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\Estudiante;
use App\Models\Afiliacion\TitularInstitucion;
use App\Models\Afiliacion\CodigoInstitucionSsu;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\BeneficiarioInstitucion;
use App\Models\Aportes\Institucion;
use App\Models\Administracion\Persona;
use App\Models\Administracion\Zona;
use App\Models\Administracion\Residencia;
use App\Models\Administracion\TipoAsegurado;
use App\Models\Administracion\Departamento;
use App\Models\Administracion\Provincia;
use App\Models\Administracion\Municipio;
use App\Models\Administracion\EstadoCivil;
use Livewire\Attributes\Locked;
use App\Services\BeneficiariosAsuss;
use App\Models\Administracion\NotificacionUsuarios;
use App\Models\Vigencia\HistorialAsegurado;
use App\Services\BeneficiariosGehma2;
use Carbon\Carbon;
use App\Services\ValidarPersona;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\Afiliacion\AfiliacionBeneficiariosMail;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Services\InteroperabilidadAsuss\AfiliacionBeneficiarioAsussService;
use App\Services\InteroperabilidadAsuss\LoginAsussService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BeneficiarioNuevo extends Component
{
    use WithFileUploads;
    #[Validate]
    public $foto;

    public $idTitular;
    public $nombres;
    public $pApellido;
    public $sApellido;
    public $nombreCompleto;
    public $ci;
    public $complemento;
    public $nombreInstitucion;
    public $codigoTitular;
    public $codigoBeneficiario;
    public $cargo;
    public $salario;
    public $numero_item;
    public $carga_horaria = "";
    public $titularidad = "";
    public $encontrado;
    public $foto_titular_mostrar;
    public $foto_titular;
    public $datosPersona;
    public $buscarCarnet;
    public $buscarComplemento;
    #[Validate]
    public $nombresBen, $pApellidoBen, $sApellidoBen;
    #[Validate]
    public $ciBen;
    public $complementoBen;
    #[Validate]
    public $fechaNacimientoBen;
    public $edad;
    public $sexoBen = false;
    public $estado_asuss;

    public $id_estado_civil;
    #[Validate]
    public $estado_civil;
    #[Validate]
    public $matriculaBen;
    public $busqueda;
    //Datos Referenciales Beneficiario
    public $fecha_afiliacion;
    public $fecha_reafiliacion;
    public $fecha_reafiliacion_n;
    public $grupo_sanguineo;
    public $alergias;
    public $tieneSangre = false;

    #[Validate]
    public $foto_nombre;
    public $observacion;
    public $vigencia = 'VIGENTE';
    #[Validate]
    public $telefono_referencia;
    #[Validate]
    public $nombre_referencia;

    public $id_titular; // ya se tiene
    //public $id_persona; //ya se tiene
    #[Validate]
    public $telefono;
    #[Validate]
    public $correo;
    public $preafiliacion = false;
    public $estado = true;
    //public $estado_requisitos = false;
    #[Validate]
    public $con_remuneracion = false;

    public $id_residencia;
    //#[Locked]
    #[Validate]
    public $id_tipo_asegurado;
    #[Validate]
    public $tipo_doc;
    #[Validate]
    public $expedido_ci;
    #[Validate]
    public $dep_nacimiento;
    public $dep_nacimiento_nombre;
    #[Validate]
    public $pro_nacimiento;
    public $pro_nacimiento_nombre;
    #[Validate]
    public $mun_nacimiento;
    public $mun_nacimiento_nombre;
    //Para la Residencia
    #[Validate]
    public $direccion;
    #[Validate]
    public $nro_vivienda;
    #[Validate]
    public $id_zona;
    public $departamento = [];
    public $provincias = [];
    public $municipios = [];
    public $datosLugarNacimiento = false;
    //Para la institucion Beneficiario Institucion

    public $id_institucion;
    public $fecha_ingreso;
    public $cargoRemu;
    public $salarioRemu;
    #[Validate]
    public $file_digital, $color_titular;
    public $ubicacion_select = 0;
    public $latitud, $longitud;
    public $tipo_titutlar;
    public $matricula_titular, $titularPermanente;
    public $zonasSelect;
    public $institucionSelect;
    public $estadoCivilSelect;
    public $tipoBeneficiarioSelect;
    public $permanente = false;
    //ESTOY SON SWITCHS PARA CONTROLAR MEJOR SI ANTES EXISTIAN O NO DATOS
    public $fechaNacimientoBenSwitch;
    public $tipo_docSwitch;
    public $expedido_ciSwitch;
    public $matriculaBenSwitch;
    public $estado_civilSwitch;
    public $idPersona;
    public $usuario;
    public $idInstitucionParaAsuss = null;
    public $idPersonaRegistroAsuss;
    protected $listeners = ['registrarBeneficiario', 'recibirLonLat', 'validarBeneficiario'];

    public function rules()
    {
        return [
            'direccion' => 'required|min:5',
            'id_zona' => 'required',
            'nro_vivienda' => 'required|min:2',
            'telefono' => 'required|min:6',
            'nombre_referencia' => 'required|min:2',
            'telefono_referencia' => 'required|min:6',
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'file_digital' => 'required|mimes:pdf|max:50000',
            'tipo_doc' => 'required',
            // 'expedido_ci' => 'required',
            //  'dep_nacimiento' => 'required',
            //  'pro_nacimiento' => 'required',
            //  'mun_nacimiento' => 'required',
            'estado_civil' => 'required',
            'nombresBen' => 'required|min:1',
            'matriculaBen' => 'required',
            'fechaNacimientoBen' => 'required|date|after_or_equal:1900-01-01|before_or_equal:today',
            'id_tipo_asegurado' => 'required',
            //Beneficiario si es con remuneracion
            'id_institucion' => 'sometimes|required_if:con_remuneracion,true',
            'fecha_ingreso' => 'sometimes|required_if:con_remuneracion,true',
            'cargoRemu' => 'sometimes|required_if:con_remuneracion,true',
            'salarioRemu' => 'sometimes|required_if:con_remuneracion,true'

        ];
    }

    public function recibirLonLat($d_lat, $d_lng)
    {
        $this->latitud = $d_lat;
        $this->longitud = $d_lng;
        $this->ubicacion_select = 1;
    }

    public function atras()
    {
        return redirect()->route('afiliacion.beneficiario.index');
    }

    public function mount()
    {
        $this->buscarTitular();
        $this->zonasSelect = Zona::orderBy('nombre', 'asc')->get();
        $this->institucionSelect = Institucion::where('estado', true)
            ->orderBy('nombre', 'asc')->get();
        $this->departamento = Departamento::get();
        $this->estadoCivilSelect = EstadoCivil::all();
        $this->tipoBeneficiarioSelect = TipoAsegurado::where('tipo_asegurado', 'like', '%BENEFICIARIO%')->whereNot('tipo_asegurado', 'BENEFICIARIO DEL INTERIOR')->get();

        $this->fecha_afiliacion = Carbon::now()->format('d/m/Y');
        $this->id_estado_civil = EstadoCivil::all();
    }

    public function ObtenerProvincia()
    {
        $this->provincias = Provincia::where('id_departamento', '=', $this->dep_nacimiento)->get();
    }

    public function ObtenerMunicipio()
    {
        $this->municipios = Municipio::where('id_provincia', '=', $this->pro_nacimiento)->get();
    }

    private function obtenerImagenBase64($filename)
    {
        try {
            if ($filename) {
                $path = 'Afiliacion/FOTOS/' . $filename;
                $imageData = Storage::disk('ftp')->get($path);
                //dd($imageData);
                $foto_titular_mostrar = base64_encode($imageData);
                return $foto_titular_mostrar;
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function render()
    {
        return view('livewire.Afiliacion.beneficiarios.beneficiario-nuevo');
    }

    public function buscarTitular()
    {

        try {
            $titular = Titular::where('id', $this->idTitular)
                ->where('titular.estado', true)
                ->where('titular.estado_vigencia', '=', 'VIGENTE')->first();
            if ($titular->tipo_titular == true) {
                $this->tipo_titutlar = 'INSTITUCIONAL';
            } else {
                $this->tipo_titutlar = 'VOLUNTARIO';
            }
            $this->titularPermanente = $titular->permanente ? 'PERMANENTE' : 'EVENTUAL';
            // color de franja
            if ($this->tipo_titutlar == 'INSTITUCIONAL') {
                if ($titular->permanente) {
                    $this->color_titular = match ($titular->tipo_rentista) {
                        'TITULAR-SORTEO' => '#5d5757', // Rentista Sorteo 4
                        'TITULAR-RENTISTA' => '#e0241c', // Rentista Jubilado 3 rojo
                        default => '#e0241c', // Permanente titular 1
                    };
                } else {
                    $this->color_titular = match ($titular->tipo_rentista) {
                        'TITULAR-SORTEO' => '#5d5757', // Rentista Sorteo 4
                        'VIUDEDAD' => '#e0241c', // Viudedad 5
                        'ORFANDAD' => ($this->edad > 19 ? '#0b61b7' : '#e0241c'), // Orfandad Mayor (6) o Menor (7)
                        default => '#0b61b7', // Eventual 2 azul
                    };
                }
            } elseif ($this->tipo_titutlar == 'VOLUNTARIO') {
                $this->color_titular = '#0b61b7'; // Voluntario 8
            } else {
                $this->color_titular = '#c4dff3'; // No definido 0
            }

            // Paso 1 foto. obtener el filename de la foto titular
            $this->foto_titular_mostrar = $this->obtenerImagenBase64($titular->foto_nombre);
            $idPersona = $titular->id_persona;

            $this->idPersonaRegistroAsuss = $idPersona;

            $persona = Persona::find($idPersona);
            $this->nombres = $persona->nombres;
            $this->pApellido = $persona->p_apellido;
            $this->sApellido = $persona->s_apellido;

            if ($persona->p_apellido == null) {
                $this->nombreCompleto = $persona->s_apellido . ' ' . $persona->nombres;
            } elseif ($persona->s_apellido == null) {
                $this->nombreCompleto = $persona->p_apellido . ' ' . $persona->nombres;
            } else {
                $this->nombreCompleto = $persona->p_apellido . ' ' . $persona->s_apellido . ' ' . $persona->nombres;
            }

            $this->complemento = $persona->complemento;
            if ($this->complemento == null) {
                $this->ci = $persona->ci;
            } else {
                $this->ci = $persona->ci . ' - ' . $this->complemento;
            }

            $this->matricula_titular = $persona->matricula_seguro;
            // datos referenciales
            $referenciaTitular = Residencia::where('id', $titular->id_residencia)->first();
            $this->nro_vivienda = $referenciaTitular->nro_vivienda;
            $this->direccion = $referenciaTitular->direccion;
            $this->id_zona = $referenciaTitular->id_zona;
            // dd($this->id_zona);
            // DAOTS INSTITUCIONALES DEL TITULAR ---- 
            $titularInstitucion = TitularInstitucion::where('id_titular', $this->idTitular)
                //->where('tipo_institucion', 'PATRONAL')
                ->where('estado', true)
                ->orderBy('id', 'asc')
                ->first();
            //dd($titularInstitucion);
            if ($titularInstitucion) {
                // $this->tipoAporteTitular = 'INSTITUCIONAL';
                $this->cargo = $titularInstitucion->cargo_actual;
                $this->salario = $titularInstitucion->salario;
                $idInstiTit = $titularInstitucion->id_institucion;
                $this->idInstitucionParaAsuss = $idInstiTit;

                $institucionTit = Institucion::find($idInstiTit);
                // dd($institucionTit);
                if ($institucionTit) {
                    $idInsti = $institucionTit->id;
                    $this->nombreInstitucion = $institucionTit->nombre;
                    $codigo = CodigoInstitucionSsu::where('id_institucion', $idInsti)->first();
                    if ($codigo) {
                        $this->codigoTitular = $codigo->codigo . $codigo->tipo_titular;
                        $this->codigoBeneficiario = $codigo->codigo . $codigo->tipo_beneficiario;
                    } else {
                        $this->codigoTitular = '';
                        $this->codigoBeneficiario = '';
                    }
                }
            }
        } catch (\Throwable $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Afiliacion:error al buscar titular", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }

    public function buscarDatos()
    {
        // aqui validara asi ponga full espacios 
        if (empty(trim($this->buscarCarnet))) {
            $this->dispatch('ciInvalido');
            return;
        }
        // aqui elimino espacio en caso de poner ej: 1 1 1 
        $this->buscarCarnet = preg_replace('/\s+/', '', $this->buscarCarnet);

        $this->datosLugarNacimiento = false;
        if ($this->buscarComplemento == '') {
            $this->buscarComplemento = null;
        }
        if (!$this->validarCarnet($this->buscarCarnet)) {
            $this->dispatch('ciInvalido');
            return;
        }
        //dd($this->buscarCarnet, $this->buscarComplemento);
        try {
            $datosPersonaBd = Persona::where('ci', $this->buscarCarnet)->where('complemento', $this->buscarComplemento)->first();
            if ($datosPersonaBd) {
                $existeComoTitular = Titular::where('id_persona', $datosPersonaBd->id)
                    ->whereIn('estado_vigencia', ['VIGENTE', 'CESANTE'])
                    ->where('estado', true)
                    ->first();

                if (!$existeComoTitular) {
                    $existeComoBeneficiarioNoVigente = Beneficiario::where('id_persona', $datosPersonaBd->id)
                        ->whereIn('vigencia', ['NO VIGENTE'])
                        ->where('estado', true)
                        ->first();
                    if (!$existeComoBeneficiarioNoVigente) {
                        $existeComoBeneficiario = Beneficiario::where('id_persona', $datosPersonaBd->id)
                            ->whereIn('vigencia', ['VIGENTE', 'CESANTE'])
                            ->where('estado', true)
                            ->first();
                        if (!$existeComoBeneficiario) {
                            $existeComoEstudiante = Estudiante::where('id_persona', $datosPersonaBd->id)
                                ->where('estado_vigencia', true)
                                ->first();
                            if (!$existeComoEstudiante) {
                                $this->busqueda = true;
                                $this->idPersona = $datosPersonaBd->id;
                                $this->nombresBen = $datosPersonaBd->nombres;
                                $this->pApellidoBen = $datosPersonaBd->p_apellido;
                                $this->sApellidoBen = $datosPersonaBd->s_apellido;
                                //para verificar si tenia o no antes
                                $this->fechaNacimientoBenSwitch = $datosPersonaBd->fecha_nacimiento ? true : false;
                                $this->fechaNacimientoBen = $datosPersonaBd->fecha_nacimiento;
                                $this->complementoBen = $datosPersonaBd->complemento;
                                $this->ciBen = $datosPersonaBd->ci;
                                $this->encontrado = 1;
                                if ($datosPersonaBd->sexo == 'F') {
                                    $this->sexoBen = true;
                                } elseif ($datosPersonaBd->sexo == 'M') {
                                    $this->sexoBen = false;
                                } elseif ($datosPersonaBd->sexo == null) {
                                    $this->sexoBen = false;
                                } else {
                                    $this->sexoBen = false;
                                }
                                //dd($this->sexoBen);
                                //DATOS DEL FORMULARIO QUE PODRIAN O NO SER NULOS
                                if (is_null($datosPersonaBd->es_extranjero)) {
                                    $this->tipo_docSwitch = false;
                                } else {
                                    $this->tipo_docSwitch = true;
                                }
                                $this->tipo_doc = $datosPersonaBd->es_extranjero;

                                $this->expedido_ciSwitch = $datosPersonaBd->id_dept_exp ? true : false;

                                $this->expedido_ci = $datosPersonaBd->id_dept_exp;

                                $this->matriculaBenSwitch = $datosPersonaBd->matricula_seguro ? true : false;

                                $this->matriculaBen = $datosPersonaBd->matricula_seguro;
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
                                $this->encontrado = 2; // 2 es estudiante
                            }
                        } else {
                            $this->encontrado = 3; // 3 s beneficiario activo
                        }
                    } else {
                        $this->encontrado = 5; // es beneficairio no vigente
                    }
                } else {
                    $this->encontrado = 4; // 4 Es titular vigente
                }
            } else {
                $validacionAsuss = new ValidarPersona();
                $this->datosPersona = $validacionAsuss->buscarPersona($this->buscarCarnet, $this->buscarComplemento);
                //dd($this->datosPersona);
                if (isset($this->datosPersona['datosPersonaEnFormatoJson'])) {
                    $this->busqueda = true;
                    $this->nombresBen = $this->datosPersona['datosPersonaEnFormatoJson']['Nombres'];
                    $this->pApellidoBen = $this->datosPersona['datosPersonaEnFormatoJson']['PrimerApellido'];
                    $this->sApellidoBen = $this->datosPersona['datosPersonaEnFormatoJson']['SegundoApellido'];
                    $this->fechaNacimientoBen = $this->datosPersona['datosPersonaEnFormatoJson']['FechaNacimiento'];
                    $this->complementoBen = $this->datosPersona['datosPersonaEnFormatoJson']['complemento'];
                    $this->ciBen = $this->datosPersona['datosPersonaEnFormatoJson']['numeroDocumento'];
                    $this->fechaNacimientoBenSwitch = false;
                    $this->tipo_docSwitch = false;
                    $this->expedido_ciSwitch = false;
                    $this->matriculaBenSwitch = false;
                    $this->estado_civilSwitch = false;
                    $this->estado_asuss = 1;
                    $this->encontrado = 1;
                } else {
                    //dd('entra aqui');

                    $this->fechaNacimientoBenSwitch = false;
                    $this->tipo_docSwitch = false;
                    $this->expedido_ciSwitch = false;
                    $this->matriculaBenSwitch = false;
                    $this->estado_civilSwitch = false;

                    $this->busqueda = false;
                    $this->nombresBen = null;
                    $this->pApellidoBen = null;
                    $this->sApellidoBen = null;
                    $this->fechaNacimientoBen = null;
                    $this->ciBen = $this->buscarCarnet;
                    $this->complementoBen = $this->buscarComplemento;
                    $this->encontrado = 0;
                    $this->matriculaBen = null;
                    $this->dep_nacimiento = null;
                }
            }

            if ($this->fechaNacimientoBen != null && $this->nombresBen != null && $this->pApellidoBen != null && $this->sApellidoBen != null) {
                $this->GenerarMatricula();
            }
            $this->dispatch('recibirSelectDos');
        } catch (\Throwable $e) {
            $this->fechaNacimientoBenSwitch = false;
            $this->tipo_docSwitch = false;
            $this->expedido_ciSwitch = false;
            $this->matriculaBenSwitch = false;
            $this->estado_civilSwitch = false;
            //dd($this->fechaNacimientoBen);
            $this->busqueda = false;
            $this->nombresBen = null;
            $this->pApellidoBen = null;
            $this->sApellidoBen = null;
            $this->fechaNacimientoBen = null;
            $this->ciBen = $this->buscarCarnet;
            $this->complementoBen = $this->buscarComplemento;
            $this->encontrado = 0;

            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Afiliacion:error al buscar datos", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
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
    public function cambioTipoAsegurado()
    {
        $this->validarTipoAseguradoBen();
    }
    public function validarTipoAseguradoBen()
    {
        $this->edad = Carbon::parse($this->fechaNacimientoBen)->age;
        $tipoAsegurado = $this->tipoBeneficiarioSelect->firstWhere('id', $this->id_tipo_asegurado)?->tipo_asegurado;
        $dispatchEvent = null;
        if ($tipoAsegurado === 'BENEFICIARIO HIJO MAYOR 19 AÑOS') {
            if ($this->edad < 19) {
                $dispatchEvent = 'beneficiarioMenor19Años';
            } elseif ($this->edad >= 25) {
                $dispatchEvent = 'beneficiarioMayor25Años';
            }
        } elseif ($tipoAsegurado === 'BENEFICIARIO HIJO MENOR 19 AÑOS') {
            if ($this->edad >= 19) {
                $dispatchEvent = 'beneficiarioMayor19años';
            }
        } elseif ($tipoAsegurado === 'BENEFICIARIO BEBE RECIÉN NACIDO') {
            if ($this->edad > 1) {
                $dispatchEvent = 'beneficiarioBebe';
            }
        } elseif ($tipoAsegurado === 'BENEFICIARIO MADRE - PADRE') {
            if ($this->edad <= 35) {
                $dispatchEvent = 'padreMadreMenor35años';
            }
        }
        if ($dispatchEvent) {
            $this->dispatch($dispatchEvent);
            $this->idPersona = null;
            DB::rollBack();
            return false;
        }
        return true;
    }
    public function registrarBeneficiario()
    {
        $this->validate();
        DB::beginTransaction();
        if (!$this->validarTipoAseguradoBen()) {
            return;
        }
        try {
            $this->ciBen = preg_replace('/\s+/', '', trim($this->ciBen));
            $this->complementoBen = preg_replace('/\s+/', '', trim($this->complementoBen));
            $this->complementoBen = $this->complementoBen ?: null;

            //dd($this->ciBen, $this->complementoBen);
            if ($this->idPersona) {
                $personaExiste = Persona::find($this->idPersona);
                if ($personaExiste) {
                    $personaExiste->update([
                        'fecha_nacimiento' => $this->fechaNacimientoBen,
                        'es_extranjero' => $this->tipo_doc,
                        'id_dept_exp' => $this->expedido_ci,
                        'sexo' => $this->sexoBen == true ? 'F' : 'M',
                        'matricula_seguro' => $this->matriculaBen,
                        'id_nacimiento_municipio' => $this->mun_nacimiento,
                        'id_estado_civil' => $this->estado_civil,
                        'id_tipo_asegurado' => $this->id_tipo_asegurado,
                        'afiliado' => true,
                        'alergias' => $this->alergias,
                        'grupo_sanguineo' => $this->grupo_sanguineo,
                        'id_user_updated' => auth()->user()->id,
                    ]);
                    if ($personaExiste->grupo_sanguineo) {
                        $this->tieneSangre = true;
                    }
                }
            } else {
                if ($this->complementoBen == null) {
                    $clave_unica = $this->ciBen;
                } else {
                    $clave_unica = $this->ciBen . $this->complementoBen;
                }
                $persona = Persona::create([
                    'ci' => $this->ciBen,
                    'complemento' => $this->complementoBen,
                    'nombres' => $this->nombresBen,
                    'p_apellido' => $this->pApellidoBen,
                    's_apellido' => $this->sApellidoBen,
                    'fecha_nacimiento' => $this->fechaNacimientoBen,
                    'sexo' => $this->sexoBen == true ? 'F' : 'M',
                    'estado_asuss' => $this->estado_asuss,
                    'matricula_seguro' => $this->matriculaBen,
                    'alergias' => $this->alergias,
                    'afiliado' => true,
                    'id_estado_civil' => $this->estado_civil,
                    'id_tipo_asegurado' => $this->id_tipo_asegurado,
                    'es_extranjero' => $this->tipo_doc,
                    'id_dept_exp' => $this->expedido_ci,
                    'id_nacimiento_municipio' => $this->mun_nacimiento,
                    'clave_unica' => $clave_unica,
                    'grupo_sanguineo' => $this->grupo_sanguineo,
                    'id_user_created' => auth()->user()->id,
                ]);
                if ($persona->grupo_sanguineo) {
                    $this->tieneSangre = true;
                }
                $this->idPersona = $persona->id;
            }
            $existeResidencia = Residencia::where('direccion', $this->direccion)
                ->where('nro_vivienda', $this->nro_vivienda)
                ->where('id_zona', $this->id_zona)
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
                    'id_zona' => $this->id_zona,
                    'latitud' => $this->latitud,
                    'longitud' => $this->longitud,
                    'id_user_created' => auth()->user()->id,
                ]);
                $residenciaId = $residencia->id;
            }

            $validacion_beneficiario = Beneficiario::where('id_persona', $this->idPersona)
                ->whereIn('vigencia', ['VIGENTE', 'CESANTE'])
                ->where('estado', true)
                ->first();

            if ($validacion_beneficiario == null) {
                $beneficiario = Beneficiario::create([
                    'fecha_afiliacion' => Carbon::now(),
                    'foto_nombre' => $this->foto_nombre,
                    'observacion' => $this->observacion,
                    'vigencia' => $this->vigencia,
                    'telefono_referencia' => $this->telefono_referencia,
                    'nombre_referencia' => $this->nombre_referencia,
                    'id_titular' => $this->idTitular,
                    'id_persona' => $this->idPersona,
                    'telefono' => $this->telefono,
                    'correo' => $this->correo,
                    'preafiliacion' => $this->preafiliacion,
                    'permanente' => $this->permanente,
                    'estado' => $this->estado,
                    'estado_requisitos' => $this->tieneSangre,
                    'con_remuneracion' => $this->con_remuneracion,
                    'id_residencia' => $residenciaId,
                    'id_user_created' => auth()->user()->id,
                ]);

                $beneficiarioId = $beneficiario->id;
                //PRUEBA SUBIR IMAGEN
                $nombre_foto = $beneficiario->id . '-B.png';
                //FOTO
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
                    } catch (\Exception $e) {
                        // Si rembg falla, usamos la imagen original
                        $imageWithoutBg = file_get_contents($imagePath);
                    }

                    // Codificamos para base64 si necesitas usarlo en otro lugar
                    $imageBase64_asuss = base64_encode($imageWithoutBg);
                    $imageBase64 = 'data:image/' . $this->foto->extension() . ';base64,' . base64_encode($imageWithoutBg);
                    // Guardamos en FTP
                    Storage::disk('ftp')->put('Afiliacion/FOTOS/' . $nombre_foto, $imageWithoutBg);
                }
                //
                $nombre_file = $beneficiario->id . '.pdf';
                $this->file_digital->storeAs('Afiliacion/FILES_PERSONALES/BENEFICIARIOS', $nombre_file, 'ftp');
                $beneficiario->update([
                    'foto_nombre' => $nombre_foto,
                ]);
                if ($this->con_remuneracion) {
                    $beneficiarioInstitucion = BeneficiarioInstitucion::create([
                        'id_beneficiario' => $beneficiarioId,
                        'id_institucion' => $this->id_institucion,
                        'id_titular' => $this->idTitular,
                        'fecha_ingreso' => $this->fecha_ingreso,
                        'cargo' => $this->cargoRemu,
                        'salario' => $this->salarioRemu,
                        'item' => $this->numero_item,
                        'carga_horaria' => $this->carga_horaria,
                        'titularidad' => $this->titularidad,
                        'estado' => true,
                        'id_user_created' => auth()->user()->id,
                    ]);
                }
                if ($beneficiario) {

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
                            'id_persona' => $this->idPersona,
                            'id_corresponde' => $beneficiario->id,
                            'id_user_created' => auth()->user()->id,
                        ]);
                    } else {
                        HistorialAsegurado::create([
                            'estado' => true,
                            'estado_vigencia' => 'VIGENTE',
                            'id_tipo_asegurado' => $this->id_tipo_asegurado,
                            'fecha_inicio' => Carbon::now()->format('Y-m-d'),
                            'id_persona' => $this->idPersona,
                            'id_corresponde' => $beneficiario->id,
                            'id_user_created' => auth()->user()->id,
                        ]);
                    }

                    // FIN TABLA DE HISTORIAL ASEGURADO
                    //Insercion de Datos al Asuss

                    //ASUSS -INSERSION BENEFICIARIOS
                    //$insercion = new BeneficiariosAsuss();
                    //$insercionBeneficiario = $insercion->insercionBeneficiarioAsuss(
                    //    $this->idTitular,
                    //    $this->ciBen,
                    //    $this->complementoBen,
                    //    $this->fechaNacimientoBen,
                    //    $this->id_tipo_asegurado,
                    //    $this->fecha_afiliacion
                    //);
                    //
                    //$ben = Beneficiario::find($beneficiarioId);
                    //
                    //if($insercion != []){
                    //    //dd($insercion, 'if');
                    //    $ben->codigo_respuesta_asuss = $insercionBeneficiario[0]['estado_almacenamiento']['codigo'];
                    //}else{
                    //    //dd($insercion, 'else');
                    //    $ben->codigo_respuesta_asuss = 504;
                    //}
                    //$ben->save();


                    // creacion de usuario

                    $usuario = User::where('id_persona', $this->idPersona)->count();
                    $persona_encontrada = Persona::find($this->idPersona);
                    //dd($persona_encontrada);
                    //
                    $this->usuario = $this->GenerarUsuario($persona_encontrada);
                    //   dd($this->usuario,$usuario);
                    if ($usuario == 0) {
                        $numeroInvertido = (int) strrev((string) $this->idPersona);
                        $resultado = $numeroInvertido + 3;
                        $user = User::create([
                            'name' => $persona_encontrada->nombres . ' ' . $persona_encontrada->p_apellido . ' ' . $persona_encontrada->s_apellido,
                            'email' => $this->usuario . '-' . $resultado,
                            'id_persona' => $this->idPersona,
                            'id_user_created' => auth()->user()->id,
                            'estado' => true,
                            'password' => Hash::make($persona_encontrada->ci),
                        ]);
                    } else {
                        $numeroInvertido = (int) strrev((string) $this->idPersona);
                        $resultado = $numeroInvertido + 3;
                        $user = User::where('id_persona', $this->idPersona)->first();
                        $user->update([
                            'email' => $this->usuario . '-' . $resultado,
                            'estado' => true
                        ]);
                    }

                    $nombre_foto = $user->id . '- U.jpg';
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
                    $this->js('window.open("/Administracion/usuario/nuevo/' . $user->id . '", "_blank")');
                    //fin usuario
                    //MAIL
                    $datos_beneficiario = Beneficiario::join('administracion.persona as per', 'per.id', '=', 'afiliacion.beneficiario.id_persona')
                        ->where('afiliacion.beneficiario.id', '=', $beneficiarioId)->first();

                    $asunto = "Sistema Sighov2 - Unidad de Afiliación";
                    $email = $this->correo;
                    $correo = new AfiliacionBeneficiariosMail($asunto, $datos_beneficiario, $imageBase64, $user->email, $persona_encontrada->ci);
                    try {
                        Mail::to($email)->send($correo);
                    } catch (\Exception $e) {
                        // Log del error (opcional)
                        Log::error('No se pudo enviar el correo: ' . $e->getMessage());
                    }
                    DB::commit();

                    //------------------------------------------------------- NUEVA INSERCION DE DATOS ASUSS -------------------------------------------------------  

                    /*$servicioLoginAsuss = new LoginAsussService();

                    $servicioAsussBenefciario = new AfiliacionBeneficiarioAsussService($servicioLoginAsuss);*/

                    $arrayDatosOriginales = [
                        'codigo_entidad' => '',
                        'fecha_inicio' => Carbon::parse($beneficiario->fecha_afiliacion)->format('Y-m-d'),
                        'tipo_asegurado' => 'TRABAJADOR',
                        'tipo_documento' => 'CI',
                        'numero_documento' => implode('-', array_filter([$this->ciBen, $this->complementoBen])),
                        'complemento_documento' => $this->complementoBen,
                        'fecha_nacimiento' => $this->fechaNacimientoBen,
                        'id_persona' => $this->idPersona,
                        'id_tipo_asegurado' => $this->id_tipo_asegurado,
                        'id_corresponde' => $beneficiarioId,
                        'id_titular' => $this->idTitular,
                        'id_persona_titular' => $this->idPersonaRegistroAsuss,
                        'id_institucion' => $this->idInstitucionParaAsuss,
                        'id_user_created' => $beneficiario->id_user_created,
                    ];

                    //dd($arrayDatosOriginales);

                    //$servicioAsussBenefciario->afiliarBeneficiarioAsuss($arrayDatosOriginales);

                    //ProcesarAfiliacionBeneficiarioAsussJob::dispatch($arrayDatosOriginales); ---------------------------------------------------------------------DESCOMENTAR PARA QUE EL ASUSS FUNCIONE

                    //------------------------------------------------------- FIN NUEVA INSERCION DE DATOS ASUSS -------------------------------------------------------



                    // TODO: comentado por errores de afiliacion (bladimir) 24/03/2025
                    $ins2 = new BeneficiariosGehma2();
                    $insercionBenGehma2 = $ins2->insercionBeneficiarioGehma2($beneficiario->id);
                    $this->dispatch('beneficiarioCreado');
                    //  NOTIFICACION PARA USUARIO BENEFICIARIO 30/01/25 (COMENTADO PORQUEE HAY ERROR AL MIGRAR LOS USUARIOS)
                    //  aqui empieza notificacion 
                    // $nuevaNotificacion = NotificacionUsuarios::create([
                    //     'estado' => 'NO LEIDO',
                    //     'prioridades' => 3,
                    //     'id_usu_emisor' => auth()->user()->id,
                    //     'id_usu_receptor' => $user->id,  
                    //     'fecha_hora_enviada' => Carbon::now(),
                    //     'asunto' => 'AFILIACIÓN - REGISTRO DE ASEGURADO BENEFICIARIO',
                    //     'mensaje' => 'Bienvenido al Sistema del Seguro Social Universitario, su registro fue realizado con éxito',
                    // ]);
                    //  fin de  notificacion 
                    //  NOTIFICACION PARA USUARIO TITULAR DEL BENEFICIARIO
                    //$tit = Titular::find($this->idTitular);
                    //$userTit = User::where('id_persona', $tit->id_persona)->first();
                    //$nombreBen = $persona_encontrada->nombres . ' ' . $persona_encontrada->p_apellido . ' ' . $persona_encontrada->s_apellido;
                    ////  aqui empieza notificacion 
                    //$nuevaNotificacion = NotificacionUsuarios::create([
                    //    'estado' => 'NO LEIDO',
                    //    'prioridades' => 3,
                    //    'id_usu_emisor' => auth()->user()->id,
                    //    'id_usu_receptor' => $userTit->id,
                    //    'fecha_hora_enviada' => Carbon::now(),
                    //    'asunto' => 'AFILIACIÓN - REGISTRO DE ASEGURADO BENEFICIARIO',
                    //    'mensaje' => 'Su beneficiario: ' . $nombreBen . ' fue registrado con éxito',
                    //]);
                    //  fin de  notificacion 


                    return redirect()->route('afiliacion.beneficiario.index');
                } else {
                    DB::rollBack();
                    $this->dispatch('beneficiarioCancelado');
                }
            } else {
                DB::rollBack();
                $this->dispatch('beneficiarioCancelado');
            }
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->dispatch('beneficiarioCancelado');
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Afiliacion:error al registrar beneficiario", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
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

    public function cancelarBen()
    {
        $this->dispatch('beneficiarioCancelado');

        return redirect()->route('afiliacion.beneficiario.index')->with('evento', 'beneficiarioCancelado');
    }

    public function validarFechaNacimiento()
    {
        $hoy = Carbon::now();

        if ($this->fechaNacimientoBen > $hoy->toDateString()) {
            $this->dispatch('FechaNacimientoInvalidaManana');
            $this->fechaNacimientoBen = null;
            return;
        }

        $this->GenerarMatricula();
    }
    public function GenerarMatricula()
    {
        $nombre = $this->nombresBen;
        $p_apellido = $this->pApellidoBen;
        $s_apellido = $this->sApellidoBen;
        $fecha_nacimiento = Carbon::parse($this->fechaNacimientoBen);
        $sexo = $this->sexoBen ? $this->sexoBen : false;
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

        $this->matriculaBen = $matricula;
    }
}
