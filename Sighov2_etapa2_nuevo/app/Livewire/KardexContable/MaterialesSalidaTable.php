<?php

namespace App\Livewire\KardexContable;

use App\Models\AlmacenGeneral\MaterialSolicitud;
use Illuminate\Support\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
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

final class MaterialesSalidaTable extends PowerGridComponent
{
    use WithExport;
    public string $fechaInicio = '';
    public string $fechaFin = '';
    public bool $buscarPresionado = false;


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

    #[On('resetearPaginacion')]
    public function resetPagina(): void
    {
        $this->setPage(1);
    }

    public function datasource(): Builder
    {/*
        if (!$this->buscarPresionado || empty($this->fechaInicio) || empty($this->fechaFin)) {
            return MaterialSolicitud::query()->whereRaw('1 = 0');
        }

        return MaterialSolicitud::query()
            ->join('almacen_general.solicitud as s', 's.id', '=', 'almacen_general.material_solicitud.id_solicitud')
            ->join('almacen_general.material as m', 'm.id', '=', 'almacen_general.material_solicitud.id_material')
            ->where('s.estado_validacion', 'ENTREGADO')
            ->where('s.id_unidad_recepciona', 19)
            ->whereBetween('s.fecha_entregado', [$this->fechaInicio, $this->fechaFin])
            ->select([
                'almacen_general.material_solicitud.id',
                'm.codigo as codigo_material',
                'm.nombre_material as descripcion_material',
                'm.unidad as unidad_medida',
                's.fecha_entregado as fecha_salida',
                's.nombre_unidad_solicita as unidad_servicio',
                'almacen_general.material_solicitud.cantidad_entregada as cantidad',
                'm.nro_cuenta as cuenta_contable',
            ]);*/

        if (!$this->buscarPresionado || empty($this->fechaInicio) || empty($this->fechaFin)) {
            return DB::table('almacen_general.material_solicitud')->whereRaw('1 = 0');
        }

        // 1. Subconsulta de stock para obtener el precio por lote
        $stockSub = DB::table('almacen_general.material_stock')
            ->selectRaw('DISTINCT ON (id_material, nro_lote) id_material, nro_lote, precio_unitario')
            ->orderBy('id_material')
            ->orderBy('nro_lote')
            ->orderByDesc('id');

        // 2. Subconsulta de movimientos (Salidas)
        $movimientosSub = DB::table('almacen_general.movimiento as mov')
            ->joinSub($stockSub, 'st', function ($join) {
                $join->on('st.id_material', '=', 'mov.id_material')
                    ->on('st.nro_lote', '=', 'mov.nro_lote');
            })
            ->where('mov.id_identificador_tabla', 2)
            ->where('mov.cantidad_salida', '>', 0)
            ->select(
                'mov.id_corresponde',
                'mov.id_material',
                DB::raw('MAX(st.precio_unitario) AS precio_unitario')
            )
            ->groupBy('mov.id_corresponde', 'mov.id_material');

        // 3. Consulta principal
        return DB::table('almacen_general.material_solicitud as ms')
            ->join('almacen_general.material as m', 'm.id', '=', 'ms.id_material')
            ->join('almacen_general.solicitud as s', 's.id', '=', 'ms.id_solicitud')
            ->leftJoinSub($movimientosSub, 'mov', function ($join) {
                $join->on('mov.id_corresponde', '=', 's.id')
                    ->on('mov.id_material', '=', 'ms.id_material');
            })
            ->where('s.estado_validacion', 'ENTREGADO')
            ->where('ms.cantidad_entregada', '>', 0)
            ->when(
                $this->fechaInicio && $this->fechaFin,
                fn($query) =>
                $query->whereBetween('s.fecha_entregado', [
                    $this->fechaInicio . ' 00:00:00',
                    $this->fechaFin . ' 23:59:59'
                ])
            )

            ->select([
                'ms.id as id',
                'm.codigo as codigo_material',
                'm.nombre_material as nombre_material',
                'm.unidad',
                's.fecha_entregado as fecha_salida',
                DB::raw("COALESCE(s.nombre_unidad_servicio,s.nombre_unidad_solicita) AS unidad_solicitante"),
                'ms.cantidad_entregada as cantidad',
                DB::raw('COALESCE(mov.precio_unitario, 0) AS precio_unitario'),
                DB::raw('(ms.cantidad_entregada * COALESCE(mov.precio_unitario, 0)) AS precio_total'),
                'm.nro_cuenta'
            ]);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('codigo_material')
            ->add('nombre_material')
            ->add('unidad')
            ->add('fecha_salida_formatted', fn($row) => date('d/m/Y', strtotime($row->fecha_salida)))
            ->add('unidad_solicitante')
            ->add('cantidad')
            ->add('precio_unitario', fn($row) => number_format($row->precio_unitario, 2, '.', ','))
            ->add('precio_total', fn($row) => number_format($row->precio_total, 2, '.', ','))
            ->add('nro_cuenta');
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

            Column::make('CÓD. MATERIAL', 'codigo_material')
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass)
                ->searchable()
                ->sortable(),

            Column::make('NOMBRE MATERIAL', 'nombre_material')
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass)
                ->searchable()
                ->sortable(),

            Column::make('UNIDAD DE MEDIDA', 'unidad')
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass)
                ->sortable(),

            Column::make('FECHA DE SALIDA', 'fecha_salida_formatted', 'fecha_salida')
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass)
                ->sortable(),

            Column::make('UNIDAD SOLICITANTE', 'unidad_solicitante')
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass)
                ->searchable()
                ->sortable(),

            Column::make('CANTIDAD', 'cantidad')
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass)
                ->sortable(),

            Column::make('PRECIO UNITARIO', 'precio_unitario')
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass)
                ->sortable(),

            Column::make('PRECIO TOTAL', 'precio_total')
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass)
                ->sortable(),

            Column::make('CUENTA CONTABLE', 'nro_cuenta')
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass)
                ->searchable()
                ->sortable(),
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
    public function actions(MaterialSolicitud $row): array
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
