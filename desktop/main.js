const { app, BrowserWindow, dialog, shell } = require('electron');
const http = require('http');
const fs = require('fs');
const path = require('path');

const WEB_DIST_DIR = path.join(__dirname, 'web-dist');

const MIME_TYPES = {
  '.html': 'text/html; charset=utf-8',
  '.js': 'application/javascript; charset=utf-8',
  '.mjs': 'application/javascript; charset=utf-8',
  '.css': 'text/css; charset=utf-8',
  '.png': 'image/png',
  '.jpg': 'image/jpeg',
  '.jpeg': 'image/jpeg',
  '.gif': 'image/gif',
  '.webp': 'image/webp',
  '.svg': 'image/svg+xml',
  '.ico': 'image/x-icon',
  '.json': 'application/json; charset=utf-8',
  '.ttf': 'font/ttf',
  '.woff': 'font/woff',
  '.woff2': 'font/woff2',
  '.map': 'application/json; charset=utf-8',
};

let mainWindow = null;
let staticServer = null;
let downloadHandlerRegistrado = false;

// Evitar múltiples instancias simultáneas de la aplicación
const gotTheLock = app.requestSingleInstanceLock();
if (!gotTheLock) {
  app.quit();
} else {
  app.on('second-instance', () => {
    if (mainWindow) {
      if (mainWindow.isMinimized()) mainWindow.restore();
      mainWindow.focus();
    }
  });
}

function iniciarServidorEstatico() {
  return new Promise((resolve, reject) => {
    const server = http.createServer((req, res) => {
      try {
        const rawUrl = (req.url || '/').split('?')[0];
        let decodedPath;
        try {
          decodedPath = decodeURIComponent(rawUrl);
        } catch {
          decodedPath = '/';
        }

        const relPath = decodedPath === '/' ? 'index.html' : decodedPath.replace(/^\/+/, '');
        let filePath = path.resolve(WEB_DIST_DIR, relPath);

        // Protección contra path traversal fuera de web-dist
        if (!filePath.startsWith(WEB_DIST_DIR)) {
          res.writeHead(403, { 'Content-Type': 'text/plain; charset=utf-8' });
          res.end('Acceso denegado');
          return;
        }

        // Si es directorio o no existe, fallback SPA a index.html
        if (!fs.existsSync(filePath) || fs.statSync(filePath).isDirectory()) {
          filePath = path.join(WEB_DIST_DIR, 'index.html');
        }

        if (!fs.existsSync(filePath)) {
          res.writeHead(404, { 'Content-Type': 'text/plain; charset=utf-8' });
          res.end('No se encontró web-dist/index.html. Ejecuta npm run prepare:web.');
          return;
        }

        const ext = path.extname(filePath).toLowerCase();
        const contentType = MIME_TYPES[ext] || 'application/octet-stream';
        res.writeHead(200, { 'Content-Type': contentType });
        fs.createReadStream(filePath).pipe(res);
      } catch (error) {
        res.writeHead(500, { 'Content-Type': 'text/plain; charset=utf-8' });
        res.end(`Error interno del servidor local: ${error.message}`);
      }
    });

    server.on('error', reject);
    server.listen(0, '127.0.0.1', () => {
      const address = server.address();
      if (!address || typeof address === 'string') {
        reject(new Error('No se pudo obtener el puerto del servidor local.'));
        return;
      }
      staticServer = server;
      resolve(address.port);
    });
  });
}

function cerrarServidorEstatico() {
  if (staticServer) {
    try {
      staticServer.close();
    } catch {
      // ignorar si ya estaba cerrado
    }
    staticServer = null;
  }
}

async function crearVentanaPrincipal() {
  const port = await iniciarServidorEstatico();
  const appUrl = `http://127.0.0.1:${port}`;

  mainWindow = new BrowserWindow({
    width: 1280,
    height: 800,
    minWidth: 900,
    minHeight: 600,
    title: 'Soporte SSU La Paz',
    icon: path.join(__dirname, 'assets', 'icon.png'),
    autoHideMenuBar: true,
    webPreferences: {
      contextIsolation: true,
      nodeIntegration: false,
    },
  });

  // Registrar una sola vez el manejador de descargas con diálogo "Guardar como"
  if (!downloadHandlerRegistrado) {
    downloadHandlerRegistrado = true;
    mainWindow.webContents.session.on('will-download', (_event, item) => {
      const filename = item.getFilename() || 'reporte.xlsx';
      const ext = path.extname(filename).replace('.', '').toLowerCase();
      const defaultPath = path.join(app.getPath('downloads'), filename);
      const esExcel = ext === 'xlsx';

      const savePath = dialog.showSaveDialogSync(mainWindow || undefined, {
        title: esExcel ? 'Guardar reporte Excel' : 'Guardar archivo',
        defaultPath,
        filters: esExcel
          ? [{ name: 'Excel', extensions: ['xlsx'] }]
          : [
              { name: 'Archivo', extensions: ext ? [ext] : ['*'] },
              { name: 'Todos los archivos', extensions: ['*'] },
            ],
      });

      if (!savePath) {
        item.cancel();
        return;
      }

      item.setSavePath(savePath);
      item.once('done', (_e, state) => {
        if (state === 'completed') {
          shell.showItemInFolder(savePath);
        }
      });
    });
  }

  // Interceptar descargas de reportes/archivos y enlaces externos
  mainWindow.webContents.setWindowOpenHandler(({ url }) => {
    if (url.includes('/reporte-excel/descargar') || url.includes('/uploads/')) {
      mainWindow.webContents.downloadURL(url);
      return { action: 'deny' };
    }
    if (url.startsWith('http://127.0.0.1:') || url.startsWith('blob:')) {
      return { action: 'allow' };
    }
    if (url.startsWith('http://') || url.startsWith('https://')) {
      void shell.openExternal(url);
    }
    return { action: 'deny' };
  });

  // Atajo para abrir/cerrar DevTools con F12 o Ctrl+Shift+I
  mainWindow.webContents.on('before-input-event', (event, input) => {
    if (input.type !== 'keyDown') return;
    const esF12 = input.key === 'F12';
    const esCtrlShiftI =
      (input.control || input.meta) &&
      input.shift &&
      String(input.key || '').toLowerCase() === 'i';
    if (esF12 || esCtrlShiftI) {
      event.preventDefault();
      if (mainWindow.webContents.isDevToolsOpened()) {
        mainWindow.webContents.closeDevTools();
      } else {
        mainWindow.webContents.openDevTools({ mode: 'detach' });
      }
    }
  });

  mainWindow.on('closed', () => {
    mainWindow = null;
  });

  await mainWindow.loadURL(appUrl);
}

app.whenReady().then(() => {
  if (!gotTheLock) return;
  crearVentanaPrincipal().catch((error) => {
    console.error('Error al iniciar Soporte SSU La Paz:', error);
    app.quit();
  });
});

app.on('window-all-closed', () => {
  cerrarServidorEstatico();
  app.quit();
});

app.on('before-quit', () => {
  cerrarServidorEstatico();
});
