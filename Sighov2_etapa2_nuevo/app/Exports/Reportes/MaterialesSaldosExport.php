<?php

namespace App\Exports\Reportes;

use App\Models\AlmacenGeneral\MaterialStock;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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

class MaterialesSaldosExport implements FromQuery, WithHeadings, WithMapping, WithEvents
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
        // 1. Normalizar fechas
        $inicio = $this->fechaInicio ? Carbon::parse($this->fechaInicio)->startOfDay()->toDateTimeString() : null;
        $fin    = $this->fechaFin    ? Carbon::parse($this->fechaFin)->endOfDay()->toDateTimeString() : null;

        // 2. Subconsulta de último lote por material y unidad
        $ultimoIdLote = DB::table('almacen_general.material_stock')
            ->select('id_material', DB::raw('MAX(id) as max_id'))
            ->where('id_unidad', 19)
            ->when($inicio && $fin, fn($q) => $q->whereBetween('created_at', [$inicio, $fin]))
            ->groupBy('id_material');

        // 3. Consulta principal sobre almacen_general
        $query = MaterialStock::query()
            ->join('almacen_general.material as m', 'm.id', '=', 'almacen_general.material_stock.id_material')
            ->joinSub($ultimoIdLote, 'ul', 'ul.id_material', '=', 'almacen_general.material_stock.id_material')
            ->join('almacen_general.material_stock as ul_stock', 'ul_stock.id', '=', 'ul.max_id')
            ->where('almacen_general.material_stock.id_unidad', 19)
            ->when($inicio && $fin, fn($q) => $q->whereBetween('almacen_general.material_stock.created_at', [$inicio, $fin]))
            ->select([
                'almacen_general.material_stock.id_material as id',
                'm.codigo as codigo',
                'm.nombre_material as descripcion',
                'm.unidad as unidad_medida',
                'ul_stock.precio_unitario as precio_ultimo_lote',
                DB::raw('SUM(almacen_general.material_stock.stock_actualizado) as cantidad_total'),
                DB::raw('SUM(almacen_general.material_stock.stock_actualizado) * ul_stock.precio_unitario as saldo_final')
            ])
            ->groupBy(
                'almacen_general.material_stock.id_material',
                'm.codigo',
                'm.nombre_material',
                'm.unidad',
                'ul_stock.precio_unitario'
            )
            ->havingRaw('SUM(almacen_general.material_stock.stock_actualizado) > 0')
            ->orderBy('m.codigo', 'asc');

        // 4. Conteo de registros para el membrete
        $this->totalRegistros = DB::table(DB::raw("({$query->toSql()}) as sub"))
            ->mergeBindings($query->getQuery())
            ->count();

        return $query;
    }
    public function query()
    {
        $query = DB::table('almacen_general.cierre_gestion as cg')
            ->join('almacen_general.material as m', 'm.id', '=', 'cg.id_material')
            ->join('almacen_general.movimiento as mov', function ($join) {
                $join->on('mov.id_corresponde', '=', 'cg.id')
                    ->where('mov.id_identificador_tabla', '=', 4)
                    ->on('mov.id_material', '=', 'cg.id_material')
                    ->on('mov.id_unidad', '=', 'cg.id_unidad');
            })
            ->leftJoin('almacen_general.material_stock as ms', function ($join) {
                // Vinculamos ms con cg para los IDs base, y tomamos nro_lote de mov solo si existe
                $join->on('ms.id_material', '=', 'cg.id_material')
                    ->on('ms.id_unidad', '=', 'cg.id_unidad')
                    ->on(function ($query) {
                        $query->on('ms.nro_lote', '=', 'mov.nro_lote')
                            ->orWhereNull('mov.nro_lote');
                    });
            })
            ->where('cg.id_unidad', 19)
            ->where('cg.cantidad_fisica', '>', 0) // Filtro directo sin necesidad de HAVING
            ->whereBetween('cg.fecha_registro', [
                $this->fechaInicio,
                $this->fechaFin
            ])
            ->select([
                'm.id as id',
                'm.codigo as codigo',
                'm.nombre_material as descripcion',
                'm.unidad as unidad_medida',
                'cg.cantidad_fisica as cantidad',
                DB::raw('COALESCE(MAX(ms.precio_unitario), 0) as precio_unitario'),
                DB::raw('cg.cantidad_fisica * COALESCE(MAX(ms.precio_unitario), 0) as saldo_final'),
            ])
            ->groupBy('m.id', 'm.codigo', 'm.nombre_material', 'm.unidad', 'cg.cantidad_fisica', 'cg.id')
            ->orderBy('m.codigo', 'asc');

        $this->totalRegistros = DB::table(DB::raw("({$query->toSql()}) as sub"))
            ->mergeBindings($query)
            ->count();

        return $query;
    }*/
    public function query()
    {
        $sql = "
            WITH cierre_con_movimiento AS (
                SELECT 
                    cg.id_material,
                    mov.nro_lote,
                    COALESCE(SUM(cg.cantidad_fisica), 0) AS cantidad_total,
                    MAX(mov.id) AS max_mov_id
                FROM almacen_general.cierre_gestion cg
                LEFT JOIN almacen_general.movimiento mov 
                    ON mov.id_corresponde = cg.id 
                   AND mov.id_identificador_tabla = 4 
                   AND mov.cierre_gestion = TRUE
                WHERE cg.fecha_registro BETWEEN ? AND ?
                  AND cg.id_unidad = 19
                GROUP BY cg.id_material, mov.nro_lote
                HAVING SUM(cg.cantidad_fisica) > 0
            ),
            lote_cierre_principal AS (
                SELECT DISTINCT ON (cm.id_material)
                    cm.id_material,
                    cm.nro_lote,
                    cm.cantidad_total
                FROM cierre_con_movimiento cm
                ORDER BY cm.id_material, cm.max_mov_id DESC NULLS LAST
            ),
            precios_lote AS (
                SELECT DISTINCT ON (lcp.id_material)
                    lcp.id_material,
                    ms.precio_unitario
                FROM lote_cierre_principal lcp
                INNER JOIN almacen_general.material_stock ms 
                    ON ms.id_material = lcp.id_material 
                   AND ms.nro_lote = lcp.nro_lote 
                   AND ms.id_unidad = 19
                   AND ms.precio_unitario > 0
                ORDER BY lcp.id_material, ms.id DESC
            ),
            ultimo_precio_stock AS (
                SELECT DISTINCT ON (ms.id_material)
                    ms.id_material,
                    ms.precio_unitario
                FROM almacen_general.material_stock ms
                WHERE ms.id_unidad = 19
                  AND ms.precio_unitario > 0
                ORDER BY ms.id_material, ms.id DESC
            )
            SELECT 
                lcp.id_material,
                COALESCE(m.codigo, 'S/C') AS codigo,
                COALESCE(m.nombre_material, 'MATERIAL ID: ' || lcp.id_material) AS descripcion,
                COALESCE(m.unidad, 'PZA') AS unidad_medida,
                lcp.cantidad_total AS cantidad,
                COALESCE(pl.precio_unitario, ups.precio_unitario, 0) AS precio_unitario,
                (lcp.cantidad_total * COALESCE(pl.precio_unitario, ups.precio_unitario, 0)) AS saldo_final
            FROM lote_cierre_principal lcp
            LEFT JOIN almacen_general.material m ON m.id = lcp.id_material
            LEFT JOIN precios_lote pl ON pl.id_material = lcp.id_material
            LEFT JOIN ultimo_precio_stock ups ON ups.id_material = lcp.id_material
        ";

        $query = DB::query()
            ->fromRaw("({$sql}) as reporte", [
                $this->fechaInicio,
                $this->fechaFin
            ])
            ->orderBy('codigo', 'asc');

        $this->totalRegistros = DB::query()
            ->fromRaw("({$sql}) as rec", [$this->fechaInicio, $this->fechaFin])
            ->count();

        return $query;
    }

    public function headings(): array
    {
        return [
            'N°',
            'CÓDIGO',
            'DESCRIPCIÓN DE MATERIAL',
            'UNIDAD',
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
            !empty($row->unidad_medida) ? $row->unidad_medida : 'PZA',
            number_format(is_numeric($row->cantidad) ? $row->cantidad : 0, 2, '.', ''),
            number_format(is_numeric($row->precio_unitario) ? $row->precio_unitario : 0, 2, '.', ''),
            number_format(is_numeric($row->saldo_final) ? $row->saldo_final : 0, 2, '.', ''),
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

                // Membrete principal
                $sheet->setCellValue('C2', 'KARDEX CONTABLE');
                $sheet->setCellValue('C3', 'REPORTE DE SALDOS FINALES - ALMACÉN GENERAL');

                $sheet->setCellValue(
                    'G2',
                    'FECHA IMPRESIÓN: ' . ($this->info_general['fecha_actual'] ?? now()->format('d/m/Y H:i'))
                );

                $sheet->setCellValue(
                    'G3',
                    'USUARIO: ' . ($this->info_general['user_name'] ?? 'SISTEMA')
                );

                $sheet->setCellValue(
                    'G4',
                    'N° REGISTROS: ' . $this->totalRegistros
                );

                $sheet->setCellValue('A6', 'SALDOS FINALES DE MATERIALES');
                $sheet->mergeCells('A6:G6');

                $fechaInicioStr = $this->info_general['fecha_inicio'] ?? ($this->fechaInicio ? date('d/m/Y', strtotime($this->fechaInicio)) : 'INICIO');
                $fechaFinStr    = $this->info_general['fecha_fin']    ?? ($this->fechaFin ? date('d/m/Y', strtotime($this->fechaFin)) : 'HASTA LA FECHA');

                $sheet->setCellValue('A7', 'DEL: ' . $fechaInicioStr . ' HASTA: ' . $fechaFinStr);
                $sheet->mergeCells('A7:G7');

                // Estilos del membrete
                $sheet->getStyle('C2')->getFont()->getColor()->setRGB('1A5276');
                $sheet->getStyle('C3')->getFont()->getColor()->setRGB('A93226');
                $sheet->getStyle('C2:C3')->getFont()->setBold(true);

                $sheet->getStyle('G2:G4')->getFont()->setBold(true);

                $sheet->getStyle('A6:A7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A6:A7')->getFont()->setBold(true)->setSize(12);

                // Encabezado de la tabla (Fila 8)
                $sheet->getStyle('A8:G8')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
                $sheet->getStyle('A8:G8')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('2471A3');

                $totalFilas = $this->totalRegistros + 8;

                // Fila de TOTAL GENERAL al final
                if ($this->totalRegistros > 0) {
                    $filaTotal = $totalFilas + 1;
                    $sheet->setCellValue("C{$filaTotal}", "TOTALES GENERALES:");
                    $sheet->setCellValue("E{$filaTotal}", "=SUM(E9:E{$totalFilas})"); 
                    $sheet->setCellValue("F{$filaTotal}", "=SUM(F9:F{$totalFilas})"); // Cantidad Total
                    $sheet->setCellValue("G{$filaTotal}", "=SUM(G9:G{$totalFilas})"); // Saldo Final (Bs.)

                    $sheet->getStyle("A{$filaTotal}:H{$filaTotal}")->getFont()->setBold(true);
                    $sheet->getStyle("C{$filaTotal}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                    // Fondo suave para la fila de total general
                    $sheet->getStyle("A{$filaTotal}:G{$filaTotal}")->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setRGB('EBF1F5');

                    $totalFilas = $filaTotal;
                }

                // Aplicar Bordes
                $sheet->getStyle("A8:G{$totalFilas}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '2471A3'],
                        ],
                    ],
                ]);

                // Alineaciones de celdas
                $sheet->getStyle("A9:B{$totalFilas}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("D9:D{$totalFilas}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("E9:G{$totalFilas}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                // Formato de números / moneda
                $sheet->getStyle("E9:G{$totalFilas}")->getNumberFormat()->setFormatCode('#,##0.00');

                // Anchos de columna
                $columnWidths = [
                    'A' => 6,   // N°
                    'B' => 18,  // Código
                    'C' => 48,  // Descripción
                    'D' => 14,  // Unidad
                    'E' => 16,  // Cantidad
                    'F' => 18,  // Precio U.
                    'G' => 22,  // Saldo Final
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
