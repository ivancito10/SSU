<?php

namespace App\Livewire\Enfermeria\Inyectables;

use App\Models\Enfermeria\RegistroInyeccion;
use App\Exports\Enfermeria\InyeccionesExport;
use App\Services\EnfermeriaNestService;
use Maatwebsite\Excel\Facades\Excel;
use Livewire\Component;
use Livewire\Attributes\On;

class InyectablesMain extends Component
{
    // Propiedades para controlar el modal
    public bool $modalVerAbierto = false;
    public ?array $detalleAtencion = null;

    // Filtros del reporte Excel
    public ?string $reporteFechaInicio = null;
    public ?string $reporteFechaFin = null;
    public string $reporteEstado = 'TODOS';

    public function render()
    {
        return view('livewire.enfermeria.inyectables.inyectables-main');
    }

    public function abrirModalReporte(): void
    {
        $this->resetValidation();
        $this->reporteFechaInicio = null;
        $this->reporteFechaFin    = null;
        $this->reporteEstado      = 'TODOS';
        $this->dispatch('abrirModalReporteInyecciones');
    }

    public function exportarExcel()
    {
        $this->validate([
            'reporteFechaInicio' => 'nullable|date',
            'reporteFechaFin'    => 'nullable|date|after_or_equal:reporteFechaInicio',
            'reporteEstado'      => 'required|in:TODOS,ACTIVOS,ANULADOS',
        ], [
            'reporteFechaFin.after_or_equal' => 'La fecha hasta debe ser mayor o igual a la fecha desde.',
        ]);

        // 1. Datos de Etapa 2
        $query = RegistroInyeccion::with([
            'receta',
            'recetaManual',
            'medicamentos.medicamento',
            'procedimiento',
            'viaParenteral',
            'usuarioCreador',
        ])->orderBy('id', 'desc');

        if ($this->reporteFechaInicio) {
            $query->whereDate('created_at', '>=', $this->reporteFechaInicio);
        }
        if ($this->reporteFechaFin) {
            $query->whereDate('created_at', '<=', $this->reporteFechaFin);
        }
        if ($this->reporteEstado === 'ACTIVOS') {
            $query->where('estado', true);
        } elseif ($this->reporteEstado === 'ANULADOS') {
            $query->where('estado', false);
        }

        $registros = $query->get();

        if ($registros->isEmpty()) {
            $this->dispatch('alertaError', mensaje: 'No existen registros de inyecciones para los filtros seleccionados.');
            return null;
        }

        // 2. Datos de pacientes y médicos de Etapa 1 vía NestJS (una consulta por cada ID único)
        $nestService = app(EnfermeriaNestService::class);

        $pacientes = [];
        foreach ($registros->pluck('id_persona')->unique()->filter() as $idPersona) {
            $p = null;
            try {
                $p = $nestService->obtenerPacientePorId((int) $idPersona);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning("Reporte inyecciones: paciente {$idPersona} " . $e->getMessage());
            }

            $paterno = trim((string) ($p['primerApellido'] ?? ''));
            $materno = trim((string) ($p['segundoApellido'] ?? ''));
            $nombres = trim((string) ($p['nombres'] ?? ''));
            $nombreCompleto = trim((string) ($p['nombreCompleto'] ?? $p['nombre_completo'] ?? "PACIENTE #{$idPersona}"));

            // Respaldo si NestJS no devolvió los apellidos separados
            if ($paterno === '' && $nombres === '' && $nombreCompleto !== '') {
                $nombres = $nombreCompleto;
            }

            $pacientes[$idPersona] = [
                'p_apellido'        => mb_strtoupper($paterno),
                's_apellido'        => mb_strtoupper($materno),
                'nombres'           => mb_strtoupper($nombres),
                'matricula'         => !empty($p['matricula']) ? mb_strtoupper(trim((string) $p['matricula'])) : 'S/M',
                'sexo'              => mb_strtoupper(trim((string) ($p['sexo'] ?? ''))),
                'tipo_asegurado'    => mb_strtoupper(trim((string) ($p['tipoAsegurado'] ?? ''))),
                'id_tipo_asegurado' => $p['idTipoAsegurado'] ?? null,
            ];
        }

        $idsEspecialistas = $registros->map(function ($r) {
            return optional($r->recetaManual)->id_especialista
                ?: optional($r->receta)->id_especialista
                ?: $r->id_especialista;
        })->unique()->filter();

        $especialistas = [];
        foreach ($idsEspecialistas as $idEsp) {
            $esp = null;
            try {
                $esp = $nestService->obtenerEspecialistaPorId((int) $idEsp);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning("Reporte inyecciones: especialista {$idEsp} " . $e->getMessage());
            }
            $nomEsp = trim((string) ($esp['nombreCompleto'] ?? $esp['nombre'] ?? "ESPECIALISTA #{$idEsp}"));
            $especialistas[$idEsp] = mb_strtoupper($nomEsp);
        }

        // 3. Armar las 32 columnas (A..AF) idénticas a VACUNAS_EXCEL.xlsx
        $filas = $registros->values()->map(function ($r, $i) use ($pacientes, $especialistas) {
            $pac = $pacientes[$r->id_persona] ?? [
                'p_apellido'        => '',
                's_apellido'        => '',
                'nombres'           => "PACIENTE #{$r->id_persona}",
                'matricula'         => 'S/M',
                'sexo'              => '',
                'tipo_asegurado'    => '',
                'id_tipo_asegurado' => null,
            ];

            // Sexo (G=M, H=F)
            $sexoM = in_array($pac['sexo'], ['M', 'MASCULINO', 'HOMBRE'], true) ? 1 : '';
            $sexoF = in_array($pac['sexo'], ['F', 'FEMENINO', 'MUJER'], true) ? 1 : '';

            // Tipo de asegurado (I=ACTIVOS A, J=ACTIVOS B, K=RENTISTA A, L=RENTISTA B, AE=SSUE, AF=INTERIOR)
            $tipo = $pac['tipo_asegurado'];
            $idTipo = (int) ($pac['id_tipo_asegurado'] ?? 0);

            $activoA   = '';
            $activoB   = '';
            $rentistaA = '';
            $rentistaB = '';
            $ssue      = '';
            $interior  = '';

            if ($idTipo === 11 || str_contains($tipo, 'ESTUDIANTE') || str_contains($tipo, 'SSUE')) {
                $ssue = 1;
            } elseif (in_array($idTipo, [9, 10], true) || str_contains($tipo, 'INTERIOR')) {
                $interior = 1;
            } elseif (str_contains($tipo, 'RENTISTA')) {
                if (str_contains($tipo, 'BENEFICIARIO')) {
                    $rentistaB = 1;
                } else {
                    $rentistaA = 1;
                }
            } elseif (str_contains($tipo, 'BENEFICIARIO')) {
                $activoB = 1;
            } elseif (str_contains($tipo, 'TITULAR') || str_contains($tipo, 'ACTIVO')) {
                $activoA = 1;
            }

            // Vía parenteral (M=IM, N=IV, O=SC, P=VENOCLISIS) y medicamentos (AC=TRATAMIENTO)
            $viaIM = '';
            $viaIV = '';
            $viaSC = '';
            $viaVeno = '';
            $medicamentos = [];
            $viasNombres = [];

            foreach ($r->medicamentos as $med) {
                $medicamentos[] = mb_strtoupper(optional($med->medicamento)->nombre ?? "MEDICAMENTO #{$med->id_medicamento}");
                if (preg_match('/Vía:\s*([^|]+)/i', (string) $med->observacion, $m)) {
                    $viasNombres[] = mb_strtolower(trim($m[1]));
                }
            }
            if (empty($viasNombres) && $r->viaParenteral) {
                $viasNombres[] = mb_strtolower(trim((string) $r->viaParenteral->nombre));
            }
            foreach ($viasNombres as $vn) {
                if (str_contains($vn, 'intramuscular') || $vn === 'im') {
                    $viaIM = 1;
                } elseif (str_contains($vn, 'intravenosa') || $vn === 'iv') {
                    $viaIV = 1;
                } elseif (str_contains($vn, 'subcut')) {
                    $viaSC = 1;
                } elseif (str_contains($vn, 'venoclisis') || str_contains($vn, 'infusi')) {
                    $viaVeno = 1;
                }
            }

            // Procedimientos (Q..AB)
            $proc = $r->procedimiento;
            $curacionP   = ($proc && $proc->curacion_plana) ? 1 : '';
            $curacionI   = ($proc && $proc->curacion_infectada) ? 1 : '';
            $oxigeno     = ($proc && $proc->oxigenoterapia) ? 1 : '';
            $retiroPtos  = ($proc && $proc->retiro_puntos) ? 1 : '';
            $sensib      = ($proc && $proc->prueba_sensibilidad) ? 1 : '';
            $sangria     = ($proc && $proc->sangria) ? 1 : '';
            $signosVit   = ($proc && $proc->signos_vitales) ? 1 : '';
            $txVo        = ($proc && $proc->tx_vo) ? 1 : '';
            $orientacion = ($proc && $proc->orientacion_salud) ? 1 : '';
            $vendajes    = ($proc && $proc->vendajes) ? 1 : '';
            $nebuliz     = ($proc && $proc->nebulizacion) ? 1 : '';
            $otros       = ($proc && !empty($proc->otros)) ? 1 : '';

            $idEspReg = optional($r->recetaManual)->id_especialista
                ?: optional($r->receta)->id_especialista
                ?: $r->id_especialista;
            $medicoNombre = $idEspReg ? ($especialistas[$idEspReg] ?? "ESPECIALISTA #{$idEspReg}") : '';

            return [
                $i + 1,                                                // A: N
                optional($r->created_at)->format('H:i') ?? '',         // B: HORA
                $pac['p_apellido'],                                    // C: APELLIDO PATERNO
                $pac['s_apellido'],                                    // D: APELLIDO MATERNO
                $pac['nombres'],                                       // E: NOMBRE
                $pac['matricula'],                                     // F: MATRICULA
                $sexoM,                                                // G: SEXO M
                $sexoF,                                                // H: SEXO F
                $activoA,                                              // I: ACTIVOS A
                $activoB,                                              // J: ACTIVOS B
                $rentistaA,                                            // K: RENTISTA A
                $rentistaB,                                            // L: RENTISTA B
                $viaIM,                                                // M: IM
                $viaIV,                                                // N: IV
                $viaSC,                                                // O: SC
                $viaVeno,                                              // P: VENOCLISIS
                $curacionP,                                            // Q: CURACION P
                $curacionI,                                            // R: CURACION I
                $oxigeno,                                              // S: OXIGENO TERAPIA
                $retiroPtos,                                           // T: RETIRO DE PUNTOS
                $sensib,                                               // U: PRUEBA DE SENSIBILIDAD
                $sangria,                                              // V: SANGRIA
                $signosVit,                                            // W: SIGNOS VITALES
                $txVo,                                                 // X: TX. V.O.
                $orientacion,                                          // Y: ORIENTACION EN SALUD
                $vendajes,                                             // Z: VENDAJES
                $nebuliz,                                              // AA: NEBULIZACION
                $otros,                                                // AB: OTROS
                implode(' + ', $medicamentos),                         // AC: TRATAMIENTO
                $medicoNombre,                                         // AD: MEDICO
                $ssue,                                                 // AE: SSUE
                $interior,                                             // AF: INTERIOR
            ];
        });

        $desde = $this->reporteFechaInicio ? \Carbon\Carbon::parse($this->reporteFechaInicio)->format('d/m/Y') : null;
        $hasta = $this->reporteFechaFin ? \Carbon\Carbon::parse($this->reporteFechaFin)->format('d/m/Y') : null;
        if ($desde && $hasta) {
            $fechaTexto = ($desde === $hasta) ? $desde : "{$desde} AL {$hasta}";
        } elseif ($desde) {
            $fechaTexto = "DESDE {$desde}";
        } elseif ($hasta) {
            $fechaTexto = "HASTA {$hasta}";
        } else {
            $fechaTexto = now()->format('d/m/Y');
        }

        $encargada = optional(auth()->user())->name ?? 'ENFERMERÍA';

        $this->dispatch('cerrarModalReporteInyecciones');

        return Excel::download(
            new InyeccionesExport($filas, $fechaTexto, $encargada),
            'reporte_actividades_enfermeria_' . now()->format('Ymd_His') . '.xlsx'
        );
    }

    public function abrirModalRegistrar()
    {
        $this->dispatch('limpiarFormularioInyeccion');
        $this->dispatch('modal_registrar_inyeccion');
    }

    public function redirectToCreate()
    {
        return redirect()->route('enfermeria.inyectables.create');
    }

    #[On('verInyeccion')]
    public function verInyeccion($id)
    {
        // Tolera si PowerGrid envía $id como número o como array ['id' => X]
        $idRegistro = is_array($id) ? ($id['id'] ?? null) : $id;

        if (!$idRegistro) {
            return;
        }

        // Cargar datos de Fase 2
        $registro = RegistroInyeccion::with([
            'receta',
            'recetaManual',
            'medicamentos.medicamento',
            'procedimiento',
            'usuarioCreador',
            'usuarioEditor',
        ])->find($idRegistro);

        if (!$registro) {
            $this->dispatch('errorActualizacion');
            return;
        }

        // Resolver paciente desde NestJS (Fase 1)
        $paciente = [
            'nombre'    => "PACIENTE #{$registro->id_persona}",
            'ci'        => 'S/C',
            'matricula' => 'S/M',
        ];

        try {
            $nestService = app(EnfermeriaNestService::class);
            $res = $nestService->obtenerPacientePorId((int)$registro->id_persona);
            $p = isset($res['data']) && is_array($res['data']) ? $res['data'] : $res;

            if (!empty($p) && is_array($p)) {
                $nombre = $p['nombreCompleto'] ?? $p['nombre_completo'] ?? null;
                if (!empty($nombre)) {
                    $paciente = [
                        'nombre'    => trim($nombre),
                        'ci'        => !empty($p['ci']) ? trim($p['ci']) : 'S/C',
                        'matricula' => !empty($p['matricula']) ? trim($p['matricula']) : 'S/M',
                    ];
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning("Error modal paciente {$registro->id_persona}: " . $e->getMessage());
        }

        // Estructurar información para el modal
        $this->detalleAtencion = [
            'id'             => $registro->id,
            'estado'         => (bool) $registro->estado,
            'fecha_registro' => optional($registro->created_at)->format('d/m/Y H:i:s') ?? 'Sin fecha',
            'paciente'       => $paciente,
            'nro_receta'     => optional($registro->receta)->nro_receta ?? optional($registro->recetaManual)->numero_receta ?? ($registro->id_receta ?? 'S/N (Manual)'),
            'medicamentos'   => $registro->medicamentos->map(function ($med) {
                return [
                    'nombre'      => optional($med->medicamento)->nombre ?? "Medicamento #{$med->id_medicamento}",
                    'observacion' => $med->observacion ?: 'Sin observaciones',
                ];
            })->toArray(),
            'procedimiento'  => $registro->procedimiento ? [
                'curacion_plana'       => (bool) $registro->procedimiento->curacion_plana,
                'curacion_infectada'   => (bool) $registro->procedimiento->curacion_infectada,
                'oxigenoterapia'       => (bool) $registro->procedimiento->oxigenoterapia,
                'retiro_puntos'        => (bool) $registro->procedimiento->retiro_puntos,
                'prueba_sensibilidad'  => (bool) $registro->procedimiento->prueba_sensibilidad,
                'sangria'              => (bool) $registro->procedimiento->sangria,
                'observaciones'        => $registro->procedimiento->observaciones ?: 'Ninguna',
            ] : null,
            'user_crea'      => $registro->usuarioCreador?->name ?: 'SIN DATO',
            'fecha_creado'   => $registro->created_at?->toDateTimeString(),
            'user_edit'      => $registro->usuarioEditor?->name ?: 'SIN DATO',
            'fecha_editado'  => $registro->updated_at?->toDateTimeString(),
        ];

        // Abrir la ventana emergente estilo institucional (<x-ver-modal>)
        $this->modalVerAbierto = true;
        $this->dispatch('verInyeccionModal');
    }

    public function cerrarModalVer()
    {
        $this->modalVerAbierto = false;
        $this->detalleAtencion = null;
    }

    #[On('cambiarEstadoInyeccion')]
    public function cambiarEstadoInyeccion($id)
    {
        $idRegistro = is_array($id) ? ($id['id'] ?? null) : $id;
        try {
            $inyeccion = RegistroInyeccion::find($idRegistro);
            if ($inyeccion) {
                $inyeccion->estado = !$inyeccion->estado;
                $inyeccion->id_user_updated = auth()->id();
                $inyeccion->save();

                $this->dispatch('pg:eventRefresh-InyectablesTable');
                $this->dispatch('estadoActualizado');
            }
        } catch (\Throwable $th) {
            $this->dispatch('errorActualizacion');
        }
    }

    #[On('editarInyeccion')]
    public function editarInyeccion($id)
    {
        $idRegistro = is_array($id) ? ($id['id'] ?? null) : $id;
        return redirect()->route('enfermeria.inyectables.edit', ['id' => $idRegistro]);
    }
}