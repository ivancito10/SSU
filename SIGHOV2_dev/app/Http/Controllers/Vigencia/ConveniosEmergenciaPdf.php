<?php

namespace App\Http\Controllers\Vigencia;

use App\Http\Controllers\Controller;
use App\Models\Administracion\Persona;
use App\Models\Administracion\PersonalSsu;
use App\Models\Vigencia\Convenio;
use App\Models\Vigencia\ConvenioEspecialidad;
use App\Models\Vigencia\ConvenioServicio;
use App\Models\Afiliacion\TitularInstitucion;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\QRCodeService;

class ConveniosEmergenciaPdf extends Controller
{
    public function ConvenioEmergenciaPdf($id_conv_emergencia)
    {
        $ejemplo = 'adsfsdfsd';
        $hoy = Carbon::now()->format('d/m/Y');
        $año = Carbon::now()->format('Y');

        $id = $id_conv_emergencia;
        $convenio = Convenio::where('id', $id)
            ->with(['personaConvenio'])
            ->with(['usuarioCreador', 'usuarioCreador.personaUser'])
            ->with(['titularAutorizaConvenio', 'titularAutorizaConvenio.titularPersona'])
            ->first();

        $diasValidez = (Carbon::parse($convenio->fecha_fin)->diffInDays(Carbon::parse($convenio->fecha_inicio))) + 1;
        // dd($convenio);
        $matricula_seguro = $convenio->titularAutorizaConvenio->titularPersona->matricula_seguro;
        $carnet = $convenio->titularAutorizaConvenio->titularPersona->ci;
        //Para sacar la institucion
        $titularInstitucion = TitularInstitucion::where('id_titular', $convenio->id_titular_autoriza)->with(['titularInstitucionConInstitucion', 'titularInstitucionConInstitucion.codigosInstitucion'])
            ->orderBy('id', 'asc')->first();
        if ($titularInstitucion) {
            $institucional = true;
            $nombre_institucion = $titularInstitucion->titularInstitucionConInstitucion->nombre;
            if (!empty($titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo)) {
                $matricula_seguro = $convenio->titularAutorizaConvenio->titularPersona->matricula_seguro . ' - ' . $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo
                    .  $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->tipo_titular;
            }
        } else {
            $institucional = false;
            $nombre_institucion = 'APORTANTE VOLUNTARIO';
        }


        $especialidades = ConvenioEspecialidad::where('id_convenio', $id)
            ->with(['especialidadConvenioEspecialidad'])
            ->get();
        $servicios = ConvenioServicio::where('id_convenio', $id)
            ->with(['servicioConvenioServicio'])
            ->get();
        $codigos = QRCodeService::generarQR();
        $cod_seguridad = $codigos['cod_seguridad'];
        $qrCode = $codigos['qrCode'];
        // Para abrir el blade vista del pdf con variables ya definidas


        $id_personalSsu = $convenio->id_personal_ssu;
        $personal = PersonalSsu::find($id_personalSsu);
        $idpersonaPersonal = $personal->id_persona;
        $cargoPersonal = $personal->cargo;
        $personaPersonal = Persona::find($idpersonaPersonal);
        if ($personaPersonal->p_apellido == null) {
            $nombrePersonal = $personaPersonal->s_apellido . ' ' . $personaPersonal->nombres;
        } elseif ($personaPersonal->s_apellido == null) {
            $nombrePersonal = $personaPersonal->p_apellido . ' ' . $personaPersonal->nombres;
        } else {
            $nombrePersonal = $personaPersonal->p_apellido . ' ' . $personaPersonal->s_apellido . ' ' . $personaPersonal->nombres;
        }



        $pdf = PDF::loadView(
            'Vigencia.Convenios.Emergencias.pdfConvenioEmergencia',
            compact(
                'ejemplo',
                'id',
                'año',
                'hoy',
                'convenio',
                'especialidades',
                'matricula_seguro',
                'carnet',
                'institucional',
                'nombrePersonal',
                'cargoPersonal',
                'servicios',
                'qrCode',
                'diasValidez',
                'nombre_institucion',
            )
        )
            ->setPaper('letter', 'portrait');
        $id_persona = $convenio->id_persona;
        QRCodeService::registrarPDF($pdf, $cod_seguridad, $id_persona, 13, 'VIGENCIA DE DERECHOS');


        $nombre_pdf = 'convenio_interior_carnet';
        // Para abrir pdf stream
        return $pdf->stream($nombre_pdf . '.pdf', [
            'Attachment' => false,
            'Content-Disposition' => 'inline'
        ]);
    }
}
