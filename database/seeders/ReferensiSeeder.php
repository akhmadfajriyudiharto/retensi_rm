<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReferensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('layanans')->insert([
            [
                'id' => 1,
                'nama' => 'Rawat Inap',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'nama' => 'Rawat Jalan',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'nama' => 'Kegawatdaruratan',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);


        DB::table('kasuses')->insert([
            // RAWAT INAP
            [
                'layanan_id' => 1,
                'nama' => 'Jantung Koroner',
                'aktif' => 5,
                'inaktif' => 25,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'layanan_id' => 1,
                'nama' => 'Stroke Iskemik',
                'aktif' => 4,
                'inaktif' => 20,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'layanan_id' => 1,
                'nama' => 'Gagal Ginjal Kronik',
                'aktif' => 5,
                'inaktif' => 25,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'layanan_id' => 1,
                'nama' => 'Kanker Paru-paru',
                'aktif' => 5,
                'inaktif' => 30,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'layanan_id' => 1,
                'nama' => 'COVID-19 Berat',
                'aktif' => 3,
                'inaktif' => 10,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // RAWAT JALAN
            [
                'layanan_id' => 2,
                'nama' => 'Hipertensi',
                'aktif' => 3,
                'inaktif' => 10,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'layanan_id' => 2,
                'nama' => 'Diabetes Melitus',
                'aktif' => 3,
                'inaktif' => 15,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'layanan_id' => 2,
                'nama' => 'Asma',
                'aktif' => 2,
                'inaktif' => 10,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'layanan_id' => 2,
                'nama' => 'ISPA (Infeksi Saluran Pernapasan Akut)',
                'aktif' => 1,
                'inaktif' => 5,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'layanan_id' => 2,
                'nama' => 'Demam Berdarah Dengue',
                'aktif' => 2,
                'inaktif' => 7,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // KEGAWATDARURATAN
            [
                'layanan_id' => 3,
                'nama' => 'Kecelakaan Lalu Lintas',
                'aktif' => 3,
                'inaktif' => 10,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'layanan_id' => 3,
                'nama' => 'Serangan Jantung Akut',
                'aktif' => 5,
                'inaktif' => 20,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'layanan_id' => 3,
                'nama' => 'Syok Anafilaksis',
                'aktif' => 2,
                'inaktif' => 5,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'layanan_id' => 3,
                'nama' => 'Stroke Hemoragik',
                'aktif' => 4,
                'inaktif' => 15,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'layanan_id' => 3,
                'nama' => 'Henti Napas',
                'aktif' => 1,
                'inaktif' => 5,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
