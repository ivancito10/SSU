<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;



class ReporteBeneficiariosExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    protected  $datosFinales,
        $tipo_reporte,
        $fecha_inicio,
        $fecha_fin,
        $nombre_institucion,
        $tipo_asegurado;
    protected $contador = 1;
    protected $maximoInstitucion = 0;

    public function __construct($datosFinales, $tipo_reporte, $fecha_inicio, $fecha_fin, $nombre_institucion, $tipo_asegurado)
    {
        $this->datosFinales = $datosFinales;
        if ($tipo_reporte == 'carnets') {
            $this->tipo_reporte = 'CARNETS EMITIDOS';
        } elseif ($tipo_reporte == 'registros') {
            $this->tipo_reporte = 'REGISTROS DE BENEFICIARIOS';
        } elseif ($tipo_reporte == 'institucion') {
            $this->tipo_reporte = 'POR INSTITUCIÓN';
        } elseif ($tipo_reporte == 'tipoBeneficiarios') {
            $this->tipo_reporte = 'POR TIPO DE BENEFICIARIO';
        } else {
            $this->tipo_reporte = '';
        }
        $this->nombre_institucion = $nombre_institucion;
        $this->fecha_inicio = $fecha_inicio;
        $this->fecha_fin = $fecha_fin;
        $this->tipo_asegurado = $tipo_asegurado;
    }
    public function collection()
    {
        return collect($this->datosFinales);
    }

    public function headings(): array
    {
        $cabecera = [
            'Nº',
            'PRIMER APELLIDO',
            'SEGUNDO APELLIDO',
            'NOMBRES',
            'MATRICULA',
            'C.I.',
            'FECHA NACIMIENTO',
            'EDAD',
            'SEXO',
            'TIPO ASEGURADO',
            'FECHA INGRESO',
            'FECHA AFILIACIÓN',
            'ESTADO',
            'INSTITUCIÓN TITULAR',

        ];
        if ($this->tipo_reporte == 'CARNETS EMITIDOS') {
            $cabecera[] = 'FECHA EMISIÓN';
            $cabecera[] = 'FECHA VENCIMIENTO';
            $cabecera[] = 'MOTIVO';
            $cabecera[] = 'TIPO CARNET';
        }


        return $cabecera;
    }

    public function map($row): array
    {
        $data = [
            //  $row['nro'],
            $this->contador++,
            $row['p_apellido'],
            $row['s_apellido'],
            $row['nombres'],
            $row['matricula_asegurado'],
            $row['ci'],
            $row['fecha_nacimiento'],
            $row['edad'],
            $row['sexo'],
            $row['tipo_asegurado'],
            $row['fecha_afiliacion'],
            $row['fecha_reafiliacion'],
            $row['estado'],
            $row['institucion'],
        ];
        if ($this->tipo_reporte == 'CARNETS EMITIDOS') {
            $data[] = $row['fecha_emision'];
            $data[] = $row['fecha_vencimiento'];
            $data[] = $row['motivo'];
            $data[] = $row['indefinido'];
        }

        return $data;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                // ORIENTACIÓN
                $sheet->getPageSetup()
                    ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);

                /*
            |--------------------------------------------------------------------------
            | INSERTAR FILAS SUPERIORES
            |--------------------------------------------------------------------------
            */
                $sheet->insertNewRowBefore(1, 7);

                /*
            |--------------------------------------------------------------------------
            | ÚLTIMA COLUMNA DINÁMICA
            |--------------------------------------------------------------------------
            */
                $ultima_columna = $sheet->getHighestColumn();

                /*
            |--------------------------------------------------------------------------
            | ESTILOS CABECERAS
            |--------------------------------------------------------------------------
            | headigns en la fila 8
            */
                $sheet->getStyle("A8:{$ultima_columna}8")
                    ->getFont()
                    ->setBold(true);

                $sheet->getStyle("A8:{$ultima_columna}8")
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB('9B9592');

                $sheet->getStyle("A8:{$ultima_columna}8")
                    ->getFont()
                    ->getColor()
                    ->setRGB('FFFFFF');

                /*
            |--------------------------------------------------------------------------
            | AUTO SIZE COLUMNAS
            |--------------------------------------------------------------------------
            */
                foreach (range('A', $ultima_columna) as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                /*
            |--------------------------------------------------------------------------
            | TITULO
            |--------------------------------------------------------------------------
            */
                $sheet->setCellValue('C2', 'REPORTES DE BENEFICIARIOS');
                $sheet->mergeCells('C2:E2');

                $sheet->getStyle('C2')
                    ->getFont()
                    ->setBold(true)
                    ->setSize(16);

                $sheet->getStyle('C2')
                    ->getFont()
                    ->getColor()
                    ->setRGB('1A5276');

                $sheet->setCellValue('C3', 'SEGURO SOCIAL UNIVERSITARIO LA PAZ');
                $sheet->mergeCells('C3:E3');

                $sheet->getStyle('C3')
                    ->getFont()
                    ->setBold(true)
                    ->setSize(14);

                $sheet->getStyle('C3')
                    ->getFont()
                    ->getColor()
                    ->setRGB('242424');

                if ($this->tipo_reporte == 'POR INSTITUCIÓN') {
                    $sheet->setCellValue('C4', strtoupper($this->nombre_institucion));
                    $sheet->mergeCells('C4:E4');

                    $sheet->getStyle('C4')
                        ->getFont()
                        ->setBold(true)
                        ->setSize(12);

                    $sheet->getStyle('C4')
                        ->getFont()
                        ->getColor()
                        ->setRGB('041C80');
                }
                /*
            |--------------------------------------------------------------------------
            | LOGO
            |--------------------------------------------------------------------------
            */
                $drawing = new Drawing();
                $drawing->setName('Logo SSU');
                $drawing->setDescription('Logo');
                $drawing->setPath(public_path('logo/ssu.png'));
                $drawing->setHeight(80);
                $drawing->setCoordinates('B2');
                $drawing->setWorksheet($sheet);

                /*
            |--------------------------------------------------------------------------
            | DATOS REPORTE
            |--------------------------------------------------------------------------
            */

                // TIPO REPORTE
                $sheet->setCellValue('F2', 'TIPO DE REPORTE:');
                $sheet->getStyle('F2')->getFont()->setBold(true);

                $sheet->mergeCells('G2:J2');
                $sheet->setCellValue('G2', strtoupper($this->tipo_reporte));

                // DESDE
                $sheet->setCellValue('F3', 'DESDE:');
                $sheet->getStyle('F3')->getFont()->setBold(true);

                $sheet->setCellValue('G3', $this->fecha_inicio);

                // HASTA
                $sheet->setCellValue('F4', 'HASTA:');
                $sheet->getStyle('F4')->getFont()->setBold(true);

                $sheet->setCellValue('G4', $this->fecha_fin);

                // GENERADO
                $sheet->setCellValue('F6', 'Generado por Sistema SIGHO V2');
                $sheet->mergeCells('F6:H6');

                $sheet->getStyle('F6')
                    ->getFont()
                    ->setBold(true);

                /*
            |--------------------------------------------------------------------------
            | ALINEACIÓN GENERAL
            |--------------------------------------------------------------------------
            */
                $sheet->getStyle("A8:{$ultima_columna}{$sheet->getHighestRow()}")
                    ->getAlignment()
                    ->setVertical(Alignment::VERTICAL_CENTER);

                /*
            |--------------------------------------------------------------------------
            | CENTRAR CABECERAS
            |--------------------------------------------------------------------------
            */
                $sheet->getStyle("A8:{$ultima_columna}8")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);
            },
        ];
    }
}
