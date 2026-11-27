<?php

namespace App\Livewire\Afiliacion\ExamenPreocupacional\RRHH;

use App\Models\Afiliacion\Preocupacional;
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

final class ListaSolicitudesTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'SolicitudesExamenesPreocupacionales';
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
        return Preocupacional::query()->join('administracion.estado as es','es.id','=','afiliacion.preocupacional.id_estado_solicitud')
        ->join('administracion.persona as per','per.id','=','afiliacion.preocupacional.id_persona')

        ->select('afiliacion.preocupacional.*','es.descripcion','per.nombres','per.p_apellido','per.s_apellido');

        
    }

    public function relationSearch(): array
    {
        return [
            'personaPreocupuacional' => ['nombres', 'p_apellido', 's_apellido'],

        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('fecha_contrato_formatted', fn (Preocupacional $model) => Carbon::parse($model->fecha_contrato)->format('d/m/Y'))
            ->add('fecha_solicitud_formatted', fn (Preocupacional $model) => Carbon::parse($model->fecha_solicitud)->format('d/m/Y'))
            ->add('fecha_fin_formatted', fn (Preocupacional $model) => Carbon::parse($model->fecha_fin)->format('d/m/Y'))
            ->add('descripcion')
            ->add('estado')
            ->add('fecha_liq_validacion_formatted', fn (Preocupacional $model) => Carbon::parse($model->fecha_liq_validacion)->format('d/m/Y'))
            ->add('fecha_entrega_formatted', fn (Preocupacional $model) => Carbon::parse($model->fecha_entrega)->format('d/m/Y'))
            ->add('cargo')
            ->add('nombre_completo', fn(Preocupacional $model) => $model->personaPreocupuacional->nombres . ' ' . $model->personaPreocupuacional->p_apellido . ' ' . $model->personaPreocupuacional->s_apellido)
            ->add('observaciones')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id')
            ->headerAttribute('text-center bg-teal', 'font-size:15px')
            ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('Nombre Completo', 'nombre_completo', 'nombre_completo')
            ->headerAttribute('text-center bg-teal', 'font-size:15px')
            ->bodyAttribute('text-center')
                ->sortable(),

            Column::make('Fecha contrato', 'fecha_contrato_formatted', 'fecha_contrato')
            ->headerAttribute('text-center bg-teal', 'font-size:15px')
            ->bodyAttribute('text-center')
                ->sortable(),

            Column::make('Cargo', 'cargo', 'cargo')
            ->headerAttribute('text-center bg-teal', 'font-size:15px')
            ->bodyAttribute('text-center')
                ->sortable(),


            Column::make('Fecha solicitud', 'fecha_solicitud_formatted', 'fecha_solicitud')
            ->headerAttribute('text-center bg-teal', 'font-size:15px')
            ->bodyAttribute('text-center')
                ->sortable(),

            Column::make('Fecha fin', 'fecha_fin_formatted', 'fecha_fin') ->headerAttribute('text-center bg-teal', 'font-size:15px')
            ->bodyAttribute('text-center')
                ->sortable(),

            Column::make('Estado solicitud', 'descripcion') ->headerAttribute('text-center bg-teal', 'font-size:15px')
            ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('Observación', 'observaciones') ->headerAttribute('text-center bg-teal', 'font-size:15px')
            ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

         
            Column::action('Action') ->headerAttribute('text-center bg-teal', 'font-size:15px')
            ->bodyAttribute('text-center')
        ];
    }

    public function filters(): array
    {
        return [
           
        ];
    }

    public function actions(Preocupacional $row): array
    {
        return [
            Button::add('edit')
                ->slot('Edit: '.$row->id)
                ->id()
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->dispatch('edit', ['rowId' => $row->id])
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
