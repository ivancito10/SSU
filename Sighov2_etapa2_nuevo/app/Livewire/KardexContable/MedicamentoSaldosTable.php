<?php

namespace App\Livewire\KardexContable;

use App\Models\Farmacia\Medicamento;
use \App\Models\Farmacia\MedicamentoStock;
use Illuminate\Support\Carbon;
//use Illuminate\Database\Eloquent\Builder;
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

final class MedicamentoSaldosTable extends PowerGridComponent
{
    use WithExport;
    public string $fechaInicio = '';
    public string $fechaFin = '';
    public bool $buscarPresionado = false;
    public array $idUnidad = [];

    public string $primaryKey = 'id';

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
        //dd($this->fechaInicio,$this->fechaFin,$this->idUnidad);
        $ultimoIdLote = DB::table('farmacia.medicamento_stock')
            ->select('id_medicamento', DB::raw('MAX(id) as max_id'))
            ->whereIn('id_unidad', $this->idUnidad)
            ->when($this->fechaInicio && $this->fechaFin, function ($query) {
                $query->whereBetween('created_at', [
                    $this->fechaInicio . ' 00:00:00',
                    $this->fechaFin . ' 23:59:59'
                ]);
            })
            ->groupBy('id_medicamento');

        return MedicamentoStock::query()
            ->join('farmacia.medicamento as m', 'm.id', '=', 'farmacia.medicamento_stock.id_medicamento')
            ->joinSub($ultimoIdLote, 'ul', 'ul.id_medicamento', '=', 'farmacia.medicamento_stock.id_medicamento')
            ->join('farmacia.medicamento_stock as ul_stock', 'ul_stock.id', '=', 'ul.max_id')
            ->whereIn('farmacia.medicamento_stock.id_unidad', $this->idUnidad)
            ->when($this->fechaInicio && $this->fechaFin, function ($query) {
                $query->whereBetween('farmacia.medicamento_stock.created_at', [
                    $this->fechaInicio . ' 00:00:00',
                    $this->fechaFin . ' 23:59:59'
                ]);
            })
            ->select([
                'farmacia.medicamento_stock.id_medicamento as id',
                'm.codigo_medicamento as codigo',
                'm.nombre as descripcion',
                'ul_stock.precio_unitario as precio_ultimo_lote',
                DB::raw('SUM(farmacia.medicamento_stock.stock_actualizado) as cantidad_total'),
                DB::raw('SUM(farmacia.medicamento_stock.stock_actualizado) * ul_stock.precio_unitario as saldo_final')
            ])
            ->groupBy(
                'farmacia.medicamento_stock.id_medicamento',
                'm.codigo_medicamento',
                'm.nombre',
                'ul_stock.precio_unitario'
            )
            ->havingRaw('SUM(farmacia.medicamento_stock.stock_actualizado) > 0');;
    }
    public function datasource(): Builder
    {
        // 1. Cierres de Gestión para el medicamento y las unidades indicadas
        $cierres = DB::table('farmacia.cierre_gestion')
            ->select('id', 'id_medicamento', 'id_unidad')
            ->whereIn('id_unidad', $this->idUnidad)
            ->when($this->fechaInicio && $this->fechaFin, function ($query) {
                $query->whereBetween('fecha_registro', [
                    $this->fechaInicio . ' 00:00:00',
                    $this->fechaFin . ' 23:59:59'
                ]);
            });

        // 2. Movimientos vinculados a los cierres (id_identificador_tabla = 4)
        $movimientos = DB::table('farmacia.movimiento as mov')
            ->joinSub($cierres, 'c', function ($join) {
                $join->on('mov.id_corresponde', '=', 'c.id')
                    ->on('mov.id_medicamento', '=', 'c.id_medicamento')
                    ->on('mov.id_unidad', '=', 'c.id_unidad');
            })
            ->where('mov.id_identificador_tabla', 4)
            ->whereIn('mov.id_unidad', $this->idUnidad)
            ->select('mov.id_medicamento', 'mov.nro_lote')
            ->distinct();

        // 3. Subconsulta de Stock: Filtra por cierre_gestion = true y los lotes recuperados
        $stockCierre = DB::table('farmacia.medicamento_stock as ms')
            ->joinSub($movimientos, 'm_lotes', function ($join) {
                $join->on('ms.id_medicamento', '=', 'm_lotes.id_medicamento')
                    ->on('ms.nro_lote', '=', 'm_lotes.nro_lote');
            })
            ->whereIn('ms.id_unidad', $this->idUnidad)
            ->where('ms.cierre_gestion', true)
            ->select([
                'ms.id_medicamento',
                DB::raw('MAX(ms.precio_unitario) as precio_unitario'),
                DB::raw('SUM(ms.stock_actualizado) as stock_total')
            ])
            ->groupBy('ms.id_medicamento');

        // 4. Consulta principal consolidada
        return MedicamentoStock::query()
            ->join('farmacia.medicamento as m', 'm.id', '=', 'farmacia.medicamento_stock.id_medicamento')
            ->joinSub($stockCierre, 'sc', 'sc.id_medicamento', '=', 'farmacia.medicamento_stock.id_medicamento')
            ->whereIn('farmacia.medicamento_stock.id_unidad', $this->idUnidad)
            ->where('farmacia.medicamento_stock.cierre_gestion', true)
            ->select([
                'farmacia.medicamento_stock.id_medicamento as id',
                'm.codigo_medicamento as codigo',
                'm.nombre as descripcion',
                'sc.precio_unitario as precio_ultimo_lote',
                'sc.stock_total as cantidad_total',
                DB::raw('sc.stock_total * sc.precio_unitario as saldo_final')
            ])
            ->groupBy(
                'farmacia.medicamento_stock.id_medicamento',
                'm.codigo_medicamento',
                'm.nombre',
                'sc.precio_unitario',
                'sc.stock_total'
            );
        //->havingRaw('sc.stock_total > 0');
    }
    public function datasource(): Builder
    {
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
            ->whereIn('cg.id_unidad', $this->idUnidad)
            ->whereBetween('cg.fecha_registro', [
                $this->fechaInicio . ' 00:00:00',
                $this->fechaFin . ' 23:59:59'
            ])
            ->select([
                'm.id as id',
                'm.codigo_medicamento',
                'm.nombre as descripcion',
                DB::raw('SUM(cg.cantidad_fisica) as cantidad'),
                DB::raw('COALESCE(MAX(ms.precio_unitario), 0) as precio_unitario'),
                DB::raw('SUM(cg.cantidad_fisica) * COALESCE(MAX(ms.precio_unitario), 0) as saldo_final'),
            ])
            ->groupBy('m.id', 'm.codigo_medicamento', 'm.nombre')
            ->havingRaw('SUM(cg.cantidad_fisica) > 0');
    }*/
    public function datasource(): Builder
    {
        // Subconsulta aislada: Consolidación y Prioridad de Precios (1° Almacén 89 -> 2° Farmacia 76 -> 3° Cero)
        $datosCierre = DB::table('farmacia.cierre_gestion as cg')
            ->join('farmacia.movimiento as mov', function ($join) {
                $join->on('mov.id_corresponde', '=', 'cg.id')
                    ->on('mov.id_medicamento', '=', 'cg.id_medicamento')
                    ->on('mov.id_unidad', '=', 'cg.id_unidad')
                    ->where('mov.id_identificador_tabla', 4) // 4 = Cierre de Gestión
                    ->where('mov.cierre_gestion', true);
            })
            ->leftJoin('farmacia.medicamento_stock as ms', function ($join) {
                $join->on('ms.id_medicamento', '=', 'mov.id_medicamento')
                    ->on('ms.id_unidad', '=', 'mov.id_unidad')
                    ->on('ms.nro_lote', '=', 'mov.nro_lote')
                    ->where('ms.cierre_gestion', true);
            })
            ->whereIn('cg.id_unidad', (array) $this->idUnidad)
            ->when($this->fechaInicio && $this->fechaFin, function ($q) {
                $q->whereBetween('cg.fecha_registro', [
                    $this->fechaInicio . ' 00:00:00',
                    $this->fechaFin . ' 23:59:59'
                ]);
            })
            ->select([
                'cg.id_medicamento',
                DB::raw('SUM(cg.cantidad_fisica) as total_cantidad_fisica'),

                // Prioridad estricta: Almacén (89) primero, luego Farmacia (76)
                DB::raw("
                COALESCE(
                    MAX(ms.precio_unitario) FILTER (WHERE mov.id_unidad = 89 AND ms.precio_unitario > 0),
                    MAX(ms.precio_unitario) FILTER (WHERE mov.id_unidad = 76 AND ms.precio_unitario > 0),
                    0
                ) as precio_unitario
            ")
            ])
            ->groupBy('cg.id_medicamento');

        // Consulta principal compatible con PowerGrid
        return DB::table('farmacia.medicamento as m')
            ->joinSub($datosCierre, 'dc', 'dc.id_medicamento', '=', 'm.id')
            ->select([
                'm.id as id', // Clave primaria requerida por PowerGrid
                'm.codigo_medicamento',
                'm.nombre as descripcion',
                'dc.total_cantidad_fisica as cantidad',
                'dc.precio_unitario as precio_unitario',
                DB::raw('ROUND((dc.total_cantidad_fisica * dc.precio_unitario)::numeric, 2) as saldo_final')
            ])
            ->where('dc.total_cantidad_fisica', '>', 0)
            ->orderBy('m.codigo_medicamento', 'asc');
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
            ->add('descripcion')
            ->add('cantidad', function ($row) {
                return number_format($row->cantidad, 2, ',', '.');
            })
            ->add('precio_unitario', function ($row) {
                return 'Bs. ' . number_format($row->precio_unitario, 2, ',', '.');
            })
            ->add('saldo_final', fn($row) => 'Bs. ' . number_format($row->saldo_final, 2, ',', '.'));
    }

    public function columns(): array
    {
        $headerStyle = 'font-size: 15px; background-color: #2471a3; color: white;';
        $headerClass = 'text-center';
        $bodyClass   = 'text-center';
        $bodyRight   = 'text-right font-medium'; // Para alinear los montos a la derecha

        return [
            Column::make('#', 'id')
                ->index()
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass),

            Column::make('CÓDIGO', 'codigo_medicamento', 'm.codigo_medicamento')
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass)
                ->searchable(),

            Column::make('DESCRIPCIÓN / MEDICAMENTO', 'descripcion', 'm.nombre')
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute('text-left') // La descripción se lee mejor alineada a la izquierda
                ->searchable(),

            Column::make('CANTIDAD', 'cantidad')
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyRight)
                ->searchable(),

            Column::make('PRECIO UNITARIO', 'precio_unitario')
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyRight)
                ->searchable(),

            Column::make('SALDO FINAL (Bs.)', 'saldo_final')
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyRight . ' font-bold text-emerald-600')
                ->searchable(),
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
    public function actions(MedicamentoStock $row): array
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
