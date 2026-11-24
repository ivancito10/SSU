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

class MedicamentoCuenta121Export implements FromArray, WithHeadings, WithMapping, WithEvents, WithStrictNullComparison
{
    protected array $datos;
    protected array $info_general;
    protected int $totalRegistros = 0;

    public function __construct(array $datos, array $info_general = [])
    {
        $this->datos          = $datos;
        $this->info_general    = $info_general;
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
                'CUENTA CONTABLE',
                'CÓDIGO',
                'MEDICAMENTO / DESCRIPCIÓN',
                'FORMA FARMACÉUTICA',
                'CONCENTRACIÓN',
                'SALDO INICIAL',
                '',       // G8 y H8
                'INGRESOS',
                '',            // I8 y J8
                'REINGRESOS',
                '',          // K8 y L8
                'REINGRESOS FARMACIA',
                '', // M8 y N8
                'SALIDAS',
                '',             // O8 y P8
                'BAJAS',
                '',               // Q8 y R8
                'SALDO FINAL',
                ''          // S8 y T8
            ],
            // Fila 9: Subtítulos (Cantidad y Monto)
            [
                '',
                '',
                '',
                '',
                '',
                '',    // A9 a F9 quedan vacías porque se combinan desde la fila 8
                'CANTIDAD',
                'MONTO (Bs.)', // G9 y H9
                'CANTIDAD',
                'MONTO (Bs.)', // I9 y J9
                'CANTIDAD',
                'MONTO (Bs.)', // K9 y L9
                'CANTIDAD',
                'MONTO (Bs.)', // M9 y N9
                'CANTIDAD',
                'MONTO (Bs.)', // O9 y P9
                'CANTIDAD',
                'MONTO (Bs.)', // Q9 y R9
                'CANTIDAD',
                'MONTO (Bs.)'  // S9 y T9
            ]
        ];
    }

    public function map($row): array
    {
        static $index = 0;
        $index++;

        // Manejo mediante sintaxis de Array u Objeto de forma segura
        $get = fn($key, $default = 0) => is_array($row)
            ? ($row[$key] ?? $default)
            : ($row->{$key} ?? $default);

        return [
            $index,
            $get('cuenta_contable', '-'),
            $get('codigo_medicamento', '-'),
            $get('nombre_medicamento', '-'),
            $get('forma_farmaceutica_medicamento', '-'),
            $get('concentracion_medicamento', '-'),
            (float) $get('saldo_inicial_cantidad'),
            (float) $get('saldo_inicial_monto'),
            (float) $get('ingresos_cantidad'),
            (float) $get('ingresos_monto'),
            (float) $get('reingresos_cantidad'),
            (float) $get('reingresos_monto'),
            (float) $get('reingresos_farmacia_cantidad'),
            (float) $get('reingresos_farmacia_monto'),
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

                // Configuración de página a orientación horizontal (Landscape)
                $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);

                // Insertar 7 filas al inicio para el membrete
                $sheet->insertNewRowBefore(1, 7);

                // MEMBRETE E INFORMACIÓN INSTITUCIONAL
                $sheet->setCellValue('C2', 'KARDEX CONTABLE');
                $sheet->setCellValue('C3', 'REPORTE RESUMEN DE SALDOS Y MOVIMIENTOS DE MEDICAMENTOS');

                $sheet->setCellValue('R2', 'FECHA IMPRESIÓN: ' . ($this->info_general['fecha_actual'] ?? now()->format('d/m/Y H:i')));
                $sheet->setCellValue('R3', 'USUARIO: ' . ($this->info_general['user_name'] ?? 'SISTEMA'));
                $sheet->setCellValue('R4', 'N° REGISTROS: ' . $this->totalRegistros);

                $sheet->setCellValue('A6', 'RESUMEN GENERAL DE SALDOS Y MOVIMIENTOS');
                $sheet->mergeCells('A6:T6');

                // Estilos del membrete
                $sheet->getStyle('C2')->getFont()->getColor()->setRGB('1A5276');
                $sheet->getStyle('C3')->getFont()->getColor()->setRGB('A93226');
                $sheet->getStyle('C2:C3')->getFont()->setBold(true);
                $sheet->getStyle('R2:R4')->getFont()->setBold(true);

                $sheet->getStyle('A6:A7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A6:A7')->getFont()->setBold(true)->setSize(12);

                // COMBINACIÓN DE CELDAS DEL ENCABEZADO (Filas 8 y 9)
                // Combinaciones Verticales
                $sheet->mergeCells('A8:A9'); // N°
                $sheet->mergeCells('B8:B9'); // CUENTA CONTABLE
                $sheet->mergeCells('C8:C9'); // CÓDIGO
                $sheet->mergeCells('D8:D9'); // MEDICAMENTO / DESCRIPCIÓN
                $sheet->mergeCells('E8:E9'); // FORMA FARMACÉUTICA
                $sheet->mergeCells('F8:F9'); // CONCENTRACIÓN

                // Combinaciones Horizontales
                $sheet->mergeCells('G8:H8'); // SALDO INICIAL
                $sheet->mergeCells('I8:J8'); // INGRESOS
                $sheet->mergeCells('K8:L8'); // REINGRESOS
                $sheet->mergeCells('M8:N8'); // REINGRESOS FARMACIA
                $sheet->mergeCells('O8:P8'); // SALIDAS
                $sheet->mergeCells('Q8:R8'); // BAJAS
                $sheet->mergeCells('S8:T8'); // SALDO FINAL

                // Estilos y Bordes Blancos del Encabezado
                $sheet->getStyle('A8:T9')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
                $sheet->getStyle('A8:T9')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('2471A3');
                $sheet->getStyle('A8:T9')->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                // Bordes Blancos para el Encabezado
                $sheet->getStyle('A8:T9')->applyFromArray([
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
                    $sheet->setCellValue("F{$filaTotal}", "TOTAL GENERAL:");

                    $columnasSuma = ['G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T'];
                    foreach ($columnasSuma as $col) {
                        $sheet->setCellValue("{$col}{$filaTotal}", "=SUM({$col}10:{$col}{$totalFilas})");
                    }

                    $sheet->getStyle("A{$filaTotal}:T{$filaTotal}")->getFont()->setBold(true);
                    $sheet->getStyle("F{$filaTotal}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    $sheet->getStyle("A{$filaTotal}:T{$filaTotal}")->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setRGB('EBF1F5');

                    $totalFilas = $filaTotal;
                }

                // BORDES Y ALINEACIÓN DEL CUERPO DE DATOS
                // Bordes azules aplicados solo desde la fila de datos (10) hasta el final
                $sheet->getStyle("A10:T{$totalFilas}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '2471A3'],
                        ],
                    ],
                ]);

                $sheet->getStyle("A10:F{$totalFilas}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("G10:T{$totalFilas}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("G10:T{$totalFilas}")->getNumberFormat()->setFormatCode('#,##0.00');

                // ANCHOS DE COLUMNA
                $columnWidths = [
                    'A' => 6,
                    'B' => 16,
                    'C' => 14,
                    'D' => 40,
                    'E' => 20,
                    'F' => 18,
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
                    'S' => 15,
                    'T' => 15
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
