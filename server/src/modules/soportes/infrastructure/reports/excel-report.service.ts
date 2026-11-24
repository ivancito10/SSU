import { Injectable } from '@nestjs/common';
import * as ExcelJS from 'exceljs';

export interface FilaReporteSoporte {
  codigo_ticket?: string | null;
  id_solicitud?: number;
  estado?: string | null;
  prioridad?: string | null;
  es_manual?: boolean;
  oficina?: string | null;
  consultorio?: string | null;
  piso?: string | null;
  solicitante_manual?: string | null;
  nombre_usuario?: string | null;
  username?: string | null;
  nombre_tecnico?: string | null;
  fecha_asignacion?: string | Date | null;
  fecha_inicio?: string | Date | null;
  fecha_finalizacion?: string | Date | null;
  segundos_hasta_asignacion?: number | null;
  segundos_desde_asignacion?: number | null;
  descripcion_problema?: string | null;
  diagnostico?: string | null;
}

export interface OpcionesReporteExcel {
  rol: 'OPERADOR' | 'TECNICO';
  generadoPor: string;
  etiquetaFiltro?: string;
  fechaInicio?: string;
  fechaFin?: string;
}

const formatearFechaHora = (valor?: string | Date | null): string => {
  if (!valor) return 'No registrada';
  const fecha = valor instanceof Date ? valor : new Date(valor);
  if (Number.isNaN(fecha.getTime())) return 'No registrada';
  const f = fecha.toLocaleDateString('es-BO', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
  });
  const h = fecha.toLocaleTimeString('es-BO', {
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit',
  });
  return `${f} ${h}`;
};

const formatearDuracion = (segundos?: number | null): string => {
  if (segundos === undefined || segundos === null || !Number.isFinite(Number(segundos))) {
    return 'No registrado';
  }
  const total = Math.max(0, Math.floor(Number(segundos)));
  const horas = Math.floor(total / 3600);
  const minutos = Math.floor((total % 3600) / 60);
  const segs = total % 60;
  if (horas > 0) return `${horas}h ${minutos}m ${segs}s`;
  return `${minutos}m ${segs}s`;
};

const normalizarPrioridadTexto = (prioridad?: string | null): string => {
  const p = String(prioridad || '').toUpperCase();
  if (p === 'URGENTE' || p === 'ALTA') return 'ALTA';
  if (p === 'NORMAL' || p === 'MEDIA') return 'MEDIA';
  if (p === 'BAJA') return 'BAJA';
  return p || 'MEDIA';
};

@Injectable()
export class ExcelReportService {
  async generarReporteSoportesConcluidos(
    filas: FilaReporteSoporte[],
    opciones: OpcionesReporteExcel,
  ): Promise<Buffer> {
    const workbook = new ExcelJS.Workbook();
    workbook.creator = 'SIGHO - SSU La Paz';
    workbook.created = new Date();

    const sheet = workbook.addWorksheet('Soportes Concluidos', {
      views: [{ state: 'frozen', ySplit: 4 }],
    });

    const totalColumnas = 14;
    const ultimaColumnaLetra = 'N';

    // 1. Título superior institucional
    sheet.mergeCells(`A1:${ultimaColumnaLetra}1`);
    const celdaTitulo = sheet.getCell('A1');
    celdaTitulo.value = 'REPORTE DE SOPORTES CONCLUIDOS - SSU LA PAZ';
    celdaTitulo.font = { name: 'Calibri', size: 15, bold: true, color: { argb: 'FFFFFFFF' } };
    celdaTitulo.alignment = { vertical: 'middle', horizontal: 'center' };
    celdaTitulo.fill = {
      type: 'pattern',
      pattern: 'solid',
      fgColor: { argb: 'FF0B3D78' },
    };
    sheet.getRow(1).height = 30;

    // 2. Subtítulo con rol, generador y filtro aplicado
    const rangoTexto =
      opciones.etiquetaFiltro ||
      (opciones.fechaInicio && opciones.fechaFin
        ? opciones.fechaInicio === opciones.fechaFin
          ? `Fecha: ${opciones.fechaInicio}`
          : `Del ${opciones.fechaInicio} al ${opciones.fechaFin}`
        : 'Todas las fechas');
    const fechaEmision = formatearFechaHora(new Date());

    sheet.mergeCells(`A2:${ultimaColumnaLetra}2`);
    const celdaSubtitulo = sheet.getCell('A2');
    celdaSubtitulo.value = `Rol: ${opciones.rol}   |   Generado por: ${opciones.generadoPor || 'Usuario del sistema'}   |   Filtro: ${rangoTexto}   |   Emitido: ${fechaEmision}`;
    celdaSubtitulo.font = { name: 'Calibri', size: 10.5, italic: true, color: { argb: 'FF1E3A5F' } };
    celdaSubtitulo.alignment = { vertical: 'middle', horizontal: 'center' };
    celdaSubtitulo.fill = {
      type: 'pattern',
      pattern: 'solid',
      fgColor: { argb: 'FFE8F0FE' },
    };
    sheet.getRow(2).height = 22;

    // Fila 3 vacía de separación
    sheet.getRow(3).height = 8;

    // 3. Encabezados de tabla (Fila 4)
    const encabezados = [
      'Código Ticket',
      'Estado',
      'Prioridad',
      'Origen',
      'Área / Ubicación',
      'Sede / Piso',
      'Solicitante',
      'Técnico Asignado',
      'Fecha/Hora Asignado',
      'Fecha/Hora En Proceso',
      'Fecha/Hora Finalizado',
      'Tiempo sin asignar',
      'Tiempo total desde asignación',
      'Descripción / Observación',
    ];

    const headerRow = sheet.getRow(4);
    headerRow.values = encabezados;
    headerRow.height = 26;
    headerRow.eachCell((cell) => {
      cell.font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FFFFFFFF' } };
      cell.alignment = { vertical: 'middle', horizontal: 'center', wrapText: true };
      cell.fill = {
        type: 'pattern',
        pattern: 'solid',
        fgColor: { argb: 'FF0B3D78' },
      };
      cell.border = {
        top: { style: 'thin', color: { argb: 'FF0B3D78' } },
        bottom: { style: 'medium', color: { argb: 'FF06254A' } },
        left: { style: 'thin', color: { argb: 'FF2E5C9A' } },
        right: { style: 'thin', color: { argb: 'FF2E5C9A' } },
      };
    });

    // Activar AutoFilter sobre los encabezados
    sheet.autoFilter = {
      from: { row: 4, column: 1 },
      to: { row: 4, column: totalColumnas },
    };

    // 4. Filas de datos con rayado cebra
    const anchosMaximos = encabezados.map((h) => h.length + 4);

    filas.forEach((f, idx) => {
      const codigo = f.codigo_ticket || (f.id_solicitud ? `#${f.id_solicitud}` : 'N/A');
      const areaUbicacion = [f.oficina, f.consultorio].filter(Boolean).join(' — ') || 'No registrada';
      const solicitante = f.es_manual
        ? (f.solicitante_manual || 'Solicitud verbal')
        : (f.nombre_usuario || f.username || 'No registrado');
      const partesObs = [
        f.descripcion_problema ? `Problema: ${f.descripcion_problema}` : null,
        f.diagnostico ? `Obs.: ${f.diagnostico}` : null,
      ].filter(Boolean);

      const valoresFila = [
        codigo,
        f.estado || 'FINALIZADA',
        normalizarPrioridadTexto(f.prioridad),
        f.es_manual ? 'MANUAL' : 'APP',
        areaUbicacion,
        f.piso || 'No especificado',
        solicitante,
        f.nombre_tecnico || 'Sin asignar',
        formatearFechaHora(f.fecha_asignacion),
        formatearFechaHora(f.fecha_inicio),
        formatearFechaHora(f.fecha_finalizacion),
        formatearDuracion(f.segundos_hasta_asignacion),
        formatearDuracion(f.segundos_desde_asignacion),
        partesObs.join(' | ') || 'Sin observación',
      ];

      const row = sheet.addRow(valoresFila);
      const esPar = idx % 2 === 1;

      row.eachCell((cell, colNumber) => {
        const texto = String(cell.value ?? '');
        anchosMaximos[colNumber - 1] = Math.max(anchosMaximos[colNumber - 1], Math.min(texto.length + 3, 48));

        cell.font = { name: 'Calibri', size: 10.5, color: { argb: 'FF1E293B' } };
        cell.alignment = {
          vertical: 'middle',
          horizontal: [1, 2, 3, 4, 9, 10, 11, 12, 13].includes(colNumber) ? 'center' : 'left',
          wrapText: colNumber === 14 || colNumber === 5,
        };
        if (esPar) {
          cell.fill = {
            type: 'pattern',
            pattern: 'solid',
            fgColor: { argb: 'FFF4F7FB' },
          };
        }
        cell.border = {
          top: { style: 'thin', color: { argb: 'FFD6E0EC' } },
          bottom: { style: 'thin', color: { argb: 'FFD6E0EC' } },
          left: { style: 'thin', color: { argb: 'FFE2E8F0' } },
          right: { style: 'thin', color: { argb: 'FFE2E8F0' } },
        };
      });
    });

    // 5. Fila de resumen / total al final
    const totalRowIndex = sheet.rowCount + 1;
    sheet.mergeCells(`A${totalRowIndex}:M${totalRowIndex}`);
    const totalRow = sheet.getRow(totalRowIndex);
    totalRow.height = 24;
    sheet.getCell(`A${totalRowIndex}`).value = 'TOTAL DE SOPORTES CONCLUIDOS';
    sheet.getCell(`A${totalRowIndex}`).alignment = { vertical: 'middle', horizontal: 'right' };
    sheet.getCell(`N${totalRowIndex}`).value = `${filas.length} ${filas.length === 1 ? 'soporte' : 'soportes'}`;
    sheet.getCell(`N${totalRowIndex}`).alignment = { vertical: 'middle', horizontal: 'center' };

    for (let c = 1; c <= totalColumnas; c++) {
      const cell = totalRow.getCell(c);
      cell.font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FF0B3D78' } };
      cell.fill = {
        type: 'pattern',
        pattern: 'solid',
        fgColor: { argb: 'FFE8F0FE' },
      };
      cell.border = {
        top: { style: 'medium', color: { argb: 'FF0B3D78' } },
        bottom: { style: 'medium', color: { argb: 'FF0B3D78' } },
        left: { style: 'thin', color: { argb: 'FFBFDBFE' } },
        right: { style: 'thin', color: { argb: 'FFBFDBFE' } },
      };
    }

    // 6. Ajuste automático de ancho de columnas
    anchosMaximos.forEach((ancho, i) => {
      const col = sheet.getColumn(i + 1);
      const minAncho = i === 13 ? 34 : 13;
      const maxAncho = i === 13 ? 52 : 34;
      col.width = Math.max(minAncho, Math.min(ancho, maxAncho));
    });

    const arrayBuffer = await workbook.xlsx.writeBuffer();
    return Buffer.from(arrayBuffer);
  }
}
