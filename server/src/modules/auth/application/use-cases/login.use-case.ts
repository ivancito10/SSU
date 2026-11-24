import { Injectable, UnauthorizedException } from '@nestjs/common';
import { JwtService } from '@nestjs/jwt';
import * as bcrypt from 'bcrypt';
import { UserAuthRepositoryPort } from '../../domain/ports/user-auth.repository.port';
import { LoginDto } from '../dtos/login.dto';
import { DataSource } from 'typeorm'; // <-- Importamos DataSource
import { InjectDataSource } from '@nestjs/typeorm';

@Injectable()
export class LoginUseCase {
    constructor(
        private readonly userRepo: UserAuthRepositoryPort,
        private readonly jwtService: JwtService,
        @InjectDataSource('sighov') private readonly dataSource: DataSource, // <-- AÑADIR DECORADOR AQUÍ
    ) { }

    async ejecutar(dto: LoginDto, plataforma?: string) {
        const user = await this.userRepo.buscarPorCredencial(dto.username);

        if (!user || !user.estaActivo()) {
            throw new UnauthorizedException('Credenciales inválidas o usuario inactivo');
        }

        const match = await bcrypt.compare(dto.password, user.passwordHash);
        if (!match) {
            throw new UnauthorizedException('Credenciales inválidas');
        }

        await this.userRepo.actualizarUltimoLogin(user.id);

        // --- INICIO CÓDIGO DTIC (No afecta a Fisioterapia/Enfermería) ---
        let rolSoporte = 'SIN_ROL';
        let rolesSoporte: string[] = [];
        try {
            const result = await this.dataSource.query(
                `SELECT rol_soporte FROM administracion.soportes_usuarios_roles WHERE id_usuario = $1 AND estado = true`,
                [user.id]
            );
            if (result && result.length > 0) {
                const rolesExtraidos: string[] = Array.from(
                    new Set<string>(
                        result
                            .map((r: any) => String(r.rol_soporte || '').trim().toUpperCase())
                            .filter((rol: string) => rol.length > 0)
                    )
                );
                if (rolesExtraidos.length > 0) {
                    rolesSoporte = rolesExtraidos;
                    // Prioridad de entrada por defecto si el usuario tiene varios roles activos
                    if (rolesExtraidos.includes('OPERADOR')) {
                        rolSoporte = 'OPERADOR';
                    } else if (rolesExtraidos.includes('ADMINISTRADOR')) {
                        rolSoporte = 'ADMINISTRADOR';
                    } else if (rolesExtraidos.includes('TECNICO')) {
                        rolSoporte = 'TECNICO';
                    } else if (rolesExtraidos.includes('USUARIO')) {
                        rolSoporte = 'USUARIO';
                    } else {
                        rolSoporte = rolesExtraidos[0];
                    }
                }
            }
        } catch (error) {
            // Si la tabla no existe o hay error, lo ignoramos silenciosamente
            console.log(`Log Soporte: Usuario ${user.id} sin rol específico.`);
        }
        // --- FIN CÓDIGO DTIC ---

        const payload = {
            sub: user.id,
            username: user.username,
            idPersona: user.idPersona,
            nombreCompleto: user.nombreCompleto,
            idEspecialista: user.idEspecialista,
            rolSoporte: rolSoporte,
            rolesSoporte: rolesSoporte,
        };

        const plataformaCliente = (plataforma ?? dto.plataforma)?.trim().toLowerCase();
        const expiresIn = plataformaCliente === 'mobile' ? '30d' : '8h';

        return {
            accessToken: this.jwtService.sign(payload, { expiresIn }),
            tokenType: 'Bearer',
            usuario: {
                id: user.id,
                username: user.username,
                id_persona: user.idPersona,
                nombreCompleto: user.nombreCompleto,
                idEspecialista: user.idEspecialista,
                rol: rolSoporte,
                roles: rolesSoporte,
            },
        };
    }
}