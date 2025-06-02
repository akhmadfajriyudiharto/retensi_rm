<?php

namespace Database\Seeders;

use App\Models\Dokter;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class DokterSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $gelarSpesialis = [
            ['gelar' => 'Sp.PD', 'spesialis' => 'Penyakit Dalam'],
            ['gelar' => 'Sp.OG', 'spesialis' => 'Kandungan'],
            ['gelar' => 'Sp.THT', 'spesialis' => 'Telinga Hidung Tenggorokan'],
            ['gelar' => 'Sp.KJ', 'spesialis' => 'Kesehatan Jiwa'],
            ['gelar' => 'Sp.B', 'spesialis' => 'Bedah Umum'],
            ['gelar' => 'Sp.M', 'spesialis' => 'Mata'],
            ['gelar' => 'Sp.A', 'spesialis' => 'Anak'],
            ['gelar' => 'Sp.S', 'spesialis' => 'Saraf'],
            ['gelar' => 'Sp.JP', 'spesialis' => 'Jantung dan Pembuluh Darah'],
            ['gelar' => 'Sp.Rad', 'spesialis' => 'Radiologi'],
        ];

        for ($i = 0; $i < 10; $i++) {
            $gs = $faker->randomElement($gelarSpesialis);

            Dokter::create([
                'kode_dokter'     => 'DOK' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'nama'            => $faker->firstName . ' ' . $faker->lastName, // TANPA gelar
                'gelar_depan'     => $faker->optional()->randomElement(['dr.', 'drg.', null]),
                'gelar_belakang'  => $gs['gelar'],
                'spesialis'       => $gs['spesialis'],
                'telepon'         => '0355' . $faker->numerify('########'),
                'email'           => $faker->unique()->safeEmail,
                'alamat'          => $faker->address,
                'tanggal_lahir'   => $faker->dateTimeBetween('-60 years', '-30 years')->format('Y-m-d'),
                'jenis_kelamin'   => $faker->randomElement(['L', 'P']),
            ]);
        }
    }
}
