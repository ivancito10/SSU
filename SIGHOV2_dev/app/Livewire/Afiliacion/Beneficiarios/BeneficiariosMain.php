<?php

namespace App\Livewire\Afiliacion\Beneficiarios;

use Livewire\Component;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\TitularInstitucion;
use App\Models\Afiliacion\DescargaFilePersonal;
use App\Models\Aportes\Institucion;
use App\Models\Administracion\Persona;
use App\Models\Administracion\Residencia;
use App\Models\Administracion\Zona;
use App\Models\Administracion\EstadoCivil;
use App\Models\Administracion\Departamento;
use App\Models\Administracion\Municipio;
use App\Models\Administracion\Provincia;
use App\Models\Administracion\TipoAsegurado;
use App\Models\Afiliacion\BeneficiarioInstitucion;
use App\Models\Afiliacion\CodigoInstitucionSsu;
use App\Models\Afiliacion\HCarnet;
use App\Models\Administracion\NotificacionUsuarios;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Services\Migracion\Afiliacion\MigrarBeneficiario;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BeneficiariosMain extends Component
{
    use WithFileUploads;
    public $foto, $fotito;
    public $foto_titular_mostrar;
    // datos personales
    public $idPersona;
    public $nombre;
    public $ci;
    public $complemento, $complementoBen;
    public $fecha_nacimiento;
    public $es_extranjero;
    public $extranjeroTexto;

    public $estado_civil;
    public $id_expedido_ci;
    public $expedido_ci;
    public $id_estado_civil;

    public $id_dep_nacimiento;
    public $dep_nacimiento;

    public $id_pro_nacimiento;
    public $pro_nacimiento;

    public $id_mun_nacimiento;
    public $mun_nacimiento;

    public $matricula;
    public $sexo;
    public $sexoTexto;
    public $con_remuneracion;

    public $tipoAsegurado;
    public $tipo_aseguradoBen;

    // archivo grupo
    public $archivo_grupo, $fecha_archivo;
    // datoa referenciales
    public $idBen;
    public $vigencia;
    public $sangre;
    public $grupo_sanguineo;
    public $fecha_afiliacion, $fecha_afiliacion_vista;
    public $fecha_reafiliacion;
    public $fecha_reafiliacion_r;
    public $estado_vigencia;
    public $estado_requisitos;
    public $idTit;
    public $idTitular;
    public $imageUrl;
    public $edad;
    public $beneficiario_data, $text_archivo_grupo;
    #[Validate]
    public $telefono, $correo, $nombre_referencia, $telefono_referencia;
    public $alergias;
    public $observacion;
    public $file_digital_sangre;
    //dator de direccion
    #[Validate]
    public $direccion, $zona, $zonaId, $nro_vivienda, $id_residencia;
    // datos perosnales de titular
    public $id_institucion;
    public $idInstitucion;
    public $nombreCompletoTitular;
    public $ciTitular, $matriculaTit;
    public $nombreInstitucion;
    public $vigenciaTitular;
    // datos institucionales de titular
    public $tipoAporteTitular;

    public $idInstiTit;
    public $cargoTit;
    public $salarioTit;
    public $nombreInstitucionTit, $titularPermanente;
    public $tipoBeneficiarioSelect;
    public $aseguradoId;
    public $codigoTitular;
    public $codigoBeneficiario;
    public $fechaUltimaEdicion, $institucionesTitular;
    // caso beneficiario con remuneracion   
    public $idBenIns;
    public $cargoBenIns;
    public $salarioBenIns;
    public $itemBenIns;
    public $cargaHorariaBenIns;
    public $titularidadBenIns;
    public $nombreBenIns;
    public $fechaIngresoBenIns, $fechaVencimientoCarnet, $color, $color_titular;

    public $latitud;
    public $longitud;
    public $d_latitud, $d_longitud;
    public $latitudRegistered;
    public $longitudRegistered;
    public $ubicacion_select = 0;
    public $ubicacionTexto = 'NO SELECCIONADO';
    public $motivo_carnet;
    public $fecha_emision_r, $fecha_vencimiento_r, $fecha_emision, $fecha_vencimiento;
    public $beneficiarioInstitucion = [];
    public $zonasSelect = [];
    public $indefinido = false;
    public $fecha_actual, $fecha_vencimiento_formatted, $fecha_emision_formatted, $foto_nombre;
    public $debeActualizarReferenciales = false, $debeActualizarResidencia = false;
    public $fecha_created, $user_created, $fecha_edicion, $user_edited, $tieneEdicion = false;
    public $tieneFileDigital = false;
    public $nro_recibo, $nro_recibo_reimpresion;
    protected $listeners = ['actualizarBeneficiario', 'recibirLonLat', 'descargarArchivo', 'AgregarTipoSangreBen'];

    public function rules()
    {
        return [
            'direccion' => 'required|min:5',
            'zonaId' => 'required',
            'nro_vivienda' => 'required|min:1',
            'telefono' => 'required|min:6',
            'correo' => 'required|email',
            'nombre_referencia' => 'required|min:2',
            'telefono_referencia' => 'required|min:6',

        ];
    }

    public function render()
    {
        //
        // $data=User::join('administracion.persona as p','p.id','=','administracion.users.id_persona')
        // ->where('administracion.users.id','=',auth()->user()->id)->first();
        // //dd($data);
        //$persona = Persona::all();
        //$residencia = Residencia::all();
        //$zonasSelect = Zona::all();
        //$tipoBeneficiarioSelect = TipoAsegurado::where('tipo_asegurado', 'like', '%BENEFICIARIO%')->get();
        //$datosBeneficiario =$this->mostrarBeneficiario($this->idBen);
        // Paso 2 foto. enviar el filename de la foto a una funcion 
        //$this->obtenerImagenBase64($this->foto);

        return view('livewire.Afiliacion.beneficiarios.beneficiarios-main');
    }

    public function mount()
    {
        //$migrarDatitos = new MigrarBeneficiario();
        //$migrarDatitos->migrarBeneficiario();


        //$verificar = new BeneficiariosAsuss();
        //$ver = $verificar->validarBeneficiariosAsuss(12);
        //dd($ver);
        // $ben = new BeneficiariosGehma2();
        // $inBen = $ben->insercionBeneficiarioGehma2();
        // dd($inBen);
        //$this->fecha_reafiliacion_r = now()->format('d-m-Y'); // Formato YYYY-MM-DD
        //$this->zonasSelect = Zona::all();
        //$this->tipoBeneficiarioSelect = TipoAsegurado::where('tipo_asegurado', 'like', '%BENEFICIARIO%')->get();
        // esta funcion actualiza a los bebes a ser menores de 19 años
        $this->actualizarBebes();
    }
    public function actualizarBebes()
    {
        $beneficiarios_bebes = [];
        $this->fecha_actual = Carbon::now()->subYear();

        $beneficiarios_bebes = Beneficiario::with('beneficiarioPersona')
            ->whereHas('beneficiarioPersona', function ($query) {
                $query->where('id_tipo_asegurado', 7)
                    ->whereDate('fecha_nacimiento', '<=', $this->fecha_actual);
            })
            ->where('estado', true)
            ->where('vigencia', '=', 'VIGENTE')
            ->get();
        // ahora se actualiza
        foreach ($beneficiarios_bebes as $beneficiario) {
            $beneficiario->beneficiarioPersona->update(['id_tipo_asegurado' => 4]);
        }
        // dd($beneficiarios_bebes);
    }

    public function reseteandoVarBen()
    {
        $this->reset(
            'institucionesTitular',
            'cargoTit',
            'salarioTit',
            'idInstiTit',
            'codigoTitular',
            'codigoBeneficiario',
            'user_created',
            'fecha_created',
            'user_edited',
            'fecha_edicion',
            'tieneEdicion'
        );
    }

    public function mostrarBeneficiario($idBen)
    {

        if ($idBen) {
            // DATOS PROPIOS DE BENEFICIARIO
            $beneficiario = Beneficiario::with('usuarioCreador', 'usuarioEditor')->find($idBen);
            //  dd($beneficiario);
            // archivoo
            // archivo grupo
            $this->archivo_grupo = $beneficiario->archivo_grupo ?? 'SIN DATO';
            $this->fecha_archivo = $beneficiario->fecha_archivo == null ? 'SIN DATO' : mb_strtoupper(Carbon::parse($beneficiario->fecha_archivo)->Format('d /m/ Y'));
            // -------
            $this->fecha_afiliacion = $beneficiario->fecha_afiliacion;
            //$this->fecha_afiliacion_vista = Carbon::parse($this->fecha_afiliacion)->Format('d /m/ Y ');
            $this->fecha_reafiliacion = $beneficiario->fecha_reafiliacion;
            $this->vigencia = $beneficiario->vigencia;
            $this->estado_requisitos = $beneficiario->estado_requisitos;
            $this->telefono = $beneficiario->telefono;
            $this->foto_nombre = $beneficiario->foto_nombre;
            $this->correo = $beneficiario->correo;
            $this->nombre_referencia = $beneficiario->nombre_referencia;
            $this->telefono_referencia = $beneficiario->telefono_referencia;
            $this->observacion = $beneficiario->observacion ? $beneficiario->observacion : 'NO CUENTA CON OBSERVACIONES';
            $this->idTit = $beneficiario->id_titular;
            $this->con_remuneracion = $beneficiario->con_remuneracion;
            //---- PARA VER DATOS DE CREACO Y EDICION
            $this->user_created = $beneficiario->usuarioCreador?->name ?: 'SIN DATO';
            $this->fecha_created = $beneficiario->created_at;
            if ($beneficiario->id_user_updated) {
                $this->tieneEdicion = true;
                $this->user_edited = $beneficiario->usuarioEditor?->name ?: 'SIN DATO';
                $this->fecha_edicion = $beneficiario->updated_at;
            }

            $fecha_ultima_modificacion = $beneficiario->updated_at;
            // dd( $fecha_ultima_modificacion);
            if ($fecha_ultima_modificacion) {
                $this->fechaUltimaEdicion = Carbon::parse($fecha_ultima_modificacion)->Format('d /m/ Y ');
            } else {
                $this->fechaUltimaEdicion = 'SIN DATO';
            }
            $this->foto = $this->obtenerImagenBase64($beneficiario->foto_nombre);
            //dd($beneficiario->foto_nombre);

            // DATOS DEL TITULAR
            $titular = Titular::find($this->idTit);
            $this->vigenciaTitular = $titular->estado_vigencia;
            $this->foto_titular_mostrar = $this->obtenerImagenBase64($titular->foto_nombre);
            $this->titularPermanente = $titular->permanente ? 'PERMANENTE' : 'EVENTUAL';
            $dataTitular = Persona::find($titular->id_persona);
            $this->matriculaTit = $dataTitular->matricula_seguro;
            if ($dataTitular->p_apellido == null) {
                $this->nombreCompletoTitular = $dataTitular->s_apellido . ' ' . $dataTitular->nombres;
            } elseif ($dataTitular->s_apellido == null) {
                $this->nombreCompletoTitular = $dataTitular->p_apellido . ' ' . $dataTitular->nombres;
            } else {
                $this->nombreCompletoTitular = $dataTitular->p_apellido . ' ' . $dataTitular->s_apellido . ' ' . $dataTitular->nombres;
            }
            if ($dataTitular->complemento == null) {
                $this->ciTitular = $dataTitular->ci;
            } else {
                $this->ciTitular = $dataTitular->ci . '-' . $dataTitular->complemento;
            }
            // DAOTS INSTITUCIONALES DEL TITULAR ---- 


            if ($titular->tipo_titular == true) {
                $this->tipoAporteTitular = 'INSTITUCIONAL';

                $this->institucionesTitular = TitularInstitucion::where('id_titular', $this->idTit)
                    ->with('titularInstitucionConInstitucion')
                    ->orderBy('id', 'asc')
                    ->get();
                // dd($instituciones);


                $titularInstitucion = TitularInstitucion::where('id_titular', $this->idTit)
                    //->where('tipo_institucion', 'PATRONAL')
                    ->where('estado', true)
                    ->orderBy('id', 'asc')
                    ->first();
                if ($titularInstitucion) {

                    $this->cargoTit = $titularInstitucion->cargo_actual;
                    $this->salarioTit = $titularInstitucion->salario;
                    $this->idInstiTit = $titularInstitucion->id_institucion;

                    $institucionTit = Institucion::find($this->idInstiTit);
                    if ($institucionTit) {
                        $idInsti = $institucionTit->id;
                        $this->nombreInstitucionTit = $institucionTit->nombre;
                        $codigo = CodigoInstitucionSsu::where('id_institucion', $idInsti)->first();
                        if ($codigo) {
                            $this->codigoTitular = $codigo->codigo . $codigo->tipo_titular;
                            $this->codigoBeneficiario = $codigo->codigo . $codigo->tipo_beneficiario;
                        } else {
                            $this->codigoTitular =  '';
                            $this->codigoBeneficiario =  '';
                        }
                    }
                }
            } else {
                $this->tipoAporteTitular = 'VOLUNTARIO';
                $this->reseteandoVarBen();
            }

            // DATOS PERSONALES DE BENEFICIARIOS
            $personaActual = Persona::find($beneficiario->id_persona);
            $this->idPersona = $personaActual->id;
            if ($personaActual->p_apellido == null) {
                $this->nombre = $personaActual->s_apellido . ' ' . $personaActual->nombres;
            } elseif ($personaActual->s_apellido == null) {
                $this->nombre = $personaActual->p_apellido . ' ' . $personaActual->nombres;
            } else {
                $this->nombre = $personaActual->p_apellido . ' ' . $personaActual->s_apellido . ' ' . $personaActual->nombres;
            }

            if ($personaActual->complemento == null) {
                $this->ci = $personaActual->ci;
            } else {
                $this->ci = $personaActual->ci . '-' . $personaActual->complemento;
            }
            $this->alergias = $personaActual->alergias ? $personaActual->alergias : 'NINGUNO';
            $this->grupo_sanguineo = $personaActual->grupo_sanguineo ? $personaActual->grupo_sanguineo : 'SIN GRUPO SANGUINEO';
            $this->fecha_nacimiento = $personaActual->fecha_nacimiento;
            $this->matricula = $personaActual->matricula_seguro;
            $this->sexo = $this->setSexoTexto($personaActual->sexo);
            $this->es_extranjero = $this->setExtranjeroTexto($personaActual->es_extranjero);
            $this->id_estado_civil = $personaActual->id_estado_civil;
            $this->edad = Carbon::parse($personaActual->fecha_nacimiento)->age;

            $estadoCivilPersona = EstadoCivil::find($personaActual->id_estado_civil);
            $this->estado_civil = $estadoCivilPersona->nombre;
            if ($personaActual->id_dept_exp) {
                $this->id_expedido_ci = $personaActual->id_dept_exp;
                $depExpedido = Departamento::find($this->id_expedido_ci);
                $this->expedido_ci = $depExpedido->sigla;
            } else {
                $this->expedido_ci = null;
            }
            $this->id_mun_nacimiento = $personaActual->id_nacimiento_municipio;
            if ($this->id_mun_nacimiento) {
                $munNacimiento = Municipio::find($this->id_mun_nacimiento);
                $this->mun_nacimiento = $munNacimiento->nombre;
                $this->id_pro_nacimiento = $munNacimiento->id_provincia;
                $provNacimiento = Provincia::find($this->id_pro_nacimiento);
                $this->pro_nacimiento = $provNacimiento->nombre;
                $this->id_dep_nacimiento = $provNacimiento->id_departamento;

                $depNacimiento = Departamento::find($this->id_dep_nacimiento);
                $this->dep_nacimiento = $depNacimiento->nombre;
            } else {
                $this->mun_nacimiento = 'No se encontraron datos';
                $this->pro_nacimiento = 'No se encontraron datos';
                $this->dep_nacimiento = 'No se encontraron datos';
            }


            $this->tipoAsegurado = $personaActual->id_tipo_asegurado;

            $aseguradoTipoActual = TipoAsegurado::find($personaActual->id_tipo_asegurado);
            $this->aseguradoId = $aseguradoTipoActual->id;
            $this->tipo_aseguradoBen = $aseguradoTipoActual->tipo_asegurado;

            $residenciaActual = Residencia::find($beneficiario->id_residencia);
            $this->direccion = $residenciaActual->direccion;
            $this->nro_vivienda = $residenciaActual->nro_vivienda;
            $this->latitudRegistered = $residenciaActual->latitud;
            $this->longitudRegistered = $residenciaActual->longitud;
            $this->d_latitud = $this->latitudRegistered;
            $this->d_longitud = $this->longitudRegistered;
            $zonaActual = Zona::find($residenciaActual->id_zona);
            $this->zona = $zonaActual->nombre;
            $this->zonaId = $zonaActual->id;

            $carnetBeneficiario = HCarnet::where('id_persona', $personaActual->id)
                ->orderBy('id', 'desc')
                ->first();
            if ($carnetBeneficiario) {
                $fechaVenimiento = $carnetBeneficiario->fecha_vencimiento;
                $this->fechaVencimientoCarnet = Carbon::parse($fechaVenimiento)->Format('d /m/ Y ');
            } else {
                $this->fechaVencimientoCarnet = 'SIN DATO';
            }

            //Establecer colores por tipo de beneficiario
            if ($this->tipoAporteTitular == 'INSTITUCIONAL') {
                // dd($titular);
                if ($titular->permanente) {

                    if ($titular->tipo_rentista == 'TITULAR-RENTISTA') {
                        if ($this->tipo_aseguradoBen == 'BENEFICIARIO ESPOSA - ESPOSO' || $this->tipo_aseguradoBen == 'BENEFICIARIO HIJO MENOR 19 AÑOS') {
                            $this->color = '#f5c71a'; // amarillo
                        } elseif ($this->tipo_aseguradoBen == 'BENEFICIARIO HIJO MAYOR 19 AÑOS') {
                            $this->color = '#0b61b7';
                        }
                    } elseif ($this->tipo_aseguradoBen == 'BENEFICIARIO HIJO MAYOR 19 AÑOS') {

                        $this->color = '#0b61b7'; //si es Titular Permanente ben hijo mayor a 19 años
                    } else {

                        $this->color = '#f5c71a'; //si es titular permamente  amarillo
                    }
                } else {
                    $this->color = '#0b61b7'; //Si es eventual
                }
            } elseif ($this->tipoAporteTitular == 'VOLUNTARIO') {
                $this->color = '#0b61b7'; // Voluntario
            } else {
                $this->color = 0; // No definido
            }


            if ($this->tipoAporteTitular == 'INSTITUCIONAL') {
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
            } elseif ($this->tipoAporteTitular == 'VOLUNTARIO') {
                $this->color_titular = '#0b61b7'; // Voluntario 8
            } else {
                $this->color_titular = '#c4dff3'; // No definido 0
            }

            // DATOS EN BENFICIARIO CON REMUNERACION ------
            $institucionBen = BeneficiarioInstitucion::where('id_beneficiario', $idBen)->first();
            if ($institucionBen) {
                $this->cargoBenIns = $institucionBen->cargo;
                $this->salarioBenIns = $institucionBen->salario;
                $this->fechaIngresoBenIns = $institucionBen->fecha_ingreso;
                $this->idBenIns = $institucionBen->id_institucion;
                $this->itemBenIns = $institucionBen->item;
                $this->cargaHorariaBenIns = $institucionBen->carga_horaria;
                $this->titularidadBenIns = $institucionBen->titularidad;

                $institucionBenIns = Institucion::find($this->idBenIns);
                if ($institucionBenIns) {
                    $this->nombreBenIns = $institucionBenIns->nombre;
                    if ($institucionBen) {
                        $this->beneficiarioInstitucion = $institucionBen;
                    } else {
                        $this->beneficiarioInstitucion = [];
                    }
                }
            } else {
                $this->reset(
                    'cargoBenIns',
                    'salarioBenIns',
                    'fechaIngresoBenIns',
                    'idBenIns',
                    'nombreBenIns',
                );
                $this->beneficiarioInstitucion = [];
            }
        }
    }
    // Funcion para obtener imagenes desde la FTP 
    private function obtenerImagenBase64($filename)
    {
        try {
            if ($filename) {
                $path = 'Afiliacion/FOTOS/' . $filename;
                $imageData = Storage::disk('ftp')->get($path);
                //dd($imageData);
                $fotito = base64_encode($imageData);
                return $fotito;
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            return null;
        }
    }

    // funciona para mandar el texto del sexo
    private function setSexoTexto($sexo)
    {
        if ($sexo == 'F') {
            return 'FEMENINO';
        } elseif ($sexo == 'M') {
            return 'MASCULINO';
        } else {
            return 'NO ESPECIFICADO';
        }
    }

    //PARA TRANFORMAR EL BOOLEANDO DE ES EXTRANJERO AJAJAJAJAJA
    private function setExtranjeroTexto($esExtranjero)
    {
        if ($esExtranjero == false) {
            return 'NACIONAL';
        } elseif ($esExtranjero == true) {
            return 'EXTRANJERO';
        } else {
            return 'NO ESPECIFICADO';
        }
    }
    // para la ubicacion
    public function recibirLonLat($d_lat, $d_lng)
    {
        $this->latitudRegistered = $d_lat;
        $this->longitudRegistered = $d_lng;
        $this->ubicacion_select = 1;
        $this->ubicacionTexto = 'SELECCIONADO';
        //en caso de OpenStreetMaps
        //$this->skipRender();
    }
    //MODAL PARA ABRIR LA UBICACION
    public function AbrirModalUbicacionBeneficiario()
    {
        $this->d_latitud = $this->latitudRegistered;
        $this->d_longitud = $this->longitudRegistered;
        $this->dispatch('ModalAbrirUbicacionBeneficiario', title: $this->d_latitud);
        //dd($this->latitudRegistered,$this->longitudRegistered);
        // $this->dispatch('inputUpdated', [$this->latitudRegistered, $this->longitudRegistered]);
        $this->skipRender();
    }
    // PARA ESCUCHAR MODALS ----
    #[On('tipoSangreBeneficiario')]
    public function tipoSangreBeneficiario($data)
    {
        $this->idBen = $data;
        $this->dispatch('tipoSangreBenModal');
    }

    public function AgregarTipoSangreBen()
    {
        try {
            $beneficiario_tipo_sangre = Beneficiario::find($this->idBen);
            $idPersona = $beneficiario_tipo_sangre->id_persona;
            $personaBen = Persona::find($idPersona);
            // if ($personaBen) {
            if (!empty($this->grupo_sanguineo)) {
                $personaBen->grupo_sanguineo = $this->grupo_sanguineo;
                $beneficiario_tipo_sangre->update([
                    'estado_requisitos' => true,
                ]);
                //$nombre_file_sangre = $beneficiario_tipo_sangre->id . '-B-SANGRE.pdf';
                //  $this->file_digital_sangre->storeAs('Afiliacion/TIPO_SANGRE/BENEFICIARIO/', $nombre_file_sangre, 'ftp');
                $personaBen->save();
                //inicio de NOTIFICACIONES 
                $user = User::where('id_persona', $idPersona)->first();
                $nuevaNotificacion = NotificacionUsuarios::create([
                    'estado' => 'NO LEIDO',
                    'prioridades' => 3,
                    'id_usu_emisor' => auth()->user()->id,
                    'id_usu_receptor' => $user->id, // 
                    'fecha_hora_enviada' => Carbon::now(),
                    'asunto' => 'AFILIACIÓN - GRUPO SANGUINEO',
                    'mensaje' => 'Su grupo sanguineo fue agregado exitosamente',
                ]);
                //  fin de  notificacion 
                $this->dispatch('TipoSangreBenGuardado');
                $this->dispatch('pg:eventRefresh-BeneficiariosTable');
            } else {
                $this->dispatch('TipoSangreBenNoGuardado');
            }
            //}
            $this->reset('grupo_sanguineo');
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('TipoSangreBenNoGuardado');
        }
    }

    #[On('verBeneficiario')]
    public function verBeneficiario($data)
    {
        $this->reseteandoVarBen();
        $this->mostrarBeneficiario($data);
        $this->idBen = $data;
        $this->buscarSiTieneFileDigital($this->idBen);
        $this->dispatch('modalVerBeneficiario');
    }

    #[On('editarBeneficiario')]
    public function editarBeneficiario($data)
    {
        $this->zonasSelect = Zona::all();
        $this->mostrarBeneficiario($data);
        $this->idBen = $data;
        $this->dispatch('modalEditarBeneficiario');
    }

    // Funcion para descargar file de beneficiario
    public function descargarArchivo($data)
    {
        DB::beginTransaction();
        //dd($data);
        try {
            $descarga = DescargaFilePersonal::create([
                'id_beneficiario' => $this->idBen,
                'motivo' => $data,
                'id_user_created' => auth()->user()->id,
            ]);
            DB::commit();
            $descargaId = $descarga->id;
            $path = 'Afiliacion/FILES_PERSONALES/BENEFICIARIOS/' . $this->idBen . '.pdf'; // Ajustamos la ruta del ftp para la descarga
            $name = $this->nombre . '-' . $this->fecha_afiliacion;
            if (!Storage::disk('ftp')->exists($path)) {
                abort(404);
                // $this->dispatch('archivoNoEncontrado');
            }
            //
            $file = Storage::disk('ftp')->download($path, $name);
            $this->dispatch('guardado');
            return $file;
        } catch (\Throwable $th) {
            DB::rollBack();
            return null;
        }
    }

    /*private function obtenerImagenBase64Titular($filename)
    {
        if ($filename) {
            $path = 'Afiliacion/FOTOS/' . $filename;
            $imageData = Storage::disk('ftp')->get($path);
            //dd($imageData);
            $this->foto_titular_mostrar = base64_encode($imageData);
        }

        return null;
    }*/

    public function crear_h_carnet()
    {
        // Validaciones previas
        if ($this->indefinido) {
            if (is_null($this->fecha_emision_r)) {
                $this->dispatch('indefinidoSinEmision');
                $this->resetFormCarnet();
                return; // Detener ejecución
            }
            if (!is_null($this->fecha_vencimiento_r)) {
                $this->dispatch('indefinidoConVencimiento');
                $this->resetFormCarnet();
                return; // Detener ejecución
            }
        } else {
            if (is_null($this->fecha_emision_r) || is_null($this->fecha_vencimiento_r)) {
                $this->dispatch('normalSinFechas');
                $this->resetFormCarnet();
                return;
            }

            if ($this->fecha_vencimiento_r <= $this->fecha_emision_r) {
                $this->dispatch('fechasInvalidas');
                $this->resetFormCarnet();
                return;
            }
        }

        // Intentar crear el carnet
        DB::beginTransaction();
        try {
            $r_carnet = HCarnet::create([
                'fecha_emision' => $this->fecha_emision_r,
                'fecha_vencimiento' => $this->fecha_vencimiento_r,
                'id_persona' => $this->idPersona,
                'indefinido' => $this->indefinido,
                'nro_recibo' => $this->nro_recibo,
                'motivo' => 'NUEVO CARNET',
                'id_user_created' => auth()->user()->id,
            ]);
            if (!$r_carnet) {
                DB::rollBack();
                $this->dispatch('errorCreacion'); // Notificar error
                $this->resetFormCarnet(); // Limpiar variables
                return;
            }
            DB::commit();
            $this->dispatch('RegistroCarnetCorrecto'); // Notificar éxito
            $this->js('window.open("/afiliacion/beneficiario/carnet_pdf/' . $this->idBen . '", "_blank")');
            $this->resetFormCarnet(); // Limpiar variables después de éxito

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('errorCreacion'); // Notificar error en frontend
            $this->resetFormCarnet(); // Limpiar variables
        }
    }
    public function resetFormCarnet()
    {
        $this->fecha_emision_r = null;
        $this->fecha_vencimiento_r = null;
        $this->indefinido = false; // O el valor predeterminado que necesitas
        $this->motivo_carnet = null;
        $this->nro_recibo = null;
        $this->nro_recibo_reimpresion = null;
    }


    public function recarnetizacion()
    {
        //$this->js('window.open("/afiliacion/beneficiario/carnet_pdf/' . $this->idBen . '", "_blank")');
        //$this->dispatch('RegistroCarnetCorrecto');

        DB::beginTransaction();
        try {
            if ($this->motivo_carnet != null) {
                $r_carnet = HCarnet::create([
                    'fecha_emision' => $this->fecha_emision_r,
                    'fecha_vencimiento' => $this->fecha_vencimiento,
                    'id_persona' => $this->idPersona,
                    'indefinido' => $this->indefinido,
                    'motivo' => $this->motivo_carnet,
                    'nro_recibo' => $this->nro_recibo_reimpresion,
                    'id_user_created' => auth()->user()->id,
                ]);
                if (!$r_carnet) {
                    DB::rollBack();
                    $this->dispatch('errorCreacion'); 
                    $this->resetFormCarnet(); 
                    return;
                }
                DB::commit();
                $this->dispatch('RegistroCarnetCorrecto'); 
                $this->js('window.open("/afiliacion/beneficiario/carnet_pdf/' . $this->idBen . '", "_blank")');
                $this->resetFormCarnet(); 
            } else {
                $this->dispatch('completaCamposReimpresion');
                return;
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('errorCreacion'); 
            $this->resetFormCarnet(); 
        }
    }
    public function verificarFotoTitular($data)
    {
        $id_beneficiario = $data;
        $beneficiario = Beneficiario::where('id', $id_beneficiario)->get();
        // dd($beneficiario[0]->foto_nombre);
        $foto_afiliado = null;
        if ($beneficiario) {
            $nombre_foto_afiliado = $beneficiario[0]->foto_nombre;
            $foto_afiliado = $this->obtenerImagenBase64($nombre_foto_afiliado);
        }
        // $foto_afiliado = 'data:image/png;base64,' . $foto_afiliado;
        return $foto_afiliado;
        // dd($foto_afiliado);
    }

    // Para el carnet 
    #[On('carnetizacion')]
    public function carnetizacion($data)
    {
        $this->verificarDatosBeneficiarios($data);
        if ($this->debeActualizarReferenciales &&  $this->debeActualizarResidencia) {
            $this->dispatch('actualizaReferenciaYDireccion');
            $this->resetearActualizaciones();
            return;
        }
        if ($this->debeActualizarReferenciales) {
            $this->dispatch('actualizaReferenciales');
            $this->resetearActualizaciones();
            return;
        }
        if ($this->debeActualizarResidencia) {
            $this->dispatch('actualizaDireccion');
            $this->resetearActualizaciones();
            return;
        }
        $this->fecha_emision_r = Carbon::now()->toDateString();
        $foto_afiliado_verificar = $this->verificarFotoTitular($data);

        if ($foto_afiliado_verificar != null) {
            $this->mostrarBeneficiario($data);
            $carnet = HCarnet::where('id_persona', $this->idPersona)->orderby('id', 'desc')->first();
            // dd($carnet);
            if ($carnet) {
                $this->fecha_emision = $carnet->fecha_emision;
                $this->fecha_emision_formatted = Carbon::parse($this->fecha_emision_r)->Format('d /m/ Y ');
                $this->fecha_vencimiento = $carnet->fecha_vencimiento;
                $this->fecha_vencimiento_formatted = Carbon::parse($this->fecha_vencimiento)->Format('d /m/ Y ');
            }

            $this->idBen = $data;
            $this->dispatch('modalCarnetizacionBen');
            //  $this->js('window.open("/afiliacion/beneficiario/carnet_pdf/'.$data . '", "_blank")');
        } else {
            $this->dispatch('sinFotoAfiliado');
        }
    }

    #[On('verificarDatosBeneficiarios')]
    public function verificarDatosBeneficiarios($data)
    {
        $beneficiarioParaActualizar = Beneficiario::where('id', $data)
            ->where('telefono', 0)
            ->where('telefono_referencia', 0)->first();
        if ($beneficiarioParaActualizar) {
            $this->debeActualizarReferenciales = true;
            $idResidencia = $beneficiarioParaActualizar->id_residencia;
            $residenciaActualizar = Residencia::where('id', $idResidencia)
                ->where('nro_vivienda', 0)
                ->first();
            if ($residenciaActualizar) {
                $this->debeActualizarResidencia = true;
            }
        }
        $beneficiario = Beneficiario::where('id', $data)->first();
        if ($beneficiario) {
            $idResidencia = $beneficiario->id_residencia;
            $residenciaActualizar = Residencia::where('id', $idResidencia)
                ->where('nro_vivienda', 0)
                ->first();
            if ($residenciaActualizar) {
                $this->debeActualizarResidencia = true;
            }
        }
    }
    public function resetearActualizaciones()
    {
        $this->reset('debeActualizarReferenciales', 'debeActualizarResidencia');
    }

    public $institucionSelect = [];
    public $id_benef, $id_institucion_remu,  $fecha_ingreso_remu,
        $cargoRemu, $salarioRemu, $numero_item_remu, $carga_horaria_remu, $titularidad_remu;

    #[On('agregarInstitucionBen')]
    public function agregarInstitucionBen($data)
    {
        $this->id_benef = $data;
        $this->institucionSelect = Institucion::where('estado', true)
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

        //dd($this->institucionSelect);
        $this->dispatch('modalAgregarInstitucionBeneficiario');
    }
    #[On('agregarInstitucionBeneficiario')]
    public function agregarInstitucionBeneficiario()
    {
        $yaTieneInstitucionAgregada = BeneficiarioInstitucion::where('id_beneficiario', $this->id_benef)
            ->where('estado', true)->first();
        // dd($yaTieneInstitucionAgregada);
        //  dd($this->id_institucion_remu, $this->fecha_ingreso_remu, $this->cargoRemu, $this->salarioRemu, $this->numero_item_remu, $this->carga_horaria_remu, $this->titularidad_remu);
        //try {
        if (!$yaTieneInstitucionAgregada) {
            if (
                !empty($this->id_institucion_remu)
                && !empty($this->fecha_ingreso_remu)
                && !empty($this->cargoRemu)
                && !empty($this->salarioRemu)
                && !empty($this->carga_horaria_remu)
                && !empty($this->titularidad_remu)
            ) {
                //dd('asdas');
                $beneficiario = Beneficiario::find($this->id_benef);
                $titular = Titular::where('id', $beneficiario->id_titular)->first();
                $nuevaInstitucion = BeneficiarioInstitucion::create([
                    'id_beneficiario' => $this->id_benef,
                    'id_institucion' => $this->id_institucion_remu,
                    'estado' => true,
                    'id_titular' => $titular->id,
                    'fecha_ingreso' => $this->fecha_ingreso_remu,
                    'cargo' => mb_strtoupper($this->cargoRemu),
                    'salario' => $this->salarioRemu,
                    'item' => $this->numero_item_remu,
                    'carga_horaria' => $this->carga_horaria_remu,
                    'titularidad' => $this->titularidad_remu,
                    'id_user_created' => auth()->user()->id,
                ]);

                if ($nuevaInstitucion) {
                    if ($beneficiario) {
                        $beneficiario->con_remuneracion = 'true';
                        $beneficiario->save();
                    }

                    $this->dispatch('nuevaInstitucionAgregadaBen');
                    $this->dispatch('pg:eventRefresh-BeneficiariosTable');
                } else {
                    $this->dispatch('nuevaInstitucionNoAgregadaBen');
                }
            } else {
                $this->dispatch('faltanDAtosRemu');
            }
        } else {
            $this->dispatch('yaTieneRemu');
        }

        //  } catch (\Throwable $e) {
        //      $this->dispatch('nuevaInstitucionNoAgregadaBen');
        //      $trace = collect($e->getTrace())
        //          ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));
        //
        //      Log::error("Afiliacion:error al guardar institucion nueva", [
        //          'USUARIO_ID' => auth()->user()->id ?? null,
        //          'ERROR'      => $e->getMessage() ?? '',
        //          'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
        //          'LINEA'      => $trace['line'] ?? $e->getLine(),
        //          'FECHA_HORA' => now(),
        //      ]);
        //  }
    }

    public function resetearInstitucionRemu()
    {
        $this->reset(
            'id_institucion_remu',
            'fecha_ingreso_remu',
            'cargoRemu',
            'salarioRemu',
            'numero_item_remu',
            'carga_horaria_remu',
            'titularidad_remu'
        );
    }
    #[On('archivoGrupo')]
    public function archivoGrupo($data)
    {
        $this->reseteandoVarBen();
        $this->mostrarBeneficiario($data);
        $this->idBen = $data;
        // $this->mostrarArchivoPdf($data);
        //dd($this->pdf_vista);
        $this->beneficiario_data = Beneficiario::find($data);
        $this->idPersona = $this->beneficiario_data->id_persona;
        $this->dispatch('modalArchivoGrupo');
    }
    #[On('guardarArchivoGrupo')]
    public function guardarArchivoGrupo()
    {
        $titular = $this->beneficiario_data;
        $hoy = Carbon::now();
        $id_user_archivo = auth()->user()->id;
        $text_archivo_grupo = $this->text_archivo_grupo;
        $titular->update([
            'archivo_grupo' => $text_archivo_grupo,
            'id_user_archivo' => $id_user_archivo,
            'fecha_archivo' => $hoy
        ]);
        if (!$titular) {
            $this->dispatch('errorGuardarArchivo');
        }
        $this->reseteandoVarBen();
        $this->resetearDatosArchivo();
        $this->dispatch('pg:eventRefresh-BeneficiariosTable');
        $this->dispatch('registroArchivoCorrecto');
        // dd($this->titular_data);
    }
    public function resetearDatosArchivo()
    {
        $this->reset('beneficiario_data', 'text_archivo_grupo',);
    }


    public function descargarFotoBeneficiario()
    {
        // dd($this->idTit);
        /*    try {
            $path = 'Afiliacion/FOTOS/' . $this->idTit . '-T.jpg';
            // dd($path);
            $name = $this->nombre . '- FOTO';
            if (!Storage::disk('ftp')->exists($path)) {
                abort(404);
                // $this->dispatch('archivoNoEncontrado');
            }
            return Storage::disk('ftp')->download($path, $name);
        } catch (\Throwable $th) {
            return null;
        } */
        $filename = $this->foto_nombre;
        //dd($filename);
        //  $filename = '-FOTO';
        return redirect()->route(
            'descarga.downloadFoto',
            $filename
        );
    }

    public function abrirModalReportesBeneficiarios()
    {
        $this->dispatch('modalBeneficiariosReportes');
    }
    public $pdf_vista, $rowId;
    public function buscarSiTieneFileDigital($id_beneficiario)
    {
        try {
            $path = 'Afiliacion/FILES_PERSONALES/BENEFICIARIOS/' . $id_beneficiario . '.pdf';
            if (Storage::disk('ftp')->exists($path)) {
                $this->tieneFileDigital = true;
            } else {
                $this->tieneFileDigital = false;
            }
            $this->pdf_vista = null;
        } catch (\Throwable $th) {
            $this->tieneFileDigital = false;
        }
    }
}
