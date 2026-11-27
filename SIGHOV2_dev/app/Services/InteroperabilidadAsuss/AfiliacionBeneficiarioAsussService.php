<?php

namespace App\Services\InteroperabilidadAsuss;

use App\Services\InteroperabilidadAsuss\ControlRegistrosAsussService;
use App\Models\Administracion\AccesoAsussToken;
use App\Models\Administracion\LinkAsuss;
use App\Models\Administracion\Persona;
use App\Models\Aportes\Institucion;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AfiliacionBeneficiarioAsussService
{
    protected LoginAsussService $loginService;

    public function __construct(LoginAsussService $loginService)
    {
        $this->loginService = $loginService;
    }

    public function transformarDatosContrastacion(array $datos)
    {
        $codigoEntidad = 'PER_VOL-0002';
        $tipoAsegurado = 'VOLUNTARIO';

        if ($datos['id_institucion'] !== null) {
            $tipoAsegurado = 'TRABAJADOR';
            $datosInstitucion = Institucion::find($datos['id_institucion']);
            if ($datosInstitucion) {
                $codigoEntidad = 'NIT-' . $datosInstitucion->nit;
                if ($datosInstitucion->id_clase_aportante === 2 || $datosInstitucion->id_clase_aportante === 3) {
                    $tipoAsegurado = 'RENTISTA';
                }
            }
        }

        $nombres = '';
        $primerApellido = '';
        $segundoApellido = '';
        $numeroDocumento = '';

        $datosPersona = Persona::find($datos['id_persona']);

        if ($datosPersona) {
            $nombres = $datosPersona->nombres;
            $primerApellido = $datosPersona->p_apellido;
            $segundoApellido = $datosPersona->s_apellido;
            $numeroDocumento = $datosPersona->ci;
        }

        $numeroDocumentoTitular = '';
        $fechaNacimientoTitular = '';

        $datosPersonaTitular = Persona::find($datos['id_persona_titular']);

        if ($datosPersonaTitular) {
            $numeroDocumentoTitular = implode('-', array_filter([$datosPersonaTitular->ci, $datosPersonaTitular->complemento]));
            $fechaNacimientoTitular = Carbon::parse($datosPersonaTitular->fecha_nacimiento)->format('Y-m-d');
        }

        $arrayTransformado = [
            'codigo_entidad' => $codigoEntidad,
            'data' => [
                [
                    'tipo_documento'           => 'CI',
                    'numero_documento'         => $numeroDocumento,
                    'fecha_nacimiento'         => $datos['fecha_nacimiento'],
                    'complemento_documento'    => $datos['complemento_documento'],
                    'nombres'                  => $nombres,
                    'primer_apellido'          => $primerApellido,
                    'segundo_apellido'         => $segundoApellido,
                    'tipo_asegurado'           => $tipoAsegurado,
                    'tipo_documento_titular'   => 'CI',
                    'numero_documento_titular' => $numeroDocumentoTitular,
                    'fecha_nacimiento_titular' => $fechaNacimientoTitular,
                ],
            ],
        ];

        return $arrayTransformado;
    }

    public function transformarDatosAfiliacion(array $datos)
    {
        //Para el codigo de entidad

        $codigoEntidad = 'PER_VOL-0002';

        if ($datos['id_institucion'] !== null) {
            $datosInstitucion = Institucion::find($datos['id_institucion']);
            if ($datosInstitucion) {
                $codigoEntidad = 'NIT-' . $datosInstitucion->nit;
            }
        }

        $numeroDocumentoTitular = '';
        $fechaNacimientoTitular = '';

        $datosPersonaTitular = Persona::find($datos['id_persona_titular']);

        if ($datosPersonaTitular) {
            $numeroDocumentoTitular = implode('-', array_filter([$datosPersonaTitular->ci, $datosPersonaTitular->complemento]));
            $fechaNacimientoTitular = Carbon::parse($datosPersonaTitular->fecha_nacimiento)->format('Y-m-d');
        }

        $arrayTransformado = [
            'codigo_entidad' => $codigoEntidad,
            'data' => [
                [
                    'titular' => [
                        'tipo_documento_titular'   => 'CI',
                        'numero_documento_titular' => $numeroDocumentoTitular,
                        'fecha_nacimiento_titular' => $fechaNacimientoTitular,
                    ],
                    'beneficiario' => [
                        'tipo_documento'   => 'CI',
                        'numero_documento' => $datos['numero_documento'],
                        'fecha_nacimiento' => $datos['fecha_nacimiento'],
                    ],
                    'afiliacion' => [
                        'fecha_inicio'       => $datos['fecha_inicio'],
                        'parentesco_titular' => $this->transformarParentesco($datos['id_tipo_asegurado']),
                    ],
                ],
            ],
        ];
        //dd($arrayTransformado);
        return $arrayTransformado;
    }

    public function afiliarBeneficiarioAsuss(array $datos)
    {
        //dd($datos);

        $arrayContrastacion = $this->transformarDatosContrastacion($datos);
        $arrayRegistro = $this->transformarDatosAfiliacion($datos);

        //dd($arrayContrastacion, $arrayRegistro);

        $respuestaContrastacion = $this->contrastarBeneficiario($arrayContrastacion);

        if (isset($respuestaContrastacion['error']) && $respuestaContrastacion['error'] === 'invalid_token') {
            AccesoAsussToken::latest()->update(['estado' => false]);
            $this->loginService->generarToken();
            $respuestaContrastacion = $this->contrastarBeneficiario($arrayContrastacion);
        }

        $respuestaRegistro = $this->registrarBeneficiario($arrayRegistro);

        $control = new ControlRegistrosAsussService();
        $datosRegistro = [
            'id_persona' => $datos['id_persona'],
            'id_tipo_asegurado' => $datos['id_tipo_asegurado'],
            'id_corresponde' => $datos['id_corresponde'],
            'id_titular' => $datos['id_titular'],
            'id_institucion' => $datos['id_institucion'],
            'respuesta_contrastacion_json' => $respuestaContrastacion,
            'respuesta_insercion_json' => $respuestaRegistro,
            'registro_exitoso' => null,
            'afiliacion' => true,
            'id_user_created' => $datos['id_user_created'],
        ];
        $control->registrarControl($datosRegistro);
    }

    public function contrastarBeneficiario(array $datos)
    {
        $linkAsuss = LinkAsuss::where('descripcion', 'CONTRASTACION BENEFICIARIO')
            ->where('estado', true)
            ->first();

        $respuestaContrastacion = [];

        if ($linkAsuss) {
            $token = $this->obtenerUltimoTokenDB() ?? $this->loginService->generarToken();

            $respuesta = Http::withToken($token)
                ->withoutVerifying()
                ->post($linkAsuss->link, $datos);

            $respuestaContrastacion = $respuesta->json();
        }

        return $respuestaContrastacion;
    }

    public function registrarBeneficiario(array $datos)
    {
        $linkAsuss = LinkAsuss::where('descripcion', 'AFILIACION BENEFICIARIOS')
            ->where('estado', true)
            ->first();

        $respuestaRegistro = [];

        if ($linkAsuss) {
            $token = $this->obtenerUltimoTokenDB() ?? $this->loginService->generarToken();

            $respuesta = Http::withToken($token)
                ->withoutVerifying()
                ->post($linkAsuss->link, $datos);

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

    protected function transformarEstadoCivil(int $idEstadoCivil)
    {
        $arrayEstadosCiviles = [
            1 => 'S',
            2 => 'C',
            3 => 'V',
            4 => 'D',
            5 => 'SPJ',
            6 => 'CB',
            7 => 'O',
        ];

        return $arrayEstadosCiviles[$idEstadoCivil];
    }

    protected function transformarParentesco(int $idTipoAsegurado)
    {
        $arrayTiposAsegurado = [
            2 => "PADRE/MADRE",
            3 => "HIJA/HIJO",
            4 => "HIJA/HIJO",
            5 => "ESPOSA/ESPOSO",
            6 => "OTROS",
            7 => "HIJA/HIJO",
            12 => "OTROS",
            13 => "OTROS"
        ];

        return $arrayTiposAsegurado[$idTipoAsegurado];
    }
}
