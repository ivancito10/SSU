import { Module } from '@nestjs/common';
import { TypeOrmModule } from '@nestjs/typeorm';
import { DB_CONNECTIONS } from '../../config/database.constants';
import { SoportesController } from './infrastructure/controllers/soportes.controller';
import { CreateSolicitudUseCase } from './application/use-cases/create-solicitud.use-case';
import { SoporteRepositoryPort } from './domain/ports/soporte.repository.port';
import { PostgresSoporteRepository } from './infrastructure/persistence/postgres-soporte.repository';
import { AsignarSoporteUseCase } from './application/use-cases/asignar-soporte.use-case';
import { ReasignarSoporteUseCase } from './application/use-cases/reasignar-soporte.use-case';
import { SoporteAsignacionTypeOrmEntity } from './domain/entities/asignacion.typeorm-entity';
import { SoporteReasignacionTypeOrmEntity } from './domain/entities/reasignacion.typeorm-entity';
import { FinalizarSoporteUseCase } from './application/use-cases/finalizar-soporte.use-case';
import { SoporteArchivoActaTypeOrmEntity } from './domain/entities/acta.typeorm-entity';
import { ListarBandejasUseCase } from './application/use-cases/listar-bandejas.use-case';
import { SolicitudTypeOrmEntity } from './domain/entities/solicitud.typeorm-entity';
import { SoporteFcmTokenTypeOrmEntity } from './domain/entities/fcm-token.typeorm-entity';
import { NotificationServicePort } from './domain/ports/notification.service.port';
import { FcmNotificationService } from './infrastructure/notifications/fcm-notification.service';
import { SolicitudItemTypeOrmEntity } from './domain/entities/solicitud-item.typeorm-entity';
import { AuthModule } from '../auth/auth.module';
import { ExcelReportService } from './infrastructure/reports/excel-report.service';

@Module({
    imports: [
        AuthModule,
        TypeOrmModule.forFeature(
        [
        SoporteAsignacionTypeOrmEntity,
        SoporteReasignacionTypeOrmEntity,
        SolicitudTypeOrmEntity,
        SolicitudItemTypeOrmEntity,
        SoporteAsignacionTypeOrmEntity,
        SoporteArchivoActaTypeOrmEntity,
        SoporteFcmTokenTypeOrmEntity
        ], DB_CONNECTIONS.SIGHOV)
    ],
    controllers: [SoportesController],
    providers: [
        CreateSolicitudUseCase,
        AsignarSoporteUseCase,
        ReasignarSoporteUseCase,
        FinalizarSoporteUseCase,
        ListarBandejasUseCase,
        ExcelReportService,
        {
            provide: 'SoporteRepositoryPort',
            useClass: PostgresSoporteRepository,
            
        },
        {
            provide: 'NotificationServicePort',
            useClass: FcmNotificationService,
        },
        CreateSolicitudUseCase,
        AsignarSoporteUseCase,
        ReasignarSoporteUseCase,
        FinalizarSoporteUseCase,
        ListarBandejasUseCase,
    ],
    //exports: [SoporteRepositoryPort],
})
export class SoportesModule { }