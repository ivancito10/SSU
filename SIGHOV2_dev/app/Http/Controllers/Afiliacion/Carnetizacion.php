<?php

namespace App\Http\Controllers\Afiliacion;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\TitularInstitucion;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\Estudiante;
use App\Models\Administracion\PersonalSsu;
use App\Models\Administracion\Unidad;
use App\Models\Afiliacion\BeneficiarioInstitucion;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Vigencia\AutorizacionInterior;
use App\Models\Afiliacion\CodigoInstitucionSsu;
use App\Models\User;
use Carbon\Carbon;

class Carnetizacion extends Controller
{
    public static function calcularEdad($fechaNacimiento)
    {
        // Convertir la fecha de nacimiento en un objeto Carbon
        $fechaNacimiento = Carbon::parse($fechaNacimiento);

        // Calcular la edad
        return $fechaNacimiento->age;
    }
    public $carnets_colores = [
        'rojo' => 'images/carnets_final/APROBADOS/CREDENCIAL_SSU_FRONTAL_ROJO.png',
        'azul' => 'images/carnets_final/APROBADOS/CREDENCIAL_SSU_FRONTAL_AZUL_FUERTE.png',
        'plomo' => 'images/carnets_final/APROBADOS/CREDENCIAL_SSU_FRONTAL_PLOMO.png',
        'amarillo' => 'images/carnets_final/APROBADOS/CREDENCIAL_SSU_FRONTAL_AMARILLO.png',
        'verde' => 'images/carnets_final/APROBADOS/CREDENCIAL_SSU_FRONTAL_VERDE.png',
        'naranja' => 'images/carnets_final/APROBADOS/CREDENCIAL_SSU_FRONTAL_NARANJA.png',
        'celeste' => 'images/carnets_final/APROBADOS/CREDENCIAL_SSU_FRONTAL_CELESTE.png',
    ];

    public function carnet_titular_old($id)
    {
        $ruta_carnet = 'images/carnets_final/APROBADOS/rojo.png';
        $id_titular = $id;
        $titular = Titular::where('id', $id_titular)
            ->with([
                'titularPersona.personaHCarnet',
                'titularResidencia.zonaResidencia',
                'titularPersona.departamentoPersona',
                'titularPersona.tipoAseguradoPersona',
                'titularConTitularInstitucion.titularInstitucionConInstitucion.claseAportanteInstitucion'
            ])->first();
        // $fecha_nacimiento = $titular->titularPersona->fecha_nacimiento;
        // $edad=$this->calcularEdad($fecha_nacimiento);
        // return response()->json($edad, 200);
        // return response()->json($titular, 200);
        // dd($titular->titularConTitularInstitucion);
        // dd($titular);
        $fecha_vencimiento = "INDEFINIDO";
        //  dd( $titular->permanente);
        //CAMBIOS 08/12/2024 
        // $tipo_asegurado='';
        if ($titular->permanente) {
            $tipo_asegurado = $titular->titularPersona->tipoAseguradoPersona->tipo_asegurado . " PERMANENTE";
        }
        // if ($titular->permanente == false || $titular->permanente == null) {

        $carnets_titular = $titular->titularPersona->personaHCarnet;
        // dd($carnets_titular); 
        $ultimo_carnet = count($carnets_titular);
        // dd($carnets_titular);
        if (count($carnets_titular) != 0) {
            // dd($carnets_titular);
            // return response()->json('tiene carnets', 200);
            $indefinido = $carnets_titular[$ultimo_carnet - 1]->indefinido;
            // return response()->json($indefinido, 200);

            $fecha_vencimiento = $carnets_titular[$ultimo_carnet - 1]->fecha_vencimiento;
            $fecha_vencimiento = date("d-m-Y", strtotime($fecha_vencimiento));
            if (!$indefinido && !$titular->permanente) {

                // $fecha_vencimiento = "INDEFINIDO";
                $tipo_asegurado = "TITULAR EVENTUAL";
                $ruta_carnet = 'images/carnets_final/azul.png';
            } else {
                if (!$titular->permanente) {
                    // $tipo_asegurado = $titular->titularPersona->tipoAseguradoPersona->tipo_asegurado . " EVENTUAL";
                    // $ruta_carnet = 'images/carnets_final/azul.png';
                    $fecha_vencimiento = "INDEFINIDO";
                }
            }
            // Convierte la fecha en formato 'día mes año'
            // return response()->json($carnets_titular[0]->fecha_vencimiento,200);
        }



        // }
        // return response()->json($titular,200);

        // $fecha_venicimiento=
        // TODO: sigla de carnet opcional
        $ci = $titular->titularPersona->ci;
        $complemento = $titular->titularPersona->complemento;
        $ci_expedido = $titular->titularPersona?->departamentoPersona?->sigla ?? '';
        $ci .= $complemento ? '-' . $complemento : '';
        $ci .= $ci_expedido ? ' ' . $ci_expedido : '';


        // return response()->json($titular->titularConTitularInstitucion[0]->id_institucion, 200);

        $tipo_afiliado = "Titular";
        $clave_unica = $titular->titularPersona->clave_unica;
        $nombres = mb_strtolower($titular->titularPersona->nombres);
        $p_apellido = mb_strtolower($titular->titularPersona->p_apellido);
        $s_apellido = mb_strtolower($titular->titularPersona->s_apellido);
        $matricula_seguro = $titular->titularPersona->matricula_seguro;
        $id_tipo_asegurado = $titular->titularPersona->id_tipo_asegurado;
        $afiliado = $titular->titularPersona->afiliado;
        $sigla_institucion = "VOLUNTARIO";

        $sigla_institucion_transferencia = [];
        if (count($titular->titularConTitularInstitucion) != 0) {

            $intituciones = $titular->titularConTitularInstitucion;
            $sigla_institucion = $intituciones[0]->titularInstitucionConInstitucion->nombre;

            foreach ($intituciones as $key => $value) {
                // Extraer el acrónimo desde la estructura de objetos
                $acronimo = $value->titularInstitucionConInstitucion->acronimo ?? null;

                if ($acronimo !== null) { // Verificar que exista un acrónimo
                    $sigla_institucion_transferencia[] = $acronimo; // Añadir al array
                }
            }
            $sigla_institucion_transferencia = array_slice($sigla_institucion_transferencia, 1);
        }
        $nombre_foto_afiliado = $titular->foto_nombre;
        $foto_afiliado = null;
        $foto_afiliado = Carnetizacion::obtenerImagenBase64('Afiliacion/FOTOS/', $nombre_foto_afiliado);
        if (count($titular->titularConTitularInstitucion) > 0) {
            $id_institucion = $titular->titularConTitularInstitucion[0]->id_institucion;
            $codigo = CodigoInstitucionSsu::where('id_institucion', $id_institucion)->first();
            if ($codigo) {
                $matricula_seguro = $matricula_seguro . ' - ' . $codigo->codigo . $codigo->tipo_titular;
                // $this->codigoBeneficiario = $codigo->codigo . $codigo->tipo_beneficiario;
            }
            // titularConTitularInstitucion.titularInstitucionConInstitucion.claseAportanteInstitucion
            // ClaseAportante::where('id_')
        }

        // ! PARA COLOCAR EL CARNET EN PLATEADO SI EL TITULAR ES RENTISTAS POR SOTEO
        // return response()->json($titular, 200);
        if (count($titular->titularConTitularInstitucion) > 0) {
            // return response()->json($titular->titularConTitularInstitucion[0]->tipo_institucion, 200);
            // return response()->json($titular->titularConTitularInstitucion[0]->tipo_institucion, 200);
            if ($titular->titularConTitularInstitucion[0]->tipo_institucion === "RENTISTA POR SORTEO") {
                // return response()->json("es plomo bro", 200);
                $tipo_asegurado = "TITULAR RENTISTA POR SORTEO";
                $ruta_carnet = 'images/carnets_final/plateado.png';
            }
            if ($titular->titularConTitularInstitucion[0]->tipo_institucion === "RENTISTA JUBILADO") {
                // $ruta_carnet = 'images/carnets_final/plateado.png';
                $tipo_asegurado = "TITULAR RENTISTA JUBILADO ";
                // COMENTE ESTO 27/01/2025 $fecha_vencimiento = "INDEFINIDO";
            }
        }
        if ($titular->tipo_rentista != null) {
            // return response()->json("es plomo bro y aqui te estoy cambiando", 200);

            if ($titular->tipo_rentista === "VIUDEDAD") {
                $ruta_carnet = 'images/carnets_final/rojo.png';
                $tipo_asegurado = "TITULAR RENTISTA POR VIUDEDAD";
            }
            if ($titular->tipo_rentista === "ORFANDAD") {
                $ruta_carnet = 'images/carnets_final/rojo.png';
                $tipo_asegurado = "TITULAR RENTISTA POR ORFANDAD";
                $fecha_nacimiento = $titular->titularPersona->fecha_nacimiento;
                $edad = $this->calcularEdad($fecha_nacimiento);
                if ($edad >= 19) {
                    $ruta_carnet = 'images/carnets_final/azul.png';
                }
            }
        }
        // return response()->json($foto_afiliado);
        // *    DATOS DEL GERENTE
        $datos_gerente_general = Carnetizacion::datos_gerente();
        $nombre_gral = $datos_gerente_general->getData()->nombre_completo;
        $nombre_gral = mb_strtolower($nombre_gral);
        $foto_firma_gral = $datos_gerente_general->getData()->foto_firma;
        $interino_gral = $datos_gerente_general->getData()->interino;
        $sigla_mencion_gral = $datos_gerente_general->getData()->sigla_mencion;


        $direccion = $titular->titularResidencia->direccion;
        $nro_vivienda = $titular->titularResidencia->nro_vivienda;
        $zona_residencia = $titular->titularResidencia->zonaResidencia->nombre;
        $domicilio = $direccion . ' Nro. ' . $nro_vivienda . ' Zona: ' . $zona_residencia;
        $grupo_sanguineo = $titular->titularPersona->grupo_sanguineo;
        if ($grupo_sanguineo == null) {
            $grupo_sanguineo = "Sin grupo";
        }
        $alergias = $titular->titularPersona->alergias;
        if ($alergias == null) {
            $alergias = "NINGUNO";
        }
        $telefono_referencia = $titular->telefono_referencia;
        $nombre_referencia = $titular->nombre_referencia;
        $fecha_afiliacion = $titular->fecha_afiliacion;
        $telefono = $titular->telefono;
        $mensajeQR = $clave_unica;
        $qrCode = base64_encode(QrCode::format('svg')
            ->size(300)
            ->color(
                3,
                125,
                155
            )
            ->margin(1) // Margen adicional
            ->backgroundColor(255, 255, 255)
            ->errorCorrection('H')
            ->generate($mensajeQR));


        // $qrCode = base64_encode(QrCode::format('svg')
        // ->size(300) // Aumenta el tamaño
        // ->color(0, 0, 0) // Negro (contraste máximo)
        // ->backgroundColor(255, 255, 255) // Blanco (contraste máximo)
        // ->margin(10) // Margen adicional
        // ->errorCorrection('H') // Máxima corrección de errores
        // ->generate($mensajeQR));
        // dd($fecha_vencimiento);

        $vista_final = view('Afiliacion.Titulares.carnetTitularPdf_2', compact(
            'ruta_carnet',
            'ci',
            'complemento',
            'nombres',
            'p_apellido',
            's_apellido',
            'matricula_seguro',
            'tipo_afiliado',
            'tipo_asegurado',
            'afiliado',
            // 'nombre_institucion',
            'sigla_institucion',
            'nombre_gral',
            'foto_firma_gral',
            'interino_gral',
            'sigla_mencion_gral',
            'foto_afiliado',
            'sigla_institucion_transferencia',
            'fecha_vencimiento',
            'grupo_sanguineo',
            'alergias',
            'telefono_referencia',
            'nombre_referencia',
            'fecha_afiliacion',
            'telefono',
            'qrCode',
            'domicilio'
        ));
        // Cargar el HTML combinado en el PDF
        // $pdf = Pdf::loadHTML($html);
        $pdf = Pdf::loadHTML($vista_final);


        // Configura el tamaño y la orientación del papel
        $pdf->setPaper([0, 0, 15.88 * 28.35, 10.05 * 28.35], 'portrait'); // Las dimensiones deben estar en puntos (1 cm = 28.35 puntos)

        // Renderizar en el navegador
        return $pdf->stream('pdf_carnet_titular.pdf');
    }
    public function ultimoCarnet_indefinido($carnets)
    {
        // $indefinido=false;
        $ultimo_carnet = null;
        $nro_carnest = count($carnets);
        if ($nro_carnest != 0) {
            $ultimo_carnet = $carnets[$nro_carnest - 1];
        }
        return $ultimo_carnet;
    }
    public function carnet_titular_pdf($id)
    {
        set_time_limit(24000);
        //dd('esta es  la funcion que se usa');
        $id_titular = $id;
        $ruta_carnet = $this->carnets_colores['rojo'];
        $fecha_vencimiento = "INDEFINIDO";
        $titular = Titular::where('id', $id_titular)
            ->with([
                'titularPersona.personaHCarnet',
                'titularResidencia.zonaResidencia',
                'titularPersona.departamentoPersona',
                'titularPersona.tipoAseguradoPersona',
                'titularConTitularInstitucion.titularInstitucionConInstitucion.claseAportanteInstitucion'
            ])
            ->first();
        $user = User::where('id_persona', $titular->id_persona)->first();
        //dd($user);
        //cambio de usuario personas trabajadoras del seguro 
        $this->actualizarUsuarioSSU($user->id);
        //    
        $usuario = User::where('id_persona', $titular->id_persona)->first();
        // dd($usuario);
        $ci = $titular->titularPersona->ci;
        $complemento = $titular->titularPersona->complemento;

        $departamento = $titular->titularPersona?->departamentoPersona;
        $ci_expedido = '';
        if ($departamento && $departamento->id != 10) {
            $ci_expedido = $departamento->sigla ?? '';
        }

        $ci .= $complemento ? '-' . $complemento : '';
        $ci .= $ci_expedido ? ' ' . $ci_expedido : '';

        //   $ci_expedido = $titular->titularPersona?->departamentoPersona?->sigla ?? '';
        //   $ci .= $complemento ? '-' . $complemento : '';
        //   $ci .= $ci_expedido ? ' ' . $ci_expedido : '';

        //dd($ci);
        $tipo_asegurado = "TITULAR PERMANENTE";
        $fecha_vencimiento = "INDEFINIDO";
        //sacar ultimo carnet de titular;
        $carnets_titular = $titular->titularPersona->personaHCarnet;
        $ultimo_carnet = $this->ultimoCarnet_indefinido($carnets_titular);
        if ($ultimo_carnet != null) {
            $fecha_vencimiento = $ultimo_carnet->fecha_vencimiento;
            $fecha_vencimiento = Carbon::parse($fecha_vencimiento)->format("d-m-Y");
        }
        if ($titular->tipo_rentista === 'TITULAR-RENTISTA') {
            $tipo_asegurado = "TITULAR RENTISTA JUBILADO ";
            $fecha_vencimiento = "INDEFINIDO";
        }
        if ($titular->tipo_rentista === 'TITULAR-SORTEO') {
            $tipo_asegurado = "TITULAR RENTISTA POR SORTEO ";
            $ruta_carnet = $this->carnets_colores['plomo'];
        }
        if ($titular->tipo_rentista === 'VIUDEDAD') {
            $tipo_asegurado = "TITULAR RENTISTA POR VIUDEDAD";
            // $this->carnets_colores['rojo'];
        }
        if ($titular->tipo_rentista === 'ORFANDAD') {
            $tipo_asegurado = "TITULAR RENTISTA POR ORFANDAD";
            $fecha_nacimiento = $titular->titularPersona->fecha_nacimiento;
            $edad = $this->calcularEdad($fecha_nacimiento);
            if ($edad >= 19) {
                $ruta_carnet = $this->carnets_colores['rojo'];
            }
        }

        if (!$titular->tipo_titular) {
            if ($titular->tipo_rentista == null) {

                if (!$titular->permanente) {
                    $ruta_carnet = $this->carnets_colores['azul'];
                    $tipo_asegurado = "TITULAR VOLUNTARIO ";
                }
            }
        } else {
            if ($titular->tipo_rentista == null) {
                $ruta_carnet = $this->carnets_colores['azul'];
                $tipo_asegurado = "TITULAR EVENTUAL";
                if ($titular->permanente) {
                    $tipo_asegurado = "TITULAR PERMANENTE ";
                    $ruta_carnet = $this->carnets_colores['rojo'];
                }
            }
        }
        //!LOGICA PARA OTROS DATOS:
        $clave_unica = $titular->titularPersona->clave_unica;
        $nombres = mb_strtolower($titular->titularPersona->nombres);
        $p_apellido = mb_strtolower($titular->titularPersona->p_apellido);
        $s_apellido = mb_strtolower($titular->titularPersona->s_apellido);
        $matricula_seguro = $titular->titularPersona->matricula_seguro;
        $sigla_institucion = "VOLUNTARIO";

        $sigla_institucion_transferencia = [];
        if (count($titular->titularConTitularInstitucion) != 0) {
            // $intituciones = $titular->titularConTitularInstitucion;
            $intituciones =  TitularInstitucion::where('id_titular', $id_titular)
                ->where('estado', true)
                ->orderBy('id', 'asc')
                ->get();
            $sigla_institucion = $intituciones[0]->titularInstitucionConInstitucion->nombre;

            foreach ($intituciones as $key => $value) {
                // Extraer el acrónimo desde la estructura de objetos
                $acronimo = $value->titularInstitucionConInstitucion->acronimo ?? null;

                if ($acronimo !== null) { // Verificar que exista un acrónimo
                    $sigla_institucion_transferencia[] = $acronimo; // Añadir al array
                }
            }
            $sigla_institucion_transferencia = array_slice($sigla_institucion_transferencia, 1);
        }
        $nombre_foto_afiliado = $titular->foto_nombre;
        $foto_afiliado = null;
        $foto_afiliado = Carnetizacion::obtenerImagenBase64('Afiliacion/FOTOS/', $nombre_foto_afiliado);
        if (count($titular->titularConTitularInstitucion) > 0) {
            $intituciones =  TitularInstitucion::where('id_titular', $id_titular)
                ->where('estado', true)
                ->orderBy('id', 'asc')
                ->first();

            $id_institucion = $intituciones->id_institucion;
            // dd('asdzxcxsa', $id_institucion);
            $codigo = CodigoInstitucionSsu::where('id_institucion', $id_institucion)->first();
            /*  if ($codigo) {
                $matricula_seguro = $matricula_seguro . ' - ' . $codigo->codigo . $codigo->tipo_titular;
                // $this->codigoBeneficiario = $codigo->codigo . $codigo->tipo_beneficiario;
            } */
            // titularConTitularInstitucion.titularInstitucionConInstitucion.claseAportanteInstitucion
            // ClaseAportante::where('id_')
        }
        // *    DATOS DEL GERENTE
        $datos_gerente_general = Carnetizacion::datos_gerente();
        $nombre_gral = $datos_gerente_general->getData()->nombre_completo;
        $nombre_gral = mb_strtolower($nombre_gral);
        $foto_firma_gral = $datos_gerente_general->getData()->foto_firma;
        $interino_gral = $datos_gerente_general->getData()->interino;
        $sigla_mencion_gral = $datos_gerente_general->getData()->sigla_mencion;


        $direccion = $titular->titularResidencia->direccion;
        $nro_vivienda = $titular->titularResidencia->nro_vivienda;
        $zona_residencia = $titular->titularResidencia->zonaResidencia->nombre;
        $domicilio = $direccion . ' Nro. ' . $nro_vivienda . ' Zona: ' . $zona_residencia;
        $grupo_sanguineo = $titular->titularPersona->grupo_sanguineo;
        if ($grupo_sanguineo == null) {
            $grupo_sanguineo = "Sin grupo";
        }
        $alergias = $titular->titularPersona->alergias;
        if ($alergias == null) {
            $alergias = "NINGUNO";
        }
        $telefono_referencia = $titular->telefono_referencia;
        $nombre_referencia = $titular->nombre_referencia;
        $fecha_afiliacion = $titular->fecha_afiliacion;
        $telefono = $titular->telefono;
        $mensajeQR = $clave_unica;
        $qrCode = base64_encode(QrCode::format('svg')
            ->size(300)
            ->color(
                3,
                125,
                155
            )
            ->margin(1) // Margen adicional
            ->backgroundColor(255, 255, 255)
            ->errorCorrection('H')
            ->generate($mensajeQR));

        $vista_final = view('Afiliacion.Titulares.carnetTitularPdf_2', compact(
            'ruta_carnet',
            'ci',
            'complemento',
            'nombres',
            'p_apellido',
            's_apellido',
            'matricula_seguro',
            'tipo_asegurado',
            // 'nombre_institucion',
            'sigla_institucion',
            'nombre_gral',
            'foto_firma_gral',
            'interino_gral',
            'sigla_mencion_gral',
            'foto_afiliado',
            'sigla_institucion_transferencia',
            'fecha_vencimiento',
            'grupo_sanguineo',
            'alergias',
            'telefono_referencia',
            'nombre_referencia',
            'fecha_afiliacion',
            'telefono',
            'qrCode',
            'domicilio',
            'usuario'
        ));
        // Cargar el HTML combinado en el PDF
        // $pdf = Pdf::loadHTML($html);
        $pdf = Pdf::loadHTML($vista_final);


        // Configura el tamaño y la orientación del papel
        $pdf->setPaper([0, 0, 15.88 * 28.35, 10.05 * 28.35], 'portrait'); // Las dimensiones deben estar en puntos (1 cm = 28.35 puntos)

        // Renderizar en el navegador
        return $pdf->stream('pdf_carnet_titular.pdf');
    }
    public static function carnet_beneficiario_pdf($id)
    {
        //dd('esyta es la funcion qyue si entra');
        $ruta_carnet = 'images/carnets_final/APROBADOS/CREDENCIAL_SSU_FRONTAL_AMARILLO.png';

        $beneficiario = Beneficiario::where('id', $id)->with([
            'titularBeneficiario.titularPersona',
            'titularBeneficiario.titularConTitularInstitucion.titularInstitucionConInstitucion',
            'beneficiarioPersona.personaHCarnet',
            'beneficiarioResidencia.zonaResidencia',
            'beneficiarioPersona.departamentoPersona',
            'beneficiarioPersona.tipoAseguradoPersona',
        ])->first();
        $usuario = User::where('id_persona', $beneficiario->id_persona)->first();
        // return response()->json($beneficiario->titularBeneficiario->permanente, 200);
        $fecha_vencimiento = "INDEFINIDO";
        $tipo_asegurado = $beneficiario->beneficiarioPersona->tipoAseguradoPersona->tipo_asegurado /* . " PERMANENTE" */;
        // if ($beneficiario->titularBeneficiario->permanente) {
        //     $ruta_carnet = 'images/carnets_final/amarillo.png';
        // }
        // if ($beneficiario->permanente == false || $beneficiario->permanente == null) {

        $carnets_beneficiario = $beneficiario->beneficiarioPersona->personaHCarnet;

        $ultimo_carnet = count($carnets_beneficiario);
        if (count($carnets_beneficiario) != 0) {
            $indefinido = $carnets_beneficiario[$ultimo_carnet - 1]->indefinido;
            // return response()->json($carnets_beneficiario[$ultimo_carnet - 1], 200);
            $fecha_vencimiento = $carnets_beneficiario[$ultimo_carnet - 1]->fecha_vencimiento;
            $fecha_vencimiento = date("d-m-Y", strtotime($fecha_vencimiento));
            if (!$beneficiario->titularBeneficiario->permanente) {
                // dd('auqi');
                $ruta_carnet = 'images/carnets_final/APROBADOS/CREDENCIAL_SSU_FRONTAL_AZUL_FUERTE_BENEFICIARIOS.png';
            }

            // if ($beneficiario->permanente) {
            //     $ruta_carnet = 'images/carnets_final/amarillo.png';
            // }

            //TODO: CASO EN EL QUE EL BENEFICIARIO ES MAYOR A 19 AÑOS
            // if ($tipo_asegurado == "BENEFICIARIO HIJO MAYOR 19 AÑOS") {
            //     $ruta_carnet = 'images/carnets_final/azul.png';
            //     $fecha_vencimiento = date("d-m-Y", strtotime($fecha_vencimiento));
            // }
            // Convierte la fecha en formato 'día mes año'
            // $fecha_vencimiento = date("d-m-Y", strtotime($fecha_vencimiento));
            // return response()->json($carnets_beneficiario[0]->fecha_vencimiento,200);
        }
        // }
        // return response()->json($beneficiario);
        $clave_unica = $beneficiario->beneficiarioPersona->clave_unica;
        $ci = $beneficiario->beneficiarioPersona->ci;
        $complemento = $beneficiario->beneficiarioPersona->complemento;

        $departamento = $beneficiario->beneficiarioPersona?->departamentoPersona;
        $ci_expedido = '';
        if ($departamento && $departamento->id != 10) {
            $ci_expedido = $departamento->sigla ?? '';
        }

        $ci .= $complemento ? '-' . $complemento : '';
        $ci .= $ci_expedido ? ' ' . $ci_expedido : '';

        $nombres = $beneficiario->beneficiarioPersona->nombres;
        $p_apellido = $beneficiario->beneficiarioPersona->p_apellido;
        $s_apellido = $beneficiario->beneficiarioPersona->s_apellido;
        $matricula_seguro = $beneficiario->beneficiarioPersona->matricula_seguro;
        $sigla_institucion = "VOLUNTARIO";
        $sigla_institucion_transferencia = [];

        $nombre_titular = $beneficiario->titularBeneficiario->titularPersona->nombres . ' ' . $beneficiario->titularBeneficiario->titularPersona->p_apellido . ' ' . $beneficiario->titularBeneficiario->titularPersona->s_apellido;
        $matricula_titular = $beneficiario->titularBeneficiario->titularPersona->matricula_seguro;
        // return response()->json($beneficiario->titularBeneficiario->titularConTitularInstitucion);
        if (count($beneficiario->titularBeneficiario->titularConTitularInstitucion) != 0) {

            if (count($beneficiario->titularBeneficiario->titularConTitularInstitucion) != 0) {
                $id_titular = $beneficiario->titularBeneficiario->id;
                $institucionTitular = TitularInstitucion::where('id_titular', $id_titular)
                    ->orderBy('id', 'asc')
                    ->where('estado', true)
                    ->first();
                $sigla_institucion = $institucionTitular->titularInstitucionConInstitucion->nombre;
            }
        }
        // para ver si es beneficiario con remuneracion y tiene transferencia de paortes
        $beneficiario_remuneracion = BeneficiarioInstitucion::where('id_beneficiario', $id)->first();
        if ($beneficiario_remuneracion == ! null) {
            $sigla_institucion_transferencia = $beneficiario_remuneracion->institucionBeneficiarioInstitucion->acronimo;
        }
        // return response()->json($beneficiario->titularBeneficiario, 200);
        if (count($beneficiario->titularBeneficiario->titularConTitularInstitucion) != 0) {
            $id_institucion = $beneficiario
                ->titularBeneficiario
                ->titularConTitularInstitucion
                ->firstWhere('estado', true)
                ->id_institucion ?? null;
            // dd($beneficiario,$id_institucion);
            $codigo = CodigoInstitucionSsu::where('id_institucion', $id_institucion)->first();
            /* if ($codigo) {
                $matricula_seguro = $matricula_seguro . '-' . $codigo->codigo . $codigo->tipo_beneficiario;
            } */
        }
        // $this->codigoBeneficiario = $codigo->codigo . $codigo->tipo_beneficiario;
        // return response()->json([$sigla_institucion,$sigla_institucion_transferencia],200);

        // ! PARA COLOCAR EL CARNET EN PLATEADO SI EL TITULAR ES RENTISTAS POR SOTEO
        // return response()->json($titular, 200);
        if (count($beneficiario->titularBeneficiario->titularConTitularInstitucion) > 0) {
            // dd('sdfsd');
            if ($beneficiario->titularBeneficiario->tipo_rentista === "TITULAR-SORTEO") {

                // dd('asdasddsdss');
                $ruta_carnet = 'images/carnets_final/APROBADOS/CREDENCIAL_SSU_FRONTAL_PLOMO_BENEFICIARIO.png';
                $tipo_asegurado = $beneficiario->beneficiarioPersona->tipoAseguradoPersona->tipo_asegurado /* ." - RENTISTA" */;
            }
            //COMENTADO 21/08/2025
            // if ($beneficiario->titularBeneficiario->titularConTitularInstitucion[0]->tipo_institucion === "RENTISTA POR SORTEO") {
            //    //  dd('entre');
            //     $ruta_carnet = 'images/carnets_final/APROBADOS/CREDENCIAL_SSU_FRONTAL_PLOMO_BENEFICIARIO.png';
            //     $tipo_asegurado = $beneficiario->beneficiarioPersona->tipoAseguradoPersona->tipo_asegurado /* ." - RENTISTA" */;
            //     // $tipo_asegurado = "TITULAR RENTISTA";
            // }
            elseif ($beneficiario->titularBeneficiario->titularConTitularInstitucion[0]->tipo_institucion === "RENTISTA JUBILADO" && ($tipo_asegurado != "BENEFICIARIO HIJO MAYOR 19 AÑOS")) {
                //dd('asds');
                $ruta_carnet = 'images/carnets_final/APROBADOS/CREDENCIAL_SSU_FRONTAL_AMARILLO.png';
                $tipo_asegurado = $beneficiario->beneficiarioPersona->tipoAseguradoPersona->tipo_asegurado /* . " - JUBILADO" */;
            } elseif ($tipo_asegurado == "BENEFICIARIO HIJO MAYOR 19 AÑOS") {
                // dd('eres mayor de 19');
                $ruta_carnet = 'images/carnets_final/APROBADOS/CREDENCIAL_SSU_FRONTAL_AZUL_FUERTE_BENEFICIARIOS.png';
            }
        }


        $nombre_foto_afiliado = $beneficiario->foto_nombre;
        $foto_afiliado = null;
        // $foto_afiliado = 'data:image/png;base64,' . $foto_afiliado;
        $foto_afiliado = Carnetizacion::obtenerImagenBase64('Afiliacion/FOTOS/', $nombre_foto_afiliado);

        // $sexo = $beneficiario->beneficiarioPersona->sexo;
        // $fecha_nacimiento = $beneficiario->beneficiarioPersona->fecha_nacimiento;
        // $fecha_afiliacion = $beneficiario->fecha_afiliacion;

        // $telefono = $beneficiario->titularBeneficiario->telefono;
        $alergias = $beneficiario->beneficiarioPersona->alergias;
        if ($alergias == null) {
            $alergias = "NINGUNO";
        }
        $grupo_sanguineo = $beneficiario->beneficiarioPersona->grupo_sanguineo;
        if ($grupo_sanguineo == null) {
            $grupo_sanguineo = "Sin grupo";
        }
        $direccion = $beneficiario->beneficiarioResidencia->direccion;
        $nro_vivienda = $beneficiario->beneficiarioResidencia->nro_vivienda;
        $zona_residencia = $beneficiario->beneficiarioResidencia->zonaResidencia->nombre;
        $domicilio = $direccion . ' Nro. ' . $nro_vivienda . ' Zona: ' . $zona_residencia;
        // return response()->json([
        //     $direccion,
        //     $nro_vivienda,
        //     $zona_residencia,
        // ]);
        $telefono_referencia = $beneficiario->telefono_referencia;
        $nombre_referencia = $beneficiario->nombre_referencia;
        $fecha_afiliacion = $beneficiario->fecha_afiliacion;
        $telefono = $beneficiario->telefono;
        $mensajeQR = $clave_unica;
        $qrCode = base64_encode(QrCode::format('svg')
            ->size(300)
            ->color(
                3,
                125,
                155
            )
            ->margin(1) // Margen adicional
            ->backgroundColor(255, 255, 255)
            ->errorCorrection('H')
            ->generate($mensajeQR));
        // *    DATOS DEL GERENTE
        $datos_gerente_general = Carnetizacion::datos_gerente();
        $nombre_gral = $datos_gerente_general->getData()->nombre_completo;
        $nombre_gral = mb_strtolower($nombre_gral);
        $foto_firma_gral = $datos_gerente_general->getData()->foto_firma;
        $interino_gral = $datos_gerente_general->getData()->interino;
        $sigla_mencion_gral = $datos_gerente_general->getData()->sigla_mencion;
        $vista_final = view('Afiliacion.Beneficiarios.carnetBeneficiarioPdf_2', compact(
            'ruta_carnet',
            'ci',
            'complemento',
            'nombres',
            'p_apellido',
            's_apellido',
            'matricula_seguro',
            'sigla_institucion',
            'sigla_institucion_transferencia',
            'fecha_vencimiento',
            'telefono',
            'alergias',
            'grupo_sanguineo',
            'tipo_asegurado',
            'nombre_gral',
            'foto_firma_gral',
            'interino_gral',
            'sigla_mencion_gral',
            'foto_afiliado',
            'domicilio',
            'telefono_referencia',
            'nombre_referencia',
            'qrCode',
            'nombre_titular',
            'matricula_titular',
            'beneficiario_remuneracion',
            'usuario'
        ));
        // Cargar el HTML combinado en el PDF
        // $pdf = Pdf::loadHTML($html);
        $pdf = Pdf::loadHTML($vista_final); // Las dimensiones deben estar en puntos (1 cm = 28.35 puntos)

        // Configura el tamaño y la orientación del papel        
        $pdf->setPaper([0, 0, 15.88 * 28.35, 10.05 * 28.35], 'portrait'); // Las dimensiones deben estar en puntos (1 cm = 28.35 puntos)

        // Renderizar en el navegador
        return $pdf->stream('pdf_carnet_beneficiario.pdf');
    }
    public function carnet_estudiante_pdf($id)
    {
        $ruta_carnet = 'images/carnets_final/celeste.png';

        $estudiante = Estudiante::where('id', $id)
            ->with([
                'personaEstudiante.personaHCarnet',
                'personaEstudiante.departamentoPersona',
                'personaEstudiante.tipoAseguradoPersona',
                // 'personaEstudiante.residenciaPersona',
                'estudianteResidencia.zonaResidencia',
            ])
            ->orderby('id', 'desc')
            ->first();
        // if ($estudiante->permanente == false || $estudiante->permanente != null) {
        //     $ruta_carnet = 'images/carnets_final/celeste.png';
        // }
        // return response()->json($estudiante, 200);
        $sigla_institucion = "Estudiante UMSA";
        $fecha_vencimiento = "sin fecha de vencimiento";
        $carnets_estudiante = $estudiante->personaEstudiante->personaHCarnet;
        $ultimo_carnet = count($carnets_estudiante);
        if (count($carnets_estudiante) != 0) {
            $fecha_vencimiento = $carnets_estudiante[$ultimo_carnet - 1]->fecha_vencimiento;
            // Convierte la fecha en formato 'día mes año'
            $fecha_vencimiento = date("d-m-Y", strtotime($fecha_vencimiento));
            // return response()->json($carnets_titular[0]->fecha_vencimiento,200);
        }
        $ci = $estudiante->personaEstudiante->ci;
        $complemento = $estudiante->personaEstudiante->complemento;
        $ci_expedido = $estudiante->personaEstudiante->departamentoPersona->sigla;
        if ($complemento != null) {
            $ci = $ci . '-' . $complemento;
        }
        $ci = $ci . ' ' . $ci_expedido;

        $tipo_afiliado = "Estudiante";
        $clave_unica = $estudiante->personaEstudiante->clave_unica;
        $tipo_asegurado = $estudiante->personaEstudiante->tipoAseguradoPersona->tipo_asegurado;
        $nombres = mb_strtolower($estudiante->personaEstudiante->nombres);
        $p_apellido = mb_strtolower($estudiante->personaEstudiante->p_apellido);
        $s_apellido = mb_strtolower($estudiante->personaEstudiante->s_apellido);
        $matricula_seguro = $estudiante->personaEstudiante->matricula_seguro;
        $id_tipo_asegurado = $estudiante->personaEstudiante->id_tipo_asegurado;
        $afiliado = $estudiante->personaEstudiante->afiliado;
        $sigla_institucion = "Estudiante UMSA";
        $sigla_institucion_transferencia = null;

        $nombre_foto_afiliado = $estudiante->foto_nombre;
        $foto_afiliado = null;
        $foto_afiliado = Carnetizacion::obtenerImagenBase64('Afiliacion/SSUE/FOTOS/', $nombre_foto_afiliado);
        // *    DATOS DEL GERENTE
        $datos_gerente_general = Carnetizacion::datos_gerente();
        $nombre_gral = $datos_gerente_general->getData()->nombre_completo;
        $nombre_gral = mb_strtolower($nombre_gral);
        $foto_firma_gral = $datos_gerente_general->getData()->foto_firma;
        $interino_gral = $datos_gerente_general->getData()->interino;
        $sigla_mencion_gral = $datos_gerente_general->getData()->sigla_mencion;

        $domicilio = "No especificado";
        // if ($estudiante->estudianteResidencia!=null) {
        $direccion = $estudiante->estudianteResidencia->direccion;
        $nro_vivienda = $estudiante->estudianteResidencia->nro_vivienda;
        $zona_residencia = $estudiante->estudianteResidencia->zonaResidencia->nombre;
        $domicilio = $direccion . ' Nro. ' . $nro_vivienda . ' Zona: ' . $zona_residencia;
        // }
        $grupo_sanguineo = $estudiante->personaEstudiante->grupo_sanguineo;
        if ($grupo_sanguineo == null) {
            $grupo_sanguineo = "Sin grupo";
        }
        $alergias = $estudiante->alergias;
        if ($alergias == null) {
            $alergias = "NINGUNO";
        }
        $telefono_referencia = $estudiante->personaEstudiante->telefono_referencia;
        $nombre_referencia = $estudiante->nombre_referencia;
        $fecha_afiliacion = $estudiante->fecha_afiliacion;
        $telefono = $estudiante->telefono;
        $mensajeQR = $clave_unica;
        $qrCode = base64_encode(QrCode::format('svg')
            ->size(300)
            ->color(
                3,
                125,
                155
            )
            ->margin(1) // Margen adicional
            ->backgroundColor(255, 255, 255)
            ->errorCorrection('H')
            ->generate($mensajeQR));
        // return response()->json($estudiante);
        $vista_final = view('Afiliacion.Ssue.carnetEstudiantePdf_2', compact(
            'ruta_carnet',
            'ci',
            'complemento',
            'nombres',
            'p_apellido',
            's_apellido',
            'matricula_seguro',
            'tipo_afiliado',
            'tipo_asegurado',
            'afiliado',
            'sigla_institucion',
            'nombre_gral',
            'foto_firma_gral',
            'interino_gral',
            'sigla_mencion_gral',
            'foto_afiliado',
            'sigla_institucion_transferencia',
            'fecha_vencimiento',
            'grupo_sanguineo',
            'alergias',
            'telefono_referencia',
            'nombre_referencia',
            'fecha_afiliacion',
            'telefono',
            'qrCode',
            'domicilio'
        ));
        $pdf = Pdf::loadHTML($vista_final); // Las dimensiones deben estar en puntos (1 cm = 28.35 puntos)
        $pdf->setPaper([0, 0, 15.88 * 28.35, 10.05 * 28.35], 'portrait'); // Las dimensiones deben estar en puntos (1 cm = 28.35 puntos)
        return $pdf->stream('pdf_carnet_estudiante.pdf');
    }

    public static function carnet_convenio_interior_pdf($id)
    {
        $ruta_carnet = 'images/carnets_final/verde.png';

        $convenio = AutorizacionInterior::where('id', $id)
            ->with([
                'personaInteriorAutorizacionInterior.departamentoPersona',
                'seguroInteriorAutorizacionInterior',
                'hCarnetInteriorAutorizacionInterior',
            ])
            ->orderby('id', 'desc')
            ->first();
        // if ($convenio->permanente == false || $convenio->permanente == null) {
        //     $ruta_carnet = 'images/carnets_final/naranja.png';
        // }
        // return response()->json($convenio,200);
        $sigla_institucion = $convenio->seguroInteriorAutorizacionInterior->sigla_seguro;
        $fecha_vencimiento = "sin fecha de vencimiento";
        $fecha_vencimiento = $convenio->fecha_fin;
        if ($fecha_vencimiento != null) {
            $fecha_vencimiento = date("d-m-Y", strtotime($fecha_vencimiento));
        }

        $ci = $convenio->personaInteriorAutorizacionInterior->ci;
        $complemento = $convenio->personaInteriorAutorizacionInterior->complemento;
        if ($complemento != null) {
            $ci = $ci . '-' . $complemento;
        }
        if ($convenio->personaInteriorAutorizacionInterior->id_dept_exp != null) {
            $ci_expedido = $convenio->personaInteriorAutorizacionInterior->departamentoPersona->sigla;
            $ci = $ci . ' ' . $ci_expedido;
        }
        $tipo_asegurado = "TITULAR INTERIOR";
        if ($convenio->id_persona_titular != null && $convenio->id_persona_titular != $convenio->id_persona_interior) {
            $tipo_asegurado = "BENEFICIARIO INTERIOR";
            $ruta_carnet = 'images/carnets_final/naranja.png';

            // return response()->json('este choquito es beneficiario'.$convenio->id_persona_interior,200);
        }
        $clave_unica = $convenio->personaInteriorAutorizacionInterior->clave_unica;
        $nombres = mb_strtolower($convenio->personaInteriorAutorizacionInterior->nombres);
        $p_apellido = mb_strtolower($convenio->personaInteriorAutorizacionInterior->p_apellido);
        $s_apellido = mb_strtolower($convenio->personaInteriorAutorizacionInterior->s_apellido);
        $matricula_seguro = $convenio->personaInteriorAutorizacionInterior->matricula_seguro;
        $nombre_foto_afiliado = $convenio->foto_nombre;
        $foto_afiliado = null;
        // TODO: verificar la ruta de la foto del afiliado
        $foto_afiliado = Carnetizacion::obtenerImagenBase64('Vigencia/FOTOS/CONVENIO/', $nombre_foto_afiliado);


        $domicilio = "sin especificar";
        if ($convenio->direccion_referencia != null) {
            $domicilio = $convenio->direccion_referencia;
        }

        $grupo_sanguineo = $convenio->personaInteriorAutorizacionInterior->grupo_sanguineo;
        if ($grupo_sanguineo == null) {
            $grupo_sanguineo = "Sin grupo";
        }
        $alergias = $convenio->personaInteriorAutorizacionInterior->alergias;
        if ($alergias == null) {
            $alergias = "NINGUNO";
        }
        $telefono_referencia = "sin telefono de referencia";
        if ($convenio->telefono_referencia != null) {

            $telefono_referencia = $convenio->telefono_referencia;
        }
        $nombre_referencia = "sin nombre de referencia";
        if ($convenio->datos_referencia != null) {
            $nombre_referencia = $convenio->datos_referencia;
        }
        $telefono = "sin telefono";
        if ($convenio->telefono != null) {
            $telefono = $convenio->telefono;
        }
        // *    DATOS DEL GERENTE
        $datos_gerente_general = Carnetizacion::datos_gerente();
        $nombre_gral = $datos_gerente_general->getData()->nombre_completo;
        $nombre_gral = mb_strtolower($nombre_gral);
        $interino_gral = $datos_gerente_general->getData()->interino;
        $foto_firma_gral = $datos_gerente_general->getData()->foto_firma;
        $interino_gral = $datos_gerente_general->getData()->interino;
        $sigla_mencion = $datos_gerente_general->getData()->sigla_mencion;

        $mensajeQR = $clave_unica;
        $qrCode = base64_encode(QrCode::format('svg')
            ->size(300)
            ->color(
                3,
                125,
                155
            )
            ->margin(1) // Margen adicional
            ->backgroundColor(255, 255, 255)
            ->errorCorrection('H')
            ->generate($mensajeQR));
        // resources/views/Vigencia/Convenios/Interior/carnetInteriorPdf.blade.php
        $vista_final = view('Vigencia.Convenios.Interior.carnetInteriorPdf', compact(
            'ruta_carnet',
            'ci',
            'complemento',
            'nombres',
            'p_apellido',
            's_apellido',
            'matricula_seguro',
            'tipo_asegurado',
            'sigla_institucion',
            'nombre_gral',
            'foto_firma_gral',
            'interino_gral',
            'sigla_mencion',
            'foto_afiliado',
            'fecha_vencimiento',
            'qrCode',
            'domicilio',
            'grupo_sanguineo',
            'alergias',
            'telefono_referencia',
            'nombre_referencia',
            'telefono',
        ));
        $pdf = Pdf::loadHTML($vista_final); // Las dimensiones deben estar en puntos (1 cm = 28.35 puntos)
        $pdf->setPaper([0, 0, 15.88 * 28.35, 10.05 * 28.35], 'portrait'); // Las dimensiones deben estar en puntos (1 cm = 28.35 puntos)
        return $pdf->stream('pdf_carnet_estudiante.pdf');
        // return response()->json($convenio);
    }
    public static function datos_gerente()
    {
        // $datos_gerente_general = Unidad::where('nombre', 'like', 'GERENCIA GENERAL')->with(['personaPersonalSsu'])->get();
        // dd($datos_gerente_general);

        $datos_gerente_general = PersonalSsu::where('cargo', 'like', 'GERENTE GENERAL')
            ->where('estado', true)
            ->with(['personaPersonalSsu'])->first();
        // dd($datos_gerente_general);
        $nombres = $datos_gerente_general->personaPersonalSsu->nombres;
        $p_apellido = $datos_gerente_general->personaPersonalSsu->p_apellido;
        $s_apellido = $datos_gerente_general->personaPersonalSsu->s_apellido;
        $nombre_completo = $nombres . ' ' . $p_apellido . ' ' . $s_apellido;
        $id_gerente = $datos_gerente_general->id;
        $interino = $datos_gerente_general->interino;
        $nombre_firma = $id_gerente . '.png';
        $sigla_mencion = $datos_gerente_general->sigla_mencion;
        $foto_firma = Carnetizacion::obtenerImagenBase64('Administracion/Personal/Firmas/', $nombre_firma);
        return response()->json([
            'nombre_completo' => $nombre_completo,
            'id_gerente' => $id_gerente,
            'nombre_firma' => $nombre_firma,
            'foto_firma' => $foto_firma,
            'interino' => $interino,
            'sigla_mencion' => $sigla_mencion
        ], 200);
    }
    public function actualizarUsuarioSSU($id_usuario)
    {
        $response = false;
        // $id_usuario = 1;
        // titularInstitucionConInstitucion
        $usuario = User::with(['personaUser.PersonaTitular.titularConTitularInstitucion'])->find($id_usuario);

        // $id_titular=$usuario->personaUser->PersonaTitular->id;
        // $consulta = TitularInstitucion::where('id_titular', $id_titular)->orderBy('id', 'dec');

        // return $usuario;
        if (count($usuario->personaUser->PersonaTitular) != 0) {

            $titular = Titular::where('id_persona', $usuario->id_persona)
                ->whereIn('estado_vigencia', ['VIGENTE', 'CESANTE'])
                ->where('estado', true)
                ->orderBy('id', 'desc')
                ->first();
            $id_titular = $titular->id;
            $titular_institucion = TitularInstitucion::where('id_institucion', 4)
                ->where('id_titular', $id_titular)->orderBy('id', 'desc')->first();
            if ($titular_institucion) {
                $this->cambioUsuarioSSU($usuario);
                $response = $usuario->email;
            }
        }

        // return $usuario;
        return $response;
    }

    public function cambioUsuarioSSU($usuario)
    {
        $ssu = '@ssulapaz.org';

        // Si el email ya tiene "@ssulapaz.org", no hacer nada
        if (strpos($usuario->email, $ssu) !== false) {
            return;
        }

        $name_user = $this->extraerAntesDelGuion($usuario->email);
        $new_name_user = $name_user . $ssu;

        // Solo actualizar si el email realmente cambia
        if ($usuario->email !== $new_name_user) {
            $usuario->email = $new_name_user;
            $usuario->save();
        }
    }
    public function extraerAntesDelGuion($texto)
    {
        // Si la cadena ya contiene "@ssulapaz.org", la retorna sin cambios
        if (strpos($texto, '@ssulapaz.org') !== false) {
            return $texto;
        }

        // Divide el texto en dos partes usando el primer guion encontrado
        $partes = explode('-', $texto, 2);

        // Retorna la parte antes del guion
        return $partes[0];
    }

    private static function obtenerImagenBase64($ruta, $filename)
    {
        try {
            if ($filename) {
                $path = $ruta . $filename;
                $imageData = Storage::disk('ftp')->get($path);
                $fotito = base64_encode($imageData);

                return $fotito;
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            return null;
        }
    }
}
