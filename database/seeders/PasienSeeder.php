<?php

namespace Database\Seeders;

use App\Models\Pasien;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PasienSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        for ($i = 0; $i < 20; $i++) {
            Pasien::create([
                'no_rm'          => 'RM' . str_pad($i + 1, 5, '0', STR_PAD_LEFT),
                'nama'           => $faker->name,
                'nik'            => $faker->numerify('35##############'), // 16 digit, awalan khas NIK
                'jenis_kelamin'  => $faker->randomElement(['L', 'P']),
                'tanggal_lahir'  => $faker->dateTimeBetween('-70 years', '-10 years')->format('Y-m-d'),
                'alamat'         => $faker->address,
                'telepon'        => '08' . $faker->numerify('##########'),
                'email'          => $faker->optional()->safeEmail,
            ]);
        }
    }
}
