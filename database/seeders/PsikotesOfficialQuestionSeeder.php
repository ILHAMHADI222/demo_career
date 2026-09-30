<?php

namespace Database\Seeders;

use App\Models\DiscQuestion;
use App\Models\DiscQuestionOption;
use App\Models\PsikotesStageQuestion;
use App\Models\QuestionBank;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PsikotesOfficialQuestionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Dapatkan Bank Soal Standar (utamakan Bank ID 1 yang sedang aktif digunakan, atau kode PSIKOTES-ISP-V1)
        $bank = QuestionBank::find(1) 
            ?? QuestionBank::withTrashed()->where('code', 'PSIKOTES-ISP-V1')->first();

        if ($bank) {
            if (method_exists($bank, 'trashed') && $bank->trashed()) {
                $bank->restore();
            }
            $bank->update([
                'assessment_type' => 'PSIKOTES_3_STAGE',
                'status' => 'PUBLISHED',
            ]);
        } else {
            $bank = QuestionBank::create([
                'code' => 'PSIKOTES-ISP-V1',
                'name' => 'Bank Soal Psikotes Standar PT Indodata Solusi Persada',
                'description' => 'Paket lengkap 3 Sub-Tes Psikotes Hallonet: Sub-Tes 1 Deret Hitung (25 Soal), Sub-Tes 2 Analogi Verbal (50 Soal), dan Sub-Tes 3 Karakteristik Kepribadian (24 Soal).',
                'assessment_type' => 'PSIKOTES_3_STAGE',
                'version' => '1.0',
                'status' => 'PUBLISHED',
            ]);
        }

        // Bersihkan soal stage 1 dan stage 2 untuk bank ini agar idempotent
        PsikotesStageQuestion::where('question_bank_id', $bank->id)->delete();

        // 2. Data Sub-Tes 1: DERET HITUNG (25 Soal)
        $deretQuestions = [
            [1, '4, 8, 12, 16, 20, 24, ..., ...', '28', '32', 'Pola bertambah 4 (+4) setiap angka'],
            [2, '17, 22, 27, 32, 37, 42, ..., ...', '47', '52', 'Pola bertambah 5 (+5) setiap angka'],
            [3, '1, 2, 4, 8, 16, 32, ..., ...', '64', '128', 'Pola dikali 2 (x2) setiap angka'],
            [4, '21, 19, 17, 15, 13, 11, ..., ...', '9', '7', 'Pola berkurang 2 (-2) setiap angka'],
            [5, '12, 1, 9, 1, 6, 1, ..., ...', '3', '1', 'Pola bergantian: deret pertama berkurang 3 (12,9,6,3), deret kedua konstan 1'],
            [6, '1/3, 1, 3, 9, 27, 81, ..., ...', '243', '729', 'Pola dikali 3 (x3) setiap angka'],
            [7, '65, 58, 51, 44, 37, 30, ..., ...', '23', '16', 'Pola berkurang 7 (-7) setiap angka'],
            [8, '31, 30, 28, 25, 21, 16, ..., ...', '10', '3', 'Pola pengurangan bertingkat: -1, -2, -3, -4, -5, -6 (10), -7 (3)'],
            [9, '7, 6, 12, 11, 17, 16, ..., ...', '22', '21', 'Pola selang-seling: -1, +6, -1, +6, -1, +6 (22), -1 (21)'],
            [10, '14, 16, 11, 13, 8, 10, ..., ...', '5', '7', 'Pola selang-seling: +2, -5, +2, -5, +2, -5 (5), +2 (7)'],
            [11, '12, 18, 30, 36, 48, 54, ..., ...', '66', '72', 'Pola selang-seling: +6, +12, +6, +12, +6, +12 (66), +6 (72)'],
            [12, '29, 28, 26, 23, 19, 14, ..., ...', '8', '1', 'Pola pengurangan bertingkat: -1, -2, -3, -4, -5, -6 (8), -7 (1)'],
            [13, '4, 8, 11, 22, 25, 50, ..., ...', '53', '106', 'Pola selang-seling: x2, +3, x2, +3, x2, +3 (53), x2 (106)'],
            [14, '45, 15, 18, 6, 9, 3, ..., ...', '6', '2', 'Pola selang-seling: /3, +3, /3, +3, /3, +3 (6), /3 (2)'],
            [15, '22, 28, 14, 20, 10, 16, ..., ...', '8', '14', 'Pola selang-seling: +6, /2, +6, /2, +6, /2 (8), +6 (14)'],
            [16, '4, 8, 11, 22, 25, 50, ..., ...', '53', '106', 'Pola selang-seling: x2, +3, x2, +3, x2, +3 (53), x2 (106)'],
            [17, '3, 5, 7, 4, 6, 8, ..., ...', '5', '7', 'Pola kelompok 3 angka: (3,5,7), (4,6,8), berikutnya (5,7,9) -> 5, 7'],
            [18, '1, 2, 6, 24, 120, 720, ..., ...', '5040', '40320', 'Pola perkalian bertingkat: x2, x3, x4, x5, x6, x7 (5040), x8 (40320)'],
            [19, '1, 9, 2, 8, 3, 7, ..., ...', '4', '6', 'Dua deret selang-seling: (1,2,3,4) dan (9,8,7,6) -> 4, 6'],
            [20, '37, 44, 27, 34, 17, 24, ..., ...', '7', '14', 'Pola selang-seling: +7, -17, +7, -17, +7, -17 (7), +7 (14)'],
            [21, '8, 3, 4, 6, 2, 12, ..., ...', '1', '24', 'Dua deret selang-seling: (8,4,2,1) dibagi 2, dan (3,6,12,24) dikali 2 -> 1, 24'],
            [22, '276, 272, 68, 64, 16, 12, ..., ...', '3', '-1', 'Pola selang-seling: -4, /4, -4, /4, -4, /4 (3), -4 (-1)'],
            [23, '96, 7, 24, 14, 6, 28, ..., ...', '1.5', '56', 'Dua deret selang-seling: (96,24,6, 1.5) dibagi 4, dan (7,14,28,56) dikali 2 -> 1.5, 56'],
            [24, '180, 171, 153, 126, 90, 45, ..., ...', '-9', '-72', 'Pola pengurangan kelipatan 9: -9, -18, -27, -36, -45, -54 (-9), -63 (-72)'],
            [25, '24, 16, 12, 48/5, 8, 48/7, ..., ...', '6', '16/3', 'Pola rumus 48/n (n=2,3,4,5,6,7,8,9): 48/8 = 6, 48/9 = 16/3'],
        ];

        foreach ($deretQuestions as $item) {
            PsikotesStageQuestion::create([
                'question_bank_id' => $bank->id,
                'stage' => 'STAGE_1_DERET',
                'question_number' => $item[0],
                'question_text' => $item[1],
                'options_json' => null,
                'correct_answer_1' => $item[2],
                'correct_answer_2' => $item[3],
                'explanation' => $item[4],
                'active' => true,
            ]);
        }

        // 3. Data Sub-Tes 2: ANALOGI VERBAL (50 Soal)
        $analogiQuestions = [
            [1, 'Mobil : Bensin = Pelari : ......', ['A' => 'Makanan', 'B' => 'Sepatu', 'C' => 'Kaos', 'D' => 'Lintasan'], 'A', 'Sumber energi bagi mobil adalah bensin, sumber energi bagi pelari adalah makanan'],
            [2, 'Dingin : Selimut = Hujan : ......', ['A' => 'Air', 'B' => 'Payung', 'C' => 'Dingin', 'D' => 'Basah'], 'B', 'Alat pelindung saat dingin adalah selimut, alat pelindung saat hujan adalah payung'],
            [3, 'Semir : Sepatu = Sikat : ......', ['A' => 'Kuku', 'B' => 'Rambut', 'C' => 'Televisi', 'D' => 'Gigi'], 'D', 'Semir digunakan untuk membersihkan/merawat sepatu, sikat (gigi) digunakan untuk gigi'],
            [4, 'Kepala : Pusing = Perut : ......', ['A' => 'Batuk', 'B' => 'Pilek', 'C' => 'Mules', 'D' => 'Gemuk'], 'C', 'Gangguan sakit pada kepala adalah pusing, gangguan sakit pada perut adalah mules'],
            [5, 'Bugil : Pakaian = Gundul : ......', ['A' => 'Botak', 'B' => 'Kepala', 'C' => 'Cukur', 'D' => 'Rambut'], 'D', 'Bugil artinya tanpa pakaian, gundul artinya tanpa rambut'],
            [6, 'Kayu : Pohon = Emas : ......', ['A' => 'Tambang', 'B' => 'Perhiasan', 'C' => 'Mahal', 'D' => 'Logam'], 'A', 'Kayu diperoleh dari pohon, emas diperoleh dari tambang'],
            [7, 'Saya : Kami = Dia : ......', ['A' => 'Kamu', 'B' => 'Mereka', 'C' => 'Anda', 'D' => 'Kita'], 'B', 'Bentuk jamak dari Saya adalah Kami, bentuk jamak dari Dia adalah Mereka'],
            [8, 'Kumis : Kucing = Belalai : ......', ['A' => 'Ular', 'B' => 'Harimau', 'C' => 'Gajah', 'D' => 'Hidung'], 'C', 'Ciri khas kucing adalah kumis, ciri khas gajah adalah belalai'],
            [9, 'Reguler : Senin = Karyawan : ......', ['A' => 'Selasa', 'B' => 'Rabu', 'C' => 'Minggu', 'D' => 'Jumat'], 'A', 'Hari kerja pertama reguler dimulai Senin, analogi karyawan hari kerja'],
            [10, 'Busur : Panah = Senapan : ......', ['A' => 'Peluru', 'B' => 'Senjata', 'C' => 'Berbahaya', 'D' => 'Tembakan'], 'A', 'Busur menembakkan panah, senapan menembakkan peluru'],
            [11, 'Ayah : Anak = Pohon : ......', ['A' => 'Daun', 'B' => 'Tunas', 'C' => 'Ranting', 'D' => 'Akar'], 'B', 'Ayah melahirkan anak (keturunan), pohon menghasilkan tunas'],
            [12, 'Es : Dingin = Gula : ......', ['A' => 'Bubuk', 'B' => 'Kristal', 'C' => 'Tebu', 'D' => 'Manis'], 'D', 'Sifat utama es adalah dingin, sifat utama gula adalah manis'],
            [13, 'Pintar : Belajar = Bodoh : ......', ['A' => 'Cerdas', 'B' => 'Rajin', 'C' => 'Dosen', 'D' => 'Malas'], 'D', 'Pintar karena rajin belajar, bodoh karena malas'],
            [14, 'Terbang : Burung = Jalan : ......', ['A' => 'Jauh', 'B' => 'Singa', 'C' => 'Lebah', 'D' => 'Kupu-kupu'], 'B', 'Aktivitas gerak burung adalah terbang, aktivitas gerak singa adalah berjalan'],
            [15, 'Mobil : Roda = Rumah : ......', ['A' => 'Pondasi', 'B' => 'Tanah', 'C' => 'Jendela', 'D' => 'Atap'], 'A', 'Penopang utama mobil adalah roda, penopang dasar rumah adalah pondasi'],
            [16, 'Bulan : Bumi = Yupiter : ......', ['A' => 'Venus', 'B' => 'Orbit', 'C' => 'Matahari', 'D' => 'Bulan'], 'C', 'Bulan mengelilingi Bumi, Yupiter mengelilingi Matahari'],
            [17, 'Februari : April = Mei : ......', ['A' => 'Juli', 'B' => 'Agustus', 'C' => 'September', 'D' => 'Oktober'], 'A', 'Lompat 1 bulan (Februari -> April), maka Mei -> Juli'],
            [18, 'Ekspor : Pergi = Impor : ......', ['A' => 'Luar', 'B' => 'Dagang', 'C' => 'Masuk', 'D' => 'Asing'], 'C', 'Ekspor mengirim keluar (pergi), impor memasukkan barang (masuk)'],
            [19, 'Mobil : Bensin = Perahu : ......', ['A' => 'Laut', 'B' => 'Angin', 'C' => 'Ombak', 'D' => 'Kayu'], 'B', 'Penggerak mobil adalah bensin, penggerak perahu (layar) adalah angin'],
            [20, 'Mikroskop : Mikroba = Teleskop : ......', ['A' => 'Bakteri', 'B' => 'Bioskop', 'C' => 'Teropong', 'D' => 'Bintang'], 'D', 'Mikroskop untuk melihat mikroba, teleskop untuk melihat bintang'],
            [21, 'Baju : Kancing = Pintu : ......', ['A' => 'Kayu', 'B' => 'Besi', 'C' => 'Baja', 'D' => 'Kunci'], 'D', 'Pengait baju adalah kancing, pengaman/pengunci pintu adalah kunci'],
            [22, 'Jam : Menit = Menit : ......', ['A' => 'Waktu', 'B' => 'Jam', 'C' => 'Detik', 'D' => 'Hari'], 'C', 'Satuan lebih kecil dari Jam adalah Menit, satuan lebih kecil dari Menit adalah Detik'],
            [23, 'Obat : Apotik = Pakaian : ......', ['A' => 'Butik', 'B' => 'Binatu', 'C' => 'Baju', 'D' => 'Celana'], 'A', 'Tempat menjual obat adalah apotik, tempat menjual pakaian adalah butik'],
            [24, 'Sendok : Makan = Silet : ......', ['A' => 'Tajam', 'B' => 'Potong', 'C' => 'Bahaya', 'D' => 'Hitam'], 'B', 'Sendok digunakan untuk makan, silet digunakan untuk memotong'],
            [25, 'Data : Disket = Uang : ......', ['A' => 'Kaya', 'B' => 'Bank', 'C' => 'Duit', 'D' => 'Logam'], 'B', 'Tempat menyimpan data adalah disket, tempat menyimpan uang adalah bank'],
            [26, 'Siang : Matahari = Malam : ......', ['A' => 'Bintang', 'B' => 'Gelap', 'C' => 'Bulan', 'D' => 'Awam'], 'C', 'Penerang di siang hari adalah matahari, penerang di malam hari adalah bulan'],
            [27, 'Haus : Minuman = Terang : ......', ['A' => 'Gelap', 'B' => 'Putih', 'C' => 'Benderang', 'D' => 'Lampu'], 'D', 'Penyebab hilang haus adalah minuman, penyebab terang adalah lampu'],
            [28, 'Mahkota : Raja = Helm : ......', ['A' => 'Plastik', 'B' => 'Peragawan', 'C' => 'Ratu', 'D' => 'Pembalap'], 'D', 'Penutup kepala raja adalah mahkota, penutup kepala pembalap adalah helm'],
            [29, 'Rokok : Asbak = Air : ......', ['A' => 'Selokan', 'B' => 'Ember', 'C' => 'Selang', 'D' => 'Keran'], 'B', 'Wadah penampung abu rokok adalah asbak, wadah penampung air adalah ember'],
            [30, 'Air : Haus = Nasi : ......', ['A' => 'Goreng', 'B' => 'Lapar', 'C' => 'Beras', 'D' => 'Rames'], 'B', 'Air menghilangkan haus, nasi menghilangkan lapar'],
            [31, 'Komputer : Listrik = Mobil : ......', ['A' => 'Roda', 'B' => 'Mesin', 'C' => 'Bensin', 'D' => 'Onderdil'], 'C', 'Sumber daya komputer adalah listrik, sumber daya mobil adalah bensin'],
            [32, 'Sepatu : Kaki = Topi : ......', ['A' => 'Tangan', 'B' => 'Kaki', 'C' => 'Bulat', 'D' => 'Kepala'], 'D', 'Sepatu dikenakan di kaki, topi dikenakan di kepala'],
            [33, 'Telepon : Pulsa = Pertunjukan : ......', ['A' => 'Uang', 'B' => 'Bioskop', 'C' => 'Harga', 'D' => 'Karcis'], 'D', 'Akses menggunakan telepon butuh pulsa, akses masuk pertunjukan butuh karcis'],
            [34, 'Anting : Telinga = Gelang : ......', ['A' => 'Leher', 'B' => 'Emas', 'C' => 'Tangan', 'D' => 'Jari'], 'C', 'Anting dipakai di telinga, gelang dipakai di tangan'],
            [35, 'Beo : Suara = Sapi : ......', ['A' => 'Bulu', 'B' => 'Daging', 'C' => 'Suara', 'D' => 'Warna'], 'B', 'Keunggulan beo pada suaranya, keunggulan/manfaat sapi pada daging/susunya'],
            [36, 'Lebah : Madu = Cendrawasih : ......', ['A' => 'Bulu', 'B' => 'Burung', 'C' => 'Daging', 'D' => 'Suara'], 'A', 'Hasil keindahan lebah adalah madu, daya tarik cendrawasih adalah bulunya'],
            [37, 'Bersih : Kotor = Tinggi : ......', ['A' => 'Rendah', 'B' => 'Jangkung', 'C' => 'Jauh', 'D' => 'Dekat'], 'A', 'Lawan kata bersih adalah kotor, lawan kata tinggi adalah rendah'],
            [38, 'Nasi : Beras = Tape : ......', ['A' => 'Nanas', 'B' => 'Ubi', 'C' => 'Pisang', 'D' => 'Gandum'], 'B', 'Bahan baku nasi adalah beras, bahan baku tape adalah ubi / singkong'],
            [39, 'Tambang : Emas = Laut : ......', ['A' => 'Badai', 'B' => 'Kapal', 'C' => 'Nelayan', 'D' => 'Karang'], 'D', 'Kekayaan yang dihasilkan tambang adalah emas, kekayaan laut di antaranya terumbu karang/ikan'],
            [40, 'Anjing : Binatang = Apel : ......', ['A' => 'Vitamin', 'B' => 'Merah', 'C' => 'Buah', 'D' => 'Segar'], 'C', 'Anjing adalah jenis binatang, apel adalah jenis buah'],
            [41, 'Perusahaan : Karyawan = Sekolah : ......', ['A' => 'Pengawas', 'B' => 'Pelajar', 'C' => 'Ujian', 'D' => 'Kelas'], 'B', 'Anggota/pelaku dalam perusahaan adalah karyawan, di sekolah adalah pelajar'],
            [42, 'Televisi : Gambar = Radio : ......', ['A' => 'Listrik', 'B' => 'Penyiar', 'C' => 'Suara', 'D' => 'Merdu'], 'C', 'Output utama televisi adalah gambar, output radio adalah suara'],
            [43, 'Telepon : Komunikasi = Sepeda : ......', ['A' => 'Roda', 'B' => 'Lomba', 'C' => 'Cepat', 'D' => 'Transportasi'], 'D', 'Fungsi telepon untuk komunikasi, fungsi sepeda untuk transportasi'],
            [44, 'Kertas : Pena = Dinding : ......', ['A' => 'Kuas', 'B' => 'Dempul', 'C' => 'Amplas', 'D' => 'Cat'], 'A', 'Alat untuk menorehkan pada kertas adalah pena, pada dinding adalah kuas / cat'],
            [45, 'Film : Oscar = Bulutangkis : ......', ['A' => 'Olahraga', 'B' => 'Cina', 'C' => 'Piala', 'D' => 'Sudirman'], 'D', 'Penghargaan tertinggi film adalah Oscar, kejuaraan beregu bulutangkis adalah Sudirman'],
            [46, 'Lapar : Makan = Gatal : ......', ['A' => 'Bentol', 'B' => 'Semut', 'C' => 'Garuk', 'D' => 'Luka'], 'C', 'Respon saat lapar adalah makan, respon saat gatal adalah menggaruk'],
            [47, 'Datang : Pergi = Akhir : ......', ['A' => 'Awal', 'B' => 'Kemudian', 'C' => 'Setelah', 'D' => 'Zaman'], 'A', 'Lawan kata datang adalah pergi, lawan kata akhir adalah awal'],
            [48, 'Sutra : Ulat = Madu : ......', ['A' => 'Manis', 'B' => 'Lebah', 'C' => 'Hutan', 'D' => 'Bunga'], 'B', 'Sutra dihasilkan oleh ulat, madu dihasilkan oleh lebah'],
            [49, 'Darat : Mobil = Udara : ......', ['A' => 'Angin', 'B' => 'Terbang', 'C' => 'Perahu', 'D' => 'Pesawat'], 'D', 'Kendaraan di darat adalah mobil, kendaraan di udara adalah pesawat'],
            [50, 'Pisang : Buah = Sapi : ......', ['A' => 'Banteng', 'B' => 'Gemuk', 'C' => 'Binatang', 'D' => 'Kulit'], 'C', 'Pisang adalah kategori buah, sapi adalah kategori binatang'],
        ];

        foreach ($analogiQuestions as $item) {
            PsikotesStageQuestion::create([
                'question_bank_id' => $bank->id,
                'stage' => 'STAGE_2_ANALOGI',
                'question_number' => $item[0],
                'question_text' => $item[1],
                'options_json' => $item[2],
                'correct_answer_1' => $item[3],
                'correct_answer_2' => null,
                'explanation' => $item[4],
                'active' => true,
            ]);
        }

        // 4. Pastikan 24 Soal Kepribadian (Stage 3) terdaftar pada bank ini jika belum
        if ($bank->questions()->count() < 24) {
            $existingQuestions = DiscQuestion::where('question_bank_id', '!=', $bank->id)->with('options')->get();
            if ($existingQuestions->count() >= 24) {
                foreach ($existingQuestions->take(24) as $srcQ) {
                    $newQ = DiscQuestion::create([
                        'question_bank_id' => $bank->id,
                        'question_number' => $srcQ->question_number,
                        'instruction' => $srcQ->instruction,
                        'active' => true,
                    ]);
                    foreach ($srcQ->options as $opt) {
                        DiscQuestionOption::create([
                            'disc_question_id' => $newQ->id,
                            'option_number' => $opt->option_number,
                            'option_label' => $opt->option_label,
                            'statement' => $opt->statement,
                            'most_mapping' => $opt->most_mapping,
                            'least_mapping' => $opt->least_mapping,
                            'display_order' => $opt->display_order,
                            'active' => true,
                        ]);
                    }
                }
            }
        }
    }
}
