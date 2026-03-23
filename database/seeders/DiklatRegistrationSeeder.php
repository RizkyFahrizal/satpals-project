<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DiklatRegistration;
use App\Models\DiklatPeriod;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DiklatRegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks and clear existing data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DiklatRegistration::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Get all periods for random assignment
        $periods = DiklatPeriod::pluck('id')->toArray();

        $registrations = [
            ['9/11/2024 22:59:55', 'chondulady19@gmail.com', 'CHOIRUL WAHYU ADJI', 'Laki', '24082010122', 'Ilmu Komputer', 'Sistem Informasi', 'Bassist', '085259487706'],
            ['9/11/2024 22:29:35', 'disainariestaperdanaraharja@gmail.com', 'Disain Ariesta Perdana Raharja', 'Laki', '24081010321', 'Ilmu Komputer', 'Informatika', 'Gitarist', '081338512302'],
            ['9/12/2024 9:57:16', 'ibezlaia@gmail.com', 'Ibe Zisokhi Laia', 'Laki', '24081010085', 'Ilmu Komputer', 'Informatika', 'Gitarist', '081230156692'],
            ['9/14/2024 10:05:51', 'Kartikaprabaningrum01@gmail.com', 'Sulthan Wahyu Atmojo', 'Laki', '24024010230', 'Ekonomi Dan Bisnis', 'Agribisnis', 'Gitarist', '082234592658'],
            ['9/14/2024 18:08:17', 'rizkyanandio09@gmail.com', 'Muhammad Riski Anandio', 'Laki', '24012010449', 'Ekonomi Bisnis', 'Manajemen', 'Bassist', '081230850704'],
            ['9/14/2024 19:39:51', 'setiawanalfin999@gmail.com', 'Moh Alfin Setiawan', 'Laki', '24035010093', 'Teknik', 'Teknik Sipil', 'Vocalist', '085708857761'],
            ['9/14/2024 14:35:25', 'greeze77@gmail.com', 'Muhammad Bintang Naufal', 'Laki', '24081010138', 'Ilmu Komputer', 'Informatika', 'Drummer', '083192812629'],
            ['9/14/2024 17:52:41', 'jozhuakurniawan@gmail.com', 'Joshua Kurniawan', 'Laki', '24013010157', 'Fakultas Ekonomi dan Akuntansi', 'Akuntansi', 'Gitarist, Keyboardist', '085159773035'],
            ['9/14/2024 18:01:23', 'theosinuraya241@gmail.com', 'Theopilus Sinuraya', 'Laki', '22024010146', 'Pertanian', 'Agribisnis', 'Gitarist', '081262906564'],
            ['9/14/2024 18:02:24', 'bagaspranata85@gmail.com', 'Bagas Kusuma Pranata', 'Laki', '24035010007', 'Teknik dan Sains', 'Teknik Sipil', 'Keyboardist', '083832622701'],
            ['9/14/2024 18:05:33', 'nailaaudies@gmail.com', 'Naila Audie Shayna', 'Perempuan', '24052010056', 'Arsitektur dan Desain', 'DKV', 'Vocalist, Bassist', '088297916922'],
            ['9/14/2024 19:05:22', 'ferdyowsem@gmail.com', 'Cleo Firman Ferdinand', 'Laki', '24081010173', 'Ilmu Komputer', 'Informatika', 'Gitarist', '085646458409'],
            ['9/14/2024 17:47:00', 'dkenzie431@gmail.com', 'Devan Aprila Cipta Rajasa', 'Laki', '24036010068', 'Teknik', 'Teknik Mesin', 'Gitarist', '085335318746'],
            ['9/14/2024 18:19:35', 'aryaprabu173@gmail.com', 'Arya Firmansyah Wicaksana', 'Laki', '24031010015', 'Teknik dan Sains', 'Teknik Kimia', 'Gitarist, Bassist, Drummer', '089530704665'],
            ['9/14/2024 17:47:00', 'arthaliando79@gmail.com', 'Chrisena Arthaliando Putra', 'Laki', '24031010240', 'Fakultas Teknik Dan Sains', 'Teknik Kimia', 'Gitarist', '081252371486'],
            ['9/15/2024 15:41:42', 'galangterang5@gmail.com', 'Galang Terang Christiano', 'Laki', '24043010139', 'Fakultas Ilmu Sosial dan Ilmu Politik', 'Ilmu Komunikasi', 'Gitarist, Keyboardist, Drummer', '081339410269'],
            ['9/14/2024 19:26:42', 'fachryakbarpe@gmail.com', 'Fachry Akbar Putra Ediesthia', 'Laki', '24081010056', 'Ilmu Komputer', 'Informatika', 'Gitarist', '081915150348'],
            ['9/25/2024 23:53:15', 'hylmitrii@gmail.com', 'Hylmi Tri Widiastoro', 'Laki', '24032010028', 'Teknik dan Sains', 'Teknik Industri', 'Vocalist', '0895331177032'],
            ['9/14/2024 17:47:00', 'michaelgap051@gmail.com', 'Michael Gideon Artanarga Pakpahan', 'Laki', '24071010340', 'Hukum', 'Ilmu Hukum', 'Vocalist, Drummer', '081266418480'],
            ['9/21/2024 16:57:41', 'alifmarcellino06@gmail.com', 'Bagas Alif Marcellino', 'Laki', '24043010300', 'Ilmu Sosial dan Ilmu Politik', 'Ilmu Komunikasi', 'Vocalist, Drummer', '081939457729'],
            ['9/14/2024 21:01:22', 'fauzan1464@gmail.com', 'Fauzan Rahmatullah', 'Laki', '24081010030', 'Fakultas Ilmu Komputer', 'Informatika', 'Gitarist, Bassist', '085850801262'],
            ['9/23/2024 15:34:31', 'wisanggeni.arrizal.wa@gmail.com', 'Wisanggeni Arrizal Shalikhin', 'Laki', '24071010322', 'Fakultas Hukum', 'Hukum', 'Gitarist', '081216972360'],
            ['9/14/2024 22:57:42', 'satriaalanku@gmail.com', 'Satria Alanku Yudhita Putra', 'Laki', '24045010050', 'Ilmu Sosial dan Ilmu Politik', 'Pariwisata', 'Gitarist', '089685642314'],
            ['9/14/2024 16:52:48', 'raffaaws@gmail.com', 'Mirza Ramadian Raffa', 'Laki', '24082010241', 'Ilmu Komputer', 'Sistem Informasi', 'Vocalist, Gitarist', '081336831221'],
            ['9/14/2024 10:26:35', 'haikalnauvaldi.polsat@gmail.com', 'Muhamad Haikal Nauvaldi', 'Laki', '24032010168', 'FTS', 'Teknik Industri', 'Gitarist', '085932940296'],
            ['9/17/2024 11:45:42', 'Rafaelricard22@gmail.com', 'Rafael Firdaus Ricardo Niuflapu', 'Laki', '24052010040', 'FAD', 'DKV', 'Keyboardist', '0881036721600'],
            ['9/21/2024 17:45:15', 'nindyapramana123@gmail.com', 'Nindya Pramana', 'Laki', '24081010197', 'Ilmu Komputer', 'Informatika', 'Gitarist, Bassist, Keyboardist', '085155446285'],
            ['9/21/2024 21:47:45', 'arifudinmuhamad12@gmail.com', 'Arifudin Maunillah Muhamad', 'Laki', '23025010068', 'Pertanian', 'Agroteknologi', 'Drummer', '082324117298'],
            ['9/24/2024 14:24:49', 'nasywaaarisandy@gmail.com', 'R. Aj. Nasywaa Tsaabitah Arisandy', 'Perempuan', '24052010087', 'Arsitektur dan Desain', 'DKV', 'Vocalist', '085259171895'],
            ['9/21/2024 22:56:15', 'nadindawinda@gmail.com', 'Nadinda Khairunnisa Windajaya', 'Perempuan', '24034010087', 'Fakultas Teknik dan Sains', 'Teknik Lingkungan', 'Vocalist, Gitarist', '088809592801'],
            ['9/20/2024 22:09:23', 'tanaya0401@gmail.com', 'Putri Tanaya Honesty Aurellya', 'Perempuan', '24043010100', 'Ilmu Sosial dan Ilmu Politik', 'Ilmu Komunikasi', 'Vocalist', '088809592801'],
            ['9/21/2024 20:56:15', 'slwzhr851@gmail.com', 'Nurmalinda Rista Widya', 'Perempuan', '24024010073', 'Pertanian', 'Agribisnis', 'Vocalist', '085776547820'],
            ['9/14/2024 22:02:19', 'isnanurrahmawati62@gmail.com', 'Isna Nur Rahmawati', 'Perempuan', '24041010141', 'Ilmu Sosial dan Ilmu Politik', 'Administrasi Publik', 'Vocalist', '082334110426'],
            ['9/14/2024 22:22:05', 'putrisayogo19@gmail.com', 'Rahadyani Arafah Putri Sayoga', 'Perempuan', '24013010240', 'Ekonomi dan Bisnis', 'Akuntansi', 'Vocalist', '081553702868'],
            ['9/25/2024 6:52:00', 'alyssafauziyah@gmail.com', 'Khansa Alyssa Fauziyah', 'Perempuan', '24081010110', 'Ilmu Komputer', 'Informatika', 'Keyboardist', '085696101524'],
            ['9/21/2024 15:57:02', 'nabilaliya2005@gmail.com', 'Nabila Aliya Khoirunnisa', 'Perempuan', '24012010261', 'Ekonomi dan Bisnis', 'Manajemen', 'Vocalist', '083119980889'],
            ['9/14/2024 19:54:07', 'novaanhr@gmail.com', 'Nova Amalia Nohara', 'Perempuan', '24041010172', 'Ilmu Sosial dan Ilmu Politik', 'Administrasi Publik', 'Vocalist', '085730998389'],
            ['9/14/2024 17:47:37', 'naelashafaa@gmail.com', 'Naela Shafa Rania Putri', 'Perempuan', '24071010038', 'Hukum', 'Hukum', 'Vocalist', '081218067853'],
            ['9/14/2024 15:04:41', 'dama.exelly70@gmail.com', 'Exellyana Rahmadani Damayanti', 'Perempuan', '24071010306', 'Hukum', 'Hukum', 'Violinist', '082296624571'],
            ['9/14/2024 13:35:09', 'angelchataryna20@gmail.com', 'Angel Chataryna M', 'Perempuan', '24041010325', 'Fakultas Ilmu Sosial dan Ilmu Politik', 'Administrasi Publik', 'Vocalist', '0881026435038'],
            ['9/14/2024 14:09:28', 'zahlullaili@gmail.com', 'Zahlul Noer Laily', 'Perempuan', '24082010163', 'Ilmu Komputer', 'Sistem Informasi', 'Gitarist', '085234187136'],
            ['9/14/2024 21:51:50', 'yessaagustin8@gmail.com', 'Yessa Adelia Agustin', 'Perempuan', '24042010122', 'Fakultas Ilmu Sosial dan Ilmu Politik', 'Administrasi Bisnis', 'Vocalist', '085746671356'],
            ['9/16/2024 9:38:57', 'aliyahshafanurharyana@gmail.com', 'Aliyah Shafa Nurharyana', 'Perempuan', '24043010101', 'Fakultas Ilmu Sosial dan Ilmu Politik', 'Ilmu Komunikasi', 'Vocalist', '081288007309'],
            ['9/16/2024 10:06:31', 'aisyahzahira0516@gmail.com', 'Aisyah Zahira Shofa', 'Perempuan', '24033010087', 'Fakultas Teknik dan Sains', 'Teknologi Pangan', 'Keyboardist', '081515766249'],
            ['9/17/2024 12:21:44', 'dinaselo16@gmail.com', 'Dina Selomita M.S', 'Perempuan', '24042010223', 'FISIP', 'Administrasi Bisnis', 'Keyboardist', '085730446889'],
            ['9/17/2024 10:15:15', 'chelseanaila37@gmail.com', 'Chelsea Naila Sanjaya', 'Perempuan', '24011010187', 'Ekonomi dan Bisnis', 'Ekonomi Pembangunan', 'Gitarist', '085736844381'],
            ['9/18/2024 12:22:02', 'dianalerti@gmail.com', 'Dian Alerti', 'Perempuan', '24045010016', 'FISIP', 'Pariwisata', 'Gitarist', '085773169268'],
            ['9/21/2024 5:10:57', 'riskidwiananda2@gmail.com', 'Riski Dwi Ananda', 'Perempuan', '24043010131', 'Fakultas Ilmu Sosial dan Ilmu Politik', 'Ilmu Komunikasi', 'Vocalist', '085233840804'],
            ['9/21/2024 16:20:28', 'rhan3578@gmail.com', 'Renanti Putri Gunawan', 'Perempuan', '24045010004', 'Ilmu Sosial dan Ilmu Politik', 'Pariwisata', 'Vocalist, Gitarist', '085714244796'],
            ['9/22/2024 9:31:11', 'nadyasavitriswastika@gmail.com', 'I Gst Ayu Nadya Savitri Putri Swastika', 'Perempuan', '24071010211', 'Hukum', 'Hukum', 'Vocalist', '082230119271'],
            ['9/22/2024 20:00:25', 'mayaaksafibella@gmail.com', 'Maya Aksa Fibellanindy', 'Perempuan', '24081010221', 'Ilmu Komputer', 'Informatika', 'Vocalist', '087888557433'],
            ['9/22/2024 0:54:50', 'marthawidhi03@gmail.com', 'Hangesthi Martha Widhi Wicakseni', 'Perempuan', '24035010021', 'Teknik dan Sains', 'Teknik Sipil', 'Vocalist', '081234553091'],
            ['9/22/2024 7:06:21', 'sarahjssica14@gmail.com', 'Sarah Jessica Sigalingging', 'Perempuan', '24032010194', 'Teknik', 'Teknik Industri', 'Vocalist', '087864014955'],
            ['9/22/2024 18:00:58', 'hangnestiintan@gmail.com', 'Hangesti Intan Hartanto', 'Perempuan', '24013010059', 'Ekonomi dan Bisnis', 'Akuntansi', 'Vocalist', '081228268504'],
            ['9/22/2024 18:44:42', 'vinadwi360@gmail.com', 'Vina Dwi Maulita', 'Perempuan', '24082010074', 'Ilmu Komputer', 'Sistem Informasi', 'Vocalist', '081393501415'],
            ['9/22/2024 21:22:32', 'alyadn156@gmail.com', 'Alya Dinar Putri Dewanto', 'Perempuan', '24024010104', 'Pertanian', 'Agribisnis', 'Vocalist', '081338972625'],
            ['9/22/2024 22:27:25', 'rinnasantianaagung@gmail.com', 'Rinna Santiana Agung', 'Perempuan', '24042010046', 'Ilmu Sosial dan Ilmu Politik', 'Administrasi Bisnis', 'Vocalist', '08993771827'],
            ['9/14/2024 18:01:38', 'eunikesimbolon060@gmail.com', 'Eunike Naema Simbolon', 'Perempuan', '24013010097', 'Ekonomi dan Bisnis', 'Akuntansi', 'Vocalist, Keyboardist', '0895338654487'],
        ];

        foreach ($registrations as $data) {
            // Parse timestamp from Excel format (M/DD/YYYY HH:MM:SS)
            $timestamp = Carbon::createFromFormat('n/j/Y H:i:s', $data[0]);
            
            // Parse spesifikasi to array
            $spesifikasiRaw = $data[7];
            $spesifikasiArray = $this->parseSpesifikasi($spesifikasiRaw);

            // Clean phone number
            $phone = $this->cleanPhoneNumber($data[8]);

            // Parse jenis kelamin
            $jenisKelamin = strtolower($data[3]) === 'laki' ? 'laki-laki' : 'perempuan';

            DiklatRegistration::create([
                'diklat_period_id' => collect($periods)->random(),
                'nama_lengkap' => trim($data[2]),
                'jenis_kelamin' => $jenisKelamin,
                'no_telepon_pribadi' => $phone,
                'no_telepon_ortu' => null,
                'npm' => trim($data[4]),
                'fakultas' => trim($data[5]),
                'prodi' => trim($data[6]),
                'spesifikasi' => $spesifikasiArray,
                'bukti_pembayaran' => null,
                'riwayat_penyakit' => null,
                'riwayat_alergi' => null,
                'status' => 'pending',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);
        }

        $this->command->info('Successfully seeded ' . count($registrations) . ' diklat registrations!');
    }

    /**
     * Parse spesifikasi string to array
     */
    private function parseSpesifikasi(string $raw): array
    {
        $mapping = [
            'vocalist' => 'vocalist',
            'gitarist' => 'gitarist',
            'bassist' => 'bassist',
            'keyboardist' => 'keyboardist',
            'pianist' => 'keyboardist',
            'piano' => 'keyboardist',
            'drummer' => 'drummer',
            'violin' => 'violinist',
            'violinist' => 'violinist',
            'biola' => 'violinist',
            'volin' => 'violinist',
            'ukulele' => 'lainnya',
            'menyesuaikan' => 'lainnya',
            'lagi belajar' => 'lainnya',
            'mau belajar' => 'lainnya',
            'masi belajar' => 'lainnya',
            'belum' => 'lainnya',
            'divisi' => 'lainnya',
            'pengurus' => 'lainnya',
        ];

        $result = [];
        $rawLower = strtolower($raw);

        foreach ($mapping as $keyword => $spec) {
            if (str_contains($rawLower, $keyword)) {
                if (!in_array($spec, $result)) {
                    $result[] = $spec;
                }
            }
        }

        // If no match found, add as 'lainnya'
        if (empty($result)) {
            $result[] = 'lainnya';
        }

        return $result;
    }

    /**
     * Clean phone number - remove special characters
     */
    private function cleanPhoneNumber(string $phone): string
    {
        // Remove special characters, spaces, dashes, plus sign
        $cleaned = preg_replace('/[^\d]/', '', $phone);
        
        // If starts with 62, replace with 0
        if (str_starts_with($cleaned, '62')) {
            $cleaned = '0' . substr($cleaned, 2);
        }
        
        // If doesn't start with 0, add 0
        if (!str_starts_with($cleaned, '0') && strlen($cleaned) > 0) {
            $cleaned = '0' . $cleaned;
        }

        return $cleaned;
    }
}
