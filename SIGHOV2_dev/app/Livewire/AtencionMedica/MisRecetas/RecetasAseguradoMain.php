<?php

namespace App\Livewire\AtencionMedica\MisRecetas;

use App\Models\Plataforma\Especialista;
use Livewire\Component;
use App\Models\User;
use App\Services\RecetasAseguradoService;
use Livewire\Attributes\On;
use Carbon\Carbon;

class RecetasAseguradoMain extends Component
{
    public $nombreCompleto, $matricula;
    public array $recetas = [];
    public bool $modalDetalleReceta = false;
    public $detalle_receta = [];
    public $fecha_proxima_consulta;
    public $receta;
    public $fechaEntrega;
    public $fechaCaducidad;
    // datos para el modal
    public function render()
    {
        return view('livewire.atencion-medica.mis-recetas.recetas-asegurado-main');
    }
    public function mount()
    {
        $this->nombreCompleto = auth()->user()->name;
        $this->matricula = auth()->user()->matricula;
        // $id_persona=User::find(auth()->user()->id)->id_persona;
        // $this->consultarRecetas();
        // dd($this->recetas);
    }
    public function consultarRecetas()
    {
        $id_persona = User::find(auth()->user()->id)->id_persona;
        // dd($id_persona);
        $response = RecetasAseguradoService::recetasPersona($id_persona);
        // dd($response->json());

        if (!$response->successful()) {
            // dd($response->json());
            $this->dispatch('mostrarSweetAlert', [
                'tipo'    => $this->obtenerIcono($response->status()),
                'titulo'  => "Error {$response->status()}",
                'mensaje' => $response->json()['message'] ?? $response->json()['error'] ?? 'Error desconocido',
            ]);
        }
        $datos_servicio = $response->json('data');
        // $this->datosEspecialista($datos_servicio);

        $this->recetas = $datos_servicio;
        // $data = collect($datos_servicio);
        // $this->recetas = $datos_servicio;
        // return $data;
        // $this->consult

    }
    private function datosEspecialista(&$recetas) // Agregamos & para modificar el original
    {
        foreach ($recetas as &$receta) { // Agregamos & aquí también
            $id_especialista = $receta['id_especialista'];

            $datos_especialista = Especialista::select('id', 'grado_academico', 'id_persona', 'id_especialidad')
                ->with([
                    'personaEspecialista:id,nombres,p_apellido,s_apellido',
                    'especialidadEspecialista:id,especialidad,sigla',
                ])
                ->where('id', $id_especialista)
                ->first();

            // Ahora esto sí afectará al array que se retornará después
            $receta['especialista'] = $datos_especialista->toArray();
        }
    }
    // Función auxiliar para cambiar el ícono según el código
    private function obtenerIcono($status)
    {
        return match (true) {
            $status >= 500 => 'error',     // Error de servidor
            $status === 404 => 'info',     // No encontrado
            $status === 403 => 'warning',  // No autorizado
            default => 'error',
        };
    }
    /* 
    VER DETALLES

medicamento
forma famaceui9tca
la cantidad
la concentracion
forma de administracion
y la fecha fin del tratamiento
fecha de entrega
    */
    #[On('detalle_receta')]
    public function detalle_receta($data)
    {
        // 1. RESETEO CRÍTICO: Limpiamos la propiedad del componente y cerramos el modal 
        // por si acaso hay un delay en la red.
        $id_receta = $data['id'];
        $this->receta = (object) $data;
        // dd($data);
        $fecha_proxima_consulta = isset($data['fecha_proxima_consulta'])
            ? Carbon::parse($data['fecha_proxima_consulta'])->translatedFormat('d \d\e F \d\e Y')
            : null;
        $this->fechaEntrega = isset($data['fecha_entrega'])
            ? Carbon::parse($data['fecha_entrega'])->translatedFormat('d \d\e F \d\e Y')
            : null;
        $this->fechaCaducidad = isset($data['fecha_caducidad'])
            ? Carbon::parse($data['fecha_caducidad'])->translatedFormat('d \d\e F \d\e Y')
            : null;
        $this->fecha_proxima_consulta = $fecha_proxima_consulta;
        // dd($fecha_proxima_consulta);
        $this->detalle_receta = [];
        $this->modalDetalleReceta = false;

        $detalle_recetas_local = []; // Variable temporal interna

        $response = RecetasAseguradoService::detalleRecetas($id_receta);

        if (!$response->successful()) {
            $this->dispatch('mostrarSweetAlert', [
                'tipo'    => $this->obtenerIcono($response->status()),
                'titulo'  => "Error {$response->status()}",
                'mensaje' => $response->json()['message'] ?? $response->json()['error'] ?? 'Error desconocido',
            ]);
            return; // Salimos para no abrir el modal con datos viejos o vacíos
        }

        $recetas_api = $response->json('data');
        // dd($recetas_api);
        foreach ($recetas_api as $item) {
            $med = $item['medicamento_receta_medicamento'] ?? null;

            // Configuramos Carbon en español para esta ejecución
            Carbon::setLocale('es');

            // Procesamos las fechas si existen, si no, dejamos el 'Sin dato'
            $fecha_fin = isset($item['fecha_fin'])
                ? Carbon::parse($item['fecha_fin'])->translatedFormat('d \d\e F \d\e Y')
                : 'Sin dato';

            $fecha_ini = isset($item['fecha_ini'])
                ? Carbon::parse($item['fecha_ini'])->translatedFormat('d \d\e F \d\e Y')
                : 'Sin dato';

            $datos_detalle = [
                'medicamento'           => $med['nombre'] ?? 'Sin dato',
                'forma_famacetica'      => $med['forma_farmaceutica'] ?? 'Sin dato',
                'concentracion'         => $med['concentracion'] ?? 'Sin dato',
                'cantidad'              => $item['cantidad'] ?? '0',
                'forma_administracion'  => $item['forma_adm'] ?? 'Sin dato',
                'indicaciones'          => $item['indicaciones'] ?? 'Sin dato',
                'recomendacion'         => $item['recomendacion'] ?? 'Sin dato',
                'fecha_ini'         => $fecha_ini,
                'fecha_fin'         => $fecha_fin,
                // 'fecha_proxima_consulta' => $fecha_proxima_consulta ?? null
            ];

            $detalle_recetas_local[] = (object) $datos_detalle;
        }

        // 2. ASIGNACIÓN FINAL: Solo ahora llenamos la propiedad con los datos frescos
        $this->detalle_receta = $detalle_recetas_local;
        // dd($this->detalle_receta);
        $this->modalDetalleReceta = true;

        // Disparamos el JS para abrir el modal en el navegador
        $this->dispatch('modalDetalleReceta');
    }
    public function cerrarModal()
    {
        $this->modalDetalleReceta = false;
        $this->detalle_receta = []; // Limpiamos el array
    }
}
