<?php

namespace App\Livewire\Vigencia\BajasInstitucionales;

use App\Models\Vigencia\BajaInstitucional;
use App\Models\Vigencia\HistorialAsegurado;
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

final class BajasInstitucionalesTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'BajaInstTable';
    public string $sortField = 'baja_institucional.id';
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
        return BajaInstitucional::query()
            ->join('administracion.persona as persona', function ($persona) {
                $persona->on('persona.id', '=', 'baja_institucional.id_persona');
            })
            ->leftJoin('vvdd.motivo_baja as motivo_baja', function ($motivo) {
                $motivo->on('motivo_baja.id', '=', 'baja_institucional.id_motivo_baja');
            })
            ->select(
                'vvdd.baja_institucional.*',
                'persona.nombres as p_nombres',
                'persona.p_apellido as p_p_apellido',
                'persona.s_apellido as p_s_apellido',
                'persona.matricula_seguro as p_matricula_seguro',
                'persona.ci as p_ci',
                'persona.matricula_seguro as p_matricula',
                'persona.complemento as p_complemento',
                'motivo_baja.motivo_baja as motivo',
            );
    }

    public function relationSearch(): array
    {
        return [
            'personaBajaInstitucional' => ['nombres', 'p_apellido', 's_apellido', 'matricula_seguro', 'ci'],
            'motivoBajaBajaInstitucional' => ['motivo_baja']
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('id')
            ->add('fecha_baja_inst_formatted', fn(BajaInstitucional $model) => Carbon::parse($model->fecha_baja_inst)->format('d/m/Y'))
            ->add('fecha_baja_ssu_formatted', fn(BajaInstitucional $model) => Carbon::parse($model->fecha_baja_ssu)->format('d/m/Y'))
            ->add('id_persona')
            ->add('nombre_completo_formatted', function ($nombreCompleto) {
                if ($nombreCompleto->p_p_apellido == null) {
                    return $nombreCompleto->p_s_apellido . ' ' . $nombreCompleto->p_nombres;
                } elseif ($nombreCompleto->p_s_apellido == null) {
                    return $nombreCompleto->p_p_apellido . ' ' . $nombreCompleto->p_nombres;
                } else {
                    return $nombreCompleto->p_p_apellido . ' ' . $nombreCompleto->p_s_apellido . ' ' . $nombreCompleto->p_nombres;
                }
            })
            ->add('carnet_formatted', function ($carnet) {
                if ($carnet->p_complemento == null) {
                    return $carnet->p_ci;
                } else {
                    return $carnet->p_ci . ' - ' . $carnet->p_complemento;
                }
            })
            ->add('id_motivo_baja')
            ->add('motivo_baja_formatted', function ($motivo) {
                if ($motivo->motivo == null) {
                    return $motivo->descripcion_motivo;
                } else {
                    return $motivo->motivo;
                }
            })
            ->add('descripcion_motivo')
            ->add('id_user_created')
            ->add('id_user_updated')
            /*->add('probando', function ($row){
                dd($row);
                return $row->p_ci;
            })*/
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3; color:white;')
                ->bodyAttribute('text-center')
                ->index(),

            Column::make('CARNET', 'carnet_formatted', 'p_ci')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3; color:white;')
                ->sortable()
                ->searchable(),

            Column::make('MATRICULA', 'p_matricula')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3; color:white;')
                ->sortable()
                ->searchable(),

            Column::make('NOMBRE COMPLETO', 'nombre_completo_formatted', 'p_p_apellido')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3; color:white;')
                ->sortable()
                ->searchableRaw("CONCAT(persona.p_apellido, ' ', persona.s_apellido, ' ', persona.nombres)"),

            Column::make('MOTIVO DE LA BAJA', 'motivo_baja_formatted', 'motivo')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3; color:white;')
                ->sortable()
                ->searchable(),

            Column::make('MOTIVO DE LA BAJA ESCRITO', 'descripcion_motivo')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3; color:white;')
                ->searchable()
                ->hidden(isHidden: true, isForceHidden: true),

            Column::make('FECHA INICIO CESANTIA', 'fecha_baja_inst_formatted', 'fecha_baja_inst')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3; color:white;')
                ->sortable()
                ->searchableRaw("TO_CHAR(fecha_baja_inst, 'DD/MM/YYYY')"),

            Column::make('FECHA BAJA DEFINITIVA', 'fecha_baja_ssu_formatted', 'fecha_baja_ssu')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3; color:white;')
                ->sortable()
                ->searchableRaw("TO_CHAR(fecha_baja_ssu, 'DD/MM/YYYY')"),

            Column::action('OPCIONES')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3; color:white;')
        ];
    }

    public function actions(BajaInstitucional $row): array
    {
        $historial = HistorialAsegurado::where('id_persona', $row->id_persona)->orderBy('id', 'desc')->first();
        //dd($historial);
        $buttons = [];

        $buttons[] = Button::add('ver')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
            <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
            <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
            </svg>')
            ->class('btn btn-blue')
            ->tooltip('VER')
            ->dispatchTo('vigencia.bajas-institucionales.bajas-institucionales-main', 'verBajaInstitucional', ['data' => $row->id])
            ->can(allowed: auth()->user()->can('ver.bajas_asegurados'));
        if ($row->fecha_baja_asuss == null) {
            $buttons[] = Button::add('baja_asuss')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard-minus-fill" viewBox="0 0 16 16">
            <path d="M6.5 0A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0zm3 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5z"/>
            <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1A2.5 2.5 0 0 1 9.5 5h-3A2.5 2.5 0 0 1 4 2.5zM6 9h4a.5.5 0 0 1 0 1H6a.5.5 0 0 1 0-1"/>
            </svg>')
                ->class('btn btn-yellow')
                ->tooltip('BAJA ASUSS')
                ->dispatchTo('vigencia.bajas-institucionales.bajas-institucionales-main', 'bajaAsuss', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('registrar.baja_asuss'));
        }
        if ($historial) {
            if ($historial->estado_vigencia == 'CESANTE') {
                $buttons[] = Button::add('bajaDefinitiva')
                    ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill-x" viewBox="0 0 16 16">
                <path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0m-9 8c0 1 1 1 1 1h5.256A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1 1.544-3.393Q8.844 9.002 8 9c-5 0-6 3-6 4"/>
                <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m-.646-4.854.646.647.646-.647a.5.5 0 0 1 .708.708l-.647.646.647.646a.5.5 0 0 1-.708.708l-.646-.647-.646.647a.5.5 0 0 1-.708-.708l.647-.646-.647-.646a.5.5 0 0 1 .708-.708"/>
                </svg>')
                    ->class('btn btn-red')
                    ->tooltip('DAR BAJA DEFINITIVA')
                    ->dispatchTo('vigencia.bajas-institucionales.bajas-institucionales-main', 'darBajaDefinitiva', ['data' => $row->id])
                    ->can(allowed: auth()->user()->can('ver.bajas_asegurados'));
            }
        }


        //if($historial->estado_vigencia == 'NO VIGENTE'){
        //    $buttons[] = Button::add('verPdf')
        //    ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
        //    <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zM1.6 11.85H0v3.999h.791v-1.342h.803q.43 0 .732-.173.305-.175.463-.474a1.4 1.4 0 0 0 .161-.677q0-.375-.158-.677a1.2 1.2 0 0 0-.46-.477q-.3-.18-.732-.179m.545 1.333a.8.8 0 0 1-.085.38.57.57 0 0 1-.238.241.8.8 0 0 1-.375.082H.788V12.48h.66q.327 0 .512.181.185.183.185.522m1.217-1.333v3.999h1.46q.602 0 .998-.237a1.45 1.45 0 0 0 .595-.689q.196-.45.196-1.084 0-.63-.196-1.075a1.43 1.43 0 0 0-.589-.68q-.396-.234-1.005-.234zm.791.645h.563q.371 0 .609.152a.9.9 0 0 1 .354.454q.118.302.118.753a2.3 2.3 0 0 1-.068.592 1.1 1.1 0 0 1-.196.422.8.8 0 0 1-.334.252 1.3 1.3 0 0 1-.483.082h-.563zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638z"/>
        //    </svg>')
        //    ->class('btn btn-orange')
        //    ->tooltip('VER')
        //    ->dispatchTo('vigencia.bajas-institucionales.bajas-institucionales-main', 'verPdfBajaDefinitiva', ['data' => $row->id])
        //    ->can(allowed: auth()->user()->can('ver.bajas_asegurados'));
        //}        

        return $buttons;
    }
}
