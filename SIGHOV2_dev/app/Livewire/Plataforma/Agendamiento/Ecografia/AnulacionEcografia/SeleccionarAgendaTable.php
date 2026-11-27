<?php

namespace App\Livewire\Plataforma\Agendamiento\Ecografia\AnulacionEcografia;

use App\Models\Plataforma\AgendaEcografia;
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


final class SeleccionarAgendaTable extends PowerGridComponent
{
    use WithExport;
    
    public string $tableName = 'PosiblesAnulacionesEcografiaTable';
    public string $sortField = 'agenda_ecografia.id';
    public string $sortDirecction = 'desc';

    public function setUp(): array
    {
        return [
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return AgendaEcografia::query()
            ->where(function ($consultita) {
                $consultita->where('agenda_ecografia.fecha_agenda', '>', Carbon::now()->format('Y-m-d'))
                    ->orWhere(function ($otraQuericita) {
                        $otraQuericita->where('agenda_ecografia.fecha_agenda', '=', Carbon::now()->format('Y-m-d'))
                            ->where('agenda_ecografia.hora_agenda', '>=', Carbon::now()->format('H:i:s'));
                    });
            })
            ->where('id_suspension_horario_ecografia', null)
            ->whereNot('anulacion_ficha', true)
            ->join('administracion.persona as persona', function ($persona) {
                $persona->on('agenda_ecografia.id_persona', '=', 'persona.id');
            })
            ->join('plataforma.asignacion_horario as ahorario', function ($ahorario) {
                $ahorario->on('agenda_ecografia.id_asignacion_horario', '=', 'ahorario.id');
            })
            ->join('plataforma.especialista as especialista', function ($especialista) {
                $especialista->on('ahorario.id_especialista', '=', 'especialista.id');
            })
            ->join('administracion.persona as epersona', function ($epersona) {
                $epersona->on('especialista.id_persona', '=', 'epersona.id');
            })
            ->join('administracion.especialidad as especialidad', function ($especialidad) {
                $especialidad->on('especialista.id_especialidad', '=', 'especialidad.id');
            })
            ->select(
                'agenda_ecografia.*',
                'persona.nombres as p_nombres',
                'persona.p_apellido as p_p_apellido',
                'persona.s_apellido as p_s_apellido',
                'persona.matricula_seguro as p_matricula_seguro',
                'persona.ci as p_ci',
                'persona.complemento as p_complemento',
                'especialista.grado_academico as e_grado_academico',
                'epersona.nombres as e_nombres',
                'epersona.p_apellido as e_p_apellido',
                'epersona.s_apellido as e_s_apellido',
                'especialidad.especialidad as especialidad'
            )
            ->orderBy('id','desc');
    }

    public function relationSearch(): array
    {
        return [
            'personaAgenda' => ['nombres', 'p_apellido', 's_apellido', 'matricula_seguro', 'ci'],
            'asignacionHorarioAgenda.especialistaAsignacionHorario.personaEspecialista' => ['nombres', 'p_apellido', 's_apellido'],
            'asignacionHorarioAgenda.especialistaAsignacionHorario.especialidadEspecialista' => ['especialidad']
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('id')
            ->add('fecha_agenda_formatted', fn(AgendaEcografia $model) => Carbon::parse($model->fecha_agenda)->format('d/m/Y'))
            ->add('hora_agenda')
            ->add('hora_agenda_formatted', fn(AgendaEcografia $model) => Carbon::parse($model->hora_agenda)->format('H:i'))
            ->add('confirmado')
            ->add('id_servicio_plataforma')
            ->add('id_persona')
            //PARA EL RESTO DE CAMPOS
            ->add('p_p_apellido')
            ->add('persona_formatted', function ($persona) {
                return implode(' ', array_filter([$persona->p_p_apellido, $persona->p_s_apellido, $persona->p_nombres]));
            })
            ->add('p_matricula_seguro')
            ->add('p_ci')
            ->add('carnet_formatted', function ($carnet) {
                return implode(' - ', array_filter([$carnet->p_ci, $carnet->p_complemento]));
            })
            ->add('e_p_apellido')
            ->add('especialista_formatted', function ($especialista) {
                $gradoAcademico = $especialista->e_grado_academico === 'DOCTORA'
                    ? 'DRA.'
                    : ($especialista->e_grado_academico === 'DOCTOR' ? 'DR.' : 'LIC.');

                $nombreEspecialista = implode(' ', array_filter([$especialista->e_p_apellido, $especialista->e_s_apellido, $especialista->e_nombres]));

                return $gradoAcademico . ' ' . $nombreEspecialista;
            })
            ->add('especialidad')

            //FIN DEL RESTO DE CAMPOS
            ->add('id_asignacion_horario')
            ->add('anulacion_ficha')
            ->add('motivo_anulacion')
            ->add('id_suspension_horario_ecografia')
            ->add('clave_unica')
            ->add('ficha_extra')
            ->add('ip')
            ->add('observacion')
            ->add('nombre_equipo')
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
                ->index(),
            Column::make('HORA AGENDA', 'hora_agenda_formatted', 'hora_agenda')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),
            Column::make('FECHA AGENDA', 'fecha_agenda_formatted', 'fecha_agenda')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable(),    

            Column::make('ASEGURADO', 'persona_formatted', 'p_p_apellido')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-left')
                ->sortable()
                ->searchable(),

            Column::make('MATRICULA', 'p_matricula_seguro')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('CARNET', 'carnet_formatted', 'p_ci')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('ESPECIALIDAD', 'especialidad')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-left')
                ->sortable()
                ->searchable(),

            Column::make('ESPECIALISTA', 'especialista_formatted', 'e_p_apellido')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-left')
                ->sortable()
                ->searchable(),
 

            Column::action('OPCIONES')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
        ];
    }

    public function actions(AgendaEcografia $row): array
    {
        /*return [
            Button::add('edit')
                ->slot('Edit: ' . $row->id)
                ->id()
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->dispatch('edit', ['rowId' => $row->id])
        ];*/

        return [
            Button::add('anular_agenda')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-journal-bookmark" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M6 8V1h1v6.117L8.743 6.07a.5.5 0 0 1 .514 0L11 7.117V1h1v7a.5.5 0 0 1-.757.429L9 7.083 6.757 8.43A.5.5 0 0 1 6 8"/>
                <path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2"/>
                <path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z"/>
                </svg>')
                ->class('btn btn-red')
                ->route('plataforma.agendamiento.ecografia.anulacion-ecografia.create', ['id' => $row->id])
                ->tooltip('ANULAR AGENDA')
                ->can(allowed: auth()->user()->can('ver.anulacion_ecografia')) 
        ];
    }
}
