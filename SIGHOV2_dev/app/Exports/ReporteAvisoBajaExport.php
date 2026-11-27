<?php

namespace App\Exports;

use App\Models\Aportes\AvisoBajaInstitucional;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Override;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;



use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class ReporteAvisoBajaExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    protected $fecha_inicio;
    protected $fecha_fin;
    protected $fecha_inicio_mostrar, $fecha_fin_mostrar;
    protected $nombre_institucion;
    protected $id_institucion;
    private $index = 0;

    public function __construct($fecha_inicio, $fecha_fin, $id_institucion, $nombre_institucion)
    {
        $this->fecha_inicio = $fecha_inicio;
        $this->fecha_fin = $fecha_fin;
        $this->fecha_inicio_mostrar = Carbon::parse($fecha_inicio)->format('d/m/Y');
        $this->fecha_fin_mostrar = Carbon::parse($fecha_fin)->format('d/m/Y');
        $this->nombre_institucion = $nombre_institucion;
        $this->id_institucion = $id_institucion;
    }
    public function collection()
    {
        $datos = AvisoBajaInstitucional::with('personaAportante', 'institucionAvisoBaja')
            ->where('id_institucion', $this->id_institucion)
            ->whereDate('fecha_baja', '>=', $this->fecha_inicio)
            ->whereDate('fecha_baja', '<=', $this->fecha_fin)
            ->get();
        //  dd($datos);
        return $datos;
    }

    #[Override]
    public function headings(): array
    {
        return [
            'Nº',
            'AP. PATERNO',
            'AP. MATERNO',
            'NOMBRES',
            'C.I.',
            'Nº MATRICULA',
            'ULTIMO SALARIO',
            'FECHA DE BAJA',
            'MOTIVO DE BAJA'
        ];
    }



    public function map($row): array
    {
        $this->index++;
        $persona = $row->personaAportante;
        $institucion = $row->institucionAvisoBaja;
        $fecha_formateada = Carbon::parse($row->fecha_baja)->format('d/m/Y');
        // $nombreCompleto = $persona ? trim("{$persona->nombres} {$persona->p_apellido} {$persona->s_apellido}") : '';
        return [
            $this->index,
            $persona?->p_apellido,
            $persona?->s_apellido,
            $persona?->nombres,
            $persona?->ci,
            $persona?->matricula_seguro,
            $row->ultimo_salario,
            $fecha_formateada,
            $row->motivo_baja,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);

                // 1. Insertar filas superiores primero
                $sheet->insertNewRowBefore(1, 7);

                // 2. Aplicar formato al encabezado de la tabla (Ahora está en la fila 8)
                $sheet->getStyle('A8:J8')->getFont()->setBold(true);
                $sheet->getStyle('A8:J8')->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('9B9592');
                $sheet->getStyle('A8:J8')->getFont()->getColor()->setRGB('FFFFFF');

                // 3. Insertar título e imagen
                $sheet->setCellValue('C2', 'AVISO DE BAJA');
                $sheet->mergeCells('C2:I2');
                $sheet->setCellValue('C3', $this->nombre_institucion);
                $sheet->mergeCells('C3:I3');

                $sheet->getStyle('C2')->getFont()->getColor()->setRGB('1A5276');
                $sheet->getStyle('C2')->getFont()->setBold(true);
                $sheet->getStyle('C2')->getFont()->getColor()->setRGB('1A5276');
                $sheet->getStyle('G3')->getFont()->getColor()->setRGB('A93226');
                $sheet->getStyle('C2:I3')->getFont()->setBold(true);

                $drawing = new Drawing();
                $drawing->setName('Logo SSU');
                $drawing->setDescription('Logo');
                $drawing->setPath(public_path('logo/ssu.png'));
                $drawing->setHeight(80);
                $drawing->setCoordinates('B2');
                $drawing->setWorksheet($sheet);

                // 4. Datos de la institución y fechas


                $sheet->setCellValue('H4', 'DESDE: ');
                $sheet->getStyle('H4')->getFont()->setBold(true);
                $sheet->setCellValue('I4', $this->fecha_inicio_mostrar);

                $sheet->setCellValue('H5', 'HASTA: ');
                $sheet->getStyle('H5')->getFont()->setBold(true);
                $sheet->setCellValue('I5', $this->fecha_fin_mostrar);

                $sheet->setCellValue('H6', 'Generado por Sistema Sigho V2 ');
                $sheet->getStyle('H6')->getFont()->setBold(true);
                $sheet->mergeCells('H6:J6');
            },
        ];
    }
}
