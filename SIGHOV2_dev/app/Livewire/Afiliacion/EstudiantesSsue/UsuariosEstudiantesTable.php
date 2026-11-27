<?php

namespace App\Livewire\Afiliacion\EstudiantesSsue;

use App\Models\User;
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
use Illuminate\Support\Collection;

final class UsuariosEstudiantesTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'UsuariosTable';
    public string $sortField = 'users.id';
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

    public function datasource(): collection
    {
        return User::query()
            ->join('administracion.persona as persona', function ($join) {
                $join->on('users.id_persona', '=', 'persona.id');
            })
            ->where('users.email', 'like', '%est%')
            ->select([
                'users.id',
                'users.name',
                'users.email',
                'users.id_persona',
                'users.last_login_at',
                'persona.nombres as nombre_persona',
                'persona.p_apellido as pap_persona',
                'persona.s_apellido as sap_persona',
                'persona.ci as carnet',
                'persona.matricula_seguro as matricula',
                'persona.complemento as persona_complemento',
            ])->orderBy('users.id', 'desc')->get();
    }

    public function relationSearch(): array
    {
        return [
            'personaUser' => ['nombres', 'p_apellido', 's_apellido', 'ci'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('email')
            ->add('id_persona')
            ->add('persona_nombre_completo', fn(User $model) => $model->personaUser->p_apellido . ' ' . $model->personaUser->s_apellido . ' ' . $model->personaUser->nombres)
            ->add('carnet_formatted', function ($carnet) {
                if ($carnet->persona_complemento == null) {
                    return $carnet->carnet;
                } else {
                    return $carnet->carnet . ' - ' . $carnet->persona_complemento;
                }
            })
            ->add('roles', fn(User $model) => $model->roles->map(fn($role) => '<span class="badge badge-info" style="font-size:12px">' . $role->name . '</span>')->implode(' <br>'))
            ->add('session_token')
            ->add('created_at')

            ->add('status', function (User $model) {
                $lastLoginAt = $model->last_login_at ? Carbon::parse($model->last_login_at) : null;
                return $lastLoginAt && $lastLoginAt->diffInMinutes() < 15
                    ? '<span class="badge badge-success"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-record-circle-fill" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-8 3a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                        </svg></span>'
                    : '<span class="badge badge-danger"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-record-circle-fill" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-8 3a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                        </svg></span>';
            });
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')->index()
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center'),

            Column::make('NOMBRE USUARIO', 'name')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->sortable()
                ->searchable(),

            Column::make('USUARIO', 'email')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->sortable()
                ->searchable(),

            Column::make('CARNET', 'carnet_formatted', 'carnet')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('MATRICULA', 'matricula')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center'),

            // Column::make('NOMBRE PERSONA', 'persona_nombre_completo', 'pap_persona')
            //     ->headerAttribute('text-center ', 'font-size:15px; background-color: #5c5f59  ; color:white;')
            //     ->sortable()
            //     ->searchable(),

            // Column::make('ROLES', 'roles')
            //     ->headerAttribute('text-center ', 'font-size:15px; background-color: #5c5f59  ; color:white;'),



            // Column::make('Ult. Con.', 'last_login_at')
            //     ->headerAttribute('text-center ', 'font-size:15px; background-color: #5c5f59  ; color:white;')
            //     ->bodyAttribute('text-center')
            //     ->sortable()
            //     ->searchable(),

            Column::action('OPCIONES')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
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

    public function actions(User $row): array
    {
        $buttons = [];
        if ($row->primer_inicio_sesion == null && $row->last_login_at == null) {
            $buttons[] =   Button::add('pdf')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zM1.6 11.85H0v3.999h.791v-1.342h.803q.43 0 .732-.173.305-.175.463-.474a1.4 1.4 0 0 0 .161-.677q0-.375-.158-.677a1.2 1.2 0 0 0-.46-.477q-.3-.18-.732-.179m.545 1.333a.8.8 0 0 1-.085.38.57.57 0 0 1-.238.241.8.8 0 0 1-.375.082H.788V12.48h.66q.327 0 .512.181.185.183.185.522m1.217-1.333v3.999h1.46q.602 0 .998-.237a1.45 1.45 0 0 0 .595-.689q.196-.45.196-1.084 0-.63-.196-1.075a1.43 1.43 0 0 0-.589-.68q-.396-.234-1.005-.234zm.791.645h.563q.371 0 .609.152a.9.9 0 0 1 .354.454q.118.302.118.753a2.3 2.3 0 0 1-.068.592 1.1 1.1 0 0 1-.196.422.8.8 0 0 1-.334.252 1.3 1.3 0 0 1-.483.082h-.563zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638z"/>
            </svg>')

                ->class('btn btn-red')
                ->tooltip('VER CREDENCIALES')
                /* ->dispatch('pdf', ['rowId' => $row->id]) */
                ->dispatchTo('afiliacion.estudiantes-ssue.usuario-estudiante', 'verCredencialesPdf', ['data' => $row->id]);
        }
        // ->can(allowed: auth()->user()->can('comprobante_pdf.aportes_institucionales')); 
        $buttons[] = Button::add('ver')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                </svg>')
            ->class('btn btn-blue')
            ->tooltip('VER USUARIO')
            ->dispatchTo('afiliacion.estudiantes-ssue.usuario-estudiante', 'verUsuario', ['data' => $row->id]);
        return $buttons;
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
