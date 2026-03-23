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
        // Periode 2023
        DiklatPeriod::create([
            'nama_periode' => 'Periode Diklat Angkatan 2023',
            'tahun_masuk' => 2023,
            'rekening_number' => '1234567890123456',
            'rekening_info' => "Nama: Satya Palapa UKM\nBank: BCA\nNo Rekening: 1234567890123456\nCabang: Surabaya\n\nTransfer ke rekening ini sebagai bukti pembayaran diklat.",
            'is_open' => false,
            'tanggal_buka' => now()->subMonths(12),
            'tanggal_tutup' => now()->subMonths(11),
            'keterangan' => 'Periode diklat untuk angkatan 2023 - sudah ditutup',
        ]);

        // Periode 2024
        DiklatPeriod::create([
            'nama_periode' => 'Periode Diklat Angkatan 2024',
            'tahun_masuk' => 2024,
            'rekening_number' => '9876543210987654',
            'rekening_info' => "Nama: Satya Palapa UKM\nBank: Mandiri\nNo Rekening: 9876543210987654\nCabang: Surabaya\n\nTransfer ke rekening ini sebagai bukti pembayaran diklat.",
            'is_open' => true,
            'tanggal_buka' => now()->subMonths(2),
            'tanggal_tutup' => now()->addMonths(1),
            'keterangan' => 'Periode diklat untuk angkatan 2024 - sedang dibuka',
        ]);
    }
}
