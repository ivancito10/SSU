<?php

namespace App\Services\InteroperabilidadAsuss;

use App\Models\Administracion\AccesoAsussToken;
use App\Models\Administracion\LinkAsuss;
use App\Models\Vigencia\MotivoBaja;
use Illuminate\Support\Facades\Http;

class BajaEstudianteAsussService
{
    protected LoginAsussService $loginService;

    public function __construct(LoginAsussService $loginService)
    {
        $this->loginService = $loginService;
    }

    public function transformarDatosBaja(array $datos)
    {
        $codigoEntidad = 'SIST_UNIV_0003';

        $motivoBaja = '';

        $motivos = MotivoBaja::find($datos['tipo_motivo']);

        if ($motivos) {
            $motivoBaja = $motivos->motivo_baja;
        }

        $arrayTranformado = [
            'tipo_documento' => 'CI',
            'numero_documento' => $datos['numero_documento'],
            'fecha_nacimiento' => $datos['fecha_nacimiento'],
            'codigo_entidad' => $codigoEntidad,
            'fecha_fin' => $datos['fecha_fin'],
            'fecha_fin_cesantia' => $datos['fecha_fin_cesantia'],
            'tipo_motivo' => $motivoBaja,
            'descripcion_motivo' => $motivoBaja
        ];

        return $arrayTranformado;
    }

    public function bajaEstudianteAsuss(array $datos)
    {

        $arrayRegistro = $this->transformarDatosBaja($datos);

        $respuestaRegistro = $this->registrarBajaEstudiante($arrayRegistro);

        //dd($respuestaRegistro);

        if (isset($respuestaRegistro['error']) && $respuestaRegistro['error'] === 'invalid_token') {
            AccesoAsussToken::latest()->update(['estado' => false]);
            $this->loginService->generarToken();
            $respuestaRegistro = $this->registrarBajaEstudiante($arrayRegistro);
        }

        $control = new ControlRegistrosAsussService();
        $datosRegistro = [
            'id_persona' => $datos['id_persona'],
            'id_tipo_asegurado' => $datos['id_tipo_asegurado'],
            'id_corresponde' => $datos['id_corresponde'],
            'id_titular' => $datos['id_titular'],
            'id_institucion' => $datos['id_institucion'],
            'respuesta_contrastacion_json' => null,
            'respuesta_insercion_json' => $respuestaRegistro,
            'registro_exitoso' => null,
            'afiliacion' => false,
            'id_user_created' => $datos['id_user_created'],
        ];
        $control->registrarControl($datosRegistro);
    }

    public function registrarBajaEstudiante(array $datos)
    {
        $linkAsuss = LinkAsuss::where('descripcion', 'BAJA ESTUDIANTES')
            ->where('estado', true)
            ->first();

        $respuestaRegistro = [];

        if ($linkAsuss) {
            $token = $this->obtenerUltimoTokenDB() ?? $this->loginService->generarToken();

            $respuesta = Http::withToken($token)
                ->withoutVerifying()
                ->put($linkAsuss->link, $datos);

            $respuestaRegistro = $respuesta->json();
        }

        return $respuestaRegistro;
    }

    protected function obtenerUltimoTokenDB()
    {
        $ultimo = AccesoAsussToken::where('estado', true)
            ->latest()
            ->first();

        return $ultimo ? $ultimo->token : null;
    }
}
