<?php

namespace App\Livewire\Plataforma\SuspencionVacacion;

use App\Models\Plataforma\Especialista;
use App\Models\Plataforma\SuspensionVacacion;
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
use Illuminate\Support\Facades\DB;


final class EspecialistasSuspencionTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'VacacionEspecialistaTable';
    public string $sortField = 'especialista.id';
    public string $sortDirection = 'desc';

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
        // Subconsulta para obtener el último registro de suspensión y su estado por especialista
        $subQuery = DB::table('plataforma.suspension_vacacion as suspencion')
            ->select('suspencion.id_especialista', DB::raw('MAX(suspencion.id) as id_vacain'), 'suspencion.estado')
            ->whereColumn('suspencion.id', '=', DB::raw('(SELECT MAX(id) FROM plataforma.suspension_vacacion WHERE id_especialista = suspencion.id_especialista)'))
            ->groupBy('suspencion.id_especialista', 'suspencion.estado');

        return Especialista::query()
            ->join('administracion.persona as persona', function ($join) {
                $join->on('persona.id', '=', 'especialista.id_persona');
            })
            ->leftJoinSub($subQuery, 'suspencion', function ($join) {
                $join->on('suspencion.id_especialista', '=', 'especialista.id');
            })
            ->join('administracion.especialidad as especialidad', function ($especialidad) {
                $especialidad->on('especialidad.id', '=', 'especialista.id_especialidad');
            })
            ->select(
                'especialista.id',
                'persona.nombres as persona_nombres',
                'persona.p_apellido as persona_p_apellido',
                'persona.s_apellido as persona_s_apellido',
                'suspencion.id_vacain',
                'suspencion.estado as estado_suspencion',
                'especialidad.especialidad as nombre_especialidad',
                'especialista.grado_academico',
                'especialista.permanente'
            )
            ->orderBy($this->sortField, $this->sortDirection); // Ordenamiento dinámico
    }

    public function relationSearch(): array
    {
        return [
            'personaEspecialista' => ['nombres', 'p_apellido', 's_apellido'],
            'especialidadEspecialista' => ['especialidad'],
            'suspensionVacacionEspecialista' => ['estado'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('id')
            ->add('fecha_contrato_inicio_formatted', fn(Especialista $model) => Carbon::parse($model->fecha_contrato_inicio)->format('d/m/Y'))
            ->add('fecha_contrato_fin_formatted', fn(Especialista $model) => Carbon::parse($model->fecha_contrato_fin)->format('d/m/Y'))
            ->add('foto')
            ->add('permanente', function ($estadoReq) {
                if ($estadoReq->permanente) {
                    return "<span>PERMANENTE</span>";
                } else {
                    return "<span>EVENTUAL</span>";
                }
            })
            ->add('id_especialidad')
            ->add('nombre_especialidad')
            ->add('id_persona')
            // ->add('persona_nombre_completo', fn(Especialista $model) => $model->personaEspecialista->p_apellido . ' ' . $model->personaEspecialista->s_apellido. ' ' . $model->personaEspecialista->nombres)
            ->add('persona_nombre_completo', function ($nombrePersona) {
                if ($nombrePersona->persona_p_apellido == null) {
                    return $nombrePersona->persona_s_apellido . ' ' . $nombrePersona->persona_nombres;
                } elseif ($nombrePersona->persona_s_apellido == null) {
                    return $nombrePersona->persona_p_apellido . ' ' . $nombrePersona->persona_nombres;
                } else {
                    return $nombrePersona->persona_p_apellido . ' ' . $nombrePersona->persona_s_apellido . ' ' . $nombrePersona->persona_nombres;
                }
            })
            ->add('estado_suspencion')
            ->add('vacacion', function ($suspension) {
                if ($suspension->estado_suspencion == 'VACACION ACTIVA') {
                    return '<span style="color:#1e8449  "> CON VACACIÓN  </span>';
                }
                if ($suspension->estado_suspencion == 'VACACION VENCIDA') {
                    return ' <span style="color: #ba4a00   ">SIN VACACIÓN  </span>';
                }
                if ($suspension->estado_suspencion == 'VACACION ANULADA') {
                    return '<span style="color:#e74c3c ">ANULADA  </span>';
                } else {
                    return ' <span style="color: #ba4a00   ">SIN VACACIÓN  </span>';
                }
            })
            ->add('grado_academico')
            ->add('id_user_created')
            ->add('id_user_updated')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')->index()
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center'),

            Column::make('ESPECIALISTA', 'persona_nombre_completo', 'persona_p_apellido')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->sortable()
                ->searchable(),


            Column::make('ESPECIALIDAD', 'nombre_especialidad')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('GRADO ACADÉMICO', 'grado_academico')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),
            //  Column::make('TIPO DE CONTRATO', 'permanente')
            //      ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
            //      ->bodyAttribute('text-center')
            //      ->sortable()
            //      ->searchable(),

            Column::make('Tipo Contrato', 'permanente')
                ->sortable()
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->searchable(function ($query, $search) {
                    $search = strtoupper($search);
                    if (str_contains($search, '%PERMANENTE%')) {
                        $query->where('especialista.permanente', true);
                    } elseif (str_contains($search, '%EVENTUAL%')) {
                        $query->where('especialista.permanente', false);
                    }
                }),


            Column::make('ESTADO', 'vacacion', 'estado_suspencion')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::action('OPCIONES')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')

        ];
    }

    public function filters(): array
    {
        return [];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }



    public function actions(Especialista $row): array
    {
        $buttons = [];

        // Obtener el estado de la vacación más reciente cargado con with
        $suspension = $row->suspensionVacacionEspecialista()->orderByDesc('id')->first();

        // Botón 'ver' para mostrar las vacaciones
        $buttons[] = Button::add('ver')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                </svg>')
            ->class('btn btn-blue')
            ->tooltip('VER VACACIONES')
            ->dispatchTo('plataforma.suspencion-vacacion.suspencion-vacacion-main', 'verVacaciones', ['data' => $row->id])
            ->can(allowed: auth()->user()->can('ver.suspension_vacaciones'));

        // Verificar si hay una vacación y su estado
        if ($suspension) {
            $estado_suspencion = $suspension->estado;

            // Si el estado de suspensión es 'VACACION ACTIVA', añade botón 'agregar'
            if ($estado_suspencion === 'VACACION ACTIVA') {

                $buttons[] = Button::add('editar')
                    ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                    </svg>')
                    ->class('btn btn-orange')
                    ->tooltip('EDITAR VACACIÓN')
                    ->dispatchTo('plataforma.suspencion-vacacion.suspencion-vacacion-main', 'editarVacacion', ['data' => $row->id])
                    ->can(allowed: auth()->user()->can('desabilitar.suspension_vacaciones'));

                $buttons[] = Button::add('anular')
                    ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                    <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                    </svg>')
                    ->class('btn btn-red')
                    ->tooltip('ANULAR VACACIÓN')
                    ->dispatchTo('plataforma.suspencion-vacacion.suspencion-vacacion-main', 'AnularSuspencion', ['data' => $row->id])
                    ->can(allowed: auth()->user()->can('desabilitar.suspension_vacaciones'));
            }
            if ($estado_suspencion === 'VACACION VENCIDA') {
                $buttons[] = Button::add('agregar')
                    ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z"/>
</svg>')
                    ->class('btn btn-green')
                    ->tooltip('AGREGAR VACACIÓN')
                    ->dispatchTo('plataforma.suspencion-vacacion.suspencion-vacacion-main', 'AgregarSuspencion', ['data' => $row->id])
                    ->can(allowed: auth()->user()->can('registrar.suspension_vacaciones'));
            }
            if ($estado_suspencion === 'VACACION ANULADA') {
                $buttons[] = Button::add('agregar')
                    ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z"/>
</svg>')
                    ->class('btn btn-green')
                    ->tooltip('AGREGAR VACACIÓN')
                    ->dispatchTo('plataforma.suspencion-vacacion.suspencion-vacacion-main', 'AgregarSuspencion', ['data' => $row->id])
                    ->can(allowed: auth()->user()->can('registrar.suspension_vacaciones'));
            }
        } else {
            $buttons[] = Button::add('agregar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z"/>
</svg>')
                ->class('btn btn-green')
                ->tooltip('AGREGAR VACACIÓN')
                ->dispatchTo('plataforma.suspencion-vacacion.suspencion-vacacion-main', 'AgregarSuspencion', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('registrar.suspension_vacaciones'));
        }

        return $buttons;
    }
}
