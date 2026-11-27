<?php

namespace App\Livewire\Afiliacion\Titulares;

use Livewire\Component;
use App\Models\Administracion\Persona;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\TitularInstitucion;
use App\Models\Administracion\Residencia;
use App\Models\Administracion\Zona;
use App\Models\Administracion\Municipio;
use App\Models\Administracion\Provincia;
use App\Models\Administracion\Departamento;
use App\Models\Administracion\EstadoCivil;
use App\Models\Aportes\ContratoVoluntario;
use App\Models\Aportes\Institucion;
use App\Models\Administracion\NotificacionUsuarios;
use App\Models\Afiliacion\CodigoInstitucionSsu;
use App\Models\Afiliacion\HCarnet;
use App\Models\Afiliacion\DescargaFilePersonal;
use Livewire\WithFileUploads;
use App\Models\Aportes\ClaseAportante;
use App\Models\User;
use Livewire\Attributes\Validate;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Http;

class TitularesEdit extends Component
{
    use WithFileUploads;
    public $idPersona, $fecha_nacimiento = "1993-10-30", $idTit, $id_titularF;
    public $nombre_cod, $tipo_titular, $tipo_beneficiario, $clase_institucion;
    public $nombre;
    public $carnet;
    public $complemento;
    public $fecha_nacimiento_literal;
    public $matricula, $id_estado_civil, $estado_civil, $tipoDoc;
    public $id_estado_civil_actual;
    public $sexo;
    public $sexoTexto;
    public $mun_nacimiento, $prov_nacimiento, $dep_nacimiento, $dept_exp;
    public $sangre;
    public $fecha_afiliacion, $fecha_afiliacion_literal;
    public $fecha_reafiliacion, $fecha_reafiliacion_literal, $fecha_ultima_edicion;
    public $fecha_reafiliacion_r;
    public $fechaVencimientoCarnet;
    public $estado_vigencia;
    public $estado_requisitos;
    public $grupo_sanguineo, $permanente, $permanenteSwitch;

    public $edad;
    #[Validate]
    public $direccion;
    public $zona;
    public $zonaId;
    #[Validate]
    public $nro_vivienda;
    #[Validate]
    public $telefono;
    #[Validate]
    public $correo;
    #[Validate]
    public $nombre_referencia;
    #[Validate]
    public $telefono_referencia, $color;
    public $alergias;
    public $observacion;
    public $id_residencia, $municipio, $nacimiento_municipio;
    public $tipo_aporte, $tipo_aporte_ins, $tipoAporteNombre;
    public $fotito, $motivo;
    public $cargo, $salario, $item, $carga_horaria, $titularidad;
    public $titularInstitucion = [];
    public $zonasSelect = [];
    public $datosTitular, $tipo_rentista, $perteneceRentista = false;
    public $nombreInsPatronal, $codigoTitular, $codigoBeneficiario;
    public $file_digital_sangre, $file_digital;
    public $grupoFamiliarDatos = [];
    public $estadoCivilSelect = [];

    public $foto, $fotoAntigua;
    public $latitud;
    public $longitud;
    public $d_latitud, $d_longitud;
    public $latitudRegistered;
    public $longitudRegistered;
    public $ubicacion_select = 0;
    public $ubicacionTexto = 'NO SELECCIONADO';
    public $institucionTit;
    public $departamento;
    public $provincias = [];
    public $municipios = [];
    public $idDeptExp, $id_mun_nacimiento, $id_provincia, $id_departamento;
    public $rowId;
    public $ruta = 'Titulares';
    public function rules()
    {
        return [
            'direccion' => 'required|min:5',
            'zona' => 'required',
            'nro_vivienda' => 'required|min:1',
            'telefono' => 'required|min:6',
            // 'correo' => 'required|email',
            'nombre_referencia' => 'required|min:2',
            'telefono_referencia' => 'required|min:6',
            'estado_civil' => 'required',
            'file_digital' => 'nullable|mimes:pdf|max:51200',
            // 'foto' => 'required|image|mimes:jpg,jpeg,png', 

            // 'id_mun_nacimiento'  => 'required',
        ];
    }

    public function mount()
    {
        //dd($this->id_titularF, $this->idTit);

        $this->zonasSelect = Zona::orderBy('nombre', 'asc')->get();
        $this->estadoCivilSelect = EstadoCivil::all();
        $this->departamento = Departamento::get();
        $this->mostrarDatosTitular($this->idTit);

        $this->buscarSiTieneFileDigital($this->idTit);
    }
    public function render()
    {
        return view('livewire.Afiliacion.titulares.titulares-edit');
    }
    public function ObtenerProvincia()
    {
        $this->provincias = Provincia::where('id_departamento', '=', $this->id_departamento)->get();
    }
    public function ObtenerMunicipio()
    {
        $this->municipios = Municipio::where('id_provincia', '=', $this->id_provincia)->get();
    }
    public function mostrarDatosTitular($idTit)
    {
        //  dd('asdas',);
        if ($idTit) {
            $titular = Titular::find($idTit);

            $this->fecha_afiliacion = $titular->fecha_afiliacion;
            $this->tipo_rentista = $titular->tipo_rentista;
            $this->fecha_afiliacion_literal = Carbon::parse($this->fecha_afiliacion)->Format('d /m/ Y ');
            $this->fecha_reafiliacion = $titular->fecha_reafiliacion;
            if ($this->fecha_reafiliacion != null) {
                $this->fecha_reafiliacion_literal = Carbon::parse($this->fecha_reafiliacion)->Format('d /m/ Y ');
            } else {
                $this->fecha_reafiliacion_literal = "SIN DATO";
            }
            if ($titular->updated_at) {
                $this->fecha_ultima_edicion = mb_strtoupper(Carbon::parse($titular->updated_at)->Format('d /m/ Y'));
            } else {
                $this->fecha_ultima_edicion = "SIN DATO";
            }

            $this->estado_vigencia = $titular->estado_vigencia;
            $this->permanenteSwitch = $titular->permanente;
            $this->permanente = $titular->permanente ? 'PERMANENTE' : 'EVENTUAL';
            $this->estado_requisitos = $titular->estado_requisitos;
            $this->telefono = $titular->telefono;
            $this->correo = $titular->correo;
            $this->nombre_referencia = $titular->nombre_referencia;
            $this->telefono_referencia = $titular->telefono_referencia;

            $this->tipo_titular = $titular->tipo_titular;
            $this->observacion = $titular->observacion;
            if ($this->observacion == null) {
                $this->observacion = 'NO';
            }
            // Paso 1. obtener el filename de la foto
            $this->fotoAntigua = $this->obtenerImagenBase64($titular->foto_nombre);
            $personaActual = Persona::find($titular->id_persona);
            $this->idPersona = $personaActual->id;
            $this->nombre = $personaActual->p_apellido . ' ' . $personaActual->s_apellido . ' ' . $personaActual->nombres;
            $this->complemento = $personaActual->complemento;
            if ($this->complemento == null) {
                $this->carnet = $personaActual->ci;
            } else {
                $this->carnet = $personaActual->ci . ' - ' . $personaActual->complemento;;
            }
            $this->sangre = $personaActual->grupo_sanguineo;
            if ($this->sangre == null) {
                $this->sangre = 'NO REALIZADO';
            }
            $this->alergias = $personaActual->alergias;
            if ($this->alergias == null) {
                $this->alergias = 'NINGUNO';
            }
            $this->fecha_nacimiento = $personaActual->fecha_nacimiento;
            $this->fecha_nacimiento_literal = mb_strtoupper(Carbon::parse($this->fecha_nacimiento)->translatedFormat('d \d\e F \d\e Y '));
            $this->edad = Carbon::parse($personaActual->fecha_nacimiento)->age;
            $this->matricula = $personaActual->matricula_seguro;
            $this->sexo = $this->setSexoTexto($personaActual->sexo);
            $es_extranjero = $personaActual->es_extranjero;
            if ($es_extranjero == true) {
                $this->tipoDoc = 'EXTRANJERO';
            } else {
                $this->tipoDoc = 'NACIONAL';
            }
            $this->idDeptExp = $personaActual->id_dept_exp;
            if ($this->idDeptExp) {
                $deptExp = Departamento::find($this->idDeptExp);
                $this->dept_exp = $deptExp->sigla;
            } else {
                $this->dept_exp = null;
            }

            $this->id_mun_nacimiento = $personaActual->id_nacimiento_municipio;
            if ($this->id_mun_nacimiento) {
                $municipioNacimiento = Municipio::find($this->id_mun_nacimiento);
                $this->mun_nacimiento = $municipioNacimiento->nombre;
                $this->id_provincia = $municipioNacimiento->id_provincia;
                $provinciaNacimiento = Provincia::find($this->id_provincia);
                $this->prov_nacimiento = $provinciaNacimiento->nombre;
                $this->id_departamento = $provinciaNacimiento->id_departamento;
                $departamentoNacimiento = Departamento::find($this->id_departamento);
                $this->dep_nacimiento = $departamentoNacimiento->nombre;
                $this->provincias = Provincia::all();
                $this->municipios = Municipio::all();
            } else {
                $this->mun_nacimiento = 'No se encontraron datos';
                $this->prov_nacimiento = 'No se encontraron datos';
                $this->dep_nacimiento = 'No se encontraron datos';
            }
            $id_estado_civil = $personaActual->id_estado_civil;
            $estadocivilActual = EstadoCivil::find($id_estado_civil);
            if ($estadocivilActual != null) {
                $this->estado_civil = $estadocivilActual->nombre;
            } else {
                $this->estado_civil = 'SIN DATO';
            }
            $residenciaActual = Residencia::find($titular->id_residencia);
            $this->direccion = $residenciaActual->direccion;
            $this->nro_vivienda = $residenciaActual->nro_vivienda;
            $this->latitudRegistered = $residenciaActual->latitud;
            $this->longitudRegistered = $residenciaActual->longitud;
            $this->d_latitud = $this->latitudRegistered;
            $this->d_longitud = $this->longitudRegistered;
            $zonaActual = Zona::find($residenciaActual->id_zona);
            $this->zona = $zonaActual->nombre;
            $this->zonaId = $zonaActual->id;

            $this->tipoAporteNombre = $titular->tipo_titular ? 'INSTITUCIONAL' : 'VOLUNTARIO';
            $this->institucionTit = TitularInstitucion::where('id_titular', $idTit)
                ->with([
                    'titularInstitucionConInstitucion',
                    'titularInstitucionConInstitucion.claseAportanteInstitucion',
                    'titularInstitucionConInstitucion.codigosInstitucion'
                ])
                ->where('estado', true)
                ->orderBy('id', 'asc')
                ->first();
            if ($this->institucionTit) {
                if ($this->institucionTit?->titularInstitucionConInstitucion?->claseAportanteInstitucion?->nombre === 'RENTISTA') {
                    $this->perteneceRentista = true;
                }
                $id_institucion = $this->institucionTit->id_institucion;
                $this->nombreInsPatronal = $this->institucionTit->titularInstitucionConInstitucion->nombre;
                $codigoIns = CodigoInstitucionSsu::where('id_institucion', $id_institucion)->exists();
                if ($codigoIns) {
                    $codTitular = $this->institucionTit->titularInstitucionConInstitucion->codigosInstitucion->codigo . '' . $this->institucionTit->titularInstitucionConInstitucion->codigosInstitucion->tipo_titular;
                    if ($codTitular) {
                        $this->codigoTitular = $codTitular;
                    } else {
                        $this->codigoTitular = '';
                    }
                } else {
                    $this->codigoTitular = '';
                }
                $this->cargo = $this->institucionTit->cargo_actual;
                $this->salario = $this->institucionTit->salario;
                $this->item = $this->institucionTit->item;
                $this->titularidad = $this->institucionTit->titularidad;
                $this->carga_horaria = $this->institucionTit->carga_horaria;
            }

            $carnetTitular = HCarnet::where('id_persona', $personaActual->id)
                ->orderBy('id', 'desc')
                ->first();

            if ($this->tipoAporteNombre == 'INSTITUCIONAL') {
                if ($titular->permanente) {
                    $this->color = match ($titular->tipo_rentista) {
                        'TITULAR-SORTEO' => '#5d5757', // Rentista Sorteo 4
                        'TITULAR-RENTISTA' => '#e0241c', // Rentista Jubilado 3 rojo
                        default => '#e0241c', // Permanente titular 1
                    };
                } else {
                    $this->color = match ($titular->tipo_rentista) {
                        'TITULAR-SORTEO' => '#5d5757', // Rentista Sorteo 4
                        'VIUDEDAD' => '#e0241c', // Viudedad 5
                        'ORFANDAD' => ($this->edad > 19 ? '#0b61b7' : '#e0241c'), // Orfandad Mayor (6) o Menor (7)
                        default => '#0b61b7', // Eventual 2 azul
                    };
                }
            } elseif ($this->tipoAporteNombre == 'VOLUNTARIO') {
                $this->color = '#0b61b7'; // Voluntario 8
            } else {
                $this->color = '#c4dff3'; // No definido 0
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

    public function atras()
    {
        return redirect()->route('afiliacion.titulares.index');
    }

    #[On('actualizarTitular')]
    public function actualizarTitular()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            $titular = Titular::find($this->idTit);
            if ($titular) {
                $titular->correo = $this->correo;
                $titular->estado_requisitos = $this->estado_requisitos;
                $titular->nombre_referencia = $this->nombre_referencia;
                $titular->telefono_referencia = $this->telefono_referencia;
                $titular->observacion = $this->observacion;
                $titular->telefono = $this->telefono;
                $titular->tipo_rentista = $this->tipo_rentista;
                $titular->permanente = $this->permanenteSwitch;
                $titular->id_user_updated = auth()->user()->id;

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
                $titular->update([
                    'id_residencia' => $residenciaId,
                ]);

                $titularPersona = Persona::find($titular->id_persona);
                if ($titularPersona) {
                    $datosActualizados = [];
                    if (!is_null($this->id_estado_civil)) {
                        $datosActualizados['id_estado_civil'] = $this->id_estado_civil;
                    }
                    if (!is_null($this->alergias)) {
                        $datosActualizados['alergias'] = $this->alergias;
                    }
                    if (!is_null($this->id_mun_nacimiento)) {
                        $datosActualizados['id_nacimiento_municipio'] = $this->id_mun_nacimiento;
                    }

                    if (!empty($datosActualizados)) {
                        $titularPersona->update($datosActualizados);
                    }
                }

                // actualizar datos institucionales
                if ($this->tipoAporteNombre == 'INSTITUCIONAL') {
                    $titularInstitucin = TitularInstitucion::where('id_titular', $titular->id)
                        ->orderBy('id', 'asc')
                        ->first();
                    $titularInstitucin->update([
                        'cargo_actual' => $this->cargo,
                        'salario' => $this->salario,
                        'item' => $this->item,
                        'carga_horaria' => $this->carga_horaria,
                        'titularidad' => $this->titularidad,
                        'id_user_updated' => auth()->user()->id,
                    ]);
                }
                //inicio de NOTIFICACIONES 
                $user = User::where('id_persona', $titular->id_persona)->first();
                if ($user) {
                    NotificacionUsuarios::create([
                        'estado' => 'NO LEIDO',
                        'prioridades' => 2,
                        'id_usu_emisor' => auth()->user()->id,
                        'id_usu_receptor' => $user->id,
                        'fecha_hora_enviada' => Carbon::now(),
                        'asunto' => 'AFILIACIÓN - MODIFICACIÓN DE DATOS',
                        'mensaje' => 'Querido usuario, sus datos fueron modificados exitosamente.',
                    ]);
                }
                //  fin de  notificacion 
                $titular->save();
                $nombre_foto = $titular->id . '-T.jpg';
                //condicion para quitar el fondo de una imagen desde el docker
                if ($this->foto) {
                    $url = config('services.rembg.url');
                    $imagePath = $this->foto->getRealPath();
                    $imageName = 'foto_' . uniqid() . '.png';

                    try {
                        //aca enviamos la imagen a que sea procesado al docker
                        $response = Http::timeout(90)->attach(
                            'image',
                            file_get_contents($imagePath),
                            basename($imagePath)
                        )->post($url);

                        if ($response->successful() && strlen($response->body()) > 1000) {
                            // Imagen sin fondo recibida correctamente
                            $imageWithoutBg = $response->body();
                        } else {
                            // Si falla o devuelve respuesta vacía
                            throw new \Exception('Error al procesar imagen con rembg');
                        }
                    } catch (\Exception $e) {
                        // Si hay error de red, timeout o rembg falla
                        $imageWithoutBg = file_get_contents($imagePath); // se usará la imagen original
                    }
                    //dd($nombre_foto, $imageWithoutBg);
                    // Guardamos la imagen
                    Storage::disk('ftp')->put('Afiliacion/FOTOS/' . $nombre_foto, $imageWithoutBg);

                    $titular->update([
                        'foto_nombre' => $nombre_foto,
                    ]);
                }
                //=========== file digital ====
                if ($this->file_digital) {
                    $nombre_file = $this->idTit . '.pdf';
                    $this->file_digital->storeAs('Afiliacion/FILES_PERSONALES/TITULARES', $nombre_file, 'ftp');
                }

                DB::commit();
                $this->dispatch('titularModificada');
                $this->dispatch('pg:eventRefresh-TitularesInstitucionalesVoluntarios');
                return redirect()->route('afiliacion.titulares.index');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('titularNoModificada');
            //return redirect()->route('afiliacion.titulares.index');
        }
    }
    #[On('recibirLonLat')]
    public function recibirLonLat($d_lat, $d_lng)
    {

        $this->latitud = $d_lat;
        $this->longitud = $d_lng;
        $this->latitudRegistered = $d_lat;
        $this->longitudRegistered = $d_lng;
        $this->ubicacion_select = 1;
    }
    public function cancelarTitular()
    {
        $this->dispatch('titularCancelado');
        return redirect()->route('afiliacion.titulares.index');
    }

    public $tieneFileDigital = false;
    public $pdf_vista;
    public function buscarSiTieneFileDigital($id_titular)
    {
        try {
            $path = 'Afiliacion/FILES_PERSONALES/TITULARES/' . $id_titular . '.pdf';
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
        $this->rowId = $this->idTit;
        $this->dispatch('modalVerPdfTitular');
    }

    #[On('descargarArchivoMotivo')]
    public function descargarArchivoMotivo($data)
    {
        // DB::beginTransaction();
        $descarga = DescargaFilePersonal::create([
            'id_titular' => $this->idTit,
            'motivo' => $data,
            'id_user_created' => auth()->user()->id,
        ]);

        //$filename = $this->idTit;
        return redirect()->route(
            'descarga.downloadFile',
            [
                $this->ruta,
                $this->idTit
            ]
        );
    }

    public function cerrarModalVerTitular()
    {
        $this->dispatch('modalVerPdfTitularCerrar');
    }
}
