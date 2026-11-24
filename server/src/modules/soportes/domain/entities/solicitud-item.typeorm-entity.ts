import { Entity, PrimaryGeneratedColumn, Column, CreateDateColumn } from 'typeorm';

@Entity({ schema: 'administracion', name: 'soporte_solicitud_items' })
export class SolicitudItemTypeOrmEntity {
    @PrimaryGeneratedColumn()
    id: number;

    @Column({ name: 'id_solicitud', type: 'integer' })
    idSolicitud: number;

    @Column({ name: 'id_tipo_soporte', type: 'integer' })
    idTipoSoporte: number;

    // IMPORTANTE: Asegúrate de que el tipo sea string | null
    @Column({ type: 'text', nullable: true })
    descripcion: string | null;

    @CreateDateColumn({ name: 'created_at' })
    createdAt: Date;
}