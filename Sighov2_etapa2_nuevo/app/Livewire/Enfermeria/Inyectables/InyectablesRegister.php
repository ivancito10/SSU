<?php

namespace App\Livewire\Enfermeria\Inyectables;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\Enfermeria\ViaParenteral;
use App\Models\Enfermeria\RegistroInyeccion;
use App\Models\Enfermeria\RegistroInyeccionMedicamento;
use App\Models\Enfermeria\RegistroInyeccionProcedimiento; 
use App\Models\Enfermeria\RecetaManual;                   
use App\Services\EnfermeriaNestService;
use App\Models\ConsultaExterna\Receta;
use App\Models\ConsultaExterna\RecetaMedicamento;
use App\Models\Farmacia\Medicamento;
use Livewire\Attributes\On;

class InyectablesRegister extends Component
{
    use WithPagination;

    // Modo de receta: 'SISTEMA' o 'MANUAL'
    public string $tipoReceta = 'SISTEMA';

    // --- MODO SISTEMA ---
    public string $nroRecetaBusqueda = '';
    public array $recetaSistemaEncontrada = [];
    public array $medicamentosRecetaSistema = [];

    // --- MODO MANUAL (PACIENTE Y MÉDICO) ---
    public ?int $idPersona = null;
    public string $nombreCompletoPaciente = '';
    public string $matriculaPaciente = '';
    public string $ciPaciente = '';

    public ?int $idEspecialista = null;
    public ?string $fechaEmisionManual = null;

    // Selector de medicamento en modo manual
    public ?int $idMedicamentoManual = null;
    public ?int $cantidadManual = null;
    public ?int $idViaParenteralManual = null;

    // --- BUSCADOR MODAL DE PACIENTES (NestJS) ---
    public string $terminoBusquedaPaciente = '';
    public array $listaPacientesModal = [];

    // --- TABLA TEMPORAL: INYECCIONES INGRESADOS ---
    public array $inyeccionesIngresados = [];

    // --- CHECKLIST DE PROCEDIMIENTOS COMPLEMENTARIOS ---
    public bool $curacion_plana = false;
    public string $obs_curacion_plana = '';
    public bool $curacion_infectada = false;
    public string $obs_curacion_infectada = '';
    public bool $oxigenoterapia = false;
    public string $obs_oxigenoterapia = '';
    public bool $retiro_puntos = false;
    public string $obs_retiro_puntos = '';
    public bool $prueba_sensibilidad = false;
    public string $obs_prueba_sensibilidad = '';
    public bool $sangria = false;
    public string $obs_sangria = '';
    public bool $signos_vitales = false;
    public string $obs_signos_vitales = '';
    public bool $tx_vo = false;
    public string $obs_tx_vo = '';
    public bool $orientacion_salud = false;
    public string $obs_orientacion_salud = '';
    public bool $vendajes = false;
    public string $obs_vendajes = '';
    public bool $nebulizacion = false;
    public string $obs_nebulizacion = '';
    public bool $otros = false;
    public string $obs_otros = '';
    //public array $viasParenterales = [];
    public array $especialistas = [];
    //public array $medicamentosCatalogo = [];
    // Control del modal
    public bool $modalBuscarPaciente = false;

    // Propiedades del formulario
    public ?int $id_persona = null;
    public string $nombre_paciente = '';
    public string $matricula_paciente = '';
    public string $ci_paciente = '';
    public string $buscarEspecialista = '';
    public string $nombreEspecialistaSeleccionado = '';
    public array $especialistasEncontrados = [];
    public bool $mostrarDropdownEspecialistas = false;
    public string $buscarMedicamento = '';
   
    public string $nombreMedicamentoSeleccionado = '';
    public array $medicamentosFiltrados = [];
    public bool $mostrarDropdownMedicamentos = false;
    public string $numeroRecetaManual = ''; 

    // --- MODO EDICIÓN ---
    public ?int $idRegistroEditar = null;
    public ?int $idRecetaManualEditar = null;
    public bool $modoEdicion = false;

    public function mount($id = null)
    {
        $this->fechaEmisionManual = null;
        if (!empty($id)) {
            $this->cargarParaEdicion((int) $id);
        }
    }

    public function updatedNumeroRecetaManual(): void
    {
        if ($this->tipoReceta === 'MANUAL' && !empty($this->inyeccionesIngresados)) {
            $nuevoNro = trim($this->numeroRecetaManual) !== '' ? trim($this->numeroRecetaManual) : 'S/N';
            foreach ($this->inyeccionesIngresados as $idx => $item) {
                $this->inyeccionesIngresados[$idx]['nro_receta'] = $nuevoNro;
            }
        }
    }

    protected function cargarParaEdicion(int $idRegistro): void
    {
        $registro = RegistroInyeccion::with([
            'receta',
            'recetaManual',
            'medicamentos.medicamento',
            'procedimiento',
            'viaParenteral',
        ])->find($idRegistro);

        if (!$registro) {
            return;
        }

        $this->modoEdicion      = true;
        $this->idRegistroEditar = $registro->id;
        $this->idPersona        = (int) $registro->id_persona;
        $this->idEspecialista   = $registro->id_especialista ? (int) $registro->id_especialista : null;

        // 1. Resolver datos del paciente desde NestJS (Fase 1)
        $this->nombreCompletoPaciente = "ASEGURADO SSU #{$registro->id_persona}";
        $this->matriculaPaciente      = 'S/M';
        $this->ciPaciente             = 'S/C';

        try {
            $nestService = app(EnfermeriaNestService::class);
            $dataP = $nestService->obtenerPacientePorId((int) $registro->id_persona);
            if (!empty($dataP) && is_array($dataP)) {
                $this->nombreCompletoPaciente = trim($dataP['nombreCompleto'] ?? $dataP['nombre_completo'] ?? $this->nombreCompletoPaciente);
                $this->ciPaciente             = !empty($dataP['ci']) ? trim((string) $dataP['ci']) : 'S/C';
                $this->matriculaPaciente      = !empty($dataP['matricula']) ? trim((string) $dataP['matricula']) : 'S/M';
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning("Error NestJS al cargar paciente en edición {$registro->id_persona}: " . $e->getMessage());
        }

        // 2. Cargar catálogo de vías parenterales para mapear por nombre desde la observación
        $viasMap = ViaParenteral::all()->keyBy(fn ($v) => mb_strtolower(trim($v->nombre)));
        $viaDefault = $registro->viaParenteral ?: ViaParenteral::find($registro->id_via_parenteral);

        // 3. Cargar según sea Receta de Sistema o Receta Manual
        if (!empty($registro->id_receta)) {
            $this->tipoReceta = 'SISTEMA';
            $receta = $registro->receta ?: Receta::find($registro->id_receta);
            $nroRecetaLimpio = $receta
                ? ltrim(trim((string) $receta->nro_receta), '0')
                : (string) $registro->id_receta;

            $this->nroRecetaBusqueda       = $nroRecetaLimpio;
            $this->recetaSistemaEncontrada = $receta ? $receta->toArray() : ['id' => $registro->id_receta];

            $detallesReceta = RecetaMedicamento::where('id_receta', $registro->id_receta)->get();
            $idsDetallesUsados = [];

            foreach ($registro->medicamentos as $regMed) {
                $medObj = $regMed->medicamento ?: Medicamento::find($regMed->id_medicamento);
                [$nombreViaParsed, $nroRecParsed] = $this->extraerViaYRecetaDeObservacion(
                    (string) $regMed->observacion,
                    $viaDefault ? $viaDefault->nombre : 'Intramuscular',
                    $nroRecetaLimpio
                );
                $viaEncontrada = $viasMap->get(mb_strtolower(trim($nombreViaParsed)));
                $idVia = $viaEncontrada ? (int) $viaEncontrada->id : (int) ($registro->id_via_parenteral ?: 1);

                // Buscar el detalle correspondiente en la receta del sistema
                $detMatch = $detallesReceta->first(function ($d) use ($regMed, $idsDetallesUsados) {
                    return (int) $d->id_medicamento === (int) $regMed->id_medicamento
                        && !in_array($d->id, $idsDetallesUsados);
                });

                if ($detMatch) {
                    $idsDetallesUsados[] = $detMatch->id;
                }

                $itemOriginal = $detMatch ? [
                    'id_detalle'        => $detMatch->id,
                    'id_medicamento'    => (int) $regMed->id_medicamento,
                    'codigo'            => $medObj->codigo_medicamento ?? '',
                    'nombre'            => $medObj->nombre ?? 'Medicamento Inyectable',
                    'cantidad'          => $detMatch->cantidad ?? 1,
                    'via_sugerida'      => $detMatch->forma_adm ?? '',
                    'id_via_parenteral' => '',
                ] : [
                    'id_detalle'        => null,
                    'id_medicamento'    => (int) $regMed->id_medicamento,
                    'codigo'            => $medObj->codigo_medicamento ?? '',
                    'nombre'            => $medObj->nombre ?? 'Medicamento Inyectable',
                    'cantidad'          => 1,
                    'via_sugerida'      => '',
                    'id_via_parenteral' => '',
                ];

                $this->inyeccionesIngresados[] = [
                    'nro_receta'            => $nroRecParsed ?: $nroRecetaLimpio,
                    'id_receta'             => (int) $registro->id_receta,
                    'id_receta_manual'      => null,
                    'id_medicamento'        => (int) $regMed->id_medicamento,
                    'medicamento_nombre'    => $medObj ? $medObj->nombre : 'Medicamento Inyectable',
                    'codigo_medicamento'    => $medObj ? ($medObj->codigo_medicamento ?? '') : '',
                    'id_via_parenteral'     => $idVia,
                    'via_parenteral_nombre' => $viaEncontrada ? $viaEncontrada->nombre : $nombreViaParsed,
                    'cantidad'              => (int) ($itemOriginal['cantidad'] ?? 1),
                    'id_persona'            => (int) $this->idPersona,
                    'id_especialista'       => $this->idEspecialista,
                    'item_receta_original'  => $itemOriginal,
                ];
            }

            // Cargar en la tabla superior SOLO las inyecciones de la receta que NO fueron registradas aún
            $inyectablesPendientes = [];
            foreach ($detallesReceta as $det) {
                if (in_array($det->id, $idsDetallesUsados)) {
                    continue;
                }
                $med = Medicamento::where('id', $det->id_medicamento)
                    ->where(function ($q) {
                        $q->where('forma_farmaceutica', 'ILIKE', '%Inyectable%')
                          ->orWhere('nombre', 'ILIKE', '%Inyectable%');
                    })
                    ->first();

                if (!$med) {
                    continue;
                }

                $inyectablesPendientes[] = [
                    'id_detalle'        => $det->id,
                    'id_medicamento'    => $det->id_medicamento,
                    'codigo'            => $med->codigo_medicamento ?? '',
                    'nombre'            => $med->nombre ?? 'Medicamento Inyectable',
                    'cantidad'          => $det->cantidad ?? 1,
                    'via_sugerida'      => $det->forma_adm ?? '',
                    'id_via_parenteral' => '',
                ];
            }
            $this->medicamentosRecetaSistema = $inyectablesPendientes;
        } else {
            // MODO RECETA MANUAL
            $this->tipoReceta = 'MANUAL';
            $recManual = $registro->recetaManual ?: ($registro->id_receta_manual ? RecetaManual::find($registro->id_receta_manual) : null);

            $this->idRecetaManualEditar = $recManual ? $recManual->id : $registro->id_receta_manual;
            $this->numeroRecetaManual   = $recManual ? (string) ($recManual->numero_receta ?? '') : '';
            $this->fechaEmisionManual   = ($recManual && $recManual->fecha_receta)
                ? $recManual->fecha_receta->format('Y-m-d')
                : optional($registro->created_at)->format('Y-m-d');
            $this->idEspecialista       = (int) ($recManual->id_especialista ?? $registro->id_especialista ?? 0) ?: null;

            // Resolver nombre del médico solicitante desde NestJS
            if ($this->idEspecialista) {
                try {
                    $espData = app(EnfermeriaNestService::class)->obtenerEspecialistaPorId($this->idEspecialista);
                    if (!empty($espData) && is_array($espData)) {
                        $nomEsp = $espData['nombreCompleto'] ?? $espData['nombre'] ?? "ESPECIALISTA #{$this->idEspecialista}";
                        $espRaw = $espData['especialidad'] ?? null;
                        $espNom = is_array($espRaw) ? ($espRaw['nombre'] ?? '') : (is_string($espRaw) ? $espRaw : '');
                        $this->seleccionarEspecialista($this->idEspecialista, (string) $nomEsp, (string) $espNom);
                    } else {
                        $this->nombreEspecialistaSeleccionado = "ESPECIALISTA #{$this->idEspecialista}";
                    }
                } catch (\Throwable $e) {
                    $this->nombreEspecialistaSeleccionado = "ESPECIALISTA #{$this->idEspecialista}";
                }
            }

            foreach ($registro->medicamentos as $regMed) {
                $medObj = $regMed->medicamento ?: Medicamento::find($regMed->id_medicamento);
                [$nombreViaParsed, $nroRecParsed] = $this->extraerViaYRecetaDeObservacion(
                    (string) $regMed->observacion,
                    $viaDefault ? $viaDefault->nombre : 'Intramuscular',
                    $this->numeroRecetaManual ?: 'S/N'
                );
                $viaEncontrada = $viasMap->get(mb_strtolower(trim($nombreViaParsed)));
                $idVia = $viaEncontrada ? (int) $viaEncontrada->id : (int) ($registro->id_via_parenteral ?: 1);

                if ($this->numeroRecetaManual === '' && $nroRecParsed && $nroRecParsed !== 'S/N') {
                    $this->numeroRecetaManual = $nroRecParsed;
                }

                $this->inyeccionesIngresados[] = [
                    'id_receta'             => null,
                    'nro_receta'            => $this->numeroRecetaManual ?: ($nroRecParsed ?: 'S/N'),
                    'id_medicamento'        => (int) $regMed->id_medicamento,
                    'codigo_medicamento'    => $medObj ? ($medObj->codigo_medicamento ?? '') : '',
                    'medicamento_nombre'    => $medObj ? $medObj->nombre : 'Inyectable',
                    'id_via_parenteral'     => $idVia,
                    'via_parenteral_nombre' => $viaEncontrada ? $viaEncontrada->nombre : $nombreViaParsed,
                    'es_manual'             => true,
                    'cantidad'              => 1,
                ];
            }
        }

        // 4. Cargar checklist de procedimientos complementarios
        if ($proc = $registro->procedimiento) {
            $this->curacion_plana      = (bool) $proc->curacion_plana;
            $this->curacion_infectada  = (bool) $proc->curacion_infectada;
            $this->oxigenoterapia      = (bool) $proc->oxigenoterapia;
            $this->retiro_puntos       = (bool) $proc->retiro_puntos;
            $this->prueba_sensibilidad = (bool) $proc->prueba_sensibilidad;
            $this->sangria             = (bool) $proc->sangria;
            $this->signos_vitales      = (bool) $proc->signos_vitales;
            $this->tx_vo               = (bool) $proc->tx_vo;
            $this->orientacion_salud   = (bool) $proc->orientacion_salud;
            $this->vendajes            = (bool) $proc->vendajes;
            $this->nebulizacion        = (bool) $proc->nebulizacion;
            $this->otros               = !empty($proc->otros);
            $this->obs_otros           = (string) ($proc->otros ?? '');

            $this->cargarObservacionesProcedimientos((string) ($proc->observaciones ?? ''));
        }
    }

    private function extraerViaYRecetaDeObservacion(string $observacion, string $viaFallback, string $recetaFallback): array
    {
        $via = $viaFallback;
        $receta = $recetaFallback;

        if (preg_match('/Vía:\s*([^|]+)/i', $observacion, $mVia)) {
            $via = trim($mVia[1]);
        }
        if (preg_match('/Receta:\s*(.+)$/i', $observacion, $mRec)) {
            $receta = trim($mRec[1]);
        }

        return [$via, $receta];
    }

    private function cargarObservacionesProcedimientos(string $observaciones): void
    {
        if (trim($observaciones) === '') {
            return;
        }
        $mapa = [
            'Curación plana'      => 'obs_curacion_plana',
            'Curación infectada'  => 'obs_curacion_infectada',
            'Oxigenoterapia'      => 'obs_oxigenoterapia',
            'Retiro puntos'       => 'obs_retiro_puntos',
            'Prueba sensibilidad' => 'obs_prueba_sensibilidad',
            'Sangría'             => 'obs_sangria',
            'Signos vitales'      => 'obs_signos_vitales',
            'Tx VO'               => 'obs_tx_vo',
            'Orientación salud'   => 'obs_orientacion_salud',
            'Vendajes'            => 'obs_vendajes',
            'Nebulización'        => 'obs_nebulizacion',
        ];
        foreach (explode('|', $observaciones) as $segmento) {
            $partes = explode(':', $segmento, 2);
            if (count($partes) === 2) {
                $etiqueta = trim($partes[0]);
                $valor    = trim($partes[1]);
                if (isset($mapa[$etiqueta])) {
                    $prop = $mapa[$etiqueta];
                    $this->{$prop} = $valor;
                }
            }
        }
    }

        // Se dispara cuando la enfermera teclea en el input
    public function updatedBuscarMedicamento(): void
    {
        if (strlen(trim($this->buscarMedicamento)) >= 2) {
            // IDs de medicamentos ya agregados a la tabla para no repetirlos
            $idsYaAgregados = collect($this->inyeccionesIngresados)->pluck('id_medicamento')->toArray();

            $this->medicamentosFiltrados = Medicamento::query()
                ->where('estado', true)
                ->where(function ($q) {
                    $q->where('forma_farmaceutica', 'ILIKE', '%Inyectable%')
                      ->orWhere('forma_farmaceutica', 'ILIKE', '%Ampolla%')
                      ->orWhere('forma_farmaceutica', 'ILIKE', '%Frasco%');
                })
                ->where(function ($q) {
                    $term = '%' . trim($this->buscarMedicamento) . '%';
                    $q->where('nombre', 'ILIKE', $term)
                      ->orWhere('codigo_medicamento', 'ILIKE', $term);
                })
                ->whereNotIn('id', $idsYaAgregados)
                ->orderBy('nombre', 'asc')
                ->limit(20)
                ->get()
                ->toArray();

            $this->mostrarDropdownMedicamentos = true;
        } else {
            $this->medicamentosFiltrados = [];
            $this->mostrarDropdownMedicamentos = false;
        }
    }

    public function seleccionarMedicamentoInyectable(int $id, string $codigo, string $nombre): void
    {
        $this->idMedicamentoManual = $id;
        $this->nombreMedicamentoSeleccionado = ($codigo ? "{$codigo} - " : "") . $nombre;
        $this->buscarMedicamento = $this->nombreMedicamentoSeleccionado;
        $this->mostrarDropdownMedicamentos = false;
        $this->medicamentosFiltrados = [];
    }

    public function limpiarMedicamentoInyectable(): void
    {
        $this->idMedicamentoManual = null;
        $this->nombreMedicamentoSeleccionado = '';
        $this->buscarMedicamento = '';
        $this->mostrarDropdownMedicamentos = false;
        $this->medicamentosFiltrados = [];
    }

    public function render()
    {
        $nestService = app(\App\Services\EnfermeriaNestService::class);

        // 1. Catálogo de vías parenterales
        $viasParenterales = \App\Models\Enfermeria\ViaParenteral::where('estado', true)
            ->orderBy('nombre')
            ->get();

        // 2. Catálogo de especialistas
        $especialistas = $nestService->listarEspecialistasHabilitados() ?? [];

        // 3. Catálogo de medicamentos (¡AQUÍ RESCATAMOS TU FILTRO!)
        $medicamentosCatalogo = \App\Models\Farmacia\Medicamento::query()
            ->where('estado', true)
            ->where(function ($q) {
                $q->where('forma_farmaceutica', 'ILIKE', '%Inyectable%')
                  ->orWhere('forma_farmaceutica', 'ILIKE', '%Ampolla%')
                  ->orWhere('forma_farmaceutica', 'ILIKE', '%Frasco%');
            })
            ->orderBy('nombre', 'asc')
            ->get(); // <-- Le quitamos el toArray() porque a Blade le gustan más las Collections

        // Envía todo a la vista
        return view('livewire.enfermeria.inyectables.inyectables-register', compact(
            'viasParenterales', 
            'especialistas', 
            'medicamentosCatalogo'
        ));
    }

    // Cambiar tipo de receta (SISTEMA / MANUAL)
    public function setTipoReceta(string $tipo)
    {
        $this->tipoReceta = $tipo;

        // === NUEVO: Limpiar la tabla de inyecciones para que no pasen a la otra pestaña ===
        $this->inyeccionesIngresados = [];
        // ====================================================================================

        // Limpiar los datos compartidos del paciente al cambiar de pestaña
        $this->idPersona              = null;
        $this->nombreCompletoPaciente = '';
        $this->matriculaPaciente      = '';
        $this->ciPaciente             = '';
        
        // Limpiar datos específicos de SISTEMA
        $this->nroRecetaBusqueda         = '';
        $this->recetaSistemaEncontrada   = [];
        $this->medicamentosRecetaSistema = [];

        // Limpiar datos específicos de MANUAL
        $this->limpiarEspecialista();
        $this->limpiarMedicamentoInyectable();
        $this->fechaEmisionManual = null;
        $this->numeroRecetaManual = '';
        $this->cantidadManual     = null;
        $this->idViaParenteralManual = null;
    }
    // --- MODO SISTEMA: BUSCAR RECETA UTILIZANDO TUS MODELOS ELOQUENT ---
    public function buscarRecetaSistema()
    {
        // Normalizar por seguridad en servidor (solo dígitos y sin ceros a la izquierda)
        $this->nroRecetaBusqueda = substr(ltrim(preg_replace('/[^0-9]/', '', trim($this->nroRecetaBusqueda)), '0'), 0, 10);

        $this->validate([
            'nroRecetaBusqueda' => ['required', 'regex:/^[1-9][0-9]*$/'],
        ], [
            'nroRecetaBusqueda.required' => 'Ingrese el número de receta institucional.',
            'nroRecetaBusqueda.regex'    => 'El número de receta debe contener únicamente números y no puede comenzar con 0.',
        ]);

        $termino = $this->nroRecetaBusqueda;

        // 1. LIMPIAR TABLA DE RESULTADOS DE BÚSQUEDA ANTERIOR
        $this->recetaSistemaEncontrada   = [];
        $this->medicamentosRecetaSistema = [];

        if (empty($this->inyeccionesIngresados)) {
            $this->nombreCompletoPaciente = '';
            $this->matriculaPaciente      = '';
            $this->ciPaciente             = '';
            $this->idPersona              = null;
            $this->idEspecialista         = null;
        }

        try {
            // 2. Buscar comparando solo los dígitos después de los ceros a la izquierda (ej: BD '01589' == '1589')
            $receta = \App\Models\ConsultaExterna\Receta::whereRaw(
                "LTRIM(TRIM(nro_receta::text), '0') = ?",
                [(string) $termino]
            )->first();

            if (!$receta) {
                $this->dispatch('alertaError', mensaje: 'No se encontró ninguna receta con el número ingresado.');
                return;
            }

            // === IMPEDIR MEZCLAR PACIENTES ===
            // Si ya hay inyecciones en la lista, verificamos que el id_persona sea el mismo
            if (!empty($this->inyeccionesIngresados)) {
                $idPacienteEnLista = $this->inyeccionesIngresados[0]['id_persona'];

                if ((int)$receta->id_persona !== (int)$idPacienteEnLista) {
                    $this->dispatch('alertaError', mensaje: 'No puede combinar recetas de diferentes pacientes. Guarde o cancele las inyecciones actuales.');
                    // Limpiamos el buscador para evitar confusiones
                    $this->nroRecetaBusqueda = ''; 
                    return; // Abortamos la búsqueda
                }
            }
            // =======================================================

            $this->recetaSistemaEncontrada = $receta->toArray();
            $this->idPersona = $receta->id_persona;
            $this->idEspecialista = $receta->id_especialista;

            // 3. Obtener paciente exclusivamente desde el microservicio NestJS (Etapa 1)
            $this->nombreCompletoPaciente = "ASEGURADO SSU #{$receta->id_persona}";
            $this->matriculaPaciente      = 'S/M';
            $this->ciPaciente             = 'S/C';

            try {
                $nestService = app(EnfermeriaNestService::class);
                $dataP = $nestService->obtenerPacientePorId((int) $receta->id_persona);

                if (!empty($dataP) && is_array($dataP)) {
                    $this->nombreCompletoPaciente = trim($dataP['nombreCompleto'] ?? $dataP['nombre_completo'] ?? "ASEGURADO SSU #{$receta->id_persona}");
                    $this->ciPaciente             = !empty($dataP['ci']) ? trim((string) $dataP['ci']) : 'S/C';
                    $this->matriculaPaciente      = !empty($dataP['matricula']) ? trim((string) $dataP['matricula']) : 'S/M';
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning("Error NestJS al obtener paciente {$receta->id_persona}: " . $e->getMessage());
            }

            // 4. Consultar los detalles de medicamentos de la receta
            $detalles = \App\Models\ConsultaExterna\RecetaMedicamento::where('id_receta', $receta->id)->get();

            if ($detalles->isEmpty()) {
                $this->dispatch('alertaError', mensaje: 'La receta existe pero no tiene medicamentos prescritos.');
                return;
            }

            // === NUEVA VALIDACIÓN: OBTENER LOS DETALLES QUE YA ESTÁN EN LA TABLA INFERIOR ===
            $detallesYaAgregados = collect($this->inyeccionesIngresados)
                ->pluck('item_receta_original.id_detalle')
                ->filter()
                ->toArray();
            // ===================================================================================

            // 5. Mapear filtrando ÚNICAMENTE los medicamentos cuya forma farmacéutica sea "Inyectable"
            $inyectables = [];
            foreach ($detalles as $det) {
                
                // === EVITAR DUPLICADOS ===
                // Si este detalle de receta ya está en la tabla de abajo, lo saltamos
                if (in_array($det->id, $detallesYaAgregados)) {
                    continue;
                }

                $med = \App\Models\Farmacia\Medicamento::where('id', $det->id_medicamento)
                    ->where(function($q) {
                        $q->where('forma_farmaceutica', 'ILIKE', '%Inyectable%')
                          ->orWhere('nombre', 'ILIKE', '%Inyectable%');
                    })
                    ->first();

                // Si no es inyectable, se omite
                if (!$med) {
                    continue;
                }

                $inyectables[] = [
                    'id_detalle'        => $det->id,
                    'id_medicamento'    => $det->id_medicamento,
                    'codigo'            => $med->codigo_medicamento ?? '',
                    'nombre'            => $med->nombre ?? 'Medicamento Inyectable',
                    'cantidad'          => $det->cantidad ?? 1,
                    'via_sugerida'      => $det->forma_adm ?? '',
                    'id_via_parenteral' => '',
                ];
            }

            if (empty($inyectables)) {
                // Si la receta existe pero todos sus inyectables ya están en la lista inferior
                if (!empty($detallesYaAgregados)) {
                    $this->dispatch('alertaError', mensaje: 'Todos los inyectables de esta receta ya fueron agregados a la lista.');
                } else {
                    $this->dispatch('alertaError', mensaje: 'La receta no contiene medicamentos de tipo inyectable.');
                }
                return;
            }

            $this->medicamentosRecetaSistema = $inyectables;

        } catch (\Throwable $th) {
            $this->dispatch('alertaError', mensaje: 'Error al buscar receta: ' . $th->getMessage());
        }
    }

    // Este método se activa al borrar o cambiar "nroRecetaBusqueda"
    public function updatedNroRecetaBusqueda(): void
    {
        // Mantener solo dígitos y eliminar ceros al inicio
        $soloNumeros = preg_replace('/[^0-9]/', '', (string) $this->nroRecetaBusqueda);
        $this->nroRecetaBusqueda = substr(ltrim($soloNumeros, '0'), 0, 10);

        // Si el campo está vacío, limpiamos la información de la receta buscada (conservando el paciente si ya hay inyecciones en la lista)
        if (trim($this->nroRecetaBusqueda) === '') {
            if (empty($this->inyeccionesIngresados)) {
                $this->nombreCompletoPaciente = '';
                $this->matriculaPaciente      = '';
                $this->ciPaciente             = '';
                $this->idPersona              = null;
                $this->idEspecialista         = null;
            }
            
            $this->recetaSistemaEncontrada   = [];
            $this->medicamentosRecetaSistema = [];
        }
    }
   // Se ejecuta automáticamente cuando la enfermera teclea en el buscador
    public function updatedBuscarEspecialista(): void
    {
        if (strlen(trim($this->buscarEspecialista)) >= 2) {
            $service = app(\App\Services\EnfermeriaNestService::class);
            $this->especialistasEncontrados = $service->obtenerEspecialistas($this->buscarEspecialista, 15);
            $this->mostrarDropdownEspecialistas = true;
        } else {
            $this->especialistasEncontrados = [];
            $this->mostrarDropdownEspecialistas = false;
        }
    }

    public function seleccionarEspecialista(int $id, string $nombre, string $especialidad = ''): void
    {
        $this->idEspecialista = $id;
        $this->nombreEspecialistaSeleccionado = $nombre . ($especialidad ? " [{$especialidad}]" : "");
        $this->buscarEspecialista = $this->nombreEspecialistaSeleccionado;
        $this->mostrarDropdownEspecialistas = false;
        $this->especialistasEncontrados = [];
    }

    public function limpiarEspecialista(): void
    {
        $this->idEspecialista = null;
        $this->nombreEspecialistaSeleccionado = '';
        $this->buscarEspecialista = '';
        $this->mostrarDropdownEspecialistas = false;
        $this->especialistasEncontrados = [];
    }
    // Agregar medicamento desde la tabla superior de receta sistema
    public function agregarInyeccionSistema(int $index)
    {
        if (!isset($this->medicamentosRecetaSistema[$index])) {
            return;
        }

        try {
            $this->validate([
                "medicamentosRecetaSistema.{$index}.id_via_parenteral" => 'required|integer',
            ], [
                "medicamentosRecetaSistema.{$index}.id_via_parenteral.required" => 'Debe seleccionar una vía parenteral.',
                "medicamentosRecetaSistema.{$index}.id_via_parenteral.integer"  => 'Seleccione una vía parenteral válida.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('alertaError', mensaje: 'Debe seleccionar una vía parenteral para el medicamento.');
            throw $e;
        }

        $item = $this->medicamentosRecetaSistema[$index];

        // 1. Obtener nombre legible de la vía parenteral
        $via = \App\Models\Enfermeria\ViaParenteral::find($item['id_via_parenteral']);
        $nombreVia = $via ? $via->nombre : '';

        // 2. Extraer ID numérico
        $idRecetaActual = $this->recetaSistemaEncontrada['id'] 
                       ?? $this->recetaSistemaEncontrada['id_receta'] 
                       ?? null;

        $itemOriginal = $item;
        $itemOriginal['id_via_parenteral'] = '';

        // 3. Agregar a la tabla acumulada inferior
        $this->inyeccionesIngresados[] = [
            'nro_receta'            => (string) $this->nroRecetaBusqueda,
            'id_receta'             => $idRecetaActual ? (int) $idRecetaActual : null,
            'id_receta_manual'      => null,
            'id_medicamento'        => (int) $item['id_medicamento'],
            'medicamento_nombre'    => $item['nombre'],
            'codigo_medicamento'    => $item['codigo'] ?? '',
            'id_via_parenteral'     => (int) $item['id_via_parenteral'],
            'via_parenteral_nombre' => $nombreVia,
            'cantidad'              => (int) ($item['cantidad'] ?? 1),
            'id_persona'            => (int) $this->idPersona,
            'id_especialista'       => !empty($this->idEspecialista) ? (int) $this->idEspecialista : null,
            'item_receta_original'  => $itemOriginal, // Esto es CLAVE para poder restaurarlo después
        ];

        // 4. Remover el medicamento seleccionado de la tabla superior para evitar duplicados
        unset($this->medicamentosRecetaSistema[$index]);
        // Reindexar el arreglo para que no queden huecos en los índices de Livewire
        $this->medicamentosRecetaSistema = array_values($this->medicamentosRecetaSistema);
        $this->resetValidation();
    }

   // --- GUARDADO TRANSACCIONAL ATÓMICO ---
    public function guardarAtencion()
    {
        // 1. Validaciones unificadas para activar los bordes rojos (is-invalid) en Blade
        try {
            $reglas = [
                'idPersona'             => 'required|integer',
                'inyeccionesIngresados' => 'required|array|min:1',
            ];
            
            $mensajes = [
                'idPersona.required'             => 'Debe seleccionar un paciente antes de guardar.',
                'idPersona.integer'              => 'Debe seleccionar un paciente válido.',
                'inyeccionesIngresados.required' => 'Debe ingresar al menos una inyección en la lista inferior.',
                'inyeccionesIngresados.min'      => 'Debe ingresar al menos una inyección en la lista inferior.',
            ];

            // Si es receta manual, exigimos médico, número de receta y fecha
            if ($this->tipoReceta === 'MANUAL') {
                $reglas['idEspecialista']     = 'required|integer';
                $reglas['numeroRecetaManual'] = 'required|string';
                $reglas['fechaEmisionManual'] = 'required|date';
                
                $mensajes['idEspecialista.required']     = 'Debe seleccionar el médico solicitante de la receta manual.';
                $mensajes['numeroRecetaManual.required'] = 'El número de receta manual es obligatorio.';
                $mensajes['fechaEmisionManual.required'] = 'La fecha de emisión de la receta manual es obligatoria.';
            }

            $this->validate($reglas, $mensajes);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Lanza la alerta de SweetAlert y luego dispara el error visual en rojo
            $this->dispatch('alertaError', mensaje: collect($e->errors())->first()[0]);
            throw $e; 
        }

        try {
            DB::transaction(function () {
                $idUser = auth()->id() ?? 1;
                $observacionesProc = $this->construirObservacionesProcedimientos();
                $datosProcedimiento = [
                    'curacion_plana'      => $this->curacion_plana,
                    'curacion_infectada'  => $this->curacion_infectada,
                    'oxigenoterapia'      => $this->oxigenoterapia,
                    'retiro_puntos'       => $this->retiro_puntos,
                    'prueba_sensibilidad' => $this->prueba_sensibilidad,
                    'sangria'             => $this->sangria,
                    'signos_vitales'      => $this->signos_vitales,
                    'tx_vo'               => $this->tx_vo,
                    'orientacion_salud'   => $this->orientacion_salud,
                    'vendajes'            => $this->vendajes,
                    'nebulizacion'        => $this->nebulizacion,
                    'otros'               => $this->otros ? $this->obs_otros : null,
                    'observaciones'       => $observacionesProc !== '' ? $observacionesProc : null,
                    'estado'              => true,
                ];

                if ($this->tipoReceta === 'MANUAL') {
                    $primerMed = $this->inyeccionesIngresados[0]['id_medicamento'] ?? null;
                    $primeraInyeccion = $this->inyeccionesIngresados[0];
                    $nroRecetaGuardar = !empty(trim($this->numeroRecetaManual))
                        ? trim($this->numeroRecetaManual)
                        : ($primeraInyeccion['nro_receta'] ?? null);

                    if ($this->modoEdicion && $this->idRegistroEditar) {
                        $registro = RegistroInyeccion::findOrFail($this->idRegistroEditar);
                        $idRecManual = $this->idRecetaManualEditar ?: $registro->id_receta_manual;

                        if ($idRecManual) {
                            RecetaManual::where('id', $idRecManual)->update([
                                'numero_receta'   => $nroRecetaGuardar,
                                'id_persona'      => $this->idPersona,
                                'id_especialista' => $this->idEspecialista,
                                'id_medicamento'  => $primerMed,
                                'fecha_receta'    => $this->fechaEmisionManual,
                                'id_user_updated' => $idUser,
                            ]);
                        } else {
                            $recMan = RecetaManual::create([
                                'numero_receta'   => $nroRecetaGuardar,
                                'id_persona'      => $this->idPersona,
                                'id_especialista' => $this->idEspecialista,
                                'id_medicamento'  => $primerMed,
                                'fecha_receta'    => $this->fechaEmisionManual,
                                'estado'          => true,
                                'id_user_created' => $idUser,
                            ]);
                            $idRecManual = $recMan->id;
                        }

                        $registro->update([
                            'id_persona'        => $this->idPersona,
                            'id_especialista'   => $this->idEspecialista ?? 1,
                            'id_via_parenteral' => $primeraInyeccion['id_via_parenteral'],
                            'id_receta_manual'  => $idRecManual,
                            'id_user_updated'   => $idUser,
                        ]);

                        RegistroInyeccionMedicamento::where('id_registro_inyeccion', $registro->id)->delete();
                        foreach ($this->inyeccionesIngresados as $item) {
                            $nroItem = $nroRecetaGuardar ?: ($item['nro_receta'] ?? 'S/N');
                            RegistroInyeccionMedicamento::create([
                                'id_registro_inyeccion' => $registro->id,
                                'id_medicamento'        => $item['id_medicamento'],
                                'observacion'           => "Vía: {$item['via_parenteral_nombre']} | Receta: {$nroItem}",
                                'estado'                => true,
                                'id_user_created'       => $registro->id_user_created ?? $idUser,
                                'id_user_updated'       => $idUser,
                            ]);
                        }

                        $procExistente = RegistroInyeccionProcedimiento::where('id_registro_inyeccion', $registro->id)->first();
                        if ($procExistente) {
                            $procExistente->update(array_merge($datosProcedimiento, [
                                'id_user_updated' => $idUser,
                            ]));
                        } else {
                            RegistroInyeccionProcedimiento::create(array_merge($datosProcedimiento, [
                                'id_registro_inyeccion' => $registro->id,
                                'id_user_created'       => $idUser,
                            ]));
                        }
                    } else {
                        // Creación de Receta Manual nueva
                        $recMan = RecetaManual::create([
                            'numero_receta'   => $nroRecetaGuardar,
                            'id_persona'      => $this->idPersona,
                            'id_especialista' => $this->idEspecialista,
                            'id_medicamento'  => $primerMed,
                            'fecha_receta'    => $this->fechaEmisionManual,
                            'estado'          => true,
                            'id_user_created' => $idUser,
                        ]);

                        $registro = RegistroInyeccion::create([
                            'id_persona'        => $this->idPersona,
                            'id_especialista'   => $this->idEspecialista ?? 1,
                            'id_via_parenteral' => $primeraInyeccion['id_via_parenteral'],
                            'id_receta'         => null,
                            'id_receta_manual'  => $recMan->id,
                            'estado'            => true,
                            'id_user_created'   => $idUser,
                        ]);

                        foreach ($this->inyeccionesIngresados as $item) {
                            $nroItem = $nroRecetaGuardar ?: ($item['nro_receta'] ?? 'S/N');
                            RegistroInyeccionMedicamento::create([
                                'id_registro_inyeccion' => $registro->id,
                                'id_medicamento'        => $item['id_medicamento'],
                                'observacion'           => "Vía: {$item['via_parenteral_nombre']} | Receta: {$nroItem}",
                                'estado'                => true,
                                'id_user_created'       => $idUser,
                            ]);
                        }

                        RegistroInyeccionProcedimiento::create(array_merge($datosProcedimiento, [
                            'id_registro_inyeccion' => $registro->id,
                            'id_user_created'       => $idUser,
                        ]));
                    }
                } else {
                    // MODO SISTEMA: Agrupar por receta para registrar o actualizar cada receta del paciente con sus inyecciones
                    $gruposPorReceta = collect($this->inyeccionesIngresados)
                        ->groupBy(fn ($item) => (string) ($item['id_receta'] ?? $item['nro_receta']));

                    $registroEditadoProcesado = false;
                    $registroOriginal = ($this->modoEdicion && $this->idRegistroEditar)
                        ? RegistroInyeccion::find($this->idRegistroEditar)
                        : null;

                    foreach ($gruposPorReceta as $claveReceta => $itemsReceta) {
                        $itemsArray = $itemsReceta->values()->all();
                        $primera = $itemsArray[0];
                        $idRecetaGrupo = !empty($primera['id_receta']) ? (int) $primera['id_receta'] : null;
                        $idEspecialistaGrupo = !empty($primera['id_especialista'])
                            ? (int) $primera['id_especialista']
                            : ($this->idEspecialista ?? 1);

                        if (
                            $registroOriginal &&
                            !$registroEditadoProcesado &&
                            ((int) $registroOriginal->id_receta === (int) $idRecetaGrupo || $gruposPorReceta->count() === 1)
                        ) {
                            $registroEditadoProcesado = true;
                            $registroOriginal->update([
                                'id_persona'        => $this->idPersona,
                                'id_especialista'   => $idEspecialistaGrupo,
                                'id_via_parenteral' => $primera['id_via_parenteral'],
                                'id_receta'         => $idRecetaGrupo,
                                'id_user_updated'   => $idUser,
                            ]);

                            RegistroInyeccionMedicamento::where('id_registro_inyeccion', $registroOriginal->id)->delete();
                            foreach ($itemsArray as $item) {
                                RegistroInyeccionMedicamento::create([
                                    'id_registro_inyeccion' => $registroOriginal->id,
                                    'id_medicamento'        => $item['id_medicamento'],
                                    'observacion'           => "Vía: {$item['via_parenteral_nombre']} | Receta: {$item['nro_receta']}",
                                    'estado'                => true,
                                    'id_user_created'       => $registroOriginal->id_user_created ?? $idUser,
                                    'id_user_updated'       => $idUser,
                                ]);
                            }

                            $procExistente = RegistroInyeccionProcedimiento::where('id_registro_inyeccion', $registroOriginal->id)->first();
                            if ($procExistente) {
                                $procExistente->update(array_merge($datosProcedimiento, [
                                    'id_user_updated' => $idUser,
                                ]));
                            } else {
                                RegistroInyeccionProcedimiento::create(array_merge($datosProcedimiento, [
                                    'id_registro_inyeccion' => $registroOriginal->id,
                                    'id_user_created'       => $idUser,
                                ]));
                            }
                        } else {
                            // Crear un RegistroInyeccion independiente por cada receta adicional (o nueva) del paciente
                            $nuevoRegistro = RegistroInyeccion::create([
                                'id_persona'        => $this->idPersona,
                                'id_especialista'   => $idEspecialistaGrupo,
                                'id_via_parenteral' => $primera['id_via_parenteral'],
                                'id_receta'         => $idRecetaGrupo,
                                'id_receta_manual'  => null,
                                'estado'            => true,
                                'id_user_created'   => $idUser,
                            ]);

                            foreach ($itemsArray as $item) {
                                RegistroInyeccionMedicamento::create([
                                    'id_registro_inyeccion' => $nuevoRegistro->id,
                                    'id_medicamento'        => $item['id_medicamento'],
                                    'observacion'           => "Vía: {$item['via_parenteral_nombre']} | Receta: {$item['nro_receta']}",
                                    'estado'                => true,
                                    'id_user_created'       => $idUser,
                                ]);
                            }

                            RegistroInyeccionProcedimiento::create(array_merge($datosProcedimiento, [
                                'id_registro_inyeccion' => $nuevoRegistro->id,
                                'id_user_created'       => $idUser,
                            ]));
                        }
                    }
                }
            });

            $evento = $this->modoEdicion ? 'InyeccionActualizada' : 'InyeccionRegistrada';
            return redirect()->route('enfermeria.inyectables.index')->with('evento', $evento);
        } catch (\Throwable $th) {
            $this->dispatch('alertaError', mensaje: 'Error al registrar la atención: ' . $th->getMessage());
        }
    }

    #[On('limpiarFormularioInyeccion')]
    public function limpiarFormulario(): void
    {
        $this->setTipoReceta('SISTEMA');
        $this->curacion_plana = false;
        $this->obs_curacion_plana = '';
        $this->curacion_infectada = false;
        $this->obs_curacion_infectada = '';
        $this->oxigenoterapia = false;
        $this->obs_oxigenoterapia = '';
        $this->retiro_puntos = false;
        $this->obs_retiro_puntos = '';
        $this->prueba_sensibilidad = false;
        $this->obs_prueba_sensibilidad = '';
        $this->sangria = false;
        $this->obs_sangria = '';
        $this->signos_vitales = false;
        $this->obs_signos_vitales = '';
        $this->tx_vo = false;
        $this->obs_tx_vo = '';
        $this->orientacion_salud = false;
        $this->obs_orientacion_salud = '';
        $this->vendajes = false;
        $this->obs_vendajes = '';
        $this->nebulizacion = false;
        $this->obs_nebulizacion = '';
        $this->otros = false;
        $this->obs_otros = '';
        $this->resetValidation();
    }

    public function abrirModalEspecialistas(): void
    {
        $this->dispatch('modalEspecialistasLista');
    }

    #[On('especialistaSeleccionado')]
    public function especialistaSeleccionado($id = null, $nombre = '', $especialidad = '', $datos = null): void
    {
        if (is_array($id)) {
            $nombre       = $id['nombre'] ?? $nombre;
            $especialidad = $id['especialidad'] ?? $especialidad;
            $id           = $id['id'] ?? null;
        } elseif (is_array($datos)) {
            $id           = $datos['id'] ?? $id;
            $nombre       = $datos['nombre'] ?? $nombre;
            $especialidad = $datos['especialidad'] ?? $especialidad;
        }

        $this->seleccionarEspecialista((int) $id, (string) $nombre, (string) $especialidad);
        $this->dispatch('cerrarModalEspecialistasLista');
    }

    public function abrirModalMedicamentos(): void
    {
        $this->dispatch('modalMedicamentosLista');
    }

    #[On('medicamentoInyectableSeleccionado')]
    public function medicamentoInyectableSeleccionado($id = null, $codigo = '', $nombre = '', $datos = null): void
    {
        if (is_array($id)) {
            $codigo = $id['codigo'] ?? $codigo;
            $nombre = $id['nombre'] ?? $nombre;
            $id     = $id['id'] ?? null;
        } elseif (is_array($datos)) {
            $id     = $datos['id'] ?? $id;
            $codigo = $datos['codigo'] ?? $codigo;
            $nombre = $datos['nombre'] ?? $nombre;
        }

        $idsYaAgregados = collect($this->inyeccionesIngresados)->pluck('id_medicamento')->toArray();
        if (in_array((int) $id, $idsYaAgregados)) {
            $this->dispatch('alertaError', mensaje: 'Este medicamento ya fue agregado a la lista.');
            return;
        }

        $this->seleccionarMedicamentoInyectable((int) $id, (string) $codigo, (string) $nombre);
        $this->dispatch('cerrarModalMedicamentosLista');
    }

  // Método que llama tu botón: wire:click="abrirModalPacientes"

    public function cerrarModalPacientes(): void
    {
        $this->modalBuscarPaciente = false;
        $this->dispatch('cerrarModalPersonasLista');
    }

    // --- MODO MANUAL: MODAL DE PACIENTES (NestJS) ---
    public function abrirModalPacientes()
    {
        $this->dispatch('modalPersonasLista');
    }

    public function updatedTerminoBusquedaPaciente()
    {
        $this->ejecutarBusquedaPacientesModal();
    }

    public function ejecutarBusquedaPacientesModal()
    {
        $nestService = app(EnfermeriaNestService::class);
        $res = $nestService->buscarPacientes($this->terminoBusquedaPaciente, 1, 20);
        $this->listaPacientesModal = $res['data'] ?? $res ?? [];
    }

    public function seleccionarPacienteModal(int $idPersona, string $nombreCompleto, string $ci, string $matricula)
    {
        $this->idPersona = $idPersona;
        $this->nombreCompletoPaciente = $nombreCompleto;
        $this->ciPaciente = $ci;
        $this->matriculaPaciente = $matricula;

        $this->dispatch('cerrarModalAsegurados');
    }

   // Agregar medicamento en modo manual
    public function agregarInyeccionManual()
    {
        try {
            $this->validate([
                'idPersona'             => 'required|integer',
                'idEspecialista'        => 'required|integer',
                'numeroRecetaManual'    => 'required|string', 
                'fechaEmisionManual'    => 'required|date',
                'idMedicamentoManual'   => 'required|integer',
                'idViaParenteralManual' => 'required|integer',
                'cantidadManual'        => 'required|integer|min:1',
            ], [
                'idPersona.required'             => 'Debe buscar y seleccionar un paciente primero.',
                'idEspecialista.required'        => 'Debe seleccionar el médico solicitante.',
                'numeroRecetaManual.required'    => 'El número de receta es obligatorio.', 
                'fechaEmisionManual.required'    => 'La fecha de emisión es obligatoria.',
                'idMedicamentoManual.required'   => 'Seleccione un medicamento inyectable de la lista.',
                'idViaParenteralManual.required' => 'Seleccione una vía parenteral.',
                'cantidadManual.required'        => 'La cantidad debe ser al menos 1.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('alertaError', mensaje: collect($e->errors())->first()[0]);
            throw $e; 
        }

        $med = Medicamento::find($this->idMedicamentoManual);
        $via = ViaParenteral::find($this->idViaParenteralManual);

        $this->inyeccionesIngresados[] = [
            'id_receta'             => null,
            'nro_receta'            => $this->numeroRecetaManual, // Ahora toma el número ingresado
            'id_medicamento'        => $this->idMedicamentoManual,
            'codigo_medicamento'    => $med ? $med->codigo_medicamento : '', 
            'medicamento_nombre'    => $med ? $med->nombre : 'Inyectable',
            'id_via_parenteral'     => $this->idViaParenteralManual,
            'via_parenteral_nombre' => $via ? $via->nombre : 'General',
            'es_manual'             => true,
            'cantidad'              => $this->cantidadManual,
        ];

        // Reset solo de los campos de la inyección para poder seguir agregando medicamentos a la misma receta
        $this->limpiarMedicamentoInyectable(); 
        $this->cantidadManual = null;          
        $this->idViaParenteralManual = null;

        $this->dispatch('alertaExito', mensaje: 'Medicamento agregado a la lista.');
    }
      // Quitar inyección de la lista temporal
    public function quitarInyeccion(int $index)
    {
        if (isset($this->inyeccionesIngresados[$index])) {
            $eliminado = $this->inyeccionesIngresados[$index];

            // Si provenía de la receta del sistema activa, se restaura en la tabla superior
            if (!empty($eliminado['item_receta_original']) && $eliminado['nro_receta'] == $this->nroRecetaBusqueda) {
                $this->medicamentosRecetaSistema[] = $eliminado['item_receta_original'];
            }

            unset($this->inyeccionesIngresados[$index]);
            $this->inyeccionesIngresados = array_values($this->inyeccionesIngresados);
        }
    }


    // Método para abrir el modal desde el botón "BUSCAR PACIENTE"
    public function abrirModalPaciente(): void
    {
        $this->modalBuscarPaciente = true;
    }

    public function cerrarModalPaciente(): void
    {
        $this->modalBuscarPaciente = false;
    }

    // Escuchador que captura la selección realizada en PowerGrid
   #[On('pacienteSeleccionado')]
    public function pacienteSeleccionado($id = null, $nombre_completo = '', $matricula = 'S/M', $ci = 'S/C', $datos = null): void
    {
        // Si viene empaquetado en $id o $datos por compatibilidad
        if (is_array($id)) {
            $nombre_completo = $id['nombre_completo'] ?? $nombre_completo;
            $matricula       = $id['matricula'] ?? $matricula;
            $ci              = $id['ci'] ?? $ci;
            $id              = $id['id'] ?? null;
        } elseif (is_array($datos)) {
            $id              = $datos['id'] ?? $id;
            $nombre_completo = $datos['nombre_completo'] ?? $nombre_completo;
            $matricula       = $datos['matricula'] ?? $matricula;
            $ci              = $datos['ci'] ?? $ci;
        }

        // Asignación a las propiedades del formulario
        $this->idPersona              = $id;
        $this->nombreCompletoPaciente = $nombre_completo;
        $this->matriculaPaciente      = !empty($matricula) ? $matricula : 'S/M';
        $this->ciPaciente             = !empty($ci) ? $ci : 'S/C';

        // Cerrar el modal automáticamente
        $this->modalBuscarPaciente = false;
        $this->dispatch('cerrarModalPersonasLista');
    }
   

    /**
     * Concatena las observaciones del checklist de procedimientos
     */
    private function construirObservacionesProcedimientos(): string
    {
        $obs = [];
        if ($this->curacion_plana && !empty($this->obs_curacion_plana)) $obs[] = "Curación plana: {$this->obs_curacion_plana}";
        if ($this->curacion_infectada && !empty($this->obs_curacion_infectada)) $obs[] = "Curación infectada: {$this->obs_curacion_infectada}";
        if ($this->oxigenoterapia && !empty($this->obs_oxigenoterapia)) $obs[] = "Oxigenoterapia: {$this->obs_oxigenoterapia}";
        if ($this->retiro_puntos && !empty($this->obs_retiro_puntos)) $obs[] = "Retiro puntos: {$this->obs_retiro_puntos}";
        if ($this->prueba_sensibilidad && !empty($this->obs_prueba_sensibilidad)) $obs[] = "Prueba sensibilidad: {$this->obs_prueba_sensibilidad}";
        if ($this->sangria && !empty($this->obs_sangria)) $obs[] = "Sangría: {$this->obs_sangria}";
        if ($this->signos_vitales && !empty($this->obs_signos_vitales)) $obs[] = "Signos vitales: {$this->obs_signos_vitales}";
        if ($this->tx_vo && !empty($this->obs_tx_vo)) $obs[] = "Tx VO: {$this->obs_tx_vo}";
        if ($this->orientacion_salud && !empty($this->obs_orientacion_salud)) $obs[] = "Orientación salud: {$this->obs_orientacion_salud}";
        if ($this->vendajes && !empty($this->obs_vendajes)) $obs[] = "Vendajes: {$this->obs_vendajes}";
        if ($this->nebulizacion && !empty($this->obs_nebulizacion)) $obs[] = "Nebulización: {$this->obs_nebulizacion}";
        if ($this->otros && !empty($this->obs_otros)) $obs[] = "Otros: {$this->obs_otros}";

        return implode(' | ', $obs);
    }

    public function cancelar()
    {
        return redirect()->route('enfermeria.inyectables.index')->with('evento', 'InyeccionCancelada');
    }
    /**
     * Reglas de validación de Livewire
     */
    public function rules()
    {
        $rules = [];

        if ($this->tipoReceta === 'MANUAL') {
            // Reglas cuando se llena el formulario manual
            $rules = [
                'idPersona'             => 'required|integer',
                'idEspecialista'        => 'required|integer',
                'numeroRecetaManual'    => 'nullable|string|max:50',
                'fechaEmisionManual'    => 'required|date',
                
                // Reglas temporales al hacer click en "Agregar" medicamento manual
                'idMedicamentoManual'   => 'required|integer',
                'cantidadManual'        => 'required|integer|min:1',
                'idViaParenteralManual' => 'required|integer',
            ];
        } else {
            // Reglas para el modo sistema
            $rules = [
                'nroRecetaBusqueda' => 'required|integer',
                'idPersona'         => 'required|integer', // Garantizar que se encontró el paciente
            ];
        }

        // Regla general antes de guardar a la BD
        $rules['inyeccionesIngresados'] = 'required|array|min:1';

        return $rules;
    }

    /**
     * Mensajes personalizados para los errores
     */
    public function messages()
    {
        return [
            'idPersona.required'             => 'Debe seleccionar un paciente.',
            'idEspecialista.required'        => 'Debe seleccionar un médico especialista.',
            'fechaEmisionManual.required'    => 'La fecha de emisión es obligatoria.',
            'idMedicamentoManual.required'   => 'Seleccione un medicamento inyectable.',
            'cantidadManual.required'        => 'La cantidad es obligatoria y mayor a 0.',
            'idViaParenteralManual.required' => 'Debe especificar la vía parenteral.',
            'inyeccionesIngresados.required' => 'Debe agregar al menos un medicamento a la lista antes de guardar.',
            'nroRecetaBusqueda.required'     => 'Ingrese un número de receta válido.',
        ];
    }
}