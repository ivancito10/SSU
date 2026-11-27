<?php

namespace App\Livewire\AtencionMedica\MisRecetas;

use App\Models\Plataforma\Especialista;
use App\Models\User;
use App\Services\RecetasAseguradoService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class RecetasTable extends PowerGridComponent
{
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
    public function datasource(): ?Collection
    {
        $id_persona = User::find(auth()->user()->id)->id_persona;
        $response = RecetasAseguradoService::recetasPersona($id_persona);

        if (! $response->successful()) {
            $this->dispatch('mostrarSweetAlert', [
                'tipo' => $this->obtenerIcono($response->status()),
                'titulo' => "Error {$response->status()}",
                'mensaje' => $response->json()['message'] ?? $response->json()['error'] ?? 'Error desconocido',
            ]);

            return collect();
        }

        $datos_servicio = $response->json('data');
        $this->datosEspecialista($datos_servicio);
        $total = count($datos_servicio);

        return collect($datos_servicio)->map(function ($item, $index) use ($total) {
            // Extraemos las relaciones para que el código sea limpio (como en tu ejemplo)
            $especialista = $item['especialista'] ?? null;
            $persona = $especialista['persona_especialista'] ?? null;
            $especialidad = $especialista['especialidad_especialista'] ?? null;
            $tipoAtencion = $item['tipo_consulta_receta'] ?? null;

            // Retornamos un objeto manual EXACTAMENTE como en tu otro ejemplo
            return (object) [
                // El ID es sagrado para PowerGrid, lo sacamos del ID real de la API
                'id' => $item['id'],

                // Tus campos calculados
                'numero_fila' => $total - $index,
                'nro_receta' => $item['nro_receta'] ? $item['nro_receta'] : $item['nro_receta_manual'] ?? 'Sin datos',

                // Aplanamos aquí mismo (esto es lo que evita el error del buscador)
                'especialidad' => $especialidad['especialidad'] ?? 'Sin datos',
                'especialista_full' => $persona
                    ? "{$persona['nombres']} {$persona['p_apellido']} {$persona['s_apellido']}"
                    : 'Sin datos',

                'tipo_consulta_receta' => $tipoAtencion['nombre'] ?? 'Sin datos',

                // Fechas
                'fecha' => isset($item['created_at'])
                    ? Carbon::parse($item['created_at'])->format('d/m/Y')
                    : 'Sin datos',
                'hora' => isset($item['created_at'])
                    ? Carbon::parse($item['created_at'])->format('H:i')
                    : 'Sin datos',
                'fecha_proxima_consulta' => isset($item['fecha_proxima_consulta'])
                    ? Carbon::parse($item['fecha_proxima_consulta'])->format('d/m/Y')
                    : null,
                'fecha_entrega' => isset($item['fecha_entrega'])
                    ? Carbon::parse($item['fecha_entrega'])->format('d-m-Y')
                    : null,
                'fecha_caducidad' => isset($item['fecha_caducidad'])
                    ? Carbon::parse($item['fecha_caducidad'])->format('d-m-Y')
                    : null,
                'fecha_transcripcion' => isset($item['fecha_transcripcion'])
                    ? Carbon::parse($item['fecha_transcripcion'])->format('d/m/Y')
                    : null,
            ];
        });
    }

    private function datosEspecialista(&$recetas) // Agregamos & para modificar el original
    {
        foreach ($recetas as &$receta) {
            $id_especialista = $receta['id_especialista'];

            $datos_especialista = Especialista::select('id', 'grado_academico', 'id_persona', 'id_especialidad')
                ->with([
                    'personaEspecialista:id,nombres,p_apellido,s_apellido',
                    'especialidadEspecialista:id,especialidad,sigla',
                ])
                ->where('id', $id_especialista)
                ->first();

            // Convertimos a array para que sea parte de la estructura JSON
            // Si no hay especialista, ponemos null
            $receta['especialista'] = $datos_especialista ? $datos_especialista->toArray() : null;
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

    public function setUp(): array
    {
        // $this->showCheckBox();
        return [
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    /*
    DATOS DE LA RECETA

nro de reseta
la especialidad
especialista
tipo de atencion
fecha y hora de la receta

*/
    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id') // Mantenlo aquí aunque no esté en columns()
            ->add('numero_fila')
            ->add('nro_receta')
            ->add('especialidad')
            ->add('especialista_full')
            ->add('tipo_consulta_receta')
            ->add('fecha')
            ->add('hora');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'numero_fila')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center'),

            Column::make('NRO. RECETA', 'nro_receta')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->searchable()
                ->sortable(),

            Column::make('ESPECIALIDAD', 'especialidad')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->searchable()
                ->sortable(),

            Column::make('ESPECIALISTA', 'especialista_full')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->searchable()
                ->sortable(),
            Column::make('TIPO DE ATENCION', 'tipo_consulta_receta')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->searchable()
                ->sortable(),
            Column::make('FECHA', 'fecha')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->searchable()
                ->sortable(),
            Column::make('HORA', 'hora')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->searchable()
                ->sortable(),
            Column::action('OPCIONES')
                ->headerAttribute('text-center ', 'font-size:15px;background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center'),
        ];
    }

    public function actions($row): array
    {
        $buttons = [];
        $buttons[] = Button::add('ver')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
            <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
            <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
            </svg>')
            ->class('btn btn-blue')
            ->tooltip('VER RECETA')
            ->dispatchTo('atencion-medica.mis-recetas.recetas-asegurado-main', 'detalle_receta', ['data' => $row]);
        // ->can(allowed: auth()->user()->can('ver.Materialess'));

        return $buttons;
    }
}
