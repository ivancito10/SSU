<?php

namespace App\Livewire\Farmacia\Farmacia\Receta;

use App\Models\ConsultaExterna\Receta;
use App\Services\ServicioPersona;
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

final class RecetaTable extends PowerGridComponent
{
    use WithExport;

    public function setUp(): array
    {
        return [
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function obtenerDatosPersona($id_persona)
    {
        $datosPersonales =  new ServicioPersona();
        return $datosPersonales->datosPersona($id_persona);
    }

    public function datasource(): Builder
    {
        return Receta::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('nro_receta')
            ->add('fecha_entrega_formatted', fn(Receta $model) => Carbon::parse($model->fecha_entrega)->format('d/m/Y'))
            ->add('fecha_transcripcion_formatted', fn(Receta $model) => Carbon::parse($model->fecha_transcripcion)->format('d/m/Y'))
            ->add('manual')
            ->add('estado')
            ->add('clave_unica')
            ->add('id_historia_clinica')
            ->add('id_tipo_consulta')
            ->add('id_especialista')
            ->add('id_persona')
            ->add('id_user_created')
            ->add('id_user_updated')
            ->add('created_at');
    }

    public function columns(): array
    {
        $headerStyle = 'font-size: 15px; background-color: #2471a3; color: white;';
        $headerClass = 'text-center';
        $bodyClass   = 'text-center';

        return [
            Column::make('#', 'id')
                ->index()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass),

            Column::make('CÓD. MEDICAMENTO', 'codigo_medicamento')
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass)
                ->searchable()
                ->sortable(),

            Column::make('DESCRIPCIÓN', 'descripcion')
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute('text-left') // Descripción alineada a la izquierda para mejor lectura
                ->searchable()
                ->sortable(),

            Column::make('FORMA FARMACÉUTICA', 'forma_farmaceutica')
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass),

            Column::make('FECHA INGRESO', 'fecha_ingreso_formatted', 'fecha_ingreso')
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass)
                ->sortable(),

            Column::make('MODALIDAD COMPRA', 'modalidad_compra')
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass),

            Column::make('N° INGRESO', 'numero_ingreso')
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass)
                ->searchable()
                ->sortable(),

            Column::make('PROVEEDOR', 'proveedor')
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute('text-left')
                ->searchable(),

            Column::make('CANTIDAD', 'cantidad')
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute('text-right'),

            Column::make('P. UNITARIO', 'precio_unitario')
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute('text-right'),

            Column::make('P. TOTAL', 'precio_total')
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute('text-right')
                ->sortable(),

            Column::make('CUENTA CONTABLE', 'cuenta_contable')
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass)
                ->searchable(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::datepicker('fecha_entrega'),
            Filter::datepicker('fecha_transcripcion'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }

    public function actions(Receta $row): array
    {
        return [
            Button::add('edit')
                ->slot('Edit: ' . $row->id)
                ->id()
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->dispatch('edit', ['rowId' => $row->id])
                ->can(allowed: auth()->user()->can('pdf.recetas_medicas'))

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
