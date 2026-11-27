<?php

namespace App\Livewire\Afiliacion\Afiliados;

use App\Models\Administracion\Persona;
use App\Models\Afiliacion\Titular;
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

final class HistorialAfiliadoTable extends PowerGridComponent
{
    use WithExport;

    public function setUp(): array
    {
        // $this->showCheckBox();

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
        // return Titular::with(['titularPersona']);
        return Persona::where('id','=',89)
        ;
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            // ->add('tipoAseguradoPersona.tipo_asegurado')
            ->add('id');
            // ->add('PersonaTitular.id');
           
    }

    public function columns(): array
    {
        return [
            // Column::make('Tipo Asegurado', 'tipoAseguradoPersona.tipo_asegurado')
            // ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
            // ->bodyAttribute('text-center')
            //     ->sortable()
            //     ->searchable(),
            Column::make('Id persona', 'id')
            ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
            ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),
            // Column::make('Id titular', 'PersonaTitular.id')
            // ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
            // ->bodyAttribute('text-center')
            //     ->sortable()
            //     ->searchable(),
            Column::action('Accion')
            ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
            ->bodyAttribute('text-center')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::datepicker('fecha_nacimiento'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
    }

    public function actions(Titular $row): array
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
