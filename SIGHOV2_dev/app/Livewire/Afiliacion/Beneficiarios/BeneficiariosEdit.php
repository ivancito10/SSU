<?php

namespace App\Livewire\Afiliacion\Beneficiarios;

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
use App\Models\Administracion\NotificacionUsuarios;
use App\Models\Afiliacion\HCarnet;
use App\Services\BeneficiariosAsuss;
use App\Services\BeneficiariosGehma2;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class BeneficiariosEdit extends Component
{
    public $id_beneficiario;
    use WithFileUploads;


    public $foto, $fotito;
    public $foto_titular_mostrar;
    // datos personales
    public $idPersona;
    public $nombre;
    public $ci;
    public $complemento, $complementoBen;
    public $fecha_nacimiento, $fecha_nacimiento_vista;
    public $es_extranjero;
    public $extranjeroTexto;

    public $estado_civil;
    public $id_expedido_ci;
    public $expedido_ci;
    public $id_estado_civil;
    public $estadoCivilSelect;


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
    // datoa referenciales
    public $idBen;
    public $vigencia, $edad;
    public $sangre;
    public $grupo_sanguineo;
    public $fecha_afiliacion, $fecha_afiliacion_vista;
    public $fechaVencimientoCarnet;
    public $fecha_reafiliacion;
    public $fecha_reafiliacion_r;
    public $estado_vigencia;
    public $estado_requisitos;
    public $idTit;
    public $idTitular;
    public $imageUrl, $fotoAntigua;
    public $fechaUltimaEdicion;
    #[Validate]
    public $telefono, $correo;
    #[Validate]
    public $nombre_referencia, $telefono_referencia;
    public $alergias;
    public $observacion;

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
    public $tipo_titutlar, $tipoAporteTitular;
    public $idPersonaTit;
    public $idInstiTit;
    public $cargoTit;
    public $salarioTit;
    public $nombreInstitucionTit;
    public $tipoBeneficiarioSelect;
    public $aseguradoId;
    public $codigoTitular;
    public $codigoBeneficiario;
    public $color;
    // caso beneficiario con remuneracion   
    public $idBenIns;
    public $cargoBenIns;
    public $salarioBenIns;
    public $nombreBenIns;
    public $fechaIngresoBenIns;
    public $itemBenIns;
    public $cargaHorariaIns = "";
    public $titularidadIns = "";
    public $institucionSelect;

    public $latitud;
    public $longitud;
    public $d_latitud, $d_longitud;
    public $latitudRegistered;
    public $longitudRegistered;
    public $ubicacion_select = 0;
    public $ubicacionTexto = 'NO SELECCIONADO';

    public $beneficiarioInstitucion = [];
    public $zonasSelect = [];
    public $departamento;
    public $provincias = [];
    public $municipios = [];
    public $file_digital;
    public $rowId;
    public $ruta = 'Beneficiarios';
    protected $listeners = ['actualizarBeneficiario', 'recibirLonLat'];


    public function rules()
    {
        return [
            'direccion' => 'required|min:5',
            //'zonaId' => 'required',
            'nro_vivienda' => 'required|min:1',
            'telefono' => 'required|min:6',
            // 'correo' => 'required|email',
            'nombre_referencia' => 'required|min:2',
            'telefono_referencia' => 'required|min:6',
            'estado_civil'  => 'required',
            'file_digital' => 'nullable|mimes:pdf|max:51200',
        ];
    }
    public function render()
    {
        return view('livewire.Afiliacion.beneficiarios.beneficiarios-edit');
    }
    public function mount()
    {

        $this->mostrarBeneficiario($this->id_beneficiario);
        $this->estadoCivilSelect = EstadoCivil::all();
        $this->zonasSelect = Zona::orderBy('nombre', 'asc')->get();
        $this->departamento = Departamento::get();
        $this->institucionSelect = Institucion::where('estado', true)
            ->orderBy('nombre', 'asc')->get();
        $this->buscarSiTieneFileDigital($this->id_beneficiario);
    }
    public function ObtenerProvincia()
    {
        $this->provincias = Provincia::where('id_departamento', '=', $this->id_dep_nacimiento)->get();
    }

    public function ObtenerMunicipio()
    {
        $this->municipios = Municipio::where('id_provincia', '=', $this->id_pro_nacimiento)->get();
    }

    public function mostrarBeneficiario($idBen)
    {
        try {
            if ($idBen) {
                // DATOS PROPIOS DE BENEFICIARIO
                $beneficiario = Beneficiario::find($idBen);
                $this->fecha_afiliacion = $beneficiario->fecha_afiliacion;
                //$this->fecha_afiliacion_vista = Carbon::parse($this->fecha_afiliacion)->Format('d /m/ Y ');
                $this->fecha_reafiliacion = $beneficiario->fecha_reafiliacion;
                $this->vigencia = $beneficiario->vigencia;
                $this->estado_requisitos = $beneficiario->estado_requisitos;
                $this->telefono = $beneficiario->telefono;
                $this->correo = $beneficiario->correo;
                $this->nombre_referencia = $beneficiario->nombre_referencia;
                $this->telefono_referencia = $beneficiario->telefono_referencia;
                $this->observacion = $beneficiario->observacion ? $beneficiario->observacion : 'NO CUENTA CON OBSERVACIONES';
                $this->idTit = $beneficiario->id_titular;
                $this->con_remuneracion = $beneficiario->con_remuneracion;
                $fecha_ultima_modificacion = $beneficiario->updated_at;
                $this->fechaUltimaEdicion = Carbon::parse($fecha_ultima_modificacion)->Format('d /m/ Y ');
                $this->fotoAntigua = $this->obtenerImagenBase64($beneficiario->foto_nombre);
                //dd($beneficiario->foto_nombre);

                // DATOS DEL TITULAR
                $titular = Titular::find($this->idTit);
                $this->vigenciaTitular = $titular->estado_vigencia;
                $this->foto_titular_mostrar = $this->obtenerImagenBase64($titular->foto_nombre);

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
                $titularInstitucion = TitularInstitucion::where('id_titular', $this->idTit)
                    //->where('tipo_institucion', 'PATRONAL')
                    ->where('estado', true)
                    ->orderBy('id', 'asc')
                    ->first();
                if ($titularInstitucion) {
                    $this->tipoAporteTitular = 'INSTITUCIONAL';
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
                } else {
                    $this->tipoAporteTitular = 'VOLUNTARIO';
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
                $this->fecha_nacimiento_vista = Carbon::parse($this->fecha_nacimiento)->Format('d /m/ Y ');
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
                    $this->provincias = Provincia::all();
                    $this->municipios = Municipio::all();
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
                // DATOS EN BENFICIARIO CON REMUNERACION ------
                $institucionBen = BeneficiarioInstitucion::where('id_beneficiario', $idBen)->first();
                if ($institucionBen) {
                    $this->cargoBenIns = $institucionBen->cargo;
                    $this->salarioBenIns = $institucionBen->salario;
                    $this->fechaIngresoBenIns = $institucionBen->fecha_ingreso;
                    $this->idBenIns = $institucionBen->id_institucion;
                    $this->itemBenIns = $institucionBen->item;
                    $this->cargaHorariaIns = $institucionBen->carga_horaria;
                    $this->titularidadIns = $institucionBen->titularidad;

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
                    //dd($titular->permanente);
                    if ($titular->permanente) {

                        if ($titular->tipo_rentista == 'TITULAR-RENTISTA') {
                            if ($this->tipo_aseguradoBen == 'BENEFICIARIO ESPOSA - ESPOSO' || $this->tipo_aseguradoBen == 'BENEFICIARIO HIJO MENOR 19 AÑOS') {
                                $this->color = '#f5c71a';
                            } elseif ($this->tipo_aseguradoBen == 'BENEFICIARIO HIJO MAYOR 19 AÑOS') {
                                $this->color = '#0b61b7';
                            }
                        } elseif ($this->tipo_aseguradoBen == 'BENEFICIARIO HIJO MAYOR 19 AÑOS') {

                            $this->color = '#0b61b7'; //si es Titular Permanente ben hijo mayor a 19 años
                        } else {

                            $this->color = '#f5c71a'; //si es titular permamente 
                        }
                    } else {
                        $this->color = '#0b61b7'; //Si es eventual
                    }
                } elseif ($this->tipoAporteTitular == 'VOLUNTARIO') {
                    $this->color = '#0b61b7'; // Voluntario
                } else {
                    $this->color = 0; // No definido
                }
            }
        } catch (\Throwable $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Afiliacion:error al mostrar beneficiario", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }
    //EDITAR BENEFICIARIO
    public function actualizarBeneficiario()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            $beneficiario = Beneficiario::find($this->id_beneficiario);
            if ($beneficiario) {
                $beneficiario->correo = $this->correo;
                $beneficiario->estado_requisitos = $this->estado_requisitos;
                $beneficiario->nombre_referencia = $this->nombre_referencia;
                $beneficiario->telefono_referencia = $this->telefono_referencia;
                $beneficiario->observacion = $this->observacion;
                $beneficiario->telefono = $this->telefono;
                $beneficiario->id_user_updated = auth()->user()->id;
                $existeResidencia = Residencia::where('direccion', $this->direccion)
                    ->where('nro_vivienda', $this->nro_vivienda)
                    ->where('id_zona', $this->zonaId)
                    ->first();
                if ($existeResidencia) {
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
                $beneficiario->update([
                    'id_residencia' => $residenciaId,
                ]);
                //ESTADO CIVIL 
                $personaBen = Persona::find($beneficiario->id_persona);
                if ($personaBen) {
                    $personaBen->update([
                        'id_estado_civil' => $this->id_estado_civil,
                        'alergias' => $this->alergias,
                        'id_nacimiento_municipio' => $this->id_mun_nacimiento,
                        'id_user_updated' => auth()->user()->id,
                    ]);
                }
                //dd($this->id_beneficiario);
                $beneficiarioInstitucion = BeneficiarioInstitucion::where('id_beneficiario', $this->id_beneficiario)->first();
                if ($beneficiarioInstitucion) {
                    $beneficiarioInstitucion->update([
                        'id_institucion' => $this->idBenIns,
                        'fecha_ingreso' => $this->fechaIngresoBenIns,
                        'cargo' => $this->cargoBenIns,
                        'salario' => $this->salarioBenIns,
                        'item' => $this->itemBenIns,
                        'carga_horaria' => $this->cargaHorariaIns,
                        'titularidad' => $this->titularidadIns,
                        'id_user_updated' => auth()->user()->id,
                    ]);
                }
                $beneficiario->save();

                $nombre_foto = $beneficiario->id . '-B.jpg';

                //=========== file digital ====
                if ($this->file_digital) {
                    $nombre_file = $beneficiario->id . '.pdf';
                    $this->file_digital->storeAs('Afiliacion/FILES_PERSONALES/BENEFICIARIOS', $nombre_file, 'ftp');
                }

                //ESTE ES EL NUEVO ID , DEL NUEVO BENEFICIARIO
                $beneficiarioId = $beneficiario->id;
                // para actualizar lo de las fotos 
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
                //inicio de NOTIFICACIONES 
                // notificaiones para BENEFICIARIO
                //   $user = User::where('id_persona', $beneficiario->id_persona)->first();
                //   $nuevaNotificacion = NotificacionUsuarios::create([
                //       'estado' => 'NO LEIDO',
                //       'prioridades' => 2,
                //       'id_usu_emisor' => auth()->user()->id,
                //       'id_usu_receptor' => $user->id, // para que sea a los encargados de aportes
                //       'fecha_hora_enviada' => Carbon::now(),
                //       'asunto' => 'AFILIACIÓN - MODIFICACIÓN DE DATOS',
                //       'mensaje' => 'Querido usuario, sus datos fueron modificados exitosamente.',
                //   ]);
                //  fin de  notificacion 
                //inicio de NOTIFICACIONES 
                // notificaiones para EL TITULAR del beneficiario
                //   $tit = Titular::find($beneficiario->id_titular);
                //   $userTit = User::where('id_persona', $tit->id_persona)->first();
                //   $nombreBen = $personaBen->nombres . ' ' . $personaBen->p_apellido . ' ' . $personaBen->s_apellido;
                //   $nuevaNotificacion = NotificacionUsuarios::create([
                //       'estado' => 'NO LEIDO',
                //       'prioridades' => 2,
                //       'id_usu_emisor' => auth()->user()->id,
                //       'id_usu_receptor' =>  $userTit->id, // para que sea a los encargados de aportes
                //       'fecha_hora_enviada' => Carbon::now(),
                //       'asunto' => 'AFILIACIÓN - MODIFICACIÓN DE DATOS',
                //       'mensaje' => 'Los datos de su beneficiario: ' . $nombreBen . ' , fueron modificados exitosamente.',
                //   ]);
                //  fin de  notificacion 
            }
            DB::commit();
            $this->dispatch('beneficiarioModificado');
            $this->dispatch('pg:eventRefresh-BeneficiariosTable');
            return redirect()->route('afiliacion.beneficiario.index');
        } catch (\Throwable $e) {
            DB::rollBack();
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Afiliacion:error al actualizar beneficiario", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);

            $this->dispatch('beneficiarioCancelado');
        }
    }

    public function atras()
    {
        return redirect()->route('afiliacion.beneficiario.index');
    }

    public function cancelarBen()
    {
        $this->dispatch('beneficiarioCancelado');
        return redirect()->route('afiliacion.beneficiario.index');
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

    public function recibirLonLat($d_lat, $d_lng)
    {

        $this->latitud = $d_lat;
        $this->longitud = $d_lng;
        $this->latitudRegistered = $d_lat;
        $this->longitudRegistered = $d_lng;
        $this->ubicacion_select = 1;
    }
    // ======= FILES DIGITAELS
    public $tieneFileDigital = false;
    public $pdf_vista;
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

    public function abrirModalFileEditar()
    {
        $this->rowId = $this->id_beneficiario;
        $this->dispatch('modalVerPdfTitular');
    }
    public function cerrarModalVerTitular()
    {
        $this->dispatch('modalVerPdfTitularCerrar');
    }

    #[On('descargarArchivoMotivoBeneficiario')]
    public function descargarArchivoMotivoBeneficiario($data)
    {
        // DB::beginTransaction();
        $descarga = DescargaFilePersonal::create([
            'id_beneficiario' => $this->id_beneficiario,
            'motivo' => $data,
            'id_user_created' => auth()->user()->id,
        ]);

        //$filename = $this->idTit;
        return redirect()->route(
            'descarga.downloadFile',
            [
                $this->ruta,
                $this->id_beneficiario
            ]
        );
    }
}
