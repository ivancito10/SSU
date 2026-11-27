<?php

namespace App\Services;

use App\Models\Administracion\AccesoAsussToken;
use Illuminate\Support\Facades\Http;

class InsersionAsuss
{
    public function afiliadosAsuss(
        $codigo_entidad,
        $fecha_ingreso_trabajo,
        $puesto_empleado,
        $salario,
        $departamento,
        $municipio,
        $fecha_inicio,
        $tipo_asegurado,
        $codigo_establecimiento,
        $matricula,
        $tipo_documento,
        $numero_documento,
        $fecha_nacimiento,
        $genero,
        $estado_civil,
        $foto_persona,
    ) {
        $data = [
            "codigo_entidad" => $codigo_entidad,
            "data" => [
                [
                    "empleador" => [
                        "fecha_ingreso_trabajo" => $fecha_ingreso_trabajo,
                        "puesto_empleado" => $puesto_empleado,
                        "salario" => $salario,
                        "departamento" => $departamento,
                        "municipio" => $municipio,
                    ],
                    "afiliacion" => [
                        "fecha_inicio" => $fecha_inicio,
                        "tipo_asegurado" => $tipo_asegurado,
                        "codigo_establecimiento" => $codigo_establecimiento,
                        "matricula" => $matricula,
                    ],
                    "persona" => [
                        "tipo_documento" => $tipo_documento,
                        "numero_documento" => $numero_documento,
                        "fecha_nacimiento" => $fecha_nacimiento,
                        "genero" => $genero,
                        "estado_civil" => $estado_civil,
                        "departamento" => $departamento,
                        "municipio" => $municipio,
                        "foto_persona" => $foto_persona,
                    ]
                ]
            ]
        ];
        // $json_data = json_encode($data);
        $tokenBd = AccesoAsussToken::orderby('id', 'desc')->first();
        // Convertir el array a JSON
        // URL de la API de destino
        $api_url = "https://test.asuss.gob.bo/asuss-asegurado-api/api/v2/afiliado/ente-gestor";
        // if ($tokenBd) {
        // Configuración de la solicitud
        $response = Http::withToken($tokenBd->token)
            ->withoutVerifying()
            ->post($api_url, $data);
        // Manejar la respuesta
        // dd($response);
        return response()->json($response[0]);
        // if ($response->getStatusCode() === 200) {
        //     $response_body = json_decode($response->getBody(), true);
        //     return $response_body;
        //     // Hacer algo con la respuesta exitosa
        //     // echo "Respuesta exitosa: " . print_r($response_body, true);
        // } else {
        //     // Manejar errores
        //     $response_error = [
        //         "error" => "no se realizo la insersion",
        //     ];
        //     return $response_error;
        // }
        // }
    }
}
