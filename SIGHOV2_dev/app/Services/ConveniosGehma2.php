<?php

namespace App\Services;

use App\Models\Administracion\LinkServicio;
use App\Models\Administracion\PersonalSsu;
use App\Models\Vigencia\AutorizacionInterior;
use App\Models\Vigencia\AutorizacionIntEspecialidad;
use App\Models\Vigencia\AutorizacionIntServicio;
use App\Models\Vigencia\Convenio;
use App\Models\Vigencia\ConvenioEspecialidad;
use App\Models\Vigencia\ConvenioServicio;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use App\Services\RespuestaApisService;

class ConveniosGehma2
{
    public function insercionConveniosGehma2($idConvenio)
    {
        set_time_limit(12000);
        $convenio = AutorizacionInterior::with([
            'personaInteriorAutorizacionInterior',
            'seguroInteriorAutorizacionInterior',
            'seguroInteriorAutorizacionInterior.residenciaSeguroInterior.zonaResidencia.municipioZona.provinciaMunicipio.departamentoProvincia',
            'seguroInteriorAutorizacionInterior.personaSeguroInterior'
        ])->find($idConvenio);

        $data = [];
        if ($convenio) {
            $personalSsu = PersonalSsu::where('cargo', 'GERENTE GENERAL')->where('estado', true)->with(['personaPersonalSsu'])->first();
            //dd($convenio);
            $data['ci'] = $convenio->personaInteriorAutorizacionInterior->ci;
            $data['complemento'] = $convenio->personaInteriorAutorizacionInterior->complemento;
            $data['matricula'] = $convenio->personaInteriorAutorizacionInterior->matricula_seguro;
            $data['id_regional_proc_doc'] = $convenio->seguroInteriorAutorizacionInterior->residenciaSeguroInterior->zonaResidencia->municipioZona->provinciaMunicipio->id_departamento;
            $data['nombre_completo'] = implode(' ', array_filter([
                $convenio->personaInteriorAutorizacionInterior->p_apellido ?? '',
                $convenio->personaInteriorAutorizacionInterior->s_apellido ?? '',
                $convenio->personaInteriorAutorizacionInterior->nombres
            ]));
            $data['nombres'] = $convenio->personaInteriorAutorizacionInterior->nombres;
            $data['apellido_paterno'] = $convenio->personaInteriorAutorizacionInterior->p_apellido ?? null;
            $data['apellido_materno'] = $convenio->personaInteriorAutorizacionInterior->s_apellido ?? null;
            $data['genero'] = $convenio->personaInteriorAutorizacionInterior->sexo;
            $data['fecha_nacimiento'] = $convenio->personaInteriorAutorizacionInterior->fecha_nacimiento;
            if ($convenio->personaInteriorAutorizacionInterior->id_estado_civil == 5 || $convenio->personaInteriorAutorizacionInterior->id_estado_civil == 7) {
                $data['id_estado_civil'] = 1;
            } else {
                $data['id_estado_civil'] = $convenio->personaInteriorAutorizacionInterior->id_estado_civil;
            }
            $data['id_estado_civil'] = $convenio->personaInteriorAutorizacionInterior->id_estado_civil;
            $data['telefono1'] = null;
            $data['celular'] = $convenio->telefono;
            $data['direccion'] = $convenio->direccion_referencia;
            $data['codigo_convenio'] = $convenio->cod_interior;
            $data['fecha_registro'] = $convenio->fecha_inicio;
            $data['dias_vigencia'] = (Carbon::parse($convenio->fecha_fin)->diffInDays(Carbon::parse($convenio->fecha_inicio))) + 1;
            $data['fecha_fin_vig_der'] = $convenio->fecha_fin;
            $data['id_seguro_origen'] = $convenio->id_seguro_interior;
            $data['nombre_aut'] = implode(' ', array_filter([
                $convenio->seguroInteriorAutorizacionInterior->personaSeguroInterior->p_apellido ?? '',
                $convenio->seguroInteriorAutorizacionInterior->personaSeguroInterior->s_apellido ?? '',
                $convenio->seguroInteriorAutorizacionInterior->personaSeguroInterior->nombres
            ]));
            $data['nombre_aut_ssu'] = implode(' ', array_filter([
                $personalSsu->personaPersonalSsu->p_apellido ?? '',
                $personalSsu->personaPersonalSsu->s_apellido ?? '',
                $personalSsu->personaPersonalSsu->nombres
            ]));

            $especialidades = 'AUTORIZAN ATENCIÓN MÉDICA EN ';

            $datosEsp = AutorizacionIntEspecialidad::where('id_autorizacion_interior', $idConvenio)->with(['especialidadAutorizacionIntEspecialidad'])->get();
            if ($datosEsp->count() > 0) {
                if ($datosEsp->count() == 1) {
                    $especialidades .= $datosEsp[0]->especialidadAutorizacionIntEspecialidad->especialidad . '.';
                } else {
                    foreach ($datosEsp as $key => $datoEsp) {
                        if ($key == $datosEsp->count() - 1) {
                            $especialidades .= 'y ' . $datoEsp->especialidadAutorizacionIntEspecialidad->especialidad . '.';
                        } elseif ($key == $datosEsp->count() - 2) {
                            $especialidades .= $datoEsp->especialidadAutorizacionIntEspecialidad->especialidad . ' ';
                        } else {
                            $especialidades .= $datoEsp->especialidadAutorizacionIntEspecialidad->especialidad . ', ';
                        }
                    }
                }
            } else {
                $especialidades = '';
            }

            $servicios = ' ADEMÁS DE ';

            $datosServ = AutorizacionIntServicio::where('id_autorizacion_interior', $idConvenio)->with(['servicioAutorizacionIntServicio'])->get();
            if ($datosServ->count() > 0) {
                if ($datosServ->count() == 1) {
                    $servicios .= $datosServ[0]->servicioAutorizacionIntServicio->servicio . ' SI SE REQUIERE.';
                } else {
                    foreach ($datosServ as $key => $datoServ) {
                        if ($key == $datosServ->count() - 1) {
                            $servicios .= 'y ' . $datoServ->servicioAutorizacionIntServicio->servicio . ' SI SE REQUIERE.';
                        } elseif ($key == $datosServ->count() - 2) {
                            $servicios .= $datoServ->servicioAutorizacionIntServicio->servicio . ' ';
                        } else {
                            $servicios .= $datoServ->servicioAutorizacionIntServicio->servicio . ', ';
                        }
                    }
                }
            } else {
                $servicios = '';
            }



            $liname = 'TAMBIÉN MEDICAMENTOS SEGÚN LA ';

            if ($convenio->medicamentos_liname && !$convenio->medicamentos_extraliname) {
                $liname .= 'LINAME.';
            } elseif (!$convenio->medicamentos_liname && $convenio->medicamentos_extraliname) {
                $liname .= 'EXTRALINAME.';
            } elseif ($convenio->medicamentos_liname && $convenio->medicamentos_extraliname) {
                $liname .= 'LINAME Y EXTRALINAME.';
            } else {
                $liname = '';
            }

            $otrasLimitacionesTexto = strip_tags($convenio->otras_limitaciones);
            $observacionesTexto = strip_tags($convenio->observacion);

            if ($otrasLimitacionesTexto != ''  && $observacionesTexto == '') {
                $textoFinal = $otrasLimitacionesTexto . '.';
            } elseif ($otrasLimitacionesTexto == '' && $observacionesTexto != '') {
                $textoFinal = $observacionesTexto . '.';
            } elseif ($otrasLimitacionesTexto != '' && $observacionesTexto != '') {
                $textoFinal = $otrasLimitacionesTexto . '. ' . $observacionesTexto . '.';
            } else {
                $textoFinal = '';
            }

            $data['limitacion'] = $especialidades . $servicios . ' ' . $liname . ' ' . $textoFinal;
        }

        //dd($data);
        $urlGehma = LinkServicio::where('nombre_servicio', 'INSERCION CONVENIO GEHMA2')->first();
        $url = $urlGehma->link_servicio;
        $id_user_created = auth()->user()->id;

        $respuesta = Http::post($url, $data);
        $replica = new RespuestaApisService();
        $replica->guardarRespuesta(
            $data,
            $respuesta,
            "gehma",
            "vigencia asegudaros convenio",
            "vigencia de derechos",
            $id_user_created
        );
        return $respuesta->json();
    }

    public function insercionConveniosEmergenciaGehma2($idConvenio)
    {
        set_time_limit(12000);
        // $convenio = AutorizacionInterior::with([
        //     'personaInteriorAutorizacionInterior',
        //     'seguroInteriorAutorizacionInterior',
        //     'seguroInteriorAutorizacionInterior.residenciaSeguroInterior.zonaResidencia.municipioZona.provinciaMunicipio.departamentoProvincia',
        //     'seguroInteriorAutorizacionInterior.personaSeguroInterior'
        // ])->find($idConvenio);

        $convenio = Convenio::where('id', $idConvenio)
            ->with(['personaConvenio'])
            ->with(['usuarioCreador', 'usuarioCreador.personaUser'])
            ->with(['titularAutorizaConvenio', 'titularAutorizaConvenio.titularPersona'])
            ->first();
            
        $data = [];
        if ($convenio) {
            $personalSsu = PersonalSsu::where('cargo', 'GERENTE GENERAL')->where('estado', true)->with(['personaPersonalSsu'])->first();

            $data['ci'] = $convenio->personaConvenio->ci;
            $data['genero'] = $convenio->personaConvenio->sexo;
            $data['celular'] = null;
            $data['nombres'] = $convenio->personaConvenio->nombres;
            $data['direccion'] = null;
            $data['matricula'] = $convenio->personaConvenio->matricula_seguro;
            $data['telefono1'] = null;
            
            $data['nombre_aut'] = implode(' ', array_filter([
                $convenio->titularAutorizaConvenio->titularPersona->p_apellido ?? '',
                $convenio->titularAutorizaConvenio->titularPersona->s_apellido ?? '',
                $convenio->titularAutorizaConvenio->titularPersona->nombres
            ]));
            $data['complemento'] = $convenio->personaConvenio->complemento;
            $data['dias_vigencia'] = (Carbon::parse($convenio->fecha_fin)->diffInDays(Carbon::parse($convenio->fecha_inicio))) + 1;
            $data['fecha_registro'] = $convenio->fecha_inicio;
            $data['nombre_aut_ssu'] = implode(' ', array_filter([
                $personalSsu->personaPersonalSsu->p_apellido ?? '',
                $personalSsu->personaPersonalSsu->s_apellido ?? '',
                $personalSsu->personaPersonalSsu->nombres
            ]));
            $data['codigo_convenio'] = $convenio->cod_convenio;
            if ($convenio->personaConvenio->id_estado_civil == 5 || $convenio->personaConvenio->id_estado_civil == 7) {
                $data['id_estado_civil'] = 1;
            } else {
                $data['id_estado_civil'] = $convenio->personaConvenio->id_estado_civil;
            }
            $data['nombre_completo'] = implode(' ', array_filter([
                $convenio->personaConvenio->p_apellido ?? '',
                $convenio->personaConvenio->s_apellido ?? '',
                $convenio->personaConvenio->nombres
            ]));
            $data['apellido_materno'] = $convenio->personaConvenio->s_apellido;
            $data['apellido_paterno'] = $convenio->personaConvenio->p_apellido;
            $data['fecha_nacimiento'] = $convenio->personaConvenio->fecha_nacimiento;
            $data['fecha_fin_vig_der'] = $convenio->fecha_fin;
            $data['id_regional_proc_doc'] = $convenio->personaConvenio->id_dept_exp;

            if($data['id_regional_proc_doc'] === 10){
                $data['id_regional_proc_doc'] = 1;
            }

            $especialidades = 'AUTORIZAN ATENCIÓN MÉDICA EN ';

            $datosEsp = ConvenioEspecialidad::where('id_convenio', $idConvenio)->with(['especialidadConvenioEspecialidad'])->get();
            if ($datosEsp->count() > 0) {
                if ($datosEsp->count() == 1) {
                    $especialidades .= $datosEsp[0]->especialidadConvenioEspecialidad->especialidad . '.';
                } else {
                    foreach ($datosEsp as $key => $datoEsp) {
                        if ($key == $datosEsp->count() - 1) {
                            $especialidades .= 'y ' . $datoEsp->especialidadConvenioEspecialidad->especialidad . '.';
                        } elseif ($key == $datosEsp->count() - 2) {
                            $especialidades .= $datoEsp->especialidadConvenioEspecialidad->especialidad . ' ';
                        } else {
                            $especialidades .= $datoEsp->especialidadConvenioEspecialidad->especialidad . ', ';
                        }
                    }
                }
            } else {
                $especialidades = '';
            }

            $servicios = ' ADEMÁS DE ';

            $datosServ = ConvenioServicio::where('id_convenio', $idConvenio)->with(['servicioConvenioServicio'])->get();
            if ($datosServ->count() > 0) {
                if ($datosServ->count() == 1) {
                    $servicios .= $datosServ[0]->servicioConvenioServicio->servicio . ' SI SE REQUIERE.';
                } else {
                    foreach ($datosServ as $key => $datoServ) {
                        if ($key == $datosServ->count() - 1) {
                            $servicios .= 'y ' . $datoServ->servicioConvenioServicio->servicio . ' SI SE REQUIERE.';
                        } elseif ($key == $datosServ->count() - 2) {
                            $servicios .= $datoServ->servicioConvenioServicio->servicio . ' ';
                        } else {
                            $servicios .= $datoServ->servicioConvenioServicio->servicio . ', ';
                        }
                    }
                }
            } else {
                $servicios = '';
            }



            $liname = 'TAMBIÉN MEDICAMENTOS SEGÚN LA ';

            if ($convenio->medicamentos_liname && !$convenio->medicamentos_extraliname) {
                $liname .= 'LINAME.';
            } elseif (!$convenio->medicamentos_liname && $convenio->medicamentos_extraliname) {
                $liname .= 'EXTRALINAME.';
            } elseif ($convenio->medicamentos_liname && $convenio->medicamentos_extraliname) {
                $liname .= 'LINAME Y EXTRALINAME.';
            } else {
                $liname = '';
            }

            $otrasLimitacionesTexto = strip_tags($convenio->otras_limitaciones);
            $observacionesTexto = strip_tags($convenio->observacion);

            if ($otrasLimitacionesTexto != ''  && $observacionesTexto == '') {
                $textoFinal = $otrasLimitacionesTexto . '.';
            } elseif ($otrasLimitacionesTexto == '' && $observacionesTexto != '') {
                $textoFinal = $observacionesTexto . '.';
            } elseif ($otrasLimitacionesTexto != '' && $observacionesTexto != '') {
                $textoFinal = $otrasLimitacionesTexto . '. ' . $observacionesTexto . '.';
            } else {
                $textoFinal = '';
            }

            $data['limitacion'] = $especialidades . $servicios . ' ' . $liname . ' ' . $textoFinal;

        }

        //dd($data);
        $urlGehma = LinkServicio::where('nombre_servicio', 'INSERCION CONVENIO EMERGENCIA GEHMA2')->first();
        $url = $urlGehma->link_servicio;
        $id_user_created = auth()->user()->id;

        $respuesta = Http::post($url, $data);
        $replica = new RespuestaApisService();
        $replica->guardarRespuesta(
            $data,
            $respuesta,
            "gehma",
            "vigencia asegudaros convenio",
            "vigencia de derechos EMERGENCIA",
            $id_user_created
        );
        return $respuesta->json();
    }
}
