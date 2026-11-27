<?php

namespace App\Livewire\Plataforma\Agendamiento\Ecografia\SuspensionHorarioEcografia;

use App\Models\Plataforma\AgendaEcografia;
use App\Models\Plataforma\SuspensionHorarioEcografia;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class SuspensionHorarioTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'SuspensionHorariosEcografiaTable';
    public string $sortField = 'suspension_horario_ecografia.id';
    public string $sortDirection = 'desc';

    public function setUp(): array
    {
        return [
            Exportable::make('export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return SuspensionHorarioEcografia::query()
            ->join('plataforma.motivo as motivo', function ($motivo) {
                $motivo->on('motivo.id', '=', 'suspension_horario_ecografia.id_motivo');
            })
            ->join('plataforma.especialista as especialista', function ($especialista) {
                $especialista->on('especialista.id', '=', 'suspension_horario_ecografia.id_especialista');
            })
            ->join('administracion.persona as personaEspecialista', function ($personaEspecialista) {
                $personaEspecialista->on('personaEspecialista.id', '=', 'especialista.id_persona');
            })
            ->select(
                'plataforma.suspension_horario_ecografia.*',
                'plataforma.suspension_horario_ecografia.fecha_suspension',
                'motivo.nombre_motivo as nombre_motivo',
                'personaEspecialista.nombres as nombre_especialista',
                'personaEspecialista.p_apellido as p_apellido_especialista',
                'personaEspecialista.s_apellido as s_apellido_especialista'
            );
    }

    public function relationSearch(): array
    {
        return [
            'motivoSuspensionHorario' => ['nombre_motivo'],
            'especialistaSuspensionHorario.personaEspecialista' => ['nombres', 'p_apellido', 's_apellido'],
            // 'asignacionHorarioSuspensionHorario.horarioAsignacionHorario' => ['hora_inicio', 'hora_fin']
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('fecha_suspension')
            ->add('fecha_suspension_formatted', fn(SuspensionHorarioEcografia $model) => Carbon::parse($model->fecha_suspension)->format('d/m/Y'))
            ->add('observacion')
            ->add('especialista_formatted', function ($especialista) {
                return $especialista->p_apellido_especialista . ' ' . $especialista->s_apellido_especialista . ' ' . $especialista->nombre_especialista;
            })
            ->add('estado', function ($dish) {
                if ($dish->estado) {
                    return '<span title="SUSPENSIÓN ACTIVA"> <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="green" class="bi bi-check-circle" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                    <path d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05"/>
                    </svg></span>';
                } else {
                    return '<span title="SUSPENSIÓN INACTIVA" ><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="red" class="bi bi-x-circle" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                    </svg></span>';
                }
            })
            ->add('rango_horas_formatted', function ($rango) {
                $agendasSuspendidas = AgendaEcografia::where('id_suspension_horario_ecografia', $rango->id)
                    ->with(['asignacionHorarioAgenda.horarioAsignacionHorario'])
                    ->orderBy('hora_agenda', 'asc')
                    ->get();

                if ($agendasSuspendidas->count() === 0) {
                    return '';
                } elseif ($agendasSuspendidas->count() === 1) {
                    $unicaAgenda = $agendasSuspendidas->first();
                    return Carbon::parse($unicaAgenda->hora_agenda)->format('H:i') . ' - ' .
                        Carbon::parse($unicaAgenda->hora_agenda)->addMinutes($unicaAgenda->asignacionHorarioAgenda->horarioAsignacionHorario->intervalo)
                        ->format('H:i');
                } else {
                    $primeraAgenda = $agendasSuspendidas->first();
                    $ultimaAgenda = $agendasSuspendidas->last();
                    return Carbon::parse($primeraAgenda->hora_agenda)->format('H:i') . ' - ' .
                        Carbon::parse($ultimaAgenda->hora_agenda)->addMinutes($ultimaAgenda->asignacionHorarioAgenda->horarioAsignacionHorario->intervalo)
                        ->format('H:i');
                }
            })
            ->add('id_motivo')
            ->add('id_asignacion_horario')
            ->add('id_user_created')
            ->add('id_user_updated')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->index()
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center'),

            Column::make('TIPO SUSPENSIÓN',  'nombre_motivo')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable(),

            Column::make('FECHA SUSPENSIÓN', 'fecha_suspension_formatted', 'fecha_suspension')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable(),

            Column::make('RANGO SUSPENSIÓN', 'rango_horas_formatted')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center'),

            Column::make('ESPECIALISTA', 'especialista_formatted', 'p_apellido_especialista')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable(),

            Column::make('ESTADO', 'estado')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::action('OPCIONES')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
        ];
    }

    public function actions(SuspensionHorarioEcografia $row): array
    {
        $buttons = [];
        $buttons[] = Button::add('ver')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                </svg>')
            ->class('btn btn-blue')
            ->tooltip('VER SUSPENSION')
            ->dispatchTo('plataforma.agendamiento.ecografia.suspension-horario-ecografia.suspension-horario-main', 'verSuspension', ['data' => $row->id])
            ->can(allowed: auth()->user()->can('ver.suspension_ecografia'));

        if ($row->estado) {
            $buttons[] = Button::add('anular')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                    <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                    </svg>')
                ->class('btn btn-red')
                ->tooltip('ANULAR SUSPENSION')
                ->dispatchTo('plataforma.agendamiento.ecografia.suspension-horario-ecografia.suspension-horario-main', 'anularSuspension', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('desabilitar.suspension_ecografia'));
        }
        
        return $buttons;
    }
}
