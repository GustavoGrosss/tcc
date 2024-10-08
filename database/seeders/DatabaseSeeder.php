<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         \App\Models\User::create([
             'name' => 'Admin',
             'email' => 'admin@admin.com',
             'password' => Hash::make('admin'),
             'documento' => '06422392907',
             'tipo' => 'A',
             'data_nascimento' => Carbon::parse('1999-07-20'),
         ]);

         \App\Models\DiaSemana::create([
             'nome' => 'Domingo',
         ]);

        \App\Models\DiaSemana::create([
            'nome' => 'Segunda-feira',
        ]);

        \App\Models\DiaSemana::create([
            'nome' => 'Terça-feira',
        ]);

        \App\Models\DiaSemana::create([
            'nome' => 'Quarta-feira',
        ]);

        \App\Models\DiaSemana::create([
            'nome' => 'Quinta-feira',
        ]);

        \App\Models\DiaSemana::create([
            'nome' => 'Sexta-feira',
        ]);

        \App\Models\DiaSemana::create([
            'nome' => 'Sábado',
        ]);
    }
}
