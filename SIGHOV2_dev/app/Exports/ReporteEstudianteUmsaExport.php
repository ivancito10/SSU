<?php

namespace App\Exports;

use App\Models\Estudiante;
use Maatwebsite\Excel\Concerns\FromCollection;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class ReporteEstudianteUmsaExport implements FromCollection, WithHeadings, WithMapping, WithEvents, WithTitle
{
    protected $datosFinales;
    protected $tipoReporte, $estado_validar;
    protected $fecha_inicio, $fecha_fin;
    protected $contador = 1;
    public function __construct($datosFinales, $tipo_reporte, $fecha_inicio, $fecha_fin, $estado_validar)
    {
        $this->datosFinales = $datosFinales;
        $this->fecha_inicio = $fecha_inicio;
        $this->estado_validar = $estado_validar;
        $this->fecha_fin = $fecha_fin;
        if ($tipo_reporte == 'reporte_umsa') {
            $this->tipoReporte = 'REPORTE MENSUAL UMSA';
        } else {
            $this->tipoReporte = 'REGISTROS DE ESTUDIANTES';
        }
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
            'C.I.',
            'CARRERA',
            'FACULTAD',

        ];

        if ($this->tipoReporte == 'REGISTROS DE ESTUDIANTES') {
            $cabecera[] =  'FECHA NACIMIENTO';
            $cabecera[] =  'EDAD';
            $cabecera[] =  'SEXO';
            $cabecera[] =  'FECHA INGRESO';
            $cabecera[] =  'FECHA AFILIACIÓN';
            $cabecera[] =  'ESTADO AFILIACIÓN';
            $cabecera[] =  'ESTADO';
            $cabecera[] =  'RESPONSABLE CREADOR';
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
            $row['ci'],
            $row['carrera'],
            $row['facultad'],

        ];
        if ($this->tipoReporte == 'REGISTROS DE ESTUDIANTES') {
            $data[] =  $row['fecha_nacimiento'];
            $data[] = $row['edad'];
            $data[] = $row['sexo'];

            $data[] = $row['fecha_ingreso'];
            $data[] = $row['fecha_afiliacion'];
            $data[] = $row['estado'];
            $data[] = $row['esAntiguo'];
            $data[] = $row['name_creador'];
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
                $sheet->setCellValue('C2', 'REPORTES DE ESTUDIANTES');
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


                $sheet->setCellValue('C4', strtoupper($this->tipoReporte));
                $sheet->mergeCells('C4:E4');

                $sheet->getStyle('C4')
                    ->getFont()
                    ->setBold(true)
                    ->setSize(12);

                $sheet->getStyle('C4')
                    ->getFont()
                    ->getColor()
                    ->setRGB('041C80');

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

                // ESTADO VALIDAR
                $sheet->setCellValue('F2', 'POR ESTADO:');
                $sheet->getStyle('F2')->getFont()->setBold(true);

                $sheet->mergeCells('G2:J2');
                $sheet->setCellValue('G2', strtoupper($this->estado_validar));

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
    public function title(): string
    {
        // Aquí puedes personalizar el nombre dinámicamente
        return $this->tipoReporte;
    }
}
