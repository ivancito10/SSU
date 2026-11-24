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
    

    public function mount()
    {
            $this->fechaEmisionManual = null;
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
        $this->validate([
            'nroRecetaBusqueda' => 'required|numeric|min:1', 
        ], [
            'nroRecetaBusqueda.required' => 'Ingrese el número de receta institucional.',
            'nroRecetaBusqueda.numeric'  => 'El número de receta debe contener únicamente números.', 
            'nroRecetaBusqueda.min'      => 'El número de receta debe ser mayor a 0.',
        ]);

        $termino = trim($this->nroRecetaBusqueda);

        // 1. LIMPIAR DATOS ANTERIORES POR SEGURIDAD ANTES DE BUSCAR
        $this->nombreCompletoPaciente    = '';
        $this->matriculaPaciente         = '';
        $this->ciPaciente                = '';
        $this->idPersona                 = null;
        $this->idEspecialista            = null;
        $this->recetaSistemaEncontrada   = [];
        $this->medicamentosRecetaSistema = [];

        try {
            // 2. Buscar estrictamente por el número físico de la receta institucional
            $receta = \App\Models\ConsultaExterna\Receta::where('nro_receta', (string) $termino)->first();

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

            // 3. Obtener paciente con Eloquent (o microservicio de respaldo)
            $persona = \App\Models\Administracion\Persona::find($receta->id_persona);

            if ($persona) {
                $paterno  = $persona->p_apellido ?? '';
                $materno  = $persona->s_apellido ?? '';
                $nombres  = $persona->nombres ?? '';
                $this->nombreCompletoPaciente = trim("{$paterno} {$materno} {$nombres}");
                $this->ciPaciente             = $persona->ci ?? '';
                $this->matriculaPaciente      = $persona->matricula_seguro ?? 'S/M';
            } else {
                try {
                    $token = session('access_token', session('token', ''));
                    $baseUrl = config('services.nestjs.url', 'http://127.0.0.1:3000/api');
                    $res = \Illuminate\Support\Facades\Http::withToken($token)
                        ->timeout(3)
                        ->get("{$baseUrl}/pacientes/{$receta->id_persona}");

                    if ($res->successful() && !empty($res->json())) {
                        $dataP = $res->json();
                        $this->nombreCompletoPaciente = $dataP['nombreCompleto'] ?? $dataP['nombre_completo'] ?? "ASEGURADO #{$receta->id_persona}";
                        $this->ciPaciente             = $dataP['ci'] ?? '';
                        $this->matriculaPaciente      = $dataP['matricula'] ?? 'S/M';
                    } else {
                        $this->nombreCompletoPaciente = "ASEGURADO SSU #{$receta->id_persona}";
                        $this->matriculaPaciente      = 'S/M';
                        $this->ciPaciente             = '';
                    }
                } catch (\Throwable $e) {
                    $this->nombreCompletoPaciente = "ASEGURADO SSU #{$receta->id_persona}";
                    $this->matriculaPaciente      = 'S/M';
                    $this->ciPaciente             = '';
                }
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
                    'via_sugerida'      => $det->forma_adm ?? 'Intramuscular',
                    'id_via_parenteral' => 1, // Por defecto Intramuscular
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
        // Si el campo está vacío, limpiamos la información
        if (trim($this->nroRecetaBusqueda) === '') {
            $this->nombreCompletoPaciente = '';
            $this->matriculaPaciente      = '';
            $this->ciPaciente             = '';
            $this->idPersona              = null;
            $this->idEspecialista         = null;
            
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

        $item = $this->medicamentosRecetaSistema[$index];

        // 1. Obtener nombre legible de la vía parenteral
        $nombreVia = 'Intramuscular';
        $via = \App\Models\Enfermeria\ViaParenteral::find($item['id_via_parenteral']);
        $nombreVia = $via ? $via->nombre : 'Intramuscular';

        // 2. Extraer ID numérico
        $idRecetaActual = $this->recetaSistemaEncontrada['id'] 
                       ?? $this->recetaSistemaEncontrada['id_receta'] 
                       ?? null;

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
            'item_receta_original'  => $item, // Esto es CLAVE para poder restaurarlo después
        ];

        // 4. Remover el medicamento seleccionado de la tabla superior para evitar duplicados
        unset($this->medicamentosRecetaSistema[$index]);
        // Reindexar el arreglo para que no queden huecos en los índices de Livewire
        $this->medicamentosRecetaSistema = array_values($this->medicamentosRecetaSistema);

        // Opcional: Mostrar alerta
        // $this->dispatch('alertaExito', mensaje: 'Inyección agregada a la lista.');
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

            // Si es receta manual, exigimos médico y fecha
            if ($this->tipoReceta === 'MANUAL') {
                $reglas['idEspecialista']     = 'required|integer';
                $reglas['fechaEmisionManual'] = 'required|date';
                
                $mensajes['idEspecialista.required']     = 'Debe seleccionar el médico solicitante de la receta manual.';
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

                // 2. Si es receta manual, registramos en enfermeria.receta_manual
                $idRecetaManual = null;
                if ($this->tipoReceta === 'MANUAL') {
                    $primerMed = $this->inyeccionesIngresados[0]['id_medicamento'] ?? null;

                    $recMan = RecetaManual::create([
                        'id_persona'       => $this->idPersona,
                        'id_especialista'  => $this->idEspecialista,
                        'id_medicamento'   => $primerMed,
                        'fecha_receta'     => $this->fechaEmisionManual,
                        'estado'           => true,
                        'id_user_created'  => $idUser,
                    ]);
                    $idRecetaManual = $recMan->id;
                }

                // 3. Crear cabecera: enfermeria.registro_inyeccion
                $primeraInyeccion = $this->inyeccionesIngresados[0];

                $registro = RegistroInyeccion::create([
                    'id_persona'         => $this->idPersona,
                    'id_especialista'    => $this->idEspecialista ?? 1,
                    'id_via_parenteral'  => $primeraInyeccion['id_via_parenteral'],
                    'id_receta'          => ($this->tipoReceta === 'SISTEMA') ? ($primeraInyeccion['id_receta'] ?? null) : null,
                    'id_receta_manual'   => $idRecetaManual,
                    'estado'             => true,
                    'id_user_created'    => $idUser,
                ]);

                // 4. Detalle N:M: enfermeria.registro_inyeccion_medicamento
                foreach ($this->inyeccionesIngresados as $item) {
                    RegistroInyeccionMedicamento::create([
                        'id_registro_inyeccion' => $registro->id,
                        'id_medicamento'        => $item['id_medicamento'],
                        'observacion'           => "Vía: {$item['via_parenteral_nombre']} | Receta: {$item['nro_receta']}",
                        'estado'                => true,
                        'id_user_created'       => $idUser,
                    ]);
                }

                // 5. Procedimientos complementarios (Checklist)
                $obsArray = [];
                if ($this->curacion_plana && $this->obs_curacion_plana) $obsArray[] = "Curación plana: {$this->obs_curacion_plana}";
                if ($this->curacion_infectada && $this->obs_curacion_infectada) $obsArray[] = "Curación infectada: {$this->obs_curacion_infectada}";
                if ($this->oxigenoterapia && $this->obs_oxigenoterapia) $obsArray[] = "Oxigenoterapia: {$this->obs_oxigenoterapia}";
                if ($this->retiro_puntos && $this->obs_retiro_puntos) $obsArray[] = "Retiro puntos: {$this->obs_retiro_puntos}";

                RegistroInyeccionProcedimiento::create([
                    'id_registro_inyeccion' => $registro->id,
                    'curacion_plana'        => $this->curacion_plana,
                    'curacion_infectada'    => $this->curacion_infectada,
                    'oxigenoterapia'        => $this->oxigenoterapia,
                    'retiro_puntos'         => $this->retiro_puntos,
                    'prueba_sensibilidad'   => $this->prueba_sensibilidad,
                    'sangria'               => $this->sangria,
                    'signos_vitales'        => $this->signos_vitales,
                    'tx_vo'                 => $this->tx_vo,
                    'orientacion_salud'     => $this->orientacion_salud,
                    'vendajes'              => $this->vendajes,
                    'nebulizacion'          => $this->nebulizacion,
                    'otros'                 => $this->otros ? $this->obs_otros : null,
                    'observaciones'         => !empty($obsArray) ? implode(' | ', $obsArray) : null,
                    'estado'                => true,
                    'id_user_created'       => $idUser,
                ]);
            });

            return redirect()->route('enfermeria.inyectables.index')->with('evento', 'InyeccionRegistrada');
        } catch (\Throwable $th) {
            $this->dispatch('alertaError', mensaje: 'Error al registrar la atención: ' . $th->getMessage());
        }
    }

  // Método que llama tu botón: wire:click="abrirModalPacientes"

    public function cerrarModalPacientes(): void
    {
        $this->modalBuscarPaciente = false;
    }

    // --- MODO MANUAL: MODAL DE PACIENTES (NestJS) ---
    public function abrirModalPacientes()
    {
        $this->modalBuscarPaciente = true;
        $this->terminoBusquedaPaciente = '';
        $this->ejecutarBusquedaPacientesModal();
        $this->dispatch('abrirModalAsegurados');
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