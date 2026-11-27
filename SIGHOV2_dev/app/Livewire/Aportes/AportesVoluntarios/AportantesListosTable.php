<?php

namespace App\Livewire\Aportes\AportesVoluntarios;

use App\Models\Aportes\ContratoVoluntario;
use App\Models\Administracion\Persona;
use App\Livewire\Aportes\AportesVoluntarios\AporteVoluntarioNuevo;
use App\Livewire\Aportes\AportesVoluntarios\AporteVoluntarioMain;
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

final class AportantesListosTable extends PowerGridComponent
{
    use WithExport;
    public $aportante;
    public $idContrato;
    public string $sortField = 'contrato_voluntario.id';
    public string $sortDirection = 'desc';


    public function setUp(): array
    {
       // $this->showCheckBox();

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
        return ContratoVoluntario::query()
            ->join('administracion.persona', function ($persona){
                $persona->on('contrato_voluntario.id_persona', '=', 'persona.id');
            })
            ->select( 
                'contrato_voluntario.*', 
                'persona.nombres as persona_nombres', 
                'persona.p_apellido as persona_p_apellido', 
                'persona.s_apellido as persona_s_apellido',
                'persona.ci as persona_ci',
                'persona.complemento as persona_complemento')
            ->whereIn('aportes.contrato_voluntario.id', function ($query) {
                $query->selectRaw('MAX(id)')
                    ->from('aportes.contrato_voluntario')
                    ->groupBy('id_persona');
            });
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
            ->add('id')
            ->add('id')
            ->add('persona_nombre_completo', fn(ContratoVoluntario $model) => $model->personaContratoVoluntario->p_apellido . ' ' . $model->personaContratoVoluntario->s_apellido. ' ' .$model->personaContratoVoluntario->nombres)
            ->add('direccion')
            ->add('telefono_1')
            ->add('telefono_2')
            ->add('fecha_inicio_contrato')
            ->add('fecha_inicio_contrato_formatted', fn (ContratoVoluntario $model) => Carbon::parse($model->fecha_inicio_contrato)->format('d/m/Y'))
            ->add('fecha_fin_contrato')
            ->add('fecha_fin_contrato_formatted', fn (ContratoVoluntario $model) => Carbon::parse($model->fecha_fin_contrato)->format('d/m/Y'))
            ->add('descripcion_contrato')
            ->add('id_clase_aportante')
            ->add('id_zona')
            ->add('numero')
            ->add('carnet_formatted', function ($carnet){
                if($carnet->persona_complemento == null){
                    return $carnet->persona_ci;
                } else {
                    return $carnet->persona_ci.'-'.$carnet->persona_complemento;
                }
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
            ->add('correo')
            ->add('id_user_created')
            ->add('id_user_updated')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->index(),

            Column::make('ASEGURADO', 'persona_nombre_completo', 'persona_p_apellido')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3 ; color:white;')
                ->sortable()
                ->searchable(), 
            
            Column::make('CARNET', 'carnet_formatted', 'persona_ci')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3 ; color:white;')
                ->sortable()
                ->searchable(), 

            Column::make('FECHA INICIO CONTRATO', 'fecha_inicio_contrato_formatted', 'fecha_inicio_contrato')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable(),

            Column::make('FECHA FIN CONTRATO', 'fecha_fin_contrato_formatted', 'fecha_fin_contrato')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable(),

            Column::make('ESTADO', 'estado_formatted', 'estado')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),


            Column::action('OPCIONES')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                
        ];
    }

    public function filters(): array
    {
        return [
            //Filter::datepicker('fecha_inicio_contrato'),
            //Filter::datepicker('fecha_fin_contrato'),
        ];
    }

    #[\Livewire\Attributes\On('preliq')]
    public function preliq($rowId): void
    {
        $this->idContrato = $rowId;
        $this->dispatch('preliquidacion', ['idContrato'=>$this->idContrato])
        ->to(AporteVoluntarioMain::class);
    }  

    public function actions(ContratoVoluntario $row): array
    {
        $buttons = [];
        
        if($row->estado == true){
            $buttons[] = Button::add('view')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16">
                <path d="M11 2H9v3h2z"/>
                <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z"/>
                </svg>')
                ->class('btn btn-orange')
                ->route('aportes.aportevoluntario.create', ['id' => $row->id])
                ->tooltip('REGISTRAR APORTE');
        
            $buttons[] = Button::add('preliq')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calculator-fill" viewBox="0 0 16 16">
                            <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2zm2 .5v2a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 0-.5.5m0 4v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5M4.5 9a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zM4 12.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5M7.5 6a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zM7 9.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5m.5 2.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zM10 6.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5m.5 2.5a.5.5 0 0 0-.5.5v4a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-4a.5.5 0 0 0-.5-.5z"/>
                        </svg>')
                ->class('btn btn-limon')
                ->tooltip('PRE-LIQUIDACIÓN')
                ->dispatch('preliq', ['rowId' => $row->id]);
        } else {
            $buttons[] = Button::add('view')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16">
                <path d="M11 2H9v3h2z"/>
                <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z"/>
                </svg>')
                ->class('btn btn-orange disabled')
                //->route('aportes.aportevoluntario.create', ['id' => $row->id])
                ->tooltip('REGISTRAR APORTE -- CONTRATO NO VIGENTE');
        
            $buttons[] = Button::add('preliq')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calculator-fill" viewBox="0 0 16 16">
                            <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2zm2 .5v2a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 0-.5.5m0 4v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5M4.5 9a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zM4 12.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5M7.5 6a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zM7 9.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5m.5 2.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zM10 6.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5m.5 2.5a.5.5 0 0 0-.5.5v4a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-4a.5.5 0 0 0-.5-.5z"/>
                        </svg>')
                ->class('btn btn-limon disabled')
                ->tooltip('PRE-LIQUIDACIÓN -- CONTRATO NO VIGENTE');
                //->dispatch('preliq', ['rowId' => $row->id]);
        }

        

        return $buttons;
    }
}
