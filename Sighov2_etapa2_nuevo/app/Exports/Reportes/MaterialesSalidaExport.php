<?php

namespace App\Exports\Reportes;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection; // <-- Cambiado a FromCollection
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

class MaterialesSalidaExport implements FromQuery, WithHeadings, WithMapping, WithEvents
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
    public function collection()
    {
        // Consulta nativa en SQL puro para evitar que Laravel o Excel alteren el DISTINCT ON
        $sql = "
            WITH stock_sub AS (
                SELECT DISTINCT ON (id_material, nro_lote) 
                    id_material, nro_lote, precio_unitario
                FROM almacen_general.material_stock
                ORDER BY id_material, nro_lote, id DESC
            ),
            movimientos_sub AS (
                SELECT 
                    mov.id_corresponde,
                    mov.id_material,
                    MAX(st.precio_unitario) AS precio_unitario
                FROM almacen_general.movimiento mov
                JOIN stock_sub st 
                    ON st.id_material = mov.id_material 
                   AND st.nro_lote = mov.nro_lote
                WHERE mov.id_identificador_tabla = 2
                  AND mov.cantidad_salida > 0
                GROUP BY mov.id_corresponde, mov.id_material
            )
            SELECT 
                ms.id,
                m.codigo AS codigo_material,
                m.nombre_material AS descripcion_material,
                m.unidad AS unidad_medida,
                s.fecha_entregado AS fecha_salida,
                COALESCE(s.nombre_unidad_solicita, s.nombre_unidad_servicio) AS unidad_servicio,
                ms.cantidad_entregada AS cantidad,
                COALESCE(mov.precio_unitario, 0) AS precio_unitario,
                (ms.cantidad_entregada * COALESCE(mov.precio_unitario, 0)) AS precio_total,
                m.nro_cuenta AS cuenta_contable
            FROM almacen_general.material_solicitud ms
            JOIN almacen_general.material m ON m.id = ms.id_material
            JOIN almacen_general.solicitud s ON s.id = ms.id_solicitud
            LEFT JOIN movimientos_sub mov 
                ON mov.id_corresponde = s.id 
               AND mov.id_material = ms.id_material
            WHERE s.estado_validacion = 'ENTREGADO'
              AND ms.cantidad_entregada > 0
              AND s.fecha_entregado BETWEEN ? AND ?
            ORDER BY ms.id ASC
        ";

        $bindings = [
            $this->fechaInicio,
            $this->fechaFin
        ];

        $resultados = DB::select($sql, $bindings);

        // Guardamos el conteo total para la cabecera y bordes del Excel
        $this->totalRegistros = count($resultados);

        return collect($resultados);
    }*/
    public function query()
    {
        // Nivel 1: DISTINCT ON (id_corresponde, id_material) previene la duplicación de filas
        $subMov = DB::table('almacen_general.movimiento as m')
            ->select(DB::raw('DISTINCT ON (m.id_corresponde, m.id_material) m.id_corresponde as id_solicitud, m.id_material, st.precio_unitario'))
            ->join('almacen_general.material_stock as st', function ($join) {
                $join->on('st.id_material', '=', 'm.id_material')
                    ->on('st.nro_lote', '=', 'm.nro_lote')
                    ->where('st.precio_unitario', '>', 0);
            })
            ->where('m.id_identificador_tabla', 2)
            ->where('m.cantidad_salida', '>', 0)
            ->orderBy('m.id_corresponde')
            ->orderBy('m.id_material')
            ->orderByDesc('m.id');

        // Nivel 2: Último precio por material en Stock (Fallback)
        $subStockUltimo = DB::table('almacen_general.material_stock')
            ->select(DB::raw('DISTINCT ON (id_material) id_material, precio_unitario as precio_ultimo_lote'))
            ->where('precio_unitario', '>', 0)
            ->orderBy('id_material')
            ->orderByDesc('id');

        // Nivel 3: Rescate por Precio Máximo Histórico
        $subStockMax = DB::table('almacen_general.material_stock')
            ->select('id_material', DB::raw('MAX(precio_unitario) as precio_maximo'))
            ->where('precio_unitario', '>', 0)
            ->groupBy('id_material');

        // Consulta Principal (Garantiza exactamente los 2159 registros de material_solicitud)
        $query = DB::table('almacen_general.solicitud as s')
            ->select([
                'ms.id as id',
                'm.codigo as codigo_material',
                'm.nombre_material as descripcion_material',
                'm.unidad as unidad_medida',
                's.fecha_entregado as fecha_salida',
                DB::raw("
                CASE 
                    WHEN s.nombre_unidad_servicio IS NOT NULL AND TRIM(s.nombre_unidad_servicio) <> '' 
                         THEN s.nombre_unidad_servicio
                    ELSE s.nombre_unidad_solicita
                END AS unidad_solicitante
            "),
                'ms.cantidad_entregada as cantidad',

                // Lógica de valoración por jerarquía
                DB::raw("
                COALESCE(
                    mov.precio_unitario, 
                    st_ult.precio_ultimo_lote, 
                    st_max.precio_maximo, 
                    0
                )::numeric(12,4) AS precio_unitario
            "),

                DB::raw("
                ROUND((
                    ms.cantidad_entregada::numeric * COALESCE(
                        mov.precio_unitario, 
                        st_ult.precio_ultimo_lote, 
                        st_max.precio_maximo, 
                        0
                    )::numeric
                ), 2) AS precio_total
            "),

                'm.nro_cuenta as cuenta_contable',
            ])
            ->join('almacen_general.material_solicitud as ms', 'ms.id_solicitud', '=', 's.id')
            ->join('almacen_general.material as m', 'm.id', '=', 'ms.id_material')
            ->leftJoinSub($subMov, 'mov', function ($join) {
                $join->on('mov.id_solicitud', '=', 's.id')
                    ->on('mov.id_material', '=', 'ms.id_material');
            })
            ->leftJoinSub($subStockUltimo, 'st_ult', 'st_ult.id_material', '=', 'ms.id_material')
            ->leftJoinSub($subStockMax, 'st_max', 'st_max.id_material', '=', 'ms.id_material')
            ->where('s.estado_validacion', 'ENTREGADO')
            ->where('s.estado', true)
            ->where('ms.cantidad_entregada', '>', 0)
            ->whereBetween('s.fecha_entregado', [$this->fechaInicio, $this->fechaFin])
            ->orderBy('s.fecha_entregado', 'asc')
            ->orderBy('s.id', 'asc')
            ->orderBy('ms.id', 'asc');

        $this->totalRegistros = (clone $query)->count();

        return $query;
    }

    public function headings(): array
    {
        return [
            'N°',
            'CÓDIGO MATERIAL',
            'NOMBRE MATERIAL',
            'UNIDAD MEDIDA',
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
            !empty($row->codigo_material) ? $row->codigo_material : '-',
            !empty($row->descripcion_material) ? $row->descripcion_material : '-',
            !empty($row->unidad_medida) ? $row->unidad_medida : '-',
            !empty($row->fecha_salida) ? date('d/m/Y H:i', strtotime($row->fecha_salida)) : '-',
            !empty($row->unidad_solicitante) ? $row->unidad_solicitante : '-',
            isset($row->cantidad) && $row->cantidad !== '' ? (float)$row->cantidad : 0,
            isset($row->precio_unitario) ? (float)$row->precio_unitario : 0,
            isset($row->precio_total) ? (float)$row->precio_total : 0,
            !empty($row->cuenta_contable) ? $row->cuenta_contable : '-',
        ];
    }
    /*
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);

                $sheet->insertNewRowBefore(1, 7);

                $sheet->setCellValue('C2', 'KARDEX CONTABLE');
                $sheet->setCellValue('C3', 'SALIDA DE MATERIALES - ALMACÉN GENERAL');

                $sheet->setCellValue('J2', 'FECHA DE IMPRESIÓN: ' . ($this->info_general['fecha_actual'] ?? now()->format('d/m/Y H:i')));
                $sheet->setCellValue('J3', 'USUARIO: ' . ($this->info_general['user_name'] ?? 'SISTEMA'));
                $sheet->setCellValue('J4', 'N° REGISTROS: ' . $this->totalRegistros);

                $sheet->setCellValue('A6', 'DETALLE DE SALIDAS DE MATERIALES DE ALMACÉN GENERAL');
                $sheet->mergeCells('A6:J6');

                $fechaInicioStr = $this->info_general['fecha_inicio'] ?? date('d/m/Y', strtotime($this->fechaInicio));
                $fechaFinStr    = $this->info_general['fecha_fin'] ?? date('d/m/Y', strtotime($this->fechaFin));

                $sheet->setCellValue('A7', 'DEL: ' . $fechaInicioStr . ' HASTA: ' . $fechaFinStr);
                $sheet->mergeCells('A7:J7');

                $sheet->getStyle('C2')->getFont()->getColor()->setRGB('1A5276');
                $sheet->getStyle('C3')->getFont()->getColor()->setRGB('A93226');
                $sheet->getStyle('C2:C3')->getFont()->setBold(true);

                $sheet->getStyle('J2:J4')->getFont()->setBold(true);

                $sheet->getStyle('A6:A7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A6:A7')->getFont()->setBold(true)->setSize(12);

                $sheet->getStyle('A8:J8')->getFont()->setBold(true)->getColor()->setRGB('2A3B45');
                $sheet->getStyle('A8:J8')->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('EBF1F5');

                $totalFilas = $this->totalRegistros + 8;

                $sheet->getStyle("A8:J{$totalFilas}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '2A3B45'],
                        ],
                    ],
                ]);

                $sheet->getStyle("A9:B{$totalFilas}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("E9:E{$totalFilas}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("J9:J{$totalFilas}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("G9:I{$totalFilas}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                $sheet->getStyle("G9:G{$totalFilas}")->getNumberFormat()->setFormatCode('#,##0.00');
                $sheet->getStyle("H9:I{$totalFilas}")->getNumberFormat()->setFormatCode('#,##0.00');

                $columnWidths = [
                    'A' => 6,
                    'B' => 18,
                    'C' => 45,
                    'D' => 20,
                    'E' => 18,
                    'F' => 35,
                    'G' => 20,
                    'H' => 18,
                    'I' => 18,
                    'J' => 18,
                ];

                foreach ($columnWidths as $col => $width) {
                    $sheet->getColumnDimension($col)->setAutoSize(false);
                    $sheet->getColumnDimension($col)->setWidth($width);
                }

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
    }*/
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
                $sheet->setCellValue('C2', 'KARDEX CONTABLE DE MATERIALES');
                $sheet->setCellValue('C3', 'SALIDAS DE MATERIALES E INSUMOS - ALMACÉN GENERAL');

                $sheet->setCellValue('H2', 'FECHA DE IMPRESIÓN: ' . ($this->info_general['fecha_actual'] ?? now()->format('d/m/Y H:i')));
                $sheet->setCellValue('H3', 'USUARIO: ' . ($this->info_general['user_name'] ?? 'SISTEMA'));
                $sheet->setCellValue('H4', 'N° REGISTROS: ' . $this->totalRegistros);

                $sheet->setCellValue('A6', 'REPORTE DE SALIDA DE MATERIALES DE ALMACEN GENERAL');
                $sheet->mergeCells('A6:J6');

                $fechaInicioStr = isset($this->info_general['fecha_inicio']) ? $this->info_general['fecha_inicio'] : date('d/m/Y', strtotime($this->fechaInicio));
                $fechaFinStr    = isset($this->info_general['fecha_fin']) ? $this->info_general['fecha_fin'] : date('d/m/Y', strtotime($this->fechaFin));

                $sheet->setCellValue('A7', 'DEL: ' . $fechaInicioStr . ' HASTA: ' . $fechaFinStr);
                $sheet->mergeCells('A7:J7');

                // Estilos del Membrete
                $sheet->getStyle('C2')->getFont()->getColor()->setRGB('2471A3');
                $sheet->getStyle('C3')->getFont()->getColor()->setRGB('A93226');
                $sheet->getStyle('C2:C3')->getFont()->setBold(true);

                $sheet->getStyle('H2:H4')->getFont()->setBold(true);

                $sheet->getStyle('A6:A7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A6:A7')->getFont()->setBold(true)->setSize(12);

                // Estilo de la Cabecera de la Tabla (Fila 8)
                $sheet->getStyle('A8:J8')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
                $sheet->getStyle('A8:J8')->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('2471A3');

                $totalFilas = $this->totalRegistros + 8;

                // Bordes para toda la tabla (A8 hasta J)
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

                // Formato de número nativo de Excel (#,##0.00)
                $sheet->getStyle("G9:I{$totalFilas}")->getNumberFormat()->setFormatCode('#,##0.00');

                // Ancho fijo de columnas (A hasta J)
                $columnWidths = [
                    'A' => 6,   // N°
                    'B' => 18,  // CÓDIGO MATERIAL
                    'C' => 45,  // DESCRIPCIÓN / NOMBRE
                    'D' => 22,  // UNIDAD DE MEDIDA
                    'E' => 18,  // FECHA SALIDA
                    'F' => 35,  // UNIDAD SERVICIO / SOLICITANTE
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
/*
use App\Models\AlmacenGeneral\MaterialSolicitud;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class MaterialesSalidaExport implements FromQuery, WithHeadings, WithMapping, WithEvents
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

    public function query()
    {
        $query = MaterialSolicitud::query()
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
            ]);

        // Obtenemos el total de registros para aplicar bordes e info del membrete
        $this->totalRegistros = (clone $query)->count();

        return $query;
    }

    public function headings(): array
    {
        return [
            'N°',
            'CÓDIGO MATERIAL',
            'DESCRIPCIÓN',
            'UNIDAD MEDIDA',
            'FECHA SALIDA',
            'UNIDAD SERVICIO',
            'CANTIDAD ENTREGADA',
            'CUENTA CONTABLE',
        ];
    }

    public function map($row): array
    {
        static $index = 0;
        $index++;

        return [
            $index,
            !empty($row->codigo_material) ? $row->codigo_material : '-',
            !empty($row->descripcion_material) ? $row->descripcion_material : '-',
            !empty($row->unidad_medida) ? $row->unidad_medida : '-',
            !empty($row->fecha_salida) ? date('d/m/Y H:i', strtotime($row->fecha_salida)) : '-',
            !empty($row->unidad_servicio) ? $row->unidad_servicio : '-',
            isset($row->cantidad) && $row->cantidad !== '' ? (float)$row->cantidad : '-',
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
                $sheet->setCellValue('C2', 'KARDEX CONTABLE');
                $sheet->setCellValue('C3', 'SALIDA DE MATERIALES - ALMACÉN GENERAL');

                $sheet->setCellValue('H2', 'FECHA DE IMPRESIÓN: ' . ($this->info_general['fecha_actual'] ?? now()->format('d/m/Y H:i')));
                $sheet->setCellValue('H3', 'USUARIO: ' . ($this->info_general['user_name'] ?? 'SISTEMA'));
                $sheet->setCellValue('H4', 'N° REGISTROS: ' . $this->totalRegistros);

                $sheet->setCellValue('A6', 'DETALLE DE SALIDAS DE MATERIALES / ALMACÉN GENERAL');
                $sheet->mergeCells('A6:H6');

                $fechaInicioStr = $this->info_general['fecha_inicio'] ?? date('d/m/Y', strtotime($this->fechaInicio));
                $fechaFinStr    = $this->info_general['fecha_fin'] ?? date('d/m/Y', strtotime($this->fechaFin));

                $sheet->setCellValue('A7', 'DEL: ' . $fechaInicioStr . ' HASTA: ' . $fechaFinStr);
                $sheet->mergeCells('A7:H7');

                // Estilos del Membrete
                $sheet->getStyle('C2')->getFont()->getColor()->setRGB('1A5276');
                $sheet->getStyle('C3')->getFont()->getColor()->setRGB('A93226');
                $sheet->getStyle('C2:C3')->getFont()->setBold(true);

                $sheet->getStyle('H2:H4')->getFont()->setBold(true);

                $sheet->getStyle('A6:A7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A6:A7')->getFont()->setBold(true)->setSize(12);

                // Estilo de la Cabecera de la Tabla (Fila 8)
                $sheet->getStyle('A8:H8')->getFont()->setBold(true)->getColor()->setRGB('2A3B45');
                $sheet->getStyle('A8:H8')->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('EBF1F5');

                $totalFilas = $this->totalRegistros + 8;

                // Bordes para toda la tabla
                $sheet->getStyle("A8:H{$totalFilas}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '2A3B45'],
                        ],
                    ],
                ]);

                // Alineaciones
                $sheet->getStyle("A9:B{$totalFilas}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("E9:E{$totalFilas}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("H9:H{$totalFilas}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("G9:G{$totalFilas}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                // Formato de número nativo de Excel para Cantidad
                $sheet->getStyle("G9:G{$totalFilas}")->getNumberFormat()->setFormatCode('#,##0.00');

                // Ancho fijo de columnas
                $columnWidths = [
                    'A' => 6,   // N°
                    'B' => 18,  // CÓDIGO MATERIAL
                    'C' => 45,  // DESCRIPCIÓN
                    'D' => 25,  // UNIDAD MEDIDA
                    'E' => 18,  // FECHA SALIDA
                    'F' => 35,  // UNIDAD SERVICIO
                    'G' => 20,  // CANTIDAD ENTREGADA
                    'H' => 18,  // CUENTA CONTABLE
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
}*/
