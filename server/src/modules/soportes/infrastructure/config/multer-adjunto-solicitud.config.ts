import { diskStorage } from 'multer';
import { extname } from 'path';
import { existsSync, mkdirSync } from 'fs';
import { BadRequestException } from '@nestjs/common';

export const multerAdjuntoSolicitudConfig = {
  storage: diskStorage({
    destination: (_req, _file, cb) => {
      const uploadPath = './uploads/soportes/solicitudes';
      if (!existsSync(uploadPath)) mkdirSync(uploadPath, { recursive: true });
      cb(null, uploadPath);
    },
    filename: (_req, file, cb) => {
      const uniqueSuffix = Date.now() + '-' + Math.round(Math.random() * 1e9);
      cb(null, `problema-${uniqueSuffix}${extname(file.originalname)}`);
    },
  }),
  fileFilter: (_req: any, file: any, cb: any) => {
    if (/^image\/(jpeg|png|webp|heic|heif)$/i.test(file.mimetype)) cb(null, true);
    else cb(new BadRequestException('Adjunta una imagen JPG, PNG o WebP.'), false);
  },
  limits: { fileSize: 5 * 1024 * 1024 },
};
