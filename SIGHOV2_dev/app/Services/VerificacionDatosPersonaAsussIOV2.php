<?php

namespace App\Services;

use App\Models\Administracion\AccesoAsussToken;
use App\Models\Administracion\LinkServicio;
use App\Models\Administracion\Persona;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class VerificacionDatosPersonaAsussIOV2
{
    /*{
    "ci":"6018392",
    "tipoPersona":"1",
    "nombres":"ADALID ABRAHAM",
    "apellidoPaterno": "CALLEJAS",
    "apellidoMaterno":"VEGA",
    "fechaNacimiento":"21/09/1983" 
    }*/

    public function verificarPersonaSegip($datosPersona)
    {
        //set_time_limit(1200);
        $datosCorrectos = [
            'numeroDocumento' => "2",
            'complemento' => "2",
            'primerApellido' => "2",
            'segundoApellido' => "2",
            'nombres' => "2",
            'fechaNacimiento' => "2",
            'respuestaAsuss' => false,
        ];

        $linkServicio = LinkServicio::where('nombre_servicio', 'CONTRASTAR DATOS SEGIP')->first();

        if (!$linkServicio) {
            return $datosCorrectos;
        }

        $linkContrastacionAsuss = $linkServicio->link_servicio;

        for ($i = 0; $i < 2; $i++) {
            $tokenBd = AccesoAsussToken::orderBy('id', 'desc')->first();
            if (!$tokenBd) {
                (new ConexionAsuss())->login();
                continue;
            }

            $respuesta = Http::withToken($tokenBd->token)
                ->withoutVerifying()
                ->post($linkContrastacionAsuss, $datosPersona);

            $data = $respuesta->json();
            //dd($data);

            if (array_key_exists('datosPersonaEnFormatoJson', $data)) {
                $persona = $data['datosPersonaEnFormatoJson'];
                //dd($persona);
                if ($persona) {
                    $datosCorrectos['numeroDocumento'] = $persona['numeroDocumento'];
                    $datosCorrectos['complemento'] = $persona['complemento'];
                    $datosCorrectos['primerApellido'] = $persona['primerApellido'];
                    $datosCorrectos['segundoApellido'] = $persona['segundoApellido'];
                    $datosCorrectos['nombres'] = $persona['nombres'];
                    $datosCorrectos['fechaNacimiento'] = $persona['fechaNacimiento'];
                } else {
                    $datosCorrectos['numeroDocumento'] = "0";
                    $datosCorrectos['complemento'] = "0";
                    $datosCorrectos['primerApellido'] = "0";
                    $datosCorrectos['segundoApellido'] = "0";
                    $datosCorrectos['nombres'] = "0";
                    $datosCorrectos['fechaNacimiento'] = "0";
                }

                if ($data['codigoRespuesta'] !== 0) {
                    $datosCorrectos['respuestaAsuss'] = true;
                }

                return $datosCorrectos;
            } else {
                if ($i == 0) {
                    (new ConexionAsuss())->login();
                }
            }
            
        }
        return $datosCorrectos;
    }

    /*public function probandoCachePoblacion()
    {
        $arrayDatos = [];

        $consultaPersona = Persona::where('ci', '17785388')
            ->where('afiliado', true)
            ->with([
                'tipoAseguradoPersona',
                'PersonaTitular',
                'PersonaBeneficiario.titularBeneficiario',
                'autorizacionInteriorPersonaInterior',
                'convenioPersona',
                'PersonaEstudiante'
            ])
            ->orderBy('id', 'asc')
            ->limit(100)
            ->get();

        foreach ($consultaPersona as $persona) {
            $idPersonaTitular = 0;

            $tipoAsegurado = $persona->tipoAseguradoPersona->tipo_asegurado ?? null;

            switch ($tipoAsegurado) {
                case 'TITULAR':
                    $titular = $persona->PersonaTitular->where('estado', true)->first();
                    $idPersonaTitular = $persona->id;
                    $liga = 'Afiliacion/FOTOS/';
                    $foto = $titular->foto_nombre ?? null;
                    break;

                case 'BENEFICIARIO DEL INTERIOR':
                    $autorizacionInterior = $persona->autorizacionInteriorPersonaInterior->where('estado', true)->first();
                    $idPersonaTitular = $autorizacionInterior->id_persona_titular ?? 0;
                    $liga = 'Vigencia/FOTOS/CONVENIO/';
                    $foto = $autorizacionInterior->foto_nombre ?? null;
                    break;

                case (Str::contains($tipoAsegurado, 'BENEFICIARIO')):
                    $beneficiario = $persona->PersonaBeneficiario->where('estado', true)->first();
                    $idPersonaTitular = $beneficiario->titularBeneficiario->id_persona ?? 0;
                    $liga = 'Afiliacion/FOTOS/';
                    $foto = $beneficiario->foto_nombre ?? null;
                    break;

                case 'TITULAR DEL INTERIOR':
                    $autorizacionInteriorTitular = $persona->autorizacionInteriorPersonaInterior->where('estado', true)->first();
                    $idPersonaTitular = $autorizacionInteriorTitular->id_persona_titular ?? 0;
                    $liga = 'Vigencia/FOTOS/CONVENIO/';
                    $foto = $autorizacionInteriorTitular->foto_nombre ?? null;
                    break;

                case 'TITULAR CONVENIO':
                    $convenio = $persona->convenioPersona->where('estado', true)->first();
                    $idPersonaTitular = $convenio->id_persona ?? 0;
                    $liga = 'Vigencia/FOTOS/CONVENIO/';
                    $foto = $convenio->foto_nombre ?? null;
                    break;

                case 'ESTUDIANTE':
                    $estudiante = $persona->PersonaEstudiante
                        ->where('estado_vigencia', true)
                        ->where('estado', true)
                        ->where('estado_validar', 'AFILIADO')
                        ->first();
                    $idPersonaTitular = $estudiante->id_persona ?? 0;
                    $liga = 'Afiliacion/SSUE/FOTOS/';
                    $foto = $estudiante->foto_nombre ?? null;
                    break;

                default:
                    $idPersonaTitular = 0;
                    break;
            }

            $arrayDatos[] = [
                'id_persona' => $persona->id,
                'nombres' => $persona->nombres,
                'p_apellido' => $persona->p_apellido,
                's_apellido' => $persona->s_apellido,
                'matricula_seguro' => $persona->matricula_seguro,
                'sexo' => $persona->sexo,
                'fecha_nacimiento' => $persona->fecha_nacimiento,
                'ci' => $persona->ci,
                'complemento' => $persona->complemento,
                'id_tipo_asegurado' => $persona->id_tipo_asegurado,
                'tipo_asegurado' => $tipoAsegurado,
                'excepcion' => $persona->excepcion,
                'id_persona_titular' => $idPersonaTitular,
                'foto' => $foto,
                'liga' => $liga,
            ];
        }

        return $arrayDatos;
    }*/
}
