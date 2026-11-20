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
            // samo dodaje; postojećem korisniku nikad ne mijenja lozinku ni ulogu
            if (User::where('email', $email)->orWhere('name', $name)->exists()) {
                $this->command?->warn("Korisnik '{$name}' / {$email} već postoji, ne mijenjam ga.");
            } else {
                User::create(['name' => $name, 'email' => $email, 'password' => Hash::make($password), 'role_id' => 4]);
                $this->command?->info("Superadmin '{$name}' je stvoren.");
            }
        } else {
            $this->command?->warn('Superadmin nije stvoren: postavite SEED_SUPERADMIN_* u .env.');
        }
    }
}
