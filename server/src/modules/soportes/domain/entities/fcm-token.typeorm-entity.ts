import { Entity, PrimaryGeneratedColumn, Column, CreateDateColumn } from 'typeorm';

@Entity({ schema: 'administracion', name: 'soporte_fcm_tokens' })
export class SoporteFcmTokenTypeOrmEntity {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'id_usuario', type: 'bigint' })
  idUsuario: number;

  @Column({ name: 'token_dispositivo', type: 'varchar', length: 255 })
  tokenDispositivo: string;

  @Column({ type: 'varchar', length: 50, nullable: true })
  plataforma: string | null;

  @CreateDateColumn({ name: 'created_at' })
  createdAt: Date;
}