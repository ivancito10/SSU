<?php

namespace App\Services;

use App\Models\Administracion\LinkServicio;
use App\Models\Administracion\Municipio;
use App\Models\Administracion\Persona;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\TitularInstitucion;
use App\Models\Vigencia\Preafiliacion;
use Illuminate\Support\Facades\Http;

class ReplicaPreafiliacion
{
    public function recopilarDatosPreafiliacion($idPreafiliacion)
    {
        $datosPreafiliacion = [];

        $preafiliacion = Preafiliacion::with(['personaPreafiliacion.tipoAseguradoPersona'])
            ->where('id', $idPreafiliacion)
            ->where('estado', true)
            ->first();

        if ($preafiliacion) {
            //dd($preafiliacion->personaPreafiliacion->tipoAseguradoPersona->tipo_asegurado);

            $esTitular = false;

            if ($preafiliacion->personaPreafiliacion->tipoAseguradoPersona->tipo_asegurado === 'PREAFILIADO TITULAR') {
                $esTitular = true;
                $datosPreafiliacion = [
                    'DocIdentificacion' => implode('-', array_filter([$preafiliacion->personaPreafiliacion->ci, $preafiliacion->personaPreafiliacion->complemento])),
                    'es_extranjero' => $preafiliacion->personaPreafiliacion->es_extranjero,
                    'nombres' => $preafiliacion->personaPreafiliacion->nombres,
                    'apellidoMaterno' => $preafiliacion->personaPreafiliacion->s_apellido,
                    'apellidoPaterno' => $preafiliacion->personaPreafiliacion->p_apellido,
                    'fechaNacimiento' => $preafiliacion->personaPreafiliacion->fecha_nacimiento,
                    'id_estadocivil' => $preafiliacion->personaPreafiliacion->id_estado_civil,
                    'sexo' => $preafiliacion->personaPreafiliacion->sexo,
                    'matricula' => $preafiliacion->personaPreafiliacion->matricula_seguro,
                    'id_departamentonac' => 1,
                    'telefonoCelular' => $preafiliacion->telefono,
                    'observaciones' => trim(strip_tags($preafiliacion->observaciones)),
                    'id_departamento' => $preafiliacion->personaPreafiliacion->id_dept_exp,
                    'id_institucion' => $preafiliacion->id_institucion,
                    'fechaRegistro' => $preafiliacion->created_at,
                    'fecha_ingreso' => $preafiliacion->fecha_inicio,
                ];
            }

            if ($preafiliacion->personaPreafiliacion->tipoAseguradoPersona->tipo_asegurado === 'PREAFILIADO BENEFICIARIO') {
                $esTitular = false;
                $titular = Titular::with(['titularPersona'])->find($preafiliacion->id_titular);
                if ($titular) {
                    $idInstitucionTitular = null;
                    if ($titular->tipo_titular) {
                        $institucion = TitularInstitucion::where('id_titular', $titular->id)
                            ->where('estado', true)
                            ->orderBy('id', 'asc')
                            ->first();
                        if ($institucion) {
                            $idInstitucionTitular = $institucion->id_institucion;
                        }
                    }

                    $datosPreafiliacion = [
                        'DocIdentificacion' => implode('-', array_filter([$preafiliacion->personaPreafiliacion->ci, $preafiliacion->personaPreafiliacion->complemento])),
                        'es_extranjero' => $preafiliacion->personaPreafiliacion->es_extranjero,
                        'nombres' => $preafiliacion->personaPreafiliacion->nombres,
                        'apellidoMaterno' => $preafiliacion->personaPreafiliacion->s_apellido,
                        'apellidoPaterno' => $preafiliacion->personaPreafiliacion->p_apellido,
                        'fechaNacimiento' => $preafiliacion->personaPreafiliacion->fecha_nacimiento,
                        'id_estadocivil' => $preafiliacion->personaPreafiliacion->id_estado_civil,
                        'sexo' => $preafiliacion->personaPreafiliacion->sexo,
                        'matricula' => $preafiliacion->personaPreafiliacion->matricula_seguro,
                        'id_departamentonac' => 1,
                        'telefonoCelular' => $preafiliacion->telefono,
                        'observaciones' => trim(strip_tags($preafiliacion->observaciones)),
                        'id_institucion' => $idInstitucionTitular,
                        'id_departamento' => $preafiliacion->personaPreafiliacion->id_dept_exp,
                        'fechaRegistro' => $preafiliacion->created_at,
                        'fecha_ingreso' => $preafiliacion->fecha_inicio,
                        'ci_titular' => implode('-', array_filter([$titular->titularPersona->ci, $titular->titularPersona->complemento])),
                        'id_parentesco' => 2,
                    ];
                }
            }
        }
        //return $datosPreafiliacion;
        if ($datosPreafiliacion !== []) {
            $this->enviarDatosPreafiliacion($datosPreafiliacion, $esTitular);
        }
    }


    public function enviarDatosPreafiliacion($data, $esTitular)
    {
        if ($esTitular) {
            $url = LinkServicio::where('nombre_servicio', 'PREAFILIAR TITULAR')->first();
        } else {
            $url = LinkServicio::where('nombre_servicio', 'PREAFILIAR BENEFICIARIO')->first();
        }

        $urlSigho1 = $url->link_servicio;
        $respuesta = Http::post($urlSigho1, $data);
        $id_user_created = auth()->user()->id;
        $replica = new RespuestaApisService();
        $origen = $esTitular ? "PREAFILIACION TITULAR - SIGHO V2" : "PREAFILIACION BENEFICIARIO - SIGHO V2";
        $replica->guardarRespuesta(
            $data,
            $respuesta,
            "SIGHO V1",
            "PREAFILIACION - SIGHO V1",
            $origen,
            $id_user_created
        );
        /*$validacionExito = $respuesta->status() === 200 ? true : false;
        return $validacionExito;*/
    }
}
