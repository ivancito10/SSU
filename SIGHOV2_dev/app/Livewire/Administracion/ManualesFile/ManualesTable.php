<?php

namespace App\Livewire\Administracion\ManualesFile;

use App\Models\Administracion\Manual;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class ManualesTable extends PowerGridComponent
{
    public string $tableName = 'ManualesTable';
    public string $sortField = 'administracion.manuales.id';
    public string $sortDirection = 'desc';

    public function setUp(): array
    {
        return [
            Header::make()
                ->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Manual::query()->latest('id');
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('modulo')
            ->add('modulo_badge', fn (Manual $row) => '<span class="badge badge-primary px-2 py-1" style="font-size:12px;">' . e($row->modulo) . '</span>')
            ->add('titulo')
            ->add('version')
            ->add('archivo_info', fn (Manual $row) => '<span class="badge badge-light border px-2 py-1"><i class="fas fa-file-pdf text-danger mr-1"></i>' . e($row->nombre_archivo ?: 'S/A') . ' (' . e($row->tamano_formateado) . ')</span>')
            ->add('estado_badge', function (Manual $row) {
                if ($row->estado) {
                    return '<span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="green" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                </svg>
                            </span>';
                }

                return '<span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="red" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                            </svg>
                        </span>';
            });
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->index()
                ->headerAttribute('text-center', 'font-size:15px; background-color: #2471a3; color:white;')
                ->bodyAttribute('text-center'),

            Column::make('MÓDULO', 'modulo_badge', 'modulo')
                ->searchable()
                ->sortable()
                ->headerAttribute('text-center', 'font-size:15px; background-color: #2471a3; color:white;')
                ->bodyAttribute('text-center'),

            Column::make('TÍTULO DEL MANUAL', 'titulo')
                ->searchable()
                ->sortable()
                ->headerAttribute('text-center', 'font-size:15px; background-color: #2471a3; color:white;')
                ->bodyAttribute('text-left'),

            Column::make('VERSIÓN', 'version')
                ->searchable()
                ->sortable()
                ->headerAttribute('text-center', 'font-size:15px; background-color: #2471a3; color:white;')
                ->bodyAttribute('text-center'),

            Column::make('ARCHIVO PDF', 'archivo_info', 'nombre_archivo')
                ->searchable()
                ->headerAttribute('text-center', 'font-size:15px; background-color: #2471a3; color:white;')
                ->bodyAttribute('text-center'),

            Column::make('ESTADO', 'estado_badge', 'estado')
                ->sortable()
                ->headerAttribute('text-center', 'font-size:15px; background-color: #2471a3; color:white;')
                ->bodyAttribute('text-center'),

            Column::action('OPCIONES')
                ->headerAttribute('text-center', 'font-size:15px; background-color: #2471a3; color:white;')
                ->bodyAttribute('text-center'),
        ];
    }

    public function actions(Manual $row): array
    {
        $botones = [];

        $botones[] = Button::add('ver')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                        <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                        <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                    </svg>')
            ->class('btn btn-blue')
            ->tooltip('VER MANUAL PDF')
            ->dispatch('ver_manual', ['id' => $row->id]);

        $botones[] = Button::add('descargar')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                        <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"/>
                        <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z"/>
                    </svg>')
            ->class('btn btn-green')
            ->tooltip('DESCARGAR PDF')
            ->route('administracion.manuales.descargar', ['id' => $row->id])
            ->target('_blank');

        if ($row->estado) {
            $botones[] = Button::add('deshabilitar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                            <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                        </svg>')
                ->class('btn btn-red')
                ->tooltip('DESHABILITAR MANUAL')
                ->dispatch('cambiar_estado_manual', ['id' => $row->id]);
        } else {
            $botones[] = Button::add('habilitar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                        </svg>')
                ->class('btn btn-green')
                ->tooltip('HABILITAR MANUAL')
                ->dispatch('cambiar_estado_manual', ['id' => $row->id]);
        }

        return $botones;
    }

    public function actionRules($row): array
    {
        return [
            Rule::rows()
                ->when(fn ($row) => $row->estado == false)
                ->setAttribute('class', 'table-danger text-muted font-italic'),
        ];
    }
}
