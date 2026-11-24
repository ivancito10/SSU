import { NestFactory } from '@nestjs/core';
import { AppModule, ObserveInstrument } from './app.module';
import { configurarSwagger } from './config/swagger';
import { ValidationPipe } from '@nestjs/common';

async function bootstrap() {
    // const app = await NestFactory.create(AppModule, {
    //     instrument: ObserveInstrument,
    // });

    const app = await NestFactory.create(AppModule);

    // --- AÑADE ESTE BLOQUE PARA PERMITIR PETICIONES WEB ---
    app.enableCors({
        origin: '*', // Permite que la app web y móvil se conecten
        methods: 'GET,HEAD,PUT,PATCH,POST,DELETE',
        credentials: true,
    });
    // ------------------------------------------------------

    app.setGlobalPrefix('api');

    app.useGlobalPipes(new ValidationPipe({ whitelist: true, transform: true }));
    configurarSwagger(app);

    await app.listen(process.env.PORT ?? 3000);
}
void bootstrap();