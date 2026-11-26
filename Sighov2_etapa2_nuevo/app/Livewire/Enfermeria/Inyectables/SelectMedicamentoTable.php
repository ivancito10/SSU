<?php

namespace App\Livewire\Enfermeria\Inyectables;

use App\Models\Farmacia\Medicamento;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class SelectMedicamentoTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'SelectMedicamentoTable';
    public string $primaryKey = 'id';
    public string $sortField = 'nombre';
    public string $sortDirection = 'asc';

    public function setUp(): array
    {
        return [
            Exportable::make('export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()
                ->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    /**
     * Fuente de datos: Base de datos Etapa 2 (farmacia.medicamento)
     */
    public function datasource(): Builder
    {
        return Medicamento::query()
            ->where('estado', true)
            ->where(function ($q) {
                $q->where('forma_farmaceutica', 'ILIKE', '%Inyectable%')
                  ->orWhere('forma_farmaceutica', 'ILIKE', '%Ampolla%')
                  ->orWhere('forma_farmaceutica', 'ILIKE', '%Frasco%');
            });
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('codigo_medicamento', fn ($row) => (string) ($row->codigo_medicamento ?? 'S/C'))
            ->add('nombre', fn ($row) => (string) ($row->nombre ?? 'SIN NOMBRE'))
            ->add('concentracion', fn ($row) => (string) ($row->concentracion ?? 'S/D'))
            ->add('forma_farmaceutica', fn ($row) => (string) ($row->forma_farmaceutica ?? 'Inyectable'));
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->index()
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('CÓDIGO', 'codigo_medicamento')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('MEDICAMENTO', 'nombre')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-left')
                ->sortable()
                ->searchable(),

            Column::make('CONCENTRACIÓN', 'concentracion')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('FORMA FARMACÉUTICA', 'forma_farmaceutica')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-left')
                ->sortable()
                ->searchable(),

            Column::action('OPCIONES')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable(),
        ];
    }

    public function actions(Medicamento $row): array
    {
        return [
            Button::add('seleccionar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                        </svg>')
                ->class('btn btn-green')
                ->tooltip('SELECCIONAR')
                ->dispatch('medicamentoInyectableSeleccionado', [
                    'id'     => $row->id,
                    'codigo' => $row->codigo_medicamento ?? '',
                    'nombre' => $row->nombre ?? '',
                ]),
        ];
    }
}
