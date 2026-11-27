<?php

namespace App\Livewire\Plataforma\Anulacion;

use App\Models\Plataforma\Agenda;
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


final class AnulacionTable extends PowerGridComponent
{
    use WithExport; 
    public string $tableName = 'AnulacionTables';
    public string $sortField = 'agenda.updated_at';
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
        return Agenda::query()
            ->join('administracion.persona as persona', function ($persona) {
                $persona->on('agenda.id_persona', '=', 'persona.id');
            })
            ->join('plataforma.asignacion_horario as asignacion', function ($asignacion) {
                $asignacion->on('asignacion.id', '=', 'agenda.id_asignacion_horario');
            })
            ->join('plataforma.especialista as especialista', function ($especialista) {
                $especialista->on('especialista.id', '=', 'asignacion.id_especialista');
            })
            ->join('administracion.persona as personaEsp', function ($personaEsp) {
                $personaEsp->on('especialista.id_persona', '=', 'personaEsp.id');
            })
            ->join('administracion.especialidad  as especialidad', function ($especialidad) {
                $especialidad->on('especialista.id_especialidad', '=', 'especialidad.id');
            })
            ->select(
                'agenda.id',
                'agenda.hora_agenda',
                'agenda.fecha_agenda',
                'agenda.motivo_anulacion',
                'persona.nombres as p_nombres',
                'persona.p_apellido as p_p_apellido',
                'persona.s_apellido as p_s_apellido',
                'persona.matricula_seguro',
                'personaEsp.nombres as Esp_nombres',
                'personaEsp.p_apellido as Esp_p_apellido',
                'personaEsp.s_apellido as Esp_s_apellido',
                'especialidad.especialidad as nombreEspecialidad'
            )
            ->where('agenda.anulacion_ficha', true);
    }

    public function relationSearch(): array
    {

        return [
            'personaAgenda' => ['nombres', 'p_apellido', 's_apellido', 'matricula_seguro'],
            'asignacionHorarioAgenda.especialistaAsignacionHorario.personaEspecialista' => ['nombres', 'p_apellido', 's_apellido'],
            'asignacionHorarioAgenda.especialistaAsignacionHorario.especialidadEspecialista' => ['especialidad']
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('fecha_agenda_formatted', fn(Agenda $model) => Carbon::parse($model->fecha_agenda)->format('d/m/Y'))
             
            ->add('hora_agenda', function ($row) {
                return \Carbon\Carbon::parse($row->hora_agenda)->format('H:i');
            })
            ->add('confirmado')
            ->add('id_servicio_plataforma')
            ->add('id_persona')
            //->add('persona_nombre_completo', fn(Agenda $model) => $model->personaAgenda->p_apellido . ' ' . $model->personaAgenda->s_apellido . ' ' . $model->personaAgenda->nombres)
            ->add('persona_nombre_completo', function ($nombrePersona) {
                if ($nombrePersona->p_p_apellido == null) {
                    return $nombrePersona->p_s_apellido . ' ' . $nombrePersona->p_nombres;
                } elseif ($nombrePersona->p_s_apellido == null) {
                    return $nombrePersona->p_p_apellido . ' ' . $nombrePersona->p_nombres;
                } else {
                    return $nombrePersona->p_p_apellido . ' ' . $nombrePersona->p_s_apellido . ' ' . $nombrePersona->p_nombres;
                }
            })
            ->add('id_asignacion_horario')
            ->add('anulacion_ficha')
            ->add('fecha_anulacion_formatted', fn(Agenda $model) => Carbon::parse($model->fecha_anulacion)->format('d/m/Y'))
            ->add('motivo_anulacion')
            ->add('motivo_anulacion_validado', function ($motivo) {
                if ($motivo->motivo_anulacion == null) {
                    return 'NO CUENTA CON DESCRIPCIÓN';
                } else {
                    return $motivo->motivo_anulacion;
                }
            })
            ->add('id_suspension_horario')
            ->add('clave_unica')
            ->add('id_user_created')
            ->add('id_user_updated')
            ->add('especialista_nombre_completo', function ($nombreEspecialista) {
                if ($nombreEspecialista->Esp_p_apellido == null) {
                    return $nombreEspecialista->Esp_p_apellido . ' ' . $nombreEspecialista->Esp_nombres;
                } elseif ($nombreEspecialista->Esp_s_apellido == null) {
                    return $nombreEspecialista->Esp_p_apellido . ' ' . $nombreEspecialista->Esp_nombres;
                } else {
                    return $nombreEspecialista->Esp_p_apellido . ' ' . $nombreEspecialista->Esp_s_apellido . ' ' . $nombreEspecialista->Esp_nombres;
                }
            })
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')->index()
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('HORA', 'hora_agenda')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),
                
            Column::make('FECHA', 'fecha_agenda_formatted', 'fecha_agenda')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('ASEGURADO', 'persona_nombre_completo', 'p_p_apellido')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->sortable()
                ->searchable(),

            Column::make('MATRÍCULA', 'matricula_seguro')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('ESPECIALISTA', 'especialista_nombre_completo', 'Esp_p_apellido')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->sortable()
                ->searchable(),

            Column::make('ESPECIALIDAD', 'nombreEspecialidad')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->sortable()
                ->searchable(),


            Column::make('MOTIVO', 'motivo_anulacion_validado', 'motivo_anulacion')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::action('OPCIONES')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
        ];
    }

    public function filters(): array
    {
        return [];
    }

    public function actions(Agenda $row): array
    {
        return [
            Button::add('ver')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                </svg>')
                ->class('btn btn-blue')
                ->tooltip('VER ANULACIÓN')
                ->dispatchTo('plataforma.anulacion.anulacion-main', 'verAnulacion', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('ver.anulacion_fichas')),

        ];
    }

    /*
    public function actionRules($row): array
    {
       return [
            // Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($row) => $row->id === 1)
                ->hide(),
        ];
    }
    */
}
