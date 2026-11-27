<?php

namespace App\Livewire\Administracion\PruebasFormales;

use App\Services\InteroperabilidadAsuss\AfiliacionBeneficiarioAsussService;
use App\Services\InteroperabilidadAsuss\AfiliacionTitularAsussService;
use App\Services\InteroperabilidadAsuss\LoginAsussService;
use Livewire\Component;

class PruebasFormalesMain extends Component
{
    public function render()
    {
        return view('livewire.administracion.pruebas-formales.pruebas-formales-main');
    }

    public function mount()
    {
        $servicioLoginAsuss = new LoginAsussService();
        //$servicioLoginAsuss->generarToken();

        $servicioAsussTitular = new AfiliacionTitularAsussService($servicioLoginAsuss);

        $arrayDatosOriginales = [
            'codigo_entidad' => 'NIT-120609020',
            'fecha_ingreso_trabajo' => '2024-09-17',
            'puesto_empleado' => 'AUXLIAR ADMINISTRATIVO V',
            'salario' => '5412.20',
            'departamento' => 'LA PAZ',
            'municipio' => 'NUESTRA SEÑORA DE LA PAZ',
            'fecha_inicio' => '2026-09-17',
            'tipo_asegurado' => 'TRABAJADOR',
            'matricula' => '005402QER',
            'tipo_documento' => 'CI',
            'numero_documento' => '12544032',
            'fecha_nacimiento' => '2000-04-02',
            'estado_civil' => 'S',
            'foto_persona' => '',
            'id_persona' => 2,
            'id_tipo_asegurado' => 1,
            'id_corresponde' => 10,
            'id_titular' => null,
            'id_institucion' => 23,
            'id_user_created' => 1,
        ];

        /*$data = [
            'data' => [
                [
                    'tipo_documento'   => 'CI',
                    'numero_documento' => '9868420',
                    'fecha_nacimiento' => '2000-04-29',
                ],
            ],
        ];

        $arrayDatosAfiliacionTitularMomentaneo = [
            'codigo_entidad' => 'NIT-120609020',
            'data' => [
                [
                    'empleador' => [
                        'fecha_ingreso_trabajo' => '2024-09-17',
                        'puesto_empleado'       => 'AUXLIAR ADMINISTRATIVO V',
                        'salario'               => '5412.20',
                        'departamento'          => 'LA PAZ',
                        'municipio'             => 'NUESTRA SEÑORA DE LA PAZ',
                    ],
                    'afiliacion' => [
                        'fecha_inicio'           => '2026-09-17',
                        'tipo_asegurado'         => 'TRABAJADOR',
                        'codigo_establecimiento' => '',
                        'matricula'              => '005402QER',
                    ],
                    'persona' => [
                        'tipo_documento'   => 'CI',
                        'numero_documento' => '12544032',
                        'fecha_nacimiento' => '2000-04-02',
                        'estado_civil'     => 'S',
                        'departamento'     => 'LA PAZ',
                        'municipio'        => 'LA PAZ',
                        'foto_persona'     => '',
                    ],
                ],
            ],
        ];*/

        $servicioAsussTitular->afiliarTitularAsuss($arrayDatosOriginales);
        //$servicioAsussTitular->contrastarTitular($data);
        //$servicioAsussTitular->registrarTitular($arrayDatosAfiliacionTitularMomentaneo);

        /*$servicioAsussBeneficiario = new AfiliacionBeneficiarioAsussService($servicioLoginAsuss);

        $arrayDatosContrastacionBeneficiarioMomentaneo = [
            'codigo_entidad' => 'NIT-000000001',
            'data' => [
                [
                    'tipo_documento'           => 'CI',
                    'numero_documento'         => '13244964',
                    'fecha_nacimiento'         => '2001-01-05',
                    'complemento_documento'    => 'NULL',
                    'nombres'                  => 'LIAN EMMANUEL',
                    'primer_apellido'          => 'MACEDA',
                    'segundo_apellido'         => 'VEGA',
                    'tipo_asegurado'           => '',
                    'tipo_documento_titular'   => 'CI',
                    'numero_documento_titular' => '7032430',
                    'fecha_nacimiento_titular' => '1989-01-18',
                ],
            ],
        ];

        $arrayDatosAfiliacionBeneficiarioMomentaneo = [
            'codigo_entidad' => 'NIT-000000001',
            'data' => [
                [
                    'titular' => [
                        'tipo_documento_titular'   => 'CI',
                        'numero_documento_titular' => '7032430',
                        'fecha_nacimiento_titular' => '1989-01-18',
                    ],
                    'beneficiario' => [
                        'tipo_documento'   => 'CI',
                        'numero_documento' => '13244964',
                        'fecha_nacimiento' => '2001-01-05',
                    ],
                    'afiliacion' => [
                        'fecha_inicio'       => '',
                        'parentesco_titular' => '',
                    ],
                ],
            ],
        ];*/

        //$servicioAsussBeneficiario->registrarBeneficiario($arrayDatosAfiliacionBeneficiarioMomentaneo);

        //$servicioAsussBeneficiario->contrastarBeneficiario($arrayDatosContrastacionBeneficiarioMomentaneo);
    }
}
