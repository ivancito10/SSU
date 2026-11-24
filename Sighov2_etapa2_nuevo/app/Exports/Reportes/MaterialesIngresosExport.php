<?php

namespace App\Exports\Reportes;


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

class MaterialesIngresosExport implements FromQuery, WithHeadings, WithMapping, WithEvents
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
        $query = DB::table('almacen_general.ingreso_material as im')
            ->join('almacen_general.ingreso as i', 'i.id', '=', 'im.id_ingreso')
            ->join('almacen_general.material as m', 'm.id', '=', 'im.id_material')
            ->leftJoin('farmacia.institucion_externa as ie', 'ie.id', '=', 'i.id_institucion_externa')
            ->leftJoin('farmacia.tipo_proceso as tp', 'tp.id', '=', 'i.id_tipo_proceso')
            ->where('im.estado', true)
            ->when(
                $this->fechaInicio && $this->fechaFin,
                fn($q) => $q->whereBetween('i.fecha_ingreso', [$this->fechaInicio, $this->fechaFin])
            )
            ->select([
                'im.id',
                'm.codigo as codigo',
                'm.nombre_material as nombre_material',
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
            'CÓDIGO MATERIAL',
            'DESCRIPCIÓN / NOMBRE',
            'UNIDAD DE MEDIDA',
            'FECHA INGRESO',
            'MODALIDAD - N° INGRESO',
            'PROVEEDOR',
            'CANTIDAD',
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
            !empty($row->codigo) ? $row->codigo : '-',
            !empty($row->nombre_material) ? $row->nombre_material : '-',
            !empty($row->unidad_medida) ? $row->unidad_medida : '-',
            !empty($row->fecha_ingreso) ? date('d/m/Y', strtotime($row->fecha_ingreso)) : '-',
            !empty($row->modalidad_compra) ? $row->modalidad_compra : '-',
            !empty($row->proveedor) ? $row->proveedor : '-',
            isset($row->cantidad) ? (float)$row->cantidad : 0,
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
                $sheet->setCellValue('C2', 'KARDEX CONTABLE DE MATERIALES');
                $sheet->setCellValue('C3', 'INGRESO DE MATERIALES E INSUMOS - ALMACÉN GENERAL');

                $sheet->setCellValue('I2', 'FECHA DE IMPRESIÓN: ' . ($this->info_general['fecha_actual'] ?? now()->format('d/m/Y H:i')));
                $sheet->setCellValue('I3', 'USUARIO: ' . ($this->info_general['user_name'] ?? 'SISTEMA'));
                $sheet->setCellValue('I4', 'N° REGISTROS: ' . $this->totalRegistros);

                $sheet->setCellValue('A6', 'REPORTE DE INGRESO DE MATERIALES');
                $sheet->mergeCells('A6:K6');

                $fechaInicioStr = isset($this->info_general['fecha_inicio']) ? $this->info_general['fecha_inicio'] : date('d/m/Y', strtotime($this->fechaInicio));
                $fechaFinStr    = isset($this->info_general['fecha_fin']) ? $this->info_general['fecha_fin'] : date('d/m/Y', strtotime($this->fechaFin));

                $sheet->setCellValue('A7', 'DEL: ' . $fechaInicioStr . ' HASTA: ' . $fechaFinStr);
                $sheet->mergeCells('A7:K7');

                // Estilos del Membrete
                $sheet->getStyle('C2')->getFont()->getColor()->setRGB('2471A3');
                $sheet->getStyle('C3')->getFont()->getColor()->setRGB('A93226');
                $sheet->getStyle('C2:C3')->getFont()->setBold(true);

                $sheet->getStyle('I2:I4')->getFont()->setBold(true);

                $sheet->getStyle('A6:A7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A6:A7')->getFont()->setBold(true)->setSize(12);

                // Estilo de la Cabecera de la Tabla (Fila 8)
                $sheet->getStyle('A8:K8')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
                $sheet->getStyle('A8:K8')->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('2471A3');

                $totalFilas = $this->totalRegistros + 8;

                // Bordes para toda la tabla (A8 hasta K)
                $sheet->getStyle("A8:K{$totalFilas}")->applyFromArray([
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
                $sheet->getStyle("K9:K{$totalFilas}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("H9:J{$totalFilas}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                // Formato de número nativo de Excel (#,##0.00)
                $sheet->getStyle("H9:J{$totalFilas}")->getNumberFormat()->setFormatCode('#,##0.00');

                // Ancho fijo de columnas (A hasta K)
                $columnWidths = [
                    'A' => 6,   // N°
                    'B' => 18,  // CÓDIGO MATERIAL
                    'C' => 45,  // DESCRIPCIÓN / NOMBRE
                    'D' => 22,  // UNIDAD DE MEDIDA
                    'E' => 18,  // FECHA INGRESO
                    'F' => 35,  // MODALIDAD / N° INGRESO
                    'G' => 30,  // PROVEEDOR
                    'H' => 20,  // CANTIDAD
                    'I' => 18,  // PRECIO UNITARIO
                    'J' => 18,  // PRECIO TOTAL
                    'K' => 18,  // CUENTA CONTABLE
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
