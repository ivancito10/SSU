<?php

namespace App\Livewire\KardexContable;

use App\Models\AlmacenGeneral\Material;
use App\Models\AlmacenGeneral\MaterialStock;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
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

final class MaterialesSaldosTable extends PowerGridComponent
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
    /*
    public function datasource(): Builder
    {
        // Subconsulta del último lote por material y unidad
        $ultimoIdLote = DB::table('almacen_general.material_stock')
            ->select('id_material', DB::raw('MAX(id) as max_id'))
            ->where('id_unidad', 19)
            ->when($this->fechaInicio && $this->fechaFin, function ($query) {
                $query->whereBetween('created_at', [
                    $this->fechaInicio . ' 00:00:00',
                    $this->fechaFin . ' 23:59:59'
                ]);
            })
            ->groupBy('id_material');

        return MaterialStock::query()
            ->join('almacen_general.material as m', 'm.id', '=', 'almacen_general.material_stock.id_material')
            ->joinSub($ultimoIdLote, 'ul', 'ul.id_material', '=', 'almacen_general.material_stock.id_material')
            ->join('almacen_general.material_stock as ul_stock', 'ul_stock.id', '=', 'ul.max_id')
            ->where('almacen_general.material_stock.id_unidad', 19)
            ->when($this->fechaInicio && $this->fechaFin, function ($query) {
                $query->whereBetween('almacen_general.material_stock.created_at', [
                    $this->fechaInicio . ' 00:00:00',
                    $this->fechaFin . ' 23:59:59'
                ]);
            })
            ->select([
                'almacen_general.material_stock.id_material as id',
                'm.codigo as codigo',
                'm.nombre_material as descripcion',
                'm.unidad as unidad_medida',
                'ul_stock.precio_unitario as precio_ultimo_lote',
                DB::raw('SUM(almacen_general.material_stock.stock_actualizado) as cantidad_total'),
                DB::raw('SUM(almacen_general.material_stock.stock_actualizado) * ul_stock.precio_unitario as saldo_final')
            ])
            ->groupBy(
                'almacen_general.material_stock.id_material',
                'm.codigo',
                'm.nombre_material',
                'm.unidad',
                'ul_stock.precio_unitario'
            );
        //->havingRaw('SUM(almacen_general.material_stock.stock_actualizado) > 0');
    }*/
    public function datasource(): Builder
    {
        $fInicio = Carbon::parse($this->fechaInicio)->startOfDay();
        $fFin    = Carbon::parse($this->fechaFin)->endOfDay();
        /*
        return DB::table('almacen_general.cierre_gestion as cg')
            ->join('almacen_general.material as m', 'm.id', '=', 'cg.id_material')
            ->join('almacen_general.movimiento as mov', function ($join) {
                $join->on('mov.id_corresponde', '=', 'cg.id')
                    ->where('mov.id_identificador_tabla', '=', 4)
                    ->on('mov.id_material', '=', 'cg.id_material')
                    ->on('mov.id_unidad', '=', 'cg.id_unidad');
            })
            ->leftJoin('almacen_general.material_stock as ms', function ($join) {
                $join->on('ms.id_material', '=', 'mov.id_material')
                    ->on('ms.id_unidad', '=', 'mov.id_unidad')
                    ->on('ms.nro_lote', '=', 'mov.nro_lote');
            })
            ->where('cg.id_unidad', 19) // o usa $this->idUnidad si recibes la propiedad
            ->whereBetween('cg.fecha_registro', [
                $this->fechaInicio . ' 00:00:00',
                $this->fechaFin . ' 23:59:59'
            ])
            ->select([
                'm.id as id',
                'm.codigo as codigo',
                'm.nombre_material as descripcion',
                'm.unidad as unidad_medida',
                DB::raw('SUM(cg.cantidad_fisica) as cantidad'),
                DB::raw('COALESCE(MAX(ms.precio_unitario), 0) as precio_unitario'),
                DB::raw('SUM(cg.cantidad_fisica) * COALESCE(MAX(ms.precio_unitario), 0) as saldo_final'),
            ])
            ->groupBy('m.id', 'm.codigo', 'm.nombre_material', 'm.unidad')
            ->havingRaw('SUM(cg.cantidad_fisica) > 0');*/
        // 1. Subconsulta de Cantidades por Cierre y Lote
        $subCantidades = DB::table('almacen_general.cierre_gestion as cg')
            ->leftJoin('almacen_general.movimiento as mov', function ($join) {
                $join->on('mov.id_corresponde', '=', 'cg.id')
                    ->where('mov.id_identificador_tabla', '=', 4)
                    ->where('mov.cierre_gestion', '=', true);
            })
            ->where('cg.id_unidad', 19)
            ->whereBetween('cg.fecha_registro', [$fInicio, $fFin])
            ->select([
                'cg.id_material',
                'mov.nro_lote',
                DB::raw('COALESCE(SUM(cg.cantidad_fisica), 0) as cantidad_total'),
                DB::raw('MAX(mov.id) as max_mov_id')
            ])
            ->groupBy('cg.id_material', 'mov.nro_lote')
            ->havingRaw('SUM(cg.cantidad_fisica) > 0');

        // 2. Subconsulta para obtener el Lote Principal del Cierre
        $subLotePrincipal = DB::query()
            ->fromSub($subCantidades, 'cm')
            ->select([
                DB::raw('DISTINCT ON (cm.id_material) cm.id_material'),
                'cm.nro_lote',
                'cm.cantidad_total'
            ])
            ->orderBy('cm.id_material')
            ->orderByRaw('cm.max_mov_id DESC NULLS LAST');

        // 3. Subconsulta para Precios del Lote
        $subPreciosLote = DB::table('almacen_general.material_stock as ms')
            ->where('ms.id_unidad', 19)
            ->where('ms.precio_unitario', '>', 0)
            ->select([
                DB::raw('DISTINCT ON (ms.id_material) ms.id_material'),
                'ms.precio_unitario',
                'ms.nro_lote'
            ])
            ->orderBy('ms.id_material')
            ->orderBy('ms.id', 'desc');

        // 4. Subconsulta Fallback (Último precio registrado en stock)
        $subUltimoPrecio = DB::table('almacen_general.material_stock as ms')
            ->where('ms.id_unidad', 19)
            ->where('ms.precio_unitario', '>', 0)
            ->select([
                DB::raw('DISTINCT ON (ms.id_material) ms.id_material'),
                'ms.precio_unitario'
            ])
            ->orderBy('ms.id_material')
            ->orderBy('ms.id', 'desc');

        // Consulta nativa sobre el modelo Material (PowerGrid puede leer $currentTable como 'almacen_general.material')
        return Material::query()
            ->joinSub($subLotePrincipal, 'lcp', 'lcp.id_material', '=', 'almacen_general.material.id')
            ->leftJoinSub($subPreciosLote, 'pl', function ($join) {
                $join->on('pl.id_material', '=', 'lcp.id_material')
                    ->on('pl.nro_lote', '=', 'lcp.nro_lote');
            })
            ->leftJoinSub($subUltimoPrecio, 'ups', 'ups.id_material', '=', 'lcp.id_material')
            ->select([
                'almacen_general.material.id as id',
                DB::raw("COALESCE(almacen_general.material.codigo, 'S/C') as codigo"),
                DB::raw("COALESCE(almacen_general.material.nombre_material, 'MATERIAL ID: ' || almacen_general.material.id) as descripcion"),
                DB::raw("COALESCE(almacen_general.material.unidad, 'PZA') as unidad_medida"),
                'lcp.cantidad_total as cantidad',
                DB::raw('COALESCE(pl.precio_unitario, ups.precio_unitario, 0) as precio_unitario'),
                DB::raw('(lcp.cantidad_total * COALESCE(pl.precio_unitario, ups.precio_unitario, 0)) as saldo_final')
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
            ->add('codigo')
            ->add('descripcion')
            ->add('unidad_medida', fn($row) => $row->unidad_medida ?? 'PZA')
            ->add('cantidad', fn($row) => number_format($row->cantidad, 2, '.', ','))
            ->add('precio_unitario', fn($row) => 'Bs. ' . number_format($row->precio_unitario, 2, '.', ','))
            ->add('saldo_final', fn($row) => 'Bs. ' . number_format($row->saldo_final, 2, '.', ','));
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

            Column::make('CÓD. MATERIAL', 'codigo')
                ->sortable()
                ->searchable()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass),

            Column::make('DESCRIPCIÓN DEL MATERIAL', 'descripcion')
                ->sortable()
                ->searchable()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute('text-left'),

            Column::make('UNIDAD', 'unidad_medida')
                ->sortable()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass),

            Column::make('CANTIDAD TOTAL', 'cantidad')
                ->sortable()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute('text-right font-bold'),

            Column::make('PRECIO UNITARIO', 'precio_unitario')
                ->sortable()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute('text-right'),

            Column::make('SALDO FINAL (BS.)', 'saldo_final')
                ->sortable()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute('text-right font-bold text-green-700'),
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
    public function actions(MaterialStock $row): array
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
