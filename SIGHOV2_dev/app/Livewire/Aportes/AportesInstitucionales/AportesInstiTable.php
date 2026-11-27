<?php

namespace App\Livewire\Aportes\AportesInstitucionales;

use App\Models\Aportes\AporteInstitucional;
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

final class AportesInstiTable extends PowerGridComponent
{
    use WithExport;

    public function setUp(): array
    {
        //$this->showCheckBox();

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
        return AporteInstitucional::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('id_persona')
            ->add('id_institucion')
            ->add('item')
            ->add('cargo')
            ->add('area')
            ->add('tipo_trabajador')
            ->add('carga_horaria')
            ->add('dias_trabajados')
            ->add('total_ganado')
            ->add('total_ganado_dia')
            ->add('total_aporte')
            ->add('gestion')
            ->add('reintegro_normal')
            ->add('id_mes')
            ->add('reintegro_incr_social')
            ->add('beneficio_social')
            ->add('afiliado')
            ->add('id_user_created')
            ->add('id_user_updated')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id')
                ->sortable()
                ->searchable(),

            Column::make('Id', 'id')
                ->sortable()
                ->searchable(),

            Column::make('Id persona', 'id_persona')
                ->sortable()
                ->searchable(),

            Column::make('Id institucion', 'id_institucion')
                ->sortable()
                ->searchable(),

            Column::make('Item', 'item')
                ->sortable()
                ->searchable(),

            Column::make('Cargo', 'cargo')
                ->sortable()
                ->searchable(),

            Column::make('Area', 'area')
                ->sortable()
                ->searchable(),

            Column::make('Tipo trabajador', 'tipo_trabajador')
                ->sortable()
                ->searchable(),

            Column::make('Carga horaria', 'carga_horaria')
                ->sortable()
                ->searchable(),

            Column::make('Dias trabajados', 'dias_trabajados')
                ->sortable()
                ->searchable(),

            Column::make('Total ganado', 'total_ganado')
                ->sortable()
                ->searchable(),

            Column::make('Total ganado dia', 'total_ganado_dia')
                ->sortable()
                ->searchable(),

            Column::make('Total aporte', 'total_aporte')
                ->sortable()
                ->searchable(),

            Column::make('Gestion', 'gestion')
                ->sortable()
                ->searchable(),

            Column::make('Reintegro normal', 'reintegro_normal')
                ->sortable()
                ->searchable(),

            Column::make('Id mes', 'id_mes')
                ->sortable()
                ->searchable(),

            Column::make('Reintegro incr social', 'reintegro_incr_social')
                ->sortable()
                ->searchable(),

            Column::make('Beneficio social', 'beneficio_social')
                ->sortable()
                ->searchable(),

            Column::make('Afiliado', 'afiliado')
                ->sortable()
                ->searchable(),

            Column::make('Id user created', 'id_user_created')
                ->sortable()
                ->searchable(),

            Column::make('Id user updated', 'id_user_updated')
                ->sortable()
                ->searchable(),

            Column::make('Created at', 'created_at_formatted', 'created_at')
                ->sortable(),

            Column::make('Created at', 'created_at')
                ->sortable()
                ->searchable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
    }

    public function actions(AporteInstitucional $row): array
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
