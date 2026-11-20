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
        $this->call(ReferenceDataSeeder::class);

        // Prvi superadmin se stvara samo ako su podaci upisani u .env
        // (SEED_SUPERADMIN_NAME, SEED_SUPERADMIN_EMAIL, SEED_SUPERADMIN_PASSWORD).
        // Lozinka se nikad ne upisuje u kod.
        $name = env('SEED_SUPERADMIN_NAME');
        $email = env('SEED_SUPERADMIN_EMAIL');
        $password = env('SEED_SUPERADMIN_PASSWORD');

        if ($name && $email && $password) {
            User::updateOrCreate(
                ['email' => $email],
                ['name' => $name, 'password' => Hash::make($password), 'role_id' => 4],
            );
            $this->command?->info("Superadmin '{$name}' je spreman.");
        } else {
            $this->command?->warn('Superadmin nije stvoren: postavite SEED_SUPERADMIN_* u .env.');
        }
    }
}
