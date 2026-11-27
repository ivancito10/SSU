<?php

namespace App\Http\Controllers\Afiliacion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DomPDF;
use App\Models\Afiliacion\CertificadoAfiliacion;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\Estudiante;
use App\Models\Afiliacion\TitularInstitucion;
use App\Models\Afiliacion\BeneficiarioInstitucion;
use App\Models\Aportes\Institucion;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

use App\Models\Administracion\Persona;
use App\Models\Administracion\Departamento;
use App\Models\Administracion\TipoAsegurado;
use App\Models\Administracion\DocumentoPDF;
use App\Models\User;
use App\Services\QRCodeService;
use PhpParser\Node\Stmt\Break_;

class PDFCertificadoAfiliacion extends Controller
{
    public function generarPDFCertificado($rowId)
    {
        // dd('generar certificado de parte de los de afilaicon');
        $afiliado = CertificadoAfiliacion::join('administracion.persona as persona', 'certificado_afiliacion.id_persona', '=', 'persona.id')
            //->join('afiliacion.titular as titular','persona.id','=','titular.id_persona')
            ->with('personaCertificadoAfiliacion.departamentoPersona')
            ->select(
                'certificado_afiliacion.nro_cite',
                'certificado_afiliacion.gestion',
                'certificado_afiliacion.fecha_emision',
                'certificado_afiliacion.fecha_caducidad',
                'certificado_afiliacion.codigo_seguridad',
                'certificado_afiliacion.cod_certificado',
                'certificado_afiliacion.nro_cite',
                'persona.ci as ci',
                'persona.id as id_persona',
                'persona.complemento as complemento',
                'persona.nombres as nombres',
                'persona.p_apellido as paterno',
                'persona.s_apellido as materno',
                'persona.id_dept_exp as dep_exp',
                'persona.fecha_nacimiento as nacimiento',
                'persona.matricula_seguro as matricula',
                'persona.id_tipo_asegurado as id_tipo_asegurado',
                //  'titular.estado_vigencia as vigencia'
            )
            ->where('persona.afiliado', true)
            //->where('titular.estado_vigencia', 'VIGENTE')
            ->where('certificado_afiliacion.id', $rowId)
            ->first();

        $fechaHoy = Carbon::now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY');
        if ($afiliado && $afiliado->fecha_emision) {
            $fechaEmision = Carbon::parse($afiliado->fecha_emision)
                ->locale('es')
                ->isoFormat('dddd, D [de] MMMM [de] YYYY');
        } else {
            $fechaEmision = 'Fecha de emisión no disponible'; // Manejo si el valor es nulo o el afiliado no se encuentra
        }

        $id_persona = $afiliado->id_persona;

        $id_tipo_asegurado = $afiliado->id_tipo_asegurado;
        $tipo = TipoAsegurado::find($id_tipo_asegurado);
        $tipo_asegurado = $tipo->tipo_asegurado;



        //Titular
        $titular = Titular::where('id_persona', $id_persona)
            ->where('estado_vigencia', 'VIGENTE')
            ->orderBy('id', 'DESC')
            ->first();

        //Beneficiario
        $beneficiario = Beneficiario::where('id_persona', $id_persona)
            ->where('vigencia', 'VIGENTE')
            ->orderBy('id', 'DESC')
            ->first();

        //Estudiante
        $estudiante = Estudiante::where('id_persona', $id_persona)
            ->where('estado_vigencia', true)
            ->orderBy('id', 'DESC')
            ->first();

        $fecha_afiliacion = 'sin fecha';
        $nombre_institucion = 'VOLUNTARIO';
        $nombre_titular = '';
        // dd($titular);
        if ($titular) {

            $fecha_afiliacion = Carbon::parse($titular->fecha_afiliacion)->locale('es')->isoFormat('D [de] MMMM [de] YYYY');
            $id_titular = $titular->id;
            $titular_insti = TitularInstitucion::where('id_titular', $id_titular)
                ->orderBy('id', 'ASC')
                ->where('estado', true)
                ->first();
            $id_institucion = null;
            if ($titular_insti) {
                $id_institucion = $titular_insti->id_institucion;
                $institucion = Institucion::find($id_institucion);

                if ($institucion) {
                    $nombre_institucion = $institucion->nombre ?? 'VOLUNTARIO';
                }
            }
            if ($titular->tipo_rentista != null) {
                switch ($titular->tipo_rentista) {
                    case 'VIUDEDAD':
                        $tipo_asegurado = $tipo_asegurado . ' DERECHOHABIENTE';
                        break;
                    case 'ORFANDAD':
                        $tipo_asegurado = $tipo_asegurado . ' DERECHOHABIENTE';
                        break;
                    case 'RENTISTA-INVALIDEZ':
                        $tipo_asegurado = $tipo_asegurado . ' RENTISTA POR INVALIDEZ';
                        break;
                    case 'TITULAR-RENTISTA':
                        $tipo_asegurado = $tipo_asegurado . ' RENTISTA';
                        break;
                    case 'TITULAR-SORTEO':
                        $tipo_asegurado = $tipo_asegurado . ' RENTISTA POR ALGORITMO';
                        break;
                }
            }
        } elseif ($beneficiario) {

            $fecha_afiliacion = Carbon::parse($beneficiario->fecha_afiliacion)->locale('es')->isoFormat('D [de] MMMM [de] YYYY') ?? 'sin fecha';
            $id_titular_ben = $beneficiario->id_titular;
            $nombre_institucion = 'VOLUNTARIO';

            $titular = Titular::find($id_titular_ben);
            $id_persona_tit = $titular->id_persona;

            $titular_insti = TitularInstitucion::where('id_titular', $id_titular_ben)
                ->orderBy('id', 'ASC')
                ->first();
            $id_institucion = null;
            if ($titular_insti) {
                $id_institucion = $titular_insti->id_institucion;
                $institucion = Institucion::find($id_institucion);

                if ($institucion) {
                    $nombre_institucion = $institucion->nombre ?? 'VOLUNTARIO';
                }
            }
            $persona = Persona::find($id_persona_tit);
            $nombre_titular = $persona->p_apellido . ' ' . $persona->s_apellido . ' ' . $persona->nombres;
        } elseif ($estudiante) {

            $fecha_afiliacion = Carbon::parse($estudiante->fecha_afiliacion)->locale('es')->isoFormat('D [de] MMMM [de] YYYY') ?? 'sin fecha';
            $nombre_institucion = 'UNIVERSIDAD MAYOR DE SAN ANDRÉS';
        }




        //$url = 'http://www.ssulapaz.org'; // URL que deseas codificar en el QR
        //
        //$qrCode = QrCode::size(100)->generate($url);
        //$qrCodeBase64 = base64_encode($qrCode);

        //Para hacer codigo qr
        // dd($afiliado->id_persona);
        $codigos = QRCodeService::generarQR();
        $cod_seguridad = $codigos['cod_seguridad'];
        $qrCode = $codigos['qrCode'];

        //dd('dentra');
        /* $qrImage = $qrCode->writeString();

        // Guardar el QR Code en el servidor FTP
        $filename = 'qr_' . $afiliado->ci . '.jpg'; // Nombre del archivo QR en el servidor FTP
        $filePath = 'Afiliacion/CERTIFICADOS_AFILIACION/QR/' . $filename; // Ruta donde se guardará el archivo en el servidor FTP
        Storage::disk('ftp')->put($filePath, $qrImage); */
        $id_persona = $afiliado->id_persona;
        // El nombre del archivo en el servidor FTP
        $filename = 'logossu.png';
        $filePath = 'Afiliacion/CERTIFICADOS_AFILIACION/' . $filename;
        $disk = Storage::disk('ftp');
        if ($disk->exists($filePath)) {
            $fileContent = $disk->get($filePath);
        } else {
            throw new \Exception("El archivo no existe en el servidor FTP");
        }

        $fileContent = base64_encode($fileContent);
        $pdf = DomPDF::loadView(
            'Afiliacion.Certificados.AfiliacionSSU.certificadoAfiliacionPDF',
            compact(
                'afiliado',
                'fechaHoy',
                'filename',
                'fileContent',
                'qrCode',
                'fechaEmision',
                'id_tipo_asegurado',
                'tipo_asegurado',
                'fecha_afiliacion',
                'nombre_institucion',
                'nombre_titular',
                'id_tipo_asegurado'
            )
        )
            ->setPaper('letter', 'portrait');
        QRCodeService::registrarPDF($pdf, $cod_seguridad, $id_persona, 2, 'AFILIACION');

        //AQUI MOSTRAR EL NUMERO DE PAGINAS!

        /*         Para guardar el pdf en ftp */
        $pdfContent = $pdf->output();
        $fileName = 'CA_' . $afiliado->paterno . '_' . $afiliado->ci . '.pdf';
        $filePath = 'Afiliacion/CERTIFICADOS_AFILIACION/PDF/' . $fileName;
        Storage::disk('ftp')->put($filePath, $pdfContent);

        return $pdf->stream($fileName, [
            'Attachment' => false,  // Para abrir en el navegador en lugar de descargar
            'Content-Disposition' => 'inline'  // Para asegurar que se abra en una nueva pestaña
        ]);
    }

    // Funcion para el certificado de Titular
    public function pdf_certificado_titular($row)
    {
        // dd('recurperar desde el mismo usuario ');
        $certificadoActual = CertificadoAfiliacion::find($row);
        $id_persona = $certificadoActual->id_persona;

        $codigo_seguridad = $certificadoActual->codigo_seguridad;
        $cod_certificado = $certificadoActual->cod_certificado;
        $nro_cite = $certificadoActual->nro_cite;
        $fecha_emision = $certificadoActual->fecha_emision;
        $fecha_caducidad = $certificadoActual->fecha_caducidad;
        $gestion = $certificadoActual->gestion;
        $id_usuario = $certificadoActual->id_user_created;

        $personaActual = Persona::find($id_persona);
        $ci = $personaActual->ci;
        $complemento = $personaActual->complemento ?: '';
        $nombre = $personaActual->nombres;
        $p_apellido = $personaActual->p_apellido;
        $s_apellido = $personaActual->s_apellido;
        $matricula = $personaActual->matricula_seguro;
        $fecha_nacimiento = Carbon::parse($personaActual->fecha_nacimiento)->format('d/m/Y');

        //Titular  
        $titActual = Titular::where('id_persona', $id_persona)
            ->orderBy('id', 'DESC')
            ->first();

        $mapaTipos = [
            'VIUDEDAD'           => 'TITULAR DERECHOHABIENTE',
            'ORFANDAD'           => 'TITULAR DERECHOHABIENTE',
            'RENTISTA-INVALIDEZ'  => 'TITULAR RENTISTA POR INVALIDEZ',
            'TITULAR-RENTISTA'    => 'TITULAR RENTISTA',
            'TITULAR-SORTEO'      => 'TITULAR RENTISTA POR ALGORITMO',
        ];

        $tipo = $mapaTipos[$titActual->tipo_rentista] ?? 'TITULAR';

        $fecha_afiliacion = Carbon::parse($titActual->fecha_afiliacion)->locale('es')->isoFormat('D [de] MMMM [de] YYYY');
        $nombre_institucion = 'VOLUNTARIO';
        if ($titActual) {


            $titular_insti = TitularInstitucion::where('id_titular', $titActual->id)
                ->orderBy('id', 'ASC')
                ->where('estado', true)
                ->first();
            //dd($titular_insti);
            $id_institucion = null;
            if ($titular_insti) {
                $id_institucion = $titular_insti->id_institucion;
                $institucion = Institucion::find($id_institucion);


                if ($institucion) {
                    $nombre_institucion = $institucion->nombre ?? 'VOLUNTARIO';
                }
            }
        }

        $id_dep = $personaActual->id_dept_exp;
        if ($id_dep) {
            $depActual = Departamento::find($id_dep);
            $sigla = $depActual->sigla;
        } else {
            $sigla = '';
        }

        $datos_usuario_registro = User::with('personaUser')->find($id_usuario);
        $datos_usuario = Persona::find($datos_usuario_registro->id_persona);
        $nombre_usuario = $datos_usuario->nombres . ' ' . $datos_usuario->p_apellido . ' ' . $datos_usuario->s_apellido;

        // fecha hoy
        $fechaHoy = Carbon::now()->locale('es')->isoFormat('D [de] MMMM [de] YYYY');

        $fechaEmision = Carbon::parse($fecha_emision)
            ->locale('es')
            ->isoFormat('dddd, D [de] MMMM [de] YYYY');
        //Para hacer codigo qr
        // MENSAJE NUEVO DEL QR
        $codigos = QRCodeService::generarQR();
        $cod_seguridad = $codigos['cod_seguridad'];
        $qrCode = $codigos['qrCode'];

        // Para abrir el blade vista del pdf con variables ya definidas
        $pdf = DomPDF::loadView(
            'Afiliacion.Certificados.Afiliado.titularPdf',
            compact(
                'ci',
                'complemento',
                'nombre',
                'p_apellido',
                's_apellido',
                'matricula',
                'fecha_nacimiento',
                'fechaHoy',
                'sigla',
                'tipo',
                'codigo_seguridad',
                'qrCode',
                'nro_cite',
                'fecha_afiliacion',
                'gestion',
                'nombre_institucion',
                'fechaEmision',
                'fecha_caducidad'
            )
        )
            ->setPaper('letter', 'portrait');
        QRCodeService::registrarPDF($pdf, $cod_seguridad, $id_persona, 2, 'AFILIACION');


        $nombre_pdf = 'pdf_certificado_titular';
        // Para abrir pdf
        return $pdf->stream($nombre_pdf . '.pdf', [
            'Attachment' => false,
            'Content-Disposition' => 'inline'
        ]);
    }

    // Funcion para el certificado de Beneficiario
    public function pdf_certificado_beneficiario($row)
    {
        $certificadoActual = CertificadoAfiliacion::find($row);
        $id_persona = $certificadoActual->id_persona;
        $codigo_seguridad = $certificadoActual->codigo_seguridad;
        $cod_certificado = $certificadoActual->cod_certificado;
        $nro_cite = $certificadoActual->nro_cite;
        $fecha_emision = $certificadoActual->fecha_emision;
        $fecha_caducidad = $certificadoActual->fecha_caducidad;
        $gestion = $certificadoActual->gestion;
        $id_usuario = $certificadoActual->id_user_created;

        $personaActual = Persona::find($id_persona);
        $ci = $personaActual->ci;
        $complemento = $personaActual->complemento ?: '';
        $nombre = $personaActual->nombres;
        $p_apellido = $personaActual->p_apellido;
        $s_apellido = $personaActual->s_apellido;
        $matricula = $personaActual->matricula_seguro;
        $fecha_nacimiento = Carbon::parse($personaActual->fecha_nacimiento)->format('d/m/Y');
        $fechaHoy = Carbon::now()->locale('es')->isoFormat('D [de] MMMM [de] YYYY');
        $id_dep = $personaActual->id_dept_exp;

        $id_tipo_asegurado = $personaActual->id_tipo_asegurado;

        $tipoActual = TipoAsegurado::find($id_tipo_asegurado);
        $tipo_asegurado = $tipoActual->tipo_asegurado;

        $depActual = Departamento::find($id_dep);
        if ($depActual) {
            $sigla = $depActual->sigla;
        } else {
            $sigla = '';
        }

        //Beneficiario
        $benActual = Beneficiario::where('id_persona', $id_persona)
            ->orderBy('id', 'DESC')
            ->first();
        $id_titular = $benActual->id_titular;
        $fecha_afiliacion = Carbon::parse($benActual->fecha_afiliacion)->locale('es')->isoFormat('D [de] MMMM [de] YYYY');

        //Titular  
        $titActual = Titular::find($id_titular);
        $id_persona_titular = $titActual->id_persona;

        $tit_insti = TitularInstitucion::where('id_titular', $id_titular)->first();
        if ($tit_insti) {
            $id_institucion = $tit_insti->id_institucion;
            $institucion = Institucion::find($id_institucion);
            $nombre_institucion = $institucion->nombre;
        } else {
            $nombre_institucion = null;
        }
        $personaTitular = Persona::find($id_persona_titular);
        $nombre_titular = $personaTitular->nombres;
        $p_apellido_titular = $personaTitular->p_apellido;
        $s_apellido_titular = $personaTitular->s_apellido;

        $datos_usuario_registro = User::with('personaUser')->find($id_usuario);
        $datos_usuario = Persona::find($datos_usuario_registro->id_persona);
        $nombre_usuario = $datos_usuario->nombres . ' ' . $datos_usuario->p_apellido . ' ' . $datos_usuario->s_apellido;


        // fecha hoy
        $fechaHoy = Carbon::now()->locale('es')->isoFormat('D [de] MMMM [de] YYYY');

        //Para hacer codigo qr
        // MENSAJE NUEVO DEL QR
        $codigos = QRCodeService::generarQR();
        $cod_seguridad = $codigos['cod_seguridad'];
        $qrCode = $codigos['qrCode'];
        // Para abrir el blade vista del pdf con variables ya definidas
        $pdf = DomPDF::loadView(
            'Afiliacion.Certificados.Afiliado.beneficiarioPdf',
            compact(
                'ci',
                'complemento',
                'nombre',
                'p_apellido',
                's_apellido',
                'matricula',
                'sigla',
                'fecha_nacimiento',
                'qrCode',
                'tipo_asegurado',
                'nombre_titular',
                'p_apellido_titular',
                's_apellido_titular',
                'nombre_institucion',
                'fechaHoy',
                'fecha_afiliacion',
                'codigo_seguridad',
                'nro_cite',
                'gestion'
            )
        )
            ->setPaper('letter', 'portrait');

        QRCodeService::registrarPDF($pdf, $cod_seguridad, $id_persona, 2, 'AFILIACION');

        $nombre_pdf = 'pdf_certificado_beneficiario';
        // Para abrir pdf
        return $pdf->stream($nombre_pdf . '.pdf', [
            'Attachment' => false,
            'Content-Disposition' => 'inline'
        ]);
    }


    // Funcion para descargar pdf titular 
    public function pdf_titular_download($row)
    {
        //dd('esto es el descargar de parte del mismo usuario');
        $certificadoActual = CertificadoAfiliacion::find($row);
        $id_persona = $certificadoActual->id_persona;
        $codigo_seguridad = $certificadoActual->codigo_seguridad;
        $cod_certificado = $certificadoActual->cod_certificado;
        $nro_cite = $certificadoActual->nro_cite;
        $fecha_emision = $certificadoActual->fecha_emision;
        $fecha_caducidad = $certificadoActual->fecha_caducidad;
        $gestion = $certificadoActual->gestion;
        $id_usuario = $certificadoActual->id_user_created;

        $personaActual = Persona::find($id_persona);
        $ci = $personaActual->ci;
        $complemento = $personaActual->complemento ?: '';
        $nombre = $personaActual->nombres;
        $p_apellido = $personaActual->p_apellido;
        $s_apellido = $personaActual->s_apellido;
        $matricula = $personaActual->matricula_seguro;
        $fecha_nacimiento = Carbon::parse($personaActual->fecha_nacimiento)->format('d/m/Y');


        $id_dep = $personaActual->id_dept_exp;
        if ($id_dep) {
            $depActual = Departamento::find($id_dep);
            $sigla = $depActual->sigla;
        } else {
            $sigla = '';
        }


        $datos_usuario_registro = User::with('personaUser')->find($id_usuario);
        $datos_usuario = Persona::find($datos_usuario_registro->id_persona);
        $nombre_usuario = $datos_usuario->nombres . ' ' . $datos_usuario->p_apellido . ' ' . $datos_usuario->s_apellido;

        // fecha hoy
        $fechaHoy = Carbon::now()->locale('es')->isoFormat('D [de] MMMM [de] YYYY');

        $fechaEmision = Carbon::parse($fecha_emision)
            ->locale('es')
            ->isoFormat('dddd, D [de] MMMM [de] YYYY');
        //Titular  
        $titActual = Titular::where('id_persona', $id_persona)
            ->orderBy('id', 'DESC')
            ->first();
        //dd($titActual);
        $mapaTipos = [
            'VIUDEDAD'           => 'TITULAR DERECHOHABIENTE',
            'ORFANDAD'           => 'TITULAR DERECHOHABIENTE',
            'RENTISTA-INVALIDEZ'  => 'TITULAR RENTISTA POR INVALIDEZ',
            'TITULAR-RENTISTA'    => 'TITULAR RENTISTA',
            'TITULAR-SORTEO'      => 'TITULAR RENTISTA POR ALGORITMO',
        ];

        $tipo = $mapaTipos[$titActual->tipo_rentista] ?? 'TITULAR';

        $fecha_afiliacion = Carbon::parse($titActual->fecha_afiliacion)->locale('es')->isoFormat('D [de] MMMM [de] YYYY');
        $nombre_institucion = 'VOLUNTARIO';
        if ($titActual) {


            $titular_insti = TitularInstitucion::where('id_titular', $titActual->id)
                ->orderBy('id', 'ASC')
                ->where('estado', true)
                ->first();
            // dd($titular_insti);
            $id_institucion = null;
            if ($titular_insti) {
                $id_institucion = $titular_insti->id_institucion;
                $institucion = Institucion::find($id_institucion);

                if ($institucion) {
                    $nombre_institucion = $institucion->nombre ?? 'VOLUNTARIO';
                }
            }
        }

        //Para hacer codigo qr
        //ANTIGUO MENSAJE DEL QR
        /*$mensajeQR='El presente corresponde al Código de Certificado: ' . $cod_certificado.
         '|| en Fecha solicitud: '.$fecha_emision. ' || con una fecha valida de 15 dias hasta: '.$fecha_caducidad.
         ' ||, y correspondiente a un  Código de Seguridad: '.$codigo_seguridad.
         ' || ||. Emitido por el encargado: '.$nombre_usuario.
         ' || Verificado y validado por el SSU LP - Sistema SIGHO v2.';
       
        $qrCode = base64_encode(QrCode::format('svg')
        ->size(80)
        ->color(3, 125, 155 ) 
        ->backgroundColor(255, 255, 255) 
        ->errorCorrection('H')
        ->generate( $mensajeQR));
        */

        // MENSAJE NUEVO DEL QR
        $codigos = QRCodeService::generarQR();
        $cod_seguridad = $codigos['cod_seguridad'];
        $qrCode = $codigos['qrCode'];
        // Para abrir el blade vista del pdf con variables ya definidas
        $pdf = DomPDF::loadView(
            'Afiliacion.Certificados.Afiliado.titularPdf',
            compact(
                'ci',
                'complemento',
                'nombre',
                'p_apellido',
                's_apellido',
                'matricula',
                'fecha_nacimiento',
                'fechaHoy',
                'sigla',
                'tipo',
                'codigo_seguridad',
                'qrCode',
                'nro_cite',
                'fecha_afiliacion',
                'gestion',
                'nombre_institucion',
                'fechaEmision',
                'fecha_caducidad'
            )
        )
            ->setPaper('letter', 'portrait');
        QRCodeService::registrarPDF($pdf, $cod_seguridad, $id_persona, 2, 'AFILIACION');


        //Guardar el pdf en el ftp
        $pdfContent = $pdf->output();
        $ftpPath = 'Afiliacion/CERTIFICADO_TIT_BEN/CERT_TITULARES/' . $row . '-C-T' . '.pdf';
        Storage::disk('ftp')->put($ftpPath, $pdfContent);



        $nombre_pdf = 'pdf_certificado_titular';
        // Para abrir pdf
        return $pdf->download($nombre_pdf . '.pdf', [
            'Attachment' => false,
            'Content-Disposition' => 'inline'
        ]);
    }


    // Funcion para descargar pdf beneficiario
    public function pdf_beneficiario_download($row)
    {
        // dd('dentra pdf_beneficiario_descarga');
        $certificadoActual = CertificadoAfiliacion::find($row);
        $id_persona = $certificadoActual->id_persona;
        $codigo_seguridad = $certificadoActual->codigo_seguridad;
        $cod_certificado = $certificadoActual->cod_certificado;
        $nro_cite = $certificadoActual->nro_cite;
        $fecha_emision = $certificadoActual->fecha_emision;
        $fecha_caducidad = $certificadoActual->fecha_caducidad;
        $gestion = $certificadoActual->gestion;
        $id_usuario = $certificadoActual->id_user_created;

        $personaActual = Persona::find($id_persona);
        $ci = $personaActual->ci;
        $complemento = $personaActual->complemento ?: '';
        $nombre = $personaActual->nombres;
        $p_apellido = $personaActual->p_apellido;
        $s_apellido = $personaActual->s_apellido;
        $matricula = $personaActual->matricula_seguro;
        $fecha_nacimiento = Carbon::parse($personaActual->fecha_nacimiento)->format('d/m/Y');
        $fechaHoy = Carbon::now()->locale('es')->isoFormat('D [de] MMMM [de] YYYY');
        $id_dep = $personaActual->id_dept_exp;
        // dd( $id_dep);
        $id_tipo_asegurado = $personaActual->id_tipo_asegurado;

        $tipoActual = TipoAsegurado::find($id_tipo_asegurado);
        $tipo_asegurado = $tipoActual->tipo_asegurado;

        $depActual = Departamento::find($id_dep);
        if ($depActual) {
            $sigla = $depActual->sigla;
        } else {
            $sigla = '';
        }


        //Beneficiario
        $benActual = Beneficiario::where('id_persona', $id_persona)
            ->orderBy('id', 'DESC')
            ->first();
        $id_titular = $benActual->id_titular;
        $fecha_afiliacion = Carbon::parse($benActual->fecha_afiliacion)->locale('es')->isoFormat('D [de] MMMM [de] YYYY');

        //Titular  
        $titActual = Titular::find($id_titular);

        $id_persona_titular = $titActual->id_persona;
        //dd($titActual);
        $tit_insti = TitularInstitucion::where('id_titular', $id_titular)->first();
        //dd($tit_insti);
        if ($tit_insti) {
            $id_institucion = $tit_insti->id_institucion;
            $institucion = Institucion::find($id_institucion);
            $nombre_institucion = $institucion->nombre;
        } else {
            $nombre_institucion = '';
        }
        $personaTitular = Persona::find($id_persona_titular);
        $nombre_titular = $personaTitular->nombres;
        $p_apellido_titular = $personaTitular->p_apellido;
        $s_apellido_titular = $personaTitular->s_apellido;

        $datos_usuario_registro = User::with('personaUser')->find($id_usuario);
        $datos_usuario = Persona::find($datos_usuario_registro->id_persona);
        $nombre_usuario = $datos_usuario->nombres . ' ' . $datos_usuario->p_apellido . ' ' . $datos_usuario->s_apellido;
        // fecha hoy
        $fechaHoy = Carbon::now()->locale('es')->isoFormat('D [de] MMMM [de] YYYY');

        //Para hacer codigo qr
        $codigos = QRCodeService::generarQR();
        $cod_seguridad = $codigos['cod_seguridad'];
        $qrCode = $codigos['qrCode'];
        // Para abrir el blade vista del pdf con variables ya definidas
        $pdf = DomPDF::loadView(
            'Afiliacion.Certificados.Afiliado.beneficiarioPdf',
            compact(
                'ci',
                'complemento',
                'nombre',
                'p_apellido',
                's_apellido',
                'matricula',
                'sigla',
                'fecha_nacimiento',
                'qrCode',
                'tipo_asegurado',
                'nombre_titular',
                'p_apellido_titular',
                's_apellido_titular',
                'nombre_institucion',
                'fechaHoy',
                'fecha_afiliacion',
                'codigo_seguridad',
                'gestion',
                'nro_cite'
            )
        )
            ->setPaper('letter', 'portrait');
        QRCodeService::registrarPDF($pdf, $cod_seguridad, $id_persona, 2, 'AFILIACION');

        //Guardar el pdf en el ftp
        $pdfContent = $pdf->output();
        $ftpPath = 'Afiliacion/CERTIFICADO_TIT_BEN/CERT_BENEFICIARIOS/' . $row . '-C-B' . '.pdf';
        Storage::disk('ftp')->put($ftpPath, $pdfContent);


        $nombre_pdf = 'pdf_certificado_beneficiario';
        // Para descargar  pdf
        return $pdf->download($nombre_pdf . '.pdf', [
            'Attachment' => false,
            'Content-Disposition' => 'inline'
        ]);
    }
}
