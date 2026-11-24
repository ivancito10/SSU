<?php

namespace App\Exports\Reportes;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class IngresoKardexContableExport implements FromCollection, WithHeadings, WithEvents
{
    protected $datos;
    protected $info_general;

    public function __construct(array $datos, array $info_general)
    {
        $this->datos = $datos;
        $this->info_general = $info_general;
    }

    public function collection()
    {
        return collect($this->datos);
    }

    public function headings(): array
    {
        // Encabezado dinámico para la columna D segun el almacén
        $columnaExtra = ($this->info_general['tipo_almacen'] ?? '') === 'ALMACÉN GENERAL' 
            ? 'UNIDAD DE MEDIDA' 
            : 'FORMA FARMACÉUTICA';

        return [
            'N°',
            'CÓDIGO',
            'DESCRIPCIÓN',
            $columnaExtra,
            'FECHA INGRESO',
            'TIPO DE PROCESO',
            'PROVEEDOR',
            'CANTIDAD',
            'PRECIO UNITARIO',
            'PRECIO TOTAL',
            'CUENTA CONTABLE'
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

                // Textos dinámicos del membrete
                $subtitulo = 'INGRESO DE ' . ($this->info_general['tipo_almacen'] ?? 'MATERIALES Y MEDICAMENTOS');
                $tituloDetalle = 'DETALLE DE INGRESOS DE ' . ($this->info_general['tipo_almacen'] ?? 'MATERIALES / MEDICAMENTOS');

                // Escribir Encabezado / Membrete
                $sheet->setCellValue('C2', 'KARDEX CONTABLE');
                $sheet->setCellValue('C3', $subtitulo);

                $sheet->setCellValue('I2', 'FECHA DE IMPRESIÓN: ' . $this->info_general['fecha_actual']);
                $sheet->setCellValue('I3', 'USUARIO: ' . $this->info_general['user_name']);
                $sheet->setCellValue('I4', 'N° REGISTROS: ' . $this->info_general['total_registros']);

                $sheet->setCellValue('A6', $tituloDetalle);
                $sheet->mergeCells('A6:K6');

                $sheet->setCellValue('A7', 'DEL: ' . $this->info_general['fecha_inicio'] . ' HASTA: ' . $this->info_general['fecha_fin']);
                $sheet->mergeCells('A7:K7');

                // Estilos del Membrete
                $sheet->getStyle('C2')->getFont()->getColor()->setRGB('1A5276');
                $sheet->getStyle('C3')->getFont()->getColor()->setRGB('A93226');
                $sheet->getStyle('C2:C3')->getFont()->setBold(true);

                $sheet->getStyle('I2:I4')->getFont()->setBold(true);

                $sheet->getStyle('A6:A7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A6:A7')->getFont()->setBold(true)->setSize(12);

                // Estilo de la Cabecera de la Tabla (Fila 8)
                $sheet->getStyle('A8:K8')->getFont()->setBold(true)->getColor()->setRGB('2A3B45');
                $sheet->getStyle('A8:K8')->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('EBF1F5');

                $totalFilas = count($this->datos) + 8;

                // Bordes para toda la tabla
                $sheet->getStyle("A8:K{$totalFilas}")->applyFromArray([
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
                $sheet->getStyle("K9:K{$totalFilas}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("H9:J{$totalFilas}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                // Formato de moneda/número nativo de Excel para Cantidad y Precios
                $sheet->getStyle("H9:H{$totalFilas}")->getNumberFormat()->setFormatCode('#,##0.00');
                $sheet->getStyle("I9:J{$totalFilas}")->getNumberFormat()->setFormatCode('#,##0.00');

                // Ancho fijo de columnas
                $columnWidths = [
                    'A' => 6,   // N°
                    'B' => 14,  // CÓDIGO
                    'C' => 40,  // DESCRIPCIÓN
                    'D' => 25,  // UNIDAD DE MEDIDA / FORMA FARMACÉUTICA
                    'E' => 16,  // FECHA INGRESO
                    'F' => 35,  // TIPO DE PROCESO
                    'G' => 35,  // PROVEEDOR
                    'H' => 14,  // CANTIDAD
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