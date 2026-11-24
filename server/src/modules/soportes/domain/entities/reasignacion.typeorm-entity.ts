import { Entity, PrimaryGeneratedColumn, Column, CreateDateColumn } from 'typeorm';

@Entity({ schema: 'administracion', name: 'soporte_reasignaciones' })
export class SoporteReasignacionTypeOrmEntity {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'id_asignacion', type: 'integer' })
  idAsignacion: number;

  @Column({ name: 'id_de_soporte', type: 'bigint' })
  idDeSoporte: number;

  @Column({ name: 'id_a_soporte', type: 'bigint' })
  idASoporte: number;

  @Column({ type: 'text' })
  justificacion: string;

  @Column({ name: 'rol_derivacion', type: 'varchar', length: 20, nullable: true })
  rolDerivacion: string | null;

  @CreateDateColumn({ name: 'fecha' })
  fecha: Date;
}
