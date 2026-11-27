<?php

namespace App\Http\Controllers\Plataforma;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\DummyPrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;

use App\Models\Plataforma\Agenda;
use App\Services\ServicioPersonaInstitucion;
use Carbon\Carbon;
use Exception;


class PrintController extends Controller
{
    public function impresionAgendaCiosko($idAgenda)
    {
        try {
            $agenda = Agenda::with([
                'asignacionHorarioAgenda.consultorioAsignacionHorario.consultorioSedes',
                'asignacionHorarioAgenda.especialistaAsignacionHorario.personaEspecialista',
                'asignacionHorarioAgenda.especialistaAsignacionHorario.especialidadEspecialista',
                'personaAgenda'
            ])
                ->find($idAgenda);

            if (!$agenda) {
                return response()->json([
                    'success' => false,
                    'message' => 'Agenda no encontrada.',
                ], 404);
            }

            $nombrePaciente = implode(' ', array_filter([
                $agenda->personaAgenda->p_apellido,
                $agenda->personaAgenda->s_apellido,
                $agenda->personaAgenda->nombres
            ]));
            /*
            $dato_inst = new ServicioPersonaInstitucion();
            $persona_institucion = $dato_inst->personaInstitucion($$agenda->id_persona);

            if (is_array($persona_institucion) && !empty($persona_institucion['instituciones'])) {
                $instituciones = $persona_institucion['instituciones'];
                $codigo_institucional = $instituciones[0]['codigo_institucional'] ?? '';
            } else {
                $codigo_institucional = '';
            }*/

            $sexoPaciente = $agenda->personaAgenda->sexo === 'F' ? true : false;

            $matriculaPaciente = $agenda->personaAgenda->matricula_seguro;

            $especialidad = $agenda->asignacionHorarioAgenda->especialistaAsignacionHorario->especialidadEspecialista->especialidad;

            $especialista = implode(' ', array_filter([
                $agenda->asignacionHorarioAgenda->especialistaAsignacionHorario->personaEspecialista->p_apellido,
                $agenda->asignacionHorarioAgenda->especialistaAsignacionHorario->personaEspecialista->s_apellido,
                $agenda->asignacionHorarioAgenda->especialistaAsignacionHorario->personaEspecialista->nombres,
            ]));

            $gradoAcademicoEspecialista = $agenda->asignacionHorarioAgenda->especialistaAsignacionHorario->grado_academico === 'DOCTORA'
                ? 'DRA.'
                : ($agenda->asignacionHorarioAgenda->especialistaAsignacionHorario->grado_academico === 'DOCTOR' ? 'DR.' : 'LIC.');
            $fechaAgenda = Carbon::parse($agenda->fecha_agenda)->format('d/m/Y');
            $horaAgenda = Carbon::parse($agenda->hora_agenda)->format('H:i');
            $consutorio = $agenda->asignacionHorarioAgenda->consultorioAsignacionHorario->numero_consultorio . ' - ' .
                $agenda->asignacionHorarioAgenda->consultorioAsignacionHorario->consultorioSedes->piso;
            $ubicacion = $agenda->asignacionHorarioAgenda->consultorioAsignacionHorario->consultorioSedes->ubicacion;


            /*$nombreImpresora = "POS-X Thermal Printer";
            $connector = new WindowsPrintConnector($nombreImpresora);
            $impresora = new Printer($connector);*/

            $connector = new DummyPrintConnector();
            $impresora = new Printer($connector);

            $rutaImagen = public_path('images/logo_fondo_blanco_hd_redimensionado.jpg');
            //dd($rutaImagen);
            $logo = EscposImage::load($rutaImagen, false);
            $impresora->setJustification(Printer::JUSTIFY_CENTER);
            //*$impresora->bitImage($logo);
            $impresora->setTextSize(2, 2);
            $impresora->text("SigHov2");
            $impresora->feed(1);
            $impresora->setTextSize(2, 1);
            $impresora->text("-- Agenda - Kiosco --");
            $impresora->feed(1);
            $impresora->setTextSize(1, 1);
            $impresora->text("========================================");
            $impresora->feed(1);
            $impresora->setJustification(Printer::JUSTIFY_LEFT);
            $impresora->text("Paciente:");
            $impresora->feed(1);
            $impresora->setEmphasis(true);
            $impresora->text($nombrePaciente);
            $impresora->setEmphasis(false);
            $impresora->feed(1);
            $impresora->text("Matrícula: " . $matriculaPaciente);
            $impresora->feed(1);
            $impresora->text("Especialidad: " . $especialidad);
            $impresora->feed(1);
            $impresora->text("Especialista:");
            $impresora->feed(1);
            $impresora->text($gradoAcademicoEspecialista . ' ' . $especialista);
            $impresora->feed(1);
            $impresora->text("Fecha: " . $fechaAgenda);
            $impresora->feed(1);
            $impresora->text("Hora: " . $horaAgenda);
            $impresora->feed(1);
            $impresora->text("Consultorio: " . $consutorio);
            $impresora->feed(1);
            $impresora->text($ubicacion);
            $impresora->feed(1);
            $impresora->setJustification(Printer::JUSTIFY_CENTER);
            $impresora->setEmphasis(true);
            //$impresora->setItalics(true); 
            if ($sexoPaciente) {
                $impresora->text("\"Estimada asegurada:");
            } else {
                $impresora->text("\"Estimado asegurado:");
            }
            $impresora->feed(1);
            $impresora->text("Una vez impresa esta ficha,");
            $impresora->feed(1);
            $impresora->text("debe dirigirse al área de Archivo Clínico para su atención\"");
            $impresora->setEmphasis(false);
            //$impresora->setItalics(false);
            $impresora->feed(1);
            $impresora->text("========================================");
            $impresora->feed(1);
            $impresora->setTextSize(1, 1);
            $impresora->text("© Derechos Reservados " . Carbon::now()->format('Y') . ' - SIGHO V2');
            $impresora->selectPrintMode();
            $impresora->feed(2);
            $impresora->cut();

            $escposData = $connector->getData();

            $base64EscposData = base64_encode($escposData);

            $impresora->close();

            return response()->json([
                'success' => true,
                'message' => 'Datos de impresion generados exitosamente',
                'escpos_data' => $base64EscposData,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al generar los datos de impresión: ' . $e->getMessage(),
            ], 500);
        }
    }
}
