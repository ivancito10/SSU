<?php

namespace Database\Seeders;

use App\Models\Administracion\Permission;
use App\Models\Administracion\Role;
use Illuminate\Database\Seeder;

class RolePermissionsEcografiaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rolePlataforma = Role::firstOrCreate([
            'name' => 'Encargado Plataforma',
            'guard_name' => 'web'
        ]);

        $permisosEcografia = [
            // Menú
            'menu.agenda_ecografia',
            'menu.anulacion_ecografia',
            'menu.suspension_ecografia',

            // Agenda Ecografía
            'ver.agenda_ecografia',
            'agendar.agenda_ecografia',
            'lista.agenda_ecografia',

            // Anulación Ecografía
            'ver.anulacion_ecografia',
            'registrar.anulacion_ecografia',
            'lista.anulacion_ecografia',

            // Suspensión Ecografía
            'ver.suspension_ecografia',
            'registrar.suspension_ecografia',
            'lista.suspension_ecografia',
            'desabilitar.suspension_ecografia',

        ];


        foreach ($permisosEcografia as $nombrePermiso) {
            $permiso = Permission::firstOrCreate(
                [
                    'name' => $nombrePermiso,
                    'guard_name' => 'web'
                ],
                [
                    'permissions_enabled' => true
                ]
            );

            $permiso->syncRoles([$rolePlataforma]);
        }


        $roleMed = Role::firstOrCreate([
            'name' => 'Med. Ecografia',
            'guard_name' => 'web'
        ]);

        $permisosMed = [
            'menu.agenda_ecografia_etapa2',
            'ver_agendas_anteriores.agenda_ecografia_etapa2',
            'ver.agenda_ecografia_etapa2',
            'lista.agenda_ecografia_etapa2',
        ];

        foreach ($permisosMed as $nombrePermiso) {
            $permiso = Permission::firstOrCreate(
                ['name' => $nombrePermiso, 'guard_name' => 'web'],
                ['permissions_enabled' => true]
            );
            $permiso->syncRoles([$roleMed]);
        }
    }
}
