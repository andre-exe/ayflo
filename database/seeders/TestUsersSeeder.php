<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class TestUsersSeeder extends Seeder
{
    public function run()
    {
        // Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@ayflo.com'],
            ['name' => 'Administrador', 'password' => bcrypt('ayfl0admin.')]
        );
        $admin->assignRole('admin');

        // Dibujante
        $dibujante = User::firstOrCreate(
            ['email' => 'dibujante@ayflo.com'],
            ['name' => 'Dibujante', 'password' => bcrypt('dib.ayfl0')]
        );
        $dibujante->assignRole('dibujante');

        // Campo
        $campo = User::firstOrCreate(
            ['email' => 'campo@ayflo.com'],
            ['name' => 'Campo', 'password' => bcrypt('ayflocamp0')]
        );
        $campo->assignRole('campo');
    }
}
