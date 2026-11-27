<?php

namespace App\Livewire\Afiliacion\Titulares;

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
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class TitularesMain extends Component
{
    // para ver la replica

    use WithFileUploads;

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

    //datos de las instituciones ya registradas anteriormente y las que se agregaran
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
    public $nro_recibo, $nro_recibo_reimpresion;

    #[Locked]
    public $pdf_vista;

    protected $listeners = ['descargarArchivo', 'descargarArchivoMotivo'];

    public function render()
    {
        // $titulartes=Titular::all();
        // dd($titulartes->count());
        /*$datos_afiliados_ghema2=MigracionTitular::all();
        dd("aca",$datos_afiliados_ghema2);*/
        return view('livewire.Afiliacion.titulares.titulares-main');
    }

    public function mount()
    {
        //dd(Titular::all());

        //$migrar = new MigrarTitular();
        //$migrar->migrarTitular();

        //$ins = new TitularesGehma2();
        //$insertar = $ins->insercionTitularGehma2(120);
        //dd($insertar);
        //$baja = new TitularesAsuss();
        //$bajaAsuss = $baja->bajaAfiliadoTitular(151, '2024-07-04', '2024-07-01', 'BAJA_DEFINITIVA', 'BAJA DEFINITIVA ASUSS');
        //dd($bajaAsuss);
        //$detalle = new TitularesAsuss();
        //$detalleAsuss = $detalle->detalleTitularAsuss(129);
        //dd($detalleAsuss);
    }

    /* FUNCION PARA MOSTRAR DATOS TITULAR ---------------------------------------- */
    public function mostrarDatosTitular($idTit)
    {
        try {
            if ($idTit) {
                $titular = Titular::with('usuarioCreador', 'usuarioEditor')->find($idTit);
                //dd($titular);

                $this->archivo_grupo = $titular->archivo_grupo ?? 'SIN DATO';
                $this->fecha_archivo = $titular->fecha_archivo == null ? 'SIN DATO' : mb_strtoupper(Carbon::parse($titular->fecha_archivo)->Format('d /m/ Y'));
                // -----------------
                $this->permanente = $titular->permanente ? 'PERMANENTE' : 'EVENTUAL';

                $this->estado_vigencia = $titular->estado_vigencia;

                $this->telefono = $titular->telefono;
                $this->correo = $titular->correo;


                $this->tipo_titular = $titular->tipo_titular;
                $this->foto_nombre = $titular->foto_nombre;

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

                $this->fecha_nacimiento = $personaActual->fecha_nacimiento;
                $this->fecha_nacimiento_literal = mb_strtoupper(Carbon::parse($this->fecha_nacimiento)->Format('d/m/Y '));
                $this->edad = Carbon::parse($personaActual->fecha_nacimiento)->age;
                $this->matricula = $personaActual->matricula_seguro;
                $this->sexo = $this->setSexoTexto($personaActual->sexo);
                $this->tipoAporte = $titular->tipo_titular ? 'INSTITUCIONAL' : 'VOLUNTARIO';
                // dd($this->color,$this->tipoAporte);
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

    // Funcion para obtener imagenes desde la FTP
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

    /* PARA AGREGAR SANGRE DEL TITULAR ---------------------------------------- */
    #[On('tipoSangreTitular')]
    public function tipoSangreTitular($data)
    {
        $this->idTit = $data;
        $this->dispatch('tipoSangreModal');
    }

    #[On('AgregarTipoSangre')]
    public function AgregarTipoSangre()
    {
        try {
            if ($this->grupo_sanguineo != null) {
                $titular = Titular::find($this->idTit);
                $personaTitularId = $titular->id_persona;
                $persona = Persona::find($personaTitularId);
                if ($persona) {
                    $persona->grupo_sanguineo = $this->grupo_sanguineo;
                    $titular->update([
                        'estado_requisitos' => true,
                    ]);
                    //$titular->estado_requisitos = 1;
                    $persona->save();
                    // $nombre_file_sangre = $titular->id . '-T-SANGRE.pdf';
                    //dd($this->file_digital_sangre);
                    // $this->file_digital_sangre->storeAs('Afiliacion/TIPO_SANGRE/TITULAR/', $nombre_file_sangre, 'ftp');
                }
                //inicio de NOTIFICACIONES
                $user = User::where('id_persona', $personaTitularId)->first();
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
                $this->dispatch('TipoSangreGuardaro');
                $this->dispatch('pg:eventRefresh-TitularesInstitucionalesVoluntarios');
                $this->reset('grupo_sanguineo', 'file_digital_sangre');
            } else {
                $this->dispatch('TipoSangreFaltanCampos');
            }
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('TipoSangreNoGuardaro');
        }
    }

    /* PARA EL GRUPO FAMILIAR  DEL TITULAR ---------------------------------------- */
    #[On('verGrupoFamiliar')]
    public function verGrupoFamiliar($data)
    {
        $this->mostrarDatosTitular($data);
        $this->grupoFamiliar($data);
        $this->idTit = $data;
        $this->dispatch('modalGrupoFamiliar');
    }

    public function grupoFamiliar($idTitular)
    {
        if ($idTitular) {
            $grupoFamiliar = Beneficiario::where('id_titular', $idTitular)->where('estado', true)
                ->with([
                    'beneficiarioPersona',
                    'beneficiarioPersona.departamentoPersona',
                    'beneficiarioPersona.estadoCivilPersona',
                    'beneficiarioPersona.tipoAseguradoPersona',
                ])
                ->get();
            $grupoFamiliar = $grupoFamiliar->map(function ($familiar) {
                $familiar->foto_nombre = $this->obtenerImagenBase64($familiar->foto_nombre);
                if ($familiar->beneficiarioPersona) {
                    $familiar->beneficiarioPersona->sexo_texto = $this->setSexoTexto($familiar->beneficiarioPersona->sexo);
                }

                return $familiar;
            });

            //dd($grupoFamiliar->isEmpty());
            if (! $grupoFamiliar->isEmpty()) {
                $this->grupoFamiliarDatos = $grupoFamiliar;
            } else {
                $this->grupoFamiliarDatos = [];
            }
        } else {
            $this->grupoFamiliarDatos = [];
        }
    }


    /*    PARA FUNCION ASUSS -----------------------------------*/
    #[On('verAsuss')]
    public function verAsuss($data)
    {
        $this->idTit = $data;
        $this->mostrarDatosTitular($this->idTit);
        $verificar = new TitularesAsuss();
        $verificacion = $verificar->validarTitularAsuss($this->carnet, $this->complemento, $this->fecha_nacimiento);
        //dd($verificacion);
        //Datos Personales
        if (! isset($verificacion[0]['numero_documento']) || $verificacion[0]['numero_documento'] == null) {
            $this->numero_documentoAsuss = '';
        } else {
            $this->numero_documentoAsuss = $verificacion[0]['numero_documento'];
        }

        if (! isset($verificacion[0]['fecha_nacimiento']) || $verificacion[0]['fecha_nacimiento'] == null) {
            $this->fecha_nacimientoAsuss = '';
        } else {
            $this->fecha_nacimientoAsuss = $verificacion[0]['fecha_nacimiento'];
        }

        if (! isset($verificacion[0]['imagen_fotografica']) || $verificacion[0]['imagen_fotografica'] == null) {
            $this->imagen_fotograficaAsuss = '';
        } else {
            $this->imagen_fotograficaAsuss = $verificacion[0]['imagen_fotografica'];
        }

        if ((! isset($verificacion[0]['nombres']) && ! isset($verificacion[0]['primer_apellido']) && ! isset($verificacion[0]['segundo_apellido'])) || ($verificacion[0]['nombres'] == null && $verificacion[0]['primer_apellido'] == null && $verificacion[0]['segundo_apellido'] == null)) {
            $this->nombreCompletoAsuss = 'NO SE ENCONTRARON DATOS';
        } elseif (! isset($verificacion[0]['primer_apellido']) || $verificacion[0]['primer_apellido'] == null) {
            $this->nombreCompletoAsuss = $verificacion[0]['segundo_apellido'] . ' ' . $verificacion[0]['nombres'];
        } elseif (! isset($verificacion[0]['segundo_apellido']) || $verificacion[0]['segundo_apellido'] == null) {
            $this->nombreCompletoAsuss = $verificacion[0]['primer_apellido'] . ' ' . $verificacion[0]['nombres'];
        } elseif (! isset($verificacion[0]['nombres']) || $verificacion[0]['nombres'] == null) {
            $this->nombreCompletoAsuss = 'NO SE ENCONTRARON DATOS';
        } else {
            $this->nombreCompletoAsuss = $verificacion[0]['primer_apellido'] . ' ' . $verificacion[0]['segundo_apellido'] . ' ' . $verificacion[0]['nombres'];
        }

        /*if (!isset($verificacion[0]['nombres']) || $verificacion[0]['nombres'] == null ||
            !isset($verificacion[0]['primer_apellido']) || $verificacion[0]['primer_apellido'] == null ||
            !isset($verificacion[0]['segundo_apellido']) || $verificacion[0]['segundo_apellido'] == null) {
            $this->nombreCompletoAsuss = '';
        } else {
            $this->nombreCompletoAsuss = $verificacion[0]['primer_apellido'] . ' ' . $verificacion[0]['segundo_apellido'] . ' ' . $verificacion[0]['nombres'];
        }*/

        if (! isset($verificacion[0]['complemento_documento']) || $verificacion[0]['complemento_documento'] == 'NULL') {
            $this->complemento_documentoAsuss = '';
        } else {
            $this->complemento_documentoAsuss = $verificacion[0]['complemento_documento'];
        }

        if ($this->complemento_documentoAsuss == '') {
            $this->carnet_completoAsuss = $this->numero_documentoAsuss;
        } else {
            $this->carnet_completoAsuss = $this->numero_documentoAsuss . '-' . $this->complemento_documentoAsuss;
        }

        if (! isset($verificacion[0]['sexo'])) {
            $this->sexoAsuss = '';
        } else {
            $this->sexoAsuss = $verificacion[0]['sexo'];
        }

        if (! isset($verificacion[0]['estado_civil'])) {
            $this->estado_civilAsuss = '';
        } else {
            $this->estado_civilAsuss = $verificacion[0]['estado_civil'];
        }

        if (! isset($verificacion[0]['pais'])) {
            $this->paisAsuss = '';
        } else {
            $this->paisAsuss = $verificacion[0]['pais'];
        }

        if (! isset($verificacion[0]['departamento'])) {
            $this->departamentoAsuss = '';
        } else {
            $this->departamentoAsuss = $verificacion[0]['departamento'];
        }

        //Datos poblacion asegurada (BDPA)

        $this->estado_aseguradoAsuss = $verificacion[0]['estado_asegurado']['detalle'];

        //Datos ovt (OVT)
        if (! isset($verificacion[0]['estado_fuente_pago']['detalle'])) {
            $this->estado_fuente_pagoAsuss = [];
        } else {
            $this->estado_fuente_pagoAsuss = $verificacion[0]['estado_fuente_pago']['detalle'];
        }

        $this->dispatch('modalValidacionAsuss');
    }

    public function datosInstitucionNueva()
    {
        $nuevaInstitucion = Institucion::where('id', $this->institucionNuevaId)->with('claseAportanteInstitucion')->first();
        $this->nombreNuevaInstitucion = $nuevaInstitucion->nombre;
        $this->tipoNuevaInstitucion = $nuevaInstitucion->claseAportanteInstitucion->tipo;
        $nuevosCodigos = CodigoInstitucionSsu::where('id_institucion', $nuevaInstitucion->id)->first();
        if (! $nuevosCodigos) {
            $this->nombreNuevoCodigoTitular = '';
            $this->nombreNuevoCodBen = '';
        } else {
            $this->nombreNuevoCodigoTitular = $nuevosCodigos->codigo . $nuevosCodigos->tipo_titular;
            $this->nombreNuevoCodBen = $nuevosCodigos->codigo . $nuevosCodigos->tipo_beneficiario;
        }
        $this->skipRender();
    }
    /* AGREGAR UNA NUEVA INSTITUCION ------------------------------- */
    /*     #[On('agregarFunctionInstitucion')]
    public function agregarFunctionInstitucion($data)
    {
        dd('sdas');
    }
 */

    /* PARA EL CARNET -------------------------------------------- */
    public function recarnetizacion()
    {
        //  $this->js('window.open("/afiliacion/titular/carnet_pdf/' . $this->idTit . '", "_blank")');
        //  $this->dispatch('RegistroCarnetCorrecto');
        if ($this->motivo_carnet != null && $this->nro_recibo_reimpresion != null) {
            $r_carnet = HCarnet::create([
                'fecha_emision' => $this->fecha_emision_r,
                'fecha_vencimiento' => $this->fecha_vencimiento,
                'id_persona' => $this->idPersona,
                'indefinido' => $this->indefinido,
                'motivo' => $this->motivo_carnet,
                'nro_recibo' => $this->nro_recibo_reimpresion,
                'id_user_created' => auth()->user()->id,
            ]);
            if (! $r_carnet) {
                DB::rollBack();
                $this->dispatch('RegistroCarnetInCorrecto');
                $this->resetFormCarnet();

                return;
            }
            DB::commit();
            $this->js('window.open("/afiliacion/titular/carnet_pdf/' . $this->idTit . '", "_blank")');
            $this->dispatch('RegistroCarnetCorrecto');
            $this->resetFormCarnet();
        } else {
            $this->dispatch('faltaMotivoCarnet');

            return;
        }
    }

    #[On('carnetizacion')]
    public function carnetizacion($data, $clase)
    {
        try {
            $this->verificarDatos($data);
            if ($this->debeActualizarReferenciales && $this->debeActualizarResidencia) {
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
            /// $this->fecha_vencimiento_r= Carbon::now()->toDateString();
            // dd($data, $clase);
            $titular = Titular::find($data);
            if ($titular->permanente && $titular->tipo_rentista == 'TITULAR-RENTISTA') {
                $this->carnetIndefinido = true;
                $this->indefinido = true;
            } else {
                $this->carnetIndefinido = false;
                $this->indefinido = false;
            }
            $foto_afiliado_verificar = $this->verificarFotoTitular($data);
            if ($foto_afiliado_verificar != null) {
                $this->mostrarDatosTitular($data);
                $carnet = HCarnet::where('id_persona', $this->idPersona)->orderby('id', 'desc')->first();
                if ($carnet) {
                    $this->fecha_emision = $carnet->fecha_emision;
                    $this->fecha_emision_formatted = Carbon::parse($this->fecha_emision_r)->Format('d /m/ Y ');

                    if ($carnet->fecha_vencimiento) {
                        $this->fecha_vencimiento = $carnet->fecha_vencimiento;
                        $this->fecha_vencimiento_formatted = Carbon::parse($this->fecha_vencimiento)->Format('d /m/ Y ');
                    } else {
                        $this->fecha_vencimiento_formatted = 'Sin fecha';
                    }
                    //$this->indefinido = $carnet->indefinido;
                } else {
                    $this->fecha_emision = null;
                    $this->fecha_vencimiento = null;
                    $this->fecha_emision_formatted = null;
                    $this->fecha_vencimiento_formatted = null;
                    //$this->indefinido = '';
                }
                $this->idTit = $data;
                // $this->skipRender();
                $this->dispatch('modalCarnetizacion');
                // $this->js('window.open("/afiliacion/titular/carnet_pdf/'.$data . '", "_blank")');
            } else {
                $this->dispatch('sinFotoAfiliado');
            }
        } catch (\Throwable $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error('Afiliacion:error en carnetizacion', [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR' => $e->getMessage() ?? '',
                'ARCHIVO' => $trace['file'] ?? $e->getFile(),
                'LINEA' => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }

    public function crear_h_carnet()
    {
        if ($this->indefinido) {
            if (is_null($this->fecha_emision_r)) {
                $this->dispatch('indefinidoSinEmision');
                $this->resetFormCarnet();

                return;
            }
            if (! is_null($this->fecha_vencimiento_r)) {
                $this->dispatch('indefinidoConEmision');
                $this->resetFormCarnet();

                return;
            }
        } else { // Si no es indefinido (es normal)
            if (is_null($this->fecha_emision_r) || is_null($this->fecha_vencimiento_r) || is_null($this->nro_recibo)) {
                $this->dispatch('normalSinFechas');
                $this->resetFormCarnet();
                return;
            }


            if ($this->fecha_vencimiento_r <= $this->fecha_emision_r) {
                $this->dispatch('emisionMayorVencimiento');
                $this->resetFormCarnet();
                return;
            }
        }
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
            if (! $r_carnet) {
                DB::rollBack();
                dd('ERROR: No se pudo crear el carnet. Intenta nuevamente.');
                $this->resetFormCarnet();

                return;
            }
            DB::commit();
            $this->js('window.open("/afiliacion/titular/carnet_pdf/' . $this->idTit . '", "_blank")');
            $this->dispatch('RegistroCarnetCorrecto');
            $this->resetFormCarnet();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->resetFormCarnet();

            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error('Afiliacion:error al crear el carnet', [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR' => $e->getMessage() ?? '',
                'ARCHIVO' => $trace['file'] ?? $e->getFile(),
                'LINEA' => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }

    public function resetFormCarnet()
    {
        $this->fecha_emision_r = null;
        $this->fecha_vencimiento_r = null;
        $this->motivo_carnet = null;
        $this->indefinido = false; // O el valor predeterminado que necesitas
    }


    #[On('obtenerReplicas')]
    public function obtenerReplicas($data)
    {
        $this->replicasgehma2 = $data;
    }

    // #[On('obtenerReplicas')]
    public function verReplicas()
    {
        dd($this->replicasgehma2);
    }

    // para que regularicen los campos en editar cuando esta en 0
    #[On('verificarDatos')]
    public function verificarDatos($data)
    {
        $titularParaActualizar = Titular::where('id', $data)
            ->where('telefono', 0)
            ->where('telefono_referencia', 0)->first();
        if ($titularParaActualizar) {
            $this->debeActualizarReferenciales = true;
            $idResidencia = $titularParaActualizar->id_residencia;
            $residenciaActualizar = Residencia::where('id', $idResidencia)
                ->where('nro_vivienda', 0)
                ->first();
            if ($residenciaActualizar) {
                $this->debeActualizarResidencia = true;
            }
        }
        $titular = Titular::where('id', $data)->first();
        if ($titular) {
            $idResidencia = $titular->id_residencia;
            $residenciaActualizar = Residencia::where('id', $idResidencia)
                ->where('nro_vivienda', 0)
                ->first();
            if ($residenciaActualizar) {
                $this->debeActualizarResidencia = true;
            }
        }
    }

    #[On('archivoGrupo')]
    public function archivoGrupo($data)
    {
        $this->resetearDatosVer();
        $this->mostrarDatosTitular($data);
        // $this->mostrarArchivoPdf($data);
        //dd($this->pdf_vista);
        $this->idTit = $data;
        $this->titular_data = Titular::find($data);
        $this->idPersona = $this->titular_data->id_persona;
        $this->dispatch('modalArchivoGrupo');
    }

    #[On('guardarArchivoGrupo')]
    public function guardarArchivoGrupo()
    {
        $titular = $this->titular_data;
        $hoy = Carbon::now();
        $id_user_archivo = auth()->user()->id;
        $text_archivo_grupo = $this->text_archivo_grupo;
        $titular->update([
            'archivo_grupo' => $text_archivo_grupo,
            'id_user_archivo' => $id_user_archivo,
            'fecha_archivo' => $hoy,
        ]);
        if (! $titular) {
            $this->dispatch('errorGuardarArchivo');
        }
        $this->resetearDatosVer();
        $this->resetearDatosArchivo();
        $this->dispatch('pg:eventRefresh-TitularesInstitucionalesVoluntarios');
        $this->dispatch('registroArchivoCorrecto');
        // dd($this->titular_data);
    }

    public function resetearActualizaciones()
    {
        $this->reset('debeActualizarReferenciales', 'debeActualizarResidencia');
    }

    public function resetearDatosVer()
    {
        $this->reset('nombreInsPatronal', 'permanente', 'tipoAporte', 'codigoTitular');
    }

    public function resetearDatosArchivo()
    {
        $this->reset('titular_data', 'text_archivo_grupo');
    }

    public function resetCamposIsntituciones()
    {
        $this->reset('institucionNuevaId', 'nuevaFechaIngreso', 'nuevoCargoActual', 'nuevoSalario', 'nuevoItem', 'nueva_carga_horaria', 'nuevaTitularidad', 'tipoNuevaInstitucion');
    }

    public function abrirModalReportes()
    {
        $this->dispatch('modalTitularesReportes');
    }
}
