<?php

namespace App\Livewire\Plataforma\GestionarAgenda;

use App\Models\Plataforma\DiasHabilitadosAgenda;
use App\Models\Plataforma\CantidadHorario;
use Livewire\Component;
use App\Models\Plataforma\ServicioPlataforma;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Validate;

class GestionarAgendaRegister extends Component
{
    #[Validate]
    public $servicios, $nro_dias, $estado, $id_servicio;
    public $hoy_boolean = true;
    public $cantidad_turno, $turnos = [];
    #[Validate]
    public $hora_inicio, $hora_fin, $porcentaje;
    public function render()
    {

        return view('livewire.Plataforma.gestionar-agenda.gestionar-agenda-register');
    }
    public function mount()
    {
        $this->mostrarInputs();
        $this->servicios = ServicioPlataforma::orderBy('id', 'asc')
            ->whereNot('id', 4)->get();
    }
    public function updatedCantidadTurno($value)
    {
        $this->cantidad_turno = (int) $value;
        $this->mostrarInputs();
    }

    public function mostrarInputs()
    {
        $this->turnos = array_fill(0, $this->cantidad_turno, ['hora_inicio' => null, 'hora_fin' => null]);
    }

    public function rules()
    {
        return [
            'id_servicio' => 'required',
            'nro_dias' => 'required',
            'cantidad_turno' => 'required',
            'turnos.*.hora_inicio' => 'required|date_format:H:i',
            'turnos.*.hora_fin' => 'required|date_format:H:i|after:turnos.*.hora_inicio',
            'turnos.*.porcentaje' => 'required|numeric|max:100|min:1',
        ];
    }

    #[On('guardarGestion')]
    public function guardarGestion()
    {
        $this->validate();
        DB::beginTransaction();
        try {
            $gestionExiste = DiasHabilitadosAgenda::where('id_servicio_plataforma', $this->id_servicio)
                //->where('nro_dias', $this->nro_dias)
                ->where('estado', true)
                ->first();
            if ($gestionExiste) {
                $this->dispatch('gestionAgendaYaRegistrado');
                return;
            } else {
                $servicioRegistrado = DiasHabilitadosAgenda::create([
                    'id_servicio_plataforma' => $this->id_servicio,
                    'estado' => true,
                    'hoy' => $this->hoy_boolean,
                    'nro_dias' => $this->nro_dias,
                    'cantidad_turnos' => $this->cantidad_turno,
                    'id_user_created' => auth()->user()->id,
                ]);
                if ($servicioRegistrado) {
                    for ($i = 0; $i < $this->cantidad_turno; $i++) {
                        CantidadHorario::create([
                            'id_dias_habilitados_agenda' => $servicioRegistrado->id,
                            'estado' => true,
                            'eliminado' => false,
                            'hora_inicio' => $this->turnos[$i]['hora_inicio'],
                            'hora_fin' => $this->turnos[$i]['hora_fin'],
                            'porcentaje' => $this->turnos[$i]['porcentaje'],
                            'id_user_created' => auth()->user()->id,
                        ]);
                    }
                }
                $this->dispatch('gestionAgendaExitoso');
                DB::commit();
                // $this->resetearVariables();
                return redirect()->route('plataforma.gestionar_agenda.index');
            }
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->dispatch('gestionAgendaError');

            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error al guardarGestion", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }
    public function resetearVariables()
    {
        $this->id_servicio = null;
        $this->nro_dias = null;
        $this->cantidad_turno = null;
        $this->hora_inicio =  null;
        $this->hora_fin = null;
        $this->porcentaje = null;
        $this->turnos = [];
    }
    public function atras()
    {
        return redirect()->route('plataforma.gestionar_agenda.index');
    }

    public function cancelarGestionIngreso()
    {

        return redirect()->route('plataforma.gestionar_agenda.index');
    }
}
