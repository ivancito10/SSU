<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Events\VerificarCachePoblacionAsegurada;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;

class EjecutarEventoAgendaWebCache implements ShouldQueue
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
        //Artisan::call('cache:clear');
        //aca lanzamos el evento para que se ejecute el listener de la agenda Web
        Log::info('Confirmación, Evento VerificarCachePoblacionAsegurada lanzado automáticamente a las: ' . Carbon::now());
        event(new VerificarCachePoblacionAsegurada('poblacion_asegurada', 'especialidades', 'especialistas', 'horarios'));
    }
}
