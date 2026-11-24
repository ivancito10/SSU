<?php

namespace App\Exports\Reportes;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class MedicamentoSalidaExport implements FromQuery, WithHeadings, WithMapping, WithEvents
{
    protected $fechaInicio;
    protected $fechaFin;
    protected $info_general;
    protected $totalRegistros = 0;

    public function __construct($fechaInicio, $fechaFin, array $info_general = [])
    {
        $this->fechaInicio  = $fechaInicio;
        $this->fechaFin     = $fechaFin;
        $this->info_general = $info_general;
    }
    /*
    public function query()
    {
        // Subconsulta Nivel 1: Movimiento Lote Exacto (PEPS)
        $subMov = DB::table('farmacia.movimiento as m')
            ->select(DB::raw('DISTINCT ON (m.id_corresponde) m.id_corresponde, s.precio_unitario'))
            ->join('farmacia.medicamento_stock as s', function ($join) {
                $join->on('s.id_medicamento', '=', 'm.id_medicamento')
                    ->on('s.nro_lote', '=', 'm.nro_lote')
                    ->where('s.id_unidad', '=', 89)
                    ->where('s.precio_unitario', '>', 0);
            })
            ->where('m.id_identificador_tabla', 2)
            ->where('m.id_unidad', 89)
            ->where('m.cantidad_salida', '>', 0)
            ->orderBy('m.id_corresponde')
            ->orderByDesc('m.id');

        // Subconsulta Nivel 2: Fallback Último Precio en Stock
        $subStock = DB::table('farmacia.medicamento_stock')
            ->select(DB::raw('DISTINCT ON (id_medicamento) id_medicamento, precio_unitario'))
            ->where('id_unidad', 89)
            ->where('precio_unitario', '>', 0)
            ->orderBy('id_medicamento')
            ->orderByDesc('id');

        // Subconsulta Nivel 3: Fallback Precio Máximo de Todos los Lotes
        $subStockMax = DB::table('farmacia.medicamento_stock')
            ->select('id_medicamento', DB::raw('MAX(precio_unitario) as precio_maximo'))
            ->where('id_unidad', 89)
            ->where('precio_unitario', '>', 0)
            ->groupBy('id_medicamento');

        // Consulta Principal
        $query = DB::table('farmacia.solicitud as s')
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
                'ms.cantidad_entregada as cantidad',
                DB::raw("COALESCE(mov.precio_unitario, st.precio_unitario, st_max.precio_maximo, 0)::numeric(12,2) AS precio_unitario"),
                DB::raw("ROUND((ms.cantidad_entregada::numeric * COALESCE(mov.precio_unitario, st.precio_unitario, st_max.precio_maximo, 0)::numeric), 2) AS precio_total"),
                'm.cuenta_contable',
            ])
            ->join('farmacia.medicamento_solicitud as ms', 'ms.id_solicitud', '=', 's.id')
            ->join('farmacia.medicamento as m', 'm.id', '=', 'ms.id_medicamento')
            ->leftJoinSub($subMov, 'mov', 'mov.id_corresponde', '=', 'ms.id')
            ->leftJoinSub($subStock, 'st', 'st.id_medicamento', '=', 'ms.id_medicamento')
            ->leftJoinSub($subStockMax, 'st_max', 'st_max.id_medicamento', '=', 'ms.id_medicamento')
            ->where('s.estado_validacion', 'ENTREGADO')
            ->where('s.estado', true)
            ->where('ms.cantidad_entregada', '>', 0)
            ->where('s.id_unidad_recepciona', 89)
            ->whereBetween('s.fecha_entregado', [$this->fechaInicio, $this->fechaFin])
            ->orderBy('s.fecha_entregado', 'asc');

        // Guardamos el total de filas para dimensionar bordes y membrete en Excel
        $this->totalRegistros = (clone $query)->count();

        return $query;
    }*/
    public function query()
    {
        // 1. Prioridad 1: Precio por Lote Exacto en Movimiento
        $subMov = DB::table('farmacia.movimiento as m')
            ->select(DB::raw('DISTINCT ON (m.id_corresponde, m.id_medicamento) 
            m.id_corresponde, 
            m.id_medicamento, 
            s.precio_unitario'))
            ->join('farmacia.medicamento_stock as s', function ($join) {
                $join->on('s.id_medicamento', '=', 'm.id_medicamento')
                    ->on('s.nro_lote', '=', 'm.nro_lote')
                    ->where('s.id_unidad', '=', 89)
                    ->where('s.precio_unitario', '>', 0);
            })
            ->where('m.id_identificador_tabla', 2) // 2 = Solicitudes
            ->where('m.id_unidad', 89)
            ->where('m.cantidad_salida', '>', 0)
            ->orderBy('m.id_corresponde')
            ->orderBy('m.id_medicamento')
            ->orderByDesc('m.id');

        // 2. Prioridad 2: Precio del Último Lote registrado en Stock (Fallback)
        $subStock = DB::table('farmacia.medicamento_stock')
            ->select(DB::raw('DISTINCT ON (id_medicamento) id_medicamento, precio_unitario'))
            ->where('id_unidad', 89)
            ->where('precio_unitario', '>', 0)
            ->orderBy('id_medicamento')
            ->orderByDesc('id');

        // Consulta Principal
        $query = DB::table('farmacia.solicitud as s')
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
                'ms.cantidad_entregada as cantidad',

                // Prioridad: 1. Movimiento Lote Exacto -> 2. Último Lote en Stock -> 3. Cero (0.00)
                DB::raw("COALESCE(mov.precio_unitario, st.precio_unitario, 0)::numeric(12,2) AS precio_unitario"),
                DB::raw("ROUND((ms.cantidad_entregada::numeric * COALESCE(mov.precio_unitario, st.precio_unitario, 0)::numeric), 2) AS precio_total"),

                'm.cuenta_contable',
            ])
            ->join('farmacia.medicamento_solicitud as ms', 'ms.id_solicitud', '=', 's.id')
            ->join('farmacia.medicamento as m', 'm.id', '=', 'ms.id_medicamento')

            // Unión Nivel 1: Movimiento Lote Exacto (Solicitud + Medicamento)
            ->leftJoinSub($subMov, 'mov', function ($join) {
                $join->on('mov.id_corresponde', '=', 's.id')
                    ->on('mov.id_medicamento', '=', 'ms.id_medicamento');
            })
            // Unión Nivel 2: Último Lote en Stock (Medicamento)
            ->leftJoinSub($subStock, 'st', 'st.id_medicamento', '=', 'ms.id_medicamento')

            ->where('s.estado_validacion', 'ENTREGADO')
            ->where('s.estado', true)
            ->where('ms.cantidad_entregada', '>', 0)
            ->where('s.id_unidad_recepciona', 89)
            ->whereBetween('s.fecha_entregado', [
                $this->fechaInicio,
                $this->fechaFin
            ])
            ->orderBy('s.fecha_entregado', 'asc');

        $this->totalRegistros = (clone $query)->count();

        return $query;
    }

    public function headings(): array
    {
        return [
            'N°',
            'CÓDIGO MEDICAMENTO',
            'DESCRIPCIÓN / MEDICAMENTO',
            'FORMA FARMACÉUTICA',
            'FECHA SALIDA',
            'UNIDAD SOLICITANTE',
            'CANTIDAD ENTREGADA',
            'PRECIO UNITARIO',
            'PRECIO TOTAL',
            'CUENTA CONTABLE',
        ];
    }

    public function map($row): array
    {
        static $index = 0;
        $index++;

        return [
            $index,
            !empty($row->codigo_medicamento) ? $row->codigo_medicamento : '-',
            !empty($row->descripcion_medicamento) ? $row->descripcion_medicamento : '-',
            !empty($row->forma_farmaceutica) ? $row->forma_farmaceutica : '-',
            !empty($row->fecha_salida) ? date('d/m/Y H:i', strtotime($row->fecha_salida)) : '-',
            !empty($row->unidad_solicitante) ? $row->unidad_solicitante : '-',
            isset($row->cantidad) && $row->cantidad !== '' ? (float)$row->cantidad : 0,
            isset($row->precio_unitario) ? (float)$row->precio_unitario : 0,
            isset($row->precio_total) ? (float)$row->precio_total : 0,
            !empty($row->cuenta_contable) ? $row->cuenta_contable : '-',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                $sheet->insertNewRowBefore(1, 7);

                $sheet->setCellValue('C2', 'KARDEX CONTABLE DE MEDICAMENTOS');
                $sheet->setCellValue('C3', 'SALIDAS DE MEDICAMENTOS E INSUMOS - ALMACEN DE MEDICAMENTOS');

                $sheet->setCellValue('I2', 'FECHA DE IMPRESIÓN: ' . ($this->info_general['fecha_actual'] ?? now()->format('d/m/Y H:i')));
                $sheet->setCellValue('I3', 'USUARIO: ' . ($this->info_general['user_name'] ?? 'SISTEMA'));
                $sheet->setCellValue('I4', 'N° REGISTROS: ' . $this->totalRegistros);

                $sheet->setCellValue('A6', 'REPORTE DE SALIDAS DE MEDICAMENTOS');
                $sheet->mergeCells('A6:J6');

                $fechaInicioStr = $this->info_general['fecha_inicio'] ?? date('d/m/Y', strtotime($this->fechaInicio));
                $fechaFinStr    = $this->info_general['fecha_fin'] ?? date('d/m/Y', strtotime($this->fechaFin));

                $sheet->setCellValue('A7', 'DEL: ' . $fechaInicioStr . ' HASTA: ' . $fechaFinStr);
                $sheet->mergeCells('A7:J7');

                // Paleta Azul Institucional (#2471A3)
                $sheet->getStyle('C2')->getFont()->getColor()->setRGB('2471A3');
                $sheet->getStyle('C3')->getFont()->getColor()->setRGB('A93226');
                $sheet->getStyle('C2:C3')->getFont()->setBold(true);

                $sheet->getStyle('I2:I4')->getFont()->setBold(true);

                $sheet->getStyle('A6:A7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A6:A7')->getFont()->setBold(true)->setSize(12);

                // Cabecera de Tabla en Azul con Texto Blanco
                $sheet->getStyle('A8:J8')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
                $sheet->getStyle('A8:J8')->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('2471A3');

                $totalFilas = $this->totalRegistros + 8;

                // Bordes azules finos
                $sheet->getStyle("A8:J{$totalFilas}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '2471A3'],
                        ],
                    ],
                ]);

                // Alineaciones de celdas
                $sheet->getStyle("A9:B{$totalFilas}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("D9:E{$totalFilas}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("J9:J{$totalFilas}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("G9:I{$totalFilas}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                // Formatos numéricos (#,##0.00)
                $sheet->getStyle("G9:I{$totalFilas}")->getNumberFormat()->setFormatCode('#,##0.00');

                // Ancho fijo de las 10 columnas
                $columnWidths = [
                    'A' => 6,   // N°
                    'B' => 18,  // CÓDIGO MEDICAMENTO
                    'C' => 45,  // DESCRIPCIÓN
                    'D' => 22,  // FORMA FARMACÉUTICA
                    'E' => 18,  // FECHA SALIDA
                    'F' => 35,  // UNIDAD SOLICITANTE
                    'G' => 20,  // CANTIDAD
                    'H' => 18,  // PRECIO UNITARIO
                    'I' => 18,  // PRECIO TOTAL
                    'J' => 18,  // CUENTA CONTABLE
                ];

                foreach ($columnWidths as $col => $width) {
                    $sheet->getColumnDimension($col)->setAutoSize(false);
                    $sheet->getColumnDimension($col)->setWidth($width);
                }

                // Logo
                if (file_exists(public_path('logo/ssu.png'))) {
                    $drawing = new Drawing();
                    $drawing->setName('Logo SSU');
                    $drawing->setDescription('Logo');
                    $drawing->setPath(public_path('logo/ssu.png'));
                    $drawing->setHeight(70);
                    $drawing->setCoordinates('A1');
                    $drawing->setWorksheet($sheet);
                }
            },
        ];
    }
}
/*
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class MedicamentoSalidaExport implements FromQuery, WithHeadings, WithMapping, WithEvents
{
    protected $fechaInicio;
    protected $fechaFin;
    protected $info_general;
    protected $totalRegistros = 0;

    public function __construct($fechaInicio, $fechaFin, array $info_general = [])
    {
        $this->fechaInicio         = $fechaInicio;
        $this->fechaFin            = $fechaFin;
        $this->info_general        = $info_general;
    }

    public function query()
    {
        // 1. Subconsulta de stock sin duplicados (Lote más reciente)
        // Se fuerza el ORDER BY directamente en la sintaxis de PostgreSQL
        $stockSub = DB::table('farmacia.medicamento_stock')
            ->selectRaw('DISTINCT ON (id_medicamento, nro_lote) id_medicamento, nro_lote, precio_unitario')
            ->orderByRaw('id_medicamento, nro_lote, id DESC');

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
        $query = DB::table('farmacia.medicamento_solicitud as ms')
            ->join('farmacia.medicamento as m', 'm.id', '=', 'ms.id_medicamento')
            ->join('farmacia.solicitud as s', 's.id', '=', 'ms.id_solicitud')
            ->leftJoinSub($movimientosSub, 'mov', function ($join) {
                $join->on('mov.id_corresponde', '=', 's.id')
                    ->on('mov.id_medicamento', '=', 'ms.id_medicamento');
            })
            ->where('s.estado_validacion', 'ENTREGADO')
            ->where('ms.cantidad_entregada', '>', 0)
            ->where('s.id_unidad_recepciona', 89)
            ->when(
                $this->fechaInicio && $this->fechaFin,
                fn($q) =>
                $q->whereBetween('s.fecha_entregado', [$this->fechaInicio, $this->fechaFin])
            )
            ->select([
                'ms.id',
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
            ])
            ->orderByRaw('s.fecha_entregado ASC, ms.id ASC');

        // Conteo total de filas para el membrete
        $this->totalRegistros = DB::table(DB::raw("({$query->toSql()}) as sub"))
            ->mergeBindings($query)
            ->count();

        return $query;
    }

    public function headings(): array
    {
        return [
            'N°',
            'CÓDIGO MEDICAMENTO',
            'DESCRIPCIÓN / NOMBRE',
            'FORMA FARMACÉUTICA',
            'FECHA SALIDA',
            'UNIDAD SOLICITANTE',
            'CANTIDAD ENTREGADA',
            'PRECIO UNITARIO',
            'PRECIO TOTAL',
            'CUENTA CONTABLE',
        ];
    }

    public function map($row): array
    {
        static $index = 0;
        $index++;

        return [
            $index,
            !empty($row->codigo_medicamento) ? $row->codigo_medicamento : '-',
            !empty($row->descripcion_medicamento) ? $row->descripcion_medicamento : '-',
            !empty($row->forma_farmaceutica) ? $row->forma_farmaceutica : '-',
            !empty($row->fecha_salida) ? date('d/m/Y', strtotime($row->fecha_salida)) : '-',
            !empty($row->unidad_solicitante) ? $row->unidad_solicitante : '-',
            isset($row->cantidad_entregada) ? (float)$row->cantidad_entregada : 0,
            isset($row->precio_unitario) ? (float)$row->precio_unitario : 0,
            isset($row->precio_total) ? (float)$row->precio_total : 0,
            !empty($row->cuenta_contable) ? $row->cuenta_contable : '-',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Orientación Horizontal
                $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);

                // Reservar 7 filas superiores para el membrete
                $sheet->insertNewRowBefore(1, 7);

                // Escribir Encabezado / Membrete
                $sheet->setCellValue('C2', 'KARDEX CONTABLE DE MEDICAMENTOS');
                $sheet->setCellValue('C3', 'ENTREGA DE MEDICAMENTOS Y INSUMOS - FARMACIA');

                $sheet->setCellValue('I2', 'FECHA DE IMPRESIÓN: ' . ($this->info_general['fecha_actual'] ?? now()->format('d/m/Y H:i')));
                $sheet->setCellValue('I3', 'USUARIO: ' . ($this->info_general['user_name'] ?? 'SISTEMA'));
                $sheet->setCellValue('I4', 'N° REGISTROS: ' . $this->totalRegistros);

                $sheet->setCellValue('A6', 'REPORTE DE SALIDA DE MEDICAMENTOS / FARMACIA');
                $sheet->mergeCells('A6:J6');

                $fechaInicioStr = $this->info_general['fecha_inicio'] ?? date('d/m/Y', strtotime($this->fechaInicio));
                $fechaFinStr    = $this->info_general['fecha_fin'] ?? date('d/m/Y', strtotime($this->fechaFin));

                $sheet->setCellValue('A7', 'DEL: ' . $fechaInicioStr . ' HASTA: ' . $fechaFinStr);
                $sheet->mergeCells('A7:J7');

                // Estilos del Membrete
                $sheet->getStyle('C2')->getFont()->getColor()->setRGB('1A5276');
                $sheet->getStyle('C3')->getFont()->getColor()->setRGB('A93226');
                $sheet->getStyle('C2:C3')->getFont()->setBold(true);

                $sheet->getStyle('I2:I4')->getFont()->setBold(true);

                $sheet->getStyle('A6:A7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A6:A7')->getFont()->setBold(true)->setSize(12);

                // Estilo de la Cabecera de la Tabla (Fila 8)
                $sheet->getStyle('A8:J8')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
                $sheet->getStyle('A8:J8')->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('2471A3'); // Color azul acorde a tu reporte

                $totalFilas = $this->totalRegistros + 8;

                // Bordes para toda la tabla (A8 hasta J)
                $sheet->getStyle("A8:J{$totalFilas}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '2A3B45'],
                        ],
                    ],
                ]);

                // Alineaciones de celdas
                $sheet->getStyle("A9:B{$totalFilas}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("D9:E{$totalFilas}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("J9:J{$totalFilas}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("G9:I{$totalFilas}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                // Formato de número nativo de Excel (#,##0.00)
                $sheet->getStyle("G9:I{$totalFilas}")->getNumberFormat()->setFormatCode('#,##0.00');

                // Ancho fijo de columnas (A hasta J)
                $columnWidths = [
                    'A' => 6,   // N°
                    'B' => 18,  // CÓDIGO MEDICAMENTO
                    'C' => 45,  // DESCRIPCIÓN / NOMBRE
                    'D' => 22,  // FORMA FARMACÉUTICA
                    'E' => 18,  // FECHA SALIDA
                    'F' => 35,  // UNIDAD SOLICITANTE
                    'G' => 20,  // CANTIDAD ENTREGADA
                    'H' => 18,  // PRECIO UNITARIO
                    'I' => 18,  // PRECIO TOTAL
                    'J' => 18,  // CUENTA CONTABLE
                ];

                foreach ($columnWidths as $col => $width) {
                    $sheet->getColumnDimension($col)->setAutoSize(false);
                    $sheet->getColumnDimension($col)->setWidth($width);
                }

                // Insertar Logo
                if (file_exists(public_path('logo/ssu.png'))) {
                    $drawing = new Drawing();
                    $drawing->setName('Logo SSU');
                    $drawing->setDescription('Logo');
                    $drawing->setPath(public_path('logo/ssu.png'));
                    $drawing->setHeight(70);
                    $drawing->setCoordinates('A1');
                    $drawing->setWorksheet($sheet);
                }
            },
        ];
    }
}
*/