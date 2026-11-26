const fs = require('fs');
const path = require('path');

const desktopRoot = path.resolve(__dirname, '..');
const repoRoot = path.resolve(desktopRoot, '..');

const sourceWebDist = path.join(repoRoot, 'sigho-app', 'dist');
const destWebDist = path.join(desktopRoot, 'web-dist');

const sourceIcon = path.join(repoRoot, 'sigho-app', 'assets', 'ssu-icon-square.png');
const destAssetsDir = path.join(desktopRoot, 'assets');
const destIcon = path.join(destAssetsDir, 'icon.png');

function validarPngMinimo256(filePath) {
  const buf = fs.readFileSync(filePath);
  // Firma PNG estándar: 89 50 4E 47 0D 0A 1A 0A
  const esPng =
    buf.length >= 24 &&
    buf[0] === 0x89 &&
    buf[1] === 0x50 &&
    buf[2] === 0x4e &&
    buf[3] === 0x47 &&
    buf[4] === 0x0d &&
    buf[5] === 0x0a &&
    buf[6] === 0x1a &&
    buf[7] === 0x0a;

  if (!esPng) {
    throw new Error(`El archivo ${filePath} no es un PNG válido.`);
  }

  const width = buf.readUInt32BE(16);
  const height = buf.readUInt32BE(20);
  if (width < 256 || height < 256) {
    throw new Error(`El ícono debe ser de al menos 256x256 px (actual: ${width}x${height}).`);
  }
  return { width, height };
}

function main() {
  if (!fs.existsSync(sourceWebDist)) {
    console.error(
      `No se encontró "${sourceWebDist}". Primero genera el build web ejecutando:\n  cd ../sigho-app && npx expo export --platform web`
    );
    process.exit(1);
  }

  if (fs.existsSync(destWebDist)) {
    fs.rmSync(destWebDist, { recursive: true, force: true });
  }

  fs.cpSync(sourceWebDist, destWebDist, { recursive: true });
  console.log(`Build web copiado en: ${destWebDist}`);

  if (!fs.existsSync(sourceIcon)) {
    console.error(`No se encontró el ícono en "${sourceIcon}".`);
    process.exit(1);
  }

  const { width, height } = validarPngMinimo256(sourceIcon);
  fs.mkdirSync(destAssetsDir, { recursive: true });
  fs.copyFileSync(sourceIcon, destIcon);
  console.log(`Ícono copiado (${width}x${height} px) en: ${destIcon}`);
}

main();
