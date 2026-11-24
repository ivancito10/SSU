<?php

namespace App\Http\Controllers\Fisioterapia;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CredencialesFisioterapeutaController extends Controller
{
    private static function llave(string $folio): string
    {
        return 'fisioterapia:credenciales:'.auth()->id().':'.session()->getId().':'.$folio;
    }

    public static function preparar(array $usuario, array $persona): string
    {
        $folio = (string) Str::uuid();
        $vence = now()->addMinutes(10);
        $datos = [
            'id' => $usuario['idUsuario'], 'email' => $usuario['email'], 'clave' => $usuario['passwordTemporal'],
            'persona' => [
                'nombres' => $persona['nombres'], 'p_apellido' => $persona['primerApellido'] ?? '',
                's_apellido' => $persona['segundoApellido'] ?? '', 'ci' => $persona['ci'],
                'complemento' => $persona['complemento'] ?? '',
            ],
            'fecha' => now()->translatedFormat('d \d\e F \d\e Y H:i:s'),
        ];
        Cache::put(self::llave($folio), Crypt::encrypt($datos), $vence);
        return URL::temporarySignedRoute('fisioterapia.credenciales', $vence, ['folio' => $folio]);
    }

    public function __invoke(Request $request, string $folio)
    {
        $cifrado = Cache::get(self::llave($folio));
        abort_if(!$cifrado, 404);
        $datos = Crypt::decrypt($cifrado);
        $mensaje = 'Usuario: '.$datos['email'].'|| Contraseña: '.$datos['clave'].' || Verificado y validado por el SSU LP - Sistema SIGHO v2.';
        $qrCode = base64_encode(QrCode::format('svg')->size(80)->color(3, 125, 155)
            ->backgroundColor(255, 255, 255)->errorCorrection('H')->generate($mensaje));
        $pdf = Pdf::loadView('Administracion.Usuarios.pdfUsuarioContraseña', [
            'datos_usuario_registro' => (object) ['email' => $datos['email'], 'personaUser' => (object) $datos['persona']],
            'datos_usuario_modificador' => $request->user(),
            'passwordTemporal' => $datos['clave'],
            'fecha' => $datos['fecha'],
            'qrCode' => $qrCode,
        ])->setPaper('letter', 'portrait');
        // DomPDF intenta getimagesize() antes de reconocer el SVG del QR.
        $anterior = set_error_handler(static function ($nivel, $mensaje, $archivo, $linea) use (&$anterior) {
            if ($mensaje === 'Cannot use bool as array'
                && str_ends_with(str_replace('\\', '/', $archivo), '/dompdf/dompdf/src/Helpers.php')) {
                return true;
            }
            return $anterior ? $anterior($nivel, $mensaje, $archivo, $linea) : false;
        });
        try {
            $contenido = $pdf->output();
        } finally {
            restore_error_handler();
        }
        $archivo = 'Administracion/Usuarios/Credenciales/'.$datos['id'].'.pdf';
        try {
            $archivado = Storage::disk('ftp')->put($archivo, $contenido);
        } catch (\League\Flysystem\FilesystemException $e) {
            $archivado = false;
        }
        if (!$archivado) {
            logger()->warning('No se pudo archivar el PDF de credenciales de Fisioterapia en FTP.');
        }
        $nombre = $datos['persona']['nombres'].' - Documento confidencial '.now()->format('d-m-Y').'.pdf';
        return $pdf->stream($nombre)->header('Cache-Control', 'private, no-store');
    }
}
