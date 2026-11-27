<?php

namespace App\Livewire\ArchivoClinico\AgendasDiarias;

use App\Models\Administracion\Especialidad;
use App\Models\Plataforma\Agenda;
use App\Models\Plataforma\AsignacionHorario;
use App\Models\Plataforma\Especialista;
use App\Models\Plataforma\Horario;
use Carbon\Carbon;
use Livewire\Component;
use DB;

class AgendaDiariaMain extends Component
{
    public $fecha;
    public $especialidades;
    public $idEspecialidad;
    public $especialistas = [];
    public $idEspecialista;
    public $mostrarBoton = 0;
    public $turnos = [];
    public $idTurno;
    public $mostrarturnos = 0;
    public $idAsignacionHorario;
    public function render()
    {
        return view('livewire.archivo-clinico.agendas-diarias.agenda-diaria-main');
    }
    public function mount()
    {
        $this->fecha = Carbon::now()->format('Y-m-d');
        $this->especialidades = Especialidad::orderBy('especialidad', 'asc')->where('estado', 'true')->get();
        //  dd($this->especialidades,$this->fecha);
    }

    public function seleccionFecha()
    {
        $this->dispatch('FechaSeleccionada', fecha: $this->fecha);
        $this->reset('idEspecialidad');
        $this->reset('idEspecialista');
        $this->reset('mostrarBoton');
        $this->reset('mostrarturnos');
        $this->reset('idTurno');
    }

    public function seleccionEspecialidad()
    {
        $this->especialistas = Especialista::with('personaEspecialista')->where('id_especialidad', $this->idEspecialidad)
            ->where('estado', 'true')->get();
        // dd($this->especialistas);
        // dd($this->fecha,$this->idEspecialidad);
    }
    public function seleccionEspecialista()
    {
        // dd("asd");
        $this->reset('mostrarturnos');
        $this->reset('idTurno');
        // dd($this->fecha, $this->idEspecialidad, $this->idEspecialista);
        $fecha = $this->fecha ? Carbon::parse($this->fecha)->toDateString() : now()->toDateString();
        $diaSemanaNumerico = Carbon::parse($this->fecha)->dayOfWeek;
        // Trae turnos (distintos) del especialista
        $this->turnos = Horario::query()
            ->from('plataforma.horario as h')
            ->join('plataforma.asignacion_horario as ah', 'ah.id_horario', '=', 'h.id')
            ->where('ah.id_especialista', $this->idEspecialista)
            ->where('h.estado', true)
            ->where('ah.estado', true)
            ->where('ah.id_dia', $diaSemanaNumerico)
            ->where(function ($q) use ($fecha) {
                $q->where('ah.permanente', true)
                    ->orWhere(function ($q2) use ($fecha) {
                        $q2->whereDate('ah.fecha_inicio', '<=', $fecha)
                            ->where(function ($qq) use ($fecha) {
                                $qq->whereNull('ah.fecha_fin')
                                    ->orWhereDate('ah.fecha_fin', '>=', $fecha);
                            });
                    });
            })
            ->distinct()
            ->orderBy('h.hora_inicio')
            ->get(['h.id', 'h.turno', 'h.hora_inicio'])
            ->map(fn($r) => ['id' => $r->id, 'turno' => $r->turno])
            ->unique('id')
            ->values()
            ->all();
        if (count($this->turnos) === 1) {
            $this->idTurno = $this->turnos[0]['id'];
            // dd($this->idTurno,"un turno");
            $this->mostrarBoton = 1;
        } elseif (count($this->turnos) === 0) {

            // dd($this->turnos);
            $this->dispatch('NoHayHorarios');
            $this->mostrarBoton = 0;
        } else {
            // dd($this->turnos);
            $this->mostrarturnos = 1;
            $this->mostrarBoton = 0;
        }
        // foreach($this->turnos as $turno){
        //     dd($turno['turno']);
        // }
        // dd($this->turnos);

    }

    public function seleccionTurno()
    {
        $this->mostrarBoton = 1;
    }

    public function imprimir_boucher_mañana()
    {
        // dd(
        //     "entro al boton",
        //     $this->fecha,
        //     $this->idEspecialidad,
        //     $this->idEspecialista,
        //     $this->idTurno
        // );
        $fecha_hoy = Carbon::parse($this->fecha)->format('Y-m-d');
        $date = Carbon::parse($this->fecha);
        $diaSemanaNumerico = $date->dayOfWeek;
        $idEsp = $this->idEspecialista;
        $datosAsignacionHorario = AsignacionHorario::where('id_horario', $this->idTurno)->where('id_especialista', $this->idEspecialista)
            ->where('estado', 'true')->where('id_dia', $diaSemanaNumerico)->first();
        $this->idAsignacionHorario = $datosAsignacionHorario->id;
        //inicio

        $consulta = Agenda::where('fecha_agenda', $this->fecha)
            ->whereNot('anulacion_ficha', true)
            ->where('id_asignacion_horario', $this->idAsignacionHorario)
            ->with([
                'asignacionHorarioAgenda',
                'asignacionHorarioAgenda.horarioAsignacionHorario',
                'asignacionHorarioAgenda.consultorioAsignacionHorario',
                'servicioPlataformaAgenda',
                'personaAgenda',
                'personaAgenda.tipoAseguradoPersona'
            ])
            ->orderBy('hora_agenda', 'asc')
            ->get();
            // dd($consulta);
        //fin
        if (count($consulta) === 0) {
            $this->dispatch('NoExistenAgendas');
        } else {
            $this->dispatch('BoucherGenerado');
            $this->js('window.open("' . route('pdf.boucher.agenda', ['idEsp' => $this->idAsignacionHorario, 'fecha' => $fecha_hoy]) . '", "_blank")');
            
            $this->skipRender();
        }
    }
    public function verificarExistenciaAgendaBoucher($idAsignacionHorario, $fecha)
    {
        $fecha_hoy = Carbon::parse($fecha)->format('Y-m-d');
        $agendados = Agenda::where('fecha_agenda', $fecha_hoy)
            ->whereNot('anulacion_ficha', true)
            ->where('id_asignacion_horario', $idAsignacionHorario)
            ->with([
                'asignacionHorarioAgenda',
                'asignacionHorarioAgenda.horarioAsignacionHorario',
                'asignacionHorarioAgenda.consultorioAsignacionHorario',
                'servicioPlataformaAgenda',
                'personaAgenda',
                'personaAgenda.tipoAseguradoPersona',
                'suspensionHorarioAgenda'
            ])
            ->orderby('hora_agenda', 'asc')
            ->get();
        if (count($agendados) > 0) {
            $datos_especialista = Especialista::with('personaEspecialista', 'especialidadEspecialista')
                ->where('id', $agendados[0]->asignacionHorarioAgenda->id_especialista)
                ->first();
        }
        return count($agendados) > 0 && $datos_especialista;
    }
}
