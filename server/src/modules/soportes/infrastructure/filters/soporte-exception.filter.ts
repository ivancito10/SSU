import {
    ExceptionFilter,
    Catch,
    ArgumentsHost,
    HttpException,
    HttpStatus,
    Logger,
} from '@nestjs/common';
import { Response, Request } from 'express';
import { QueryFailedError } from 'typeorm';

@Catch()
export class SoporteExceptionFilter implements ExceptionFilter {
    private readonly logger = new Logger('SoportesModule');

    catch(exception: unknown, host: ArgumentsHost) {
        const ctx = host.switchToHttp();
        const response = ctx.getResponse<Response>();
        const request = ctx.getRequest<Request>();

        let status = HttpStatus.INTERNAL_SERVER_ERROR;
        let message: string | object = 'Error interno del servidor';
        let code = 'INTERNAL_SERVER_ERROR';

        if (exception instanceof HttpException) {
            status = exception.getStatus();
            const res = exception.getResponse();
            message = typeof res === 'object' && (res as any).message ? (res as any).message : res;

            switch (status) {
                case HttpStatus.BAD_REQUEST: code = 'BAD_REQUEST'; break;
                case HttpStatus.UNAUTHORIZED: code = 'UNAUTHORIZED'; break;
                case HttpStatus.FORBIDDEN: code = 'FORBIDDEN'; break;
                case HttpStatus.NOT_FOUND: code = 'NOT_FOUND'; break;
                default: code = 'HTTP_EXCEPTION';
            }
        } else if (exception instanceof QueryFailedError) {
            status = HttpStatus.INTERNAL_SERVER_ERROR;
            code = 'DATABASE_ERROR';
            message = 'Error en la base de datos al guardar/consultar soporte';
            this.logger.error(`[DB Error]: ${exception.message}`, exception.stack);
        } else if (exception instanceof Error) {
            this.logger.error(`[Unhandled Error]: ${exception.message}`, exception.stack);
            message = exception.message;
        }

        response.status(status).json({
            statusCode: status,
            error: code,
            message,
            path: request.url,
            timestamp: new Date().toISOString(),
        });
    }
}