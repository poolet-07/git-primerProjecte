<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TestingDataSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database with deterministic testing rows.
     */
    public function run(): void
    {
        $now = now();

        foreach (range(1, 3) as $index) {
            $this->firstOrInsert('users',
                ['email' => "test-user-{$index}@example.test"],
                [
                    'name' => "Usuari de prova {$index}",
                    'password' => Hash::make('test1234'),
                    'email_verified_at' => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            );

            $this->firstOrInsert('administradors',
                ['user' => "test_admin_{$index}"],
                [
                    'pwd' => Hash::make('test1234'),
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            );
        }

        $familyUserIds = [];
        $doctorUserIds = [];

        foreach (range(1, 3) as $index) {
            $familyUserIds[$index] = $this->firstOrInsert('usuaris',
                ['correu' => "familia.prova{$index}@example.test"],
                [
                    'nom' => "Família de prova {$index}",
                    'pwd' => Hash::make('test1234'),
                    'telefon' => "60000000{$index}",
                    'dni' => "TESTF00{$index}",
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            )->id;

            $doctorUserIds[$index] = $this->firstOrInsert('usuaris',
                ['correu' => "metge.prova{$index}@example.test"],
                [
                    'nom' => "Metge de prova {$index}",
                    'pwd' => Hash::make('test1234'),
                    'telefon' => "70000000{$index}",
                    'dni' => "TESTM00{$index}",
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            )->id;
        }

        foreach (range(1, 3) as $index) {
            $this->firstOrInsert('families',
                ['user_id' => $familyUserIds[$index]],
                [
                    'foto_csns' => "test/families/familia-{$index}.jpg",
                    'what_csns' => "Grup de prova {$index}",
                    'dades_csns' => "Dades de contacte de prova {$index}",
                    'direccio' => "Carrer de Prova {$index}, 1",
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            );

            $this->firstOrInsert('metges',
                ['user_id' => $doctorUserIds[$index]],
                [
                    'hospital' => "Hospital de Prova {$index}",
                    'pertinenca' => $index !== 2,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            );

            $this->firstOrInsert('pacients',
                [
                    'fam_id' => $familyUserIds[$index],
                    'nom' => "Pacient de prova {$index}",
                ],
                [
                    'data_naix' => sprintf('201%d-05-15', $index),
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            );

            $this->firstOrInsert('publicacions',
                ['url' => "https://example.test/publicacions/prova-{$index}"],
                [
                    'autor' => "Autor de prova {$index}",
                    'any' => 2020 + $index,
                    'revista' => "Revista de Prova {$index}",
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            );
        }

        $trobadaIds = [];

        foreach (range(1, 3) as $index) {
            $trobadaIds[$index] = $this->firstOrInsert('trobades',
                [
                    'data' => sprintf('2026-%02d-15', $index),
                    'ciutat' => "Ciutat de prova {$index}",
                ],
                [
                    'hora' => sprintf('%02d:30:00', $index + 8),
                    'pdf_info' => "test/trobades/info-{$index}.pdf",
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            )->trob_id;
        }

        $presentacioIds = [];

        foreach (range(1, 3) as $index) {
            $presentacioIds[$index] = $this->firstOrInsert('presentacions',
                [
                    'ponent' => "Ponent de prova {$index}",
                    'data' => sprintf('2026-%02d-20', $index),
                ],
                [
                    'ciutat' => "Ciutat de prova {$index}",
                    'acta' => "test/presentacions/acta-{$index}.pdf",
                    'ordre' => "Ordre del dia de prova {$index}",
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            )->pre_id;
        }

        $serveiIds = [];

        foreach (range(1, 3) as $index) {
            $serveiIds[$index] = $this->firstOrInsert('serveis',
                ['tipus' => "Servei de prova {$index}"],
                [
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            )->servei_id;
        }

        $estats = ['pendent', 'aprovada', 'rebutjada'];

        foreach (range(1, 3) as $index) {
            $this->firstOrInsert('solicitud',
                [
                    'fam_id' => $familyUserIds[$index],
                    'servei_id' => $serveiIds[$index],
                    'estat' => $estats[$index - 1],
                ],
                [
                    'observacions' => "Observacions de prova per a la sol·licitud {$index}.",
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            );

            $this->firstOrInsert('fotos',
                [
                    'trob_id' => $trobadaIds[$index],
                    'ruta' => "test/trobades/foto-{$index}.jpg",
                ],
                [
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            );

            $this->firstOrInsert('fotos_pre',
                [
                    'pre_id' => $presentacioIds[$index],
                    'ruta' => "test/presentacions/foto-{$index}.jpg",
                ],
                [
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            );
        }
    }

    /**
     * Insert a row only when the identifying attributes are not already present.
     *
     * @param  array<string, mixed>  $attributes
     * @param  array<string, mixed>  $values
     */
    private function firstOrInsert(string $table, array $attributes, array $values): object
    {
        $existing = DB::table($table)->where($attributes)->first();

        if ($existing !== null) {
            return $existing;
        }

        DB::table($table)->insert(array_merge($attributes, $values));

        return DB::table($table)->where($attributes)->firstOrFail();
    }
}
