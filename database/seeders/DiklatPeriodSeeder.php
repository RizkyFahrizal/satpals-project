<?php

namespace Database\Seeders;

use App\Models\DiklatPeriod;
use Illuminate\Database\Seeder;

class DiklatPeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DiklatPeriod::create([
            'nama_periode' => 'Periode 1',
            'tahun_masuk' => 2025,
            'is_open' => true,
            'tanggal_buka' => '2025-01-15',
            'tanggal_tutup' => '2025-02-28',
            'keterangan' => 'Periode Diklat Pertama',
        ]);

        DiklatPeriod::create([
            'nama_periode' => 'Periode 2',
            'tahun_masuk' => 2025,
            'is_open' => true,
            'tanggal_buka' => '2025-04-01',
            'tanggal_tutup' => '2025-05-31',
            'keterangan' => 'Periode Diklat Kedua',
        ]);
    }
}
