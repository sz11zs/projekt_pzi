<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Osnovni podaci koje aplikacija treba za rad.
 * ID-jevi su fiksni jer ih frontend i backend koriste izravno
 * (npr. role_id 2 = kupac, 3/4 = admin, category_id 1 = voće).
 * Seeder se može pokretati više puta bez dupliciranja.
 */
class ReferenceDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->upsert('roles', [
            1 => 'gost',
            2 => 'kupac',
            3 => 'admin',
            4 => 'superadmin',
        ]);

        $this->upsert('categories', [
            1 => 'Voće',
            2 => 'Povrće',
            3 => 'Gotovi proizvodi',
        ]);

        $this->upsert('payment_methods', [
            1 => 'Kartica',
            2 => 'Gotovina',
        ]);
    }

    private function upsert(string $table, array $rows): void
    {
        $now = now();

        foreach ($rows as $id => $name) {
            $exists = DB::table($table)->where('id', $id)->exists();

            DB::table($table)->updateOrInsert(
                ['id' => $id],
                $exists
                    ? ['name' => $name, 'updated_at' => $now]
                    : ['name' => $name, 'created_at' => $now, 'updated_at' => $now],
            );
        }
    }
}
