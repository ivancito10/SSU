<?php

namespace App\Http\Controllers\Vigencia;

use App\Http\Controllers\Controller;
use App\Models\Administracion\Persona;
use Illuminate\Http\Request;
use DomPDF;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Services\ServicioPersonaInstitucion;
use App\Models\Aportes\Institucion;
use App\Models\Aportes\ContratoVoluntario;
use App\Models\Vigencia\AutorizacionInterior;
use App\Models\Vigencia\Convenio;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\Estudiante;

class TapaHistoriaClinica extends Controller
{
    public function pdfTapaHistoriaClinica($id_persona)
    {
        $id_per = $id_persona;

        $datos_persona = Persona::where('id', $id_persona)->orderBy('id', 'desc')->first();;
        // Servicio de Instituciones
        $dato_inst = new ServicioPersonaInstitucion();
        $persona_institucion = $dato_inst->personaInstitucion($id_persona);
       // dd($persona_institucion);
        // aseguramos que sea un array
        if (is_array($persona_institucion) && !empty($persona_institucion['instituciones'])) {
            $instituciones = $persona_institucion['instituciones'];

            $id_institucion = $instituciones[0]['id_institucion'] ?? '';
            $codigo_institucional = $instituciones[0]['codigo_institucional'] ?? '';
            // dd( $codigo_institucional);
            $primera_institucion_nombre = $instituciones[0]['nombre_institucion'] ?? '';
        } else {
            $id_institucion = '';
            $codigo_institucional = '';
            $primera_institucion_nombre = '';
        }



        $acronimo_institucion = 'sin dato';
        $clase_aportante = 'sin dato';
        $nro_vivienda = '';
        $zona = '';
        $telefono = '';

        if ($id_institucion != null) {
            $institucion = Institucion::with('codigosInstitucion')
                ->find($id_institucion);
            // dd($institucion);
            $acronimo_institucion = $institucion->acronimo;
            $clase_aportante = $institucion->claseAportanteInstitucion->nombre;
        } else {
            $voluntario = ContratoVoluntario::where('id_persona', $id_persona)
                ->orderBy('id', 'desc')
                ->first();

            if ($voluntario != null) {
                $acronimo_institucion = 'VOLUNTARIO';
                $clase_aportante = 'VOLUNTARIO';
            }
        }


        if ($clase_aportante == 'RENTISTA') {
            $valor = 3; // Es rentista
        } elseif ($datos_persona->tipoAseguradoPersona->tipo_asegurado == 'TITULAR') {
            if (str_contains($primera_institucion_nombre, 'UNIVERSIDAD') && $id_institucion != 2 && $id_institucion != 10 && $id_institucion == 6 && $id_institucion == 11) {
                $valor = 1; // Es docente
            } else {
                if ($id_institucion == 2 || $id_institucion == 10) {
                    $valor = 1; //Es docente
                } elseif ($id_institucion == 6 || $id_institucion == 11) {
                    $valor = 2; // Es administrativo
                } else {
                    $valor = 2; // Es administrativo
                }
            }
        } else {
            $valor = 3;
        }

        $autorizacion_interior = AutorizacionInterior::where('id_persona_interior', $id_persona)
            ->orderBy('id', 'desc')
            ->first();

        $sigla_seguro_interior = $autorizacion_interior->seguroInteriorAutorizacionInterior->sigla_seguro ?? '';

        // DATOS DE DIRECCIÓN
        // TITULAR 
        $titular = Titular::where('id_persona', $id_persona)->orderBy('id', 'desc')->first();
        $beneficiario = Beneficiario::where('id_persona', $id_persona)->orderBy('id', 'desc')->first();
        $estudiante = Estudiante::where('id_persona', $id_persona)->orderBy('id', 'desc')->first();

        $tipo_asegurado = $datos_persona->tipoAseguradoPersona->id;
        $codigo_institucional_titular = '';
        $persona_ben_tit = '';
        $nro_colspan = 0;
        $direccion = '';

        if ($titular != null) {
            $direccion = $titular->titularResidencia->direccion ?? '';
            $nro_vivienda = $titular->titularResidencia->nro_vivienda ?? '';
            $zona = $titular->titularResidencia->zonaResidencia->nombre ?? '';
            $telefono = $titular->telefono ?? '';
        } elseif ($beneficiario != null) {
            $direccion = $beneficiario->beneficiarioResidencia->direccion ?? '';
            $nro_vivienda = $beneficiario->beneficiarioResidencia->nro_vivienda ?? '';
            $zona = $beneficiario->beneficiarioResidencia->zonaResidencia->nombre ?? '';
            $telefono = $beneficiario->telefono ?? '';
            $id_titular_beneficiario = $beneficiario->id_titular ?? '';
            //dd($id_titular_beneficiario);

            $titular_ben = Titular::where('id', $id_titular_beneficiario)->orderBy('id', 'desc')->first();
            //dd($titular_ben);
            $id_persona_titular = $titular_ben->id_persona;
            //dd($id_persona_titular);
            $persona_ben_tit = Persona::where('id', $id_persona_titular)->orderBy('id', 'desc')->first();
            // Servicio de Instituciones
            $dato_inst_titular = new ServicioPersonaInstitucion();
            $persona_institucion_tit = $dato_inst_titular->personaInstitucion($id_persona_titular);
            $instituciones_tit = $persona_institucion_tit['instituciones'];
            $codigo_institucional_titular = $instituciones_tit[0]['codigo_institucional'] ?? '';
        } elseif ($estudiante != null) {
            $direccion = $estudiante->estudianteResidencia->direccion ?? '';
            $nro_vivienda = $estudiante->estudianteResidencia->nro_vivienda ?? '';
            $zona = $estudiante->estudianteResidencia->zonaResidencia->nombre ?? '';
            $telefono = $estudiante->telefono ?? '';
        } else {
            if ($tipo_asegurado == 8) {
                //Es titular convenio
                $convenio = Convenio::where('id_persona', $id_persona)->orderBy('id', 'desc')->first();
                $direccion =  '';
                $telefono =  '';
                $nro_vivienda = '';
                $zona = '';
                $nro_colspan = 2;
            } elseif ($tipo_asegurado == 9 || $tipo_asegurado == 10) {
                // Es titular del interior o beneficiario del interior
                //Es titular convenio
                $autorizacion = AutorizacionInterior::where('id_persona_interior', $id_persona)->orderBy('id', 'desc')->first();
                $direccion = $autorizacion->direccion_referencia ?? '';
                $telefono = $autorizacion->telefono ?? '';
                $nro_vivienda = '';
                $zona = '';
                $nro_colspan = 2;
            }
            //dd($tipo_asegurado);
        }



        $mensajeQR = $datos_persona->clave_unica;
        $qrCode = base64_encode(QrCode::format('svg')
            ->size(300)
            ->color(0, 0,  0)
            //->margin(1) // Margen adicional
            ->backgroundColor(255, 255, 255)
            ->errorCorrection('H')
            ->generate($mensajeQR));

        // Para abrir el blade vista del pdf con variables ya definidas
        $pdf = DomPDF::loadView(
            'Vigencia.PoblacionAsegurada.tapaHIstoriaClinicaPdf',
            compact(
                'datos_persona',
                'qrCode',
                'codigo_institucional',
                'acronimo_institucion',
                'clase_aportante',
                'id_institucion',
                'primera_institucion_nombre',
                'valor',
                'sigla_seguro_interior',
                'direccion',
                'nro_vivienda',
                'zona',
                'telefono',
                'codigo_institucional_titular',
                'persona_ben_tit',
                'nro_colspan'
            )
        )
            ->setPaper([0, 0, 610, 936], 'portrait'); //tamaño Oficio-> ancho 21.5cm = 610pt, y alto 33cm = 936pt


        $nombre_pdf = 'tapa_hc';
        // Para descargar pdf
        return $pdf->stream($nombre_pdf . '.pdf', [
            'Attachment' => false,
            'Content-Disposition' => 'inline'
        ]);
    }
}
