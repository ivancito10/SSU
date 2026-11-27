<?php

namespace App\Services;

use App\Models\Administracion\AccesoAsussToken;
use App\Services\ConexionAsuss;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\TitularInstitucion;
use App\Models\Administracion\TipoAsegurado;
use App\Models\Administracion\Persona;
use App\Models\Administracion\LinkServicio;
use App\Models\Aportes\Institucion;

class BeneficiariosAsuss
{
    public $respuesta;
    public $respuestaValidar;
    public $respuestaBaja;

    public function validarBeneficiariosAsuss($idBeneficiario)
    {
        try {
            $beneficiario = Beneficiario::find($idBeneficiario);
            $idTitular = $beneficiario->id_titular;
            $idPersonaBen = $beneficiario->id_persona;

            $titular = Titular::find($idTitular);
            $idPersona = $titular->id_persona;
            $persona = Persona::find($idPersona);
            $ciTitular = $persona->ci;
            $complementoTitular = $persona->complemento;
            if ($complementoTitular == null) {
                $carnetTitular = $ciTitular;
            } else {
                $carnetTitular = $ciTitular . '-' . $complementoTitular;
            }
            $fechaNacimientoTitular = Carbon::parse($persona->fecha_nacimiento)->format('Y-m-d');

            $titularInstitucion = TitularInstitucion::where('id_titular', $idTitular)->first();
            if ($titularInstitucion == null) {
                $codigoEntidad = 'PER_VOL-0002';
            } else {
                $idInstitucion = $titularInstitucion->id_institucion;
                $institucion = Institucion::find($idInstitucion);
                $nit = $institucion->nit;
                $codigoEntidad = 'NIT-' . $nit;
            }

            //PARA LOS DATOS DEL BENEFICIARIO
            $personaBen = Persona::find($idPersonaBen);
            $complementoBen = $personaBen->complemento;
            $ciBen = $personaBen->ci;
            if ($complementoBen == null) {
                $carnetCompleto = $ciBen;
            } else {
                $carnetCompleto = $ciBen . '-' . $complementoBen;
            }

            $idTipoAsegurado = $personaBen->id_tipo_asegurado;

            $tipoAsegurado = TipoAsegurado::find($idTipoAsegurado);
            $tipoBeneficiario = $tipoAsegurado->tipo_asegurado;

            if ($tipoBeneficiario == 'BENEFICIARIO MADRE - PADRE') {
                $parentesco = 'PADRE/MADRE';
            } elseif ($tipoBeneficiario == 'BENEFICIARIO HIJO MAYOR 19 AÑOS' || $tipoBeneficiario == 'BENEFICIARIO HIJO MENOR 19 AÑOS' || $tipoBeneficiario == 'BENEFICIARIO BEBE RECIÉN NACIDO') {
                $parentesco = 'HIJA/HIJO';
            } elseif ($tipoBeneficiario == 'BENEFICIARIO ESPOSA - ESPOSO') {
                $parentesco = 'ESPOSA/ESPOSO';
            } else {
                $parentesco = 'OTROS';
            }

            $data = [
                'codigo_entidad' => $codigoEntidad,
                'data' => [
                    [
                        'tipo_documento_titular' => 'CI',
                        'numero_documento_titular' => $carnetTitular,
                        'fecha_nacimiento_titular' => $fechaNacimientoTitular,
                        'tipo_documento' => 'CI',
                        'numero_documento' => $carnetCompleto,
                        'parentesco_titular' => $parentesco,
                    ],
                ],
            ];

            $link_servicio = LinkServicio::where('nombre_servicio', 'VALIDAR BENEFICIARIO ASUSS')->first();

            $asuss_url = $link_servicio->link_servicio;

            $tokenBd = AccesoAsussToken::orderby('id', 'desc')->first();

            if ($tokenBd) {
                $response = Http::withToken($tokenBd->token)
                    ->withoutVerifying()
                    ->post($asuss_url, $data);
                $datos = $response->json();

                if (isset($datos['error'])) {
                    $logeo = new ConexionAsuss();
                    $logueado = $logeo->login();
                    $tokenBd = AccesoAsussToken::orderby('id', 'desc')->first();
                    $response = Http::withToken($tokenBd->token)
                        ->withoutVerifying()
                        ->post($asuss_url, $data);
                    $this->respuestaValidar = $response->json();
                } else {
                    $this->respuestaValidar = $datos;
                    //dd($this->respuesta);
                }
            } else {
                $logeo = new ConexionAsuss();
                $logueado = $logeo->login();
                $tokenBd = AccesoAsussToken::orderby('id', 'desc')->first();
                $response = Http::withToken($tokenBd->token)
                    ->withoutVerifying()
                    ->post($asuss_url, $data);
                $this->respuestaValidar = $response->json();
            }
            return $this->respuestaValidar;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function insercionBeneficiarioAsuss($idTitular, $ciBen, $complementoBen, $fechaNacimientoBen, $idTipoAsegurado, $fechaAfiliacion)
    {
        $titular = Titular::find($idTitular);
        $idPersona = $titular->id_persona;
        $persona = Persona::find($idPersona);
        
        $ciTitular = $persona->ci;
        $complementoTitular = $persona->complemento;
        if ($complementoTitular == null) {
            $carnetTitular = $ciTitular;
        } else {
            $carnetTitular = $ciTitular . '-' . $complementoTitular;
        }
        $fechaNacimientoTitular = Carbon::parse($persona->fecha_nacimiento)->format('Y-m-d');

        $titularInstitucion = TitularInstitucion::where('id_titular', $idTitular)->first();
        
        if ($titularInstitucion == null) {
            $codigoEntidad = 'PER_VOL-0002';
        } else {
            $idInstitucion = $titularInstitucion->id_institucion;
            $institucion = Institucion::find($idInstitucion);
            $nit = $institucion->nit;
            $codigoEntidad = 'NIT-' . $nit;
        }

        if ($complementoBen == null) {
            $carnetCompleto = $ciBen;
        } else {
            $carnetCompleto = $ciBen . '-' . $complementoBen;
        }

        $fechaNacimientoFormateada = Carbon::parse($fechaNacimientoBen)->format('Y-m-d');

        $tipoAsegurado = TipoAsegurado::find($idTipoAsegurado);
        $tipoBeneficiario = $tipoAsegurado->tipo_asegurado;

        if ($tipoBeneficiario == 'BENEFICIARIO MADRE - PADRE') {
            $parentesco = 'PADRE/MADRE';
        } elseif ($tipoBeneficiario == 'BENEFICIARIO HIJO MAYOR 19 AÑOS' || $tipoBeneficiario == 'BENEFICIARIO HIJO MENOR 19 AÑOS' || $tipoBeneficiario == 'BENEFICIARIO BEBE RECIÉN NACIDO') {
            $parentesco = 'HIJA/HIJO';
        } elseif ($tipoBeneficiario == 'BENEFICIARIO ESPOSA - ESPOSO') {
            $parentesco = 'ESPOSA/ESPOSO';
        } else {
            $parentesco = 'OTROS';
        }
        //dd($fechaAfiliacion);
        if (!empty($fechaAfiliacion)) {
            $fechaAfiliacionFormateada = Carbon::createFromFormat('d/m/Y', $fechaAfiliacion)->format('Y-m-d');

        } 

        //$fechaAfiliacionFormateada = Carbon::parse($fechaAfiliacion)->format('Y-m-d');
        
        $data = [
            'codigo_entidad' => $codigoEntidad,
            'data' => [
                [
                    'numero_documento_titular' => $carnetTitular,
                    'tipo_documento_titular' => 'CI',
                    'fecha_nacimiento_titular' => $fechaNacimientoTitular,
                    'tipo_documento' => 'CI',
                    'numero_documento' => $carnetCompleto,
                    'fecha_nacimiento' => $fechaNacimientoFormateada,
                    'parentesco_titular' => $parentesco,
                    'fecha_inicio' => $fechaAfiliacionFormateada,
                ],
            ],
        ];
        //dd($data);

        $link_servicio = LinkServicio::where('nombre_servicio', 'INSERCION BENEFICIARIO ASUSS')->first();

        $asuss_url = $link_servicio->link_servicio;

        $tokenBd = AccesoAsussToken::orderby('id', 'desc')->first();

        if ($tokenBd) {
            $response = Http::withToken($tokenBd->token)
                ->withoutVerifying()
                ->post($asuss_url, $data);
            // Aqui ya se registra  la inyeccion sin  errores
            $datos = $response->json();

            if (isset($datos['error'])) {
                $logeo = new ConexionAsuss();
                $logueado = $logeo->login();
                $tokenBd = AccesoAsussToken::orderby('id', 'desc')->first();
                $response = Http::withToken($tokenBd->token)
                    ->withoutVerifying()
                    ->post($asuss_url, $data);

                // Aqui muestra el tipo de  errores
                $this->respuesta = $response->json();

            } else {
                // Aqui ya se envia  los datos sin  errores

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
        }
        return $this->respuesta;
    }

    public function bajaBeneficiarioAsuss($idBeneficiario, $fechaFin, $fechaFinCesantia, $tipoMotivo, $descripcionMotivo)
    {
        $beneficiario = Beneficiario::find($idBeneficiario);
        $personaBeneficiario = Persona::find($beneficiario->id_persona);
        $titular = Titular::find($beneficiario->id_titular);
        $personaTitular = Persona::find($titular->id_persona);

        $ciTitular = $personaTitular->ci;
        $complementoTitular = $personaTitular->complemento;
        if ($complementoTitular == null) {
            $numero_documento_titular = $ciTitular;
        } else {
            $numero_documento_titular = $ciTitular . '-' . $complementoTitular;
        }

        $fecha_nacimiento_titular = Carbon::parse()->format('Y-m-d');

        $fecha_fin = Carbon::parse($fechaFin)->format('Y-m-d');
        $fecha_fin_cesantia = Carbon::parse($fechaFinCesantia)->format('Y-m-d');

        $ciBeneficiario = $personaBeneficiario->ci;
        $complementoBeneficiario = $personaBeneficiario->complemento;
        if ($complementoBeneficiario == null) {
            $numero_documento = $ciBeneficiario;
        } else {
            $numero_documento = $ciBeneficiario . '-' . $complementoBeneficiario;
        }

        $fecha_nacimiento = Carbon::parse($personaBeneficiario->fecha_nacimiento)->format('Y-m-d');

        if ($titular->tipo_titular == true) {
            $titularInstitucion = TitularInstitucion::where('id_titular', $beneficiario->id_titular)->first();

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

        $data = [
            'tipo_documento_titular' => 'CI',
            'numero_documento_titular' => $numero_documento_titular,
            'fecha_nacimiento_titular' => $fecha_nacimiento_titular,
            'tipo_documento' => 'CI',
            'numero_documento' => $numero_documento,
            'fecha_nacimiento' => $fecha_nacimiento,
            'codigo_entidad' => $codigo_entidad,
            'fecha_fin' => $fecha_fin,
            'fecha_fin_cesantia' => $fecha_fin_cesantia,
            'tipo_motivo' => $tipoMotivo,
            'descripcion_motivo' => $descripcionMotivo,
        ];

        $link_servicio = LinkServicio::where('nombre_servicio', 'BAJA BENEFICIARIO ASUSS')->first();

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
        return $this->respuestaBaja;
    }
}
