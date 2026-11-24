import { Entity, PrimaryGeneratedColumn, Column, CreateDateColumn } from 'typeorm';

@Entity({ schema: 'administracion', name: 'soporte_asignaciones' })
export class SoporteAsignacionTypeOrmEntity {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'id_solicitud_item', type: 'integer' })
  idSolicitudItem: number;

  @Column({ name: 'id_soporte', type: 'bigint' })
  idSoporte: number;

  @Column({ name: 'id_asignado_por', type: 'bigint' })
  idAsignadoPor: number;

  @CreateDateColumn({ name: 'fecha_asignacion' })
  fechaAsignacion: Date;

  @Column({ name: 'fecha_inicio', type: 'timestamp', nullable: true })
  fechaInicio: Date | null;

  @Column({ name: 'fecha_fin', type: 'timestamp', nullable: true })
  fechaFin: Date | null;

  @Column({ type: 'varchar', length: 50, default: 'ASIGNADA' })
  estado: string;

  @Column({ type: 'text', nullable: true })
  observacion: string | null;

  @Column({ name: 'instrucciones_tecnico', type: 'text', nullable: true })
  instruccionesTecnico: string | null;

  @Column({ type: 'varchar', length: 30, nullable: true, default: 'MEDIA' })
  prioridad: string | null;

  @Column({ name: 'fecha_en_camino', type: 'timestamp', nullable: true })
  fechaEnCamino: Date | null;

  @Column({ name: 'latitud_en_camino', type: 'double precision', nullable: true })
  latitudEnCamino: number | null;

  @Column({ name: 'longitud_en_camino', type: 'double precision', nullable: true })
  longitudEnCamino: number | null;

  @Column({ name: 'fecha_llegada', type: 'timestamp', nullable: true })
  fechaLlegada: Date | null;

  @Column({ name: 'latitud_llegada', type: 'double precision', nullable: true })
  latitudLlegada: number | null;

  @Column({ name: 'longitud_llegada', type: 'double precision', nullable: true })
  longitudLlegada: number | null;
}
