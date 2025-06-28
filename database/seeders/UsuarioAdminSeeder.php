<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User as Usuario;
use Illuminate\Support\Facades\Hash;
class UsuarioAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         Usuario::create([
            'name' => 'Administrador',
            'email' => 'admin@campeonato.com',
            'password' => Hash::make('admin123'),
            'role' => 1, // 1 = admin
            'idclub' => null,
        ]);

        Usuario::create([
            'name' => 'Dirigente',
            'email' => 'dirigente@campeonato.com',
            'password' => Hash::make('dirigente123'),
            'role' => 2, // 2 = dirigente
            'idclub' => null,
        ]);

        Usuario::create([
            'name' => 'Invitado',
            'email' => 'invitado@campeonato.com',
            'password' => Hash::make('invitado123'),
            'role' => 3, // 3 = invitado
            'idclub' => null,
        ]);

        
    }
}
