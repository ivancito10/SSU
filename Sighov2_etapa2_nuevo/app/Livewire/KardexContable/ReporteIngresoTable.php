<?php

namespace App\Livewire\KardexContable;

use App\Models\Farmacia\DetalleIngreso;
use Illuminate\Support\Carbon;
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
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use Livewire\Attributes\On;

final class ReporteIngresoTable extends PowerGridComponent
{
    use WithExport;
    public string $primaryKey = 'id';
    //public string $tableName = 'IngresosFarmaciaTable';
    public string $tableName = 'farmacia.detalle_ingreso';
    public string $sortDirection = 'asc';

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

    public function updatedFechaInicio(): void
    {
        $this->setPage(1);
    }

    public function updatedFechaFin(): void
    {
        $this->setPage(1);
    }

    public function datasource(): Builder
    {
        // Si no se ha presionado el botón Buscar o faltan fechas, retorna una consulta vacía
        if (!$this->buscarPresionado || empty($this->fechaInicio) || empty($this->fechaFin)) {
            return DB::table('farmacia.detalle_ingreso')->whereRaw('1 = 0');
        }

        $fechaInicioParsed = Carbon::parse($this->fechaInicio)->startOfDay();
        $fechaFinParsed    = Carbon::parse($this->fechaFin)->endOfDay();

        return DB::table('farmacia.detalle_ingreso as di')
            ->join('farmacia.ingreso as i', 'i.id', '=', 'di.id_ingreso')
            ->join('farmacia.medicamento as m', 'm.id', '=', 'di.id_medicamento')
            ->leftJoin('farmacia.institucion_externa as ie', 'ie.id', '=', 'i.id_institucion_externa')
            ->leftJoin('farmacia.tipo_proceso as tp', 'tp.id', '=', 'i.id_tipo_proceso')
            ->where('i.estado', true)
            ->where('i.id_institucion_externa', '!=', 1)
            ->whereBetween('i.fecha_recepcion', [$fechaInicioParsed, $fechaFinParsed])
            ->select([
                'di.id as id',
                'm.codigo_medicamento',
                'm.nombre as descripcion',
                'm.forma_farmaceutica',
                'i.fecha_ingreso',
                DB::raw("
                CASE 
                    WHEN i.cod_ingreso IS NOT NULL AND i.cod_ingreso != '' 
                    THEN CONCAT(COALESCE(tp.nombre, ''), ' - N° ', i.cod_ingreso)
                    ELSE COALESCE(tp.nombre, '-')
                END AS modalidad_compra
            "),
                'ie.razon_social as proveedor',
                'di.cantidad',
                'di.precio_unitario',
                'di.precio_total',
                'm.cuenta_contable',
            ])
            ->orderByRaw('m.cuenta_contable ASC, i.fecha_ingreso ASC');
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
            ->add('forma_farmaceutica', fn($row) => $row->forma_farmaceutica ?? 'N/A')
            ->add('fecha_ingreso_formatted', fn($row) => date('d/m/Y', strtotime($row->fecha_ingreso)))
            ->add('modalidad_compra', fn($row) => $row->modalidad_compra ?? 'N/A')
            ->add('numero_ingreso')
            ->add('proveedor', fn($row) => $row->proveedor ?? 'SIN PROVEEDOR')
            ->add('cantidad', fn($row) => number_format($row->cantidad, 0))
            ->add('precio_unitario', fn($row) => number_format($row->precio_unitario, 2))
            ->add('precio_total', fn($row) => number_format($row->precio_total, 2))
            ->add('cuenta_contable', fn($row) => $row->cuenta_contable ?? 'S/C');
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

            Column::make('CÓD. MEDICAMENTO', 'codigo_medicamento')
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass)
                ->searchable()
                ->sortable(),

            Column::make('DESCRIPCIÓN', 'descripcion')
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute('text-left')
                ->searchable()
                ->sortable(),

            Column::make('FORMA FARMACÉUTICA', 'forma_farmaceutica')
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass)
                ->searchable(),

            Column::make('FECHA INGRESO', 'fecha_ingreso_formatted', 'fecha_ingreso')
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass)
                ->searchable(),

            Column::make('MODALIDAD COMPRA', 'modalidad_compra')
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass)
                ->searchable(),

            Column::make('PROVEEDOR', 'proveedor')
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute('text-left')
                ->searchable(),

            Column::make('CANTIDAD', 'cantidad')
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute('text-right')
                ->searchable(),

            Column::make('P. UNITARIO', 'precio_unitario')
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute('text-right')
                ->searchable(),

            Column::make('P. TOTAL', 'precio_total')
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute('text-right')
                ->searchable(),

            Column::make('CUENTA CONTABLE', 'cuenta_contable')
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass)
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
    public function actions(DetalleIngreso $row): array
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
