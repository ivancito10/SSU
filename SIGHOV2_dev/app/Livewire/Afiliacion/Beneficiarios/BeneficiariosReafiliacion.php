<?php

namespace App\Livewire\Afiliacion\Beneficiarios;

use App\Jobs\ProcesarAfiliacionBeneficiarioAsussJob;
use Livewire\WithFileUploads;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\TitularInstitucion;
use App\Models\Aportes\Institucion;
use App\Services\BeneficiariosGehma2;

use App\Models\Administracion\Persona;
use App\Models\Administracion\Residencia;
use App\Models\Administracion\Zona;
use App\Models\Administracion\EstadoCivil;
use App\Models\Administracion\Departamento;
use App\Models\Administracion\Municipio;
use App\Models\Administracion\Provincia;
use App\Models\Administracion\TipoAsegurado;
use App\Models\Afiliacion\BeneficiarioInstitucion;
use App\Models\Administracion\NotificacionUsuarios;
use App\Models\Afiliacion\CodigoInstitucionSsu;
use App\Models\Afiliacion\Estudiante;
use App\Models\Afiliacion\HCarnet;
use App\Models\Vigencia\HistorialAsegurado;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Services\BeneficiariosAsuss;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Locked;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use PowerComponents\LivewirePowerGrid\Concerns\Listeners;

class BeneficiariosReafiliacion extends Component
{
    use WithFileUploads;
    #[Validate]
    public $foto, $fotito, $file_digital, $foto_titular_mostrar;
    public $fotoAntigua;
    public $titular;
    // datos personales
    public $ci, $edad;
    public $complemento;
    public $nombre;
    public $p_apellido;
    public $s_apellido;
    public $nombres;
    public $fecha_nacimiento;
    public $sexo;
    public $sexoTexto;
    public $estado_asuss;
    public $matricula;
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

    // datos para el tipo de asegurado 
    public $tipoAsegurado;
    public $aseguradoId;
    public $tipo_aseguradoBen;
    public $idPersona;
    // datos referenciales

    public $idBen;
    public $fecha_afiliacion, $fecha_afiliacion_vista;
    public $fecha_reafiliacion;
    public $fecha_reafiliacion_r;
    public $grupo_sanguineo;
    public $alergias, $color_titular;
    #[Validate]
    public $foto_nombre;
    public $observacion;
    public $vigencia;
    public $vigenciaTitular;
    public $estado = true;
    #[Validate]
    public $telefono_referencia;
    #[Validate]
    public $nombre_referencia;
    #[Locked]
    public $id_titular;
    #[Validate]
    public $telefono;
    #[Validate]
    public $correo;
    public $estado_requisitos = true;
    public $permanente = false;
    public $con_remuneracion;

    //Para la Residencia
    #[Validate]
    public $direccion;
    #[Validate]
    public $nro_vivienda;
    // dats para zona
    #[Validate]
    public $zona;
    // #[Locked]  
    #[Validate]
    public $zonaId;

    // datos de titular
    // #[Locked]
    public $idTit, $idTitular, $idPersonaTit, $titularAnterior, $titularNuevo;
    public $nombreCompletoTitular;
    public $ciTitular, $matriculaTit;
    //datos institucionales de titular
    public $nombreInstitucion, $titularPermanente;
    public $codigoTitular;
    public $codigoBeneficiario;
    public $cargoTit;
    public $salarioTit, $tipo_titutlar;
    // #[Locked]
    public $idInsti;
    public $nombreInstitucionTit;

    //Para la institucion Beneficiario Institucion
    public $id_institucion;
    public $fecha_ingreso;
    public $cargoRemu;
    public $salarioRemu;

    // caso beneficiario con remuneracion   
    public $idBenIns;
    public $cargoBenIns;
    public $salarioBenIns;
    public $nombreBenIns;
    public $fechaIngresoBenIns;
    public $itemBenIns;
    public $cargaHorariaBenIns;
    public $titularidadBenIns;

    // para la ubicacion 
    public $ubicacion_select = 0;
    public $latitud, $longitud;

    //LO QUE ESTABA EN EL RENDER
    public $residencia;
    public $zonasSelect;
    public $institucionSelect;
    public $tipoBeneficiarioSelect;
    public $estadosCivilesSelect;

    public $matriculaNuevoTitular;
    public $beneficiarioId;
    public $cambio = false;
    public $fechaVencimientoCarnet;

    public $idInstitucionParaAsuss = null;


    protected $listeners = ['reafiliarBeneficiario', 'recibirLonLat'];

    public function rules()
    {
        return [
            'direccion' => 'required|min:5',
            'zonaId' => 'required',
            'nro_vivienda' => 'required|min:2',
            'telefono' => 'required|min:6',
            'nombre_referencia' => 'required|min:2',
            'telefono_referencia' => 'required|min:6',
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'file_digital' => 'required|mimes:pdf',
            'estado_civil' => 'required',
            'aseguradoId' => 'required',
            'fechaIngresoBenIns' => $this->con_remuneracion ? 'required|date' : 'nullable| date',
            'cargoBenIns' => $this->con_remuneracion ? 'required|min:2' : 'nullable| min:2',
            'salarioBenIns' => $this->con_remuneracion ? 'required|min:1' : 'nullable| min:1',
            'id_institucion' => $this->con_remuneracion ? 'required|min:1' : 'nullable| min:1',


        ];
    }

    public function render()
    {
        return view('livewire.Afiliacion.beneficiarios.beneficiarios-reafiliacion');
    }

    public function mount()
    {
        $this->residencia = Residencia::all();
        $this->zonasSelect = Zona::orderBy('nombre', 'asc')->get();
        $this->institucionSelect = Institucion::where('estado', true)->get();

        $this->tipoBeneficiarioSelect = TipoAsegurado::where('tipo_asegurado', 'like', '%BENEFICIARIO%')->whereNot('tipo_asegurado', 'BENEFICIARIO DEL INTERIOR')->get();
        $this->mostrarBeneficiario($this->idBen);
        $this->estadosCivilesSelect = EstadoCivil::all();
        //$this->zonasSelect = Zona::all();
        $this->beneficiarioVigenteFunction();
    }
    public function beneficiarioVigenteFunction()
    {
        $beneficiarioVigente = Beneficiario::where('id_persona', $this->idPersona)
            ->whereIn('vigencia', ['VIGENTE', 'CESANTE'])
            ->where('estado', true)
            ->first();
        if ($beneficiarioVigente) {
            $this->dispatch('esBeneficiario');
        }
    }

    public function atras()
    {
        return redirect()->route('afiliacion.beneficiario.index');
    }

    public function recibirLonLat($d_lat, $d_lng)
    {
        $this->latitud = $d_lat;
        $this->longitud = $d_lng;
        $this->ubicacion_select = 1;
    }

    //funcion para mostrar los datos de beneficiario
    public function mostrarBeneficiario($idBen)
    {
        $beneficiario = Beneficiario::find($idBen);
        if ($beneficiario) {
            $this->idPersona = $beneficiario->id_persona;
            $this->fecha_afiliacion = $beneficiario->fecha_afiliacion;
            $this->fecha_afiliacion_vista = Carbon::parse($this->fecha_afiliacion)->Format('d/m/Y');
            $this->fecha_reafiliacion = Carbon::now()->format('Y-m-d');
            $this->vigencia = $beneficiario->vigencia;
            $this->estado_requisitos = $beneficiario->estado_requisitos;
            $this->telefono = $beneficiario->telefono;
            $this->correo = $beneficiario->correo;
            $this->nombre_referencia = $beneficiario->nombre_referencia;
            $this->telefono_referencia = $beneficiario->telefono_referencia;
            $this->fotoAntigua = $this->obtenerImagenBase64($beneficiario->foto_nombre);
            $this->observacion = $beneficiario->observacion;
            if ($this->observacion == null) {
                $this->observacion = 'NO PRESENTA OBSERVACIONES';
            }
            $this->idTit = $beneficiario->id_titular;
            $this->con_remuneracion = $beneficiario->con_remuneracion;
            //LOGICA PARA MOSTRAR EL NUEVO TITULAR
            $this->titularAnterior = Titular::where('id', $this->idTit)->with(['titularPersona'])->first();
            $this->titularNuevo = Titular::where('id_persona', $this->titularAnterior->titularPersona->id)->whereIn('estado_vigencia', ['VIGENTE', 'CESANTE'])->first();
            if ($this->titularNuevo) {
                $this->idTit = $this->titularNuevo->id;
            }
            //FIN DE LA LOGICA PARA MOSTRAR EL NUEVO TITULAR

            $this->titular = Titular::where('id', $this->idTit)->with(['titularPersona'])->first();
            $this->titularPermanente = $this->titular->permanente ? 'PERMANENTE' : 'EVENTUAL';
            $this->idTitular = $this->titular->id;

            $this->idPersonaTit = $this->titular->id_persona;
            $this->vigenciaTitular = $this->titular->estado_vigencia;
            $this->foto_titular_mostrar = $this->obtenerImagenBase64($this->titular->foto_nombre);

            $dataTitular = Persona::find($this->idPersonaTit);

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
                $this->ciTitular = $dataTitular->ci . ' - ' . $dataTitular->complemento;
            }
            $this->matriculaTit = $dataTitular->matricula_seguro;

            $titularInstitucion = TitularInstitucion::where('id_titular', $this->idTit)
                //->where('tipo_institucion', 'PATRONAL')
                ->where('estado', true)
                ->orderBy('id', 'asc')
                ->first();
            if ($titularInstitucion) {
                $this->idInstitucionParaAsuss = $titularInstitucion->id_institucion;
                $this->cargoTit = $titularInstitucion->cargo_actual;
                $this->salarioTit = $titularInstitucion->salario;
                $idInstiTit = $titularInstitucion->id_institucion;
                $institucionTit = Institucion::find($idInstiTit);
                if ($institucionTit) {
                    $this->nombreInstitucionTit = $institucionTit->nombre;
                    $codigo = CodigoInstitucionSsu::where('id_institucion', $institucionTit->id)
                        ->first();
                    if ($codigo) {
                        $this->codigoTitular = $codigo->codigo . $codigo->tipo_titular;
                        $this->codigoBeneficiario = $codigo->codigo . $codigo->tipo_beneficiario;
                    } else {
                        $this->codigoTitular = ' ';
                        $this->codigoBeneficiario = ' ';
                    }
                }
                $this->tipo_titutlar = 'INSTITUCIONAL';
            } else {
                $this->tipo_titutlar = 'VOLUNTARIO';
                $this->cargoTit = '';
                $this->salarioTit = '';
                $this->nombreInstitucionTit = '';
                $this->codigoTitular = '';
                $this->codigoBeneficiario = '';
            }

            if ($this->tipo_titutlar == 'INSTITUCIONAL') {
                if ($this->titular->permanente) {
                    $this->color_titular = match ($this->titular->tipo_rentista) {
                        'TITULAR-RENTISTA' => '#e0241c', // Rentista Jubilado 3 rojo
                        'TITULAR-SORTEO' => '#5d5757', // Rentista Sorteo 4
                        default => '#e0241c', // Permanente titular 1
                    };
                } else {
                    $this->color_titular = match ($this->titular->tipo_rentista) {
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

            $personaActual = Persona::find($beneficiario->id_persona);
            if ($personaActual->p_apellido == null) {
                $this->nombre = $personaActual->s_apellido . ' ' . $personaActual->nombres;
            } elseif ($personaActual->s_apellido == null) {
                $this->nombre = $personaActual->p_apellido . ' ' . $personaActual->nombres;
            } else {
                $this->nombre = $personaActual->p_apellido . ' ' . $personaActual->s_apellido . ' ' . $personaActual->nombres;
            }
            $this->nombres = $personaActual->nombres;
            $this->p_apellido = $personaActual->p_apellido;
            $this->s_apellido = $personaActual->s_apellido;
            $this->complemento = $personaActual->complemento;
            if ($this->complemento) {
                $this->ci = $personaActual->ci . ' - ' . $personaActual->complemento;
            } else {
                $this->ci = $personaActual->ci;
            }
            $this->fecha_nacimiento = $personaActual->fecha_nacimiento;
            $this->matricula = $personaActual->matricula_seguro;
            $this->sexo = $this->setSexoTexto($personaActual->sexo);
            $this->edad = Carbon::parse($personaActual->fecha_nacimiento)->age;
            $this->grupo_sanguineo = $personaActual->grupo_sanguineo;
            $this->alergias = $personaActual->alergias;
            if ($this->alergias == null) {
                $this->alergias = 'NO PRESENTA ALERGIAS';
            }
            $this->es_extranjero = $this->setExtranjeroTexto($personaActual->es_extranjero);

            $this->id_estado_civil = $personaActual->id_estado_civil;

            $estadoCivilPersona = EstadoCivil::find($personaActual->id_estado_civil);
            $this->estado_civil = $estadoCivilPersona->nombre;

            $this->id_expedido_ci = $personaActual->id_dept_exp;
            if ($this->id_expedido_ci) {
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
            $zonaActual = Zona::find($residenciaActual->id_zona);
            $this->zona = $zonaActual->nombre;
            $this->zonaId = $zonaActual->id;
            $institucionBen = BeneficiarioInstitucion::where('id_beneficiario', $this->idBen)
                ->first();
            if ($institucionBen) {
                $this->cargoBenIns = $institucionBen->cargo;
                $this->salarioBenIns = $institucionBen->salario;
                $this->fechaIngresoBenIns = $institucionBen->fecha_ingreso;
                $this->idBenIns = $institucionBen->id_institucion;
                $this->itemBenIns = $institucionBen->item;
                $this->cargaHorariaBenIns = $institucionBen->carga_horaria;
                $this->titularidadBenIns = $institucionBen->titularidad;


                $institucionBenIns = Institucion::find($this->idBenIns);
                $this->id_institucion = $institucionBenIns->id;
                if ($institucionBenIns) {
                    $this->nombreBenIns = $institucionBenIns->nombre;
                    if ($institucionBen) {
                        $beneficiarioInstitucion = $institucionBen;
                    } else {
                        $beneficiarioInstitucion = [];
                    }
                }
            }
            $carnetBeneficiario = HCarnet::where('id_persona', $personaActual->id)
                ->orderBy('id', 'desc')
                ->first();
            if ($carnetBeneficiario) {
                $fechaVenimiento = $carnetBeneficiario->fecha_vencimiento;
                $this->fechaVencimientoCarnet = Carbon::parse($fechaVenimiento)->Format('d /m/ Y ');
            } else {
                $this->fechaVencimientoCarnet = 'SIN DATO';
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
                //dd($imageData);
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
    // para obtener el literal de extranjero 
    private function setExtranjeroTexto($es_extranjero)
    {
        if ($es_extranjero == false) {
            return 'NACIONAL';
        } elseif ($es_extranjero == true) {
            return 'EXTRANJERO';
        } else {
            return 'NO ESPECIFICADO';
        }
    }
    public function cambioTipoAsegurado()
    {
        $this->validarTipoAsegurado();
    }
    public function validarTipoAsegurado()
    {
        $beneficiarioMayor19 = TipoAsegurado::where('tipo_asegurado', 'like', 'BENEFICIARIO HIJO MAYOR 19 AÑOS')->first();
        $id_beneficiarioMayor19 = $beneficiarioMayor19 ? $beneficiarioMayor19->id : null;
        $beneficiarioMenor19 = TipoAsegurado::where('tipo_asegurado', 'like', 'BENEFICIARIO HIJO MENOR 19 AÑOS')->first();
        $id_beneficiarioMenor19 = $beneficiarioMenor19 ? $beneficiarioMenor19->id : null;
        $beneficiarioBebe = TipoAsegurado::where('tipo_asegurado', 'BENEFICIARIO BEBE RECIÉN NACIDO')->first();
        $id_beneficiarioBebe = $beneficiarioBebe?->id;
        $beneficiarioPadreMadre = TipoAsegurado::where('tipo_asegurado', 'BENEFICIARIO MADRE - PADRE')->first();
        $id_beneficiarioPadreMadre = $beneficiarioPadreMadre?->id;
        $noAsegurado = TipoAsegurado::where('tipo_asegurado', 'NO ASEGURADO')->first();
        $id_noAsegurado = $noAsegurado?->id;

        if ($this->aseguradoId == $id_noAsegurado) {
            $this->dispatch('ingresaTipoAsegurado');
            DB::rollBack();
            return false;
        }
        if ($this->aseguradoId == $id_beneficiarioMayor19) {
            if ($this->edad < 19) {
                $this->dispatch('beneficiarioMenor19Años');
                DB::rollBack();
                return false;
            }
            if ($this->edad >= 25) {
                $this->dispatch('beneficiarioMayor25Años');
                DB::rollBack();
                return false;
            }
        }
        if ($this->aseguradoId == $id_beneficiarioMenor19) {
            if ($this->edad >= 19) {
                $this->dispatch('beneficiarioMayor19años');
                DB::rollBack();
                return false;
            }
        }
        if ($this->aseguradoId == $id_beneficiarioBebe) {
            if ($this->edad > 1) {
                $this->dispatch('beneficiarioBebe');
                DB::rollBack();
                return false;
            }
        }
        if ($this->aseguradoId == $id_beneficiarioPadreMadre) {
            if ($this->edad <= 35) {
                $this->dispatch('padreMadreMenor35años');
                DB::rollBack();
                return false;
            }
        }


        return true;
    }
    public function reafiliarBeneficiario()
    {
        $this->validate();
        DB::beginTransaction();
        $titularActual = Titular::find($this->idTitular);
        if ($titularActual->estado_vigencia == 'NO VIGENTE') {
            $this->dispatch('titularNoVigente');
            return;
        }
        if (!$this->validarTipoAsegurado()) {
            return;
        }
        try {
            $personaActual = Persona::find($this->idPersona);
            if ($personaActual) {
                $es_titular = Titular::where('id_persona', $personaActual->id)
                    ->where(function ($query) {
                        $query->where('estado_vigencia', 'VIGENTE')
                            ->orWhere('estado_vigencia', 'CESANTE');
                    })
                    ->where('estado', true)
                    ->orderBy('id', 'DESC')
                    ->first();
                $es_estudiante = Estudiante::where('id_persona', $personaActual->id)
                    ->where('estado_vigencia', true)
                    ->where('estado', true)
                    ->orderBy('id', 'DESC')
                    ->first();
                if ($es_titular) {
                    $this->dispatch('esTitular');
                } elseif ($es_estudiante) {
                    $this->dispatch('esEstudiante');
                } else {
                    if ($this->aseguradoId != $personaActual->id_tipo_asegurado) {
                        $personaActual->id_tipo_asegurado = $this->aseguradoId;
                        $personaActual->id_user_updated = auth()->user()->id;
                        $personaActual->save();
                    }
                    if ($this->id_estado_civil != $personaActual->id_estado_civil) {
                        $personaActual->id_estado_civil = $this->id_estado_civil;
                        $personaActual->id_user_updated = auth()->user()->id;
                        $personaActual->save();
                    }
                    $personaActual->update([
                        'alergias' => $this->alergias,
                        'afiliado' => true,
                    ]);
                    $existeResidencia = Residencia::where('direccion', $this->direccion)
                        ->where('nro_vivienda', $this->nro_vivienda)
                        ->where('id_zona', $this->zonaId)
                        ->first();
                    if ($existeResidencia) {
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
                            'id_zona' => $this->zonaId,
                            'latitud' => $this->latitud,
                            'longitud' => $this->longitud,
                            'id_user_created' => auth()->user()->id,
                        ]);
                        $residenciaId = $residencia->id;
                    }

                    // aqui actualizo el estado del beneficiario pasado
                    $beneficiario_act = Beneficiario::find($this->idBen);
                    if ($beneficiario_act) {
                        $beneficiario_act->estado = false;
                        $beneficiario_act->vigencia = 'NO VIGENTE';
                        if ($beneficiario_act->fecha_baja == null) {
                            $beneficiario_act->fecha_baja = Carbon::now()->format('Y-m-d');
                        }
                        $beneficiario_act->save();
                    }
                    // aqui empiezo a crear de nuevo al beneficiario con los mismos datos de persona
                    $beneficiario = Beneficiario::create([
                        'fecha_afiliacion' => $this->fecha_afiliacion,
                        'fecha_reafiliacion' => $this->fecha_reafiliacion,
                        'observacion' => $this->observacion,
                        'vigencia' => 'VIGENTE',
                        'telefono_referencia' => $this->telefono_referencia,
                        'nombre_referencia' => $this->nombre_referencia,
                        'id_titular' => $this->idTitular,
                        'id_persona' => $this->idPersona,
                        'telefono' => $this->telefono,
                        'correo' => $this->correo,
                        'permanente' => $this->permanente,
                        'estado' => true,
                        'estado_requisitos' => $this->estado_requisitos,
                        'con_remuneracion' => $this->con_remuneracion,
                        'id_residencia' => $residenciaId,
                        'id_user_created' => auth()->user()->id,
                    ]);
                    $nombre_foto = $beneficiario->id . '-B.jpg';
                    $this->beneficiarioId = $beneficiario->id;
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
                        $beneficiario->update([
                            'foto_nombre' => $nombre_foto,
                        ]);
                    }
                    //
                    $nombre_file = $beneficiario->id . '.pdf';
                    $this->file_digital->storeAs('Afiliacion/FILES_PERSONALES', $nombre_file, 'ftp');

                    if ($beneficiario) {
                        if ($this->con_remuneracion) {
                            $beneficiarioInstitucion = BeneficiarioInstitucion::create([
                                'id_beneficiario' => $this->beneficiarioId,
                                'id_institucion' => $this->id_institucion,
                                'id_titular' => $this->idTit,
                                'fecha_ingreso' => $this->fechaIngresoBenIns,
                                'cargo' => $this->cargoBenIns,
                                'salario' => $this->salarioBenIns,
                                'item' => $this->itemBenIns,
                                'carga_horaria' => $this->cargaHorariaBenIns,
                                'titularidad' => $this->titularidadBenIns,
                                'estado' => true,
                                'id_user_created' => auth()->user()->id,
                            ]);
                            //dd($beneficiarioInstitucion);
                            if ($beneficiarioInstitucion) {
                                // INICIO TABLA DE HISTORIAL ASEGURADO
                                $existeHistorialAsegurado = HistorialAsegurado::where('id_persona', $this->idPersona)
                                    ->orderBy('id', 'desc')
                                    ->first();
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
                                        'id_tipo_asegurado' => $this->aseguradoId,
                                        'fecha_inicio' => Carbon::now()->format('Y-m-d'),
                                        'id_persona' => $this->idPersona,
                                        'id_corresponde' => $beneficiario->id,
                                        'id_user_created' => auth()->user()->id,
                                    ]);
                                } else {
                                    HistorialAsegurado::create([
                                        'estado' => true,
                                        'estado_vigencia' => 'VIGENTE',
                                        'id_tipo_asegurado' => $this->aseguradoId,
                                        'fecha_inicio' => Carbon::now()->format('Y-m-d'),
                                        'id_persona' => $this->idPersona,
                                        'id_corresponde' => $beneficiario->id,
                                        'id_user_created' => auth()->user()->id,
                                    ]);
                                }
                                // FIN TABLA DE HISTORIAL ASEGURADO
                                //inicio de NOTIFICACIONES 
                                // notificaiones para BENEFICIARIO
                                $user = User::where('id_persona', $beneficiario->id_persona)->first();
                                //asignacion de rol
                                $user->assignRole('Asegurado Afiliado');
                                $user->syncRoles(['Asegurado Afiliado']);
                                //
                                $nuevaNotificacion = NotificacionUsuarios::create([
                                    'estado' => 'NO LEIDO',
                                    'prioridades' => 2,
                                    'id_usu_emisor' => auth()->user()->id,
                                    'id_usu_receptor' => $user->id, // para que sea a los encargados de aportes
                                    'fecha_hora_enviada' => Carbon::now(),
                                    'asunto' => 'AFILIACIÓN - REAFILIACIÓN DE ASEGURADO BENEFICIARIO',
                                    'mensaje' => 'Bienvenido al Seguro Social Universitario nuevamente!',
                                ]);
                                //  fin de  notificacion 
                                //inicio de NOTIFICACIONES 
                                // notificaiones para EL TITULAR del beneficiario
                                $tit = Titular::find($beneficiario->id_titular);

                                $userTit = User::where('id_persona', $tit->id_persona)->first();
                                $nombreBen = $personaActual->nombres . ' ' . $personaActual->p_apellido . ' ' . $personaActual->s_apellido;
                                $nuevaNotificacion = NotificacionUsuarios::create([
                                    'estado' => 'NO LEIDO',
                                    'prioridades' => 2,
                                    'id_usu_emisor' => auth()->user()->id,
                                    'id_usu_receptor' => $userTit->id, // para que sea a los encargados de aportes
                                    'fecha_hora_enviada' => Carbon::now(),
                                    'asunto' => 'AFILIACIÓN - REAFILIACIÓN DE DATOS DE BENEFICIARIO',
                                    'mensaje' => 'La reafiliación del beneficiario: ' . $nombreBen . ' fue realizado exitosamente! ',
                                ]);
                                //  fin de  notificacion 
                                DB::commit();
                                $this->dispatch('beneficiarioCreado');
                                $this->js('window.open("/Administracion/usuario/repetir/reafiliacion/beneficiario/' . $beneficiario->id . '", "_blank")');
                                // TODO: comentado por errores de afiliacion (bladimir) 24/03/2025
                                $ins2 = new BeneficiariosGehma2();
                                $insercionBenGehma2 = $ins2->insercionBeneficiarioGehma2($beneficiario->id);
                                // ----------------------------------------------------------------------
                                return redirect()->route('afiliacion.beneficiario.index')->with('evento', 'beneficiarioCreado');
                            } else {
                                DB::rollBack();
                                $this->dispatch('beneficiarioCancelado');
                            }
                        } else {
                            // INICIO  TABLA DE HISTORIAL ASEGURADO
                            $existeHistorialAsegurado = HistorialAsegurado::where('id_persona', $this->idPersona)
                                ->orderBy('id', 'desc')
                                ->first();
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
                                    'id_tipo_asegurado' => $this->aseguradoId,
                                    'fecha_inicio' => Carbon::now()->format('Y-m-d'),
                                    'id_persona' => $this->idPersona,
                                    'id_corresponde' => $beneficiario->id,
                                    'id_user_created' => auth()->user()->id,
                                ]);
                            } else {
                                HistorialAsegurado::create([
                                    'estado' => true,
                                    'estado_vigencia' => 'VIGENTE',
                                    'id_tipo_asegurado' => $this->aseguradoId,
                                    'fecha_inicio' => Carbon::now()->format('Y-m-d'),
                                    'id_persona' => $this->idPersona,
                                    'id_corresponde' => $beneficiario->id,
                                    'id_user_created' => auth()->user()->id,
                                ]);
                            }
                            // FIN TABLA DE HISTORIAL ASEGURADO
                            //inicio de NOTIFICACIONES 
                            // notificaiones para BENEFICIARIO
                            $user = User::where('id_persona', $beneficiario->id_persona)->first();
                            $nuevaNotificacion = NotificacionUsuarios::create([
                                'estado' => 'NO LEIDO',
                                'prioridades' => 2,
                                'id_usu_emisor' => auth()->user()->id,
                                'id_usu_receptor' => $user->id, // para que sea a los encargados de aportes
                                'fecha_hora_enviada' => Carbon::now(),
                                'asunto' => 'AFILIACIÓN - REAFILIACIÓN DE ASEGURADO BENEFICIARIO',
                                'mensaje' => 'Bienvenido al Seguro Social Universitario nuevamente!',
                            ]);
                            //  fin de  notificacion 
                            //inicio de NOTIFICACIONES 
                            // notificaiones para EL TITULAR del beneficiario
                            $tit = Titular::find($beneficiario->id_titular);
                            $userTit = User::where('id_persona', $tit->id_persona)->first();
                            $nombreBen = $personaActual->nombres . ' ' . $personaActual->p_apellido . ' ' . $personaActual->s_apellido;
                            $nuevaNotificacion = NotificacionUsuarios::create([
                                'estado' => 'NO LEIDO',
                                'prioridades' => 2,
                                'id_usu_emisor' => auth()->user()->id,
                                'id_usu_receptor' => $userTit->id, // para que sea a los encargados de aportes
                                'fecha_hora_enviada' => Carbon::now(),
                                'asunto' => 'AFILIACIÓN - REAFILIACIÓN DE DATOS DE BENEFICIARIO',
                                'mensaje' => 'La reafiliación del beneficiario: ' . $nombreBen . ' fue realizado exitosamente! ',
                            ]);
                            //  fin de  notificacion 
                            $this->js('window.open("/Administracion/usuario/repetir/reafiliacion/beneficiario/' . $beneficiario->id . '", "_blank")');
                        }

                        // TODO: comentado por errores de afiliacion (bladimir) 24/03/2025
                        $ins2 = new BeneficiariosGehma2();
                        $insercionBenGehma2 = $ins2->insercionBeneficiarioGehma2($this->beneficiarioId);
                        DB::commit();

                        //------------------------------------------------------- NUEVA INSERCION DE DATOS ASUSS -------------------------------------------------------  

                        /*$servicioLoginAsuss = new LoginAsussService();

                    $servicioAsussBenefciario = new AfiliacionBeneficiarioAsussService($servicioLoginAsuss);*/

                        $arrayDatosOriginales = [
                            'codigo_entidad' => '',
                            'fecha_inicio' => Carbon::parse($this->fecha_reafiliacion)->format('Y-m-d'),
                            'tipo_asegurado' => 'TRABAJADOR',
                            'tipo_documento' => 'CI',
                            'numero_documento' => implode('-', array_filter([$this->ci, $this->complemento])),
                            'complemento_documento' => $this->complemento,
                            'fecha_nacimiento' => $this->fecha_nacimiento,
                            'id_persona' => $this->idPersona,
                            'id_tipo_asegurado' => $this->aseguradoId,
                            'id_corresponde' => $this->beneficiarioId,
                            'id_titular' => $this->idTitular,
                            'id_persona_titular' => $this->idPersonaTit,
                            'id_institucion' => $this->idInstitucionParaAsuss,
                            'id_user_created' => $beneficiario->id_user_created,
                        ];

                        //dd($arrayDatosOriginales);

                        //$servicioAsussBenefciario->afiliarBeneficiarioAsuss($arrayDatosOriginales);

                        //ProcesarAfiliacionBeneficiarioAsussJob::dispatch($arrayDatosOriginales); ------------------------------------------DESCOMENTAR PARA QUE EL ASUSS FUNCIONE

                        //------------------------------------------------------- FIN NUEVA INSERCION DE DATOS ASUSS -------------------------------------------------------

                        $this->dispatch('beneficiarioCreado');
                        return redirect()->route('afiliacion.beneficiario.index');
                        //  return redirect()->route('afiliacion.beneficiario.index')->with('evento', 'beneficiarioCreado');
                    } else {
                        DB::rollBack();
                        $this->dispatch('beneficiarioCancelado');
                    }
                }
            }
        } catch (\Throwable $e) {
            // dd($th->getMessage());
            DB::rollBack();
            $this->dispatch('beneficiarioCancelado');
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Afiliacion:error al reafiliar beneficiario", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }

    public function cancelarBen()
    {
        $this->dispatch('beneficiarioCancelado');
        return redirect()->route('afiliacion.beneficiario.index')->with('evento', 'beneficiarioCancelado');
    }

    #[On('cambiarTitular')]
    public function cambiarTitular($data)
    {
        $this->idInstitucionParaAsuss = null;
        try {
            if ($data != null) {
                $titular = Titular::where('id', $data)->with(['titularPersona'])->first();
                //dd($titular);
                $this->titularPermanente = $titular->permanente ? 'PERMANENTE' : 'EVENTUAL';
                $this->idTitular = $titular->id;

                $this->idPersonaTit = $titular->id_persona;
                $this->vigenciaTitular = $titular->estado_vigencia;
                $this->foto_titular_mostrar = $this->obtenerImagenBase64($titular->foto_nombre);

                $dataTitular = Persona::find($this->idPersonaTit);

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
                    $this->ciTitular = $dataTitular->ci . ' - ' . $dataTitular->complemento;
                }
                $this->matriculaTit = $dataTitular->matricula_seguro;

                $titularInstitucion = TitularInstitucion::where('id_titular', $data)
                    //->where('tipo_institucion', 'PATRONAL')
                    ->where('estado', true)
                    ->orderBy('id', 'asc')
                    ->first();
                if ($titularInstitucion) {
                    $this->idInstitucionParaAsuss = $titularInstitucion->id_institucion;
                    $this->cargoTit = $titularInstitucion->cargo_actual;
                    $this->salarioTit = $titularInstitucion->salario;
                    $idInstiTit = $titularInstitucion->id_institucion;
                    $institucionTit = Institucion::find($idInstiTit);
                    if ($institucionTit) {
                        $this->nombreInstitucionTit = $institucionTit->nombre;
                        $codigo = CodigoInstitucionSsu::where('id_institucion', $institucionTit->id)
                            ->first();
                        if ($codigo) {
                            $this->codigoTitular = $codigo->codigo . $codigo->tipo_titular;
                            $this->codigoBeneficiario = $codigo->codigo . $codigo->tipo_beneficiario;
                        } else {
                            $this->codigoTitular = ' ';
                            $this->codigoBeneficiario = ' ';
                        }
                    }
                    $this->tipo_titutlar = 'INSTITUCIONAL';
                } else {
                    $this->tipo_titutlar = 'VOLUNTARIO';
                    $this->cargoTit = '';
                    $this->salarioTit = '';
                    $this->nombreInstitucionTit = '';
                    $this->codigoTitular = '';
                    $this->codigoBeneficiario = '';
                }

                $beneficiario_reafiliado = Beneficiario::find($this->beneficiarioId);
                if ($beneficiario_reafiliado) {
                    $beneficiario_reafiliado->update([
                        'id_titular' => $beneficiario_reafiliado->id,
                    ]);
                }
            }
            $this->dispatch('cerrarModalAseguradosTitular');
        } catch (\Throwable $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Afiliacion:error al cambiar titular", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }
}
