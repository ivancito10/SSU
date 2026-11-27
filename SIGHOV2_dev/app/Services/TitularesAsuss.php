<?php

namespace App\Services;

use App\Models\Administracion\AccesoAsussToken;
use App\Models\Administracion\Persona;
use App\Models\Administracion\Municipio;
use App\Models\Administracion\Provincia;
use App\Models\Administracion\Departamento;
use App\Models\Administracion\EstadoCivil;
use App\Models\Administracion\Residencia;
use App\Models\Administracion\Zona;
use App\Models\Administracion\LinkServicio;
use App\Models\Aportes\Institucion;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\Estudiante;
use App\Models\Afiliacion\TitularInstitucion;
use App\Services\ConexionAsuss;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class TitularesAsuss
{
    public $respuesta;
    public $respuestaInsercion = [];
    public $respuestaDetalleTitular;
    public $respuestaBaja;
    public $respuestaInsercionEstudiante;

    public function validarTitularAsuss($ci, $complemento, $fecha_nacimiento)
    {
        //dd($ci, $complemento, $fecha_nacimiento);yy
        if ($complemento != null) {
            $data = [
                'data' => [
                    [
                        'tipo_documento' => 'CI',
                        'numero_documento' => $ci . '-' . $complemento,
                        'fecha_nacimiento' => Carbon::parse($fecha_nacimiento)->format('d/m/Y'),
                    ],
                ],
            ];
        } else {
            $data = [
                'data' => [
                    [
                        'tipo_documento' => 'CI',
                        'numero_documento' => $ci,
                        'fecha_nacimiento' => Carbon::parse($fecha_nacimiento)->format('d/m/Y'),
                    ],
                ],
            ];
        }

        $link_servicio = LinkServicio::where('nombre_servicio', 'VALIDAR TITULAR ASUSS')->first();

        $asuss_url = $link_servicio->link_servicio;

        $tokenBd = AccesoAsussToken::orderby('id', 'desc')->first();

        if ($tokenBd) {
            //$startTime = microtime(true);
            $response = Http::withToken($tokenBd->token)
                ->withoutVerifying()
                ->post($asuss_url, $data);
            $datos = $response->json();
            //$endTime = microtime(true);
            //$executionTime = $endTime - $startTime;
            //if($execu
            //    dd($executionTime);
            //} else {
            //    dd('RAPIDISIMO LOCOOOO');
            //}
            //dd($datos);
            if (isset($datos['error'])) {
                $logeo = new ConexionAsuss();
                $logueado = $logeo->login();
                $tokenBd = AccesoAsussToken::orderby('id', 'desc')->first();
                $response = Http::withToken($tokenBd->token)
                    ->withoutVerifying()
                    ->post($asuss_url, $data);
                $this->respuesta = $response->json();
            } else {
                $this->respuesta = $datos;
                //dd($this->respuesta);
            }
        } else {
            $logeo = new ConexionAsuss();
            $logueado = $logeo->login();
            $tokenBd = AccesoAsussToken::orderby('id', 'desc')->first();
            $response = Http::withToken($tokenBd->token)
                ->withoutVerifying()
                ->post($asuss_url, $data);
            $this->respuesta = $response->json();
            //dd($this->respuesta);
        }
        if (isset($this->respuesta['error'])) {
            $this->respuesta = [];
            $this->respuesta[0]['numero_documento'] = '';
            $this->respuesta[0]['fecha_nacimiento'] = '';
            $this->respuesta[0]['imagen_fotografica'] = '';
            $this->respuesta[0]['nombres'] = '';
            $this->respuesta[0]['primer_apellido'] = '';
            $this->respuesta[0]['segundo_apellido'] = '';
            $this->respuesta[0]['complemento_documento'] = '';
            $this->respuesta[0]['sexo'] = '';
            $this->respuesta[0]['estado_civil'] = '';
            $this->respuesta[0]['pais'] = '';
            $this->respuesta[0]['departamento'] = '';
            $this->respuesta[0]['estado_asegurado']['detalle'] = [];
            $this->respuesta[0]['estado_fuente_pago']['detalle'] = [];
            $this->respuesta[0]['estado_almacenamiento']['codigo'] = 504; //CODIGO 504: OCURRIO UN ERROR

            //dd($this->respuesta);

            return $this->respuesta;
        } else {
            //dd($this->respuesta);
            return $this->respuesta;
        }
    }

    public function insercionTitularAsuss($idTitular, $fotoBase64)
    {
        if ($idTitular == null || $fotoBase64 == null) {
            dd('NULOS');
        }
        //Datos de Persona
        $titular = Titular::find($idTitular);
        $idPersona = $titular->id_persona;
        $persona = Persona::find($idPersona);
        
        $ci = $persona->ci;
        $complemento = $persona->complemento;
        if ($complemento == null) {
            $numero_documento = $ci;
        } else {
            $numero_documento = $ci . '-' . $complemento;
        }
        $fecha_nacimiento = Carbon::parse($persona->fecha_nacimiento)->format('Y-m-d');
        $genero = $persona->sexo;
        $estadoCivil = EstadoCivil::find($persona->id_estado_civil);
        $estado_civil = $estadoCivil->sigla_asuss;
        $municipioNacimiento = Municipio::find($persona->id_nacimiento_municipio);
        $municipio = $municipioNacimiento->sigla_asuss;
        $idProvincia = $municipioNacimiento->id_provincia;
        $provinciaNacimiento = Provincia::find($idProvincia);
        $idDepartamento = $provinciaNacimiento->id_departamento;
        $departamentoNacimiento = Departamento::find($idDepartamento);
        $departamento = $departamentoNacimiento->sigla;
        $matricula = $persona->matricula_seguro;

        // Datos del Titular
        $fecha_inicio = Carbon::parse($titular->fecha_reafiliacion)->format('Y-m-d');

        //Datos Empleador

        if ($titular->tipo_titular) {
            $titularInstitucion = TitularInstitucion::where('id_titular', $idTitular)->first();

            $idInstitucion = $titularInstitucion->id_institucion;
            
            $institucion = Institucion::find($idInstitucion);
            if ($institucion->id_clase_aportante == 2 || $institucion->id_clase_aportante == 3) {
                $tipo_asegurado = 'REN';
                $codigo_entidad = 'SIST_RENT-0001';
                $fecha_ingreso_trabajo = $titularInstitucion->fecha_ingreso;
                $puesto_empleado = '';
                $salario = '';
            } else {
                $tipo_asegurado = 'TRA';
                $codigo_entidad = 'NIT-' . $institucion->nit;
                $fecha_ingreso_trabajo = $titularInstitucion->fecha_ingreso;
                $puesto_empleado = $titularInstitucion->cargo_actual;
                $salario = $titularInstitucion->salario;
            }
            
            //Para hallar el departamento y el municipio
            $residencia = Residencia::find($institucion->id_residencia);
            $zonaInst = Zona::find($residencia->id_zona);
            $municipioInst = Municipio::find($zonaInst->id_municipio);
            $municipioEmpleador = $municipioInst->sigla_asuss;

            $provinciaInst = Provincia::find($municipioInst->id_provincia);
            $departamentoInst = Departamento::find($provinciaInst->id_departamento);
            $departamentoEmpleador = $departamentoInst->sigla;
        } else {
            $tipo_asegurado = 'VOL';
            $codigo_entidad = 'PER_VOL-0002';
            $fecha_ingreso_trabajo = $fecha_inicio;
            $puesto_empleado = '';
            $salario = '';
            $departamentoEmpleador = '';
            $municipioEmpleador = '';
        }

        $data = [
            'codigo_entidad' => $codigo_entidad,
            'data' => [
                [
                    'empleador' => [
                        'fecha_ingreso_trabajo' => $fecha_ingreso_trabajo,
                        'puesto_empleado' => $puesto_empleado,
                        'salario' => $salario,
                        'departamento' => $departamentoEmpleador,
                        'municipio' => $municipioEmpleador,
                    ],
                    'afiliacion' => [
                        'fecha_inicio' => $fecha_inicio,
                        'tipo_asegurado' => $tipo_asegurado,
                        'codigo_establecimiento' => '200633',
                        'matricula' => $matricula,
                    ],
                    'persona' => [
                        'tipo_documento' => 'CI',
                        'numero_documento' => $numero_documento,
                        'fecha_nacimiento' => $fecha_nacimiento,
                        'genero' => $genero,
                        'estado_civil' => $estado_civil,
                        'departamento' => $departamento,
                        'municipio' => $municipio,
                        'foto_persona' => $fotoBase64,
                    ],
                ],
            ],
        ];
        //Aqui deben mostrarse todos lo datos diferentes de null, en especial de municipio
        //dd($data);

        $tokenBd = AccesoAsussToken::orderby('id', 'desc')->first();

        $link_servicio = LinkServicio::where('nombre_servicio', 'INSERCION TITULAR ASUSS')->first();

        $asuss_url = $link_servicio->link_servicio;

        if ($tokenBd) {
            //dd('entra aqui');
            $response = Http::withToken($tokenBd->token)
                ->withoutVerifying()
                ->post($asuss_url, $data);
            $datos = $response->json();
            //dd($data, 'hay token');
            if (isset($datos['error'])) {
                $logeo = new ConexionAsuss();
                $logueado = $logeo->login();
                $tokenBd = AccesoAsussToken::orderby('id', 'desc')->first();
                $response = Http::withToken($tokenBd->token)
                    ->withoutVerifying()
                    ->post($asuss_url, $data);

                // Aqui NO registra  la inyeccion y determina el error
                $this->respuestaInsercion = $response->json();

                //dd($data, 'hay token pero hay error', $this->respuestaInsercion);
            } else {

                // Aqui ya se registra  la inyeccion sin  errores
                $this->respuestaInsercion = $datos;

                //dd($data, 'hay token pero no error', $this->respuestaInsercion);
            }
        } else {
            //dd('SE PASO DE LARGO CARNAL');
            //dd($data, 'no hay token');
            $logeo = new ConexionAsuss();
            $logueado = $logeo->login();
            $tokenBd = AccesoAsussToken::orderby('id', 'desc')->first();
            $response = Http::withToken($tokenBd->token)
                ->withoutVerifying()
                ->post($asuss_url, $data);
            $this->respuestaInsercion = $response->json();

        }
        //dd($this->respuestaInsercion);

        if (isset($this->respuestaInsercion['error'])) {
            $this->respuestaInsercion = [];
            $this->respuestaInsercion[0]['estado_almacenamiento']['codigo'] = 504; //CODIGO 504: OCURRIO UN ERROR

            //dd($this->respuestaInsercion, 'ENTRO A ESTA OTRA RESPUESTA QUE SE SUPONE QUE ES ERROR');
            return $this->respuestaInsercion;
        } else {
            //dd($this->respuestaInsercion, 'ENTRO A ESTA RESPUESTA QUE DEBERIA SER LA BUENA');
            return $this->respuestaInsercion;
        }
    }

    public function detalleTitularAsuss($idTitular)
    {
        $titular = Titular::find($idTitular);
        $personaTitular = Persona::find($titular->id_persona);
        $tipo_documento = 'CI';
        $ciTitular = $personaTitular->ci;
        $complementoTitular = $personaTitular->complemento;
        if ($complementoTitular == null) {
            $documento_identidad = $ciTitular;
        } else {
            $documento_identidad = $ciTitular . '-' . $complementoTitular;
        }
        $fecha_nacimiento = Carbon::parse($personaTitular->fecha_nacimiento)->format('Y-m-d');

        //Datos codigo entidad

        if ($titular->tipo_titular == true) {
            $titularInstitucion = TitularInstitucion::where('id_titular', $idTitular)->first();

            $idInstitucion = $titularInstitucion->id_institucion;
            $institucion = Institucion::find($idInstitucion);
            if ($institucion->id_clase_aportante == 2 || $institucion->id_clase_aportante == 3) {
                $codigo_entidad = 'SIST_RENT-0001';
            } else {
                $codigo_entidad = 'NIT-' . $institucion->nit;
            }
        } else {
            $codigo_entidad = 'PER_VOL-0002';
        }

        $fecha_afiliacion = Carbon::parse($titular->fecha_afiliacion)->format('Y-m-d');

        $tokenBd = AccesoAsussToken::orderby('id', 'desc')->first();

        $link_servicio = LinkServicio::where('nombre_servicio', 'DETALLE TITULAR ASUSS')->first();

        $asuss_url = $link_servicio->link_servicio . $tipo_documento . '/' . $documento_identidad . '/' . $fecha_nacimiento . '/' . $codigo_entidad . '/' . $fecha_afiliacion;
        //dd($asuss_url);
        if ($tokenBd) {

            $response = Http::withToken($tokenBd->token)
                ->withoutVerifying()
                ->get($asuss_url);

            $datos = $response->json();

            if (isset($datos['error'])) {
                $logeo = new ConexionAsuss();
                $logueado = $logeo->login();
                $tokenBd = AccesoAsussToken::orderby('id', 'desc')->first();
                $response = Http::withToken($tokenBd->token)
                    ->withoutVerifying()
                    ->get($asuss_url);
                $this->respuestaDetalleTitular = $response->json();
            } else {
                $this->respuestaDetalleTitular = $datos;
                //dd($this->respuesta);
            }
        } else {
            $logeo = new ConexionAsuss();
            $logueado = $logeo->login();
            $tokenBd = AccesoAsussToken::orderby('id', 'desc')->first();
            $response = Http::withToken($tokenBd->token)
                ->withoutVerifying()
                ->get($asuss_url);
            $this->respuestaDetalleTitular = $response->json();
        }
        return $this->respuestaDetalleTitular;
    }

    public function bajaAfiliadoTitular($idTitular, $fechaFin, $fechaFinCesantia, $tipoMotivo, $descripcionMotivo)
    {
        $titular = Titular::find($idTitular);
        $persona = Persona::find($titular->id_persona);

        $ciTitular = $persona->ci;
        $complementoTitular = $persona->complemento;
        if ($complementoTitular == null) {
            $numero_documento = $ciTitular;
        } else {
            $numero_documento = $ciTitular . '-' . $complementoTitular;
        }
        $fecha_nacimiento = Carbon::parse($persona->fecha_nacimiento)->format('Y-m-d');

        if ($titular->tipo_titular == true) {
            $titularInstitucion = TitularInstitucion::where('id_titular', $idTitular)->first();

            $idInstitucion = $titularInstitucion->id_institucion;
            $institucion = Institucion::find($idInstitucion);
            if ($institucion->id_clase_aportante == 2 || $institucion->id_clase_aportante == 3) {
                $codigo_entidad = 'SIST_RENT-0001';
            } else {
                $codigo_entidad = 'NIT-' . $institucion->nit;
            }
        } else {
            $codigo_entidad = 'PER_VOL-0002';
        }

        $fecha_fin = Carbon::parse($fechaFin)->format('Y-m-d');
        $fecha_fin_cesantia = Carbon::parse($fechaFinCesantia)->format('Y-m-d');

        $data = [
            'tipo_documento' => 'CI',
            'numero_documento' => $numero_documento,
            'fecha_nacimiento' => $fecha_nacimiento,
            'codigo_entidad' => $codigo_entidad, //NIT UMSA: 1020071028 NIT UPEA: 122025022
            'fecha_fin' => $fecha_fin,
            'tipo_motivo' => $tipoMotivo, //BAJA POR SOLICITUD, BAJA POR DEFUNCION, OTROS
            'descripcion_motivo' => $descripcionMotivo, // DETALLAR EL MOTIVO DE LA BAJA, CAMPO OPCIONAL
        ];

        //dd($data);

        $link_servicio = LinkServicio::where('nombre_servicio', 'BAJA TITULAR ASUSS')->first();

        $asuss_url = $link_servicio->link_servicio;

        $tokenBd = AccesoAsussToken::orderby('id', 'desc')->first();

        if ($tokenBd) {
            $response = Http::withToken($tokenBd->token)
                ->withoutVerifying()
                ->put($asuss_url, $data);
            $datos = $response->json();

            if (isset($datos['error'])) {
                $logeo = new ConexionAsuss();
                $logueado = $logeo->login();
                $tokenBd = AccesoAsussToken::orderby('id', 'desc')->first();
                $response = Http::withToken($tokenBd->token)
                    ->withoutVerifying()
                    ->put($asuss_url, $data);
                $this->respuestaBaja = $response->json();
            } else {
                $this->respuestaBaja = $datos;
                //dd($this->respuesta);
            }
        } else {
            $logeo = new ConexionAsuss();
            $logueado = $logeo->login();
            $tokenBd = AccesoAsussToken::orderby('id', 'desc')->first();
            $response = Http::withToken($tokenBd->token)
                ->withoutVerifying()
                ->put($asuss_url, $data);
            $this->respuestaBaja = $response->json();
        }
        dd($this->respuestaBaja);
        return $this->respuestaBaja;
    }

    public function insercionEstudianteAsuss($idEstudiante, $fotoBase64)
    {
        //Datos de Persona

        $estudiante = Estudiante::find($idEstudiante);
        $idPersona = $estudiante->id_persona;
        $persona = Persona::find($idPersona);
        $ci = $persona->ci;
        $complemento = $persona->complemento;
        if ($complemento == null) {
            $numero_documento = $ci;
        } else {
            $numero_documento = $ci . '-' . $complemento;
        }
        $fecha_nacimiento = Carbon::parse($persona->fecha_nacimiento)->format('Y-m-d');
        $genero = $persona->sexo;
        $estadoCivil = EstadoCivil::find($persona->id_estado_civil);
        $estado_civil = $estadoCivil->sigla_asuss;
        $municipioNacimiento = Municipio::find($persona->id_nacimiento_municipio);
        $municipio = $municipioNacimiento->sigla_asuss;
        $idProvincia = $municipioNacimiento->id_provincia;
        $provinciaNacimiento = Provincia::find($idProvincia);
        $idDepartamento = $provinciaNacimiento->id_departamento;
        $departamentoNacimiento = Departamento::find($idDepartamento);
        $departamento = $departamentoNacimiento->sigla;
        $matricula = $persona->matricula_seguro;

        // Datos del Titular
        $fecha_inicio = Carbon::parse($estudiante->fecha_reafiliacion)->format('Y-m-d');

        //Datos Empleador

        $tipo_asegurado = 'UNIV';
        $codigo_entidad = 'SIST_UNIV_0003';
        $fecha_ingreso_trabajo = $fecha_inicio;
        $puesto_empleado = '';
        $salario = '';
        $departamentoEmpleador = '';
        $municipioEmpleador = '';

        $data = [
            'codigo_entidad' => $codigo_entidad,
            'data' => [
                [
                    'empleador' => [
                        'fecha_ingreso_trabajo' => $fecha_ingreso_trabajo,
                        'puesto_empleado' => $puesto_empleado,
                        'salario' => $salario,
                        'departamento' => $departamentoEmpleador,
                        'municipio' => $municipioEmpleador,
                    ],
                    'afiliacion' => [
                        'fecha_inicio' => $fecha_inicio,
                        'tipo_asegurado' => $tipo_asegurado,
                        'codigo_establecimiento' => '200633',
                        'matricula' => $matricula,
                    ],
                    'persona' => [
                        'tipo_documento' => 'CI',
                        'numero_documento' => $numero_documento,
                        'fecha_nacimiento' => $fecha_nacimiento,
                        'genero' => $genero,
                        'estado_civil' => $estado_civil,
                        'departamento' => $departamento,
                        'municipio' => $municipio,
                        'foto_persona' => $fotoBase64,
                    ],
                ],
            ],
        ];

        
       // dd($data);

        $tokenBd = AccesoAsussToken::orderby('id', 'desc')->first();

        $link_servicio = LinkServicio::where('nombre_servicio', 'INSERCION TITULAR ASUSS')->first();

        $asuss_url = $link_servicio->link_servicio;

        if ($tokenBd) {
            $response = Http::withToken($tokenBd->token)
                ->withoutVerifying()
                ->post($asuss_url, $data);

            // Aqui envia el método post al ASUSS
            $datos = $response->json();

            if (isset($datos['error'])) {
                $logeo = new ConexionAsuss();
                $logueado = $logeo->login();
                $tokenBd = AccesoAsussToken::orderby('id', 'desc')->first();
                $response = Http::withToken($tokenBd->token)
                    ->withoutVerifying()
                    ->post($asuss_url, $data);

                // DETECTA EL ERROR Y LO CORTA
                $this->respuestaInsercionEstudiante = $response->json();

            } else {
                // Se registra en el postman con éxito
                $this->respuestaInsercionEstudiante = $datos;
                
            }
        } else {
            $logeo = new ConexionAsuss();
            $logueado = $logeo->login();
            $tokenBd = AccesoAsussToken::orderby('id', 'desc')->first();
            $response = Http::withToken($tokenBd->token)
                ->withoutVerifying()
                ->post($asuss_url, $data);
                // Se registra en el postman con éxito cuando no hay TOKEN
            $this->respuestaInsercionEstudiante = $response->json();
        }
        //dd($this->respuestaInsercion);
        return $this->respuestaInsercionEstudiante;
    }
}
