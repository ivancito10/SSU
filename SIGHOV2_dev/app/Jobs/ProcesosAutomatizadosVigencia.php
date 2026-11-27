<?php

namespace App\Jobs;

use App\Models\Administracion\LinkServicio;
use App\Models\Administracion\Persona;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\Estudiante;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\TitularInstitucion;
use App\Models\Aportes\ContratoVoluntario;
use App\Models\User;
use App\Models\Vigencia\AutorizacionInterior;
use App\Models\Vigencia\BajaInstitucional;
use App\Models\Vigencia\Convenio;
use App\Models\Vigencia\HistorialAsegurado;
use App\Models\Vigencia\SolicitudPMedica;
use App\Services\GrupoFamiliarPersona;
use App\Services\ListadoAsegurados;
use App\Services\RespuestaApisService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ProcesosAutomatizadosVigencia implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //try {
        $this->verificarDarBaja();
        $this->verificarConvenios();
        $this->bajaMayores25();
        $this->bajaMayores19();
        /*} catch (\Throwable $th) {
            dd($th);
        }*/
    }

    public function verificarDarBaja()
    {
        $bajas = [];

        $bajasInt = BajaInstitucional::where('fecha_baja_ssu', '<', Carbon::now()->format('Y-m-d'))
            ->where('baja_definitiva', false)
            //->where('baja_automatica', false)
            ->where('registro_tratado', false)
            ->with(['motivoBajaBajaInstitucional'])
            ->get();
        // dd($bajasInt);
        foreach ($bajasInt as $key => $datoBajaInstitucional) {

            $personaServicio = Persona::where('id', $datoBajaInstitucional->id_persona)->first();

            $verificarDatos = new ListadoAsegurados();

            $datosPersona = $verificarDatos->busquedaAfiliadoPorIdParaBajaInstitucionalNuevo($personaServicio->id);

            if ($datosPersona != []) {
                if (count($datosPersona['instituciones']) > 1 && $datosPersona['tipo_asegurado'] === 'TITULAR') {
                    $institucionTitularBaja = TitularInstitucion::where('id_titular', $datoBajaInstitucional->id_corresponde)
                        ->where('id_institucion', $datoBajaInstitucional->id_institucion_baja)
                        ->first();

                    if ($institucionTitularBaja) {
                        $institucionTitularBaja->estado = false;
                        //$institucionTitularBaja->id_user_updated = auth()->user()->id;
                        $institucionTitularBaja->save();
                    }
                    $datoBajaInstitucional->observacion = $datoBajaInstitucional->observacion != null ? $datoBajaInstitucional->observacion : 'BAJA INSTITUCIONAL REGISTRADA CORRECTAMENTE.';
                    $datoBajaInstitucional->registro_tratado = true;
                    $datoBajaInstitucional->baja_automatica = true;
                    //$datoBajaInstitucional->id_user_updated = auth()->user()->id;
                    $datoBajaInstitucional->save();
                } elseif (count($datosPersona['instituciones']) <= 1 || $datosPersona['tipo_asegurado'] !== 'TITULAR') {
                    $personaCarnet = $personaServicio->ci;

                    if ($personaServicio->complemento != null) {
                        $personaCarnet .= '-' . $personaServicio->complemento;
                    }

                    $personaTipoAfiliadoServicio = 3;

                    if ($datoBajaInstitucional->motivoBajaBajaInstitucional->motivo_baja == 'FALLECIDO') {
                        $personaTipoAfiliadoServicio = 4;
                    }

                    $bajas[] = [
                        'ci' => $personaCarnet,
                        'id_estado_afiliado' => $personaTipoAfiliadoServicio,
                    ];

                    //DB::beginTransaction();
                    //try {
                    $bajaInstitucional = BajaInstitucional::find($datoBajaInstitucional->id);

                    if ($bajaInstitucional) {
                        $bajaInstitucional->observacion = $bajaInstitucional->observacion != null ? $bajaInstitucional->observacion : 'BAJA INSTITUCIONAL REGISTRADA CORRECTAMENTE';
                        $bajaInstitucional->fecha_baja_ssu = $bajaInstitucional->fecha_baja_ssu != null ? $bajaInstitucional->fecha_baja_ssu : Carbon::now()->format('Y-m-d');
                        $bajaInstitucional->baja_automatica = true;
                        $bajaInstitucional->registro_tratado = true;
                        //$bajaInstitucional->id_user_updated = auth()->user()->id;
                        $bajaInstitucional->save();

                        // logica para dar de baja definitivo

                        $persona = Persona::where('id', $bajaInstitucional->id_persona)->with(['tipoAseguradoPersona'])->first();

                        if ($persona) {

                            if ($persona->tipoAseguradoPersona->tipo_asegurado == 'TITULAR') {
                                $datosTitular = Titular::where('id_persona', $persona->id)
                                    ->where('estado_vigencia', 'CESANTE')
                                    ->where('estado', true)
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
                                        //$actualizacionHistorial->id_user_updated = auth()->user()->id;
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
                                                            //$actualizacionHistorialBeneficiario->id_user_updated = auth()->user()->id;
                                                            $actualizacionHistorialBeneficiario->save();
                                                        }

                                                        //MODIFICADO RECIEN EN LA TABLA BENEFICIARIO

                                                        $actualizarBeneficiario->vigencia = 'NO VIGENTE';
                                                        $actualizarBeneficiario->fecha_baja = Carbon::now()->format('Y-m-d');
                                                        //$actualizarBeneficiario->id_user_updated = auth()->user()->id;
                                                        $actualizarBeneficiario->save();

                                                        //PARA ACTUALIZAR A LA PERSONA

                                                        $actualizarPersonaBeneficiario = Persona::where('id', $familiar['id'])->first();

                                                        if ($actualizarPersonaBeneficiario) {
                                                            $actualizarPersonaBeneficiario->afiliado = false;
                                                            $actualizarPersonaBeneficiario->id_tipo_asegurado = 0;
                                                            //$actualizarPersonaBeneficiario->id_user_updated = auth()->user()->id;
                                                            $actualizarPersonaBeneficiario->save();
                                                        }

                                                        //PARA EL USUARIO

                                                        $actualizarUsuario = User::where('id_persona', $familiar['id'])->first();

                                                        if ($actualizarUsuario) {
                                                            $this->darBajaUsuario($actualizarUsuario->id);
                                                        }

                                                        /*if ($actualizarUsuario) {
                                                            $actualizarUsuario->estado = false;
                                                            $actualizarUsuario->id_user_updated = auth()->user()->id;
                                                            $actualizarUsuario->save();
                                                        }*/
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
                                            //$institucionTitular->id_user_updated = auth()->user()->id;
                                            $institucionTitular->save();
                                        }
                                    }

                                    //FIN PARA DAR DE BAJA A LAS INSTITUCIONES

                                    $datosTitular->estado_vigencia = 'NO VIGENTE';
                                    $datosTitular->fecha_baja = Carbon::now()->format('Y-m-d');
                                    //$datosTitular->id_user_updated = auth()->user()->id;
                                    $datosTitular->save();

                                    //PARA ACTUALIZAR A LA PERSONA
                                    $actualizarPersona = Persona::where('id', $persona->id)->first();

                                    if ($actualizarPersona) {
                                        $actualizarPersona->afiliado = false;
                                        $actualizarPersona->id_tipo_asegurado = 0;
                                        //$actualizarPersona->id_user_updated = auth()->user()->id;
                                        $actualizarPersona->save();
                                    }

                                    //PARA EL USUARIO

                                    $actualizarUsuario = User::where('id_persona', $persona->id)->first();

                                    if ($actualizarUsuario) {
                                        $this->darBajaUsuario($actualizarUsuario->id);
                                    }

                                    /*if ($actualizarUsuario) {
                                        $actualizarUsuario->estado = false;
                                        $actualizarUsuario->id_user_updated = auth()->user()->id;
                                        $actualizarUsuario->save();
                                    }*/
                                    //DB::commit();
                                } else {
                                    //DB::rollBack();
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
                                        //$datosBeneficiario->id_user_updated = auth()->user()->id;
                                        $datosBeneficiario->save();

                                        //PARA LA TABLA HISTORIAL

                                        $actualizacionHistorial = HistorialAsegurado::where('id_tipo_asegurado', $persona->id_tipo_asegurado)
                                            ->where('id_corresponde', $datosBeneficiario->id)
                                            ->where('id_persona', $persona->id)
                                            ->first();

                                        if ($actualizacionHistorial) {
                                            $actualizacionHistorial->estado_vigencia = 'NO VIGENTE';
                                            $actualizacionHistorial->fecha_baja = Carbon::now()->format('Y-m-d');
                                            //$actualizacionHistorial->id_user_updated = auth()->user()->id;
                                            $actualizacionHistorial->save();
                                        }

                                        //PARA ACTUALIZAR A LA PERSONA
                                        $actualizarPersona = Persona::where('id', $persona->id)->first();

                                        if ($actualizarPersona) {
                                            $actualizarPersona->afiliado = false;
                                            $actualizarPersona->id_tipo_asegurado = 0;
                                            //$actualizarPersona->id_user_updated = auth()->user()->id;
                                            $actualizarPersona->save();
                                        }

                                        //PARA EL USUARIO

                                        $actualizarUsuario = User::where('id_persona', $persona->id)->first();

                                        if ($actualizarUsuario) {
                                            $this->darBajaUsuario($actualizarUsuario->id);
                                        }

                                        /*if ($actualizarUsuario) {
                                            $actualizarUsuario->estado = false;
                                            $actualizarUsuario->id_user_updated = auth()->user()->id;
                                            $actualizarUsuario->save();
                                        }*/
                                        //DB::commit();
                                    } else {
                                        //DB::rollBack();
                                    }
                                }
                            } elseif ($persona->tipoAseguradoPersona->tipo_asegurado == 'ESTUDIANTE') {
                                $datosEstudiante = Estudiante::where('id_persona', $persona->id)
                                    ->orderBy('id', 'desc')
                                    ->first();

                                if ($datosEstudiante) {
                                    $datosEstudiante->estado_vigencia = false;
                                    $datosEstudiante->fecha_baja = Carbon::now()->format('Y-m-d');
                                    //$datosEstudiante->id_user_updated = auth()->user()->id;
                                    $datosEstudiante->save();

                                    //PARA LA TABLA HISTORIAL

                                    $actualizacionHistorial = HistorialAsegurado::where('id_tipo_asegurado', $persona->id_tipo_asegurado)
                                        ->where('id_corresponde', $datosEstudiante->id)
                                        ->where('id_persona', $persona->id)
                                        ->first();

                                    if ($actualizacionHistorial) {
                                        $actualizacionHistorial->estado_vigencia = 'NO VIGENTE';
                                        $actualizacionHistorial->fecha_baja = Carbon::now()->format('Y-m-d');
                                        //$actualizacionHistorial->id_user_updated = auth()->user()->id;
                                        $actualizacionHistorial->save();
                                    }

                                    //PARA ACTUALIZAR A LA PERSONA
                                    $actualizarPersona = Persona::where('id', $persona->id)->first();

                                    if ($actualizarPersona) {
                                        $actualizarPersona->afiliado = false;
                                        $actualizarPersona->id_tipo_asegurado = 0;
                                        //$actualizarPersona->id_user_updated = auth()->user()->id;
                                        $actualizarPersona->save();
                                    }

                                    //PARA EL USUARIO

                                    $actualizarUsuario = User::where('id_persona', $persona->id)->first();

                                    if ($actualizarUsuario) {
                                        $this->darBajaUsuario($actualizarUsuario->id);
                                    }

                                    /*if ($actualizarUsuario) {
                                        $actualizarUsuario->estado = false;
                                        $actualizarUsuario->id_user_updated = auth()->user()->id;
                                        $actualizarUsuario->save();
                                    }*/
                                    //DB::commit();
                                } else {
                                    //DB::rollBack();
                                }
                            }
                        } else {
                            //DB::rollBack();
                        }
                    }
                    //} catch (\Throwable $th) {
                    //    DB::rollBack();
                    //}
                }
            }
        }

        //inicio bajas violuntarios
        $bajasVoluntarios = ContratoVoluntario::select(
            'persona.ci',
            'persona.matricula_seguro',
            'aportes.contrato_voluntario.fecha_fin_contrato',
            'aportes.contrato_voluntario.id_persona',
            'titular.id as id_titular'
        )
            ->join(
                'administracion.persona as persona',
                'persona.id',
                '=',
                'aportes.contrato_voluntario.id_persona'
            )
            ->join('afiliacion.titular as titular', function ($join) {
                $join->on('titular.id_persona', '=', 'aportes.contrato_voluntario.id_persona')
                    ->where('titular.tipo_titular', false)
                    ->where('titular.estado_vigencia', 'VIGENTE');
            })
            ->join(DB::raw('(
        SELECT id_persona, MAX(fecha_fin_contrato) AS max_fecha
        FROM aportes.contrato_voluntario
        GROUP BY id_persona
    ) as ult'), function ($join) {
                $join->on('aportes.contrato_voluntario.id_persona', '=', 'ult.id_persona')
                    ->on('aportes.contrato_voluntario.fecha_fin_contrato', '=', 'ult.max_fecha');
            })
            ->where('aportes.contrato_voluntario.fecha_fin_contrato', '<', Carbon::now()->format('Y-m-d'))
            ->orderBy('aportes.contrato_voluntario.id_persona', 'asc')
            ->get();

        // dd($contratos->pluck('id_persona'));

        // foreach ($contratos as $key => $bajasVoluntarios) {
        //     // $bajasVoluntarios->ci = (string) $bajasVoluntarios->ci;
        //     // $verificarTitular = Titular::where('id_persona', $bajasVoluntarios->id_persona)->where('tipo_titular', false)->where('estado_vigencia', 'like', 'VIGENTE')->first();
        //     // if ($verificarTitular) {
        //         //  dd($bajasVoluntarios->id_persona);
        \Log::info('Total registros encontrados: ' . $bajasVoluntarios->count());

        foreach ($bajasVoluntarios as $key => $datoBajaInstitucional) {

            $personaServicio = Persona::where('id', $datoBajaInstitucional->id_persona)->first();
            // dd($personaServicio);

            $verificarDatos = new ListadoAsegurados();

            $datosPersona = $verificarDatos->busquedaAfiliadoPorIdParaBajaInstitucionalNuevo($personaServicio->id);
            // dd(count($datosPersona['instituciones']) > 1 && $datosPersona['tipo_asegurado'] === 'TITULAR');
            if ($datosPersona != []) {
                if (count($datosPersona['instituciones']) > 1 && $datosPersona['tipo_asegurado'] === 'TITULAR') {
                    $institucionTitularBaja = TitularInstitucion::where('id_titular', $datoBajaInstitucional->id_corresponde)
                        ->where('id_institucion', $datoBajaInstitucional->id_institucion_baja)
                        ->first();
                    // dd($institucionTitularBaja);
                    if ($institucionTitularBaja) {
                        $institucionTitularBaja->estado = false;
                        //$institucionTitularBaja->id_user_updated = auth()->user()->id;
                        $institucionTitularBaja->save();
                    }
                    $datoBajaInstitucional->observacion = $datoBajaInstitucional->observacion != null ? $datoBajaInstitucional->observacion : 'BAJA INSTITUCIONAL REGISTRADA CORRECTAMENTE.';
                    $datoBajaInstitucional->registro_tratado = true;
                    $datoBajaInstitucional->baja_automatica = true;
                    //$datoBajaInstitucional->id_user_updated = auth()->user()->id;
                    $datoBajaInstitucional->save();
                } else {
                    $personaCarnet = $personaServicio->ci;

                    if ($personaServicio->complemento != null) {
                        $personaCarnet .= '-' . $personaServicio->complemento;
                    }

                    $personaTipoAfiliadoServicio = 3;

                    // if ($datoBajaInstitucional->motivoBajaBajaInstitucional->motivo_baja == 'FALLECIDO') {
                    //     $personaTipoAfiliadoServicio = 4;
                    // }

                    $bajas[] = [
                        'ci' => $personaCarnet,
                        'id_estado_afiliado' => $personaTipoAfiliadoServicio,
                    ];
                    // dd($bajas);

                    //DB::beginTransaction();
                    //try {
                    $baja = new BajaInstitucional();

                    $baja->fecha_baja_inst = Carbon::now()->format('Y-m-d');
                    $baja->fecha_baja_ssu = Carbon::now()->format('Y-m-d');
                    $baja->id_persona = $datoBajaInstitucional->id_persona;
                    $baja->id_motivo_baja = 3;
                    $baja->descripcion_motivo = 'BAJA POR FINALIZACION DE CONTRATO VOLUNTARIO';
                    $baja->nro_documento_referencia = 'BAJA APORTES VOLUNTARIOS';
                    $baja->baja_definitiva = true;
                    $baja->baja_automatica = true;
                    $baja->observacion = 'BAJA FINALIZACION DE CONTRATO VOLUNTARIO';
                    $baja->registro_tratado = true;
                    $baja->id_tipo_asegurado = 1;
                    $baja->id_corresponde = $datoBajaInstitucional->id_titular;
                    $baja->id_institucion_baja = null;
                    $baja->id_user_created = 1;
                    $baja->fecha_baja_asuss = null;
                    $baja->observacion_asuss = null;
                    $baja->save();

                    $bajaInstitucional = BajaInstitucional::find($baja->id);
                    // dd($bajaInstitucional);
                    if ($bajaInstitucional) {
                        // $bajaInstitucional->observacion = $bajaInstitucional->observacion != null ? $bajaInstitucional->observacion : 'BAJA INSTITUCIONAL REGISTRADA CORRECTAMENTE';
                        // $bajaInstitucional->fecha_baja_ssu = $bajaInstitucional->fecha_baja_ssu != null ? $bajaInstitucional->fecha_baja_ssu : Carbon::now()->format('Y-m-d');
                        // $bajaInstitucional->baja_automatica = true;
                        // $bajaInstitucional->registro_tratado = true;
                        // //$bajaInstitucional->id_user_updated = auth()->user()->id;
                        // $bajaInstitucional->save();

                        // logica para dar de baja definitivo

                        $persona = Persona::where('id', $bajaInstitucional->id_persona)->with(['tipoAseguradoPersona'])->first();
                        // dd($persona);
                        if ($persona) {

                            if ($persona->tipoAseguradoPersona->tipo_asegurado == 'TITULAR') {
                                $datosTitular = Titular::where('id_persona', $persona->id)
                                    // ->where('estado_vigencia', 'CESANTE')
                                    ->where('estado', true)
                                    ->first();
                                // dd($datosTitular);
                                if ($datosTitular) {

                                    //PARA LA TABLA HISTORIAL

                                    $actualizacionHistorial = HistorialAsegurado::where('id_tipo_asegurado', $persona->id_tipo_asegurado)
                                        ->where('id_corresponde', $datosTitular->id)
                                        ->where('id_persona', $persona->id)
                                        ->first();
                                    // dd($actualizacionHistorial);
                                    if ($actualizacionHistorial) {
                                        $actualizacionHistorial->estado_vigencia = 'NO VIGENTE';
                                        $actualizacionHistorial->fecha_baja = Carbon::now()->format('Y-m-d');
                                        //$actualizacionHistorial->id_user_updated = auth()->user()->id;
                                        $actualizacionHistorial->save();
                                    }

                                    //PARA EL GRUPO FAMILIAR

                                    $servicioGrupoFamiliar = new GrupoFamiliarPersona();

                                    $listadoFamiliares = $servicioGrupoFamiliar->encontrarPersona($persona->id);
                                    // dd($listadoFamiliares);

                                    if ($listadoFamiliares != []) {
                                        if (count($listadoFamiliares) > 1) {

                                            foreach ($listadoFamiliares as $familiar) {
                                                if ($familiar['id'] != $persona->id) {

                                                    $actualizarBeneficiario = Beneficiario::where('id_persona', $familiar['id'])
                                                        ->where('id_titular', $datosTitular->id)
                                                        ->first();

                                                    if ($actualizarBeneficiario) {

                                                        //PARA LA TABLA HISTORIAL

                                                        $actualizacionHistorialBeneficiario = HistorialAsegurado::where('id_tipo_asegurado', $familiar['id_tipo_asegurado'])
                                                            ->where('id_corresponde', $actualizarBeneficiario->id)
                                                            ->where('id_persona', $familiar['id'])
                                                            ->first();
                                                        // dd($actualizacionHistorialBeneficiario);
                                                        if ($actualizacionHistorialBeneficiario) {
                                                            $actualizacionHistorialBeneficiario->estado_vigencia = 'NO VIGENTE';
                                                            $actualizacionHistorialBeneficiario->fecha_baja = Carbon::now()->format('Y-m-d');
                                                            //$actualizacionHistorialBeneficiario->id_user_updated = auth()->user()->id;
                                                            $actualizacionHistorialBeneficiario->save();
                                                        }

                                                        //MODIFICADO RECIEN EN LA TABLA BENEFICIARIO

                                                        $actualizarBeneficiario->vigencia = 'NO VIGENTE';
                                                        $actualizarBeneficiario->fecha_baja = Carbon::now()->format('Y-m-d');
                                                        //$actualizarBeneficiario->id_user_updated = auth()->user()->id;
                                                        $actualizarBeneficiario->save();

                                                        $baja_ben = new BajaInstitucional();
                                                        $baja_ben->fecha_baja_inst = Carbon::now()->format('Y-m-d');
                                                        $baja_ben->fecha_baja_ssu = Carbon::now()->format('Y-m-d');
                                                        $baja_ben->id_persona = $familiar['id'];
                                                        $baja_ben->id_motivo_baja = 3;
                                                        $baja_ben->descripcion_motivo = 'BAJA POR FINALIZACION DE CONTRATO VOLUNTARIO';
                                                        $baja_ben->nro_documento_referencia = 'BAJA APORTES VOLUNTARIOS - BENEFICIARIO';
                                                        $baja_ben->baja_definitiva = true;
                                                        $baja_ben->baja_automatica = true;
                                                        $baja_ben->observacion = 'BAJA FINALIZACION DE CONTRATO VOLUNTARIO - BENEFICIARIO';
                                                        $baja_ben->registro_tratado = true;
                                                        $baja_ben->id_tipo_asegurado = $familiar['id_tipo_asegurado'];
                                                        $baja_ben->id_corresponde = $actualizarBeneficiario->id;
                                                        $baja_ben->id_institucion_baja = null;
                                                        $baja_ben->id_user_created = 1;
                                                        $baja_ben->fecha_baja_asuss = null;
                                                        $baja_ben->observacion_asuss = null;
                                                        $baja_ben->save();

                                                        //PARA ACTUALIZAR A LA PERSONA

                                                        $actualizarPersonaBeneficiario = Persona::where('id', $familiar['id'])->first();

                                                        if ($actualizarPersonaBeneficiario) {
                                                            $actualizarPersonaBeneficiario->afiliado = false;
                                                            $actualizarPersonaBeneficiario->id_tipo_asegurado = 0;
                                                            //$actualizarPersonaBeneficiario->id_user_updated = auth()->user()->id;
                                                            $actualizarPersonaBeneficiario->save();
                                                        }

                                                        //PARA EL USUARIO

                                                        $actualizarUsuario = User::where('id_persona', $familiar['id'])->first();

                                                        if ($actualizarUsuario) {
                                                            $this->darBajaUsuario($actualizarUsuario->id);
                                                        }

                                                        /*if ($actualizarUsuario) {
                                                            $actualizarUsuario->estado = false;
                                                            $actualizarUsuario->id_user_updated = auth()->user()->id;
                                                            $actualizarUsuario->save();
                                                        }*/
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
                                            //$institucionTitular->id_user_updated = auth()->user()->id;
                                            $institucionTitular->save();
                                        }
                                    }

                                    //FIN PARA DAR DE BAJA A LAS INSTITUCIONES

                                    $datosTitular->estado_vigencia = 'NO VIGENTE';
                                    $datosTitular->fecha_baja = Carbon::now()->format('Y-m-d');
                                    //$datosTitular->id_user_updated = auth()->user()->id;
                                    $datosTitular->save();

                                    //PARA ACTUALIZAR A LA PERSONA
                                    $actualizarPersona = Persona::where('id', $persona->id)->first();

                                    if ($actualizarPersona) {
                                        $actualizarPersona->afiliado = false;
                                        $actualizarPersona->id_tipo_asegurado = 0;
                                        //$actualizarPersona->id_user_updated = auth()->user()->id;
                                        $actualizarPersona->save();
                                    }

                                    //PARA EL USUARIO

                                    $actualizarUsuario = User::where('id_persona', $persona->id)->first();

                                    if ($actualizarUsuario) {
                                        $this->darBajaUsuario($actualizarUsuario->id);
                                    }

                                    /*if ($actualizarUsuario) {
                                        $actualizarUsuario->estado = false;
                                        $actualizarUsuario->id_user_updated = auth()->user()->id;
                                        $actualizarUsuario->save();
                                    }*/
                                    //DB::commit();
                                } else {
                                    //DB::rollBack();
                                }
                            } elseif (Str::contains($persona->tipoAseguradoPersona->tipo_asegurado, 'BENEFICIARIO')) {
                                if ($persona->tipoAseguradoPersona->tipo_asegurado != 'BENEFICIARIO DEL INTERIOR') {

                                    $datosBeneficiario = Beneficiario::where('id_persona', $persona->id)
                                        // ->where('vigencia', 'CESANTE')
                                        ->orderBy('id', 'desc')
                                        ->first();

                                    if ($datosBeneficiario) {

                                        $datosBeneficiario->vigencia = 'NO VIGENTE';
                                        $datosBeneficiario->fecha_baja = Carbon::now()->format('Y-m-d');
                                        //$datosBeneficiario->id_user_updated = auth()->user()->id;
                                        $datosBeneficiario->save();

                                        //PARA LA TABLA HISTORIAL

                                        $actualizacionHistorial = HistorialAsegurado::where('id_tipo_asegurado', $persona->id_tipo_asegurado)
                                            ->where('id_corresponde', $datosBeneficiario->id)
                                            ->where('id_persona', $persona->id)
                                            ->first();

                                        if ($actualizacionHistorial) {
                                            $actualizacionHistorial->estado_vigencia = 'NO VIGENTE';
                                            $actualizacionHistorial->fecha_baja = Carbon::now()->format('Y-m-d');
                                            //$actualizacionHistorial->id_user_updated = auth()->user()->id;
                                            $actualizacionHistorial->save();
                                        }

                                        //PARA ACTUALIZAR A LA PERSONA
                                        $actualizarPersona = Persona::where('id', $persona->id)->first();

                                        if ($actualizarPersona) {
                                            $actualizarPersona->afiliado = false;
                                            $actualizarPersona->id_tipo_asegurado = 0;
                                            //$actualizarPersona->id_user_updated = auth()->user()->id;
                                            $actualizarPersona->save();
                                        }

                                        //PARA EL USUARIO

                                        $actualizarUsuario = User::where('id_persona', $persona->id)->first();

                                        if ($actualizarUsuario) {
                                            $this->darBajaUsuario($actualizarUsuario->id);
                                        }


                                        /*if ($actualizarUsuario) {
                                            $actualizarUsuario->estado = false;
                                            $actualizarUsuario->id_user_updated = auth()->user()->id;
                                            $actualizarUsuario->save();
                                        }*/
                                        //DB::commit();
                                    } else {
                                        //DB::rollBack();
                                    }
                                }
                            } elseif ($persona->tipoAseguradoPersona->tipo_asegurado == 'ESTUDIANTE') {
                                $datosEstudiante = Estudiante::where('id_persona', $persona->id)
                                    ->orderBy('id', 'desc')
                                    ->first();

                                if ($datosEstudiante) {
                                    $datosEstudiante->estado_vigencia = false;
                                    $datosEstudiante->fecha_baja = Carbon::now()->format('Y-m-d');
                                    //$datosEstudiante->id_user_updated = auth()->user()->id;
                                    $datosEstudiante->save();

                                    //PARA LA TABLA HISTORIAL

                                    $actualizacionHistorial = HistorialAsegurado::where('id_tipo_asegurado', $persona->id_tipo_asegurado)
                                        ->where('id_corresponde', $datosEstudiante->id)
                                        ->where('id_persona', $persona->id)
                                        ->first();

                                    if ($actualizacionHistorial) {
                                        $actualizacionHistorial->estado_vigencia = 'NO VIGENTE';
                                        $actualizacionHistorial->fecha_baja = Carbon::now()->format('Y-m-d');
                                        //$actualizacionHistorial->id_user_updated = auth()->user()->id;
                                        $actualizacionHistorial->save();
                                    }

                                    //PARA ACTUALIZAR A LA PERSONA
                                    $actualizarPersona = Persona::where('id', $persona->id)->first();

                                    if ($actualizarPersona) {
                                        $actualizarPersona->afiliado = false;
                                        $actualizarPersona->id_tipo_asegurado = 0;
                                        //$actualizarPersona->id_user_updated = auth()->user()->id;
                                        $actualizarPersona->save();
                                    }

                                    //PARA EL USUARIO

                                    $actualizarUsuario = User::where('id_persona', $persona->id)->first();

                                    if ($actualizarUsuario) {
                                        $this->darBajaUsuario($actualizarUsuario->id);
                                    }

                                    /*if ($actualizarUsuario) {
                                        $actualizarUsuario->estado = false;
                                        $actualizarUsuario->id_user_updated = auth()->user()->id;
                                        $actualizarUsuario->save();
                                    }*/
                                    //DB::commit();
                                } else {
                                    //DB::rollBack();
                                }
                            }
                        } else {
                            //DB::rollBack();
                        }
                    }
                    //} catch (\Throwable $th) {
                    //    DB::rollBack();
                    //}
                }
            }
        }
        // }
        // }
        \Log::info('Proceso finalizado correctamente');
        //fin bajas voluntarios

        $this->bajasGehma2(['bajas' => $bajas]);
        $bajas = [];
    }

    public function verificarConvenios()
    {
        $solicitudes = SolicitudPMedica::where('fecha_fin', '<', Carbon::now()->format('Y-m-d'))
            ->where('estado', true)
            ->get();

        if (count($solicitudes) > 0) {
            foreach ($solicitudes as $key => $solicitud) {
                $solicitud->estado = false;
                //$solicitud->id_user_updated = auth()->user()->id;
                $solicitud->save();
            }
        }

        $conveniosInterior = AutorizacionInterior::where('fecha_fin', '<', Carbon::now()->format('Y-m-d'))
            ->where('estado', true)
            ->get();

        if (count($conveniosInterior) > 0) {
            foreach ($conveniosInterior as $key => $convenioInterior) {
                $convenioInterior->estado = false;
                //$convenioInterior->id_user_updated = auth()->user()->id;
                $convenioInterior->save();

                $idPersona = $convenioInterior->id_persona_interior;

                $historialAsegurado = HistorialAsegurado::where('id_persona', $idPersona)
                    ->where('estado', true)
                    ->where('id_corresponde', $convenioInterior->id)
                    ->orderBy('id', 'desc')
                    ->first();

                if ($historialAsegurado) {
                    $historialAsegurado->estado_vigencia = 'NO VIGENTE';
                    if ($historialAsegurado->fecha_baja == null) {
                        $historialAsegurado->fecha_baja = $convenioInterior->fecha_fin;
                    }
                    //$historialAsegurado->id_user_updated = auth()->user()->id;
                    $historialAsegurado->save();
                }

                $aunTieneConvenio = AutorizacionInterior::where('id_persona_interior', $idPersona)
                    ->where('fecha_fin', '>=', Carbon::now()->format('Y-m-d'))
                    ->where('estado', true)
                    ->first();

                if ($aunTieneConvenio == null) {
                    $personaInterior = Persona::find($idPersona);
                    $personaInterior->afiliado = false;
                    $personaInterior->id_tipo_asegurado = 0;
                    //$personaInterior->id_user_updated = auth()->user()->id;
                    $personaInterior->save();

                    //PARA EL USUARIO

                    $actualizarUsuario = User::where('id_persona', $idPersona)->first();

                    if ($actualizarUsuario) {
                        $this->darBajaUsuario($actualizarUsuario->id);
                    }

                    /*if ($actualizarUsuario) {
                        $actualizarUsuario->estado = false;
                        $actualizarUsuario->id_user_updated = auth()->user()->id;
                        $actualizarUsuario->save();
                    }*/
                }
            }
        }

        $conveniosEmergencia = Convenio::where('fecha_fin', '<', Carbon::now()->format('Y-m-d'))
            ->where('estado', true)
            ->get();

        if (count($conveniosEmergencia) > 0) {
            foreach ($conveniosEmergencia as $key => $convenioEmergencia) {
                $convenioEmergencia->estado_validez = 'NO VIGENTE';
                $convenioEmergencia->estado = false;
                //$convenioEmergencia->id_user_updated = auth()->user()->id;
                $convenioEmergencia->save();

                $idPersona = $convenioEmergencia->id_persona;

                $historialAsegurado = HistorialAsegurado::where('id_persona', $idPersona)
                    ->where('estado', true)
                    ->where('id_corresponde', $convenioEmergencia->id)
                    ->orderBy('id', 'desc')
                    ->first();

                if ($historialAsegurado) {
                    $historialAsegurado->estado_vigencia = 'NO VIGENTE';
                    if ($historialAsegurado->fecha_baja == null) {
                        $historialAsegurado->fecha_baja = $convenioInterior->fecha_fin;
                    }
                    //$historialAsegurado->id_user_updated = auth()->user()->id;
                    $historialAsegurado->save();
                }

                $aunTieneConvenio = AutorizacionInterior::where('id_persona_interior', $idPersona)
                    ->where('fecha_fin', '>=', Carbon::now()->format('Y-m-d'))
                    ->where('estado', true)
                    ->first();

                $aunTieneConvenioEmergencia = Convenio::where('id_persona', $idPersona)
                    ->where('fecha_fin', '>=', Carbon::now()->format('Y-m-d'))
                    ->where('estado', true)
                    ->first();

                if ($aunTieneConvenio == null && $aunTieneConvenioEmergencia == null) {
                    $personaInterior = Persona::find($idPersona);
                    $personaInterior->afiliado = false;
                    $personaInterior->id_tipo_asegurado = 0;
                    //$personaInterior->id_user_updated = auth()->user()->id;
                    $personaInterior->save();

                    $actualizarUsuario = User::where('id_persona', $personaInterior->id)->first();

                    if ($actualizarUsuario) {
                        $this->darBajaUsuario($actualizarUsuario->id);
                    }
                }
            }
        }
    }

    public function bajasGehma2($data)
    {
        $urlGehma2 = LinkServicio::where('nombre_servicio', 'REGISTRO BAJAS INST GEHMA2')->first();
        $url_gehma2 = $urlGehma2->link_servicio;
        $respuesta = Http::post($url_gehma2, $data);
        // TODO: guardar en tabla replicas para ver respuestas de la API
        //$id_user_created = auth()->user()->id;
        $replica = new RespuestaApisService();
        $replica->guardarRespuesta(
            $data,
            $respuesta,
            'gehma2',
            'vigencia asegudaros convenio',
            'vigencia de derechos bajas institucionales',
            null
        );
        //dd($respuesta->json());
    }

    public function bajaMayores25()
    {
        //dd(Carbon::now()->subYears(20)->format('Y-m-d'));

        $personasBeneficiarios = Persona::where('id_tipo_asegurado', 3)
            ->where('afiliado', true)
            ->where('fecha_nacimiento', '<=', Carbon::now()->subYears(25)->format('Y-m-d'))
            ->get();
        // dd($personasBeneficiarios);
        if (count($personasBeneficiarios) > 0) {
            foreach ($personasBeneficiarios as $personaBeneficiario) {
                if (Carbon::parse($personaBeneficiario->fecha_nacimiento)->age > 24) {
                    $beneficiarioMayor = Beneficiario::where('id_persona', $personaBeneficiario->id)
                        ->whereIn('vigencia', ['VIGENTE', 'CESANTE'])
                        ->where('estado', true)
                        ->first();
                    // dd($beneficiarioMayor,$personaBeneficiario->id);
                    if ($beneficiarioMayor) {
                        $idInstitucion = 25;
                        $titularInstitucion = TitularInstitucion::where('id_titular', $beneficiarioMayor->id_titular)
                            ->where('estado', true)
                            ->orderBy('id', 'asc')
                            ->first();

                        if ($titularInstitucion !== null) {
                            $idInstitucion = $titularInstitucion->id_institucion;
                        }

                        $tieneBajaInstitiucional = BajaInstitucional::where('id_persona', $personaBeneficiario->id)->first();

                        if ($tieneBajaInstitiucional) {
                            $tieneBajaInstitiucional->observacion = $tieneBajaInstitiucional->observacion != null ? $tieneBajaInstitiucional->observacion : 'BAJA INSTITUCIONAL REGISTRADA CORRECTAMENTE';
                            $tieneBajaInstitiucional->fecha_baja_ssu = Carbon::now()->format('Y-m-d');
                            $tieneBajaInstitiucional->baja_automatica = true;
                            $tieneBajaInstitiucional->registro_tratado = true;
                            //$tieneBajaInstitiucional->id_user_updated = auth()->user()->id;
                            $tieneBajaInstitiucional->save();
                        } else {

                            BajaInstitucional::create([
                                'fecha_baja_inst' => Carbon::now()->format('Y-m-d'),
                                'fecha_baja_ssu' => Carbon::now()->format('Y-m-d'),
                                'id_persona' => $personaBeneficiario->id,
                                'id_motivo_baja' => 8,
                                'descripcion_motivo' => 'EL AFILIADO HA CUMPLIDO 25 AÑOS',
                                'nro_documento_referencia' => null,
                                'baja_definitiva' => true,
                                'baja_automatica' => true,
                                'registro_tratado' => true,
                                'id_tipo_asegurado' => $personaBeneficiario->id_tipo_asegurado,
                                'id_corresponde' => $beneficiarioMayor->id,
                                'id_institucion_baja' => $idInstitucion,
                                //'id_user_created' => auth()->user()->id,
                            ]);

                            /*if($tieneBajaInstitiucional){
                                dd('se creo', $tieneBajaInstitiucional);
                            } else {
                                dd('es una mierda ese beneficiario che');
                            }*/
                        }

                        $beneficiarioMayor->vigencia = 'NO VIGENTE';
                        $beneficiarioMayor->fecha_baja = Carbon::now()->format('Y-m-d');
                        //$beneficiarioMayor->id_user_updated = auth()->user()->id;
                        $beneficiarioMayor->save();

                        //PARA LA TABLA HISTORIAL

                        $actualizacionHistorial = HistorialAsegurado::where('id_tipo_asegurado', $personaBeneficiario->id_tipo_asegurado)
                            ->where('id_corresponde', $beneficiarioMayor->id)
                            ->where('id_persona', $personaBeneficiario->id)
                            ->first();

                        if ($actualizacionHistorial) {
                            $actualizacionHistorial->estado_vigencia = 'NO VIGENTE';
                            $actualizacionHistorial->fecha_cesantia = Carbon::now()->format('Y-m-d');
                            $actualizacionHistorial->fecha_baja = Carbon::now()->format('Y-m-d');
                            //$actualizacionHistorial->id_user_updated = auth()->user()->id;
                            $actualizacionHistorial->save();
                        }

                        //PARA ACTUALIZAR A LA PERSONA
                        $actualizarPersona = Persona::where('id', $personaBeneficiario->id)->first();

                        if ($actualizarPersona) {
                            $actualizarPersona->afiliado = false;
                            $actualizarPersona->id_tipo_asegurado = 0;
                            //$actualizarPersona->id_user_updated = auth()->user()->id;
                            $actualizarPersona->save();
                        }

                        //PARA EL USUARIO

                        $actualizarUsuario = User::where('id_persona', $personaBeneficiario->id)->first();

                        if ($actualizarUsuario) {
                            $this->darBajaUsuario($actualizarUsuario->id);
                        }

                        /*if ($actualizarUsuario) {
                            $actualizarUsuario->estado = false;
                            $actualizarUsuario->id_user_updated = auth()->user()->id;
                            $actualizarUsuario->save();
                        }*/

                        $ciServicio = $actualizarPersona->ci;

                        if ($actualizarPersona->complemento != null) {
                            $ciServicio .= '-' . $actualizarPersona->complemento;
                        }

                        $motivoServicio = 3;

                        $bajas = [];
                        $bajas[] = [
                            'ci' => $ciServicio,
                            'id_estado_afiliado' => $motivoServicio,
                        ];

                        $this->bajasGehma2(['bajas' => $bajas]);
                    }
                }
            }
        }
    }

    public function bajaMayores19()
    {
        $personasBeneficiarios = Persona::where('id_tipo_asegurado', 4)
            ->where('afiliado', true)
            ->where('fecha_nacimiento', '<=', Carbon::now()->subYears(19)->format('Y-m-d'))
            ->get();

        if (count($personasBeneficiarios) > 0) {
            foreach ($personasBeneficiarios as $key => $personaBeneficiario) {
                if (Carbon::parse($personaBeneficiario->fecha_nacimiento)->age > 18) {
                    $beneficiarioMayor = Beneficiario::where('id_persona', $personaBeneficiario->id)
                        ->where('vigencia', 'VIGENTE')
                        ->orderBy('id', 'desc')
                        ->first();

                    if ($beneficiarioMayor) {

                        $idInstitucion = 25;
                        $titularInstitucion = TitularInstitucion::where('id_titular', $beneficiarioMayor->id_titular)
                            ->where('estado', true)
                            ->orderBy('id', 'desc')
                            ->first();

                        if ($titularInstitucion !== null) {
                            $idInstitucion = $titularInstitucion->id_institucion;
                        }

                        $tieneBajaInstitiucional = BajaInstitucional::where('id_persona', $personaBeneficiario->id)->first();

                        if ($tieneBajaInstitiucional) {
                            $tieneBajaInstitiucional->observacion = $tieneBajaInstitiucional->observacion != null ? $tieneBajaInstitiucional->observacion : 'BAJA INSTITUCIONAL REGISTRADA CORRECTAMENTE';
                            $tieneBajaInstitiucional->fecha_baja_ssu = Carbon::now()->format('Y-m-d');
                            $tieneBajaInstitiucional->baja_automatica = true;
                            $tieneBajaInstitiucional->registro_tratado = true;
                            //$tieneBajaInstitiucional->id_user_updated = auth()->user()->id;
                            $tieneBajaInstitiucional->save();
                        } else {
                            $tieneBajaInstitiucional = BajaInstitucional::create([
                                'fecha_baja_inst' => Carbon::now()->format('Y-m-d'),
                                'fecha_baja_ssu' => Carbon::now()->format('Y-m-d'),
                                'id_persona' => $personaBeneficiario->id,
                                'id_motivo_baja' => 8,
                                'descripcion_motivo' => 'EL AFILIADO HA CUMPLIDO 19 AÑOS',
                                'nro_documento_referencia' => null,
                                'baja_definitiva' => true,
                                'baja_automatica' => true,
                                'registro_tratado' => true,
                                'id_tipo_asegurado' => $personaBeneficiario->id_tipo_asegurado,
                                'id_corresponde' => $beneficiarioMayor->id,
                                'id_institucion_baja' => $idInstitucion,
                                //'id_user_created' => auth()->user()->id,
                            ]);
                        }

                        $beneficiarioMayor->vigencia = 'NO VIGENTE';
                        $beneficiarioMayor->fecha_baja = Carbon::now()->format('Y-m-d');
                        //$beneficiarioMayor->id_user_updated = auth()->user()->id;
                        $beneficiarioMayor->save();

                        //PARA LA TABLA HISTORIAL

                        $actualizacionHistorial = HistorialAsegurado::where('id_tipo_asegurado', $personaBeneficiario->id_tipo_asegurado)
                            ->where('id_corresponde', $beneficiarioMayor->id)
                            ->where('id_persona', $personaBeneficiario->id)
                            ->first();

                        if ($actualizacionHistorial) {
                            $actualizacionHistorial->estado_vigencia = 'NO VIGENTE';
                            $actualizacionHistorial->fecha_cesantia = Carbon::now()->format('Y-m-d');
                            $actualizacionHistorial->fecha_baja = Carbon::now()->format('Y-m-d');
                            //$actualizacionHistorial->id_user_updated = auth()->user()->id;
                            $actualizacionHistorial->save();
                        }

                        //PARA ACTUALIZAR A LA PERSONA
                        $actualizarPersona = Persona::where('id', $personaBeneficiario->id)->first();

                        if ($actualizarPersona) {
                            $actualizarPersona->afiliado = false;
                            $actualizarPersona->id_tipo_asegurado = 0;
                            //$actualizarPersona->id_user_updated = auth()->user()->id;
                            $actualizarPersona->save();
                        }

                        //PARA EL USUARIO

                        $actualizarUsuario = User::where('id_persona', $personaBeneficiario->id)->first();

                        if ($actualizarUsuario) {
                            $this->darBajaUsuario($actualizarUsuario->id);
                        }

                        /*if ($actualizarUsuario) {
                            $actualizarUsuario->estado = false;
                            $actualizarUsuario->id_user_updated = auth()->user()->id;
                            $actualizarUsuario->save();
                        }*/

                        $ciServicio = $actualizarPersona->ci;

                        if ($actualizarPersona->complemento != null) {
                            $ciServicio .= '-' . $actualizarPersona->complemento;
                        }

                        $motivoServicio = 3;

                        $bajas = [];
                        $bajas[] = [
                            'ci' => $ciServicio,
                            'id_estado_afiliado' => $motivoServicio,
                        ];

                        $this->bajasGehma2(['bajas' => $bajas]);
                    }
                }
            }
        }
    }

    public function darBajaUsuario($idUsuario)
    {
        $usuario = User::find($idUsuario);
        if ($usuario) {
            $usuario->estado = false;
            $usuario->syncRoles([]);
            $usuario->syncPermissions([]);
            //$usuario->id_user_updated = auth()->user()->id;
            $usuario->save();
        }
    }
}
