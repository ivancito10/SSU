import { Module } from '@nestjs/common';
import { UsuarioAPIsPort } from './domain/usuarioAPIs';
import { ConsultarUsuariosAPIsUseCase } from './application/consultar-usuariosAPIs.use-case';
import { UsuariosAPIsController } from './infrastructure/usuariosAPIs.controller';
import { PostgresUsuariosAPIsRepository } from './infrastructure/postgres-usuariosAPIs.repository';

@Module({
    controllers: [UsuariosAPIsController],
    providers: [
        { provide: UsuarioAPIsPort, useClass: PostgresUsuariosAPIsRepository },
        {
            provide: ConsultarUsuariosAPIsUseCase,
            inject: [UsuarioAPIsPort],
            useFactory: (repo: UsuarioAPIsPort) =>
                new ConsultarUsuariosAPIsUseCase(repo),
        },
    ],
})
export class UsuariosAPIsModule {}
