<?php

namespace App\Services;

use App\Models\Administracion\LinkServicio;
use Illuminate\Support\Facades\DB;
use App\Models\Vigencia\BajaInstitucional;
use App\Models\Administracion\Persona;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\Estudiante;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\TitularInstitucion;
use App\Models\User;
use App\Models\Vigencia\AutorizacionInterior;
use App\Models\Vigencia\Convenio;
use App\Models\Vigencia\HistorialAsegurado;
use App\Models\Vigencia\SolicitudPMedica;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Services\RespuestaApisService;

class VerificarDarBaja
{
    public function verificarDarBaja()
    {
        $bajas = [];

        $bajasInt = BajaInstitucional::where('fecha_baja_ssu', '<', Carbon::now()->format('Y-m-d'))
            ->where('baja_definitiva', false)
            //->where('baja_automatica', false)
            ->where('registro_tratado', false)
            ->with(['motivoBajaBajaInstitucional'])
            ->get();

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
                        $institucionTitularBaja->id_user_updated = auth()->user()->id;
                        $institucionTitularBaja->save();
                    }
                    $datoBajaInstitucional->observacion = $datoBajaInstitucional->observacion != null ? $datoBajaInstitucional->observacion : 'BAJA INSTITUCIONAL REGISTRADA CORRECTAMENTE.';
                    $datoBajaInstitucional->registro_tratado = true;
                    $datoBajaInstitucional->baja_automatica = true;
                    $datoBajaInstitucional->id_user_updated = auth()->user()->id;
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
                        $bajaInstitucional->id_user_updated = auth()->user()->id;
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
                $solicitud->id_user_updated = auth()->user()->id;
                $solicitud->save();
            }
        }

        $conveniosInterior = AutorizacionInterior::where('fecha_fin', '<', Carbon::now()->format('Y-m-d'))
            ->where('estado', true)
            ->get();

        if (count($conveniosInterior) > 0) {
            foreach ($conveniosInterior as $key => $convenioInterior) {
                $convenioInterior->estado = false;
                $convenioInterior->id_user_updated = auth()->user()->id;
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
                    $historialAsegurado->id_user_updated = auth()->user()->id;
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
                    $personaInterior->id_user_updated = auth()->user()->id;
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
                $convenioEmergencia->id_user_updated = auth()->user()->id;
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
                    $historialAsegurado->id_user_updated = auth()->user()->id;
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
                    $personaInterior->id_user_updated = auth()->user()->id;
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
                        $idInstitucion = null;
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
                            $tieneBajaInstitiucional->id_user_updated = auth()->user()->id;
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
                                'id_user_created' => auth()->user()->id,
                            ]);

                            /*if($tieneBajaInstitiucional){
                                dd('se creo', $tieneBajaInstitiucional);
                            } else {
                                dd('es una mierda ese beneficiario che');
                            }*/
                        }

                        $beneficiarioMayor->vigencia = 'NO VIGENTE';
                        $beneficiarioMayor->fecha_baja = Carbon::now()->format('Y-m-d');
                        $beneficiarioMayor->id_user_updated = auth()->user()->id;
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
                            $actualizacionHistorial->id_user_updated = auth()->user()->id;
                            $actualizacionHistorial->save();
                        }

                        //PARA ACTUALIZAR A LA PERSONA
                        $actualizarPersona = Persona::where('id', $personaBeneficiario->id)->first();

                        if ($actualizarPersona) {
                            $actualizarPersona->afiliado = false;
                            $actualizarPersona->id_tipo_asegurado = 0;
                            $actualizarPersona->id_user_updated = auth()->user()->id;
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

                        $idInstitucion = null;
                        $titularInstitucion = TitularInstitucion::where('id_titular', $beneficiarioMayor->id_titular)
                            ->where('estado', true)
                            ->orderBy('id', 'desc')
                            ->first();
                        //dd($idInstitucion);
                        if ($titularInstitucion !== null) {
                            $idInstitucion = $titularInstitucion->id_institucion;
                        }

                        $tieneBajaInstitiucional = BajaInstitucional::where('id_persona', $personaBeneficiario->id)->first();
                        

                        if ($tieneBajaInstitiucional) {
                            $tieneBajaInstitiucional->observacion = $tieneBajaInstitiucional->observacion != null ? $tieneBajaInstitiucional->observacion : 'BAJA INSTITUCIONAL REGISTRADA CORRECTAMENTE';
                            $tieneBajaInstitiucional->fecha_baja_ssu = Carbon::now()->format('Y-m-d');
                            $tieneBajaInstitiucional->baja_automatica = true;
                            $tieneBajaInstitiucional->registro_tratado = true;
                            $tieneBajaInstitiucional->id_user_updated = auth()->user()->id;
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
                                'id_user_created' => auth()->user()->id,
                            ]);
                            
                        }

                        $beneficiarioMayor->vigencia = 'NO VIGENTE';
                        $beneficiarioMayor->fecha_baja = Carbon::now()->format('Y-m-d');
                        $beneficiarioMayor->id_user_updated = auth()->user()->id;
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
                            $actualizacionHistorial->id_user_updated = auth()->user()->id;
                            $actualizacionHistorial->save();
                        }

                        //PARA ACTUALIZAR A LA PERSONA
                        $actualizarPersona = Persona::where('id', $personaBeneficiario->id)->first();

                        if ($actualizarPersona) {
                            $actualizarPersona->afiliado = false;
                            $actualizarPersona->id_tipo_asegurado = 0;
                            $actualizarPersona->id_user_updated = auth()->user()->id;
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
            $usuario->id_user_updated = auth()->user()->id;
            $usuario->save();
        }
    }
}
