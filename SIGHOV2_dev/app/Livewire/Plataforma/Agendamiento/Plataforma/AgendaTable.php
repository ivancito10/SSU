<?php

namespace App\Livewire\Plataforma\Agendamiento\Plataforma;

use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\BeneficiarioInstitucion;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\TitularInstitucion;
use App\Models\Plataforma\Agenda;
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
use Illuminate\Support\Str;

final class AgendaTable extends PowerGridComponent
{
    use WithExport;
    public string $sortField = 'agenda.id';
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
        return Agenda::query()
            ->join('administracion.persona as persona', function ($persona) {
                $persona->on('persona.id', '=', 'agenda.id_persona');
            })
            ->join('administracion.tipo_asegurado as tipo_asegurado', function ($tipo) {
                $tipo->on('tipo_asegurado.id', '=', 'persona.id_tipo_asegurado');
            })
            ->join('plataforma.servicios_plataforma as servicio', function ($servicio) {
                $servicio->on('servicio.id', '=', 'agenda.id_servicio_plataforma');
            })
            ->whereNot('id_persona', null)
            ->select(
                'agenda.*',
                'persona.id as p_id',
                'persona.nombres',
                'persona.p_apellido',
                'persona.s_apellido',
                'persona.ci',
                'persona.complemento',
                'persona.matricula_seguro',
                'tipo_asegurado.tipo_asegurado',
                'servicio.nombre_servicio'

            );
    }

    public function relationSearch(): array
    {
        return [
            'personaAgenda' => ['nombres', 'p_apellido', 's_apellido', 'ci', 'matricula_seguro'],
            'personaAgenda.tipoAseguradoPersona' => ['tipo_asegurado'],
            'servicioPlataformaAgenda' => ['nombre_servicio'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('persona_nombre_completo', function ($nombrePersona) {
                if ($nombrePersona->p_apellido == null) {
                    return $nombrePersona->s_apellido . ' ' . $nombrePersona->nombres;
                } elseif ($nombrePersona->s_apellido == null) {
                    return $nombrePersona->p_apellido . ' ' . $nombrePersona->nombres;
                } else {
                    return $nombrePersona->p_apellido . ' ' . $nombrePersona->s_apellido . ' ' . $nombrePersona->nombres;
                }
            })
            ->add('carnet_formatted', function ($carnet) {
                if ($carnet->complemento == null) {
                    return $carnet->ci;
                } else {
                    return $carnet->ci . ' - ' . $carnet->complemento;
                }
            })
            /*  ->add('matricula_formatted', function ($matricula) {
                $matriculaCodigo = $matricula->matricula_seguro;
                if ($matricula->tipo_asegurado == 'TITULAR') {
                    $titular = Titular::where('id_persona', $matricula->p_id)->orderBy('id', 'desc')->first();
                    if ($titular) {
                        $titularInstitucion = TitularInstitucion::where('id_titular', $titular->id)
                            ->with(['titularInstitucionConInstitucion', 'titularInstitucionConInstitucion.codigosInstitucion'])->first();
                        if ($titularInstitucion) {
                            $matriculaCodigo = $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion != null ?
                                $matricula->matricula_seguro . ' - ' .
                                $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                                $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->tipo_titular :
                                $matricula->matricula_seguro;
                        }
                    }
                } elseif (Str::contains($matricula->tipo_asegurado, 'BENEFICIARIO')) {
                    if ($matricula->tipo_asegurado == 'BENEFICIARIO DEL INTERIOR') {
                        $matriculaCodigo = $matricula->matricula_seguro;
                    } else {
                        $beneficiario = Beneficiario::where('id_persona', $matricula->p_id)->orderBy('id', 'desc')->first();
                        if ($beneficiario) {
                            $titular = Titular::where('id', $beneficiario->id_titular)->first();
                            if ($titular) {
                                $titularInstitucion = TitularInstitucion::where('id_titular', $titular->id)
                                    ->with(['titularInstitucionConInstitucion', 'titularInstitucionConInstitucion.codigosInstitucion'])->first();
                                if ($titularInstitucion) {
                                    $matriculaCodigo = $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion != null ?
                                        $matricula->matricula_seguro . ' - ' .
                                        $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                                        $titularInstitucion->titularInstitucionConInstitucion->codigosInstitucion->tipo_beneficiario :
                                        $matricula->matricula_seguro;
                                }
                            }
                        }
                    }
                }
                return $matriculaCodigo;
            }) */
            ->add('fecha_agenda_formatted', fn(Agenda $model) => Carbon::parse($model->fecha_agenda)->format('d/m/Y'))

            ->add('hora_agenda', function ($row) {
                return "<span style='background-color: #f4d03f; padding: 2px 5px; border-radius:8px;'>
                            <strong>" . \Carbon\Carbon::parse($row->hora_agenda)->format('H:i') . "</strong>
                        </span>";
            })
            ->add('informe_medico')
            ->add('confirmado')
            ->add('id_servicios_plataforma')
            ->add('id_persona')
            ->add('id_asignacion_horario')
            ->add('anulacion_ficha')
            ->add('anulacion_ficha_formatted', function ($anulacion) {
                if ($anulacion->anulacion_ficha == false) {

                    return '<span style="color: green" title="FICHA VIGENTE"> <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                        </svg>
                        </span>';
                } else {
                    return '<span style="color: red" title="FICHA ANULADA">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                        </svg>
                        </span>';
                }
            })
            ->add('fecha_anulacion_formatted', fn(Agenda $model) => Carbon::parse($model->fecha_anulacion)->format('d/m/Y'))
            ->add('motivo_anulacion')
            ->add('id_user_created')
            ->add('id_user_updated')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->index()
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center'),

            Column::make('HORA AGENDA', 'hora_agenda')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            //   Column::make('FECHA AGENDA', 'fecha_agenda_formatted', 'fecha_agenda')
            //       ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
            //       ->bodyAttribute('text-center')
            //       ->sortable()
            //       ->searchable(),

            Column::make('FECHA AGENDA', 'fecha_agenda_formatted', 'fecha_agenda')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->sortable()
                ->searchable(function ($query, $buscaDate) {
                    try {
                        $buscaDate = Carbon::createFromFormat('d/m/Y', $buscaDate)->format('Y-m-d');
                        $query->whereDate('agenda.fecha_agenda', $buscaDate);
                    } catch (\Exception $e) {
                        $query->where('agenda.fecha_agenda', 'like', "%{$buscaDate}%");
                    }
                }),


            Column::make('CARNET', 'carnet_formatted', 'ci')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->sortable()
                ->searchable(),

            Column::make('MATRICULA', 'matricula_seguro', 'matricula_seguro')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->sortable()
                ->searchable(),

            Column::make('ASEGURADO', 'persona_nombre_completo', 'p_apellido')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->sortable()
                ->searchable(),

            Column::make('TIPO ASEGURADO', 'tipo_asegurado')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->sortable()
                ->searchable(),

            Column::make('SERVICIO ORIGEN', 'nombre_servicio')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->sortable()
                ->searchable(),



            Column::make('ESTADO AGENDA', 'anulacion_ficha_formatted', 'anulacion_ficha')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::action('OPCIONES')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center '),


        ];
    }


    public function actions(Agenda $row): array
    {
        return [
            Button::add('ver')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                </svg>')
                ->class('btn btn-blue')
                ->tooltip('VER AGENDA')
                ->dispatchTo('plataforma.agendamiento.plataforma.agenda-main', 'verAgenda', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('ver.agenda_plataforma')),

        ];
    }

    /*
    public function actionRules($row): array
    {
       return [
            // Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($row) => $row->id === 1)
                ->hide(),
        ];
    }
    */
}
