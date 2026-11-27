<?php

namespace App\Jobs;

use App\Models\Administracion\Persona;
use App\Models\Vigencia\HistorialAsegurado;
use App\Models\Vigencia\Preafiliacion;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ActualizarPreafiliaciones implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $preafiliacionesActualizar = Preafiliacion::where('fecha_fin', '<', Carbon::now()->format('Y-m-d'))
            ->where('estado', true)
            ->get();

        foreach ($preafiliacionesActualizar as $preafiliacionActualizar) {

            $personaActualizar = Persona::where('id', $preafiliacionActualizar->id_persona)
                ->whereIn('id_tipo_asegurado', [14, 15])
                ->first();

            if ($personaActualizar) {
                $personaActualizar->id_tipo_asegurado = 0;
                $personaActualizar->afiliado = false;
                //$personaActualizar->id_user_updated = auth()->user()->id;
                $personaActualizar->save();

                $existeHistorialAsegurado = HistorialAsegurado::where('id_persona', $personaActualizar->id)
                    ->where('id_corresponde', $preafiliacionActualizar->id)
                    ->whereIn('id_tipo_asegurado', [14, 15])
                    ->orderBy('id', 'desc')
                    ->first();

                if ($existeHistorialAsegurado) {
                    $existeHistorialAsegurado->estado_vigencia = 'NO VIGENTE';
                    if ($existeHistorialAsegurado->fecha_baja == null) {
                        $existeHistorialAsegurado->fecha_baja = Carbon::now()->format('Y-m-d');
                    }
                    //$existeHistorialAsegurado->id_user_updated = auth()->user()->id;
                    $existeHistorialAsegurado->save();
                }
            }

            $preafiliacionActualizar->estado = false;
            //$preafiliacionActualizar->id_user_updated = auth()->user()->id;
            $preafiliacionActualizar->save();
            Log::info('Actualización baja de preafiliación correspondiente al id_preafiliacion = ' . $preafiliacionActualizar->id . ' y a la persona 
                con id_persona = ' . $preafiliacionActualizar->id_persona . ': Evento ActualizarPreafiliaciones lanzado automáticamente a las: ' . Carbon::now());
        }
    }
}
