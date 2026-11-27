<?php

namespace App\Http\Controllers\Afiliacion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DomPDF;
use Illuminate\Support\Facades\DB;

use App\Models\Administracion\Persona;
use App\Models\Aportes\ContratoVoluntario;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\TitularInstitucion;
use App\Models\Afiliacion\BeneficiarioInstitucion;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\Estudiante;
use App\Models\Vigencia\HistorialAsegurado;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\User;
use App\Services\GrupoFamiliarPersona;
use App\Services\QRCodeService;



class AfiliadoHistorialPdf extends Controller
{
    public function afiliado_historial_pdf($row)
    {

        $persona = Persona::find($row);
        $nombre_completo = $persona->nombres . ' ' . $persona->p_apellido . ' ' . $persona->s_apellido;
        $fechaNacimiento = Carbon::parse($persona->fecha_nacimiento);  // Convertir fecha de nacimiento a una instancia de Carbon
        $edad = $fechaNacimiento->age;
        if ($persona->sexo === 'F') {
            $sexo_textual = 'FEMENINO';
        } elseif ($persona->sexo === 'M') {
            $sexo_textual = 'MASCULINO';
        }

        $afiliado = Persona::with(['tipoAseguradoPersona'])->find($row);
        $tipo_asegurado = $afiliado->tipoAseguradoPersona->tipo_asegurado;


        $estudiante = Estudiante::where('id_persona', $persona->id)
            ->with(['carreraEstudiante'])
            ->get();
        if ($estudiante->isNotEmpty()) {
            $varEstudiante = true;
            $fue_estudiante = $estudiante;
        } else {
            $varEstudiante = false;
            $fue_estudiante = 'no hay';
        }
        // dd($estudiante);

        $historial = HistorialAsegurado::where('id_persona', $persona->id)
            ->with('tipoAseguradoHistorialAsegurado')
            ->get();

        $fue_beneficiario = [];
        $varBeneficiario = false;
        foreach ($historial as $key => $dato) {
            if (strpos($dato->tipoAseguradoHistorialAsegurado->tipo_asegurado, 'BENEFICIARIO') !== false) {
                $beneficiario = Beneficiario::with('beneficiarioConInstitucionBeneficiario.institucionBeneficiarioInstitucion')->find($dato->id_corresponde);
                if ($beneficiario) {
                    $varBeneficiario = true;
                    foreach ($beneficiario->beneficiarioConInstitucionBeneficiario as $institucion) {
                        $fue_beneficiario[] = [
                            'id' => $beneficiario->id,
                            'tipo_asegurado' => $dato->tipoAseguradoHistorialAsegurado->tipo_asegurado,
                            'fecha_afiliacion' => $beneficiario->fecha_afiliacion,
                            'fecha_baja' => $beneficiario->fecha_baja,
                            'remuneracion' => $beneficiario->con_remuneracion ? 'SI' : 'NO',
                            'institucion' => $institucion->institucionBeneficiarioInstitucion ? $institucion->institucionBeneficiarioInstitucion->nombre : 'No aplica',
                        ];
                    }
                    if ($beneficiario->beneficiarioConInstitucionBeneficiario->isEmpty()) {
                        $fue_beneficiario[]  = [
                            'id' => $beneficiario->id,
                            'tipo_asegurado' => $dato->tipoAseguradoHistorialAsegurado->tipo_asegurado,
                            'fecha_afiliacion' => $beneficiario->fecha_afiliacion,
                            'fecha_baja' => $beneficiario->fecha_baja,
                            'remuneracion' => $beneficiario->con_remuneracion ? 'SI' : 'NO',
                            'institucion' => 'No aplica',
                        ];
                    }
                }
            }
        }
        $fue_titular = [];
        $varTitular = false;
        // $tit = Titular::where('id_persona', $persona->id)->first();
        $titular = Titular::with([
            'titularConTitularInstitucion.titularInstitucionConInstitucion',
            'titularPersona.contratoVoluntarioPersona'
        ])->where('id_persona', $persona->id)
            ->get();
        // dd($titular);
        if ($titular->isNotEmpty()) {
            $varTitular = true;
            foreach ($titular as $t) {
                if ($t->tipo_titular) { // Si tipo_titular es true
                    $primeraInstitucion = TitularInstitucion::where('id_titular', $t->id)
                        //->where('tipo_institucion', 'PATRONAL')
                        // ->where('estado', true)
                        ->with([
                            'titularInstitucionConTitular',
                            'titularInstitucionConInstitucion',
                            'titularInstitucionConInstitucion.claseAportanteInstitucion',
                            'titularInstitucionConInstitucion.codigosInstitucion'
                        ])
                        ->orderBy('id', 'asc')
                        ->first();
                    $fue_titular[] = [
                        'id' => $t->id,
                        'tipo_titular' => 'TITULAR INSTITUCIONAL',
                        'fecha_afiliacion' => $t->fecha_reafiliacion,
                        'fecha_baja' => $t->fecha_baja,
                        'institucion' => $primeraInstitucion->titularInstitucionConInstitucion->nombre ?? 'No aplica',
                        'sigla'  => $primeraInstitucion->titularInstitucionConInstitucion->sigla ?? '',
                        'contrato' => 'No aplica',
                        'fecha_inicio' => null,
                        'fecha_fin' => null,
                    ];
                } else { // Si tipo_titular es false
                    $contrato = $t->titularPersona->contratoVoluntarioPersona->first();
                    $fue_titular[] = [
                        'id' => $t->id,
                        'tipo_titular' => 'TITULAR VOLUNTARIO',
                        'fecha_afiliacion' => $t->fecha_afiliacion,
                        'fecha_baja' => $t->fecha_baja,
                        'institucion' => 'No aplica',
                        'sigla' => 'No aplica',
                        'contrato' => $contrato->descripcion_contrato ?? 'No aplica',
                        'fecha_inicio' => $contrato->fecha_inicio_contrato ?? 'No cuenta con detalles',
                        'fecha_fin' => $contrato->fecha_fin_contrato ?? 'No cuenta con detalles',
                    ];
                }
            }
        }
        $count = 0;
        $fecha_registro = Carbon::now();
        $userActual = User::join('administracion.persona as p', 'p.id', '=', 'administracion.users.id_persona')
            ->where('administracion.users.id', '=', auth()->user()->id)
            ->first();
        $id_persona = $userActual->id_persona;

        $personaUsuario = Persona::find($id_persona);
        $nombre_usuario = $personaUsuario->nombres . ' ' . $personaUsuario->p_apellido . ' ' . $personaUsuario->s_apellido;
        //  dd($this->fue_titular)
        //  Para abrir el blade vista del pdf con variables ya definidas

        // MENSAJE NUEVO DEL QR
        $codigos = QRCodeService::generarQR();
        $cod_seguridad = $codigos['cod_seguridad'];
        $qrCode = $codigos['qrCode'];

        $pdf = DomPDF::loadView(
            'Afiliacion.Afiliacion.afiliadoPdf',
            compact(
                'persona',
                'edad',
                'fecha_registro',
                'nombre_usuario',
                'count',
                'sexo_textual',
                'nombre_completo',
                'fue_estudiante',
                'fue_titular',
                'fue_beneficiario',
                'varEstudiante',
                'varBeneficiario',
                'varTitular',
                'nombre_usuario',
                'qrCode',
            )
        )
            ->setPaper('letter', 'portrait');

        QRCodeService::registrarPDF($pdf, $cod_seguridad, $id_persona, 11, 'AFILIACION');

        $nombre_pdf = 'pdf_afiliado_historial';
        // Para abrir pdf stream
        return $pdf->stream($nombre_pdf . '.pdf', [
            'Attachment' => false,
            'Content-Disposition' => 'inline'
        ]);
    }

    // PARA LA FICHA DE AFILIACION DEL TITULAR SOLAMENTE
    public function ficha_afiliacion_pdf($id_persona)
    {
        $id = $id_persona;

        $titular = Titular::where('id_persona', $id)
            ->with(['titularPersona', 'titularPersona.departamentoPersona'])
            ->with(['titularResidencia', 'titularResidencia.zonaResidencia'])
            ->orderBy('id', 'desc')
            ->first();
        // dd($titular);
        $id_titular = $titular->id;

        if ($titular) {
            // Asignar la imagen en base64 a la propiedad foto_nombre del titular
            $titular->foto_nombre = $this->obtenerImagenBase64($titular->foto_nombre);
            if ($titular->fecha_reafiliacion) {
                $fechaAfiliacion = Carbon::parse($titular->fecha_reafiliacion)->Format('d/m/Y ');
            } else {
                $fechaAfiliacion = Carbon::parse($titular->fecha_afiliacion)->Format('d/m/Y ');
            }
        }
        $foto = $titular->foto_nombre;
        //dd($foto);
        // dd($id_titular);
        $titularInstitucional = TitularInstitucion::where('id_titular', $id_titular)
            // ->where('tipo_institucion', '=', 'PATRONAL')
            ->where('estado', true)
            ->with([
                'titularInstitucionConInstitucion',
                'titularInstitucionConInstitucion.codigosInstitucion'
            ])
            ->orderBy('id', 'asc')
            ->first();
        //dd($titularInstitucional);
        $titularVoluntario = ContratoVoluntario::where('id_persona', $id)
            ->first();

        $fecha_registro = Carbon::now();
        $userActual = User::join('administracion.persona as p', 'p.id', '=', 'administracion.users.id_persona')
            ->where('administracion.users.id', '=', auth()->user()->id)
            ->first();
        $id_persona = $userActual->id_persona;

        $personaUsuario = Persona::find($id_persona);
        $nombre_usuario = $personaUsuario->nombres . ' ' . $personaUsuario->p_apellido . ' ' . $personaUsuario->s_apellido;

        // GRUPO FAMILIAR
        if ($id_titular) {
            $grupoFamiliar = Beneficiario::where('id_titular', $id_titular)
                ->where('vigencia', '!=', 'NO VIGENTE')
                ->where('estado', true)
                ->with([
                    'beneficiarioPersona',
                    'beneficiarioPersona.departamentoPersona',
                    'beneficiarioPersona.estadoCivilPersona',
                    'beneficiarioPersona.tipoAseguradoPersona'
                ])
                ->get();
            $grupoFamiliar = $grupoFamiliar->map(function ($familiar) {
                $familiar->foto_nombre = $this->obtenerImagenBase64($familiar->foto_nombre);
                return $familiar;
            });

            //dd($grupoFamiliar->isEmpty());
            if (!$grupoFamiliar->isEmpty()) {
                $grupoFamiliarDatos = $grupoFamiliar;
            } else {
                $grupoFamiliarDatos = [];
            }
        } else {
            $grupoFamiliarDatos = [];
        }

        // Para abrir el blade vista del pdf con variables ya definidas
        $codigos = QRCodeService::generarQR();
        $cod_seguridad = $codigos['cod_seguridad'];
        $qrCode = $codigos['qrCode'];

        $pdf = DomPDF::loadView(
            'Afiliacion.Afiliacion.fichaAfiliado',
            compact(
                'id',
                'titular',
                'titularInstitucional',
                'fecha_registro',
                'nombre_usuario',
                'titularVoluntario',
                'qrCode',
                'id_titular',
                'fechaAfiliacion',
                'foto',
                'grupoFamiliarDatos'
            )
        )
            ->setPaper('letter', 'portrait');
        QRCodeService::registrarPDF($pdf, $cod_seguridad, $id_persona, 15, 'AFILIACION');

        $nombre_pdf = 'pdf_ficha_afiliado';
        // Para abrir pdf stream
        return $pdf->stream($nombre_pdf . '.pdf', [
            'Attachment' => false,
            'Content-Disposition' => 'inline'
        ]);
    }

    // Funcion para obtener imagenes desde la FTP
    /*     private function obtenerImagenBase64($filename)
    {
        try {
            if ($filename) {
                $path = 'Afiliacion/FOTOS/' . $filename;
                $imageData = Storage::disk('ftp')->get($path);
                $fotito = base64_encode($imageData);

                return $fotito;
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            return null;
        }
    } */

    private function obtenerImagenBase64($filename)
    {
        try {

            if (!$filename) {
                return null;
            }

            $path = 'Afiliacion/FOTOS/' . $filename;
            $imageData = Storage::disk('ftp')->get($path);

            $source = imagecreatefromstring($imageData);

            if (!$source) {
                return base64_encode($imageData);
            }

            $width  = imagesx($source);
            $height = imagesy($source);

            $ratio = $height / $width;

            /*
         * Si NO es una foto extremadamente vertical,
         * devolverla original.
         */
            if ($ratio < 3) {
                imagedestroy($source);
                return base64_encode($imageData);
            }

            /*
         * Para fotos largas:
         * tomar un cuadrado desde arriba.
         */
            $cropSize = $width;

            $cropped = imagecrop($source, [
                'x'      => 0,
                'y'      => 0,
                'width'  => $cropSize,
                'height' => $cropSize
            ]);

            if (!$cropped) {
                imagedestroy($source);
                return base64_encode($imageData);
            }

            /*
         * Fondo blanco para evitar negros
         * en imágenes transparentes.
         */
            $final = imagecreatetruecolor($cropSize, $cropSize);

            $white = imagecolorallocate($final, 255, 255, 255);

            imagefilledrectangle(
                $final,
                0,
                0,
                $cropSize,
                $cropSize,
                $white
            );

            imagecopy(
                $final,
                $cropped,
                0,
                0,
                0,
                0,
                imagesx($cropped),
                imagesy($cropped)
            );

            ob_start();
            imagejpeg($final, null, 95);
            $contenido = ob_get_clean();

            imagedestroy($source);
            imagedestroy($cropped);
            imagedestroy($final);

            return base64_encode($contenido);
        } catch (\Throwable $th) {
            return null;
        }
    }
}
