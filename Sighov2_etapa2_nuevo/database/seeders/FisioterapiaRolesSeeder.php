<?php

namespace Database\Seeders;

use App\Models\Administracion\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class FisioterapiaRolesSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Encargado de Fisioterapia', 'Fisioterapeuta'] as $nombre) {
            Role::findOrCreate($nombre, 'web');
        }
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
