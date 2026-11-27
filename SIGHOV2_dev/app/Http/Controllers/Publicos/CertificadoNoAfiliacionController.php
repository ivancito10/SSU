<?php

namespace App\Http\Controllers\Publicos;

use App\Http\Controllers\Controller;
use App\Models\Publicos\CertificadoNoAfiliacion;
use App\Services\QRCodeService;
use Carbon\Carbon;
use DomPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class CertificadoNoAfiliacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function pdf_certificado_no_afiliacion()
    {
        if (! session()->has('certificado_no_afiliacion')) {
            abort(403, 'Acceso no autorizado');
        }
        // try {
        $datos = session('certificado_no_afiliacion');
        // dd($datos);
        if (now()->diffInMinutes($datos['created_at']) > 2) {
            session()->forget('certificado_no_afiliacion');
            abort(403, 'Sesión expirada');
        }
        // dd($datos);
        $codigos = QRCodeService::generarQR();
        $cod_seguridad = $codigos['cod_seguridad'];
        $qrCode = $codigos['qrCode'];
        $cod2 = Crypt::encrypt(random_int(1, 999999));
        $codigo_seguridad = strtoupper(substr(hash('sha256', Str::uuid()), 0, 16));
        $yaGenerado = CertificadoNoAfiliacion::where('ip', request()->ip())
            ->whereDate('created_at', now())
            ->count();

        if ($yaGenerado >= 10) {
            abort(429, 'Límite diario alcanzado');
        }
        $creacionCerificado = CertificadoNoAfiliacion::create([
            'nombres' => $datos['nombres'],
            'paterno' => $datos['apellido_paterno'],
            'materno' => $datos['apellido_materno'],
            'fecha_emision' => Carbon::now()->format('Y-m-d'),
            'fecha_validez' => Carbon::now()->addDays(15)->format('Y-m-d'),
            'codigo_seguridad' => $codigo_seguridad,
            'ip' => request()->ip(),
            'dispositivo' => request()->userAgent(),
        ]);

        $pdf = DomPDF::loadView('Publicos.CertificadoNoAfiliacion.CertificadoPdf', compact('datos', 'qrCode', 'codigo_seguridad'))->setPaper('letter', 'portrait');
        QRCodeService::registrarPDF($pdf, $cod_seguridad, 1, 1, 'AFILIACION');
        $pdfContent = $pdf->output();
        session()->forget('certificado_no_afiliacion');

        return $pdf->download('Certificado de No Afiliacion - '.$creacionCerificado->id.'.pdf', [
            'Attachment' => false,
            'Content-Disposition' => 'inline',
        ]);

        return response()->json($datos);
        // } catch (\Throwable $e) {
        //     report($e); // logs internos
        //     return redirect()
        //         ->route('obtenerCertificadoNoAfiliacion.usuario.index')
        //         ->with('error', 'No fue posible generar el certificado.');
        // }
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
