<?php

namespace App\Exports\Reportes;

use App\Models\Farmacia\MedicamentoStock;
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

class AlmacenMedicamentoFarmaciaSaldosExport implements FromQuery, WithHeadings, WithMapping, WithEvents
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
            ->whereIn('id_unidad', [76, 89])
            ->when($inicio && $fin, fn($q) => $q->whereBetween('created_at', [$inicio, $fin]))
            ->groupBy('id_medicamento');

        // 3. Consulta principal compatible con PostgreSQL
        $query = MedicamentoStock::query()
            ->join('farmacia.medicamento as m', 'm.id', '=', 'farmacia.medicamento_stock.id_medicamento')
            ->joinSub($ultimoIdLote, 'ul', 'ul.id_medicamento', '=', 'farmacia.medicamento_stock.id_medicamento')
            ->join('farmacia.medicamento_stock as ul_stock', 'ul_stock.id', '=', 'ul.max_id')
            ->whereIn('farmacia.medicamento_stock.id_unidad', [76, 89])
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
    }
    public function query()
    {
        // 1. Normalizar fechas con Carbon
        $inicio = $this->fechaInicio ? Carbon::parse($this->fechaInicio)->startOfDay()->toDateTimeString() : null;
        $fin    = $this->fechaFin    ? Carbon::parse($this->fechaFin)->endOfDay()->toDateTimeString() : null;

        // 2. Suma ÚNICA de cantidad_fisica por medicamento desde cierre_gestion (sin duplicaciones)
        $cantidadesCierre = DB::table('farmacia.cierre_gestion')
            ->select('id_medicamento', DB::raw('SUM(cantidad_fisica) as total_cantidad_fisica'))
            ->whereIn('id_unidad', [76, 89])
            ->when($inicio && $fin, function ($query) use ($inicio, $fin) {
                $query->whereBetween('fecha_registro', [$inicio, $fin]);
            })
            ->groupBy('id_medicamento');

        // 3. Obtener los cierres y sus movimientos para ubicar el lote de evaluación de precio
        $movimientosCierre = DB::table('farmacia.movimiento as mov')
            ->join('farmacia.cierre_gestion as cg', function ($join) {
                $join->on('mov.id_corresponde', '=', 'cg.id')
                    ->on('mov.id_medicamento', '=', 'cg.id_medicamento')
                    ->on('mov.id_unidad', '=', 'cg.id_unidad');
            })
            ->where('mov.id_identificador_tabla', 4)
            ->whereIn('mov.id_unidad', [76, 89])
            ->when($inicio && $fin, function ($query) use ($inicio, $fin) {
                $query->whereBetween('cg.fecha_registro', [$inicio, $fin]);
            })
            ->select('mov.id_medicamento', 'mov.nro_lote')
            ->distinct();

        // 4. Extraer ÚNICAMENTE el precio unitario del lote correspondiente
        $preciosLote = DB::table('farmacia.medicamento_stock as ms')
            ->joinSub($movimientosCierre, 'mc', function ($join) {
                $join->on('ms.id_medicamento', '=', 'mc.id_medicamento')
                    ->on('ms.nro_lote', '=', 'mc.nro_lote');
            })
            ->whereIn('ms.id_unidad', [76, 89])
            ->where('ms.cierre_gestion', true)
            ->select('ms.id_medicamento', DB::raw('MAX(ms.precio_unitario) as precio_unitario'))
            ->groupBy('ms.id_medicamento');

        // 5. Consulta principal uniendo los montos sin multiplicar filas
        $query = MedicamentoStock::query()
            ->join('farmacia.medicamento as m', 'm.id', '=', 'farmacia.medicamento_stock.id_medicamento')
            ->joinSub($cantidadesCierre, 'cc', 'cc.id_medicamento', '=', 'farmacia.medicamento_stock.id_medicamento')
            ->joinSub($preciosLote, 'pl', 'pl.id_medicamento', '=', 'farmacia.medicamento_stock.id_medicamento')
            ->whereIn('farmacia.medicamento_stock.id_unidad', [76, 89])
            ->where('farmacia.medicamento_stock.cierre_gestion', true)
            ->select([
                'farmacia.medicamento_stock.id_medicamento as id',
                'm.codigo_medicamento as codigo',
                'm.nombre as descripcion',
                'pl.precio_unitario as precio_ultimo_lote',
                'cc.total_cantidad_fisica as cantidad_total',
                DB::raw('cc.total_cantidad_fisica * pl.precio_unitario as saldo_final')
            ])
            ->groupBy(
                'farmacia.medicamento_stock.id_medicamento',
                'm.codigo_medicamento',
                'm.nombre',
                'pl.precio_unitario',
                'cc.total_cantidad_fisica'
            )
            ->havingRaw('cc.total_cantidad_fisica > 0')
            ->orderBy('m.codigo_medicamento', 'asc');

        // 6. Conteo seguro de registros para PostgreSQL
        $this->totalRegistros = DB::table(DB::raw("({$query->toSql()}) as sub"))
            ->mergeBindings($query->getQuery())
            ->count();

        return $query;
    }
    public function query()
    {
        //Subconsulta consolidada: Cierre + Movimiento + Stock en un solo escaneo
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
            ->whereIn('cg.id_unidad', [76, 89])
            ->when($this->fechaInicio && $this->fechaFin, function ($q) {
                $q->whereBetween('cg.fecha_registro', [$this->fechaInicio, $this->fechaFin]);
            })
            ->select([
                'cg.id_medicamento',
                DB::raw('SUM(cg.cantidad_fisica) as total_cantidad_fisica'),

                DB::raw("
                COALESCE(
                    MAX(ms.precio_unitario) FILTER (WHERE mov.id_unidad = 89 AND ms.precio_unitario > 0),
                    MAX(ms.precio_unitario) FILTER (WHERE mov.id_unidad = 76 AND ms.precio_unitario > 0),
                    0
                ) as precio_unitario
            ")
            ])
            ->groupBy('cg.id_medicamento');

        // Consulta principal optimizada a partir del catálogo de Medicamentos
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
    public function query()
    {
        // 1. Subconsulta de agregación agrupada por id_medicamento
        $datosCierre = DB::table('farmacia.cierre_gestion as cg')
            ->leftJoin('farmacia.movimiento as mov', function ($join) {
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
            ->whereIn('cg.id_unidad', [76, 89])
            ->when($this->fechaInicio && $this->fechaFin, function ($q) {
                $q->whereBetween('cg.fecha_registro', [$this->fechaInicio, $this->fechaFin]);
            })
            ->select([
                'cg.id_medicamento',
                DB::raw('SUM(cg.cantidad_fisica) as total_cantidad_fisica'),
                DB::raw("
                COALESCE(
                    -- 1. Precio del lote de cierre en stock
                    MAX(ms.precio_unitario) FILTER (WHERE ms.precio_unitario > 0),
                    
                    -- 2. Precio PEPS en movimiento
                    MAX(mov.precio_unitario_peps) FILTER (WHERE mov.precio_unitario_peps > 0),
                    
                    -- 3. Fallback Lote 1 Almacén (89)
                    (
                        SELECT ms89.precio_unitario 
                        FROM farmacia.medicamento_stock ms89 
                        WHERE ms89.id_medicamento = cg.id_medicamento 
                          AND ms89.id_unidad = 89 
                          AND ms89.precio_unitario > 0 
                        ORDER BY ms89.id ASC 
                        LIMIT 1
                    ),

                    -- 4. Fallback Lote 1 Farmacia (76)
                    (
                        SELECT ms76.precio_unitario 
                        FROM farmacia.medicamento_stock ms76 
                        WHERE ms76.id_medicamento = cg.id_medicamento 
                          AND ms76.id_unidad = 76 
                          AND ms76.precio_unitario > 0 
                        ORDER BY ms76.id ASC 
                        LIMIT 1
                    ),

                    0
                ) as precio_unitario
            ")
            ])
            ->groupBy('cg.id_medicamento');
            //->havingRaw('SUM(cg.cantidad_fisica) > 0');

        // 2. Consulta Principal basada en el Cierre con LEFT JOIN al catálogo
        $query = DB::table(DB::raw("({$datosCierre->toSql()}) as dc"))
            ->mergeBindings($datosCierre)
            ->leftJoin('farmacia.medicamento as m', 'm.id', '=', 'dc.id_medicamento')
            ->select([
                'dc.id_medicamento as id',
                DB::raw("COALESCE(m.codigo_medicamento, 'S/C') as codigo"),
                DB::raw("COALESCE(m.nombre, 'MEDICAMENTO NO REGISTRADO (ID: ' || dc.id_medicamento || ')') as descripcion"),
                'dc.total_cantidad_fisica as cantidad_total',
                'dc.precio_unitario as precio_ultimo_lote',
                DB::raw('dc.total_cantidad_fisica * dc.precio_unitario as saldo_final')
            ])
            ->orderBy('dc.id_medicamento', 'asc');

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
                    cg.id_medicamento,
                    mov.nro_lote,
                    COALESCE(SUM(cg.cantidad_fisica) FILTER (WHERE cg.id_unidad = 89), 0) AS cantidad_almacen,
                    COALESCE(SUM(cg.cantidad_fisica) FILTER (WHERE cg.id_unidad = 76), 0) AS cantidad_farmacia,
                    COALESCE(SUM(cg.cantidad_fisica), 0) AS cantidad_total,
                    MAX(mov.id) AS max_mov_id
                FROM farmacia.cierre_gestion cg
                LEFT JOIN farmacia.movimiento mov 
                    ON mov.id_corresponde = cg.id 
                   AND mov.id_identificador_tabla = 4 
                   AND mov.cierre_gestion = TRUE
                WHERE cg.fecha_registro BETWEEN ? AND ?
                  AND cg.id_unidad IN (76, 89)
                GROUP BY cg.id_medicamento, mov.nro_lote
                HAVING SUM(cg.cantidad_fisica) > 0
            ),
            lote_cierre_principal AS (
                SELECT DISTINCT ON (cm.id_medicamento)
                    cm.id_medicamento,
                    cm.nro_lote,
                    cm.cantidad_almacen,
                    cm.cantidad_farmacia,
                    cm.cantidad_total
                FROM cierre_con_movimiento cm
                ORDER BY cm.id_medicamento, cm.max_mov_id DESC NULLS LAST
            ),
            precios_lote AS (
                SELECT DISTINCT ON (lcp.id_medicamento)
                    lcp.id_medicamento,
                    ms.precio_unitario
                FROM lote_cierre_principal lcp
                INNER JOIN farmacia.medicamento_stock ms 
                    ON ms.id_medicamento = lcp.id_medicamento 
                   AND ms.nro_lote = lcp.nro_lote 
                   AND ms.id_unidad IN (89, 76)
                   AND ms.precio_unitario > 0
                ORDER BY lcp.id_medicamento, ms.id_unidad DESC
            ),
            ultimo_precio_stock AS (
                SELECT DISTINCT ON (ms.id_medicamento)
                    ms.id_medicamento,
                    ms.precio_unitario
                FROM farmacia.medicamento_stock ms
                WHERE ms.id_unidad IN (76, 89)
                  AND ms.precio_unitario > 0
                ORDER BY ms.id_medicamento, ms.id DESC
            )
            SELECT 
                lcp.id_medicamento,
                COALESCE(m.codigo_medicamento, 'S/C') AS codigo,
                COALESCE(m.nombre, 'MEDICAMENTO ID: ' || lcp.id_medicamento) AS descripcion,
                lcp.cantidad_almacen,
                lcp.cantidad_farmacia,
                lcp.cantidad_total,
                COALESCE(pl.precio_unitario, ups.precio_unitario, 0) AS precio_unitario,
                (lcp.cantidad_total * COALESCE(pl.precio_unitario, ups.precio_unitario, 0)) AS saldo_final
            FROM lote_cierre_principal lcp
            LEFT JOIN farmacia.medicamento m ON m.id = lcp.id_medicamento
            LEFT JOIN precios_lote pl ON pl.id_medicamento = lcp.id_medicamento
            LEFT JOIN ultimo_precio_stock ups ON ups.id_medicamento = lcp.id_medicamento
        ";

        $query = DB::query()
            ->fromRaw("({$sql}) as reporte", [
                $this->fechaInicio,
                $this->fechaFin
            ])
            ->orderBy('id_medicamento', 'asc');

        // Captura el total de registros para las fórmulas del Excel
        $this->totalRegistros = DB::query()->fromRaw("({$sql}) as rec", [$this->fechaInicio, $this->fechaFin])->count();

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
            isset($row->precio_unitario) ? (float) $row->precio_unitario : 0,
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
                $sheet->setCellValue('C3', 'REPORTE DE SALDOS FINALES - ALMACÉN DE MEDICAMENTOS Y FARMACIA');

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

                $sheet->setCellValue('A6', 'SALDOS FINALES DE ALMACÉN DE MEDICAMENTOS Y FARMACIA');
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
                    $sheet->setCellValue("D{$filaTotal}", "=SUM(D9:DF{$totalFilas})");
                    $sheet->setCellValue("F{$filaTotal}", "=SUM(F9:F{$totalFilas})");

                    $sheet->getStyle("A{$filaTotal}:F{$filaTotal}")->getFont()->setBold(true);
                    $sheet->getStyle("C{$filaTotal}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    $sheet->getStyle("F{$filaTotal}")->getNumberFormat()->setFormatCode('#,##0.00');

                    // Fondo suave para la fila de total general
                    $sheet->getStyle("A{$filaTotal}:F{$filaTotal}")->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setRGB('EBF1F5');
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
