<?php

namespace App\Livewire\Plataforma\Especialista\EspecialistaServicio;

use App\Models\Plataforma\EspecialistaHabilitadoServicio;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use Livewire\Attributes\On;

final class VistaServicioEspecialistaTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'VistaServiciosTable';
    public string $sortField = 'especialista_habilitado_servicio.id';
    public string $sortDirection = 'desc';
    public int $idEspecialista = 0;

    #[On('idEspe')]
    public function idEspe($title)
    {
        $this->idEspecialista = $title;
    }

    public function setUp(): array
    {
        return [
            Exportable::make('export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return EspecialistaHabilitadoServicio::query()
            ->join('plataforma.dia as dia', function ($dia) {
                $dia->on('especialista_habilitado_servicio.id_dia', '=', 'dia.id');
            })
            ->join('plataforma.servicios_plataforma as serv', function ($serv) {
                $serv->on('especialista_habilitado_servicio.id_servicio', '=', 'serv.id');
            })
            ->where('id_especialista', $this->idEspecialista)
            ->select(
                'especialista_habilitado_servicio.*',
                'dia.nombre as dia_nombre',
                'serv.nombre_servicio as serv_nombres',
            );
    }

    public function relationSearch(): array
    {
        return [
            'diaEspecialistaHabilitadoServicio' => ['nombre'],
            'servicioEspecialistaHabilitadoServicio' => ['nombre_servicio']
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('id')
            ->add('fecha_inicio')
            ->add('fecha_inicio_formatted', function ($fechaini) {

                if ($fechaini->fecha_inicio === null) {
                    return 'PERMANENTE';
                } else {
                    return Carbon::parse($fechaini->fecha_inicio)->format('d/m/Y');
                }
            })
            ->add('fecha_fin')
            ->add('fecha_fin_formatted', function ($fechafin) {

                if ($fechafin->fecha_fin === null) {
                    return 'PERMANENTE';
                } else {
                    return Carbon::parse($fechafin->fecha_fin)->format('d/m/Y');
                }
            })
            ->add('permanente')
            ->add('permanente_formatted', function ($perm) {
                if ($perm->permanente) {
                    return 'SI';
                } else {
                    return 'NO';
                }
            })
            ->add('estado')
            ->add('estado_formatted', function ($estado) {
                if ($estado->estado) {
                    return '<span style="color: green" title="HABILITADO"> <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                </svg>
                </span>';
                } else {
                    return '<span style="color: #c0392b" title="DESHABILITADO">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                    </svg>
                    </span>';
                }
            })
            ->add('id_especialista')
            ->add('id_servicio')
            ->add('id_dia')
            ->add('id_user_created')
            ->add('id_user_updated')
            ->add('created_at');
    }

    public function columns(): array
    {

        return [
            Column::make('#', 'id')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->index()
                ->sortable()
                ->searchable(),


            Column::make('FECHA INICIO', 'fecha_inicio_formatted', 'fecha_inicio')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->sortable()
                ->searchable(function ($query, $buscaDate) {
                    try {
                        $buscaDate = Carbon::createFromFormat('d/m/Y', $buscaDate)->format('Y-m-d');
                        $query->whereDate('especialista_habilitado_servicio.fecha_inicio', $buscaDate);
                    } catch (\Exception $e) {
                        $query->where('especialista_habilitado_servicio.fecha_inicio', 'like', "%{$buscaDate}%");
                    }
                }),

            Column::make('FECHA FIN', 'fecha_fin_formatted', 'fecha_fin')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->sortable()
                ->searchable(function ($query, $buscaDate) {
                    try {
                        $buscaDate = Carbon::createFromFormat('d/m/Y', $buscaDate)->format('Y-m-d');
                        $query->whereDate('especialista_habilitado_servicio.fecha_fin', $buscaDate);
                    } catch (\Exception $e) {
                        $query->where('especialista_habilitado_servicio.fecha_fin', 'like', "%{$buscaDate}%");
                    }
                }),


            /*Column::make('PERMANENTE', 'permanente_formatted', 'permanente')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),*/

            Column::make('SERVICIO', 'serv_nombres')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('DIA', 'dia_nombre')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('ESTADO', 'estado_formatted', 'estado')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),


            Column::action('OPCIONES')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
        ];
    }

    public function actions(EspecialistaHabilitadoServicio $row): array
    {
        $buttons = [];

        if ($row->estado === true) {
            $buttons[] = Button::add('deshabilitar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                        <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                        </svg>')
                ->class('btn btn-red')
                ->tooltip('DESHABILITAR')
                ->dispatchTo('plataforma.especialista.especialista-servicio.especialista-servicio-main', 'cambiarEstadoServicio', ['data' => $row->id]);
        } else {
            $buttons[] = Button::add('habilitar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                        <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z"/>
                        </svg>')
                ->class('btn btn-green')
                ->tooltip('HABILITAR')
                ->dispatchTo('plataforma.especialista.especialista-servicio.especialista-servicio-main', 'cambiarEstadoServicio', ['data' => $row->id]);
        }

        return $buttons;
    }
}
