<?php

namespace App\Livewire\Aportes\AportesVoluntarios;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use App\Services\Tesoreria\RecibosCajaService;
use PowerComponents\LivewirePowerGrid\Button;

final class RecibosCajaVoluntarioTable extends PowerGridComponent
{
    public function datasource(): ?Collection
    {
        $servicio = new RecibosCajaService();
        $recibos = $servicio->listadoRecibos();
        // Evita errores si no hay datos o si la API devolvió null
        if (empty($recibos)) {
            return collect();
        }

        $recibos = collect($recibos)
            // Filtrar solo los que son aportes
            ->filter(function ($item) {
                return isset($item['es_aporte'], $item['estado_recibo'])
                    && $item['es_aporte'] === true
                    && $item['estado_recibo'] === 'VALIDADO';
            })

            // Convertir cada elemento del array asociativo a objeto
            ->map(function ($item) {
                $obj = (object) $item;
                $obj->recibo_completo = $obj->nro_recibo . '/' . \Carbon\Carbon::parse($obj->created_at)->format('Y');
                return $obj;
            })

            // Ordenar de más reciente a más antiguo
            ->sortByDesc(fn($obj) => $obj->created_at)
            ->values();
        return $recibos;
    }

    public function setUp(): array
    {
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

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('nro_recibo')
            ->add('created_at')
            ->add('recibo_completo', function ($row) {
                return $row->nro_recibo . '/' . \Carbon\Carbon::parse($row->created_at)->format('Y');
            })
            ->add('concepto')
            ->add('fecha_recibo', function ($row) {
                return \Carbon\Carbon::parse($row->fecha_recibo)->format('d/m/Y');
            })

            //->add('es_aporte')
            ->add('es_aporte', function ($dish) {
                if ($dish->es_aporte) {
                    return "<span style='color: green'><b>SI</b></span>";
                } else {
                    return "<span style='color:#cb4335'><b>NO</b></span>";
                }
            })
            ->add('periodo_inicio', function ($row) {
                return \Carbon\Carbon::parse($row->periodo_inicio)->format('d/m/Y');
            })
            ->add('periodo_fin', function ($row) {
                return \Carbon\Carbon::parse($row->periodo_fin)->format('d/m/Y');
            })
            //->add('total_recibo')
            ->add('total_recibo', function ($row) {
                return number_format($row->total_recibo, 2, '.', ',');
            });
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->index()
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3; color:white;')
                ->bodyAttribute('text-center'),

            Column::make('# RECIBO', 'recibo_completo')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3; color:white;')
                ->bodyAttribute('text-center',)
                // ->sortable()
                ->searchable(),

            Column::make('FECHA RECIBO', 'fecha_recibo')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3; color:white;')
                ->bodyAttribute('text-center',)
                // ->sortable()
                ->searchable(),

            Column::make('CONCEPTO', 'concepto')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3; color:white;')
                ->bodyAttribute('text-left',)
                // ->sortable()
                ->searchable(),

            Column::make('ES APORTE', 'es_aporte')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3; color:white;')
                ->bodyAttribute('text-center',)
                // ->sortable()
                ->searchable(),

            Column::make('PERIODO INICIO', 'periodo_inicio')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3; color:white;')
                ->bodyAttribute('text-center',)
                // ->sortable()
                ->searchable(),

            Column::make('PERIODO FIN', 'periodo_fin')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3; color:white;')
                ->bodyAttribute('text-center',)
                // ->sortable()
                ->searchable(),

            Column::make('TOTAL RECIBO', 'total_recibo')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3; color:white;')
                ->bodyAttribute('text-right',)
                // ->sortable()
                ->searchable(),

            Column::action('OPCIONES')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3; color:white;')
                ->bodyAttribute('text-center',),
        ];
    }
    public function actions($row): array
    {
        return [
            Button::add('registrar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right-circle-fill" viewBox="0 0 16 16">
                    <path d="M8 0a8 8 0 1 1 0 16A8 8 0 0 1 8 0M4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5z"/>
                    </svg>')
                ->class('btn btn-orange')
                ->tooltip('SELECCIONAR RECIBO')
                // resources/views/livewire/Aportes/aportes-voluntarios/aporte-voluntario-nuevo.blade.php
                ->dispatchTo('aportes.aportes-voluntarios.aporte-voluntario-nuevo', 'guardarReciboOficialVoluntarios', ['data' => $row->id])
        ];
    }
}
