<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Primero limpiamos cache de permisos/roles
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        //permisos por módulo
        $modules = [
            'clientes',
            'empleados',
            'egresos',
            'trabajos',
            'bitacoras',
            'responsables',
        ];

        $actions = ['index', 'create', 'edit', 'delete'];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => "{$module}.{$action}"]);
            }
        }

        // roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $dibujante = Role::firstOrCreate(['name' => 'dibujante']);
        $campo = Role::firstOrCreate(['name' => 'campo']);

        // permisos

        // Admin tiene todos los permisos
        $admin->givePermissionTo(Permission::all());

        // Dibujante -> solo trabajos.index y trabajos.edit
        $dibujante->givePermissionTo([
            'trabajos.index',
            'trabajos.edit',
        ]);

        // Campo -> solo trabajos.index y trabajos.create
        $campo->givePermissionTo([
            'trabajos.index',
            'trabajos.create',
        ]);
    }
}
