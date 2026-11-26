<?php

namespace App\Exports\Enfermeria;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class InyeccionesExport implements FromCollection, WithEvents
{
    protected $filas, $fechaTexto, $encargada;

    public function __construct($filas, string $fechaTexto, string $encargada)
    {
        $this->filas      = $filas;
        $this->fechaTexto = $fechaTexto;
        $this->encargada  = $encargada;
    }

    public function collection()
    {
        return collect($this->filas);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $sheet->getPageSetup()
                    ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
                    ->setPaperSize(PageSetup::PAPERSIZE_LETTER)
                    ->setFitToWidth(1)
                    ->setFitToHeight(0);

                // Desplazar los datos hacia abajo para dejar espacio a las 8 filas de cabecera (A1:AF8)
                $sheet->insertNewRowBefore(1, 8);

                // =========================================================
                // 1. ENCABEZADO SUPERIOR (FILAS 1 - 4)
                // =========================================================
                $sheet->mergeCells('A1:B3');
                $sheet->mergeCells('C1:D2');
                $sheet->mergeCells('C3:D3');
                $sheet->mergeCells('E1:AF3');

                $sheet->setCellValue('C1', 'SEGURO SOCIAL UNIVERSITARIO LA PAZ');
                $sheet->setCellValue('C3', 'LA PAZ - BOLIVIA');
                $sheet->setCellValue('E1', "ACTIVIDADES DEL SERVICIO DE ENFERMERIA\nCONSULTA EXTERNA");

                $sheet->getStyle('C1:D2')->getFont()->setBold(true)->setSize(10);
                $sheet->getStyle('C3')->getFont()->setBold(true)->setSize(9);
                $sheet->getStyle('E1')->getFont()->setBold(true)->setSize(12);
                $sheet->getStyle('C1:AF3')->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER)
                    ->setWrapText(true);

                if (file_exists(public_path('logo/ssu.png'))) {
                    $drawing = new Drawing();
                    $drawing->setName('Logo SSU');
                    $drawing->setDescription('Logo');
                    $drawing->setPath(public_path('logo/ssu.png'));
                    $drawing->setHeight(60);
                    $drawing->setCoordinates('A1');
                    $drawing->setOffsetX(10);
                    $drawing->setOffsetY(4);
                    $drawing->setWorksheet($sheet);
                }

                // Fila 4: FECHA y ENCARGADA
                $sheet->mergeCells('A4:B4');
                $sheet->mergeCells('C4:D4');
                $sheet->mergeCells('E4:F4');
                $sheet->mergeCells('G4:AF4');

                $sheet->setCellValue('A4', 'FECHA:');
                $sheet->setCellValue('C4', $this->fechaTexto);
                $sheet->setCellValue('E4', 'ENCARGADA (O):');
                $sheet->setCellValue('G4', mb_strtoupper($this->encargada));
                $sheet->getStyle('A4:AF4')->getFont()->setBold(true)->setSize(9);
                $sheet->getStyle('A4:F4')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getStyle('G4')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                // =========================================================
                // 2. CABECERA DE TABLA (FILAS 5 - 8) IGUAL A VACUNAS_EXCEL
                // =========================================================
                $sheet->mergeCells('A5:A8')->setCellValue('A5', 'N');
                $sheet->mergeCells('B5:B8')->setCellValue('B5', 'HORA');
                $sheet->mergeCells('C5:C8')->setCellValue('C5', 'APELLIDO PATERNO');
                $sheet->mergeCells('D5:D8')->setCellValue('D5', 'APELLIDO MATERNO');
                $sheet->mergeCells('E5:E8')->setCellValue('E5', 'NOMBRE');
                $sheet->mergeCells('F5:F8')->setCellValue('F5', 'MATRICULA');

                $sheet->mergeCells('G5:H7')->setCellValue('G5', 'SEXO');
                $sheet->setCellValue('G8', 'M');
                $sheet->setCellValue('H8', 'F');

                $sheet->mergeCells('I5:J7')->setCellValue('I5', 'ACTIVOS');
                $sheet->setCellValue('I8', 'A');
                $sheet->setCellValue('J8', 'B');

                $sheet->mergeCells('K5:L7')->setCellValue('K5', 'RENTISTA');
                $sheet->setCellValue('K8', 'A');
                $sheet->setCellValue('L8', 'B');

                $sheet->mergeCells('M5:P7')->setCellValue('M5', "VIA\nPARENTERAL");
                $sheet->setCellValue('M8', 'IM');
                $sheet->setCellValue('N8', 'IV');
                $sheet->setCellValue('O8', 'SC');
                $sheet->setCellValue('P8', 'VENOCLISIS');

                $sheet->mergeCells('Q5:R7')->setCellValue('Q5', 'CURACION');
                $sheet->setCellValue('Q8', 'P');
                $sheet->setCellValue('R8', 'I');

                $sheet->mergeCells('S5:S8')->setCellValue('S5', 'OXIGENO TERAPIA');
                $sheet->mergeCells('T5:T8')->setCellValue('T5', 'RETIRO DE PUNTOS');
                $sheet->mergeCells('U5:U8')->setCellValue('U5', 'PRUEBA DE SENSIBILIDAD');
                $sheet->mergeCells('V5:V8')->setCellValue('V5', 'SANGRIA');
                $sheet->mergeCells('W5:W8')->setCellValue('W5', 'SIGNOS VITALES');
                $sheet->mergeCells('X5:X8')->setCellValue('X5', 'TX. V.O.');
                $sheet->mergeCells('Y5:Y8')->setCellValue('Y5', 'ORIENTACION EN SALUD');
                $sheet->mergeCells('Z5:Z8')->setCellValue('Z5', 'VENDAJES');
                $sheet->mergeCells('AA5:AA8')->setCellValue('AA5', 'NEBULIZACION');
                $sheet->mergeCells('AB5:AB8')->setCellValue('AB5', 'OTROS');

                $sheet->mergeCells('AC5:AC8')->setCellValue('AC5', 'TRATAMIENTO');
                $sheet->mergeCells('AD5:AD8')->setCellValue('AD5', 'MEDICO');
                $sheet->mergeCells('AE5:AE8')->setCellValue('AE5', 'SSUE');
                $sheet->mergeCells('AF5:AF8')->setCellValue('AF5', 'INTERIOR');

                // Estilos cabecera tabla
                $sheet->getStyle('A5:AF8')->getFont()->setBold(true)->setSize(8);
                $sheet->getStyle('A5:AF8')->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER)
                    ->setWrapText(true);
                $sheet->getStyle('A5:AF8')->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('E8F1F8');

                // Rotaciones exactas de columnas
                $sheet->getStyle('I5')->getAlignment()->setTextRotation(45);
                $sheet->getStyle('K5')->getAlignment()->setTextRotation(45);
                $sheet->getStyle('Q5')->getAlignment()->setTextRotation(45);
                $sheet->getStyle('P8')->getAlignment()->setTextRotation(90);
                foreach (['S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB'] as $colRot) {
                    $sheet->getStyle("{$colRot}5")->getAlignment()->setTextRotation(90);
                }

                // Alturas de filas de cabecera
                $sheet->getRowDimension(1)->setRowHeight(22);
                $sheet->getRowDimension(2)->setRowHeight(22);
                $sheet->getRowDimension(3)->setRowHeight(18);
                $sheet->getRowDimension(4)->setRowHeight(18);
                $sheet->getRowDimension(5)->setRowHeight(22);
                $sheet->getRowDimension(6)->setRowHeight(22);
                $sheet->getRowDimension(7)->setRowHeight(22);
                $sheet->getRowDimension(8)->setRowHeight(42);

                // =========================================================
                // 3. ANCHOS DE COLUMNAS Y BORDES DE DATOS
                // =========================================================
                $anchos = [
                    'A' => 4.5, 'B' => 9.5, 'C' => 17, 'D' => 17, 'E' => 18, 'F' => 15,
                    'G' => 3.5, 'H' => 3.5, 'I' => 3.5, 'J' => 3.5, 'K' => 3.5, 'L' => 3.5,
                    'M' => 4,   'N' => 3.5, 'O' => 3.5, 'P' => 4,   'Q' => 3.5, 'R' => 3.5,
                    'S' => 3.5, 'T' => 3.5, 'U' => 3.5, 'V' => 3.5, 'W' => 3.5, 'X' => 3.5,
                    'Y' => 3.5, 'Z' => 3.5, 'AA' => 3.5, 'AB' => 3.5,
                    'AC' => 34, 'AD' => 24, 'AE' => 6, 'AF' => 8,
                ];
                foreach ($anchos as $col => $ancho) {
                    $sheet->getColumnDimension($col)->setWidth($ancho);
                }

                $ultFila = count($this->filas) + 8;
                $sheet->getStyle("A1:AF{$ultFila}")->getBorders()->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                if ($ultFila >= 9) {
                    $sheet->getStyle("A9:AF{$ultFila}")->getFont()->setSize(8);
                    $sheet->getStyle("A9:AF{$ultFila}")->getAlignment()
                        ->setVertical(Alignment::VERTICAL_CENTER)
                        ->setWrapText(true);
                    $sheet->getStyle("A9:B{$ultFila}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("F9:AB{$ultFila}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("AE9:AF{$ultFila}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }
            },
        ];
    }
}
