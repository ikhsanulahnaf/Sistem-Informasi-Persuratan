<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Admin
        User::create([
            'name' => 'Admin Sistem',
            'email' => 'admin@iti.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'unit' => null,
        ]);

        // Rektor
        User::create([
            'name' => 'Prof. Dr. Ir. Budi Santoso, M.T.',
            'email' => 'rektor@iti.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'rektor',
            'unit' => 'Rektorat',
        ]);

        // Wakil Rektor I (Akademik)
        User::create([
            'name' => 'Dr. Ani Wijaya, S.Kom., M.T.',
            'email' => 'wr1@iti.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'wakil_rektor',
            'unit' => 'Wakil Rektor I - Akademik',
        ]);

        // Departemen: Bidang Akademik
        User::create([
            'name' => 'Dina Kusuma',
            'email' => 'akademik@iti.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'departemen',
            'unit' => 'Bidang Akademik',
        ]);

        // Departemen: Bidang Keuangan
        User::create([
            'name' => 'Rudi Hartono',
            'email' => 'keuangan@iti.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'departemen',
            'unit' => 'Bidang Keuangan',
        ]);
    }
}
