<?php

namespace App\Livewire\Plataforma\GestionarAgenda;

use App\Models\Plataforma\DiasHabilitadosAgenda;
use App\Models\Plataforma\ServicioPlataforma;
use App\Models\Plataforma\CantidadHorario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class GestionarAgendaEdit extends Component
{
    #[Validate]
    public $servicios, $nro_dias, $estado, $servicioNombre, $hoy, $hoy_boolean, $id_servicio;
    public $id_gestion_agenda, $cantidad_turnos, $cantidadActualturnos;
    public $turnos = [];
    #[Validate]
    public $hora_inicio, $hora_fin, $porcentaje;
    public function render()
    {
        $this->mostrarGestionAgenda();
        return view('livewire.Plataforma.gestionar-agenda.gestionar-agenda-edit');
    }
    public function atras()
    {
        return redirect()->route('plataforma.gestionar_agenda.index');
    }
    public function mount()
    {
        $this->servicios = ServicioPlataforma::orderBy('id', 'asc')
            ->whereNot('id', 4)->get();

        // $this->ConvertirAArray();
    }

    public function mostrarGestionAgenda()
    {
        $gestionAgenda = DiasHabilitadosAgenda::with('servicioPlataforma')->find($this->id_gestion_agenda);
        if ($gestionAgenda) {
            $this->id_servicio = $gestionAgenda->id_servicio_plataforma;
            $this->servicioNombre = $gestionAgenda->servicioPlataforma->nombre_servicio;
            $this->nro_dias = $gestionAgenda->nro_dias;
            $this->hoy_boolean = $gestionAgenda->hoy;
            $this->hoy = $gestionAgenda->hoy ? 'HOY' : 'MAÑANA';
            $this->estado = $gestionAgenda->estado;
            $this->cantidad_turnos = $gestionAgenda->cantidad_turnos;
            $this->turnos = CantidadHorario::where('id_dias_habilitados_agenda', $gestionAgenda->id)
                ->where('eliminado', false)
                ->orderBy('id', 'asc')
                ->get();
        }
    }

    public function rules()
    {
        return [
            'id_servicio' => 'required',
            'nro_dias' => 'required',
            'turnos.*.hora_inicio' => 'required|date_format:H:i',
            'turnos.*.hora_fin' => 'required|date_format:H:i|after:turnos.*.hora_inicio',
            'turnos.*.porcentaje' => 'required|numeric|max:100|min:1',
        ];
    }

    #[On('editandoGestionAgenda')]
    public function editandoGestionAgenda()
    {
        $this->cantidadActualturnos = collect($this->turnos)->where('eliminado', false)->count();
        // dd($this->cantidadActualturnos);
        try {
            $gestionActual = DiasHabilitadosAgenda::find($this->id_gestion_agenda);
            if ($gestionActual) {
                $gestionActual->nro_dias = $this->nro_dias;
                $gestionActual->id_servicio_plataforma = $this->id_servicio;
                $gestionActual->hoy = $this->hoy_boolean;
                $gestionActual->cantidad_turnos = $this->cantidadActualturnos;
                $gestionActual->id_user_updated = auth()->user()->id;
                $gestionActual->save();
                $turnosActuales = CantidadHorario::where('id_dias_habilitados_agenda', $gestionActual->id)
                    ->get();
                foreach ($this->turnos as $key => $turno) {
                    if (isset($turno['id'])) {
                        $turnoActual = $turnosActuales->where('id', $turno['id'])->first();
                        if ($turnoActual) {
                            $turnoActual->update([
                                'hora_inicio' => $turno['hora_inicio'],
                                'hora_fin' => $turno['hora_fin'],
                                'porcentaje' => $turno['porcentaje'],
                                'id_user_updated' => auth()->user()->id,
                            ]);
                        }
                        //else {
                        //    CantidadHorario::create([
                        //        'id_dias_habilitados_agenda' => $gestionActual->id,
                        //        'estado' => true,
                        //        'hora_inicio' => $turno['hora_inicio'],
                        //        'hora_fin' => $turno['hora_fin'],
                        //        'porcentaje' => $turno['porcentaje'],
                        //        'id_user_created' => auth()->user()->id,
                        //    ]);
                        //}
                    }
                }

                $this->dispatch('gestionAgendaEdicionExitoso');
                return redirect()->route('plataforma.gestionar_agenda.index');
            }
        } catch (\Throwable $e) {
            $this->dispatch('gestionAgendaErrorEdicion');

            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error gestion de agenda edit", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }
    #[On('eliminarTurno')]
    public function eliminarTurno($index)
    {
        try {
            if (isset($this->turnos[$index])) {
                $turnoId = $this->turnos[$index]['id'] ?? null;
                //unset($this->turnos[$index]); // elimino el indice
                // $this->turnos = array_values($this->turnos); // vuelve a contar
                if ($turnoId) {
                    $cantidadHorario = CantidadHorario::find($turnoId);
                    if ($cantidadHorario) {
                        $cantidadHorario->update([
                            'eliminado' => true,
                        ]);
                    }
                }
            }
        } catch (\Throwable $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error en buscar eliminar turno", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }
    public function ConvertirAArray()
    {
        $this->turnos = CantidadHorario::where('id_dias_habilitados_agenda', $this->id_gestion_agenda)->get()->toArray();
    }
    public function getTurnosProperty()
    {
        return CantidadHorario::where('eliminado', false)->get();
    }


    //  public function updatedCantidadTurnos($value)
    //  {
    //      $this->cantidad_turnos = (int) $value;
    //      $cantidadActual = count($this->turnos);
    //      if ($cantidadActual < $value) {
    //          for ($i = $cantidadActual; $i < $value; $i++) {
    //              $this->turnos[] = [
    //                  'hora_inicio' => '',
    //                  'hora_fin' => '',
    //                  'porcentaje' => '',
    //              ];
    //          }
    //      } elseif ($value < $cantidadActual) {
    //          $this->turnos = array_slice($this->turnos, 0, $value);
    //      }
    // $this->mostrarInputs();
    //  }
    // public function mostrarInputs()
    // {
    //     $this->turnos = array_fill(0, $this->cantidad_turnos, ['hora_inicio' => null, 'hora_fin' => null]);
    // }

    public function cancelarGestionIngreso()
    {

        return redirect()->route('plataforma.gestionar_agenda.index');
    }
}
