<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;


class ReporteAfiliadosPorInstitucion implements FromCollection, WithEvents, WithHeadings
{

    protected $datos, $datos_institucion, $mes, $gestion;
    public function __construct($data, $datosInstitucion, $mes, $gestion)
    {
        $this->datos = $data;
        $this->datos_institucion = $datosInstitucion;
        $this->mes = $mes;
        $this->gestion = $gestion;
    }


    public function collection()
    {
        //dd($this->datos);
        return collect($this->datos);
    }

    public function headings(): array
    {
        return [
            'Nº',
            'PRIMER APELLIDO',
            'SEGUNDO APELLIDO',
            'NOMBRE',
            'CARNET IDENTIDAD',
            'COMPLEMENTO',
            'MATRICULA SEGURO',
            'TOTAL GANADO',
            'TOTAL APORTE',
            'TIPO APORTE',
            'ESTADO AFILIACIÓN',
        ];
    }

    // Eventos para formatear el Excel
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);

                // Cabecera: Negrita y color
                $sheet->getStyle('A1:K1')->getFont()->setBold(true);
                $sheet->getStyle('A1:K1')->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('9B9592');

                $sheet->getStyle('A1:K1')->getFont()->getColor()->setRGB('FFFFFF');

                // Ajustar ancho de columnas
                foreach (range('A', 'K') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
                // Insertar filas de información del medicamento arriba de la tabla
                $sheet->insertNewRowBefore(1, 7);
                // insertamos titulo 
                $sheet->setCellValue('C2', 'DETALLE DE APORTANTES');
                $sheet->mergeCells('C2:D2');
                $sheet->setCellValue('C3', 'UNIDAD DE APORTES');
                $sheet->mergeCells('C3:D3');

                // CAMBIA COLORES
                $sheet->getStyle('C2')->getFont()->getColor()->setRGB('1A5276');
                $sheet->getStyle('C3')->getFont()->getColor()->setRGB('A93226');
                $sheet->getStyle('C2:C3')->getFont()->setBold(true);

                // --- Insertar imagen ---
                $drawing = new Drawing();
                $drawing->setName('Logo SSU');
                $drawing->setDescription('Logo');
                $drawing->setPath(public_path('logo/ssu.png'));
                $drawing->setHeight(80); // altura en pixeles
                $drawing->setCoordinates('B2');
                $drawing->setWorksheet($sheet);

                // INSTITUCION -------------
                $sheet->setCellValue('G2', 'INSTITUCIÓN: ');
                $sheet->getStyle('G2')->getFont()->setBold(true);
                // Combinar H2 hasta J2
                $sheet->mergeCells('H2:J2');
                // Escribir el nombre en la primera celda del rango
                $sheet->setCellValue('H2', $this->datos_institucion->nombre);
                // MES  --------------------
                $sheet->setCellValue('G3', 'MES: ');
                $sheet->getStyle('G3')->getFont()->setBold(true);
                //  $sheet->mergeCells('H3:J3');
                $sheet->setCellValue('H3', $this->mes);
                $sheet->getStyle('H3:J3')
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                // GESTION  --------------------
                $sheet->setCellValue('G4', 'GESTIÓN: ');
                $sheet->getStyle('G4')->getFont()->setBold(true);
                // $sheet->mergeCells('H3:J3');
                $sheet->setCellValue('H4', $this->gestion);
                $sheet->getStyle('H4:J4')
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);
            },
        ];
    }
}
