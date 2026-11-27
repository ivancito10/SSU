<?php

namespace App\Services\InteroperabilidadAsuss;

use App\Models\Administracion\AccesoAsussToken;
use App\Models\Administracion\LinkAsuss;
use App\Models\Administracion\Persona;
use App\Models\Aportes\Institucion;
use App\Services\InteroperabilidadAsuss\ControlRegistrosAsussService;
use Illuminate\Support\Facades\Http;

class AfiliacionTitularAsussService
{
    protected LoginAsussService $loginService;

    public function __construct(LoginAsussService $loginService)
    {
        $this->loginService = $loginService;
    }

    /*public function transformarDatosContrastacionSegip(array $datos)
    {
        $datosPersona = Persona::find($datos['id_persona']);

        $arrayTransformado = [
            'ci' => implode('-', array_filter([$datosPersona->ci, $datosPersona->complemento])),
            'tipoPersona' => '',
            'nombres' => $datosPersona->nombres,
            'apellidoPaterno' => $datosPersona->p_apellido,
            'apellidoMaterno' => $datosPersona->s_apellido,
            'fecha_nacimiento' => $datos['fecha_nacimiento']
        ];

        return $arrayTransformado;
    }*/

    public function transformarDatosContrastacion(array $datos)
    {
        $arrayTransformado = [
            'data' => [
                [
                    'tipo_documento'   => 'CI',
                    'numero_documento' => $datos['numero_documento'],
                    'fecha_nacimiento' => $datos['fecha_nacimiento'],
                ],
            ],
        ];

        return $arrayTransformado;
    }

    public function transformarDatosAfiliacion(array $datos)
    {
        //Para el codigo de entidad

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

        //dd($datos['id_institucion'], $tipoAsegurado);

        $departamentoNacimiento = $datos['departamento'];
        $municipioNacimiento = $datos['municipio'];

        $datosPersonaNacimiento = Persona::with('municipioPersona.provinciaMunicipio.departamentoProvincia')->find($datos['id_persona']);

        if ($datosPersonaNacimiento) {
            $municipioNacimiento = $datosPersonaNacimiento->municipioPersona->nombre;
            $departamentoNacimiento = $datosPersonaNacimiento->municipioPersona->provinciaMunicipio->departamentoProvincia->nombre;
        }

        $arrayTransformado = [
            'codigo_entidad' => $codigoEntidad,
            'data' => [
                [
                    'empleador' => [
                        'fecha_ingreso_trabajo' => $datos['fecha_ingreso_trabajo'],
                        'puesto_empleado'       => $datos['puesto_empleado'],
                        'salario'               => $datos['salario'],
                        'departamento'          => $datos['departamento'],
                        'municipio'             => $datos['municipio'],
                    ],
                    'afiliacion' => [
                        'fecha_inicio'           => $datos['fecha_inicio'],
                        'tipo_asegurado'         => $tipoAsegurado,
                        'codigo_establecimiento' => '',
                        'matricula'              => $datos['matricula'],
                    ],
                    'persona' => [
                        'tipo_documento'   => 'CI',
                        'numero_documento' => $datos['numero_documento'],
                        'fecha_nacimiento' => $datos['fecha_nacimiento'],
                        'genero'           => $datos['genero'],
                        'estado_civil'     => $this->transformarEstadoCivil($datos['estado_civil']),
                        'departamento'     => $departamentoNacimiento,
                        'municipio'        => $municipioNacimiento,
                        'foto_persona'     => $datos['foto_persona'],
                    ],
                ],
            ],
        ];
        //dd($arrayTransformado);
        return $arrayTransformado;
    }

    public function afiliarTitularAsuss(array $datos)
    {
        //dd($datos);

        $arrayContrastacion = $this->transformarDatosContrastacion($datos);
        $arrayRegistro = $this->transformarDatosAfiliacion($datos);

        $respuestaContrastacion = $this->contrastarTitular($arrayContrastacion);

        if (isset($respuestaContrastacion['error']) && $respuestaContrastacion['error'] === 'invalid_token') {
            AccesoAsussToken::latest()->update(['estado' => false]);
            $this->loginService->generarToken();
            $respuestaContrastacion = $this->contrastarTitular($arrayContrastacion);
        }

        $respuestaRegistro = $this->registrarTitular($arrayRegistro);

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

    /*public function contrastarPersonaSegip(array $datos)
    {
        $linkAsuss = LinkAsuss::where('descripcion', 'CONTRASTACION PERSONA SEGIP')
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
    }*/

    public function contrastarTitular(array $datos)
    {
        $linkAsuss = LinkAsuss::where('descripcion', 'CONTRASTACION TITULAR')
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

    public function registrarTitular(array $datos)
    {
        $linkAsuss = LinkAsuss::where('descripcion', 'AFILIACION TITULARES')
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
}
