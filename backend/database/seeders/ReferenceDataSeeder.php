<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Osnovni podaci koje aplikacija treba za rad.
 * ID-jevi su fiksni jer ih frontend i backend koriste izravno
 * (npr. role_id 2 = kupac, 3/4 = admin, category_id 1 = voće,
 * payment_method_id 1 = pouzećem, 2 = kartica).
 *
 * Seeder SAMO DODAJE retke koji nedostaju (po ID-ju). Postojeće retke
 * nikad ne mijenja, pa ga je sigurno pokrenuti i na bazi s podacima.
 */
class ReferenceDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->insertMissing('roles', [
            1 => 'gost',
            2 => 'kupac',
            3 => 'admin',
            4 => 'superadmin',
        ]);

        $this->insertMissing('categories', [
            1 => 'Voće',
            2 => 'Povrće',
            3 => 'Gotovi proizvodi',
        ]);

        $this->insertMissing('payment_methods', [
            1 => 'Pouzećem',
            2 => 'Kartica',
        ]);
    }

    private function insertMissing(string $table, array $rows): void
    {
        $existing = DB::table($table)->whereIn('id', array_keys($rows))->pluck('id')->all();
        $now = now();
        $added = 0;

        foreach ($rows as $id => $name) {
            if (in_array($id, $existing)) {
                continue; // postojeći redak se ne dira
            }

            DB::table($table)->insert([
                'id' => $id,
                'name' => $name,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $added++;
        }

        $this->command?->line("  {$table}: dodano {$added}, postojećih (nedirnuto) " . count($existing));
    }
}
