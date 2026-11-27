<?php

namespace App\Livewire\Afiliacion\EstudiantesSsue;

use App\Models\Afiliacion\Estudiante;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class EstudiantesTable extends PowerGridComponent
{
    public string $sortField = 'estudiante.id';

    public string $tableName = 'EstudianteTable';

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
        //return Estudiante::query();
        //  public function datasource(): Collection
        //  {

        /*      $estudiantes = Estudiante::query()
            ->leftJoin('administracion.persona as p', 'estudiante.id_persona', '=', 'p.id')
            ->leftJoin('afiliacion.carrera as c', 'estudiante.id_carrera', '=', 'c.id')
            //            ->where('estudiante.estado', true)
            ->select(
                'estudiante.*',
                'p.nombres as p_nombres',
                'p.p_apellido as p_p_apellido',
                'p.s_apellido as p_s_apellido',
                'p.ci as p_ci',
                'p.complemento as p_complemento',
                'p.matricula_seguro as p_matricula_seguro',
                'c.nombre as nombre_carrera'
            )
            ->orderBy('updated_at', 'desc')
            ->get();

 */
        $sub = Estudiante::selectRaw('id_persona, MAX(id) as max_id')
            ->groupBy('id_persona');

        return Estudiante::query()
            ->joinSub($sub, 'ultimos', function ($join) {
                $join->on('estudiante.id', '=', 'ultimos.max_id');
            })
            ->leftJoin('administracion.persona as p', 'estudiante.id_persona', '=', 'p.id')
            ->leftJoin('afiliacion.carrera as c', 'estudiante.id_carrera', '=', 'c.id')
            ->select(
                'estudiante.*',
                'p.nombres as p_nombres',
                'p.p_apellido as p_p_apellido',
                'p.s_apellido as p_s_apellido',
                'p.ci as p_ci',
                'p.complemento as p_complemento',
                'p.matricula_seguro as p_matricula_seguro',
                'c.nombre as nombre_carrera'
            );
        //  ->get();

        /*     $estudiantes->map(function ($estudiante) {
            //$estudiante->fecha_afiliacion_formatted = Carbon::parse($estudiante->fecha_afiliacion)->format('d/m/Y');
            $fecha_solicitud = $estudiante->fecha_solicitud_afiliacion;
            if ($fecha_solicitud) {
                $estudiante->fecha_solicitud_afiliacion_formatted = mb_strtoupper(Carbon::parse($estudiante->fecha_solicitud_afiliacion)->translatedFormat('j \\de F \\de Y'));
            } else {
                $estudiante->fecha_solicitud_afiliacion_formatted = 'SIN DATO';
            }

            $estudiante->persona_nombre_completo = $estudiante->p_p_apellido . ' ' . $estudiante->p_s_apellido . ' ' . $estudiante->p_nombres;
            //$estudiante->fecha_reafiliacion_formatted = Carbon::parse($estudiante->fecha_reafiliacion)->format('d/m/Y');
            return $estudiante;
        });
        return $estudiantes; */
    }

    public function relationSearch(): array
    {
        return [
            'personaEstudiante' => ['nombres', 'p_apellido', 's_apellido', 'ci', 'matricula_seguro'],
            'carreraEstudiante' => ['nombre'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('id_persona')
            ->add(
                'ci_formatted',
                function ($carnet) {
                    if ($carnet->p_complemento == null) {
                        return $carnet->p_ci;
                    } else {
                        return $carnet->p_ci . '-' . $carnet->p_complemento;
                    }
                }
            )
            ->add('id_carrera')
            ->add('semestre')
            ->add('persona_nombre_completo', function ($estudiante) {
                return $estudiante->p_p_apellido . ' ' . $estudiante->p_s_apellido . ' ' . $estudiante->p_nombres;
            })
            ->add('registro_universitario')
            ->add('gestion')
            ->add('foto_nombre')
            ->add('observacion')
            ->add('fecha_solicitud_afiliacion_formatted', function ($estudiante) {
                if ($estudiante->fecha_solicitud_afiliacion) {
                    return mb_strtoupper(Carbon::parse($estudiante->fecha_solicitud_afiliacion)->format('d/m/Y'));
                } else {
                    return 'SIN DATO';
                }
            })
            // ->add('fecha_afiliacion_formatted', fn(Estudiante $model) => Carbon::parse($model->fecha_afiliacion)->format('d/m/Y'))

            // ->add('fecha_solicitud_afiliacion', function ($estudiante) {
            //     if ($estudiante->fecha_solicitud_afiliacion) {
            //         return Carbon::parse($estudiante->fecha_solicitud_afiliacion)->translatedFormat('j \\de F \\de Y');
            //     } else {
            //         return 'SIN DATO';
            //     }
            // })

            //->add('fecha_solicitud_afiliacion_formatted', fn (Estudiante $model) => mb_strtoupper(Carbon::parse($model->fecha_solicitud_afiliacion)->translatedFormat('j \\de F \\de Y')))
            ->add('alergias')
            ->add('telefono_referencia')
            ->add('nombre_referencia')
            ->add('grupo_sanguineo')
            ->add('estado_validar', function ($dish) {
                if ($dish->estado_validar == 'AFILIADO') {
                    return '<span style="color: green" > 
                   AFILIADO';
                }
                if ($dish->estado_validar == 'PENDIENTE') {
                    return '<span style="color: #f39a19" > 
                  PENDIENTE';
                }
                if ($dish->estado_validar == 'OBSERVADO') {
                    return '<span style="color: #e12217"> 
                OBSERVADO';
                }
                if ($dish->estado_validar == 'VALIDADO') {
                    return '<span style="color: green" > 
                  VALIDADO';
                }
            })
            ->add('telefono')
            ->add('correo_institucional')
            ->add('fecha_reafiliacion_formatted', fn(Estudiante $model) => Carbon::parse($model->fecha_reafiliacion)->format('d/m/Y'))
            ->add('estado')
            ->add('estado_vigencia', function ($dish) {
                if ($dish->estado_vigencia == true) {
                    return '<span style="color: #27ae60" title="VIGENTE">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                    </svg>
                    </span>';
                } else {
                    return '<span style="color: #e74c3c" title="NO VIGENTE"> 
                  <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                </svg> </span>';
                }
            })
            ->add('afiliacion')
            ->add('id_residencia')
            ->add('id_user_created')
            ->add('id_user_updated')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')->index()
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center'),

            Column::make('NOMBRE COMPLETO', 'persona_nombre_completo')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                // ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('C.I.', 'ci_formatted', 'p_ci')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('MATRICULA', 'p_matricula_seguro')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('CARRERA', 'nombre_carrera')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('R. U.', 'registro_universitario')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('GESTIÓN', 'gestion')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('FECHA SOL. AFILIACIÓN', 'fecha_solicitud_afiliacion_formatted', 'fecha_solicitud_afiliacion')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                //->sortable()
                ->searchable(),

            Column::make('VIGENCIA', 'estado_vigencia')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('VALIDACIÓN', 'estado_validar')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::action('OPCIONES')
                ->headerAttribute('text-center tablita-cabecera-sin-mover', 'font-size:15px; background-color:#2471a3; color:white;')
                ->bodyAttribute('text-left tablita-cuerpo-sin-mover '),
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

    public function actions(Estudiante $row): array
    {
        $buttons = [];
        $buttons[] = Button::add('ver')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                </svg>')
            ->class('btn btn-blue')
            ->tooltip('VER ESTUDIANTE')
            ->dispatchTo('afiliacion.estudiantes-ssue.estudiantes-main', 'verEstudiante', ['data' => $row->id])
            ->can(allowed: auth()->user()->can('ver.afiliacion_estudiantes_ssue'));
        if ($row->estado_validar == 'PENDIENTE') {

            $buttons[] = Button::add('habilitar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
  <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z"/>
</svg>')
                ->class('btn btn-green')
                ->tooltip('VALIDAR ESTUDIANTE')
                ->dispatchTo('afiliacion.estudiantes-ssue.estudiantes-main', 'habilitarEstudiante', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('afiliar.afiliacion_estudiantes_ssue'));

            $buttons[] = Button::add('observacion')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-question-circle" viewBox="0 0 16 16">
  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
  <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286m1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94"/>
</svg>')
                ->class('btn btn-orange')
                ->tooltip('AGREGAR OBSERVACIÓN')
                ->dispatchTo('afiliacion.estudiantes-ssue.estudiantes-main', 'agregarObservacion', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('observar.afiliacion_estudiantes_ssue'));
        }

        if ($row->estado_validar == 'OBSERVADO') {
            $buttons[] = Button::add('habilitar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
  <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z"/>
</svg>')
                ->class('btn btn-green')
                ->tooltip('VALIDAR ESTUDIANTE')
                ->dispatchTo('afiliacion.estudiantes-ssue.estudiantes-main', 'habilitarEstudiante', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('afiliar.afiliacion_estudiantes_ssue'));

            $buttons[] = Button::add('observacion')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-question-circle" viewBox="0 0 16 16">
  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
  <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286m1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94"/>
</svg>')
                ->class('btn btn-orange')
                ->tooltip('AGREGAR OBSERVACIÓN')
                ->dispatchTo('afiliacion.estudiantes-ssue.estudiantes-main', 'agregarObservacion', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('observar.afiliacion_estudiantes_ssue'));
        }

        if ($row->estado_validar == 'AFILIADO') {
            $buttons[] = Button::add('carnetizacion')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-credit-card-2-back" viewBox="0 0 16 16">
                  <path d="M11 5.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5z"/>
                  <path d="M2 2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2zm13 2v5H1V4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1m-1 9H2a1 1 0 0 1-1-1v-1h14v1a1 1 0 0 1-1 1"/>
                  </svg>')
                ->class('btn btn-green')
                ->tooltip('CARNET')
                ->dispatchTo('afiliacion.estudiantes-ssue.estudiantes-main', 'carnetizacion', ['data' => $row->id])
                //->can(allowed: auth()->user()->can('carntizacion.estudiantes_ssue'));
                ->can(allowed: auth()->user()->can('super-admin-only'));
        }
        if ($row->estado_validar == 'VALIDADO') {
            $buttons[] = Button::add('dar_vigencia')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
<path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
</svg>')
                ->class('btn btn-blue')
                ->tooltip('DAR VIGENCIA')
                ->dispatchTo('afiliacion.estudiantes-ssue.estudiantes-main', 'darVigencia', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('vigencia.estudiantes_ssue'));

            $buttons[] = Button::add('observacion')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-question-circle" viewBox="0 0 16 16">
  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
  <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286m1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94"/>
</svg>')
                ->class('btn btn-orange')
                ->tooltip('AGREGAR OBSERVACIÓN')
                ->dispatchTo('afiliacion.estudiantes-ssue.estudiantes-main', 'agregarObservacion', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('observar.afiliacion_estudiantes_ssue'));
        }
        // PARA REAFILIAR
        //  if ($row->estado_validar == 'AFILIADO' || $row->estado_validar == 'VALIDADO') {
        $buttons[] = Button::add('reafiliar')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-plus-fill" viewBox="0 0 16 16">
                      <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                      <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5"/>
                    </svg>')
            ->class('btn btn-green')
            ->tooltip('HABILITAR REAFILIACIÓN')
            ->dispatchTo('afiliacion.estudiantes-ssue.estudiantes-main', 'reafiliarEstudiante', ['data' => $row->id])
            ->can(allowed: auth()->user()->can('reafiliar.afiliacion_estudiantes_ssue'));
        // }

        return $buttons;
    }
}
