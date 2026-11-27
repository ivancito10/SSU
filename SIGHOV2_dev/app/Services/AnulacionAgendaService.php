<?php

namespace App\Services;

use App\Models\Plataforma\AnulacionAgenda;
use Carbon\Carbon;

class AnulacionAgendaService
{

    public function anularAgenda($agenda)
    {
        // $habilitado = true;
        AnulacionAgenda::create([
            'id_persona' => $agenda->id_persona,
            'fecha_agenda' => $agenda->fecha_agenda,
            'id_asignacion' => $agenda->id_asignacion_horario,
            'nombre_equipo' => $agenda->nombre_equipo,
            'ip' => $agenda->ip,
            'id_agenda' => $agenda->id,
            'estado' => true,
            'id_user_created' => auth()->user()->id,
            // 'id_user_updated',
            // 'create_ad',
            // 'update_ad',
        ]);
        // return $habilitado;
    }
    public function bloquear_anular_agenda($agenda)
    {
        $fechaHoy = Carbon::now()->format('Y-m-d');
        // dd($agenda->id_persona);
        // Cuántas anulaciones ya hizo hoy
        $conteo = AnulacionAgenda::where('id_persona', $agenda->id_persona)
            ->whereDate('created_at', $fechaHoy)
            ->count();
        // dd($conteo);
        // Si ya anuló 3 o más, bloquear
        if ($conteo >= 3) {
            return [
                'estado_anulacion' => true,
                'mensaje' => 'No puede anular más de 3 agendas en el día'
            ];
        }

        // Si aún tiene disponibles, permitir y registrar anulación
        $this->anularAgenda($agenda);

        $restantes = 2 - $conteo; // porque ya acaba de usar una

        return [
            'estado_anulacion' => false,
            'mensaje' => "Tiene {$restantes} anulaciones de agenda disponibles hoy"
        ];
    }
}
