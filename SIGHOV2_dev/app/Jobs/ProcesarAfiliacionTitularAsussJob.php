<?php

namespace App\Jobs;

use App\Services\InteroperabilidadAsuss\AfiliacionTitularAsussService;
use App\Services\InteroperabilidadAsuss\LoginAsussService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcesarAfiliacionTitularAsussJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3; //Veces que el job intentará volver a ejecutarse si falla

    public int $backoff = 10; //segundos de espera antes de reintentar

    public int $timeout = 180;

    protected array $datos;

    /**
     * Create a new job instance.
     */
    public function __construct(array $datos)
    {
        $this->datos = $datos;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $loginService = new LoginAsussService();
            $afiliacionTitularesService = new AfiliacionTitularAsussService($loginService);

            $afiliacionTitularesService->afiliarTitularAsuss($this->datos);
        } catch (\Throwable $th) {
            Log::error('Fallo el registro de TITULARES al ASUSS en segundo plano', [
                'error' => $th->getMessage(),
                'id_persona' => $this->datos['id_persona'] ?? null,
                'trace' => $th->getTraceAsString()
            ]);
        }                       
    }
}
