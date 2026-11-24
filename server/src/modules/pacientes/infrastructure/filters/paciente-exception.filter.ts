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
export class PacienteExceptionFilter implements ExceptionFilter {
  private readonly logger = new Logger('PacientesModule');

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
        case HttpStatus.BAD_REQUEST:
          code = 'BAD_REQUEST'; // 400
          break;
        case HttpStatus.UNAUTHORIZED:
          code = 'UNAUTHORIZED'; // 401
          break;
        case HttpStatus.FORBIDDEN:
          code = 'FORBIDDEN'; // 403
          break;
        case HttpStatus.NOT_FOUND:
          code = 'NOT_FOUND'; // 404
          break;
        default:
          code = 'HTTP_EXCEPTION';
      }
    } else if (exception instanceof QueryFailedError) {
      status = HttpStatus.INTERNAL_SERVER_ERROR; // 500 para fallos de BD
      code = 'DATABASE_ERROR';
      message = 'Error en la base de datos al consultar pacientes';

      this.logger.error(`[DB Error]: ${exception.message}`, exception.stack);
    } else if (exception instanceof Error) {
      status = HttpStatus.INTERNAL_SERVER_ERROR;
      message = exception.message;
      this.logger.error(`[Unhandled Error]: ${exception.message}`, exception.stack);
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