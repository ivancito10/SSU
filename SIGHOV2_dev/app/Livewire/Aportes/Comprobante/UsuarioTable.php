<?php

namespace App\Livewire\Aportes\Comprobante;

use App\Models\Aportes\ComprobanteTemp;
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

final class UsuarioTable extends PowerGridComponent
{
    use WithExport;

    public function setUp(): array
    {
        $this->showCheckBox();

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
        return ComprobanteTemp::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('id_institucion')
            ->add('gestion')
            ->add('cantidad_eventuales')
            ->add('total_salario_cotizable')
            ->add('reintegro')
            ->add('beneficio_social')
            ->add('id_mes')
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

            Column::make('Id institucion', 'id_institucion')
                ->sortable()
                ->searchable(),

            Column::make('Gestion', 'gestion')
                ->sortable()
                ->searchable(),

            Column::make('Cantidad eventuales', 'cantidad_eventuales')
                ->sortable()
                ->searchable(),

            Column::make('Total salario cotizable', 'total_salario_cotizable')
                ->sortable()
                ->searchable(),

            Column::make('Reintegro', 'reintegro')
                ->sortable()
                ->searchable(),

            Column::make('Beneficio social', 'beneficio_social')
                ->sortable()
                ->searchable(),

            Column::make('Id mes', 'id_mes')
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

    public function actions(ComprobanteTemp $row): array
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
