<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Afiliacion\Beneficiario;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcesosAutomatizadosAfiliacion implements ShouldQueue
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
        $this->actualizarBebes();
    }

    public function actualizarBebes()
    {
        $beneficiarios_bebes = [];
        $fecha_actual = Carbon::now()->subYear();

        $beneficiarios_bebes = Beneficiario::with('beneficiarioPersona')
            ->whereHas('beneficiarioPersona', function ($query) use ($fecha_actual) {
                $query->where('id_tipo_asegurado', 7)
                    ->whereDate('fecha_nacimiento', '<=', $fecha_actual);
            })
            ->where('estado', true)
            ->where('vigencia', '=', 'VIGENTE')
            ->get();
        // ahora se actualiza
        foreach ($beneficiarios_bebes as $beneficiario) {
            $beneficiario->beneficiarioPersona->update(['id_tipo_asegurado' => 4]);
        }
        // dd($beneficiarios_bebes);
    }
}
