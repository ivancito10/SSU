<?php

namespace App\Http\Controllers\Afiliacion;

use App\Exports\ReporteTitularesExport;
use App\Exports\ReporteBeneficiariosExport;
use App\Models\Afiliacion\HCarnet;
use App\Models\Afiliacion\Titular;
use App\Models\User;
use App\Models\Administracion\Persona;
use App\Models\Administracion\TipoAsegurado;
use Carbon\Carbon;
use DomPDF;

use App\Http\Controllers\Controller;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\Estudiante;
use App\Models\Aportes\Institucion;
use App\Models\Afiliacion\TitularInstitucion;
use Illuminate\Http\Request;
use App\Services\QRCodeService;
use Maatwebsite\Excel\Facades\Excel;


class ReportesPdfController extends Controller
{
    public function reporteTitular($tipo_reporte, $id_institucion, $fecha_inicio, $fecha_fin)
    {

        if ($tipo_reporte === 'carnets') {
            $dataTitular = $this->obtenerDataTitularesActivos();
            $idsPersonaTitular = $dataTitular->pluck('id_persona');

            $ultimosCarnets = $this->obtenerUltimosCarnets($idsPersonaTitular, $fecha_inicio, $fecha_fin);
            $idsPersonaCarnet = $ultimosCarnets->pluck('id_persona')->unique()->values();
            $datos_generales = $this->obtenerDatosGeneralesTitualares($idsPersonaCarnet);

            $datosFinales = $ultimosCarnets->map(function ($carnet) use ($datos_generales) {
                $general = $datos_generales
                    ->firstWhere('id_persona', $carnet['id_persona']);
                return [
                    ...$general,
                    ...$carnet,
                ];
            });
            // dd($datosFinales);
        } elseif ($tipo_reporte === 'registros') {
            $idsPersonaTitular = $this->obtenerDataTitularesActivos($fecha_inicio, $fecha_fin)->pluck('id_persona');
            $datosFinales = $this->obtenerDatosGeneralesTitualares($idsPersonaTitular);
        } elseif ($tipo_reporte === 'institucion') {
            $ids_titulares = TitularInstitucion::where('id_institucion', $id_institucion)
                ->where('estado', true)
                ->pluck('id_titular');
            $idsPersonaTitular = $this->obtenerDataTitularesActivos($fecha_inicio, $fecha_fin)
                ->whereIn('id', $ids_titulares)
                ->pluck('id_persona');

            $datosFinales = $this->obtenerDatosGeneralesTitualares($idsPersonaTitular);
            //dd($datos_generales);
        } elseif ($tipo_reporte === 'traspaso') {
            $ids_titulares = TitularInstitucion::where('estado', true)
                ->select('id_titular')
                ->groupBy('id_titular')
                ->havingRaw('COUNT(*) > 1')
                ->pluck('id_titular');
            $datos = TitularInstitucion::with('titularInstitucionConInstitucion')
                ->whereIn('id_titular', $ids_titulares)
                ->where('estado', true)
                ->orderBy('id')
                ->get()
                ->groupBy('id_titular');
            $datosTraspaso = $datos->map(function ($grupo, $idTitular) {
                return [
                    'id_titular' => $idTitular,
                    'instituciones' => $grupo->map(function ($tit) {
                        return [
                            'institucion' =>
                            $tit->titularInstitucionConInstitucion?->nombre ?? '',
                            'cargo' =>
                            $tit->cargo_actual ?? '',
                            'titularidad' =>
                            $tit->titularidad ?? '',
                        ];
                    })->values(),
                ];
            });
            // dd($ids_titulares);

            $idsPersonaTitular = $this->obtenerDataTitularesActivos($fecha_inicio, $fecha_fin)
                ->whereIn('id', $ids_titulares)
                ->pluck('id_persona');

            $datos_generales = $this->obtenerDatosGeneralesTitualares($idsPersonaTitular);

            $datosFinales = $datos_generales->map(function ($general) use ($datosTraspaso) {
                $traspaso = $datosTraspaso->get($general['id_titular']);
                return [
                    ...$general,
                    'instituciones_traspaso' =>
                    $traspaso['instituciones'] ?? collect(),
                ];
            });

            // dd($datos_generales, $datosTraspaso, $datosFinales);
        } else {
            return [];
        }

        $fechaInicioFormatted = Carbon::parse($fecha_inicio)->format('d/m/Y');
        $fechaFinFormatted = Carbon::parse($fecha_fin)->format('d/m/Y');
        $institucion = Institucion::find($id_institucion);
        $nombre_institucion = $institucion->nombre ?? '';
        // $acronimo = $institucion->acronimo;
        //$nombre

        $name = 'ReporteTitulares' . '-' . $tipo_reporte . '-' . $fecha_inicio . '-' . $fecha_fin;

        return Excel::download(
            new ReporteTitularesExport(
                $datosFinales,
                $tipo_reporte,
                $fechaInicioFormatted,
                $fechaFinFormatted,
                $nombre_institucion,
            ),
            $name . '.xlsx'
        );
    }


    public function obtenerDataTitularesActivos($fecha_inicio = null, $fecha_fin = null)
    {
        $dataTitular = Titular::where('estado', true)
            ->where('estado_vigencia', '!=', 'NO VIGENTE');
        if ($fecha_inicio && $fecha_fin) {
            $dataTitular->whereDate('created_at', '>=', $fecha_inicio)
                ->whereDate('created_at', '<=', $fecha_fin);
        }
        return $dataTitular;
    }

    public function obtenerDatosGeneralesTitualares($idsPersonas)
    {

        $titulares = Titular::with([
            'titularPersona.tipoAseguradoPersona',
            'institucionPrincipal.titularInstitucionConInstitucion.codigosInstitucion',
        ])
            ->whereIn('id_persona', $idsPersonas)
            ->where('estado_vigencia', '!=', 'NO VIGENTE')
            ->where('estado', true)
            ->get();

        $datos = $titulares->map(function ($titular) {
            if ($titular->titularPersona->complemento != null) {
                $carnet = $titular->titularPersona->ci . '-' . $titular->titularPersona->complemento;
            } else {
                $carnet = $titular->titularPersona->ci;
            }
            $codigoInstitucion =  ($titular->institucionPrincipal->titularInstitucionConInstitucion?->codigosInstitucion?->codigo ?? '') .
                ($titular->institucionPrincipal->titularInstitucionConInstitucion?->codigosInstitucion?->tipo_titular ?? '');

            return [
                // 'nro' => $index + 1,
                'id_titular' => $titular->id,
                'id_persona' => $titular->id_persona,
                'nombres' => $titular->titularPersona->nombres,
                'p_apellido' => $titular->titularPersona->p_apellido,
                's_apellido' => $titular->titularPersona->s_apellido,
                // 'nombre_asegurado' => trim("{$titular->titularPersona->nombres} {$titular->titularPersona->p_apellido} {$titular->titularPersona->s_apellido}"),
                // 'matricula_asegurado' => $titular->titularPersona->matricula_seguro . '-' . $titular->institucionPrincipal->titularInstitucionConInstitucion?->codigosInstitucion?->codigo ?: '' . '' . $titular->institucionPrincipal->titularInstitucionConInstitucion?->codigosInstitucion?->tipo_titular ?: '',
                'matricula_asegurado' => ($titular->titularPersona->matricula_seguro ?? '') . ($codigoInstitucion ? '-' . $codigoInstitucion : ''),
                'ci' => $carnet,
                'fecha_nacimiento' => Carbon::parse($titular->titularPersona->fecha_nacimiento)->format('d/m/Y'),
                'edad' =>  Carbon::parse($titular->titularPersona->fecha_nacimiento)->age,
                'sexo' => $titular->titularPersona->sexo === 'F' ? 'FEMENINO' : 'MASCULINO',
                'tipo_asegurado' => $titular->titularPersona->tipoAseguradoPersona->tipo_asegurado,
                'estado' => $titular->estado_vigencia,
                'fecha_afiliacion' => Carbon::parse($titular->fecha_afiliacion)->format('d/m/Y'),
                'fecha_reafiliacion' => $titular->fecha_reafiliacion ? Carbon::parse($titular->fecha_reafiliacion)->format('d/m/Y') : Carbon::parse($titular->fecha_afiliacion)->format('d/m/Y'),
                //'institucion' => $titular->institucionPrincipal??->titularInstitucionConInstitucion??->nombre ? :'',
                'institucion' => $titular->institucionPrincipal?->titularInstitucionConInstitucion?->nombre ?? '',
                'cargo' => $titular->institucionPrincipal?->cargo_actual ?? '',
                'titularidad' => $titular->institucionPrincipal?->titularidad ?? '',
            ];
        });
        return $datos;
    }

    public function obtenerUltimosCarnets($id_personas, $fecha_inicio, $fecha_fin)
    {
        $carnet = HCarnet::whereIn('id_persona', $id_personas)
            //->distinct('fecha_emision')
            ->whereDate('created_at', '>=', $fecha_inicio)
            ->whereDate('created_at', '<=', $fecha_fin)
            //->orderBy('fecha_emision')
            ->orderBy('id', 'desc')
            ->get()
            ->groupBy(function ($dato) {
                return $dato->id_persona . '-' . $dato->fecha_emision;
            })
            ->map(function ($grupo) {
                return $grupo->first();
            });

        $datos_carnet = $carnet->map(function ($ci) {
            return [
                'id' => $ci->id,
                'id_persona' => $ci->id_persona,
                'fecha_emision' =>  Carbon::parse($ci->fecha_emision)->format('d/m/Y'),
                'fecha_vencimiento' => $ci->indefinido ? '' : Carbon::parse($ci->fecha_vencimiento)->format('d/m/Y'),
                'motivo' => $ci->motivo,
                'indefinido' => $ci->indefinido ? 'CARNET INDEFINIDO' : 'CARNET NO INDEFINIDO',
            ];
        });
        return $datos_carnet;
    }
    // ================ BENEFICIARIOS =========================================================
    public function reporteBeneficiario($tipo_reporte, $id_institucion, $id_tipo_asegurado, $fecha_inicio, $fecha_fin)
    {
        // dd('hola');
        // $idsPersonas = [6524, 6525, 6526, 6527];
        // $resultados = $this->obtenerDatosGeneralesBeneficiarios($idsPersonas);
        // dd($resultados);
        if ($tipo_reporte == 'carnets') {
            $dataBeneficiario = $this->obtenerBeneficiariosActivos();
            $idsPersonasBeneficiarios = $dataBeneficiario->pluck('id_persona');

            $carnets = $this->obtenerUltimosCarnets($idsPersonasBeneficiarios, $fecha_inicio, $fecha_fin);
            $idsPersonaCarnet = $carnets->pluck('id_persona')->unique()->values();
            $datos_generales = $this->obtenerDatosGeneralesBeneficiarios($idsPersonaCarnet);

            $datosFinales = $carnets->map(function ($carnet) use ($datos_generales) {
                $general = $datos_generales
                    ->firstWhere('id_persona', $carnet['id_persona']);
                return [
                    ...$general,
                    ...$carnet,
                ];
            });
        } elseif ($tipo_reporte == 'registros') {
            $idsPersonasBeneficiarios = $this->obtenerBeneficiariosActivos($fecha_inicio, $fecha_fin)->pluck('id_persona');
            $datosFinales = $this->obtenerDatosGeneralesBeneficiarios($idsPersonasBeneficiarios);
        } elseif ($tipo_reporte == 'institucion') {
            $ids_titulares = TitularInstitucion::where('id_institucion', $id_institucion)
                ->where('estado', true)
                ->pluck('id_titular');
            $idsPersonasBeneficiarios = $this->obtenerBeneficiariosActivos($fecha_inicio, $fecha_fin)
                ->whereIn('id_titular', $ids_titulares)
                ->pluck('id_persona');
            $datosFinales = $this->obtenerDatosGeneralesBeneficiarios($idsPersonasBeneficiarios);
            // dd($datosFinales);
        } elseif ($tipo_reporte == 'tipoBeneficiarios') {
            $idsPersonas = Persona::where('afiliado', true)
                ->where('id_tipo_asegurado', $id_tipo_asegurado)
                ->pluck('id');
            $idsPersonasBeneficiarios = $this->obtenerBeneficiariosActivos($fecha_inicio, $fecha_fin)
                ->whereIn('id_persona', $idsPersonas)
                ->pluck('id_persona');

            $datosFinales = $this->obtenerDatosGeneralesBeneficiarios($idsPersonasBeneficiarios);
        } else {
            return [];
        }

        $fechaInicioFormatted = Carbon::parse($fecha_inicio)->format('d/m/Y');
        $fechaFinFormatted = Carbon::parse($fecha_fin)->format('d/m/Y');
        $institucion = Institucion::find($id_institucion);
        $nombre_institucion = $institucion->nombre ?? '';

        $tipo = TipoAsegurado::find($id_tipo_asegurado);
        $tipo_asegurado = $tipo->tipo_asegurado ?? '';

        $name = 'ReporteBeneficiarios' . '-' . $tipo_reporte . '-' . $fecha_inicio . '-' . $fecha_fin;

        return Excel::download(
            new ReporteBeneficiariosExport(
                $datosFinales,
                $tipo_reporte,
                $fechaInicioFormatted,
                $fechaFinFormatted,
                $nombre_institucion,
                $tipo_asegurado,
            ),
            $name . '.xlsx'
        );
    }

    public function obtenerBeneficiariosActivos($fecha_inicio = null, $fecha_fin = null)
    {
        $dataBeneficiario = Beneficiario::where('estado', true)
            ->where('vigencia', '!=', 'NO VIGENTE');
        if ($fecha_inicio && $fecha_fin) {
            $dataBeneficiario->whereDate('created_at', '>=', $fecha_inicio)
                ->whereDate('created_at', '<=', $fecha_fin);
        }
        return $dataBeneficiario;
    }

    public function obtenerDatosGeneralesBeneficiarios($idsPersonas)
    {
        $beneficiarios = Beneficiario::with([
            'beneficiarioPersona',
            'titularBeneficiario.institucionPrincipal.titularInstitucionConInstitucion.codigosInstitucion'
        ])
            ->whereIn('id_persona', $idsPersonas)
            ->where('vigencia', '!=', 'NO VIGENTE')
            ->where('estado', true)
            ->get();

        $datos = $beneficiarios->map(function ($beneficiario) {

            if ($beneficiario->beneficiarioPersona->complemento != null) {
                $carnet = $beneficiario->beneficiarioPersona->ci . '-' . $beneficiario->beneficiarioPersona->complemento;
            } else {
                $carnet = $beneficiario->beneficiarioPersona->ci;
            }
            $codigoInstitucion =  ($beneficiario->titularBeneficiario->institucionPrincipal->titularInstitucionConInstitucion?->codigosInstitucion?->codigo ?? '') .
                ($beneficiario->titularBeneficiario->institucionPrincipal->titularInstitucionConInstitucion?->codigosInstitucion?->tipo_beneficiario ?? '');


            return [
                'id_beneficiario' => $beneficiario->id,
                'id_persona' => $beneficiario->id_persona,
                'nombres' => $beneficiario->beneficiarioPersona->nombres,
                'p_apellido' => $beneficiario->beneficiarioPersona->p_apellido,
                's_apellido' => $beneficiario->beneficiarioPersona->s_apellido,
                'matricula_asegurado' => ($beneficiario->beneficiarioPersona->matricula_seguro ?? '') . ($codigoInstitucion ? '-' . $codigoInstitucion : ''),
                'ci' => $carnet,
                'fecha_nacimiento' => Carbon::parse($beneficiario->beneficiarioPersona->fecha_nacimiento)->format('d/m/Y'),
                'edad' =>  Carbon::parse($beneficiario->beneficiarioPersona->fecha_nacimiento)->age,
                'sexo' => $beneficiario->beneficiarioPersona->sexo === 'F' ? 'FEMENINO' : 'MASCULINO',
                'tipo_asegurado' => $beneficiario->beneficiarioPersona->tipoAseguradoPersona->tipo_asegurado,

                'fecha_afiliacion' => Carbon::parse($beneficiario->fecha_afiliacion)->format('d/m/Y'),
                'fecha_reafiliacion' => $beneficiario->fecha_reafiliacion ? Carbon::parse($beneficiario->fecha_reafiliacion)->format('d/m/Y') : Carbon::parse($beneficiario->fecha_afiliacion)->format('d/m/Y'),
                'estado' => $beneficiario->vigencia,
                //'institucion' => $beneficiario->institucionPrincipal??->beneficiarioInstitucionConInstitucion??->nombre ? :'',
                'institucion' => $beneficiario->titularBeneficiario->institucionPrincipal?->titularInstitucionConInstitucion?->nombre ?? '',

            ];
        });
        return $datos;
    }


    /* public function reporteTitular($fecha_inicio, $fecha_fin, $tipo_reporte)
    {
       // dd('adfd');
        $fecha_inicio;
        $fecha_fin;
        $tipo_reporte;
        $carnets_emitidos = [];
        $titulares_registrados = [];
        $carnets_emitidos_beneficiarios = [];
        $beneficiarios_registrados = [];
        $carnets_emitidos_estudiantes = [];
        $estudiantes_registrados = [];
        $count = 0;
        $fecha_registro = Carbon::now();
        $userActual = User::join('administracion.persona as p', 'p.id', '=', 'administracion.users.id_persona')
            ->where('administracion.users.id', '=', auth()->user()->id)
            ->first();
        $id_persona = $userActual->id_persona;

        $personaUsuario = Persona::find($id_persona);
        $nombre_usuario = $personaUsuario->nombres . ' ' . $personaUsuario->p_apellido . ' ' . $personaUsuario->s_apellido;
        if ($tipo_reporte == 'carnets') {
            $carnets_emitidos = HCarnet::with('personaHCarnet')
                ->whereDate('created_at', '>=', $fecha_inicio)
                ->whereDate('created_at', '<=', $fecha_fin)
                ->whereHas('personaHCarnet', function ($query) {
                    $query->where('id_tipo_asegurado', 1);
                })
                ->get();
        }
        if ($tipo_reporte == 'registros') {
            $titulares_registrados = Titular::with('titularPersona')
                ->whereDate('created_at', '>=', $fecha_inicio)
                ->whereDate('created_at', '<=', $fecha_fin)
                ->get();
        }
        if ($tipo_reporte == 'carnets_beneficiarios') {
            $carnets_emitidos_beneficiarios = HCarnet::with('personaHCarnet')
                ->whereDate('created_at', '>=', $fecha_inicio)
                ->whereDate('created_at', '<=', $fecha_fin)
                ->whereHas('personaHCarnet', function ($query) {
                    $query->where('id_tipo_asegurado', [2, 3, 4, 5, 6, 7]);
                })
                ->get();
        }
        if ($tipo_reporte == 'registros_beneficiarios') {
            $beneficiarios_registrados = Beneficiario::with([
                'beneficiarioPersona.tipoAseguradoPersona'
            ])
                ->whereDate('created_at', '>=', $fecha_inicio)
                ->whereDate('created_at', '<=', $fecha_fin)
                ->get();
        }
        if ($tipo_reporte == 'carnets_estudiantes') {
            $carnets_emitidos_estudiantes = HCarnet::with('personaHCarnet')
                ->whereDate('created_at', '>=', $fecha_inicio)
                ->whereDate('created_at', '<=', $fecha_fin)
                ->whereHas('personaHCarnet', function ($query) {
                    $query->where('id_tipo_asegurado', 11);
                })
                ->get();
        }
        if ($tipo_reporte == 'registros_estudiantes') {
            $estudiantes_registrados = Estudiante::with([
                'personaEstudiante.tipoAseguradoPersona'
            ])
                ->whereDate('fecha_afiliacion', '>=', $fecha_inicio)
                ->whereDate('fecha_afiliacion', '<=', $fecha_fin)
                ->where('estado', true)
                ->where('estado_validar', 'AFILIADO')
                ->orderBy('fecha_afiliacion', 'desc')
                ->get();
        }
        $codigos = QRCodeService::generarQR();
        $cod_seguridad = $codigos['cod_seguridad'];
        $qrCode = $codigos['qrCode'];
        $pdf = DomPDF::loadView(
            'Afiliacion.Afiliacion.reporteTitular',
            compact(
                'tipo_reporte',
                'carnets_emitidos',
                'titulares_registrados',
                'fecha_inicio',
                'fecha_fin',
                'count',
                'fecha_registro',
                'nombre_usuario',
                'carnets_emitidos_beneficiarios',
                'beneficiarios_registrados',
                'estudiantes_registrados',
                'carnets_emitidos_estudiantes',
            )
        )
            ->setPaper('letter', 'portrait');
        QRCodeService::registrarPDF($pdf, $cod_seguridad, $id_persona, 11, 'AFILIACION');
        $nombre_pdf = 'pdf_reporte_titular';
        return $pdf->stream($nombre_pdf . '.pdf', [
            'Attachment' => false,
            'Content-Disposition' => 'inline'
        ]);
    } */
}
