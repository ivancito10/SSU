import { Entity, PrimaryGeneratedColumn, Column, CreateDateColumn } from 'typeorm';

@Entity({ schema: 'administracion', name: 'soporte_archivos_actas' })
export class SoporteArchivoActaTypeOrmEntity {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'id_asignacion', type: 'integer' })
  idAsignacion: number;

  @Column({ name: 'nombre_archivo', type: 'varchar', length: 255 })
  nombreArchivo: string;

  @Column({ name: 'ruta_url', type: 'text' })
  rutaUrl: string;

  @Column({ name: 'tipo_mime', type: 'varchar', length: 100, nullable: true })
  tipoMime: string | null;

  @CreateDateColumn({ name: 'created_at' })
  createdAt: Date;
}