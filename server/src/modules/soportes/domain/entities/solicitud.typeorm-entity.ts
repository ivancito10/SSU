import { Entity, PrimaryGeneratedColumn, Column, CreateDateColumn, UpdateDateColumn } from 'typeorm';

@Entity({ schema: 'administracion', name: 'soporte_solicitudes' })
export class SolicitudTypeOrmEntity {
    @PrimaryGeneratedColumn()
    id: number;

    @Column({ name: 'codigo_ticket', type: 'varchar', length: 24, unique: true })
    codigoTicket: string;

    @Column({ name: 'id_usuario', type: 'bigint' })
    idUsuario: number;

    @Column({ type: 'varchar', length: 100, nullable: true })
    piso: string | null;

    @Column({ type: 'varchar', length: 100, nullable: true })
    oficina: string | null;

    @Column({ type: 'varchar', length: 100, nullable: true })
    consultorio: string | null;

    @Column({ default: 'PENDIENTE' })
    estado: string;

    @Column({ name: 'es_manual', type: 'boolean', default: false })
    esManual: boolean;

    @Column({ name: 'solicitante_manual', type: 'varchar', length: 180, nullable: true })
    solicitanteManual: string | null;

    @CreateDateColumn({ name: 'created_at' })
    createdAt: Date;

    @UpdateDateColumn({ name: 'updated_at' })
    updatedAt: Date;
}
