<?php

namespace App\Livewire\Vigencia\BajasInstitucionales;

use App\Jobs\ProcesarBajaBeneficiarioAsussJob;
use App\Jobs\ProcesarBajaEstudianteAsussJob;
use App\Jobs\ProcesarBajaTitularAsussJob;
use App\Models\Administracion\LinkServicio;
use App\Models\Administracion\Persona;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\BeneficiarioInstitucion;
use App\Models\Afiliacion\Estudiante;
use App\Models\Afiliacion\HCarnet;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\TitularInstitucion;
use App\Models\User;
use App\Models\Vigencia\AutorizacionInterior;
use App\Models\Vigencia\BajaInstitucional;
use App\Models\Vigencia\Convenio;
use App\Models\Vigencia\HistorialAsegurado;
use App\Models\Vigencia\SolicitudPMedica;
use App\Services\GrupoFamiliarPersona;
use App\Services\ListadoAsegurados;
use App\Services\RespuestaApisService;
use App\Services\VerificarDarBaja;
use Livewire\Component;
use Livewire\Attributes\On;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class BajasInstitucionalesMain extends Component
{
    public $idBajaDefinitiva;

    public $foto;
    public $estado_vigencia;
    public $edad;
    public $matricula;
    public $codigoInstitucional;
    public $nombreInsPatronal;
    public $tipoAsegurado;
    public $nombre;
    public $carnet;
    public $sexo;
    public $fecha_nacimiento;
    public $motivo_baja;
    public $fecha_baja_inst;
    public $fecha_baja_ssu;
    public $nro_documento_referencia;
    public $descripcion_motivo;
    public $baja_definitiva;
    public $fechaInicio, $fechaFin;
    public $fechaRegistroBaja;
    public $id_motivo_baja;
    public $id_persona;

    public $fechaReafiliacion;
    public $vencimientoCarnet;
    public $id_baja_institucional_asuss;

    protected $listeners = ['guardarBajaDefinitiva'];

    public function render()
    {
        return view('livewire.Vigencia.bajas-institucionales.bajas-institucionales-main');
    }

    public function mount()
    {
        /*$bajas = new VerificarDarBaja();
        $bajas->verificarDarBaja();
        $bajas->verificarConvenios();
        $bajas->bajaMayores19();
        $bajas->bajaMayores25();*/
    }

    public function redirectToRegistro()
    {
        return redirect()->route('vigencia.bajasinstitucionales.create');
    }

    #[On('verBajaInstitucional')]
    public function verBajaInstitucional($data)
    {
        $this->idBajaDefinitiva = $data;
        $this->mostrarBajaInstitucional($data);
        $this->dispatch('verModalBajaInstitucional');
    }

    public function mostrarBajaInstitucional($idBaja)
    {

        $baja = BajaInstitucional::where('id', $idBaja)
            ->with(['motivoBajaBajaInstitucional', 'tipoAseguradoBajaInstitucional', 'institucionBajaInstitucional', 'personaBajaInstitucional'])
            ->first();



        /*$historial = HistorialAsegurado::where('id_persona', $baja->personaBajaInstitucional->id)
            ->with('tipoAseguradoHistorialAsegurado')
            ->orderBy('id', 'desc')
            ->first();*/
        //dd($historial, $baja->personaBajaInstitucional->id);

        if ($baja) {

            $fechaInicio = Carbon::parse($baja->fecha_baja_inst);
            $fechaFin = Carbon::parse($baja->fecha_baja_ssu);
            $hoy = Carbon::now();

            if ($hoy->between($fechaInicio, $fechaFin)) {
                $this->estado_vigencia = 'CESANTE';
            } elseif ($hoy->greaterThanOrEqualTo($fechaFin)) {
                $this->estado_vigencia = 'NO VIGENTE';
            } else {
                $this->estado_vigencia = 'VIGENTE';
            }

            if ($baja->tipoAseguradoBajaInstitucional->tipo_asegurado == 'TITULAR') {
                if ($baja->id_corresponde != null) {
                    $datosComoTitular = Titular::where('id', $baja->id_corresponde)->first();
                    if ($datosComoTitular) {
                        $this->foto = $this->obtenerImagenBase64($datosComoTitular->foto_nombre);
                        //$this->estado_vigencia = $datosComoTitular->estado_vigencia;
                        $this->tipoAsegurado = $baja->tipoAseguradoBajaInstitucional->tipo_asegurado;

                        $datosInstitucionTitular = TitularInstitucion::where('id_titular', $datosComoTitular->id)
                            ->where('id_institucion', $baja->id_institucion_baja)
                            ->with(['titularInstitucionConInstitucion', 'titularInstitucionConInstitucion.codigosInstitucion'])
                            ->orderBy('id', 'asc')
                            ->first();
                        //dd($datosInstitucionTitular);                       
                        if ($datosInstitucionTitular) {
                            $this->codigoInstitucional = $datosInstitucionTitular->titularInstitucionConInstitucion->codigosInstitucion != null ?
                                $datosInstitucionTitular->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                                $datosInstitucionTitular->titularInstitucionConInstitucion->codigosInstitucion->tipo_titular : '';
                            $this->nombreInsPatronal = $datosInstitucionTitular->titularInstitucionConInstitucion->nombre;
                        } else {
                            $this->codigoInstitucional = '';
                            $this->nombreInsPatronal = 'APORTANTE VOLUNTARIO';
                        }

                        $this->fechaReafiliacion = $datosComoTitular->fecha_reafiliacion != null ?
                            $datosComoTitular->fecha_reafiliacion : $datosComoTitular->fecha_afiliacion;

                        $datosCarnet = HCarnet::where('id_persona', $datosComoTitular->id_persona)->orderBy('id', 'desc')->first();

                        if ($datosCarnet) {
                            if ($datosCarnet->indefinido) {
                                $this->vencimientoCarnet = 'INDEFINIDO';
                            } else {
                                $this->vencimientoCarnet = $datosCarnet->fecha_vencimiento;
                            }
                        } else {
                            $this->vencimientoCarnet = null;
                        }
                    }
                }
            } elseif (Str::contains($baja->tipoAseguradoBajaInstitucional->tipo_asegurado, 'BENEFICIARIO')) {
                if ($baja->id_corresponde != null) {
                    $datosComoBeneficiario = Beneficiario::where('id', $baja->id_corresponde)->first();
                    if ($datosComoBeneficiario) {
                        $this->foto = $this->obtenerImagenBase64($datosComoBeneficiario->foto_nombre);
                        //$this->estado_vigencia = $datosComoBeneficiario->vigencia;
                        $this->tipoAsegurado = $baja->tipoAseguradoBajaInstitucional->tipo_asegurado;

                        /*if ($datosComoBeneficiario->con_remuneracion) {
                            $datosInstitucionBeneficiario = BeneficiarioInstitucion::where('id_beneficiario', $datosComoBeneficiario->id)
                                ->with(['institucionBeneficiarioInstitucion', 'institucionBeneficiarioInstitucion.codigosInstitucion'])
                                ->orderBy('id', 'asc')
                                ->first();
                            //dd($datosInstitucionBeneficiario);
                            if ($datosInstitucionBeneficiario) {
                                $this->codigoInstitucional = $datosInstitucionBeneficiario->institucionBeneficiarioInstitucion->codigosInstitucion != null ?
                                    $datosInstitucionBeneficiario->institucionBeneficiarioInstitucion->codigosInstitucion->codigo .
                                    $datosInstitucionBeneficiario->institucionBeneficiarioInstitucion->codigosInstitucion->tipo_beneficiario : '';
                                $this->nombreInsPatronal = $datosInstitucionBeneficiario->institucionBeneficiarioInstitucion->nombre;
                            }
                        } else {*/
                        $datosComoTitular = Titular::where('id', $datosComoBeneficiario->id_titular)->first();
                        if ($datosComoTitular) {

                            $datosInstitucionTitular = TitularInstitucion::where('id_titular', $datosComoTitular->id)
                                ->with(['titularInstitucionConInstitucion', 'titularInstitucionConInstitucion.codigosInstitucion'])
                                ->orderBy('id', 'asc')
                                ->first();
                            if ($datosInstitucionTitular) {
                                $this->codigoInstitucional = $datosInstitucionTitular->titularInstitucionConInstitucion->codigosInstitucion != null ?
                                    $datosInstitucionTitular->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                                    $datosInstitucionTitular->titularInstitucionConInstitucion->codigosInstitucion->tipo_beneficiario : '';
                                $this->nombreInsPatronal = $datosInstitucionTitular->titularInstitucionConInstitucion->nombre;
                            } else {
                                $this->codigoInstitucional = '';
                                $this->nombreInsPatronal = 'APORTANTE VOLUNTARIO';
                            }
                        }
                        //}
                    }
                }
            } elseif (Str::contains($baja->tipoAseguradoBajaInstitucional->tipo_asegurado, 'ESTUDIANTE')) {
                if ($baja->id_corresponde != null) {
                    $datosComoEstudiante = Estudiante::where('id', $baja->id_corresponde)->first();
                    if ($datosComoEstudiante) {
                        $this->foto = $this->obtenerImagenBase64Estudiante($datosComoEstudiante->foto_nombre);
                        //$this->estado_vigencia = $datosComoEstudiante->estado_vigencia;
                        $this->tipoAsegurado = $baja->tipoAseguradoBajaInstitucional->tipo_asegurado;
                        $this->codigoInstitucional = '';
                        $this->nombreInsPatronal = 'UMSA ESTUDIANTES';
                    }
                }
            }
        }

        $this->edad  = Carbon::parse($baja->personaBajaInstitucional->fecha_nacimiento)->age;
        $this->matricula = $baja->personaBajaInstitucional->matricula_seguro;
        if ($baja->personaBajaInstitucional->p_apellido == null) {
            $this->nombre = $baja->personaBajaInstitucional->s_apellido . ' ' . $baja->personaBajaInstitucional->nombres;
        } elseif ($baja->personaBajaInstitucional->s_apellido == null) {
            $this->nombre = $baja->personaBajaInstitucional->p_apellido . ' ' . $baja->personaBajaInstitucional->nombres;
        } else {
            $this->nombre = $baja->personaBajaInstitucional->p_apellido . ' ' . $baja->personaBajaInstitucional->s_apellido . ' ' . $baja->personaBajaInstitucional->nombres;
        }
        if ($baja->personaBajaInstitucional->complemento == null) {
            $this->carnet = $baja->personaBajaInstitucional->ci;
        } else {
            $this->carnet = $baja->personaBajaInstitucional->ci . ' - ' . $baja->personaBajaInstitucional->complemento;
        }
        $this->sexo = $baja->personaBajaInstitucional->sexo;
        $this->fecha_nacimiento = $baja->personaBajaInstitucional->fecha_nacimiento;
        $this->id_motivo_baja = $baja->id_motivo_baja;
        $this->motivo_baja = $baja->motivoBajaBajaInstitucional ? $baja->motivoBajaBajaInstitucional->motivo_baja : $baja->descripcion_motivo;

        $this->fecha_baja_inst = $baja->fecha_baja_inst;
        $this->fecha_baja_ssu = $baja->fecha_baja_ssu;
        $this->nro_documento_referencia = $baja->nro_documento_referencia;
        $this->descripcion_motivo = $baja->descripcion_motivo;
        $this->baja_definitiva = $baja->baja_definitiva;
        $this->fechaRegistroBaja = Carbon::parse($baja->created_at)->format('Y-m-d');
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

    public function darBajaUsuario($idUsuario)
    {
        $usuario = User::find($idUsuario);
        if ($usuario) {
            $usuario->estado = false;
            $usuario->syncRoles([]);
            $usuario->syncPermissions([]);
            $usuario->id_user_updated = auth()->user()->id;
            $usuario->save();
        }
    }

    private function obtenerImagenBase64Estudiante($filename)
    {
        //dd($filename);
        try {
            if ($filename) {
                $path = 'Afiliacion/SSUE/FOTOS/' . $filename;
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


    public function bajasGehma2($data)
    {
        $urlGehma2 = LinkServicio::where('nombre_servicio', 'REGISTRO BAJAS INST GEHMA2')->first();
        $url_gehma2 = $urlGehma2->link_servicio;
        $respuesta = Http::post($url_gehma2, $data);
        // TODO: guardar en tabla replicas para ver respuestas de la API
        $id_user_created = auth()->user()->id;
        $replica = new RespuestaApisService();
        $replica->guardarRespuesta(
            $data,
            $respuesta,
            "gehma2",
            "vigencia asegudaros convenio",
            "vigencia de derechos bajas institucionales",
            $id_user_created
        );
        //dd($respuesta->json());
    }

    #[On('darBajaDefinitiva')]
    public function darBajaDefinitiva($data)
    {
        $this->idBajaDefinitiva = $data;
        $this->id_motivo_baja = BajaInstitucional::find($this->idBajaDefinitiva) ? BajaInstitucional::find($this->idBajaDefinitiva)->id_motivo_baja : 3;
        $this->id_persona = BajaInstitucional::find($this->idBajaDefinitiva)->id_persona;
        $this->dispatch('alertaBajaDefinitiva');
    }

    public function guardarBajaDefinitiva($data)
    {
        DB::beginTransaction();
        try {
            $bajaInstitucional = BajaInstitucional::find($this->idBajaDefinitiva);

            if ($bajaInstitucional) {

                $bajaInstitucional->observacion = $data;
                $bajaInstitucional->fecha_baja_ssu = Carbon::now()->format('Y-m-d');
                $bajaInstitucional->registro_tratado = true;
                $bajaInstitucional->id_user_updated = auth()->user()->id;
                $bajaInstitucional->save();

                // logica para dar de baja definitivo

                $persona = Persona::where('id', $bajaInstitucional->id_persona)->with(['tipoAseguradoPersona'])->first();

                if ($persona) {

                    if ($persona->tipoAseguradoPersona->tipo_asegurado == 'TITULAR') {
                        $datosTitular = Titular::where('id_persona', $persona->id)
                            ->where('estado_vigencia', 'CESANTE')
                            ->orderBy('id', 'desc')
                            ->first();

                        if ($datosTitular) {

                            //PARA LA TABLA HISTORIAL

                            $actualizacionHistorial = HistorialAsegurado::where('id_tipo_asegurado', $persona->id_tipo_asegurado)
                                ->where('id_corresponde', $datosTitular->id)
                                ->where('id_persona', $persona->id)
                                ->first();

                            if ($actualizacionHistorial) {
                                $actualizacionHistorial->estado_vigencia = 'NO VIGENTE';
                                $actualizacionHistorial->fecha_baja = Carbon::now()->format('Y-m-d');
                                $actualizacionHistorial->id_user_updated = auth()->user()->id;
                                $actualizacionHistorial->save();
                            }

                            //PARA EL GRUPO FAMILIAR 

                            $servicioGrupoFamiliar = new GrupoFamiliarPersona();

                            $listadoFamiliares = $servicioGrupoFamiliar->encontrarPersona($persona->id);
                            //dd($listadoFamiliares);

                            if ($listadoFamiliares != []) {
                                if (count($listadoFamiliares) > 1) {

                                    foreach ($listadoFamiliares as $familiar) {
                                        if ($familiar['id'] != $persona->id) {

                                            $actualizarBeneficiario = Beneficiario::where('id_persona', $familiar['id'])
                                                ->where('id_titular', $datosTitular->id)
                                                ->orderBy('id', 'desc')
                                                ->first();

                                            if ($actualizarBeneficiario) {

                                                //PARA LA TABLA HISTORIAL 

                                                $actualizacionHistorialBeneficiario = HistorialAsegurado::where('id_tipo_asegurado', $familiar['id_tipo_asegurado'])
                                                    ->where('id_corresponde', $actualizarBeneficiario->id)
                                                    ->where('id_persona', $familiar['id'])
                                                    ->first();

                                                if ($actualizacionHistorialBeneficiario) {
                                                    $actualizacionHistorialBeneficiario->estado_vigencia = 'NO VIGENTE';
                                                    $actualizacionHistorialBeneficiario->fecha_baja = Carbon::now()->format('Y-m-d');
                                                    $actualizacionHistorialBeneficiario->id_user_updated = auth()->user()->id;
                                                    $actualizacionHistorialBeneficiario->save();
                                                }

                                                //MODIFICADO RECIEN EN LA TABLA BENEFICIARIO

                                                $actualizarBeneficiario->vigencia = 'NO VIGENTE';
                                                $actualizarBeneficiario->fecha_baja = Carbon::now()->format('Y-m-d');
                                                $actualizarBeneficiario->id_user_updated = auth()->user()->id;
                                                $actualizarBeneficiario->save();

                                                //PARA ACTUALIZAR A LA PERSONA

                                                $actualizarPersonaBeneficiario = Persona::where('id', $familiar['id'])->first();

                                                if ($actualizarPersonaBeneficiario) {
                                                    $actualizarPersonaBeneficiario->afiliado = false;
                                                    $actualizarPersonaBeneficiario->id_tipo_asegurado = 0;
                                                    $actualizarPersonaBeneficiario->id_user_updated = auth()->user()->id;
                                                    $actualizarPersonaBeneficiario->save();

                                                    //SERVICIO DE REPLICA

                                                    $ciServicio = $actualizarPersonaBeneficiario->ci;

                                                    if ($actualizarPersonaBeneficiario->complemento != null) {
                                                        $ciServicio .= '-' . $actualizarPersonaBeneficiario->complemento;
                                                    }

                                                    $motivoServicio = 3;

                                                    if ($this->id_motivo_baja == 6) {
                                                        $motivoServicio = 4;
                                                    }

                                                    $bajas = [];
                                                    $bajas[] = [
                                                        'ci' => $ciServicio,
                                                        'id_estado_afiliado' => $motivoServicio,
                                                    ];

                                                    $regBajasGehma2 = new VerificarDarBaja();
                                                    $regBajasGehma2->bajasGehma2(['bajas' => $bajas]);
                                                }

                                                //ACTUALIZAR USUARIO

                                                $actualizarUsuario = User::where('id_persona', $this->id_persona)->first();

                                                if ($actualizarUsuario) {
                                                    $this->darBajaUsuario($actualizarUsuario->id);
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                            //PARA DAR DE BAJA A LAS INSTITUCIONES 

                            $institucionesTitular = TitularInstitucion::where('id_titular', $datosTitular->id)->get();

                            if (count($institucionesTitular) > 0) {
                                foreach ($institucionesTitular as $institucionTitular) {
                                    $institucionTitular->estado = false;
                                    $institucionTitular->id_user_updated = auth()->user()->id;
                                    $institucionTitular->save();
                                }
                            }

                            //FIN PARA DAR DE BAJA A LAS INSTITUCIONES

                            $datosTitular->estado_vigencia = 'NO VIGENTE';
                            $datosTitular->fecha_baja = Carbon::now()->format('Y-m-d');
                            $datosTitular->id_user_updated = auth()->user()->id;
                            $datosTitular->save();

                            //PARA ACTUALIZAR A LA PERSONA
                            $actualizarPersona = Persona::where('id', $persona->id)->first();

                            if ($actualizarPersona) {
                                $actualizarPersona->afiliado = false;
                                $actualizarPersona->id_tipo_asegurado = 0;
                                $actualizarPersona->id_user_updated = auth()->user()->id;
                                $actualizarPersona->save();

                                //SERVICIO DE REPLICA

                                $ciServicio = $actualizarPersona->ci;

                                if ($actualizarPersona->complemento != null) {
                                    $ciServicio .= '-' . $actualizarPersona->complemento;
                                }

                                $motivoServicio = 3;

                                if ($this->id_motivo_baja == 6) {
                                    $motivoServicio = 4;
                                }

                                $bajas = [];
                                $bajas[] = [
                                    'ci' => $ciServicio,
                                    'id_estado_afiliado' => $motivoServicio,
                                ];

                                $regBajasGehma2 = new VerificarDarBaja();
                                $regBajasGehma2->bajasGehma2(['bajas' => $bajas]);
                            }

                            //PARA EL USUARIO

                            $actualizarUsuario = User::where('id_persona', $this->id_persona)->first();

                            if ($actualizarUsuario) {
                                $this->darBajaUsuario($actualizarUsuario->id);
                            }


                            DB::commit();
                            $this->dispatch('guardado');
                            $this->dispatch('pg:eventRefresh-BajaInstTable');

                            //------------------------------------------------------- NUEVA BAJA DE DATOS ASUSS (TITULARES) -------------------------------------------------------  

                            /*$servicioLoginAsuss = new LoginAsussService();

                            $servicioAsussTitular = new AfiliacionTitularAsussService($servicioLoginAsuss);*/

                            $arrayDatosOriginales = [
                                'tipo_documento' => 'CI',
                                'numero_documento' => implode('-', array_filter([$actualizarPersona->ci, $actualizarPersona->complemento])),
                                'fecha_nacimiento' => $actualizarPersona->fecha_nacimiento,
                                'codigo_entidad' => '',
                                'fecha_fin' => $bajaInstitucional->fecha_baja_inst,
                                'fecha_fin_cesantia' => $bajaInstitucional->fecha_baja_ssu,
                                'tipo_motivo' => $bajaInstitucional->id_motivo_baja,
                                'descripcion_motivo' => $bajaInstitucional->id_motivo_baja,
                                'id_persona' => $actualizarPersona->id,
                                'id_tipo_asegurado' => $bajaInstitucional->id_tipo_asegurado,
                                'id_corresponde' => $bajaInstitucional->id_corresponde,
                                'id_titular' => null,
                                'id_institucion' => $bajaInstitucional->id_institucion_baja,
                                'id_user_created' => $bajaInstitucional->id_user_created,
                            ];

                            //dd($arrayDatosOriginales);

                            //$servicioAsussTitular->afiliarTitularAsuss($arrayDatosOriginales);

                            //ProcesarBajaTitularAsussJob::dispatch($arrayDatosOriginales); -------------------------------------------------- DESCOMENTAR PARA QUE EL ASUSS FUNCIONE

                            //------------------------------------------------------- FIN NUEVA BAJA DE DATOS ASUSS (TITULARES) ------------------------------------------------------- 

                        } else {
                            DB::rollBack();
                            $this->dispatch('noValidado');
                        }
                    } elseif (Str::contains($persona->tipoAseguradoPersona->tipo_asegurado, 'BENEFICIARIO')) {
                        if ($persona->tipoAseguradoPersona->tipo_asegurado != 'BENEFICIARIO DEL INTERIOR') {

                            $datosBeneficiario = Beneficiario::where('id_persona', $persona->id)
                                ->where('vigencia', 'CESANTE')
                                ->orderBy('id', 'desc')
                                ->first();

                            if ($datosBeneficiario) {

                                $datosBeneficiario->vigencia = 'NO VIGENTE';
                                $datosBeneficiario->fecha_baja = Carbon::now()->format('Y-m-d');
                                $datosBeneficiario->id_user_updated = auth()->user()->id;
                                $datosBeneficiario->save();

                                //PARA LA TABLA HISTORIAL

                                $actualizacionHistorial = HistorialAsegurado::where('id_tipo_asegurado', $persona->id_tipo_asegurado)
                                    ->where('id_corresponde', $datosBeneficiario->id)
                                    ->where('id_persona', $persona->id)
                                    ->first();

                                if ($actualizacionHistorial) {
                                    $actualizacionHistorial->estado_vigencia = 'NO VIGENTE';
                                    $actualizacionHistorial->fecha_baja = Carbon::now()->format('Y-m-d');
                                    $actualizacionHistorial->id_user_updated = auth()->user()->id;
                                    $actualizacionHistorial->save();
                                }

                                //PARA ACTUALIZAR A LA PERSONA
                                $actualizarPersona = Persona::where('id', $persona->id)->first();

                                if ($actualizarPersona) {
                                    $actualizarPersona->afiliado = false;
                                    $actualizarPersona->id_tipo_asegurado = 0;
                                    $actualizarPersona->id_user_updated = auth()->user()->id;
                                    $actualizarPersona->save();

                                    //SERVICIO DE REPLICA

                                    $ciServicio = $actualizarPersona->ci;

                                    if ($actualizarPersona->complemento != null) {
                                        $ciServicio .= '-' . $actualizarPersona->complemento;
                                    }

                                    $motivoServicio = 3;

                                    if ($this->id_motivo_baja == 6) {
                                        $motivoServicio = 4;
                                    }

                                    $bajas = [];
                                    $bajas[] = [
                                        'ci' => $ciServicio,
                                        'id_estado_afiliado' => $motivoServicio,
                                    ];

                                    $regBajasGehma2 = new VerificarDarBaja();
                                    $regBajasGehma2->bajasGehma2(['bajas' => $bajas]);
                                }




                                //PARA EL USUARIO

                                $actualizarUsuario = User::where('id_persona', $this->id_persona)->first();

                                if ($actualizarUsuario) {
                                    $this->darBajaUsuario($actualizarUsuario->id);
                                }

                                DB::commit();
                                $this->dispatch('guardado');
                                $this->dispatch('pg:eventRefresh-BajaInstTable');

                                //------------------------------------------------------- NUEVA BAJA DE DATOS ASUSS (BENEFICIARIO) -------------------------------------------------------  

                                /*$servicioLoginAsuss = new LoginAsussService();

                                $servicioAsussTitular = new AfiliacionTitularAsussService($servicioLoginAsuss);*/

                                $arrayDatosOriginales = [
                                    'tipo_documento' => 'CI',
                                    'numero_documento' => implode('-', array_filter([$actualizarPersona->ci, $actualizarPersona->complemento])),
                                    'fecha_nacimiento' => $actualizarPersona->fecha_nacimiento,
                                    'codigo_entidad' => '',
                                    'fecha_fin' => $bajaInstitucional->fecha_baja_inst,
                                    'fecha_fin_cesantia' => $bajaInstitucional->fecha_baja_ssu,
                                    'tipo_motivo' => $bajaInstitucional->id_motivo_baja,
                                    'descripcion_motivo' => $bajaInstitucional->id_motivo_baja,
                                    'id_persona' => $actualizarPersona->id,
                                    'id_tipo_asegurado' => $bajaInstitucional->id_tipo_asegurado,
                                    'id_corresponde' => $bajaInstitucional->id_corresponde,
                                    'id_titular' => $datosBeneficiario->id_titular,
                                    'id_institucion' => $bajaInstitucional->id_institucion_baja,
                                    'id_user_created' => $bajaInstitucional->id_user_created,
                                ];

                                //dd($arrayDatosOriginales);

                                //$servicioAsussTitular->afiliarTitularAsuss($arrayDatosOriginales);

                                //ProcesarBajaBeneficiarioAsussJob::dispatch($arrayDatosOriginales);  -------------------------------------------------- DESCOMENTAR PARA QUE EL ASUSS FUNCIONE

                                //------------------------------------------------------- FIN NUEVA BAJA DE DATOS ASUSS (BENEFICIARIO) -------------------------------------------------------
                            } else {
                                DB::rollBack();
                                $this->dispatch('noValidado');
                            }
                        }
                    } elseif ($persona->tipoAseguradoPersona->tipo_asegurado == 'ESTUDIANTE') {
                        $datosEstudiante = Estudiante::where('id_persona', $persona->id)
                            ->orderBy('id', 'desc')
                            ->first();

                        if ($datosEstudiante) {
                            $datosEstudiante->estado_vigencia = false;
                            $datosEstudiante->fecha_baja = Carbon::now()->format('Y-m-d');
                            $datosEstudiante->id_user_updated = auth()->user()->id;
                            $datosEstudiante->save();

                            //PARA LA TABLA HISTORIAL

                            $actualizacionHistorial = HistorialAsegurado::where('id_tipo_asegurado', $persona->id_tipo_asegurado)
                                ->where('id_corresponde', $datosEstudiante->id)
                                ->where('id_persona', $persona->id)
                                ->first();

                            if ($actualizacionHistorial) {
                                $actualizacionHistorial->estado_vigencia = 'NO VIGENTE';
                                $actualizacionHistorial->fecha_baja = Carbon::now()->format('Y-m-d');
                                $actualizacionHistorial->id_user_updated = auth()->user()->id;
                                $actualizacionHistorial->save();
                            }

                            //PARA ACTUALIZAR A LA PERSONA
                            $actualizarPersona = Persona::where('id', $persona->id)->first();

                            if ($actualizarPersona) {
                                $actualizarPersona->afiliado = false;
                                $actualizarPersona->id_tipo_asegurado = 0;
                                $actualizarPersona->id_user_updated = auth()->user()->id;
                                $actualizarPersona->save();

                                //SERVICIO DE REPLICA

                                $ciServicio = $actualizarPersona->ci;

                                if ($actualizarPersona->complemento != null) {
                                    $ciServicio .= '-' . $actualizarPersona->complemento;
                                }

                                $motivoServicio = 3;

                                if ($this->id_motivo_baja == 6) {
                                    $motivoServicio = 4;
                                }

                                $bajas = [];
                                $bajas[] = [
                                    'ci' => $ciServicio,
                                    'id_estado_afiliado' => $motivoServicio,
                                ];

                                $regBajasGehma2 = new VerificarDarBaja();
                                $regBajasGehma2->bajasGehma2(['bajas' => $bajas]);
                            }

                            //PARA EL USUARIO

                            $actualizarUsuario = User::where('id_persona', $this->id_persona)->first();

                            if ($actualizarUsuario) {
                                $this->darBajaUsuario($actualizarUsuario->id);
                            }


                            DB::commit();
                            $this->dispatch('guardado');
                            $this->dispatch('pg:eventRefresh-BajaInstTable');

                            //------------------------------------------------------- NUEVA BAJA DE DATOS ASUSS (ESTUDIANTE) -------------------------------------------------------  

                            /*$servicioLoginAsuss = new LoginAsussService();

                            $servicioAsussTitular = new AfiliacionTitularAsussService($servicioLoginAsuss);*/

                            $arrayDatosOriginales = [
                                'tipo_documento' => 'CI',
                                'numero_documento' => implode('-', array_filter([$actualizarPersona->ci, $actualizarPersona->complemento])),
                                'fecha_nacimiento' => $actualizarPersona->fecha_nacimiento,
                                'codigo_entidad' => '',
                                'fecha_fin' => $bajaInstitucional->fecha_baja_inst,
                                'fecha_fin_cesantia' => $bajaInstitucional->fecha_baja_ssu,
                                'tipo_motivo' => $bajaInstitucional->id_motivo_baja,
                                'descripcion_motivo' => $bajaInstitucional->id_motivo_baja,
                                'id_persona' => $actualizarPersona->id,
                                'id_tipo_asegurado' => $bajaInstitucional->id_tipo_asegurado,
                                'id_corresponde' => $bajaInstitucional->id_corresponde,
                                'id_titular' => null,
                                'id_institucion' => null,
                                'id_user_created' => $bajaInstitucional->id_user_created,
                            ];

                            //dd($arrayDatosOriginales);

                            //$servicioAsussTitular->afiliarTitularAsuss($arrayDatosOriginales);

                            //ProcesarBajaEstudianteAsussJob::dispatch($arrayDatosOriginales); -------------------------------------------------- DESCOMENTAR PARA QUE EL ASUSS FUNCIONE

                            //------------------------------------------------------- FIN NUEVA BAJA DE DATOS ASUSS (ESTUDIANTE) -------------------------------------------------------

                        } else {
                            DB::rollBack();
                            $this->dispatch('noValidado');
                        }
                    }
                    DB::commit();
                } else {
                    DB::rollBack();
                    $this->dispatch('noValidado');
                }
            }
        } catch (\Throwable $th) {
            dd($th);
            DB::rollBack();
            $this->dispatch('noValidado');
        }
    }

    public function generarPDF()
    {
        if ($this->fechaInicio != null && $this->fechaFin != null) {
            $this->js('window.open("/vigencia/BajasInstitucionales/' . $this->fechaInicio . '/' . $this->fechaFin . '")');
        } else {
            $this->dispatch('completeDatos');
            $this->fechaInicio = null;
            $this->fechaFin = null;
        }
        $this->skipRender();
    }
    #[On('bajaAsuss')]
    public function bajaAsuss($data)
    {
        $this->id_baja_institucional_asuss = $data;
        $this->dispatch('preguntaBajaAsuss');
        // dd($data);
    }
    #[On('confirmarBajaAsuss')]
    public function confirmarBajaAsuss(string $motivo, DateTime $fecha)
    {
        $id_user_baja_asuss = auth()->user()->id;
        // dd($motivo, $fecha, $this->id_baja_institucional_asuss, $id_user_baja_asuss);
        $baja = BajaInstitucional::where('id', $this->id_baja_institucional_asuss)
            ->update([
                'observacion_asuss' => $motivo == '' ? null : $motivo,
                'fecha_baja_asuss' => $fecha,
                'id_user_baja_asuss' => $id_user_baja_asuss,
            ]); //->update()
        if ($baja) {
            $this->dispatch('aceptaBajaAsuss');
            $this->dispatch('pg:eventRefresh-BajaInstTable');
        } else {
            $this->dispatch('noValidadoBajaAsuss');
        }
        // dd($data);
    }



    ////////////////////////////////  PROBANDO :(

}
