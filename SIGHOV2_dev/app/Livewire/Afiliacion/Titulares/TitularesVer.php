<?php

namespace App\Livewire\Afiliacion\Titulares;

use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\Administracion\Departamento;
use App\Models\Administracion\EstadoCivil;
use App\Models\Administracion\Municipio;
use App\Models\Administracion\NotificacionUsuarios;
use App\Models\Administracion\Persona;
use App\Models\Administracion\Provincia;
use App\Models\Administracion\Residencia;
use App\Models\Administracion\Zona;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\CodigoInstitucionSsu;
use App\Models\Afiliacion\DescargaFilePersonal;
use App\Models\Afiliacion\HCarnet;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\TitularInstitucion;
use App\Models\Aportes\ContratoVoluntario;
use App\Models\Aportes\Institucion;
use App\Models\Migracion\MigracionTitular;
use App\Models\User;
use App\Services\Migracion\Afiliacion\MigrarTitular;
use App\Services\TitularesAsuss;
use App\Services\TitularesGehma2;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

use Livewire\WithFileUploads;

class TitularesVer extends Component
{
    public $idtit;

    public $replicasgehma2;

    public $idTit;

    public $modal;

    public $idPersona;

    public $primerapellido;

    public $tipo_titular;

    public $nombre;

    public $carnet;

    public $complemento;

    public $fecha_nacimiento;

    public $fecha_nacimiento_literal;

    public $matricula;

    public $id_estado_civil;

    public $estado_civil;

    public $tipoDoc;

    public $id_estado_civil_actual;

    public $sexo;

    public $sexoTexto;

    public $mun_nacimiento;

    public $prov_nacimiento;

    public $dep_nacimiento;

    public $dept_exp;

    public $sangre;

    public $fecha_afiliacion;

    public $fecha_afiliacion_literal;

    public $fecha_reafiliacion;

    public $fecha_reafiliacion_literal;

    public $fecha_ultima_edicion;

    public $fecha_reafiliacion_r;

    public $estado_vigencia;

    public $estado_requisitos;

    public $grupo_sanguineo;

    public $edad;

    public $imageUrl;

    public $imageContents;

    public $imageData;

    public $imageBase64;

    public $foto;

    public $fotoGrupoFamiliar;

    public $direccion;

    public $zona;

    public $zonaId;

    public $nro_vivienda;

    public $telefono;

    public $correo;

    public $nombre_referencia;

    public $telefono_referencia;

    public $alergias;

    public $observacion;

    public $id_residencia;

    public $municipio;

    public $nacimiento_municipio;

    public $tipo_aporte;

    public $tipoAporte;

    public $fotito;

    public $motivo;

    public $voluntario;

    public $correoVoluntario;

    public $telefono_1;

    public $telefono_2;

    public $fecha_inicio_contrato;

    public $fecha_fin_contrato;

    public $fecha_inicio_contrato_literal;

    public $fecha_fin_contrato_literal;

    public $latitud;

    public $longitud;

    public $d_latitud;

    public $d_longitud;

    public $latitudRegistered;

    public $longitudRegistered;

    public $ubicacion_select = 0;

    public $ubicacionTexto = 'NO SELECCIONADO';

    public $codigo_institucion;

    public $titularInstitucion = [];

    public $nombreInsPatronal;

    public $zonasSelect = [];

    public $datosTitular;

    public $file_digital_sangre;

    public $grupoFamiliarDatos = [];

    public $estadoCivilSelect = [];

    public $numero_documentoAsuss;

    public $fecha_nacimientoAsuss;

    public $carnet_completoAsuss;

    public $nombreCompletoAsuss;

    public $complemento_documentoAsuss;

    public $sexoAsuss;

    public $estado_civilAsuss;

    public $paisAsuss;

    public $departamentoAsuss;

    public $imagen_fotograficaAsuss;

    public $estado_aseguradoAsuss = [];

    public $estado_fuente_pagoAsuss = [];
    public $instituciones = [];

    public $institucionesRegistradas = [];

    public $institucionNuevaId;

    public $nombreNuevaInstitucion;

    public $tipoNuevaInstitucion;

    public $codigoTitular;

    public $codigoBeneficiario;

    public $nombreNuevoCodigoTitular;

    public $nombreNuevoCodBen;

    public $nuevaFechaIngreso;

    public $nuevoCargoActual;

    public $nuevoItem;

    public $nuevaTitularidad;

    public $nueva_carga_horaria;

    public $nuevoSalario;

    public $permanente;

    public $tipo_rentista;

    public $perteneceRentista = false;

    public $nombre_cod_v;

    public $tipo_titular_v;

    public $tipo_beneficiario_v;

    public $clase_institucion_v;

    public $fecha_emision_r;

    public $fecha_vencimiento_r;

    public $fecha_emision;

    public $fecha_vencimiento;

    public $institucionTit;

    public $indefinido = false;

    public $fecha_emision_formatted;

    public $fecha_vencimiento_formatted;

    public $carnetIndefinido;

    public $motivo_carnet;

    public $debeActualizarReferenciales = false;

    public $debeActualizarResidencia = false;

    public $fechaVencimientoCarnet;

    public $color;

    public $es_carnet_indefinido;

    public $fecha_created;

    public $user_created;

    public $fecha_edicion;

    public $user_edited;

    public $tieneEdicion = false;

    public $titular_data;

    public $text_archivo_grupo;

    public $foto_nombre;

    public $archivo_grupo;

    public $fecha_archivo;

    public $rowId;
    public $pdf_vista;
    public function render()
    {
        return view('livewire.Afiliacion.titulares.titulares-ver');
    }

    #[On('verTitular')]
    public function verTitular($data)
    {
        $this->mostrarDatosTitular($data);
        $this->buscarSiTieneFileDigital($data);
        $this->dispatch('abrirModalVerTitular');
    }

    public function mostrarDatosTitular($idTit)
    {
        try {
            if ($idTit) {
                $titular = Titular::with('usuarioCreador', 'usuarioEditor')->find($idTit);
                //dd($titular);
                $this->idTit = $idTit;
                $this->fecha_afiliacion = $titular->fecha_afiliacion;
                $this->tipo_rentista = $titular->tipo_rentista;
                $this->fecha_afiliacion_literal = mb_strtoupper(Carbon::parse($this->fecha_afiliacion)->Format('d /m/ Y'));
                $this->fecha_reafiliacion = $titular->fecha_reafiliacion;
                // archivo grupo
                $this->archivo_grupo = $titular->archivo_grupo ?? 'SIN DATO';
                $this->fecha_archivo = $titular->fecha_archivo == null ? 'SIN DATO' : mb_strtoupper(Carbon::parse($titular->fecha_archivo)->Format('d /m/ Y'));
                // -----------------
                if ($this->fecha_reafiliacion != null) {
                    $this->fecha_reafiliacion_literal = mb_strtoupper(Carbon::parse($this->fecha_reafiliacion)->Format('d /m/ Y'));
                } else {
                    $this->fecha_reafiliacion_literal = 'SIN DATO';
                }
                if ($titular->updated_at) {
                    $this->fecha_ultima_edicion = mb_strtoupper(Carbon::parse($titular->updated_at)->Format('d /m/ Y'));
                } else {
                    $this->fecha_ultima_edicion = 'SIN DATO';
                }
                //modificar para que muestre en otro formato
                //  $this->fecha_afiliacion->format('d-m-Y');
                //  $this->fecha_reafiliacion->format('d-m-Y');
                $this->permanente = $titular->permanente ? 'PERMANENTE' : 'EVENTUAL';

                $this->estado_vigencia = $titular->estado_vigencia;
                $this->estado_requisitos = $titular->estado_requisitos;
                $this->telefono = $titular->telefono;
                $this->correo = $titular->correo;
                $this->nombre_referencia = $titular->nombre_referencia;
                $this->telefono_referencia = $titular->telefono_referencia;
                $this->tipo_titular = $titular->tipo_titular;
                $this->foto_nombre = $titular->foto_nombre;
                $this->observacion = $titular->observacion;
                if ($this->observacion == null) {
                    $this->observacion = 'NO';
                }
                // Paso 1. obtener el filename de la foto
                $this->foto = $this->obtenerImagenBase64($titular->foto_nombre);
                //dd($titular->foto_nombre);
                $personaActual = Persona::find($titular->id_persona);
                $this->idPersona = $personaActual->id;
                $this->nombre = $personaActual->p_apellido . ' ' . $personaActual->s_apellido . ' ' . $personaActual->nombres;
                $this->complemento = $personaActual->complemento;
                if ($this->complemento == null) {
                    $this->carnet = $personaActual->ci;
                } else {
                    $this->carnet = $personaActual->ci . ' - ' . $personaActual->complemento;
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
                $this->fecha_nacimiento_literal = mb_strtoupper(Carbon::parse($this->fecha_nacimiento)->Format('d/m/Y '));
                $this->edad = Carbon::parse($personaActual->fecha_nacimiento)->age;
                $this->matricula = $personaActual->matricula_seguro;
                $this->sexo = $this->setSexoTexto($personaActual->sexo);
                $es_extranjero = $personaActual->es_extranjero;
                if ($es_extranjero == true) {
                    $this->tipoDoc = 'EXTRANJERO';
                } else {
                    $this->tipoDoc = 'NACIONAL';
                }
                $idDeptExp = $personaActual->id_dept_exp;
                if ($idDeptExp) {
                    $deptExp = Departamento::find($idDeptExp);
                    $this->dept_exp = $deptExp->sigla;
                } else {
                    $this->dept_exp = null;
                }

                $mun_nacimiento = $personaActual->id_nacimiento_municipio;
                if ($mun_nacimiento) {
                    $municipioNacimiento = Municipio::find($mun_nacimiento);
                    $this->mun_nacimiento = $municipioNacimiento->nombre;
                    $id_provincia = $municipioNacimiento->id_provincia;
                    $provinciaNacimiento = Provincia::find($id_provincia);
                    $this->prov_nacimiento = $provinciaNacimiento->nombre;
                    $id_departamento = $provinciaNacimiento->id_departamento;
                    $departamentoNacimiento = Departamento::find($id_departamento);
                    $this->dep_nacimiento = $departamentoNacimiento->nombre;
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
                    $this->estado_civil = '';
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

                $this->tipoAporte = $titular->tipo_titular ? 'INSTITUCIONAL' : 'VOLUNTARIO';
                // AQUI OBTENEMOS SOLO LA PRIMERA INSTITUCION
                $this->institucionTit = TitularInstitucion::where('id_titular', $idTit)
                    //->where('tipo_institucion', 'PATRONAL')
                    ->where('estado', true)
                    ->with([
                        'titularInstitucionConInstitucion',
                        'titularInstitucionConInstitucion.claseAportanteInstitucion',
                        'titularInstitucionConInstitucion.codigosInstitucion',
                    ])
                    ->orderBy('id', 'asc')
                    ->first();
                // dd($this->institucionTit);
                if ($this->institucionTit) {
                    /// dd('sdas');
                    $id_institucion = $this->institucionTit->id_institucion;
                    $this->nombreInsPatronal = $this->institucionTit->titularInstitucionConInstitucion->nombre;
                    if ($this->institucionTit?->titularInstitucionConInstitucion?->claseAportanteInstitucion?->nombre === 'RENTISTA') {
                        $this->perteneceRentista = true;
                    } else {
                        $this->perteneceRentista = false;
                    }
                    $codigoIns = CodigoInstitucionSsu::where('id_institucion', $id_institucion)->exists();
                    if ($codigoIns) {
                        $codTitular = $this->institucionTit->titularInstitucionConInstitucion->codigosInstitucion->codigo . '' . $this->institucionTit->titularInstitucionConInstitucion->codigosInstitucion->tipo_titular;
                        $this->codigoTitular = $codTitular ?: ' ';
                    } else {
                        $this->codigoTitular = '';
                    }
                } else {
                    // dd('asdsa');
                    $this->nombreInsPatronal = '';
                    $voluntario = Contratovoluntario::where('id_persona', $titular->id_persona)
                        ->where('estado', true)
                        ->orderBy('id', 'desc')
                        ->first();
                    //  dd($voluntario);
                    if ($voluntario) {
                        $this->fecha_inicio_contrato = $voluntario->fecha_inicio_contrato;
                        $this->fecha_inicio_contrato_literal = Carbon::parse($this->fecha_inicio_contrato)->Format('d /m/ Y ');
                        $this->fecha_fin_contrato = $voluntario->fecha_fin_contrato;
                        $this->fecha_fin_contrato_literal = Carbon::parse($this->fecha_fin_contrato)->Format('d /m/ Y ');
                        $this->correoVoluntario = $voluntario->correo;
                        $this->telefono_1 = $voluntario->telefono_1;
                        $this->telefono_2 = $voluntario->telefono_2 ?: 'NO TIENE';
                    } else {
                        $this->fecha_inicio_contrato = 'No se encontraron datos';
                        $this->fecha_inicio_contrato_literal = 'No se encontraron datos';
                        $this->fecha_fin_contrato = 'No se encontraron datos';
                        $this->fecha_fin_contrato_literal = 'No se encontraron datos';
                        $this->correoVoluntario = 'No se encontraron datos';
                        $this->telefono_1 = 'No se encontraron datos';
                        $this->telefono_2 = 'No se encontraron datos';
                    }
                }
                // AQUI OBTENEMOS MAS INSTITUCIONES
                $this->titularInstitucion = TitularInstitucion::where('id_titular', $idTit)
                    ->with([
                        'titularInstitucionConInstitucion',
                        'titularInstitucionConInstitucion.claseAportanteInstitucion',
                        'titularInstitucionConInstitucion.codigosInstitucion',
                    ])
                    ->orderBy('estado', 'desc')
                    ->orderBy('id', 'asc')
                    ->get();

                $carnetTitular = HCarnet::where('id_persona', $personaActual->id)
                    ->orderBy('id', 'desc')
                    ->first();

                if ($this->tipoAporte == 'INSTITUCIONAL') {
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
                } elseif ($this->tipoAporte == 'VOLUNTARIO') {

                    $this->color = '#0b61b7'; // Voluntario 8s
                } else {
                    $this->color = '#c4dff3'; // No definido 0
                }
                if ($carnetTitular) {
                    $fechaVenimiento = $carnetTitular->fecha_vencimiento;
                    $this->fechaVencimientoCarnet = Carbon::parse($fechaVenimiento)->Format('d /m/ Y ');
                    //dd($carnetTitular->indefinido);
                    if ($carnetTitular->indefinido == true) {
                        $this->es_carnet_indefinido = true;
                    } else {
                        $this->es_carnet_indefinido = false;
                    }
                } else {
                    $this->fechaVencimientoCarnet = 'SIN DATO';
                }
                //---- PARA VER DATOS DE CREACO Y EDICION
                $this->user_created = $titular->usuarioCreador?->name ?: 'SIN DATO';
                $this->fecha_created = $titular->created_at;
                if ($titular->id_user_updated) {
                    $this->tieneEdicion = true;
                    $this->user_edited = $titular->usuarioEditor?->name ?: 'SIN DATO';
                    $this->fecha_edicion = $titular->updated_at;
                }
            }
        } catch (\Throwable $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error('Afiliacion:error al mostrar datos del titular', [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR' => $e->getMessage() ?? '',
                'ARCHIVO' => $trace['file'] ?? $e->getFile(),
                'LINEA' => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }
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
    private function obtenerImagenBase64($filename)
    {
        try {
            if ($filename) {
                $path = 'Afiliacion/FOTOS/' . $filename;
                $imageData = Storage::disk('ftp')->get($path);
                $fotito = base64_encode($imageData);

                return $fotito;
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            return null;
        }
    }
    public function verificarFotoTitular($data)
    {
        $id_titular = $data;
        $titular = Titular::where('id', $id_titular)->get();
        // dd($titular[0]->foto_nombre);
        $foto_afiliado = null;
        if ($titular) {
            $nombre_foto_afiliado = $titular[0]->foto_nombre;
            $foto_afiliado = $this->obtenerImagenBase64($nombre_foto_afiliado);
        }

        // $foto_afiliado = 'data:image/png;base64,' . $foto_afiliado;
        return $foto_afiliado;
        // dd($foto_afiliado);
    }

    public $ruta = 'Titulares';
    #[On('descargarArchivoConMotivo')]
    public function descargarArchivoConMotivo($motivo)
    {
        //dd('asdfsd', $this->idTit);
        // DB::beginTransaction();
        $descarga = DescargaFilePersonal::create([
            'id_titular' => $this->idTit,
            'motivo' => $motivo,
            'id_user_created' => auth()->user()->id,
        ]);
        //$descargaId = $descarga->id;
        $this->dispatch('guardado');
        return redirect()->route(
            'descarga.downloadFile',
            [
                $this->ruta,
                $this->idTit
            ]
        );
    }
    public $tieneFileDigital = false;
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

    public function descargarFotoTitular()
    {
        $filename = $this->foto_nombre;

        //dd($filename);
        //  $filename = '-FOTO';
        return redirect()->route(
            'descarga.downloadFoto',
            $filename
        );
    }

    public function abrirModalVerFileDigital()
    {
        // dd('asdas');
        // $this->titularIdParaVer = $this->idTit;
        $this->rowId = $this->idTit;
        $this->dispatch('modalVerPdfTitular');
        // $this->skipRender();
    }



    /*  MODAL para ver la ubicación ---------------------------- */
    // PARA LA UBICACION  ---------------------
    /*  public function AbrirModalUbicacionTitular()
    {
        $this->d_latitud = $this->latitudRegistered;
        $this->d_longitud = $this->longitudRegistered;
        $this->dispatch('ModalAbrirUbicacionTitular');
        // dd($this->latitudRegistered,$this->longitudRegistered);
        //$this->dispatch('inputUpdated', [$this->latitudRegistered, $this->longitudRegistered]);
        //  $this->skipRender();
    } */

    /*     #[On('recibirLonLat')]
    public function recibirLonLat($d_lat, $d_lng)
    {
        $this->latitudRegistered = $d_lat;
        $this->longitudRegistered = $d_lng;
        $this->ubicacion_select = 1;
        $this->ubicacionTexto = 'SELECCIONADO';
    }
 */


    public function verAportesIns($idPersona)
    {
        //dd('sdas');
        $this->idPersona = $idPersona; // Guarda el idPersona
        $this->dispatch('modalVerAportesIns');
        $this->dispatch('setPersonaId', $idPersona);
    }

    public function verAportesVol($idPersona)
    {
        $this->dispatch('modalVerAportesVol');
        $this->dispatch('setPersonaId', $idPersona);
    }

    public function cerrarModalVerTitular()
    {

        $this->dispatch('modalVerPdfTitularCerrar');
    }
}
