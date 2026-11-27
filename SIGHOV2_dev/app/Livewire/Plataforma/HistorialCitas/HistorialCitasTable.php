<?php

namespace App\Livewire\Plataforma\HistorialCitas;

use App\Models\Plataforma\Agenda;
use App\Models\User;
use App\Services\GrupoFamiliarPersona;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
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

final class HistorialCitasTable extends PowerGridComponent
{
    use WithExport;

    public function setUp(): array
    {
        return [
            // Exportable::make('export')
            //     ->striped()
            //     ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {

        $id_persona = Auth::user()->id_persona;
        //$id_persona = 1;
        $servicioFamiliar = new GrupoFamiliarPersona();
        $grupoFamiliar = $servicioFamiliar->encontrarPersona($id_persona);
        $idsPersonas = array_column($grupoFamiliar, 'id');
        return Agenda::query()->whereIn('id_persona', $idsPersonas);
    }

    public function relationSearch(): array
    {
        return [
            'personaAgenda' => ['nombres', 'p_apellido', 's_apellido'],
            'asignacionHorarioAgenda.especialistaAsignacionHorario.personaEspecialista' => ['nombres', 'p_apellido', 's_apellido'],
            'asignacionHorarioAgenda.especialistaAsignacionHorario.especialidadEspecialista' => ['especialidad'],
            'servicioPlataformaAgenda' => ['nombre_servicio']

        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('fecha_agenda_formatted', fn(Agenda $model) => Carbon::parse($model->fecha_agenda)->format('d/m/Y'))
            ->add('hora_agenda')
            ->add('especialista', fn(Agenda $model)
            => $model->asignacionHorarioAgenda->especialistaAsignacionHorario->personaEspecialista->nombres . ' ' .
                $model->asignacionHorarioAgenda->especialistaAsignacionHorario->personaEspecialista->p_apellido . ' ' .
                $model->asignacionHorarioAgenda->especialistaAsignacionHorario->personaEspecialista->s_apellido)
            ->add('especialidad', fn(Agenda $model)
            => $model->asignacionHorarioAgenda->especialistaAsignacionHorario->especialidadEspecialista->especialidad ?? 'No asignado')
            ->add('confirmado')
            ->add('id_persona', fn(Agenda $model)
            => $model->personaAgenda->nombres . ' ' . $model->personaAgenda->p_apellido . ' ' . $model->personaAgenda->p_apellido)
            //->add('fecha_anulacion_formatted', fn (Agenda $model) => Carbon::parse($model->fecha_anulacion)->format('d/m/Y'));
            ->add('id_servicio_plataforma', fn(Agenda $model)
            => $model->servicioPlataformaAgenda->nombre_servicio);
        //->add('estado_agenda',fn(Agenda $model) => $model->)
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->index(),

            Column::make('FECHA AGENDA', 'fecha_agenda_formatted', 'fecha_agenda')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable(),

            Column::make('HORA AGENDA', 'hora_agenda')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('ESPECIALISTA', 'especialista')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('ESPECIALIDAD', 'especialidad')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('PERSONA', 'id_persona')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),
            // Column::make('ESTADO', 'estado_agenda')
            //     ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3  ; color:white;')
            //     ->bodyAttribute('text-center')
            //     ->sortable()
            //     ->searchable(),
            Column::make('TIPO AGENDA', 'id_servicio_plataforma')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            // Column::make('Fecha anulacion', 'fecha_anulacion_formatted', 'fecha_anulacion')
            //     ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3  ; color:white;')
            //     ->bodyAttribute('text-center')
            //     ->sortable(),

            Column::action('OPCIONES')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
        ];
    }

    public function filters(): array
    {
        return [
            // Filter::datepicker('fecha_agenda'),
            // Filter::datepicker('fecha_anulacion'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
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
                ->tooltip('VER HISTORIAL CITAS')
                ->dispatchTo('plataforma.historial-citas.historial-citas-main', 'verModalAgenda', ['data' => $row->id])
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
