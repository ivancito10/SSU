<?php

namespace App\Livewire\Aportes\AportesVoluntarios\AportantesVoluntarios;

use App\Models\Aportes\ContratoVoluntario;
use App\Livewire\Aportes\AportesVoluntarios\AportantesVoluntarios\AportantesVoluntariosMain;
use Carbon\Carbon;
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
use App\Models\Administracion\Zona;
use Illuminate\Support\Collection;

final class AportantesVoluntariosTable extends PowerGridComponent
{
    use WithExport;

    public $contratoId;
    public $modal = false;
    public string $tableName = 'AportanteVoluntariLista';
    public string $sortField = 'contrato_voluntario.id';
    public string $sortDirection = 'desc';
    public function setUp(): array
    {
        return [
            Exportable::make('export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()->showSearchInput(),
            Footer::make()->showPerPage()->showRecordCount(),
        ];
    }

    public function datasource(): Collection
    {
        $contratoVoluntario = ContratoVoluntario::query()
            ->join('administracion.persona', function ($persona) {
                $persona->on('contrato_voluntario.id_persona', '=', 'persona.id');
            })
            ->where('contrato_voluntario.estado', true)
            ->select(
                'contrato_voluntario.*',
                'persona.nombres as persona_nombres',
                'persona.p_apellido as persona_p_apellido',
                'persona.s_apellido as persona_s_apellido',
                'persona.ci as persona_ci',
                'persona.complemento as persona_complemento'
            )
            ->selectRaw("CONCAT(persona.nombres, ' ', persona.p_apellido, ' ', persona.s_apellido) as nombre_completo_raw")
            ->orderBy('contrato_voluntario.id', 'desc')
            ->get();
        $contratoVoluntario->map(function ($contrato) {
            $contrato->persona_nombre_completo = $contrato->persona_p_apellido . ' ' . $contrato->persona_s_apellido . ' ' . $contrato->persona_nombres;
            $contrato->persona_ci = (string) $contrato->persona_ci;
            $contrato->carnet_complemento_completo = $contrato->persona_ci . ' ' . $contrato->persona_complemento;
            $contrato->fecha_inicio_contrato_formatted = Carbon::parse($contrato->fecha_inicio_contrato)->format('d/m/Y');
            $contrato->fecha_fin_contrato_formatted = Carbon::parse($contrato->fecha_fin_contrato)->format('d/m/Y');
            $contrato->created_at_formatted = Carbon::parse($contrato->created_at)->format('d/m/Y');
            $fechaFin = Carbon::parse($contrato->fecha_fin_contrato);
            $hoy = Carbon::now()->startOfDay(); // Nos aseguramos de comparar desde el inicio del día actual

            if ($fechaFin->greaterThanOrEqualTo($hoy)) {
                // Si la fecha de fin es HOY o en el FUTURO, calculamos la diferencia de días
                $contrato->dias_restantes = $fechaFin->diffInDays($hoy);
            } else {
                // Si la fecha de fin es en el PASADO, el contrato ha vencido
                $contrato->dias_restantes = 0;
            }

            return $contrato;
        });
        return $contratoVoluntario;
    }

    public function relationSearch(): array
    {
        return [
            'personaContratoVoluntario' => ['nombres', 'p_apellido', 's_apellido', 'ci', 'complemento'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('persona_nombre_completo', fn(ContratoVoluntario $model) => $model->personaContratoVoluntario->p_apellido . ' ' . $model->personaContratoVoluntario->s_apellido . ' ' . $model->personaContratoVoluntario->nombres)
            //->add('carnet_complemento_completo', fn(ContratoVoluntario $model) => $model->personaContratoVoluntario->ci . ' ' . $model->personaContratoVoluntario->complemento)
            ->add('carnet_complemento_completo')
            ->add('telefono_1')
            ->add('telefono_2')
            ->add('direccion')
            ->add('persona_ci')
            ->add('persona_complemento')
            //->add('fecha_inicio_contrato')
            //->add('fecha_inicio_contrato_formatted', fn(ContratoVoluntario $model) => Carbon::parse($model->fecha_inicio_contrato)->format('d/m/Y'))
            ->add('descripcion_contrato')
            //->add('fecha_fin_contrato')
            //->add('fecha_fin_contrato_formatted', fn(ContratoVoluntario $model) => Carbon::parse($model->fecha_fin_contrato)->format('d/m/Y'))
            //->add('dias_restantes', fn(ContratoVoluntario $model) => Carbon::parse($model->fecha_fin_contrato)->diffInDays(Carbon::now()))
            ->add('nombre_zona')
            ->add('numero')
            ->add('dias_restantes', function (ContratoVoluntario $model) {
                $fechaFin = Carbon::parse($model->fecha_fin_contrato);
                $hoy = Carbon::now()->startOfDay();

                if ($fechaFin->greaterThanOrEqualTo($hoy)) {
                    $dias = $fechaFin->diffInDays($hoy);
                } else {
                    $dias = 0;
                }

                if ($dias === 0) {
                    return '<span style="color: red; font-weight: bold;">' . $dias . '</span>';
                }
                return $dias;
            })
            ->add('estado')
            ->add('estado_formatted', function ($estado) {
                if ($estado->estado) {
                    return '<span> <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="green" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                    </svg></span>';
                } else {
                    return '<span ><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill=" #c0392b" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                    </svg></span>';
                }
            })
            ->add('created_at');
        //->add('created_at_formatted', fn(ContratoVoluntario $model) => Carbon::parse($model->created_at)->format('d/m/Y'));
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->index(),

            Column::make('APORTANTE', 'persona_nombre_completo')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3; color:white;')
                ->sortable()
                ->searchable(),
            Column::make('CARNET', 'carnet_complemento_completo')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3; color:white;')
                ->bodyAttribute('text-left')
                ->sortable()
                ->searchable(),

            Column::make('INICIO DE CONTRATO', 'fecha_inicio_contrato_formatted')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),
            Column::make('FIN DE CONTRATO', 'fecha_fin_contrato_formatted')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),
            Column::make('DÍAS RESTANTES', 'dias_restantes')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3; color:white;')
                ->bodyAttribute('text-center')
                ->searchable(),

            /*Column::make('ZONA', 'nombre_zona')
            ->headerAttribute('text-center ', 'font-size:15px; background-color: #154360  ; color:white;')
                ->sortable()
                ->searchable(), */


            // Column::make('FECHA DE REGISTRO', 'created_at_formatted')
            //     ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3; color:white;')
            //     ->bodyAttribute('text-center')
            //     ->sortable()
            //     ->searchable(),

            Column::make('ESTADO', 'estado_formatted', 'estado')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::action('OPCIONES')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3; color:white;')
        ];
    }

    public function filters(): array
    {
        return [];
    }

    // #[\Livewire\Attributes\On('edit')]
    // public function edit($rowId, $abrirModal): void
    // {
    //     $this->modal = $abrirModal;
    //     $this->contratoId = $rowId;
    //     $this->dispatch('edicion', ['modalEstado' => $this->modal, 'contratoId' => $this->contratoId])
    //         ->to(AportantesVoluntariosMain::class);
    // }

    #[\Livewire\Attributes\On('ver')]
    public function ver($rowId, $abrirModalVer): void
    {
        $this->modal = $abrirModalVer;
        $this->contratoId = $rowId;
        $this->dispatch('vista', ['modalVista' => $this->modal, 'contratoId' => $this->contratoId])
            ->to(AportantesVoluntariosMain::class);
    }


    #[\Livewire\Attributes\On('habilitar')]
    public function habilitar($rowId): void
    {

        $this->contratoId = $rowId;
        $this->dispatch('habilitar_contrato', $this->contratoId)->to(AportantesVoluntariosMain::class);
    }

    #[\Livewire\Attributes\On('deshabilitar')]
    public function deshabilitar($rowId): void
    {
        $this->contratoId = $rowId;
        $this->dispatch('deshabilitar_contrato', $this->contratoId)->to(AportantesVoluntariosMain::class);
    }


    public function actions(ContratoVoluntario $row): array
    {
        $buttons = [
            Button::add('ver')
                ->slot(
                    '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                    <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                    <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                    </svg>'
                )
                ->id()
                ->class('btn btn-blue')
                ->dispatchTo('aportes.aportes-voluntarios.aportantes-voluntarios.aportantes-voluntarios-main', 'verAportante', ['data' => $row->id])
                ->tooltip('VER APORTANTE')
                ->can(allowed: auth()->user()->can('ver.aportantes_voluntarios')),

            Button::add('edit')
                ->slot(
                    '<svg xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="16"
                    fill="currentColor"
                    class="bi bi-pencil-square"
                    viewBox="0 0 16 16">
                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                    </svg>'
                )
                ->class('btn btn-orange')
                //->dispatchTo('aportes.aportes-voluntarios.aportantes-voluntarios.aportantes-voluntarios-main', 'editarAportante', ['data' => $row->id])
                ->route('aportes.contrato_voluntario.edit-contrato', ['id' => $row->id])
                ->tooltip('MODIFICAR CONTRATO')
                ->can(allowed: auth()->user()->can('editar.aportantes_voluntarios')),

            Button::add('calendario')
                ->slot(
                    '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard2-check" viewBox="0 0 16 16">
                <path d="M9.5 0a.5.5 0 0 1 .5.5.5.5 0 0 0 .5.5.5.5 0 0 1 .5.5V2a.5.5 0 0 1-.5.5h-5A.5.5 0 0 1 5 2v-.5a.5.5 0 0 1 .5-.5.5.5 0 0 0 .5-.5.5.5 0 0 1 .5-.5z"/>
                <path d="M3 2.5a.5.5 0 0 1 .5-.5H4a.5.5 0 0 0 0-1h-.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1H12a.5.5 0 0 0 0 1h.5a.5.5 0 0 1 .5.5v12a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5z"/>
                <path d="M10.854 7.854a.5.5 0 0 0-.708-.708L7.5 9.793 6.354 8.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0z"/>
                </svg>'
                )
                ->id()
                ->class('btn btn-green')
                ->dispatchTo('aportes.aportes-voluntarios.aportantes-voluntarios.aportantes-voluntarios-main', 'registroAportes', ['data' => $row->id])
                ->tooltip(' APORTES')
                ->can(allowed: auth()->user()->can('ver.aportantes_voluntarios')),



        ];

        if ($row->estado == false) {
            $buttons[] = Button::add('habilitar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                        <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z"/>
                        </svg>')
                ->id()
                ->class('btn btn-green')
                ->tooltip(' HABILITAR')
                ->dispatch('habilitar', ['rowId' => $row->id])
                ->can(allowed: auth()->user()->can('habilitar_desabilitar.aportantes_voluntarios'));
        } elseif ($row->estado == true) {
            $buttons[] = Button::add('deshabilitar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                </svg>')
                ->id()
                ->class('btn btn-red')
                ->tooltip(' DESHABILITAR')
                ->dispatch('deshabilitar', ['rowId' => $row->id])
                ->can(allowed: auth()->user()->can('habilitar_desabilitar.aportantes_voluntarios'));
        }

        return $buttons;
    }
}
