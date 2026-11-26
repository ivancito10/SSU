<?php

namespace App\Livewire\Enfermeria\Inyectables;

use App\Services\EnfermeriaNestService;
use Illuminate\Support\Collection;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class SelectPacienteTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'SelectPacienteTable';
    public string $primaryKey = 'id';
    public string $sortField = 'id';
    public string $sortDirection = 'desc';

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
     * Fuente de datos: Microservicio NestJS (/api/pacientes)
     */
    public function datasource(): Collection
    {
        try {
            $nestService = app(EnfermeriaNestService::class);
            $respuesta = $nestService->buscarPacientes(
                termino: $this->search ?? '',
                page: 1,
                limit: 150
            );

            $items = $respuesta['data'] ?? $respuesta ?? [];

            if (!is_array($items)) {
                return collect([]);
            }

            // Normalizamos cada elemento asegurando que exista la propiedad 'id' requerida por PowerGrid
            return collect($items)->map(function ($item) {
                $obj = is_array($item) ? (object) $item : $item;
                
                // Asignamos 'id' a partir de idPersona para satisfacer a PowerGrid
                $obj->id             = $obj->idPersona ?? $obj->id ?? 0;
                $obj->ci             = !empty($obj->ci) ? trim((string) $obj->ci) : 'S/C';
                $obj->matricula      = !empty($obj->matricula) ? trim((string) $obj->matricula) : 'S/M';
                $obj->nombreCompleto = !empty($obj->nombreCompleto) ? trim((string) $obj->nombreCompleto) : 'SIN NOMBRE';
                $obj->tipoAsegurado  = !empty($obj->tipoAsegurado) ? trim((string) $obj->tipoAsegurado) : 'TITULAR';

                return $obj;
            });
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('SelectPacienteTable error NestJS: ' . $e->getMessage());
            return collect([]);
        }
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id', fn ($row) => (string) ($row->id ?? ''))
            ->add('ci', fn ($row) => (string) ($row->ci ?? 'S/C'))
            ->add('matricula', fn ($row) => (string) ($row->matricula ?? 'S/M'))
            ->add('nombreCompleto', fn ($row) => (string) ($row->nombreCompleto ?? 'SIN NOMBRE'))
            ->add('tipoAsegurado', fn ($row) => (string) ($row->tipoAsegurado ?? 'TITULAR'));
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

            Column::make('C.I.', 'ci')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('MATRÍCULA', 'matricula')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('NOMBRE COMPLETO', 'nombreCompleto')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-left')
                ->sortable()
                ->searchable(),

            Column::make('TIPO ASEGURADO', 'tipoAsegurado')
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

    public function actions($row): array
    {
        $idPersona      = $row->idPersona ?? $row->id ?? null;
        $nombreCompleto = $row->nombreCompleto ?? '';
        $matricula      = $row->matricula ?? 'S/M';
        $ci             = $row->ci ?? 'S/C';

        return [
            Button::add('seleccionar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                        </svg>')
                ->class('btn btn-green')
                ->tooltip('SELECCIONAR')
                ->dispatch('pacienteSeleccionado', [
                    'id'              => $idPersona,
                    'nombre_completo' => $nombreCompleto,
                    'matricula'       => $matricula,
                    'ci'              => $ci,
                ]),
        ];
    }
}