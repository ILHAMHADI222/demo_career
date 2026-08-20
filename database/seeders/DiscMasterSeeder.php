<?php

namespace Database\Seeders;

use App\Models\DiscNormalizationTable;
use App\Models\DiscProfile;
use App\Models\DiscQuestion;
use App\Models\DiscQuestionOption;
use App\Models\Permission;
use App\Models\QuestionBank;
use App\Models\Role;
use App\Models\User;
use App\Services\DiscScoringEngine;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DiscMasterSeeder extends Seeder
{
    public function run(): void
    {
        $superadminUser = User::query()->where('email', 'superadmin@hallonet.test')->first();
        $adminRole = Role::query()->where('name', 'ADMIN')->first();
        $superadminRole = Role::query()->where('name', 'SUPERADMIN')->first();

        // 1. Seed DISC Permissions
        $discPermissions = [
            'disc.bank.view', 'disc.bank.create', 'disc.bank.update', 'disc.bank.publish', 'disc.bank.archive',
            'disc.scoring.view', 'disc.scoring.manage',
            'disc.normalization.view', 'disc.normalization.manage',
            'disc.profile.view', 'disc.profile.manage',
            'disc.assessment.create', 'disc.assessment.manage',
            'disc.result.view', 'disc.result.export',
            'disc.security.monitor',
        ];

        $permissionModels = collect($discPermissions)->map(function ($perm) {
            return Permission::query()->firstOrCreate(
                ['name' => $perm],
                ['label' => Str::headline(str_replace('.', ' ', $perm))]
            );
        });

        if ($superadminRole) {
            $superadminRole->permissions()->syncWithoutDetaching($permissionModels->pluck('id'));
        }
        if ($adminRole) {
            $adminRole->permissions()->syncWithoutDetaching($permissionModels->pluck('id'));
        }

        // 2. Seed Default Question Bank
        $bank = QuestionBank::query()->firstOrCreate(
            ['code' => 'DISC-ISP'],
            [
                'name' => 'DISC MASTER ISP',
                'description' => 'Bank Soal Standar 24 Pertanyaan DISC PT Indodata Solusi Persada untuk psikotes dan asesmen rekrutmen karyawan.',
                'assessment_type' => 'DISC',
                'version' => '1.0',
                'status' => 'PUBLISHED',
                'created_by' => $superadminUser?->id,
            ]
        );

        // 3. 24 Standard DISC Questions & Options Dataset
        $questionsData = [
            1 => [
                ['label' => 'A', 'statement' => 'Gampangan, mudah setuju, bersikap santai dan toleran', 'most' => 'S', 'least' => 'S'],
                ['label' => 'B', 'statement' => 'Percaya, mudah percaya pada orang lain, ramah dan optimis', 'most' => 'I', 'least' => 'I'],
                ['label' => 'C', 'statement' => 'Petualang, suka mengambil risiko, mandiri dan penuh inisiatif', 'most' => '*', 'least' => 'D'],
                ['label' => 'D', 'statement' => 'Toleran, menghormati aturan, cermat dan teratur', 'most' => 'C', 'least' => 'C'],
            ],
            2 => [
                ['label' => 'A', 'statement' => 'Korektif, tidak mudah percaya, teliti dan penuh kalkulasi', 'most' => 'C', 'least' => 'C'],
                ['label' => 'B', 'statement' => 'Penenang, menyukai suasana damai dan meredakan ketegangan', 'most' => 'S', 'least' => 'S'],
                ['label' => 'C', 'statement' => 'Suka memimpin, tegas, kompetitif dan berorientasi hasil', 'most' => 'D', 'least' => 'D'],
                ['label' => 'D', 'statement' => 'Ramah tamah, lincah berbicara, ceria dan hangat', 'most' => 'I', 'least' => 'I'],
            ],
            3 => [
                ['label' => 'A', 'statement' => 'Suka bergaul, bersemangat membina relasi baru dan ekspresif', 'most' => 'I', 'least' => 'I'],
                ['label' => 'B', 'statement' => 'Tenang, stabil, tidak mudah panik dan setia kawan', 'most' => 'S', 'least' => 'S'],
                ['label' => 'C', 'statement' => 'Berani, suka tantangan, cepat bertindak dan independen', 'most' => 'D', 'least' => 'D'],
                ['label' => 'D', 'statement' => 'Hati-hati, logis, patuh prosedur dan sistematis', 'most' => 'C', 'least' => 'C'],
            ],
            4 => [
                ['label' => 'A', 'statement' => 'Keras kepala, gigih mempertahankan pendirian dan target', 'most' => 'D', 'least' => 'D'],
                ['label' => 'B', 'statement' => 'Periang, suka menghibur, humoris dan komunikatif', 'most' => 'I', 'least' => 'I'],
                ['label' => 'C', 'statement' => 'Penurut, suka membantu, kooperatif dan pengertian', 'most' => 'S', 'least' => 'S'],
                ['label' => 'D', 'statement' => 'Tepat, metodis, mencari kesempurnaan dan akurasi tinggi', 'most' => 'C', 'least' => 'C'],
            ],
            5 => [
                ['label' => 'A', 'statement' => 'Sopan, menghargai tata krama, menjaga mutu dan rapi', 'most' => 'C', 'least' => 'C'],
                ['label' => 'B', 'statement' => 'Penuh kesabaran, pendengar yang baik dan tulus', 'most' => 'S', 'least' => 'S'],
                ['label' => 'C', 'statement' => 'Penuh keyakinan, persuasif dan bersemangat', 'most' => 'I', 'least' => 'I'],
                ['label' => 'D', 'statement' => 'Berani mengambil kendali, kompetitif dan fokus sasaran', 'most' => 'D', 'least' => 'D'],
            ],
            6 => [
                ['label' => 'A', 'statement' => 'Pandai membujuk, menarik perhatian, pandai bernegosiasi', 'most' => 'I', 'least' => 'I'],
                ['label' => 'B', 'statement' => 'Menghindari konflik, cinta damai, setia dan bersahaja', 'most' => 'S', 'least' => 'S'],
                ['label' => 'C', 'statement' => 'Tegas, menuntut hasil cepat, mandiri dan pantang menyerah', 'most' => 'D', 'least' => 'D'],
                ['label' => 'D', 'statement' => 'Berhati-hati dalam bertindak, analitis dan disiplin tinggi', 'most' => 'C', 'least' => 'C'],
            ],
            7 => [
                ['label' => 'A', 'statement' => 'Ekspresif, antusias, menyukai pujian dan pergaulan luas', 'most' => 'I', 'least' => 'I'],
                ['label' => 'B', 'statement' => 'Berprinsip, teliti, kritis, mengutamakan fakta empiris', 'most' => 'C', 'least' => 'C'],
                ['label' => 'C', 'statement' => 'Gigih, agresif mengejar peluang, tangguh dan dinamis', 'most' => 'D', 'least' => 'D'],
                ['label' => 'D', 'statement' => 'Puas dengan keadaan, konsisten, tidak banyak menuntut', 'most' => 'S', 'least' => 'S'],
            ],
            8 => [
                ['label' => 'A', 'statement' => 'Rela berkorban, mendahulukan orang lain dan loyal', 'most' => 'S', 'least' => 'S'],
                ['label' => 'B', 'statement' => 'Percaya diri tinggi, berani mengambil keputusan tanpa ragu', 'most' => 'D', 'least' => 'D'],
                ['label' => 'C', 'statement' => 'Penuh pesona, ceria, disukai banyak orang dan ramah', 'most' => 'I', 'least' => 'I'],
                ['label' => 'D', 'statement' => 'Fokus pada standar mutu, cermat, akurat dan terencana', 'most' => 'C', 'least' => 'C'],
            ],
            9 => [
                ['label' => 'A', 'statement' => 'Diplomatis, menjaga keharmonisan dan sopan santun', 'most' => 'C', 'least' => 'C'],
                ['label' => 'B', 'statement' => 'Pengertian, stabil, setia, siap mendukung kapan saja', 'most' => 'S', 'least' => 'S'],
                ['label' => 'C', 'statement' => 'Optimis, mudah beradaptasi dengan suasana baru dan riang', 'most' => 'I', 'least' => 'I'],
                ['label' => 'D', 'statement' => 'Pengambil inisiatif, dominan, suka memecahkan masalah rumit', 'most' => 'D', 'least' => 'D'],
            ],
            10 => [
                ['label' => 'A', 'statement' => 'Pemberani, suka mengatasi rintangan dan menyukai wewenang', 'most' => 'D', 'least' => 'D'],
                ['label' => 'B', 'statement' => 'Inspiratif, memotivasi orang lain, ceria dan terbuka', 'most' => 'I', 'least' => 'I'],
                ['label' => 'C', 'statement' => 'Penyabar, konsisten, tenang dan menghargai rutinitas', 'most' => 'S', 'least' => 'S'],
                ['label' => 'D', 'statement' => 'Sistematis, teratur, memperhatikan detail teknis terkecil', 'most' => 'C', 'least' => 'C'],
            ],
            11 => [
                ['label' => 'A', 'statement' => 'Cermat, skeptis terhadap hal baru sebelum diuji, analitis', 'most' => 'C', 'least' => 'C'],
                ['label' => 'B', 'statement' => 'Sederhana, mudah didekati, bersahabat dan pendiam', 'most' => 'S', 'least' => 'S'],
                ['label' => 'C', 'statement' => 'Kharismatik, pandai bicara di depan umum dan persuasif', 'most' => 'I', 'least' => 'I'],
                ['label' => 'D', 'statement' => 'Fokus pada kemenangan, kompetitif dan berenergi tinggi', 'most' => 'D', 'least' => 'D'],
            ],
            12 => [
                ['label' => 'A', 'statement' => 'Menstimulasi orang lain, antusias menyambut ide inovatif', 'most' => 'I', 'least' => 'I'],
                ['label' => 'B', 'statement' => 'Menghindari risiko, mengutamakan keamanan dan stabilitas', 'most' => 'S', 'least' => 'S'],
                ['label' => 'C', 'statement' => 'Terus terang, berani mengungkapkan pendapat tanpa basa-basi', 'most' => 'D', 'least' => 'D'],
                ['label' => 'D', 'statement' => 'Disiplin tinggi, taat pedoman kerja dan teliti', 'most' => 'C', 'least' => 'C'],
            ],
            13 => [
                ['label' => 'A', 'statement' => 'Ingin tahu, suka menganalisis data, detail dan mendalam', 'most' => 'C', 'least' => 'C'],
                ['label' => 'B', 'statement' => 'Penuh perhatian, ramah, suka membantu sesama tanpa pamrih', 'most' => 'S', 'least' => 'S'],
                ['label' => 'C', 'statement' => 'Percaya diri kuat, berani memimpin dalam situasi krisis', 'most' => 'D', 'least' => 'D'],
                ['label' => 'D', 'statement' => 'Menyenangkan, berjiwa muda, ekspresif dan ramah', 'most' => 'I', 'least' => 'I'],
            ],
            14 => [
                ['label' => 'A', 'statement' => 'Suka mengarahkan, tegas, bertindak cepat dan ambisius', 'most' => 'D', 'least' => 'D'],
                ['label' => 'B', 'statement' => 'Mudah bergaul, pandai mencairkan suasana dan humoris', 'most' => 'I', 'least' => 'I'],
                ['label' => 'C', 'statement' => 'Tenang, sabar menghadapi tekanan, tidak mudah goyah', 'most' => 'S', 'least' => 'S'],
                ['label' => 'D', 'statement' => 'Mengikuti standar kualitas, rapi dan bertanggung jawab', 'most' => 'C', 'least' => 'C'],
            ],
            15 => [
                ['label' => 'A', 'statement' => 'Menjaga harmoni, tidak suka perselisihan, pengertian', 'most' => 'S', 'least' => 'S'],
                ['label' => 'B', 'statement' => 'Mandiri, teguh pendirian, tidak bergantung pada orang lain', 'most' => 'D', 'least' => 'D'],
                ['label' => 'C', 'statement' => 'Komunikatif, ceria, membagikan energi positif ke sekitar', 'most' => 'I', 'least' => 'I'],
                ['label' => 'D', 'statement' => 'Berpikir kritis, logis, terstruktur dan berhati-hati', 'most' => 'C', 'least' => 'C'],
            ],
            16 => [
                ['label' => 'A', 'statement' => 'Optimis, memandang sisi positif segala hal, penuh harapan', 'most' => 'I', 'least' => 'I'],
                ['label' => 'B', 'statement' => 'Perfeksionis, menuntut akurasi tanpa kompromi', 'most' => 'C', 'least' => 'C'],
                ['label' => 'C', 'statement' => 'Penggerak utama, tidak suka bertele-tele, fokus tujuan', 'most' => 'D', 'least' => 'D'],
                ['label' => 'D', 'statement' => 'Setia, dapat diandalkan dalam jangka panjang dan tekun', 'most' => 'S', 'least' => 'S'],
            ],
            17 => [
                ['label' => 'A', 'statement' => 'Sabar, mendengarkan dengan penuh empati dan tenang', 'most' => 'S', 'least' => 'S'],
                ['label' => 'B', 'statement' => 'Penuh strategi, analitis, menyukai data dan perencanaan matang', 'most' => 'C', 'least' => 'C'],
                ['label' => 'C', 'statement' => 'Penuh semangat, menarik perhatian, periang dan santai', 'most' => 'I', 'least' => 'I'],
                ['label' => 'D', 'statement' => 'Berani bersaing, bertekad menang, tangguh dan mandiri', 'most' => 'D', 'least' => 'D'],
            ],
            18 => [
                ['label' => 'A', 'statement' => 'Suka mengambil inisiatif perubahan, tegas dan fokus target', 'most' => 'D', 'least' => 'D'],
                ['label' => 'B', 'statement' => 'Menyukai interaksi tim, populer, ramah dan spontan', 'most' => 'I', 'least' => 'I'],
                ['label' => 'C', 'statement' => 'Menjaga rutinitas kerja, sabar, teratur dan stabil', 'most' => 'S', 'least' => 'S'],
                ['label' => 'D', 'statement' => 'Tertib, mengikuti prosedur, terukur dan hati-hati', 'most' => 'C', 'least' => 'C'],
            ],
            19 => [
                ['label' => 'A', 'statement' => 'Cermat, objektif, tidak emosional dalam mengambil keputusan', 'most' => 'C', 'least' => 'C'],
                ['label' => 'B', 'statement' => 'Kooperatif, suka berkompromi demi kebaikan bersama', 'most' => 'S', 'least' => 'S'],
                ['label' => 'C', 'statement' => 'Ekspresif, antusias berbagi ide dan mengajak orang lain', 'most' => 'I', 'least' => 'I'],
                ['label' => 'D', 'statement' => 'Ambisius, menuntut standar tinggi, tegas dan dominan', 'most' => 'D', 'least' => 'D'],
            ],
            20 => [
                ['label' => 'A', 'statement' => 'Ramah, mudah akrab, menciptakan suasana hangat dan ceria', 'most' => 'I', 'least' => 'I'],
                ['label' => 'B', 'statement' => 'Tenang, stabil, tidak menyukai perubahan mendadak', 'most' => 'S', 'least' => 'S'],
                ['label' => 'C', 'statement' => 'Gigih, tidak mudah menyerah oleh rintangan berat', 'most' => 'D', 'least' => 'D'],
                ['label' => 'D', 'statement' => 'Sistematis, teliti dalam menyusun detail laporan', 'most' => 'C', 'least' => 'C'],
            ],
            21 => [
                ['label' => 'A', 'statement' => 'Diplomatis, bijaksana, menjaga etika profesional', 'most' => 'C', 'least' => 'C'],
                ['label' => 'B', 'statement' => 'Penyayang, suportif, selalu siap membantu rekan kerja', 'most' => 'S', 'least' => 'S'],
                ['label' => 'C', 'statement' => 'Persuasif, pandai meyakinkan pihak lain dan optimis', 'most' => 'I', 'least' => 'I'],
                ['label' => 'D', 'statement' => 'Berani memimpin, berorientasi hasil, cepat mengambil tindakan', 'most' => 'D', 'least' => 'D'],
            ],
            22 => [
                ['label' => 'A', 'statement' => 'Pengambil risiko terhitung, fokus efisiensi dan progres', 'most' => 'D', 'least' => 'D'],
                ['label' => 'B', 'statement' => 'Ceria, mudah berteman, antusias dan komunikatif', 'most' => 'I', 'least' => 'I'],
                ['label' => 'C', 'statement' => 'Tulus, setia, menjaga komitmen dan pendengar setia', 'most' => 'S', 'least' => 'S'],
                ['label' => 'D', 'statement' => 'Kritis, teliti, mengevaluasi fakta sebelum bertindak', 'most' => 'C', 'least' => 'C'],
            ],
            23 => [
                ['label' => 'A', 'statement' => 'Menjaga keteraturan, disiplin, berpegang pada SOP tertulis', 'most' => 'C', 'least' => 'C'],
                ['label' => 'B', 'statement' => 'Pengertian, menghindari perdebatan, bersikap toleran', 'most' => 'S', 'least' => 'S'],
                ['label' => 'C', 'statement' => 'Inspiratif, menarik perhatian, berjiwa sosial tinggi', 'most' => 'I', 'least' => 'I'],
                ['label' => 'D', 'statement' => 'Tegas, memiliki determinasi kuat dan menyukai tantangan baru', 'most' => 'D', 'least' => 'D'],
            ],
            24 => [
                ['label' => 'A', 'statement' => 'Cepat mengambil keputusan, berani bersaing dan mandiri', 'most' => 'D', 'least' => 'D'],
                ['label' => 'B', 'statement' => 'Hangat, menyenangkan, pandai menjalin relasi publik', 'most' => 'I', 'least' => 'I'],
                ['label' => 'C', 'statement' => 'Konsisten, dapat dipercaya menjaga rahasia, sabar', 'most' => 'S', 'least' => 'S'],
                ['label' => 'D', 'statement' => 'Presisi, mengutamakan kebenaran data dan standar mutu', 'most' => 'C', 'least' => 'C'],
            ],
        ];

        foreach ($questionsData as $num => $opts) {
            $question = DiscQuestion::query()->updateOrCreate(
                ['question_bank_id' => $bank->id, 'question_number' => $num],
                [
                    'instruction' => 'Pilihlah satu pernyataan yang PALING menggambarkan diri Anda (Most) dan satu pernyataan yang PALING TIDAK menggambarkan diri Anda (Least).',
                    'active' => true,
                    'created_by' => $superadminUser?->id,
                ]
            );

            foreach ($opts as $idx => $opt) {
                DiscQuestionOption::query()->updateOrCreate(
                    [
                        'disc_question_id' => $question->id,
                        'option_number' => $idx + 1,
                    ],
                    [
                        'option_label' => $opt['label'],
                        'statement' => $opt['statement'],
                        'most_mapping' => $opt['most'],
                        'least_mapping' => $opt['least'],
                        'display_order' => $idx + 1,
                        'active' => true,
                    ]
                );
            }
        }

        // 4. Seed Normalization Tables (Graph 1, 2, 3)
        foreach (DiscScoringEngine::NORM_TABLE_GRAPH_1 as $raw => $scores) {
            DiscNormalizationTable::query()->updateOrCreate(
                [
                    'code' => 'DISC_STANDARD_V1',
                    'version' => '1.0',
                    'chart_type' => 'GRAPH_1_MOST',
                    'raw_score' => $raw,
                ],
                [
                    'd_score' => $scores['D'],
                    'i_score' => $scores['I'],
                    's_score' => $scores['S'],
                    'c_score' => $scores['C'],
                    'is_active' => true,
                    'created_by' => $superadminUser?->id,
                ]
            );
        }

        foreach (DiscScoringEngine::NORM_TABLE_GRAPH_2 as $raw => $scores) {
            DiscNormalizationTable::query()->updateOrCreate(
                [
                    'code' => 'DISC_STANDARD_V1',
                    'version' => '1.0',
                    'chart_type' => 'GRAPH_2_LEAST',
                    'raw_score' => $raw,
                ],
                [
                    'd_score' => $scores['D'],
                    'i_score' => $scores['I'],
                    's_score' => $scores['S'],
                    'c_score' => $scores['C'],
                    'is_active' => true,
                    'created_by' => $superadminUser?->id,
                ]
            );
        }

        // Graph 3 Change from -24 to +24
        for ($raw = -24; $raw <= 24; $raw++) {
            $normVal = round(($raw / 24) * 8.0, 2);
            DiscNormalizationTable::query()->updateOrCreate(
                [
                    'code' => 'DISC_STANDARD_V1',
                    'version' => '1.0',
                    'chart_type' => 'GRAPH_3_CHANGE',
                    'raw_score' => $raw,
                ],
                [
                    'd_score' => $normVal,
                    'i_score' => $normVal,
                    's_score' => $normVal,
                    'c_score' => $normVal,
                    'is_active' => true,
                    'created_by' => $superadminUser?->id,
                ]
            );
        }

        // 5. Seed DISC Master Profiles & Interpretations
        $profiles = [
            [
                'code' => 'D',
                'name' => 'Dominance (The Commander / Driver)',
                'desc' => 'Individu yang tegas, berorientasi hasil, berani mengambil risiko, menyukai tantangan, dan fokus pada efisiensi serta pencapaian target tinggi.',
                'strengths' => 'Kepemimpinan kuat, pengambilan keputusan cepat, berani menghadapi tekanan, gigih mengatasi rintangan.',
                'limitations' => 'Dapat terkesan kurang sabar, menuntut, kurang memperhatikan detail administratif atau perasaan orang lain.',
                'comm' => 'Langsung pada inti (to the point), singkat, tegas, fokus pada sasaran dan hasil konkret.',
                'work' => 'Mandiri, target-driven, menyukai kendali atas proyek dan ruang inovasi yang bebas birokrasi.',
                'dev' => 'Tingkatkan empati, dengarkan masukan anggota tim secara aktif, dan latih kesabaran dalam proses bertahap.',
            ],
            [
                'code' => 'I',
                'name' => 'Influence (The Influencer / Communicator)',
                'desc' => 'Individu yang antusias, ramah, persuasif, optimis, dan membangun jejaring hubungan sosial dengan sangat baik.',
                'strengths' => 'Kemampuan komunikasi verbal sangat baik, memotivasi orang lain, membangun atmosfer kerja positif dan kreatif.',
                'limitations' => 'Terkadang kurang teliti pada detail, mudah terdistraksi ide baru, dan kurang menyukai tugas rutin berulang.',
                'comm' => 'Hangat, ekspresif, persuasif, menyukai diskusi dua arah yang interaktif dan menyenangkan.',
                'work' => 'Kolaboratif, dinamis, menyukai interaksi dengan orang banyak dan proyek bernuansa presentasi/networking.',
                'dev' => 'Tingkatkan manajemen waktu, dokumentasikan detail rencana tertulis, dan selesaikan proyek hingga tuntas.',
            ],
            [
                'code' => 'S',
                'name' => 'Steadiness (The Supporter / Specialist)',
                'desc' => 'Individu yang tenang, setia, sabar, dapat diandalkan, suportif, serta menyukai konsistensi dan harmoni dalam tim.',
                'strengths' => 'Pendengar yang sangat baik, loyalitas tinggi, telaten, menjaga stabilitas tim, konsisten dalam menyelesaikan tugas.',
                'limitations' => 'Cenderung enggan terhadap perubahan mendadak, sulit berkata tidak, dan menghindari konfrontasi langsung.',
                'comm' => 'Tenang, ramah, tulus, menghargai perasaan rekan kerja, dan menggunakan pendekatan non-konfrontatif.',
                'work' => 'Terstruktur, teratur, menyukai prosedur yang jelas dan lingkungan kerja yang kooperatif tanpa konflik.',
                'dev' => 'Latih keberanian menyuarakan opini pribadi secara terbuka, dan belajar beradaptasi lebih cepat terhadap dinamika baru.',
            ],
            [
                'code' => 'C',
                'name' => 'Conscientiousness (The Analyst / Perfectionist)',
                'desc' => 'Individu yang analitis, teliti, sistematis, objektif, berorientasi data dan standar kualitas yang tinggi.',
                'strengths' => 'Akurasi tinggi, pemikiran logis mendalam, teratur, taat prosedur, menghasilkan pekerjaan berkualitas tanpa cela.',
                'limitations' => 'Dapat terjebak dalam analisis berlebih (overthinking), terlalu kritis terhadap ketidaksempurnaan, lambat jika data minim.',
                'comm' => 'Faktual, berbasis data dan fakta, logis, terstruktur, dan tidak menyukai asumsi tanpa bukti.',
                'work' => 'Metodis, fokus pada akurasi, riset, standarisasi mutu, dan kepatuhan terhadap SOP operasional.',
                'dev' => 'Berani mengambil keputusan pada kondisi ketidakpastian data, dan toleran terhadap kesalahan minor yang dapat diperbaiki.',
            ],
            [
                'code' => 'DI',
                'name' => 'Result-Oriented Influencer (The Motivator)',
                'desc' => 'Kombinasi ketegasan dan antusiasme tinggi. Mampu mendorong perubahan besar sekaligus menggerakkan tim dengan energi positif.',
                'strengths' => 'Inisiatif tinggi, persuasif, cepat bertindak, kepemimpinan transformatif.',
                'limitations' => 'Terkadang impulsif dan kurang sabar dalam mengawal detail pelaksanaan teknis.',
                'comm' => 'Bersemangat, langsung pada inti persoalan, inspiratif, dan penuh keyakinan.',
                'work' => 'Dinamis, target tinggi, menyukai proyek ekspansi atau perintisan inisiatif baru.',
                'dev' => 'Terapkan review teratur dan delegasikan pengawasan detail teknis kepada tim pendukung.',
            ],
            [
                'code' => 'DC',
                'name' => 'Creative Analyst (The Architect)',
                'desc' => 'Kombinasi determinasi tinggi dan standar kualitas presisi. Memecahkan masalah rumit dengan solusi sistemik yang teruji.',
                'strengths' => 'Visioner, analitis, mandiri, gigih menetapkan standar keunggulan.',
                'limitations' => 'Ekspektasi sangat tinggi terhadap diri sendiri dan rekan, terkesan berjarak.',
                'comm' => 'Objektif, lugas, fokus pada logika, strategi, dan perbaikan berkelanjutan.',
                'work' => 'Strategis, pemecahan masalah kompleks, arsitektur sistem, dan manajemen kualitas.',
                'dev' => 'Apresiasi kontribusi orang lain secara terbuka dan luangkan waktu untuk relasi informal.',
            ],
            [
                'code' => 'IS',
                'name' => 'Harmonious Communicator (The Counselor)',
                'desc' => 'Kombinasi kehangatan sosial dan empati mendalam. Sangat pandai membina hubungan antarmanusia dan menyelesaikan konflik internal.',
                'strengths' => 'Empati tinggi, komunikatif, menjaga moral tim, setia kawan, mediator ulung.',
                'limitations' => 'Sulit memberikan feedback yang tegas atau kritik keras kepada rekan kerja.',
                'comm' => 'Lembut, menghargai, ramah, membangun kepercayaan, dan penuh penerimaan.',
                'work' => 'People-oriented, customer relations, bimbingan, HR, dan layanan dukungan.',
                'dev' => 'Latih ketegasan asertif saat menetapkan batasan atau menegakkan disiplin kerja.',
            ],
            [
                'code' => 'SC',
                'name' => 'Diligent Specialist (The Practitioner)',
                'desc' => 'Kombinasi ketenangan, ketekunan, dan presisi tinggi. Selalu menyelesaikan tugas dengan kualitas konsisten dan minim kesalahan.',
                'strengths' => 'Dapat diandalkan 100%, metodis, tenang di bawah tekanan, rapi, teliti.',
                'limitations' => 'Menghindari risiko, cenderung pasif dalam mengambil inisiatif perubahan radikal.',
                'comm' => 'Sopan, terukur, hati-hati, berbobot, dan mengutamakan kejelasan tertulis.',
                'work' => 'Operasional berstandar tinggi, kepatuhan regulasi, administrasi keuangan/teknis teratur.',
                'dev' => 'Kembangkan fleksibilitas dalam menghadapi ketidakpastian dan inisiatif kepemimpinan.',
            ],
        ];

        foreach ($profiles as $p) {
            DiscProfile::query()->updateOrCreate(
                ['profile_code' => $p['code']],
                [
                    'profile_name' => $p['name'],
                    'description' => $p['desc'],
                    'strengths' => $p['strengths'],
                    'potential_limitations' => $p['limitations'],
                    'communication_style' => $p['comm'],
                    'work_style' => $p['work'],
                    'development_suggestions' => $p['dev'],
                    'is_active' => true,
                    'created_by' => $superadminUser?->id,
                ]
            );
        }

        // 6. Seed Default Active 10-Minute Assessment
        \App\Models\DiscAssessment::query()->updateOrCreate(
            ['code' => 'DISC-RECRUIT-ISP'],
            [
                'title' => 'Psikotest DISC Rekrutmen ISP',
                'question_bank_id' => $bank->id,
                'question_bank_version' => $bank->version,
                'duration_minutes' => 10,
                'randomize_questions' => false,
                'randomize_options' => false,
                'result_visibility' => 'HRD_ONLY',
                'status' => 'PUBLISHED',
                'created_by' => $superadminUser?->id,
            ]
        );
    }
}
