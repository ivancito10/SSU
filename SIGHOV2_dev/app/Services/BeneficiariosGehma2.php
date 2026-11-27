<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\Titular;
use App\Models\Administracion\Persona;
use App\Models\Administracion\Provincia;
use App\Models\Administracion\Municipio;
use App\Models\Administracion\Zona;
use App\Models\Administracion\Residencia;
use App\Models\Administracion\LinkServicio;
use App\Models\Administracion\ApiReplicaLogs;
use Carbon\Carbon;

class BeneficiariosGehma2
{
    public $respuestaInsercion;

    public function insercionBeneficiarioGehma2($idBeneficiario)
    {
        $beneficiario = Beneficiario::find($idBeneficiario);
        $titular = Titular::find($beneficiario->id_titular);
        $personaBen = Persona::find($beneficiario->id_persona);
        $personaTit = Persona::find($titular->id_persona);

        $matricula = $personaBen->matricula_seguro;
        $nombres = $personaBen->nombres;
        $apellidoPaterno = $personaBen->p_apellido;
        $apellidoMaterno = $personaBen->s_apellido;
        $id_estadocivil = $personaBen->id_estado_civil ?? 1;
        // if ($personaBen->id_estado_civil == 1) {
        //     $id_estadocivil = 2;
        // } elseif ($personaBen->id_estado_civil == 2) {
        //     $id_estadocivil = 3;
        // } elseif ($personaBen->id_estado_civil == 3) {
        //     $id_estadocivil = 4;
        // } elseif ($personaBen->id_estado_civil == 4) {
        //     $id_estadocivil = 5;
        // } elseif ($personaBen->id_estado_civil == 5) {
        //     $id_estadocivil = 1;
        // } elseif ($personaBen->id_estado_civil == 6) {
        //     $id_estadocivil = 6;
        // } elseif ($personaBen->id_estado_civil == 7) {
        //     $id_estadocivil = 1;
        // } else {
        //     $id_estadocivil = 1;
        // }

        $sexo = $personaBen->sexo;
        $fechaNacimiento = Carbon::parse($personaBen->fecha_nacimiento)->format('Y-m-d');

        if ($personaBen->complemento == null) {
            $DocIdentificacion = $personaBen->ci;
        } else {
            $DocIdentificacion = $personaBen->ci . '-' . $personaBen->complemento;
        }

        $residenciaRes = Residencia::find($beneficiario->id_residencia);
        $zonaRes = Zona::find($residenciaRes->id_zona);
        $municipioRes = Municipio::find($zonaRes->id_municipio);
        $provinciaRes = Provincia::find($municipioRes->id_provincia);
        $id_departamento = $provinciaRes->id_departamento ?? 1;

        $municipioNac = Municipio::find($personaBen->id_nacimiento_municipio);
        if($municipioNac){
            $provinciaNac = Provincia::find($municipioNac->id_provincia);
        } else {
            $provinciaNac = null;
        }        
        $id_departamentonac = $provinciaNac ? $provinciaNac->id_departamento : 1;

        $domicilio = strtoupper($residenciaRes->direccion);
        $telefonoDomicilio = $beneficiario->telefono;
        $telefonoCelular = $beneficiario->telefono;
        $fechaRegistro = $beneficiario->fecha_afiliacion ?? $beneficiario->fecha_reafiliacion;
        $alergias = $beneficiario->alergias;
        $telefonocontacto = $beneficiario->telefono_referencia;
        $detallecontacto = strtoupper($beneficiario->nombre_referencia);
        $id_parentesco = $personaBen->id_tipo_asegurado ?? 0;
        $id_parentesco=$id_parentesco==7 ? 4:$id_parentesco; 
        $fechaAfiliacion = Carbon::parse($beneficiario->fecha_afiliacion)->format('Y-m-d');
        $observaciones = strtoupper($beneficiario->observacion);

        if ($personaTit->complemento == null) {
            $ci_titular = $personaTit->ci;
        } else {
            $ci_titular = $personaTit->ci . '-' . $personaTit->complemento;
        }

        $data = [
            "matricula" => $matricula,
            "nombres" => $nombres,
            "apellidoPaterno" => $apellidoPaterno,
            "apellidoMaterno" => $apellidoMaterno,
            "id_estadocivil" => $id_estadocivil,
            "sexo" => $sexo,
            "fechaNacimiento" => $fechaNacimiento,
            "DocIdentificacion" => $DocIdentificacion,
            "id_departamento" => $id_departamento,
            "id_departamentonac" => $id_departamentonac ?? 1,
            "domicilio" => $domicilio,
            "telefonoDomicilio" => $telefonoDomicilio,
            "telefonoCelular" => $telefonoCelular,
            "fechaRegistro" => $fechaRegistro,
            "alergias" => $alergias,
            "telefonocontacto" => $telefonocontacto,
            "detallecontacto" => $detallecontacto,
            "id_parentesco" => $id_parentesco,
            "fechaAfiliacion" => $fechaAfiliacion,
            "observaciones" => $observaciones,
            "ci_titular" => $ci_titular
        ];
        // dd($data);

        $urlGehma2 = LinkServicio::where('nombre_servicio', 'INSERCION BENEFICIARIO GEHMA2')->first();
        $url_gehma2 = $urlGehma2->link_servicio;
        $id_user_created = auth()->user()->id;

        $respuesta = Http::post($url_gehma2, $data);
        $replica = new RespuestaApisService();
        $replica->guardarRespuesta(
            $data,
            $respuesta,
            "ssu ude gehma2 afiliacion",
            "gehma2_Afiliacion",
            "SighoV2_Afiliacion beneficiarios",
            $id_user_created
        );
        $this->respuestaInsercion = $respuesta->json();
        return $this->respuestaInsercion;
    }
}
