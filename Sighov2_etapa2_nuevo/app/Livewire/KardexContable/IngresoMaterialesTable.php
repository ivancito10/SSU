<?php

namespace App\Livewire\KardexContable;

use App\Models\AlmacenGeneral\IngresoMaterial;
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

final class IngresoMaterialesTable extends PowerGridComponent
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

    public function updatedFechaInicio(): void
    {
        $this->setPage(1);
    }

    public function updatedFechaFin(): void
    {
        $this->setPage(1);
    }

    public function datasource(): Builder
    {/*
        return IngresoMaterial::query()
            ->where('estado', true)
            ->with([
                'materialIngresoMaterial',
                'ingresoIngresoMaterial.institucionExternaIngreso',
                'ingresoIngresoMaterial.procesoIngreso',
            ])
            // Filtro por rango de fechas si se proporcionan
            ->when($this->fechaInicio, function ($query) {
                $query->whereHas('ingresoIngresoMaterial', function ($q) {
                    $q->whereDate('fecha_ingreso', '>=', $this->fechaInicio);
                });
            })
            ->when($this->fechaFin, function ($query) {
                $query->whereHas('ingresoIngresoMaterial', function ($q) {
                    $q->whereDate('fecha_ingreso', '<=', $this->fechaFin);
                });
            });*/
        // Si no se ha presionado el botón Buscar o faltan fechas, retorna una consulta vacía
        if (!$this->buscarPresionado || empty($this->fechaInicio) || empty($this->fechaFin)) {
            return DB::table('almacen_general.ingreso_material')->whereRaw('1 = 0');
        }

        $fechaInicioParsed = Carbon::parse($this->fechaInicio)->startOfDay();
        $fechaFinParsed    = Carbon::parse($this->fechaFin)->endOfDay();

        return DB::table('almacen_general.ingreso_material as im')
            ->join('almacen_general.ingreso as i', 'i.id', '=', 'im.id_ingreso')
            ->join('almacen_general.material as m', 'm.id', '=', 'im.id_material')
            ->leftJoin('farmacia.institucion_externa as ie', 'ie.id', '=', 'i.id_institucion_externa')
            ->leftJoin('farmacia.tipo_proceso as tp', 'tp.id', '=', 'i.id_tipo_proceso')
            ->where('im.estado', true)
            ->whereBetween('i.fecha_ingreso', [$fechaInicioParsed, $fechaFinParsed])
            ->select([
                'im.id as id',
                'm.codigo as codigo_material',
                'm.nombre_material',
                'm.unidad as unidad_medida',
                'i.fecha_ingreso',
                DB::raw("
                CASE 
                    WHEN i.codigo_ingreso IS NOT NULL AND i.codigo_ingreso != '' 
                    THEN CONCAT(COALESCE(tp.nombre, ''), ' - N° ', i.codigo_ingreso)
                    ELSE COALESCE(tp.nombre, '-')
                END AS modalidad_compra
            "),
                'ie.razon_social as proveedor',
                'im.cantidad',
                'im.precio_unitario',
                'im.precio_total',
                'm.nro_cuenta as cuenta_contable',
            ])
            ->orderByRaw('m.nro_cuenta ASC, i.fecha_ingreso ASC');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('codigo_material', fn($row) => $row->codigo_material ?? '-')
            ->add('nombre_material', fn($row) => $row->nombre_material ?? '-')
            ->add('unidad_medida', fn($row) => $row->unidad_medida ?? '-')
            ->add('fecha_ingreso_formatted', fn($row) => !empty($row->fecha_ingreso) ? Carbon::parse($row->fecha_ingreso)->format('d/m/Y') : '-')
            ->add('modalidad_compra', fn($row) => $row->modalidad_compra ?? '-')
            ->add('proveedor', fn($row) => $row->proveedor ?? '-')
            ->add('cantidad', fn($row) => number_format((float)($row->cantidad ?? 0), 2, '.', ',')) // Cantidad con 2 Decimales
            ->add('precio_unitario', fn($row) => 'Bs ' . number_format((float)($row->precio_unitario ?? 0), 2, '.', ','))
            ->add('precio_total', fn($row) => 'Bs ' . number_format((float)($row->precio_total ?? 0), 2, '.', ','))
            ->add('cuenta_contable', fn($row) => $row->cuenta_contable ?? '-');
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

            Column::make('DESCRIPCIÓN / NOMBRE', 'nombre_material')
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass)
                ->searchable()
                ->sortable(),

            Column::make('UNIDAD MEDIDA', 'unidad_medida')
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass),

            Column::make('FECHA INGRESO', 'fecha_ingreso_formatted')
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass),

            Column::make('MODALIDAD COMPRA', 'modalidad_compra')
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass),

            Column::make('PROVEEDOR', 'proveedor')
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass)
                ->searchable(),

            Column::make('CANTIDAD', 'cantidad')
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass)
                ->sortable(),

            Column::make('PRECIO UNITARIO', 'precio_unitario')
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass),

            Column::make('PRECIO TOTAL', 'precio_total')
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass),

            Column::make('CUENTA CONTABLE', 'cuenta_contable')
                ->headerAttribute($headerClass, $headerStyle)
                ->bodyAttribute($bodyClass)
                ->searchable(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::datepicker('fecha_vencimiento'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }

    /*
    public function actions(IngresoMaterial $row): array
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
