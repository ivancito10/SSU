<?php

namespace App\Livewire\Fisioterapia\Administracion\Fisioterapeutas;

use App\Services\Fisioterapia\FisioterapeutasService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use PowerComponents\LivewirePowerGrid\{Button, Column, Footer, Header, PowerGrid, PowerGridComponent, PowerGridFields};

use PowerComponents\LivewirePowerGrid\Facades\Filter;

final class FisioterapeutasTable extends PowerGridComponent
{
    public string $tableName = 'FisioterapeutasTable';
    public string $sortField = 'nombre';

    public function boot(): void
    {
        Gate::authorize('fisioterapia.administrar');
    }

    public function setUp(): array
    {
        return [Header::make()->showSearchInput(), Footer::make()->showPerPage(10, [10, 25, 50])->showRecordCount()];
    }

    public function datasource(): Collection
    {
        Gate::authorize('fisioterapia.administrar');
        try {
            return app(FisioterapeutasService::class)->listar();
        } catch (\Exception $e) {
            report($e);
            $this->dispatch('error-fisioterapeutas');
            return collect();
        }
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()->add('id')
            ->add('carnet', fn ($row) => e($row->carnet ?? '—'))
            ->add('matricula', fn ($row) => e($row->matricula ?? '—'))
            ->add('nombre', fn ($row) => e($row->nombre ?? '—'))
            ->add('contrato', fn ($row) => e($row->contrato ?? '—'))
            ->add('desde', fn ($row) => e($row->desde ?? '—'))
            ->add('hasta', fn ($row) => e($row->hasta ?? '—'))
            ->add('estado')
            ->add('estado_formatted', fn ($row) => match ($row->estado) {
                true => '<span ">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="green" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                    </svg>
                </span>',
                false => '<span >
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="red" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                    </svg>
                </span>',
                default => 'Sin estado',
            });
    }

    public function columns(): array
    {
        $style = 'font-size:15px; background-color:#2471a3; color:white;';
        return [
            Column::make('#', 'id')->index()->headerAttribute('text-center', $style)->bodyAttribute('text-center'),
            Column::make('CARNET', 'carnet')->sortable()->searchable()->headerAttribute('text-center', $style),
            Column::make('MATRÍCULA', 'matricula')->sortable()->searchable()->headerAttribute('text-center', $style),
            Column::make('NOMBRE DEL FISIOTERAPEUTA', 'nombre')->sortable()->searchable()->headerAttribute('text-center', $style),
            Column::make('TIPO DE CONTRATO', 'contrato')->sortable()->searchable()->headerAttribute('text-center', $style),
            Column::make('CONTRATO DESDE', 'desde')->sortable()->searchable()->headerAttribute('text-center', $style),
            Column::make('CONTRATO HASTA', 'hasta')->sortable()->searchable()->headerAttribute('text-center', $style),
            Column::make('ESTADO', 'estado_formatted', 'estado')->sortable()->headerAttribute('text-center', $style)->bodyAttribute('text-center'),
            Column::action('OPCIONES')->headerAttribute('text-center', $style)->bodyAttribute('text-center text-nowrap'),
        ];
    }

    // public function filters(): array
    // {
    //     return [Filter::boolean('estado_formatted', 'estado')->label('Activos', 'De baja')];
    // }

    public function actions($row): array
    {
        $target = 'fisioterapia.administracion.fisioterapeutas.fisioterapeutas-main';
        $icon = fn ($accion) => view('components.table-action-icon', compact('accion'))->render();
        $args = ['id' => data_get($row, 'id')];
        $buttons = [Button::add('ver')->slot($icon('ver'))->class('btn btn-blue')->tooltip('VER FISIOTERAPEUTA')
            ->dispatchTo($target, 'ver-fisioterapeuta', $args)];
        $activo = data_get($row, 'estado');
        if ($activo === true && Gate::allows('fisioterapia.administrar')) {
            $buttons[] = Button::add('horario')->slot($icon('asignar_horario'))->class('btn btn-lila')
                ->tooltip('AGREGAR HORARIO SEMANAL')->dispatchTo($target, 'agregar-horario', $args);
        }
        if (Gate::allows('fisioterapia.administrar') && is_bool($activo)) {
            $buttons[] = Button::add('estado')->slot($icon($activo ? 'deshabilitar' : 'habilitar'))
                ->class($activo ? 'btn btn-red' : 'btn btn-green')
                ->tooltip($activo ? 'DESHABILITAR FISIOTERAPEUTA' : 'REHABILITAR FISIOTERAPEUTA')
                ->dispatchTo($target, 'solicitar-estado-fisioterapeuta', $args + ['estado' => !$activo]);
        }
        return $buttons;
    }
}