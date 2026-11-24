<?php

namespace App\Livewire\Fisioterapia\Ordenes;

use App\Models\Fisioterapia\Ordenes;
use App\Services\Fisioterapia\OrdenesService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use PowerComponents\LivewirePowerGrid\{Button, Column, Footer, Header, PowerGrid, PowerGridComponent, PowerGridFields};

final class OrdenesTable extends PowerGridComponent
{
    public string $tableName = 'fisioterapiaOrdenesTable';
    public string $sortField = 'id';
    public string $sortDirection = 'desc';

    public function boot(): void
    {
        Gate::authorize('fisioterapia.ordenes.ver');
    }

    public function setUp(): array
    {
        return [Header::make()->showSearchInput(), Footer::make()->showPerPage(10, [10, 25, 50])->showRecordCount()];
    }

    public function datasource(): Builder
    {
        try {
            $consulta = app(OrdenesService::class)->buscar($this->search);
            $this->dispatch('fisioterapia:aviso-busqueda-orden', mensaje: '')
                ->to('fisioterapia.ordenes.ordenes-main');
            return $consulta;
        } catch (\RuntimeException $e) {
            $this->dispatch('fisioterapia:aviso-busqueda-orden', mensaje: $e->getMessage())
                ->to('fisioterapia.ordenes.ordenes-main');
            return app(OrdenesService::class)->consulta()->whereNull('id');
        }
    }

    public function fields(): PowerGridFields
    {
        $service = app(OrdenesService::class);
        return PowerGrid::fields()->add('id')
            ->add('nro_solicitud')
            ->add('nombre_especialista', fn (Ordenes $row) => e($service->especialista($row->id_user_created)['datos']['nombreCompleto'] ?? 'Datos no disponibles'))
            ->add('nombre_paciente', fn (Ordenes $row) => e($service->paciente($row->id_persona)['datos']['nombreCompleto'] ?? 'Datos no disponibles'));
    }

    public function columns(): array
    {
        $style = 'font-size:15px; background-color:#2471a3; color:white;';
        return [
            Column::make('#', 'id')->index()->headerAttribute('text-center', $style),
            Column::make('ORDEN', 'id')->sortable()->headerAttribute('text-center', $style),
            Column::make('NRO DE SOLICITUD', 'nro_solicitud')->sortable()->headerAttribute('text-center', $style),
            Column::make('NOMBRE DEL ESPECIALISTA', 'nombre_especialista')->headerAttribute('text-center', $style),
            Column::make('NOMBRE DEL PACIENTE', 'nombre_paciente')->headerAttribute('text-center', $style),
            Column::action('OPCIONES')->headerAttribute('text-center', $style)->bodyAttribute('text-center'),
        ];
    }

    public function actions(Ordenes $row): array
    {
        return [Button::add('ver')->slot(view('components.table-action-icon', ['accion' => 'ver'])->render())
            ->class('btn btn-blue')->tooltip('VER ORDEN')
            ->dispatchTo('fisioterapia.ordenes.ordenes-main', 'fisioterapia:ver-orden', ['id' => (string) $row->id])];
    }
}