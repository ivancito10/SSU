<?php

namespace App\Livewire\KardexContable;

use App\Models\Farmacia\MedicamentoSolicitud;
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

final class MedicamentoSalidaTable extends PowerGridComponent
{
    use WithExport;
    public string $fechaInicio = '';
    public string $fechaFin = '';
    public bool $buscarPresionado = false;
    public int $idUnidad = 0;

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
        //dd($this->idUnidad);
        if (!$this->buscarPresionado || empty($this->fechaInicio) || empty($this->fechaFin)) {
            return DB::table('farmacia.medicamento_solicitud')->whereRaw('1 = 0');
        }
        
        return MedicamentoSolicitud::query()
            ->join('farmacia.solicitud as s', 's.id', '=', 'farmacia.medicamento_solicitud.id_solicitud')
            ->join('farmacia.medicamento as m', 'm.id', '=', 'farmacia.medicamento_solicitud.id_medicamento')
            ->where('s.estado_validacion', 'ENTREGADO')
            ->where('s.id_unidad_recepciona', $this->idUnidad)
            ->whereBetween('s.fecha_entregado', [$this->fechaInicio . ' 00:00:00', $this->fechaFin . ' 23:59:59'])
            ->select([
                'farmacia.medicamento_solicitud.id as id',
                'm.codigo_medicamento',
                'm.nombre as descripcion_medicamento',
                'm.forma_farmaceutica',
                's.fecha_entregado as fecha_salida',
                DB::raw("CASE 
                WHEN s.farmacia IS TRUE THEN 'FARMACIA' 
                ELSE COALESCE(s.nombre_unidad_solicita, s.nombre_unidad_servicio) 
            END as unidad_solicitante"),
                'farmacia.medicamento_solicitud.cantidad_entregada',
                'm.cuenta_contable',
            ]);
        // Subconsulta para obtener el último precio unitario de movimiento para cada solicitud y medicamento
        // Subconsulta para obtener ÚNICAMENTE el precio PEPS del registro MÁS RECIENTE de cada medicamento por solicitud
        $ultimosPreciosMovimiento = DB::table('farmacia.movimiento as m_sub')
            ->select('m_sub.id_corresponde', 'm_sub.id_medicamento', 'm_sub.precio_unitario_peps')
            ->where('m_sub.id_identificador_tabla', 2)
            ->whereRaw('m_sub.id = (
            select max(m_inner.id)
            from farmacia.movimiento m_inner
            where m_inner.id_identificador_tabla = 2
              and m_inner.id_corresponde = m_sub.id_corresponde
              and m_inner.id_medicamento = m_sub.id_medicamento
        )');

        return MedicamentoSolicitud::query()
            ->join('farmacia.solicitud as s', 's.id', '=', 'farmacia.medicamento_solicitud.id_solicitud')
            ->join('farmacia.medicamento as m', 'm.id', '=', 'farmacia.medicamento_solicitud.id_medicamento')
            ->leftJoinSub($ultimosPreciosMovimiento, 'mov_precio', function ($join) {
                $join->on('s.id', '=', 'mov_precio.id_corresponde')
                    ->on('farmacia.medicamento_solicitud.id_medicamento', '=', 'mov_precio.id_medicamento');
            })
            ->where('s.estado_validacion', 'ENTREGADO')
            ->where('s.id_unidad_recepciona', $this->idUnidad)
            ->whereBetween('s.fecha_entregado', [
                $this->fechaInicio . ' 00:00:00',
                $this->fechaFin . ' 23:59:59'
            ])
            ->select([
                'farmacia.medicamento_solicitud.id as id',
                'm.codigo_medicamento',
                'm.nombre as descripcion_medicamento',
                'm.forma_farmaceutica',
                's.fecha_entregado as fecha_salida',
                DB::raw("CASE 
                WHEN s.farmacia IS TRUE THEN 'FARMACIA' 
                ELSE COALESCE(s.nombre_unidad_solicita, s.nombre_unidad_servicio) 
            END as unidad_solicitante"),
                'farmacia.medicamento_solicitud.cantidad_entregada',
                DB::raw('COALESCE(mov_precio.precio_unitario_peps, 0) as precio_unitario'),
                DB::raw('(farmacia.medicamento_solicitud.cantidad_entregada * COALESCE(mov_precio.precio_unitario_peps, 0)) as precio_total'),
                'm.cuenta_contable',
            ]);

        // 1. Subconsulta de stock sin duplicados (Lote más reciente)
        $stockSub = DB::table('farmacia.medicamento_stock')
            ->selectRaw('DISTINCT ON (id_medicamento, nro_lote) id_medicamento, nro_lote, precio_unitario')
            ->orderBy('id_medicamento')
            ->orderBy('nro_lote')
            ->orderByDesc('id');

        // 2. Subconsulta de movimientos (Salidas)
        $movimientosSub = DB::table('farmacia.movimiento as mov')
            ->joinSub($stockSub, 'st', function ($join) {
                $join->on('st.id_medicamento', '=', 'mov.id_medicamento')
                    ->on('st.nro_lote', '=', 'mov.nro_lote');
            })
            ->where('mov.id_identificador_tabla', 2)
            ->where('mov.cantidad_salida', '>', 0)
            ->select(
                'mov.id_corresponde',
                'mov.id_medicamento',
                DB::raw('MAX(st.precio_unitario) AS precio_unitario')
            )
            ->groupBy('mov.id_corresponde', 'mov.id_medicamento');

        // 3. Consulta principal
        return DB::table('farmacia.medicamento_solicitud as ms')
            ->join('farmacia.medicamento as m', 'm.id', '=', 'ms.id_medicamento')
            ->join('farmacia.solicitud as s', 's.id', '=', 'ms.id_solicitud')
            ->leftJoinSub($movimientosSub, 'mov', function ($join) {
                $join->on('mov.id_corresponde', '=', 's.id')
                    ->on('mov.id_medicamento', '=', 'ms.id_medicamento');
            })
            ->where('s.estado_validacion', 'ENTREGADO')
            ->where('ms.cantidad_entregada', '>', 0)

            // Filtros opcionales
            ->when(
                $this->idUnidad,
                fn($query) =>
                $query->where('s.id_unidad_recepciona', $this->idUnidad)
            )
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
                'm.codigo_medicamento',
                'm.nombre as descripcion_medicamento',
                'm.forma_farmaceutica',
                's.fecha_entregado as fecha_salida',
                DB::raw("
                    CASE 
                        WHEN s.farmacia IS TRUE THEN 'FARMACIA'
                        ELSE COALESCE(s.nombre_unidad_solicita, s.nombre_unidad_servicio)
                    END AS unidad_solicitante
                "),
                'ms.cantidad_entregada',
                DB::raw('COALESCE(mov.precio_unitario, 0) AS precio_unitario'),
                DB::raw('(ms.cantidad_entregada * COALESCE(mov.precio_unitario, 0)) AS precio_total'),
                'm.cuenta_contable'
            ]);

        // Si no se ha presionado el botón Buscar o faltan fechas, retorna una consulta vacía
        if (!$this->buscarPresionado || empty($this->fechaInicio) || empty($this->fechaFin)) {
            return DB::table('farmacia.medicamento_solicitud')->whereRaw('1 = 0');
        }

        $fechaInicioParsed = Carbon::parse($this->fechaInicio)->startOfDay();
        $fechaFinParsed    = Carbon::parse($this->fechaFin)->endOfDay();

        return DB::table('farmacia.medicamento_solicitud as ms')
            ->join('farmacia.solicitud as s', 's.id', '=', 'ms.id_solicitud')
            ->join('farmacia.medicamento as m', 'm.id', '=', 'ms.id_medicamento')
            // OPCIÓN A: Unir movimiento mediante id_corresponde = ms.id (detalle)
            ->join('farmacia.movimiento as mov', function ($join) {
                $join->on('mov.id_corresponde', '=', 'ms.id')
                    ->where('mov.id_identificador_tabla', '=', 2) // 2 = Solicitud
                    ->where('mov.cantidad_salida', '>', 0);
            })
            ->leftJoin('farmacia.medicamento_stock as ms_stock', function ($join) {
                $join->on('ms_stock.id_medicamento', '=', 'mov.id_medicamento')
                    ->on('ms_stock.nro_lote', '=', 'mov.nro_lote');
            })
            ->where('s.estado', true)
            ->whereNotNull('s.fecha_entregado')
            ->where('ms.cantidad_entregada', '>', 0)
            ->whereBetween('s.fecha_entregado', [$fechaInicioParsed, $fechaFinParsed])
            ->select([
                'ms.id as id',
                'm.codigo_medicamento as codigo',
                'm.nombre as descripcion',
                'm.forma_farmaceutica',
                's.fecha_entregado as fecha_salida',
                's.nombre_unidad_solicita as unidad_solicitante',
                'mov.cantidad_salida as cantidad',
                DB::raw("COALESCE(mov.precio_unitario_peps, ms_stock.precio_unitario, 0) as precio_unitario"),
                DB::raw("
                ROUND(
                    (mov.cantidad_salida * COALESCE(mov.precio_unitario_peps, ms_stock.precio_unitario, 0))::numeric, 2
                ) as precio_total
            "),
                'm.cuenta_contable',
            ])
            ->orderByRaw('s.fecha_entregado ASC, ms.id ASC');*/
        if (!$this->buscarPresionado || empty($this->fechaInicio) || empty($this->fechaFin)) {
            return DB::table('farmacia.medicamento_solicitud')->whereRaw('1 = 0');
        }

        $fechaInicioParsed = Carbon::parse($this->fechaInicio)->startOfDay();
        $fechaFinParsed    = Carbon::parse($this->fechaFin)->endOfDay();

        // 1. Subconsulta Nivel 1: Corrección con DB::raw("DISTINCT ON (...)")
        $subMov = DB::table('farmacia.movimiento as m')
            ->select(DB::raw('DISTINCT ON (m.id_corresponde) m.id_corresponde, s.precio_unitario'))
            ->join('farmacia.medicamento_stock as s', function ($join) {
                $join->on('s.id_medicamento', '=', 'm.id_medicamento')
                    ->on('s.nro_lote', '=', 'm.nro_lote')
                    ->where('s.id_unidad', '=', 89);
            })
            ->where('m.id_identificador_tabla', 2)
            ->where('m.id_unidad', 89)
            ->where('m.cantidad_salida', '>', 0)
            ->orderBy('m.id_corresponde')
            ->orderByDesc('m.id');

        // 2. Subconsulta Nivel 2: Corrección con DB::raw("DISTINCT ON (...)")
        $subStock = DB::table('farmacia.medicamento_stock')
            ->select(DB::raw('DISTINCT ON (id_medicamento) id_medicamento, precio_unitario'))
            ->where('id_unidad', 89)
            ->orderBy('id_medicamento')
            ->orderByDesc('id');

        // 3. Consulta Principal
        return DB::table('farmacia.solicitud as s')
            ->select([
                'ms.id as id',
                'm.codigo_medicamento',
                'm.nombre as descripcion_medicamento',
                'm.forma_farmaceutica',
                's.fecha_entregado as fecha_salida',
                DB::raw("
                CASE 
                    WHEN s.farmacia = TRUE THEN 'FARMACIA'
                    WHEN s.nombre_unidad_servicio IS NOT NULL AND TRIM(s.nombre_unidad_servicio) <> '' 
                         THEN s.nombre_unidad_servicio
                    ELSE s.nombre_unidad_solicita
                END AS unidad_solicitante
            "),
                'ms.cantidad_entregada',
                DB::raw("COALESCE(mov.precio_unitario, st.precio_unitario, 0)::numeric(12,2) AS precio_unitario"),
                DB::raw("ROUND((ms.cantidad_entregada::numeric * COALESCE(mov.precio_unitario, st.precio_unitario, 0)::numeric), 2) AS precio_total"),
                'm.cuenta_contable',
            ])
            ->join('farmacia.medicamento_solicitud as ms', 'ms.id_solicitud', '=', 's.id')
            ->join('farmacia.medicamento as m', 'm.id', '=', 'ms.id_medicamento')
            ->leftJoinSub($subMov, 'mov', 'mov.id_corresponde', '=', 'ms.id')
            ->leftJoinSub($subStock, 'st', 'st.id_medicamento', '=', 'ms.id_medicamento')
            ->where('s.estado_validacion', 'ENTREGADO')
            ->where('s.estado', true)
            ->where('ms.cantidad_entregada', '>', 0)
            ->where('s.id_unidad_recepciona', 89)
            ->whereBetween('s.fecha_entregado', [$fechaInicioParsed, $fechaFinParsed]);
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
            ->add('descripcion_medicamento')
            ->add('forma_farmaceutica')
            ->add('fecha_salida', fn($row) => \Carbon\Carbon::parse($row->fecha_salida)->format('d/m/Y'))
            ->add('unidad_solicitante')
            ->add('cantidad_entregada')
            ->add('precio_unitario', fn($row) => number_format($row->precio_unitario, 2, '.', ','))
            ->add('precio_total', fn($row) => number_format($row->precio_total, 2, '.', ','))
            ->add('cuenta_contable');
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

            Column::make('CÓDIGO MEDICAMENTO', 'codigo_medicamento')
                ->searchable()
                ->sortable()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass),

            Column::make('DESCRIPCIÓN / NOMBRE', 'descripcion_medicamento')
                ->searchable()
                ->sortable()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute('text-left'),

            Column::make('FORMA FARMACÉUTICA', 'forma_farmaceutica')
                ->searchable()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass),

            Column::make('FECHA DE SALIDA', 'fecha_salida')
                ->sortable()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass),

            Column::make('UNIDAD SOLICITANTE', 'unidad_solicitante')
                ->searchable()
                ->sortable()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute('text-left'),

            Column::make('CANTIDAD', 'cantidad_entregada')
                ->sortable()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass),

            Column::make('PRECIO UNITARIO', 'precio_unitario')
                ->sortable()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute('text-right'),

            Column::make('PRECIO TOTAL', 'precio_total')
                ->sortable()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute('text-right'),

            Column::make('CUENTA CONTABLE', 'cuenta_contable')
                ->searchable()
                ->sortable()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass),
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
    public function actions(MedicamentoSolicitud $row): array
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
