import { Module } from '@nestjs/common';
import { UsuarioPort } from './domain/usuario';
import { ConsultarUsuariosUseCase } from './application/consultar-usuarios.use-case';
import { UsuariosController } from './infrastructure/usuarios.controller';
import { PostgresUsuariosRepository } from './infrastructure/postgres-usuarios.repository';

@Module({
    controllers: [UsuariosController],
    providers: [
        { provide: UsuarioPort, useClass: PostgresUsuariosRepository },
        {
            provide: ConsultarUsuariosUseCase,
            inject: [UsuarioPort],
            useFactory: (repo: UsuarioPort) =>
                new ConsultarUsuariosUseCase(repo),
        },
    ],
})
export class UsuariosModule {}
