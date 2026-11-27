<?php

namespace App\Livewire\Afiliacion\Certificados\AfiliadoSsu;

use App\Models\Afiliacion\CertificadoAfiliacion;
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

final class AfiliadoSsuTable extends PowerGridComponent
{
    use WithExport;
    public string $sortField = "certificado_afiliacion.id";
    public string $sortDirection = "desc";

    public string $tableName = 'CertificadoAfiliacion';

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
        return CertificadoAfiliacion::query()
            ->join('administracion.persona', function ($persona) {
                $persona->on('certificado_afiliacion.id_persona', '=', 'persona.id');
            })
            ->select(
                'certificado_afiliacion.*',
                'persona.ci as cedula_identidad',
                'persona.complemento as p_complemento',
                'persona.nombres as nombres',
                'persona.p_apellido as paterno',
                'persona.s_apellido as materno',
                'persona.matricula_seguro as matricula'
            );
    }

    public function relationSearch(): array
    {
        return [
            'personaCertificadoAfiliacion' => ['nombres', 'p_apellido', 's_apellido', 'matricula_seguro', 'ci'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('cedula_identidad')
            ->add('nombres')
            ->add('paterno')
            ->add('materno')
            ->add('nro_cite')
            ->add('matricula')
            ->add('nombre_completo', function ($nombre) {
                if ($nombre->paterno == null) {
                    return $nombre->materno . ' ' . $nombre->nombres;
                } elseif ($nombre->materno == null) {
                    return $nombre->paterno . ' ' . $nombre->nombres;
                } else {
                    return $nombre->paterno . ' ' . $nombre->materno . ' ' . $nombre->nombres;
                }
            })
            ->add('cedula_identidad_formatted', function ($carnet) {
                if ($carnet->p_complemento == null) {
                    return $carnet->cedula_identidad;
                } else {
                    return $carnet->cedula_identidad . ' - ' . $carnet->p_complemento;
                }
            })
            ->add('fecha_emision_formatted', fn(CertificadoAfiliacion $model) => Carbon::parse($model->fecha_emision)->format('d/m/Y'))
            ->add('fecha_caducidad_formatted', fn(CertificadoAfiliacion $model) => Carbon::parse($model->fecha_caducidad)->format('d/m/Y'))
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->index()
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),
            Column::make('CODIGO CERTIFICADO', 'cod_certificado', 'paterno')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('NOMBRE COMPLETO', 'nombre_completo', 'paterno')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-left')
                ->sortable()
                ->searchableRaw("
                TRIM(CONCAT(
                    COALESCE(persona.p_apellido, ''), 
                    ' ', 
                    COALESCE(persona.s_apellido, ''), 
                    ' ', 
                    COALESCE(persona.nombres, '')
                ))
            "),

            Column::make('CARNET', 'cedula_identidad_formatted', 'cedula_identidad')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('MATRICULA', 'matricula')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('FECHA EMISION', 'fecha_emision_formatted', 'fecha_emision')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchableRaw("TO_CHAR(fecha_emision, 'DD/MM/YYYY')"),

            Column::make('FECHA CADUCIDAD', 'fecha_caducidad_formatted', 'fecha_caducidad')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchableRaw("TO_CHAR(fecha_caducidad, 'DD/MM/YYYY')"),

            /*             Column::make('Codigo seguridad', 'codigo_seguridad')
                ->headerAttribute('text-center bg-info', 'font-size:14px')
                ->sortable()
                ->searchable(),

            Column::make('Id persona', 'id_persona')
                ->headerAttribute('text-center bg-teal', 'font-size:14px')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('Created at', 'created_at_formatted', 'created_at')
                ->sortable(),

            Column::make('Created at', 'created_at')
                ->sortable()
                ->searchable(), */

            Column::action('OPCIONES')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
        ];
    }

    public function filters(): array
    {
        return [];
    }


    #[\Livewire\Attributes\On('pdf')]
    public function pdf($rowId): void
    {
        $this->js('window.open("/afiliacion/certificados/afiliacionssu/' . $rowId . '", "_blank")');
    }

    public function actions(CertificadoAfiliacion $row): array
    {
        return [
            Button::add('pdf')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zM1.6 11.85H0v3.999h.791v-1.342h.803q.43 0 .732-.173.305-.175.463-.474a1.4 1.4 0 0 0 .161-.677q0-.375-.158-.677a1.2 1.2 0 0 0-.46-.477q-.3-.18-.732-.179m.545 1.333a.8.8 0 0 1-.085.38.57.57 0 0 1-.238.241.8.8 0 0 1-.375.082H.788V12.48h.66q.327 0 .512.181.185.183.185.522m1.217-1.333v3.999h1.46q.602 0 .998-.237a1.45 1.45 0 0 0 .595-.689q.196-.45.196-1.084 0-.63-.196-1.075a1.43 1.43 0 0 0-.589-.68q-.396-.234-1.005-.234zm.791.645h.563q.371 0 .609.152a.9.9 0 0 1 .354.454q.118.302.118.753a2.3 2.3 0 0 1-.068.592 1.1 1.1 0 0 1-.196.422.8.8 0 0 1-.334.252 1.3 1.3 0 0 1-.483.082h-.563zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638z"/>
                </svg>')
                ->class('btn btn-red')
                ->tooltip('PDF')
                ->dispatch('pdf', ['rowId' => $row->id])
                ->can(allowed: auth()->user()->can('ver.certificados_afiliacion')),
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
