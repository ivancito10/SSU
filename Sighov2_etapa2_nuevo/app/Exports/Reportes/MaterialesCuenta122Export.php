<?php

namespace App\Exports\Reportes;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class MaterialesCuenta122Export implements FromArray, WithHeadings, WithMapping, WithEvents, WithStrictNullComparison
{
    protected array $datos;
    protected array $info_general;
    protected int $totalRegistros = 0;

    public function __construct(array $datos, array $info_general = [])
    {
        $this->datos          = $datos;
        $this->info_general   = $info_general;
        $this->totalRegistros = count($datos);
    }

    public function array(): array
    {
        return $this->datos;
    }

    public function headings(): array
    {
        return [
            // Fila 8: Títulos Principales
            [
                'N°',
                'NRO. CUENTA',
                'CÓDIGO',
                'MATERIAL / DESCRIPCIÓN',
                'UNIDAD MEDIDA',
                'SALDO INICIAL',
                '',       // F8 y G8
                'INGRESOS',
                '',            // H8 e I8
                'REINGRESOS',
                '',          // J8 y K8
                'REINGRESOS OTROS',
                '',    // L8 y M8
                'SALIDAS',
                '',             // N8 u O8
                'BAJAS',
                '',               // P8 y Q8
                'SALDO FINAL',
                ''          // R8 y S8
            ],
            // Fila 9: Subtítulos (Cantidad y Monto)
            [
                '',
                '',
                '',
                '',
                '',        // A9 a E9 combinadas desde la fila 8
                'CANTIDAD',
                'MONTO (Bs.)', // F9 y G9
                'CANTIDAD',
                'MONTO (Bs.)', // H9 e I9
                'CANTIDAD',
                'MONTO (Bs.)', // J9 y K9
                'CANTIDAD',
                'MONTO (Bs.)', // L9 y M9
                'CANTIDAD',
                'MONTO (Bs.)', // N9 y O9
                'CANTIDAD',
                'MONTO (Bs.)', // P9 y Q9
                'CANTIDAD',
                'MONTO (Bs.)'  // R9 y S9
            ]
        ];
    }

    public function map($row): array
    {
        static $index = 0;
        $index++;

        // Helper para mapear seguros objetos o arreglos
        $get = fn($key, $default = 0) => is_array($row)
            ? ($row[$key] ?? $default)
            : ($row->{$key} ?? $default);

        return [
            $index,
            $get('nro_cuenta', $get('cuenta_contable', '-')),
            $get('codigo', $get('codigo_material', '-')),
            $get('nombre_material', $get('descripcion', '-')),
            $get('unidad_material', '-'),
            (float) $get('saldo_inicial_cantidad'),
            (float) $get('saldo_inicial_monto'),
            (float) $get('ingresos_cantidad'),
            (float) $get('ingresos_monto'),
            (float) $get('reingresos_cantidad'),
            (float) $get('reingresos_monto'),
            (float) $get('reingresos_otros_cantidad', 0),
            (float) $get('reingresos_otros_monto', 0),
            (float) $get('salidas_cantidad'),
            (float) $get('salidas_monto'),
            (float) $get('bajas_cantidad'),
            (float) $get('bajas_monto'),
            (float) $get('saldo_final_cantidad'),
            (float) $get('saldo_final_monto'),
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Orientación de página Horizontal
                $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);

                // Insertar 7 filas iniciales para el membrete
                $sheet->insertNewRowBefore(1, 7);

                // MEMBRETE E INFORMACIÓN INSTITUCIONAL
                $sheet->setCellValue('C2', 'ALMACÉN GENERAL');
                $sheet->setCellValue('C3', 'REPORTE RESUMEN DE SALDOS Y MOVIMIENTOS DE MATERIALES Y SUMINISTROS');

                $sheet->setCellValue('Q2', 'FECHA IMPRESIÓN: ' . ($this->info_general['fecha_actual'] ?? now()->format('d/m/Y H:i')));
                $sheet->setCellValue('Q3', 'USUARIO: ' . ($this->info_general['user_name'] ?? 'SISTEMA'));
                $sheet->setCellValue('Q4', 'N° REGISTROS: ' . $this->totalRegistros);

                $sheet->setCellValue('A6', 'RESUMEN GENERAL DE SALDOS Y MOVIMIENTOS - MATERIALES');
                $sheet->mergeCells('A6:S6');

                // Estilos del membrete
                $sheet->getStyle('C2')->getFont()->getColor()->setRGB('1A5276');
                $sheet->getStyle('C3')->getFont()->getColor()->setRGB('A93226');
                $sheet->getStyle('C2:C3')->getFont()->setBold(true);
                $sheet->getStyle('Q2:Q4')->getFont()->setBold(true);

                $sheet->getStyle('A6:A7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A6:A7')->getFont()->setBold(true)->setSize(12);

                // COMBINACIÓN DE CELDAS DEL ENCABEZADO (Filas 8 y 9)
                // Combinaciones Verticales
                $sheet->mergeCells('A8:A9'); // N°
                $sheet->mergeCells('B8:B9'); // NRO. CUENTA
                $sheet->mergeCells('C8:C9'); // CÓDIGO
                $sheet->mergeCells('D8:D9'); // MATERIAL / DESCRIPCIÓN
                $sheet->mergeCells('E8:E9'); // UNIDAD MEDIDA

                // Combinaciones Horizontales
                $sheet->mergeCells('F8:G8'); // SALDO INICIAL
                $sheet->mergeCells('H8:I8'); // INGRESOS
                $sheet->mergeCells('J8:K8'); // REINGRESOS
                $sheet->mergeCells('L8:M8'); // REINGRESOS OTROS
                $sheet->mergeCells('N8:O8'); // SALIDAS
                $sheet->mergeCells('P8:Q8'); // BAJAS
                $sheet->mergeCells('R8:S8'); // SALDO FINAL

                // Estilos y Bordes Blancos del Encabezado
                $sheet->getStyle('A8:S9')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
                $sheet->getStyle('A8:S9')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('1F618D');
                $sheet->getStyle('A8:S9')->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                // Bordes Blancos para el Encabezado
                $sheet->getStyle('A8:S9')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'FFFFFF'],
                        ],
                    ],
                ]);

                // FILA DE TOTALES GENERALES
                $totalFilas = $this->totalRegistros + 9;

                if ($this->totalRegistros > 0) {
                    $filaTotal = $totalFilas + 1;
                    $sheet->setCellValue("E{$filaTotal}", "TOTAL GENERAL:");

                    $columnasSuma = ['F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S'];
                    foreach ($columnasSuma as $col) {
                        $sheet->setCellValue("{$col}{$filaTotal}", "=SUM({$col}10:{$col}{$totalFilas})");
                    }

                    $sheet->getStyle("A{$filaTotal}:S{$filaTotal}")->getFont()->setBold(true);
                    $sheet->getStyle("E{$filaTotal}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    $sheet->getStyle("A{$filaTotal}:S{$filaTotal}")->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setRGB('EBF1F5');

                    $totalFilas = $filaTotal;
                }

                // BORDES Y ALINEACIÓN DEL CUERPO DE DATOS
                $sheet->getStyle("A10:S{$totalFilas}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '1F618D'],
                        ],
                    ],
                ]);

                $sheet->getStyle("A10:C{$totalFilas}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("D10:E{$totalFilas}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("F10:S{$totalFilas}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("F10:S{$totalFilas}")->getNumberFormat()->setFormatCode('#,##0.00');

                // ANCHOS DE COLUMNA
                $columnWidths = [
                    'A' => 6,
                    'B' => 16,
                    'C' => 14,
                    'D' => 45,
                    'E' => 18,
                    'F' => 15,
                    'G' => 15,
                    'H' => 15,
                    'I' => 15,
                    'J' => 15,
                    'K' => 15,
                    'L' => 15,
                    'M' => 15,
                    'N' => 15,
                    'O' => 15,
                    'P' => 15,
                    'Q' => 15,
                    'R' => 15,
                    'S' => 15
                ];

                foreach ($columnWidths as $col => $width) {
                    $sheet->getColumnDimension($col)->setAutoSize(false);
                    $sheet->getColumnDimension($col)->setWidth($width);
                }

                // LOGOTIPO
                if (file_exists(public_path('logo/ssu.png'))) {
                    $drawing = new Drawing();
                    $drawing->setName('Logo SSU');
                    $drawing->setPath(public_path('logo/ssu.png'));
                    $drawing->setHeight(70);
                    $drawing->setCoordinates('A1');
                    $drawing->setWorksheet($sheet);
                }
            },
        ];
    }
}
