<?php

namespace App\Exports\Reportes;

use App\Models\Farmacia\MedicamentoStock;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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

class AlmacenMedicamentosSaldosExport implements FromQuery, WithHeadings, WithMapping, WithEvents
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
    public function query()
    {
        // 1. Normalizar fechas con Carbon para evitar sintaxis inválida "00:00:00 00:00:00"
        $inicio = $this->fechaInicio ? Carbon::parse($this->fechaInicio)->startOfDay()->toDateTimeString() : null;
        $fin    = $this->fechaFin    ? Carbon::parse($this->fechaFin)->endOfDay()->toDateTimeString() : null;

        // 2. Subconsulta de último lote
        $ultimoIdLote = DB::table('farmacia.medicamento_stock')
            ->select('id_medicamento', DB::raw('MAX(id) as max_id'))
            ->where('id_unidad', 89)
            ->when($inicio && $fin, fn($q) => $q->whereBetween('created_at', [$inicio, $fin]))
            ->groupBy('id_medicamento');

        // 3. Consulta principal compatible con PostgreSQL
        $query = MedicamentoStock::query()
            ->join('farmacia.medicamento as m', 'm.id', '=', 'farmacia.medicamento_stock.id_medicamento')
            ->joinSub($ultimoIdLote, 'ul', 'ul.id_medicamento', '=', 'farmacia.medicamento_stock.id_medicamento')
            ->join('farmacia.medicamento_stock as ul_stock', 'ul_stock.id', '=', 'ul.max_id')
            ->where('farmacia.medicamento_stock.id_unidad', 89)
            ->when($inicio && $fin, fn($q) => $q->whereBetween('farmacia.medicamento_stock.created_at', [$inicio, $fin]))
            ->select([
                'farmacia.medicamento_stock.id_medicamento as id',
                'm.codigo_medicamento as codigo',
                'm.nombre as descripcion',
                'ul_stock.precio_unitario as precio_ultimo_lote',
                DB::raw('SUM(farmacia.medicamento_stock.stock_actualizado) as cantidad_total'),
                DB::raw('SUM(farmacia.medicamento_stock.stock_actualizado) * ul_stock.precio_unitario as saldo_final')
            ])
            ->groupBy(
                'farmacia.medicamento_stock.id_medicamento',
                'm.codigo_medicamento',
                'm.nombre',
                'ul_stock.precio_unitario'
            )
            ->havingRaw('SUM(farmacia.medicamento_stock.stock_actualizado) > 0')
            ->orderBy('m.codigo_medicamento', 'asc'); // Evita el fallo de PostgreSQL al agrupar

        // 4. Calcular el total de registros de forma segura para PostgreSQL
        $this->totalRegistros = DB::table(DB::raw("({$query->toSql()}) as sub"))
            ->mergeBindings($query->getQuery())
            ->count();

        return $query;
    }*/
    public function query()
    {
        // Subconsulta consolidada exclusiva para Almacén (Unidad 89)
        $datosCierre = DB::table('farmacia.cierre_gestion as cg')
            ->join('farmacia.movimiento as mov', function ($join) {
                $join->on('mov.id_corresponde', '=', 'cg.id')
                    ->on('mov.id_medicamento', '=', 'cg.id_medicamento')
                    ->on('mov.id_unidad', '=', 'cg.id_unidad')
                    ->where('mov.id_identificador_tabla', 4)
                    ->where('mov.cierre_gestion', true);
            })
            ->leftJoin('farmacia.medicamento_stock as ms', function ($join) {
                $join->on('ms.id_medicamento', '=', 'mov.id_medicamento')
                    ->on('ms.id_unidad', '=', 'mov.id_unidad')
                    ->on('ms.nro_lote', '=', 'mov.nro_lote')
                    ->where('ms.cierre_gestion', true);
            })
            ->where('cg.id_unidad', 89) // Exclusivo Almacén de Medicamentos
            ->when($this->fechaInicio && $this->fechaFin, function ($q) {
                $q->whereBetween('cg.fecha_registro', [$this->fechaInicio, $this->fechaFin]);
            })
            ->select([
                'cg.id_medicamento',
                DB::raw('SUM(cg.cantidad_fisica) as total_cantidad_fisica'),
                DB::raw('COALESCE(MAX(ms.precio_unitario), 0) as precio_unitario')
            ])
            ->groupBy('cg.id_medicamento');

        // Consulta principal uniendo con el catálogo maestro de Medicamentos
        $query = DB::table('farmacia.medicamento as m')
            ->joinSub($datosCierre, 'dc', 'dc.id_medicamento', '=', 'm.id')
            ->select([
                'm.id',
                'm.codigo_medicamento as codigo',
                'm.nombre as descripcion',
                'dc.precio_unitario as precio_ultimo_lote',
                'dc.total_cantidad_fisica as cantidad_total',
                DB::raw('dc.total_cantidad_fisica * dc.precio_unitario as saldo_final')
            ])
            ->where('dc.total_cantidad_fisica', '>', 0)
            ->orderBy('m.codigo_medicamento', 'asc');

        // Conteo optimizado para PostgreSQL
        $this->totalRegistros = DB::table(DB::raw("({$query->toSql()}) as sub"))
            ->mergeBindings($query)
            ->count();

        return $query;
    }

    public function headings(): array
    {
        return [
            'N°',
            'CÓDIGO',
            'DESCRIPCIÓN / MEDICAMENTO',
            'CANTIDAD',
            'PRECIO UNITARIO',
            'SALDO FINAL (Bs.)',
        ];
    }

    public function map($row): array
    {
        static $index = 0;
        $index++;

        return [
            $index,
            !empty($row->codigo) ? $row->codigo : '-',
            !empty($row->descripcion) ? $row->descripcion : '-',
            isset($row->cantidad_total) ? (float) $row->cantidad_total : 0,
            isset($row->precio_ultimo_lote) ? (float) $row->precio_ultimo_lote : 0,
            isset($row->saldo_final) ? (float) $row->saldo_final : 0,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                // Orientación horizontal
                $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);

                // Insertar 7 filas superiores para el membrete
                $sheet->insertNewRowBefore(1, 7);

                // Membrete
                $sheet->setCellValue('C2', 'KARDEX CONTABLE');
                $sheet->setCellValue('C3', 'REPORTE DE SALDOS FINALES - ALMACÉN MEDICAMENTOS');

                $sheet->setCellValue(
                    'F2',
                    'FECHA IMPRESIÓN: ' . ($this->info_general['fecha_actual'] ?? now()->format('d/m/Y H:i'))
                );

                $sheet->setCellValue(
                    'F3',
                    'USUARIO: ' . ($this->info_general['user_name'] ?? 'SISTEMA')
                );

                $sheet->setCellValue(
                    'F4',
                    'N° REGISTROS: ' . $this->totalRegistros
                );

                $sheet->setCellValue('A6', 'SALDOS FINALES DE ALMACÉN DE MEDICAMENTOS');
                $sheet->mergeCells('A6:F6');

                $fechaInicioStr = $this->info_general['fecha_inicio'] ?? ($this->fechaInicio ? date('d/m/Y', strtotime($this->fechaInicio)) : 'INICIO');
                $fechaFinStr    = $this->info_general['fecha_fin']    ?? ($this->fechaFin ? date('d/m/Y', strtotime($this->fechaFin)) : 'HASTA LA FECHA');

                $sheet->setCellValue('A7', 'DEL: ' . $fechaInicioStr . ' HASTA: ' . $fechaFinStr);
                $sheet->mergeCells('A7:F7');

                // Estilos de membrete
                $sheet->getStyle('C2')->getFont()->getColor()->setRGB('1A5276');
                $sheet->getStyle('C3')->getFont()->getColor()->setRGB('A93226');
                $sheet->getStyle('C2:C3')->getFont()->setBold(true);

                $sheet->getStyle('F2:F4')->getFont()->setBold(true);

                $sheet->getStyle('A6:A7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A6:A7')->getFont()->setBold(true)->setSize(12);

                // Encabezado de la tabla (Fila 8)
                $sheet->getStyle('A8:F8')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
                $sheet->getStyle('A8:F8')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('2471A3');

                $totalFilas = $this->totalRegistros + 8;

                // Fila de TOTAL GENERAL al final
                if ($this->totalRegistros > 0) {
                    $filaTotal = $totalFilas + 1;
                    $sheet->setCellValue("C{$filaTotal}", "TOTAL GENERAL (Bs.):");
                    $sheet->setCellValue("F{$filaTotal}", "=SUM(F9:F{$totalFilas})");

                    $sheet->getStyle("A{$filaTotal}:F{$filaTotal}")->getFont()->setBold(true);
                    $sheet->getStyle("C{$filaTotal}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    $sheet->getStyle("F{$filaTotal}")->getNumberFormat()->setFormatCode('#,##0.00');

                    // Actualizar límite de bordes incluyendo fila de total
                    $totalFilas = $filaTotal;
                }

                // Aplicar Bordes
                $sheet->getStyle("A8:F{$totalFilas}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '2471A3'],
                        ],
                    ],
                ]);

                // Alineaciones de celdas
                $sheet->getStyle("A9:B{$totalFilas}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("D9:F{$totalFilas}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                // Formato de moneda / números con dos decimales
                $sheet->getStyle("D9:F{$totalFilas}")->getNumberFormat()->setFormatCode('#,##0.00');

                // Anchos fijos por columna
                $columnWidths = [
                    'A' => 6,
                    'B' => 18,
                    'C' => 50,
                    'D' => 18,
                    'E' => 20,
                    'F' => 22,
                ];

                foreach ($columnWidths as $col => $width) {
                    $sheet->getColumnDimension($col)->setAutoSize(false);
                    $sheet->getColumnDimension($col)->setWidth($width);
                }

                // Logotipo institucional
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
