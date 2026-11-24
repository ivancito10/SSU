<?php

namespace App\Livewire\KardexContable;

use App\Models\Farmacia\Medicamento;
use Illuminate\Support\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
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

final class MedicamentoCuenta121Table extends PowerGridComponent
{
    use WithExport;
    public string $fechaInicio = '';
    public string $fechaFin = '';
    public bool $buscarPresionado = false;
    public int $idUnidad = 89;

    public string $primaryKey = 'm.id';
    public string $sortField = 'm.codigo_medicamento';
    public string $sortDirection = 'asc';

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

    public function datasource(): Builder
    {
        // Retorno de consulta vacía si no se presiona el botón de búsqueda o faltan fechas
        if (!$this->buscarPresionado || empty($this->fechaInicio) || empty($this->fechaFin)) {
            return DB::table('farmacia.medicamento as m')->whereRaw('1 = 0');
        }

        // ---------------------------------------------------------------------
        // 0. SUBQUERY BASE: PRECIO DE ÚLTIMO LOTE
        // ---------------------------------------------------------------------
        $ultimoLote = DB::table('farmacia.medicamento_stock as ms_sub')
            ->selectRaw('DISTINCT ON (ms_sub.id_medicamento) ms_sub.id_medicamento, ms_sub.precio_unitario')
            ->where('ms_sub.id_unidad', $this->idUnidad)
            ->orderBy('ms_sub.id_medicamento')
            ->orderBy('ms_sub.id', 'desc');

        // ---------------------------------------------------------------------
        // 1. SALDOS INICIALES
        // ---------------------------------------------------------------------
        $subInicial = DB::table('farmacia.cierre_gestion as cg')
            ->join('farmacia.movimiento as mov', function ($join) {
                $join->on('mov.id_corresponde', '=', 'cg.id')
                    ->where('mov.id_identificador_tabla', '=', 4)
                    ->on('mov.id_medicamento', '=', 'cg.id_medicamento')
                    ->on('mov.id_unidad', '=', 'cg.id_unidad');
            })
            ->leftJoin('farmacia.medicamento_stock as ms', function ($join) {
                $join->on('ms.id_medicamento', '=', 'mov.id_medicamento')
                    ->on('ms.id_unidad', '=', 'mov.id_unidad')
                    ->on('ms.nro_lote', '=', 'mov.nro_lote');
            })
            ->whereIn('cg.id_unidad', [76, $this->idUnidad])
            ->whereBetween('cg.fecha_registro', [
                $this->fechaInicio . ' 00:00:00',
                $this->fechaFin . ' 23:59:59'
            ])
            ->select([
                'cg.id_medicamento',
                DB::raw('SUM(cg.cantidad_fisica) as cant_ini'),
                DB::raw('SUM(cg.cantidad_fisica) * COALESCE(MAX(ms.precio_unitario), 0) as monto_ini')
            ])
            ->groupBy('cg.id_medicamento')
            ->havingRaw('SUM(cg.cantidad_fisica) > 0');

        // ---------------------------------------------------------------------
        // 2. INGRESOS
        // ---------------------------------------------------------------------
        $subIngresos = DB::table('farmacia.detalle_ingreso as di')
            ->join('farmacia.ingreso as i', 'di.id_ingreso', '=', 'i.id')
            ->where('i.id_institucion_externa', '!=', 1)
            ->whereBetween('i.fecha_ingreso', [
                $this->fechaInicio . ' 00:00:00',
                $this->fechaFin . ' 23:59:59'
            ])
            ->select([
                'di.id_medicamento',
                DB::raw('SUM(di.cantidad) as cant_ing'),
                DB::raw('SUM(di.precio_total) as monto_ing')
            ])
            ->groupBy('di.id_medicamento');

        // ---------------------------------------------------------------------
        // 3. REINGRESOS DE ALMACÉN DE MEDICAMENTOS
        // ---------------------------------------------------------------------
        $subReingresosAlmacen = DB::table('farmacia.detalle_ingreso as di')
            ->join('farmacia.ingreso as i', 'di.id_ingreso', '=', 'i.id')
            ->leftJoinSub($ultimoLote, 'ultimo_lote', function ($join) {
                $join->on('di.id_medicamento', '=', 'ultimo_lote.id_medicamento');
            })
            ->where('i.id_institucion_externa', 1)
            ->where('i.estado', true)
            ->whereBetween('i.fecha_ingreso', [
                $this->fechaInicio . ' 00:00:00',
                $this->fechaFin . ' 23:59:59'
            ])
            ->select([
                'di.id_medicamento',
                DB::raw('SUM(di.cantidad) as cant_reing_alm'),
                DB::raw('SUM(di.cantidad) * COALESCE(MAX(ultimo_lote.precio_unitario), 0) as monto_reing_alm')
            ])
            ->groupBy('di.id_medicamento');

        // ---------------------------------------------------------------------
        // 4. REINGRESOS DE FARMACIA (STOCK EN FARMACIA)
        // ---------------------------------------------------------------------
        $subReingresosFarmacia = DB::table('farmacia.medicamento_stock as ms')
            ->where('ms.id_unidad', $this->idUnidad)
            ->whereBetween('ms.created_at', [
                $this->fechaInicio . ' 00:00:00',
                $this->fechaFin . ' 23:59:59'
            ])
            ->select([
                'ms.id_medicamento',
                DB::raw('SUM(ms.stock_actualizado) as cant_reing_far'),
                DB::raw('SUM(ms.stock_actualizado) * COALESCE(MAX(ms.precio_unitario), 0) as monto_reing_far')
            ])
            ->groupBy('ms.id_medicamento')
            ->havingRaw('SUM(ms.stock_actualizado) > 0');

        // ---------------------------------------------------------------------
        // 5. TOTAL DE SALIDAS
        // ---------------------------------------------------------------------
        $subSalidas = DB::table('farmacia.medicamento_solicitud as ms')
            ->join('farmacia.solicitud as s', 's.id', '=', 'ms.id_solicitud')
            ->leftJoinSub($ultimoLote, 'st', function ($join) {
                $join->on('ms.id_medicamento', '=', 'st.id_medicamento');
            })
            ->where('ms.cantidad_entregada', '>', 0)
            ->where('s.estado_validacion', 'ENTREGADO')
            ->where('s.id_unidad_recepciona', $this->idUnidad)
            ->whereBetween('s.fecha_entregado', [
                $this->fechaInicio . ' 00:00:00',
                $this->fechaFin . ' 23:59:59'
            ])
            ->select([
                'ms.id_medicamento',
                DB::raw('SUM(ms.cantidad_entregada) as cant_sal'),
                DB::raw('SUM(ms.cantidad_entregada) * COALESCE(MAX(st.precio_unitario), 0) as monto_sal')
            ])
            ->groupBy('ms.id_medicamento');

        // ---------------------------------------------------------------------
        // 6. BAJAS REGISTRADAS
        // ---------------------------------------------------------------------
        $subBajas = DB::table('farmacia.cierre_gestion as cg')
            ->leftJoinSub($ultimoLote, 'st', function ($join) {
                $join->on('cg.id_medicamento', '=', 'st.id_medicamento');
            })
            ->where('cg.id_unidad', $this->idUnidad)
            ->whereNotNull('cg.cantidad_bajas')
            ->where('cg.cantidad_bajas', '<>', 0)
            ->whereBetween('cg.fecha_registro', [
                $this->fechaInicio . ' 00:00:00',
                $this->fechaFin . ' 23:59:59'
            ])
            ->select([
                'cg.id_medicamento',
                DB::raw('SUM(cg.cantidad_bajas) as cant_baj'),
                DB::raw('SUM(cg.cantidad_bajas) * COALESCE(MAX(st.precio_unitario), 0) as monto_baj')
            ])
            ->groupBy('cg.id_medicamento');

        // ---------------------------------------------------------------------
        // 7. CONSULTA PRINCIPAL
        // ---------------------------------------------------------------------
        return DB::table('farmacia.medicamento as m')
            ->leftJoinSub($subInicial, 'ini', 'ini.id_medicamento', '=', 'm.id')
            ->leftJoinSub($subIngresos, 'ing', 'ing.id_medicamento', '=', 'm.id')
            ->leftJoinSub($subReingresosAlmacen, 're_alm', 're_alm.id_medicamento', '=', 'm.id')
            ->leftJoinSub($subReingresosFarmacia, 're_far', 're_far.id_medicamento', '=', 'm.id')
            ->leftJoinSub($subSalidas, 'sal', 'sal.id_medicamento', '=', 'm.id')
            ->leftJoinSub($subBajas, 'baj', 'baj.id_medicamento', '=', 'm.id')
            ->select([
                'm.id',
                'm.codigo_medicamento',
                'm.nombre as nombre_medicamento',
                'm.forma_farmaceutica',

                // Saldo Inicial
                DB::raw('COALESCE(ini.cant_ini, 0) as cant_inicial'),
                DB::raw('COALESCE(ini.monto_ini, 0) as monto_inicial'),

                // Ingresos
                DB::raw('COALESCE(ing.cant_ing, 0) as cant_ingresos'),
                DB::raw('COALESCE(ing.monto_ing, 0) as monto_ingresos'),

                // Reingresos Almacén
                DB::raw('COALESCE(re_alm.cant_reing_alm, 0) as cant_reing_alm'),
                DB::raw('COALESCE(re_alm.monto_reing_alm, 0) as monto_reing_alm'),

                // Reingresos Farmacia
                DB::raw('COALESCE(re_far.cant_reing_far, 0) as cant_reing_far'),
                DB::raw('COALESCE(re_far.monto_reing_far, 0) as monto_reing_far'),

                // Salidas
                DB::raw('COALESCE(sal.cant_sal, 0) as cant_salidas'),
                DB::raw('COALESCE(sal.monto_sal, 0) as monto_salidas'),

                // Bajas
                DB::raw('COALESCE(baj.cant_baj, 0) as cant_bajas'),
                DB::raw('COALESCE(baj.monto_baj, 0) as monto_bajas'),

                // Saldo Final (Aritmética unificada)
                DB::raw('(COALESCE(ini.cant_ini,0) + COALESCE(ing.cant_ing,0) + COALESCE(re_alm.cant_reing_alm,0) + COALESCE(re_far.cant_reing_far,0) - COALESCE(sal.cant_sal,0) - COALESCE(baj.cant_baj,0)) as cant_final'),
                DB::raw('(COALESCE(ini.monto_ini,0) + COALESCE(ing.monto_ing,0) + COALESCE(re_alm.monto_reing_alm,0) + COALESCE(re_far.monto_reing_far,0) - COALESCE(sal.monto_sal,0) - COALESCE(baj.monto_baj,0)) as monto_final'),
            ]);





        /*
        // Retorno de consulta vacía si no se ha presionado el botón de búsqueda o faltan fechas
        if (!$this->buscarPresionado || empty($this->fechaInicio) || empty($this->fechaFin)) {
            return DB::table('farmacia.medicamento_solicitud')->whereRaw('1 = 0');
        }

        //SALDOS INICIALES
        return DB::table('farmacia.cierre_gestion as cg')
            ->join('farmacia.medicamento as m', 'm.id', '=', 'cg.id_medicamento')
            ->join('farmacia.movimiento as mov', function ($join) {
                $join->on('mov.id_corresponde', '=', 'cg.id')
                    ->where('mov.id_identificador_tabla', '=', 4)
                    ->on('mov.id_medicamento', '=', 'cg.id_medicamento')
                    ->on('mov.id_unidad', '=', 'cg.id_unidad');
            })
            ->leftJoin('farmacia.medicamento_stock as ms', function ($join) {
                $join->on('ms.id_medicamento', '=', 'mov.id_medicamento')
                    ->on('ms.id_unidad', '=', 'mov.id_unidad')
                    ->on('ms.nro_lote', '=', 'mov.nro_lote');
            })
            ->whereIn('cg.id_unidad', [76, 89])
            ->whereBetween('cg.fecha_registro', [
                $this->fechaInicio . ' 00:00:00',
                $this->fechaFin . ' 23:59:59'
            ])
            ->select([
                'm.id as id',
                'm.codigo_medicamento',
                'm.nombre as nombre_medicamento',
                DB::raw('SUM(cg.cantidad_fisica) as cantidad_total'),
                DB::raw('SUM(cg.cantidad_fisica) * COALESCE(MAX(ms.precio_unitario), 0) as monto_total'),
            ])
            ->groupBy('m.id', 'm.codigo_medicamento', 'm.nombre')
            ->havingRaw('SUM(cg.cantidad_fisica) > 0');

        //return Medicamento::query();
        
        /* INGRESOS
        return Medicamento::query()
            ->join('farmacia.detalle_ingreso as di', 'farmacia.medicamento.id', '=', 'di.id_medicamento')
            ->join('farmacia.ingreso as i', 'di.id_ingreso', '=', 'i.id')
            ->where('i.id_institucion_externa', '!=', 1)
            ->when($this->fechaInicio && $this->fechaFin, function ($query) {
                $query->whereBetween('i.fecha_ingreso', [$this->fechaInicio, $this->fechaFin]);
            })
            ->groupBy(
                'farmacia.medicamento.id',
                'farmacia.medicamento.codigo_medicamento',
                'farmacia.medicamento.nombre',
                'farmacia.medicamento.forma_farmaceutica'
            )
            ->select(
                'farmacia.medicamento.id',
                'farmacia.medicamento.codigo_medicamento',
                'farmacia.medicamento.nombre as nombre',
                'farmacia.medicamento.forma_farmaceutica',
                DB::raw('SUM(di.cantidad) as cantidad_ingresos'),
                DB::raw('SUM(di.precio_total) as monto_ingreso')
            );

        //REINGRESOS DE ALMACEN MEDICAMENTO 

        $ultimoStock = DB::table('farmacia.medicamento_stock as ms_last')
            ->select('ms_last.id_medicamento', 'ms_last.precio_unitario')
            ->whereRaw('ms_last.id = (
            select max(ms_inner.id) 
            from farmacia.medicamento_stock ms_inner 
            where ms_inner.id_medicamento = ms_last.id_medicamento
        )');

        return Medicamento::query()
            ->join('farmacia.detalle_ingreso as di', 'farmacia.medicamento.id', '=', 'di.id_medicamento')
            ->join('farmacia.ingreso as i', 'di.id_ingreso', '=', 'i.id')
            ->leftJoinSub($ultimoStock, 'ultimo_lote', function ($join) {
                $join->on('farmacia.medicamento.id', '=', 'ultimo_lote.id_medicamento');
            })
            ->where('i.id_institucion_externa', 1) // Reingresos
            ->where('i.estado', true)
            ->when($this->fechaInicio && $this->fechaFin, function ($query) {
                $query->whereBetween('i.fecha_ingreso', [
                    $this->fechaInicio . ' 00:00:00',
                    $this->fechaFin . ' 23:59:59'
                ]);
            })
            ->groupBy(
                'farmacia.medicamento.id',
                'farmacia.medicamento.codigo_medicamento',
                'farmacia.medicamento.nombre',
                'farmacia.medicamento.forma_farmaceutica',
                'ultimo_lote.precio_unitario'
            )
            ->select(
                'farmacia.medicamento.id as id',
                'farmacia.medicamento.codigo_medicamento',
                'farmacia.medicamento.nombre as nombre',
                'farmacia.medicamento.forma_farmaceutica',
                DB::raw('SUM(di.cantidad) as cantidad_total'),
                DB::raw('SUM(di.cantidad) * COALESCE(ultimo_lote.precio_unitario, 0) as monto_total')
            );


        /*
        //REINGRESOS SIN PRECIO 
        return Medicamento::query()
            ->join('farmacia.detalle_ingreso as di', 'farmacia.medicamento.id', '=', 'di.id_medicamento')
            ->join('farmacia.ingreso as i', 'di.id_ingreso', '=', 'i.id')
            ->where('i.id_institucion_externa', 1) // Filtra exclusivamente Reingresos
            ->where('i.estado', true)
            ->when($this->fechaInicio && $this->fechaFin, function ($query) {
                $query->whereBetween('i.fecha_ingreso', [$this->fechaInicio . ' 00:00:00', $this->fechaFin . ' 23:59:59']);
            })
            ->groupBy(
                'farmacia.medicamento.id',
                'farmacia.medicamento.codigo_medicamento',
                'farmacia.medicamento.nombre',
                'farmacia.medicamento.forma_farmaceutica'
            )
            ->select(
                'farmacia.medicamento.id as id',
                'farmacia.medicamento.codigo_medicamento',
                'farmacia.medicamento.nombre as nombre',
                'farmacia.medicamento.forma_farmaceutica',
                DB::raw('SUM(di.cantidad) as cantidad_total'),
                DB::raw('SUM(di.precio_total) as monto_total')
            );



        //REINGRESO DE FARMACIA
        return Medicamento::query()
            ->join('farmacia.medicamento_stock as ms', 'farmacia.medicamento.id', '=', 'ms.id_medicamento')
            ->when($this->fechaFin, function ($query) {
                // Se filtra directamente por la fecha de creación del registro de stock
                $query->whereDate('ms.created_at', '<=', $this->fechaFin);
            })
            ->groupBy(
                'farmacia.medicamento.id',
                'farmacia.medicamento.codigo_medicamento',
                'farmacia.medicamento.nombre',
                'farmacia.medicamento.forma_farmaceutica'
            )
            ->havingRaw('SUM(ms.stock_actualizado) > 0')
            ->select([
                'farmacia.medicamento.id as id',
                'farmacia.medicamento.codigo_medicamento',
                'farmacia.medicamento.nombre as nombre',
                'farmacia.medicamento.forma_farmaceutica',
                DB::raw('MAX(ms.precio_unitario) as precio'),
                DB::raw('SUM(ms.stock_actualizado) as cantidad_total'),
                DB::raw('SUM(ms.stock_actualizado) * MAX(ms.precio_unitario) as monto_total'),
            ]);

        //TOTAL DE SALIDAS
        // PRECIOS OBTENIDOS DESDE MEDICAMENTO STOCK
        $subStock = DB::table('farmacia.medicamento_stock as ms_sub')
            ->selectRaw('DISTINCT ON (ms_sub.id_medicamento) ms_sub.id_medicamento, ms_sub.precio_unitario')
            ->where('ms_sub.id_unidad', 89)
            ->orderBy('ms_sub.id_medicamento')
            ->orderBy('ms_sub.id', 'asc');

        // Consulta principal
        return DB::table('farmacia.medicamento_solicitud as ms')
            ->join('farmacia.medicamento as m', 'm.id', '=', 'ms.id_medicamento')
            ->join('farmacia.solicitud as s', 's.id', '=', 'ms.id_solicitud')
            ->leftJoinSub($subStock, 'st', function ($join) {
                $join->on('st.id_medicamento', '=', 'm.id');
            })
            ->where('ms.cantidad_entregada', '>', 0)
            ->where('s.estado_validacion', 'ENTREGADO')
            ->where('s.id_unidad_recepciona', 89)
            ->whereBetween('s.fecha_entregado', ['2026-01-01 00:00:00', '2026-12-31 23:59:59'])
            ->select([
                'm.codigo_medicamento',
                'm.nombre as nombre_medicamento',
                'm.forma_farmaceutica',
                DB::raw('SUM(ms.cantidad_entregada) as cantidad_total'),
                DB::raw('SUM(ms.cantidad_entregada) * COALESCE(st.precio_unitario, 0) as monto_total')
            ])
            ->groupBy([
                'm.codigo_medicamento',
                'm.nombre',
                'm.forma_farmaceutica',
                'st.precio_unitario'
            ]);
        //BAJAS REGISTRADAS
        $ultimoLote = DB::table('farmacia.medicamento_stock')
            ->select('id_medicamento', 'precio_unitario')
            ->where('id_unidad', $this->idUnidad)
            ->whereRaw('id IN (
            SELECT MAX(id)
            FROM farmacia.medicamento_stock
            WHERE id_unidad = ?
            GROUP BY id_medicamento
        )', [$this->idUnidad]);

        return DB::table('farmacia.cierre_gestion as cg')
            ->join('farmacia.medicamento as m', 'm.id', '=', 'cg.id_medicamento')
            ->leftJoinSub($ultimoLote, 'st', function ($join) {
                $join->on('st.id_medicamento', '=', 'm.id');
            })
            ->select([
                'm.id',
                'm.codigo_medicamento',
                'm.nombre as nombre_medicamento',
                'm.forma_farmaceutica',
            ])
            ->selectRaw('SUM(cg.cantidad_bajas) AS cantidad_total')
            ->selectRaw('SUM(cg.cantidad_bajas) * COALESCE(st.precio_unitario, 0) AS monto_total')
            ->where('cg.id_unidad', $this->idUnidad)
            ->whereNotNull('cg.cantidad_bajas')
            ->where('cg.cantidad_bajas', '<>', 0)
            ->when($this->fechaInicio && $this->fechaFin, function ($query) {
                $query->whereBetween('cg.fecha_registro', [$this->fechaInicio . ' 00:00:00', $this->fechaFin . ' 23:59:59']);
            })
            ->groupBy(
                'm.id',
                'm.codigo_medicamento',
                'm.nombre',
                'm.forma_farmaceutica',
                'st.precio_unitario'
            )
            ->orderBy('m.codigo_medicamento');*/
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
            ->add('nombre_medicamento')
            ->add('forma_farmaceutica')

            // Cantidades
            ->add('cant_inicial')
            ->add('cant_ingresos')
            ->add('cant_reing_alm')
            ->add('cant_reing_far')
            ->add('cant_salidas')
            ->add('cant_bajas')
            ->add('cant_final')

            // Formateo de Montos
            ->add('monto_inicial', fn($row) => number_format($row->monto_inicial, 2, '.', ','))
            ->add('monto_ingresos', fn($row) => number_format($row->monto_ingresos, 2, '.', ','))
            ->add('monto_reing_alm', fn($row) => number_format($row->monto_reing_alm, 2, '.', ','))
            ->add('monto_reing_far', fn($row) => number_format($row->monto_reing_far, 2, '.', ','))
            ->add('monto_salidas', fn($row) => number_format($row->monto_salidas, 2, '.', ','))
            ->add('monto_bajas', fn($row) => number_format($row->monto_bajas, 2, '.', ','))
            ->add('monto_final', fn($row) => number_format($row->monto_final, 2, '.', ','));
    }

    public function columns(): array
    {
        $headerStyle = 'font-size: 14px; background-color: #2471a3; color: white;';
        $headerClass = 'text-center';
        $bodyClass   = 'text-center';
        $bodyRight   = 'text-right font-medium';

        return [
            Column::make('#', 'id')
                ->index()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass),

            Column::make('Código', 'codigo_medicamento', 'm.codigo_medicamento')
                ->sortable()
                ->searchable()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass),

            Column::make('Medicamento', 'nombre_medicamento', 'm.nombre')
                ->sortable()
                ->searchable()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute('text-left'),

            Column::make('Forma Farmacéutica', 'forma_farmaceutica', 'm.forma_farmaceutica')
                ->sortable()
                ->searchable()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass),

            // Saldo Inicial
            Column::make('Cant. Saldo Inicial', 'cant_inicial')
                ->sortable()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass),

            Column::make('Monto Saldo Inicial', 'monto_inicial')
                ->sortable()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyRight),

            // Ingresos
            Column::make('Cant. Ingresos', 'cant_ingresos')
                ->sortable()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass),

            Column::make('Monto Ingresos', 'monto_ingresos')
                ->sortable()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyRight),

            // Reingresos Almacén
            Column::make('Cant. Reing. Almacén', 'cant_reing_alm')
                ->sortable()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass),

            Column::make('Monto Reing. Almacén', 'monto_reing_alm')
                ->sortable()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyRight),

            // Reingresos Farmacia
            Column::make('Cant. Reing. Farmacia', 'cant_reing_far')
                ->sortable()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass),

            Column::make('Monto Reing. Farmacia', 'monto_reing_far')
                ->sortable()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyRight),

            // Salidas
            Column::make('Cant. Salidas', 'cant_salidas')
                ->sortable()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass),

            Column::make('Monto Salidas', 'monto_salidas')
                ->sortable()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyRight),

            // Bajas
            Column::make('Cant. Bajas', 'cant_bajas')
                ->sortable()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass),

            Column::make('Monto Bajas', 'monto_bajas')
                ->sortable()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyRight),

            // Saldo Final
            Column::make('Cant. Saldo Final', 'cant_final')
                ->sortable()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass),

            Column::make('Monto Saldo Final', 'monto_final')
                ->sortable()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyRight),
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
    public function actions(Medicamento $row): array
    {
        return [
            Button::add('edit')
                ->slot('Edit: '.$row->id)
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
