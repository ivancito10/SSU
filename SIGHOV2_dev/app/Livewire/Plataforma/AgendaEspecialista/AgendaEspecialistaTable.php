<?php

namespace App\Livewire\Plataforma\AgendaEspecialista;

use App\Models\Plataforma\Especialista;
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
use PowerComponents\LivewirePowerGrid\Facades\Rule as PGRule;

final class AgendaEspecialistaTable extends PowerGridComponent
{
    // use WithExport, PersistState;
    use WithExport;
    public string $tableName = 'AgendaEspecialistasTable';
    public string $sortField = 'especialista.estado';
    public string $sortDirection = 'desc';

    public function setUp(): array
    {
        //$this->showCheckBox();
        //  $this->persist(['searchInput', 'perPage', 'filters', 'sortField', 'sortDirection']);
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
        return Especialista::query()
            ->join('administracion.persona as persona', function ($persona) {
                $persona->on('persona.id', '=', 'especialista.id_persona');
            })
            ->join('administracion.especialidad as especialidad', function ($especialidad) {
                $especialidad->on('especialidad.id', '=', 'especialista.id_especialidad');
            })
            ->select(
                'especialista.*',
                'persona.nombres as persona_nombres',
                'persona.p_apellido as persona_p_apellido',
                'persona.s_apellido as persona_s_apellido',
                'persona.ci as persona_ci',
                'persona.complemento as persona_complemento',
                'persona.matricula_seguro as persona_matricula_seguro',
                'especialidad.especialidad as nombre_especialidad'
            )

            //->join('administracion.persona', 'especialista.id_persona', '=', 'persona.id')
            //->join('administracion.especialidad', 'especialista.id_especialidad', '=', 'especialidad.id')
            //->select('especialista.*', 'especialidad.especialidad as nombre_especialidad', 'persona.nombres as persona_nombres', 'persona.p_apellido as persona_p_apellido', 'persona.s_apellido as persona_s_apellido')
            ->orderByDesc('especialista.estado')
            ->orderBy('especialidad.especialidad', 'asc');
    }

    public function relationSearch(): array
    {
        return [
            'personaEspecialista' => ['nombres', 'p_apellido', 's_apellido', 'ci', 'complemento', 'matricula_seguro'],
            'especialidadEspecialista' => ['especialidad']
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('foto')
            ->add('permanente')
            ->add('estado', function ($dish) {
                if ($dish->estado) {
                    return '<span> <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="green" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                    </svg></span>';
                } else {
                    return '<span ><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill=" #c0392b" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                    </svg></span>';
                }
            })
            ->add('permanente', function ($dish) {
                if ($dish->estado) {
                    return "<span style='color:#1e8ee0 '><b>PERMANENTE</b></span>";
                } else {
                    return "<span style='color:#ec6716 '><b>EVENTUAL</b></span>";
                }
            })
            ->add('nombre_especialidad')

            ->add('persona_carnet', function ($ciPersona) {
                if ($ciPersona->persona_complemento == null) {
                    return $ciPersona->persona_ci;
                } else {
                    return $ciPersona->persona_ci . ' ' . $ciPersona->persona_complemento;
                }
            })
            ->add('persona_matricula_seguro', function ($matriculaPersona) {
                if ($matriculaPersona->persona_matricula_seguro == null) {
                    return $matriculaPersona = " ";
                } else {
                    return $matriculaPersona->persona_matricula_seguro;
                }
            })
            ->add('id_persona')
            // ->add('persona_nombre_completo', fn(Especialista $model) => $model->personaEspecialista->p_apellido . ' ' . $model->personaEspecialista->s_apellido. ' ' . $model->personaEspecialista->nombres)
            ->add('persona_nombre_completo', function ($nombrePersona) {
                if ($nombrePersona->persona_p_apellido == null) {
                    return $nombrePersona->persona_s_apellido . ' ' . $nombrePersona->persona_nombres;
                } elseif ($nombrePersona->persona_s_apellido == null) {
                    return $nombrePersona->persona_p_apellido . ' ' . $nombrePersona->persona_nombres;
                } else {
                    return $nombrePersona->persona_p_apellido . ' ' . $nombrePersona->persona_s_apellido . ' ' . $nombrePersona->persona_nombres;
                }
            })
            ->add('grado_academico')
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
            Column::make('NOMBRE DE ESPECIALISTA', 'persona_nombre_completo', 'persona_p_apellido')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->sortable()
                ->searchable(),
            Column::make('ESPECIALIDAD', 'nombre_especialidad')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->sortable()
                ->searchable(),


            Column::make('CARNET    ', 'persona_carnet', 'persona_carnet')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('MATRÍCULA', 'persona_matricula_seguro', 'persona_matricula_seguro')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('ESTADO', 'estado')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::action('OPCIONES')
                ->headerAttribute('text-center tablita-cabecera-sin-mover  ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center tablita-cuerpo-sin-mover')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::datepicker('fecha_contrato_inicio'),
            Filter::datepicker('fecha_contrato_fin'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }

    public function actions(Especialista $row): array
    {
        $buttons = [];
        if ($row->estado === true) {
            if ($row->especialista_imagenologia) {
                $buttons[] =  Button::add('calendario')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-calendar-week-fill" viewBox="0 0 16 16">
                <path d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4zM16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2M9.5 7h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5m3 0h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5M2 10.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3.5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5"/>
                </svg>')
                ->class('btn btn-orange')
                ->route('plataforma.AgendaEspecialista.calendarioEcografia', ['id' => $row->id])
                ->tooltip('VER AGENDA')
                ->can(allowed: auth()->user()->can('ver.agenda_especialista'));
            } else {
                $buttons[] =  Button::add('calendario')
                    ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-calendar-week-fill" viewBox="0 0 16 16">
                <path d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4zM16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2M9.5 7h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5m3 0h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5M2 10.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3.5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5"/>
                </svg>')
                    ->class('btn btn-orange')
                    ->route('plataforma.AgendaEspecialista.calendario', ['id' => $row->id])
                    ->tooltip('VER AGENDA')
                    ->can(allowed: auth()->user()->can('ver.agenda_especialista'));
            }
        }
        return $buttons;
    }


    public function actionRules($row): array
    {
        return [
            // estado = true  ➜ fila verde suave
            // PGRule::rows()->when(fn($r) => $r->estado)->setAttribute('class', 'table-success'),
            PGRule::rows()->when(fn($r) => !$r->estado)->setAttribute('class', 'table-danger'),

        ];
    }
}
