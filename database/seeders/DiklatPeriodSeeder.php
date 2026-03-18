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
        // Create sample diklat periods
        DiklatPeriod::create([
            'nama_periode' => 'Diklat Kesenian 2024/2025',
            'tahun_masuk' => 2024,
            'rekening_number' => '1234567890',
            'rekening_info' => "Bank: BCA\nAtas Nama: Satya Palapa\nNo. Rekening: 1234567890\nCabang: Kampus UPN Veteran Jawa Timur",
            'is_open' => true,
            'tanggal_buka' => now(),
            'keterangan' => 'Periode pembukaan pendaftaran angkatan 2024',
        ]);

        DiklatPeriod::create([
            'nama_periode' => 'Diklat Kesenian 2025/2026',
            'tahun_masuk' => 2025,
            'rekening_number' => '0987654321',
            'rekening_info' => "Bank: Mandiri\nAtas Nama: UKM Satya Palapa\nNo. Rekening: 0987654321\nCabang: Surabaya",
            'is_open' => false,
            'keterangan' => 'Periode untuk angkatan 2025 (belum dibuka)',
        ]);
    }
}
