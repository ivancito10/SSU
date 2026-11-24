<?php

namespace App\Livewire\KardexContable;

use App\Models\AlmacenGeneral\Material;
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

final class MaterialesCuenta121Table extends PowerGridComponent
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
        //return Material::query();
        return Material::query()->whereRaw('1 = 0');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('codigo_medicamento')
            ->add('nombre_medicamento')
            ->add('forma_farmaceutica')

            // Cantidades
            ->add('cant_inicial')
            ->add('cant_ingresos')
            ->add('cant_reing_alm')
            ->add('cant_reing_far')
            ->add('cant_salidas')
            ->add('cant_bajas')
            ->add('cant_final')

            // Formateo de Montos
            ->add('monto_inicial', fn($row) => number_format($row->monto_inicial, 2, '.', ','))
            ->add('monto_ingresos', fn($row) => number_format($row->monto_ingresos, 2, '.', ','))
            ->add('monto_reing_alm', fn($row) => number_format($row->monto_reing_alm, 2, '.', ','))
            ->add('monto_reing_far', fn($row) => number_format($row->monto_reing_far, 2, '.', ','))
            ->add('monto_salidas', fn($row) => number_format($row->monto_salidas, 2, '.', ','))
            ->add('monto_bajas', fn($row) => number_format($row->monto_bajas, 2, '.', ','))
            ->add('monto_final', fn($row) => number_format($row->monto_final, 2, '.', ','));
    }

    public function columns(): array
    {
        $headerStyle = 'font-size: 14px; background-color: #2471a3; color: white;';
        $headerClass = 'text-center';
        $bodyClass   = 'text-center';
        $bodyRight   = 'text-right font-medium';

        return [
            Column::make('#', 'id')
                ->index()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass),

            Column::make('Código', 'codigo_medicamento', 'm.codigo_medicamento')
                ->sortable()
                ->searchable()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass),

            Column::make('Material', 'nombre_medicamento', 'm.nombre')
                ->sortable()
                ->searchable()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute('text-left'),

            Column::make('Forma Farmacéutica', 'forma_farmaceutica', 'm.forma_farmaceutica')
                ->sortable()
                ->searchable()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass),

            // Saldo Inicial
            Column::make('Cant. Saldo Inicial', 'cant_inicial')
                ->sortable()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass),

            Column::make('Monto Saldo Inicial', 'monto_inicial')
                ->sortable()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyRight),

            // Ingresos
            Column::make('Cant. Ingresos', 'cant_ingresos')
                ->sortable()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass),

            Column::make('Monto Ingresos', 'monto_ingresos')
                ->sortable()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyRight),

            // Reingresos Almacén
            Column::make('Cant. Reing. Almacén', 'cant_reing_alm')
                ->sortable()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass),

            Column::make('Monto Reing. Almacén', 'monto_reing_alm')
                ->sortable()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyRight),

            // Reingresos Farmacia
            Column::make('Cant. Reing. Farmacia', 'cant_reing_far')
                ->sortable()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass),

            Column::make('Monto Reing. Farmacia', 'monto_reing_far')
                ->sortable()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyRight),

            // Salidas
            Column::make('Cant. Salidas', 'cant_salidas')
                ->sortable()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass),

            Column::make('Monto Salidas', 'monto_salidas')
                ->sortable()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyRight),

            // Bajas
            Column::make('Cant. Bajas', 'cant_bajas')
                ->sortable()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass),

            Column::make('Monto Bajas', 'monto_bajas')
                ->sortable()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyRight),

            // Saldo Final
            Column::make('Cant. Saldo Final', 'cant_final')
                ->sortable()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass),

            Column::make('Monto Saldo Final', 'monto_final')
                ->sortable()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyRight),
        ];
    }

    public function filters(): array
    {
        return [];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }
    /*
    public function actions(Material $row): array
    {
        return [
            Button::add('edit')
                ->slot('Edit: ' . $row->id)
                ->id()
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->dispatch('edit', ['rowId' => $row->id])
        ];
    }

    
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
