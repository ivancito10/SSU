<?php

namespace App\Livewire\Afiliacion\ExamenMedico;

use App\Models\Afiliacion\ExMedicoBeneficiario;
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

final class ExamenMedicoTable extends PowerGridComponent
{
    use WithExport;

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
        return ExMedicoBeneficiario::join('administracion.persona as p' , 'ex_medico_beneficiario.id_persona' , '=', 'p.id')
        ->join('afiliacion.titular as t', 'ex_medico_beneficiario.id_titular', '=', 't.id')
        ->join('administracion.persona as pp', 't.id_persona', '=', 'pp.id')
        ->select('ex_medico_beneficiario.*', 
        'p.nombres as nombre_p', 'p.p_apellido as p_a_p' ,
        'pp.nombres as nombre_t')
        ; 
    }

    public function relationSearch(): array
    {
        return [
            'titularPersona'=>['nombres', 'p_apellido', 's_apellido'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('fecha_solicitud_formatted', fn (ExMedicoBeneficiario $model) => Carbon::parse($model->fecha_solicitud)->format('d/m/Y'))
            ->add('fecha_fin_formatted', fn (ExMedicoBeneficiario $model) => Carbon::parse($model->fecha_fin)->format('d/m/Y'))
            ->add('estado_solicitud')
            ->add('estado')
            ->add('fecha_liq_formatted', fn (ExMedicoBeneficiario $model) => Carbon::parse($model->fecha_liq)->format('d/m/Y'))
            ->add('observacion')
            ->add('id_persona')
            ->add('id_titular')
            ->add('nombre_p')
            ->add('p_a_p')
            ->add('nombre_t')
            ->add('nombre_beneficiario')
            ->add('id_agenda')
            ->add('id_personal_liq_rec')
            ->add('id_personal_plataforma')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Nro', 'id')
                ->headerAttribute('text-center bg-teal', 'font-size:15px')
                ->sortable()
                ->searchable(),

            Column::make('TITULAR: Nombre completo', 'nombre_t')
            ->headerAttribute('text-center bg-teal', 'font-size:15px')
                ->sortable()
                ->searchable(),
            Column::make('BENEFICIARIO: Nombre completo', 'nombre_p')
            ->headerAttribute('text-center bg-teal', 'font-size:15px')
            ->sortable()
            ->searchable(),


            Column::make('Fecha solicitud', 'fecha_solicitud_formatted', 'fecha_solicitud')
            ->headerAttribute('text-center bg-teal', 'font-size:15px')    
            ->sortable(),

            Column::make('Fecha fin', 'fecha_fin_formatted', 'fecha_fin')
            ->headerAttribute('text-center bg-teal', 'font-size:15px')    
            ->sortable(),

            Column::make('Estado solicitud', 'estado_solicitud')
            ->headerAttribute('text-center bg-teal', 'font-size:15px')    
            ->sortable()
                ->searchable(),

            Column::action('Action')
            ->headerAttribute('text-center bg-teal', 'font-size:15px')
        ];
    }

    public function filters(): array
    {
        return [
            
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
    }

    public function actions(ExMedicoBeneficiario $row): array
    {
        return [
            Button::add('ver')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-journal-bookmark-fill" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M6 1h6v7a.5.5 0 0 1-.757.429L9 7.083 6.757 8.43A.5.5 0 0 1 6 8z"/>
                <path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2"/>
                <path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z"/>
                </svg>')
                ->id()
                ->class('btn btn-orange')
                ->dispatchTo('afiliacion.examen-medico.examen-medico-main', 'modalVerAgendado',['data' => $row->id])
                
                ->tooltip('VER AGENDA'),
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
