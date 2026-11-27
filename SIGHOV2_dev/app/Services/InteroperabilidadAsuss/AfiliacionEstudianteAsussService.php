<?php

namespace App\Services\InteroperabilidadAsuss;

use App\Models\Administracion\AccesoAsussToken;
use App\Models\Administracion\LinkAsuss;
use App\Models\Administracion\Persona;
use App\Models\Afiliacion\Estudiante;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class AfiliacionEstudianteAsussService
{
    protected LoginAsussService $loginService;

    public function __construct(LoginAsussService $loginService)
    {
        $this->loginService = $loginService;
    }

    public function transformarDatosContrastacion(array $datos)
    {
        $datosPersona = Persona::find($datos['id_persona']);
        $arrayTransformado = [
            'data' => [
                [
                    'tipo_documento'   => 'CI',
                    'numero_documento' => $datosPersona ? implode('-', array_filter([$datosPersona->ci, $datosPersona->complemento])) : '',
                    'fecha_nacimiento' => $datosPersona ? $datosPersona->fecha_nacimiento : '',
                ],
            ],
        ];

        return $arrayTransformado;
    }

    public function transformarDatosAfiliacion(array $datos)
    {
        //Para el codigo de entidad

        $codigoEntidad = 'SIST_UNIV_0003';
        $tipoAsegurado = 'UNIVERSITARIO';

        //dd($datos['id_institucion'], $tipoAsegurado);

        $departamentoNacimiento = $datos['departamento'];
        $municipioNacimiento = $datos['municipio'];
        $matricula = $datos['matricula'];
        $numeroDocumento = $datos['numero_documento'];
        $fechaNacimiento = $datos['fecha_nacimiento'];
        $genero = $datos['genero'];
        $estadoCivil = $datos['estado_civil'];

        $datosPersona = Persona::with('municipioPersona.provinciaMunicipio.departamentoProvincia')->find($datos['id_persona']);

        if ($datosPersona) {
            $municipioNacimiento = $datosPersona->municipioPersona->nombre;
            $departamentoNacimiento = $datosPersona->municipioPersona->provinciaMunicipio->departamentoProvincia->nombre;

            $matricula = $datosPersona->matricula_seguro;
            $numeroDocumento = implode('-', array_filter([$datosPersona->ci, $datosPersona->complemento]));
            $fechaNacimiento = $datosPersona->fecha_nacimiento;
            $genero = $datosPersona->sexo;
            $estadoCivil = $datosPersona->id_estado_civil;
        }

        $fechaInicio = Carbon::now()->format('YYYY-mm-dd');
        $fotoPersona = $datos['foto_persona'];

        $datosEstudiante = Estudiante::find($datos['id_corresponde']);

        if ($datosEstudiante){
            $fechaInicio = Carbon::parse($datosEstudiante->fecha_afiliacion)->format('YYYY-mm-dd');
            $fotoPersona = $this->obtenerImagenBase64($datosEstudiante->foto_nombre);
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
                        'fecha_inicio'           => $fechaInicio,
                        'tipo_asegurado'         => $tipoAsegurado,
                        'codigo_establecimiento' => '',
                        'matricula'              => $matricula,
                    ],
                    'persona' => [
                        'tipo_documento'   => 'CI',
                        'numero_documento' => $numeroDocumento,
                        'fecha_nacimiento' => $fechaNacimiento,
                        'genero'           => $genero,
                        'estado_civil'     => $this->transformarEstadoCivil($estadoCivil),
                        'departamento'     => $departamentoNacimiento,
                        'municipio'        => $municipioNacimiento,
                        'foto_persona'     => $fotoPersona,
                    ],
                ],
            ],
        ];
        //dd($arrayTransformado);
        return $arrayTransformado;
    }

    public function afiliarEstudianteAsuss(array $datos)
    {
        //dd($datos);

        $arrayContrastacion = $this->transformarDatosContrastacion($datos);
        $arrayRegistro = $this->transformarDatosAfiliacion($datos);

        $respuestaContrastacion = $this->contrastarEstudiante($arrayContrastacion);

        if (isset($respuestaContrastacion['error']) && $respuestaContrastacion['error'] === 'invalid_token') {
            AccesoAsussToken::latest()->update(['estado' => false]);
            $this->loginService->generarToken();
            $respuestaContrastacion = $this->contrastarEstudiante($arrayContrastacion);
        }

        $respuestaRegistro = $this->registrarEstudiante($arrayRegistro);

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

    public function contrastarEstudiante(array $datos)
    {
        $linkAsuss = LinkAsuss::where('descripcion', 'CONTRASTACION ESTUDIANTE')
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

    public function registrarEstudiante(array $datos)
    {
        $linkAsuss = LinkAsuss::where('descripcion', 'AFILIACION ESTUDIANTES')
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

    private function obtenerImagenBase64($filename)
    {
        try {
            if ($filename) {
                $path = 'Plataforma/Especialista/Fotos/' . $filename;
                $imageData = Storage::disk('ftp')->get($path);

                $fotito = base64_encode($imageData);
                return 'data:image/jpeg;base64,' .$fotito;
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            return null;
        }
    }
}
