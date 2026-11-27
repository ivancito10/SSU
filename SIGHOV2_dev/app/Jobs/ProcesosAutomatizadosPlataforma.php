<?php

namespace App\Jobs;

use App\Models\Plataforma\AsignacionHorario;
use App\Models\Plataforma\EspecialidadHabilitadoServicio;
use App\Models\Plataforma\Especialista;
use App\Models\Plataforma\EspecialistaHabilitadoServicio;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcesosAutomatizadosPlataforma implements ShouldQueue
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
        $this->actualizacionContratosEspecialistas();
        $this->actualizacionAsignacionHorarioEspecialista();
        $this->actualizacionEspecialidadHabilitadoServicio();
        $this->actualizacionEspecialistaHabilitadoServicio();
    }

    public function actualizacionContratosEspecialistas()
    {
        //$ayer = Carbon::now()->subDay()->format('Y-m-d');
        $hoy = Carbon::now()->format('Y-m-d');
        $especialistas = Especialista::where('fecha_contrato_fin', '<', $hoy)
            ->where('estado', true)
            ->get();
        //dd($especialistas);
        foreach ($especialistas as $key => $especialista) {
            $especialista->estado = false;
            $especialista->save();
        }
    }

    public function actualizacionAsignacionHorarioEspecialista()
    {
        $hoy = Carbon::now()->format('Y-m-d');
        $asignacionesHorarios = AsignacionHorario::whereNot('permanente', true)
            ->where('fecha_fin', '<', $hoy)
            ->where('estado', true)
            ->get();
        //dd($asignacionesHorarios);
        foreach ($asignacionesHorarios as $key => $asignacionHorario) {
            $asignacionHorario->estado = false;
            $asignacionHorario->save();
        }
    }

    public function actualizacionEspecialidadHabilitadoServicio()
    {
        $hoy = Carbon::now()->format('Y-m-d');
        $especialidadesHabilitadasServicios = EspecialidadHabilitadoServicio::whereNot('permanente', true)
            ->where('fecha_fin', '<', $hoy)
            ->where('estado', true)
            ->get();
        //dd($especialidadesHabilitadasServicios);
        foreach ($especialidadesHabilitadasServicios as $key => $especialidadHabilitadaServicio) {
            $especialidadHabilitadaServicio->estado = false;
            $especialidadHabilitadaServicio->save();
        }
    }

    public function actualizacionEspecialistaHabilitadoServicio()
    {
        $hoy = Carbon::now()->format('Y-m-d');
        $especialistasHabilitadosServicios = EspecialistaHabilitadoServicio::whereNot('permanente', true)
            ->where('fecha_fin', '<', $hoy)
            ->where('estado', true)
            ->get();
        //dd($especialistasHabilitadosServicios);
        foreach ($especialistasHabilitadosServicios as $key => $especialistaHabilitadoServicio) {
            $especialistaHabilitadoServicio->estado = false;
            $especialistaHabilitadoServicio->save();
        }
    }
}
