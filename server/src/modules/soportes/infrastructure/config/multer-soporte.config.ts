import { diskStorage } from 'multer';
import { extname } from 'path';
import { existsSync, mkdirSync } from 'fs';
import { BadRequestException } from '@nestjs/common';

export const multerSoporteConfig = {
  storage: diskStorage({
    // 1. Destino: Crea la carpeta si no existe
    destination: (req, file, cb) => {
      const uploadPath = './uploads/soportes/actas';
      if (!existsSync(uploadPath)) {
        mkdirSync(uploadPath, { recursive: true });
      }
      cb(null, uploadPath);
    },
    // 2. Renombrar: Agrega la fecha actual para evitar archivos duplicados
    filename: (req, file, cb) => {
      const uniqueSuffix = Date.now() + '-' + Math.round(Math.random() * 1e9);
      const ext = extname(file.originalname);
      cb(null, `acta-${uniqueSuffix}${ext}`);
    },
  }),
  // 3. Filtro de seguridad: Solo permitir imágenes y PDFs
  fileFilter: (req: any, file: any, cb: any) => {
    if (file.mimetype.match(/\/(jpg|jpeg|png|pdf)$/)) {
      cb(null, true);
    } else {
      cb(new BadRequestException('Formato no soportado. Sube una imagen (JPG/PNG) o un PDF.'), false);
    }
  },
  limits: {
    fileSize: 5 * 1024 * 1024, // Límite de 5 MB por archivo
  },
};