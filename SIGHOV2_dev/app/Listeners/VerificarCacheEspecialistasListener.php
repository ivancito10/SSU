<?php

namespace App\Listeners;

use App\Events\VerificarCachePoblacionAsegurada;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use App\Models\Administracion\Persona;
use Illuminate\Support\Str;
use Carbon\Carbon;
use  App\Models\Plataforma\Especialista;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class VerificarCacheEspecialistasListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(VerificarCachePoblacionAsegurada $event): void
    {
        //
        $key = $event->cacheKey3;
        Log::info('Confirmación, Cache Creado de : ' . $key . ' a las: ' . Carbon::now());
        $array_collect = Cache::rememberForever($key, function () {
            $fechaActual = Carbon::now()->format('Y-m-d');

            $especialistas = Especialista::with([
                'personaEspecialista',
                'especialidadEspecialista',
                'suspensionVacacionEspecialista',
                'especialistaHabilitadoEspecialista'
            ])
                ->where('estado', true)
                /*->where(function ($quericito) {
                    $quericito->whereDoesntHave('suspensionVacacionEspecialista')
                        ->orWhereHas('suspensionVacacionEspecialista', function ($otroQuery) {
                            $otroQuery->whereNot('estado', 'VACACION ACTIVA');
                        });
                })*/
                ->get();

            $listaEspecialistas = $especialistas->filter(function ($especialista) use ($fechaActual) {
                return $especialista->especialistaHabilitadoEspecialista->filter(function ($servicio) use ($fechaActual) {
                    return $servicio->id_servicio === 2 && $servicio->estado &&
                        ($servicio->permanente || ($servicio->fecha_inicio <= $fechaActual && $servicio->fecha_fin >= $fechaActual));
                })->isNotEmpty();
            })->map(function ($especialista) {
                $foto = $this->obtenerFotoEspecialista($especialista->foto);
                $persona = $especialista->personaEspecialista;
                $especialidad = $especialista->especialidadEspecialista;

                $serviciosHabilitados = $especialista->especialistaHabilitadoEspecialista
                    ->filter(function ($servicio) {
                        return $servicio->id_servicio === 2 && $servicio->estado;
                    })
                    ->map(function ($servicio) {
                        return [
                            'id_especialista_habilitado_servicio' => $servicio->id,
                            'fecha_inicio' => $servicio->fecha_inicio,
                            'fecha_fin' => $servicio->fecha_fin,
                            'permanente' => $servicio->permanente,
                            'id_dia' => $servicio->id_dia,
                        ];
                    })
                    ->values()
                    ->all();
                
                $vacacionesEspecialista = $especialista->suspensionVacacionEspecialista
                    ->map(function ($vacacion) {
                        return [
                            'id_suspension_vacacion' => $vacacion->id,
                            'id_especialista_suspension_vacacion' => $vacacion->id_especialista,
                            'estado' => $vacacion->estado,
                            'fecha_inicio_vacacion' => $vacacion->fecha_inicio,
                            'fecha_fin_vacacion' => $vacacion->fecha_fin
                        ];
                    })
                    ->values()
                    ->all();

                return [
                    'id_especialista' => $especialista->id,
                    'nombres' => $persona->nombres,
                    'p_apellido' => $persona->p_apellido,
                    's_apellido' => $persona->s_apellido,
                    'carnet' => $persona->complemento !== null ? $persona->ci . ' - ' . $persona->complemento : $persona->ci,
                    'sexo' => $persona->sexo,
                    'id_especialidad' => $especialidad->id,
                    'especialidad' => $especialidad->especialidad,
                    'afiliados' => $especialista->afiliados,
                    'estudiantes' => $especialista->estudiantes,
                    'convenios' => $especialista->convenios,
                    'grado_academico' => $especialista->grado_academico,
                    'fecha_contrato_inicio' => $especialista->fecha_contrato_inicio,
                    'fecha_contrato_fin' => $especialista->fecha_contrato_fin,
                    'permanente' => $especialista->permanente,
                    'foto' => $foto,
                    'especialistaHabilitado' => $serviciosHabilitados,
                    'vacacionesEspecialista' => $vacacionesEspecialista, 
                ];
            })->values()->all();

            return $listaEspecialistas;
        });
    }

    private function obtenerFotoEspecialista($idFoto)
    {
        try {
            if ($idFoto) {
                $path = 'Plataforma/Especialista/Fotos/' . $idFoto;

                $imageData = Storage::disk('ftp')->get($path);
                $foto = base64_encode($imageData);
                if ($foto === '') {
                    return null;
                } else {
                    return $foto;
                }
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            return null;
        }
    }
}
