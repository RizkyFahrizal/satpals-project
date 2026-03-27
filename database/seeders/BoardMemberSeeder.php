<?php

namespace Database\Seeders;

use App\Models\BoardMember;
use App\Models\Member;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BoardMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing board members
        BoardMember::truncate();

        // Use BoardMember::getCurrentPeriode() to get proper format (e.g., "2025/2026" for March 2026)
        $currentPeriode = BoardMember::getCurrentPeriode();
        $diklatPeriodId = 1; // Periode Diklat Angkatan 2023

        // Define board structure
        $boardStructure = [
            // Badan Pengurus Harian (BPH)
            [
                'member_id' => 1,  // Hylmi Tri Widiastoro - Vocalist
                'jabatan' => 'ketua_umum',
                'periode' => $currentPeriode,
                'diklat_period_id' => $diklatPeriodId,
                'urutan' => 1,
            ],
            [
                'member_id' => 13, // Mirza Ramadian Raffa - Vocalist & Gitarist
                'jabatan' => 'wakil_ketua_umum',
                'periode' => $currentPeriode,
                'diklat_period_id' => $diklatPeriodId,
                'urutan' => 2,
            ],
            [
                'member_id' => 16, // Nurmalinda Rista Widya - Vocalist
                'jabatan' => 'sekretaris',
                'periode' => $currentPeriode,
                'diklat_period_id' => $diklatPeriodId,
                'urutan' => 3,
            ],
            [
                'member_id' => 17, // Isna Nur Rahmawati - Vocalist
                'jabatan' => 'bendahara',
                'periode' => $currentPeriode,
                'diklat_period_id' => $diklatPeriodId,
                'urutan' => 4,
            ],
            [
                'member_id' => 18, // Nabila Aliya Khoirunnisa - Vocalist
                'jabatan' => 'mpa',
                'periode' => $currentPeriode,
                'diklat_period_id' => $diklatPeriodId,
                'urutan' => 5,
            ],

            // Subsie Band (Musik)
            [
                'member_id' => 2,  // CHOIRUL WAHYU ADJI - Bassist
                'jabatan' => 'subsie_band',
                'periode' => $currentPeriode,
                'diklat_period_id' => $diklatPeriodId,
                'urutan' => 6,
            ],
            [
                'member_id' => 3,  // Ibe Zisokhi Laia - Gitarist
                'jabatan' => 'subsie_band',
                'periode' => $currentPeriode,
                'diklat_period_id' => $diklatPeriodId,
                'urutan' => 7,
            ],
            [
                'member_id' => 6,  // Muhammad Bintang Naufal - Drummer
                'jabatan' => 'subsie_band',
                'periode' => $currentPeriode,
                'diklat_period_id' => $diklatPeriodId,
                'urutan' => 8,
            ],
            [
                'member_id' => 8,  // Bagas Kusuma Pranata - Keyboardist
                'jabatan' => 'subsie_band',
                'periode' => $currentPeriode,
                'diklat_period_id' => $diklatPeriodId,
                'urutan' => 9,
            ],

            // Subsie Peralatan
            [
                'member_id' => 4,  // Sulthan Wahyu Atmojo - Gitarist
                'jabatan' => 'subsie_peralatan',
                'periode' => $currentPeriode,
                'diklat_period_id' => $diklatPeriodId,
                'urutan' => 10,
            ],
            [
                'member_id' => 5,  // Muhammad Riski Anandio - Bassist
                'jabatan' => 'subsie_peralatan',
                'periode' => $currentPeriode,
                'diklat_period_id' => $diklatPeriodId,
                'urutan' => 11,
            ],
            [
                'member_id' => 7,  // Theopilus Sinuraya - Gitarist
                'jabatan' => 'subsie_peralatan',
                'periode' => $currentPeriode,
                'diklat_period_id' => $diklatPeriodId,
                'urutan' => 12,
            ],

            // Subsie Humas (Public Relations)
            [
                'member_id' => 9,  // Cleo Firman Ferdinand - Gitarist
                'jabatan' => 'subsie_humas',
                'periode' => $currentPeriode,
                'diklat_period_id' => $diklatPeriodId,
                'urutan' => 13,
            ],
            [
                'member_id' => 10, // Fachry Akbar Putra Ediesthia - Gitarist
                'jabatan' => 'subsie_humas',
                'periode' => $currentPeriode,
                'diklat_period_id' => $diklatPeriodId,
                'urutan' => 14,
            ],

            // Subsie Produksi dan Dokumentasi (PDD)
            [
                'member_id' => 11, // Michael Gideon Artanarga Pakpahan - Vocalist & Drummer
                'jabatan' => 'subsie_pdd',
                'periode' => $currentPeriode,
                'diklat_period_id' => $diklatPeriodId,
                'urutan' => 15,
            ],
            [
                'member_id' => 12, // Satria Alanku Yudhita Putra - Gitarist
                'jabatan' => 'subsie_pdd',
                'periode' => $currentPeriode,
                'diklat_period_id' => $diklatPeriodId,
                'urutan' => 16,
            ],

            // Subsie Kesekretariatan
            [
                'member_id' => 19, // Nova Amalia Nohara - Vocalist
                'jabatan' => 'subsie_kesekretariatan',
                'periode' => $currentPeriode,
                'diklat_period_id' => $diklatPeriodId,
                'urutan' => 17,
            ],
            [
                'member_id' => 20, // Naela Shafa Rania Putri - Vocalist
                'jabatan' => 'subsie_kesekretariatan',
                'periode' => $currentPeriode,
                'diklat_period_id' => $diklatPeriodId,
                'urutan' => 18,
            ],
        ];

        // Get diklat period for auto-fetching dates
        $diklatPeriod = \App\Models\DiklatPeriod::find($diklatPeriodId);

        // Insert board members
        foreach ($boardStructure as $data) {
            $boardMember = new BoardMember($data);
            
            // Auto-fill dates from diklat period if available
            if ($diklatPeriod) {
                $boardMember->tanggal_buka = $diklatPeriod->tanggal_buka;
                $boardMember->tanggal_tutup = $diklatPeriod->tanggal_tutup;
            }

            $boardMember->save();
        }

        $this->command->info('Board members seeded successfully!');
        $this->command->line('Total board members created: ' . count($boardStructure));
        $this->command->line('');
        $this->command->line('Board Structure:');
        $this->command->line('├─ BPH (Badan Pengurus Harian): 5 orang');
        $this->command->line('├─ Subsie Band: 4 orang');
        $this->command->line('├─ Subsie Peralatan: 3 orang');
        $this->command->line('├─ Subsie Humas: 2 orang');
        $this->command->line('├─ Subsie PDD: 2 orang');
        $this->command->line('└─ Subsie Kesekretariatan: 2 orang');
    }
}
