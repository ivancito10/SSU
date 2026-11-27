<?php

namespace App\Jobs;

use App\Services\InteroperabilidadAsuss\BajaEstudianteAsussService;
use App\Services\InteroperabilidadAsuss\LoginAsussService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcesarBajaEstudianteAsussJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $backoff = 10;

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
            $bajaTitularesService = new BajaEstudianteAsussService($loginService);

            $bajaTitularesService->bajaEstudianteAsuss($this->datos);
        } catch (\Throwable $th) {
            Log::error('Fallo la BAJA de ESTUDIANTES al ASUSS en segundo plano', [
                'error' => $th->getMessage(),
                'id_persona' => $this->datos['id_persona'] ?? null,
                'trace' => $th->getTraceAsString()
            ]);
        }
    }
}
