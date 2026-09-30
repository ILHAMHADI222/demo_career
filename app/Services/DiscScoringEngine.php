<?php

namespace App\Services;

use App\Models\DiscAssessmentSession;
use App\Models\DiscNormalizationTable;
use App\Models\DiscProfile;
use App\Models\DiscQuestionOption;

class DiscScoringEngine
{
    /**
     * Standard Conversion Tables (Fallback / Baseline)
     */
    public const NORM_TABLE_GRAPH_1 = [
        0  => ['D' => -7.0, 'I' => -7.0, 'S' => -7.0, 'C' => -7.0],
        1  => ['D' => -6.0, 'I' => -6.0, 'S' => -6.0, 'C' => -6.0],
        2  => ['D' => -5.0, 'I' => -5.0, 'S' => -5.0, 'C' => -5.0],
        3  => ['D' => -4.0, 'I' => -4.0, 'S' => -3.5, 'C' => -4.0],
        4  => ['D' => -3.0, 'I' => -3.0, 'S' => -2.0, 'C' => -3.0],
        5  => ['D' => -2.0, 'I' => -2.0, 'S' => -0.5, 'C' => -2.0],
        6  => ['D' => -1.0, 'I' => -1.0, 'S' =>  1.0, 'C' => -1.0],
        7  => ['D' =>  0.0, 'I' =>  0.0, 'S' =>  2.5, 'C' =>  0.0],
        8  => ['D' =>  1.0, 'I' =>  1.0, 'S' =>  4.0, 'C' =>  1.0],
        9  => ['D' =>  2.0, 'I' =>  2.0, 'S' =>  5.0, 'C' =>  2.0],
        10 => ['D' =>  3.0, 'I' =>  3.0, 'S' =>  6.0, 'C' =>  3.0],
        11 => ['D' =>  4.0, 'I' =>  4.0, 'S' =>  7.0, 'C' =>  4.0],
        12 => ['D' =>  5.0, 'I' =>  5.0, 'S' =>  7.5, 'C' =>  5.0],
        13 => ['D' =>  5.5, 'I' =>  5.5, 'S' =>  8.0, 'C' =>  5.5],
        14 => ['D' =>  6.0, 'I' =>  6.0, 'S' =>  8.0, 'C' =>  6.0],
        15 => ['D' =>  6.5, 'I' =>  6.5, 'S' =>  8.0, 'C' =>  6.5],
        16 => ['D' =>  7.0, 'I' =>  7.0, 'S' =>  8.0, 'C' =>  7.0],
        17 => ['D' =>  7.5, 'I' =>  7.5, 'S' =>  8.0, 'C' =>  7.5],
        18 => ['D' =>  8.0, 'I' =>  8.0, 'S' =>  8.0, 'C' =>  8.0],
        19 => ['D' =>  8.0, 'I' =>  8.0, 'S' =>  8.0, 'C' =>  8.0],
        20 => ['D' =>  8.0, 'I' =>  8.0, 'S' =>  8.0, 'C' =>  8.0],
        21 => ['D' =>  8.0, 'I' =>  8.0, 'S' =>  8.0, 'C' =>  8.0],
        22 => ['D' =>  8.0, 'I' =>  8.0, 'S' =>  8.0, 'C' =>  8.0],
        23 => ['D' =>  8.0, 'I' =>  8.0, 'S' =>  8.0, 'C' =>  8.0],
        24 => ['D' =>  8.0, 'I' =>  8.0, 'S' =>  8.0, 'C' =>  8.0],
    ];

    public const NORM_TABLE_GRAPH_2 = [
        0  => ['D' =>  8.0, 'I' =>  8.0, 'S' =>  8.0, 'C' =>  8.0],
        1  => ['D' =>  7.0, 'I' =>  7.0, 'S' =>  7.0, 'C' =>  7.0],
        2  => ['D' =>  6.0, 'I' =>  6.0, 'S' =>  6.0, 'C' =>  6.0],
        3  => ['D' =>  5.0, 'I' =>  5.0, 'S' =>  4.5, 'C' =>  5.0],
        4  => ['D' =>  4.0, 'I' =>  4.0, 'S' =>  3.0, 'C' =>  4.0],
        5  => ['D' =>  3.0, 'I' =>  3.0, 'S' =>  1.5, 'C' =>  3.0],
        6  => ['D' =>  2.0, 'I' =>  2.0, 'S' =>  0.0, 'C' =>  2.0],
        7  => ['D' =>  1.0, 'I' =>  1.0, 'S' => -1.5, 'C' =>  1.0],
        8  => ['D' =>  0.0, 'I' =>  0.0, 'S' => -3.0, 'C' =>  0.0],
        9  => ['D' => -1.0, 'I' => -1.0, 'S' => -4.0, 'C' => -1.0],
        10 => ['D' => -2.0, 'I' => -2.0, 'S' => -5.0, 'C' => -2.0],
        11 => ['D' => -3.0, 'I' => -3.0, 'S' => -6.0, 'C' => -3.0],
        12 => ['D' => -4.0, 'I' => -4.0, 'S' => -7.0, 'C' => -4.0],
        13 => ['D' => -5.0, 'I' => -5.0, 'S' => -7.5, 'C' => -5.0],
        14 => ['D' => -6.0, 'I' => -6.0, 'S' => -8.0, 'C' => -6.0],
        15 => ['D' => -6.5, 'I' => -6.5, 'S' => -8.0, 'C' => -6.5],
        16 => ['D' => -7.0, 'I' => -7.0, 'S' => -8.0, 'C' => -7.0],
        17 => ['D' => -7.5, 'I' => -7.5, 'S' => -8.0, 'C' => -7.5],
        18 => ['D' => -8.0, 'I' => -8.0, 'S' => -8.0, 'C' => -8.0],
        19 => ['D' => -8.0, 'I' => -8.0, 'S' => -8.0, 'C' => -8.0],
        20 => ['D' => -8.0, 'I' => -8.0, 'S' => -8.0, 'C' => -8.0],
        21 => ['D' => -8.0, 'I' => -8.0, 'S' => -8.0, 'C' => -8.0],
        22 => ['D' => -8.0, 'I' => -8.0, 'S' => -8.0, 'C' => -8.0],
        23 => ['D' => -8.0, 'I' => -8.0, 'S' => -8.0, 'C' => -8.0],
        24 => ['D' => -8.0, 'I' => -8.0, 'S' => -8.0, 'C' => -8.0],
    ];

    /**
     * Compute full score for an assessment session
     */
    public function processSession(DiscAssessmentSession $session): array
    {
        $answers = $session->answers()->with(['mostOption', 'leastOption'])->get();

        $rawScores = [
            'graph1_most' => ['D' => 0, 'I' => 0, 'S' => 0, 'C' => 0, '*' => 0],
            'graph2_least' => ['D' => 0, 'I' => 0, 'S' => 0, 'C' => 0, '*' => 0],
            'graph3_change' => ['D' => 0, 'I' => 0, 'S' => 0, 'C' => 0],
        ];

        foreach ($answers as $ans) {
            if ($ans->mostOption && !empty($ans->mostOption->most_mapping)) {
                $mapping = strtoupper(trim($ans->mostOption->most_mapping));
                if (isset($rawScores['graph1_most'][$mapping])) {
                    $rawScores['graph1_most'][$mapping]++;
                }
            }

            if ($ans->leastOption && !empty($ans->leastOption->least_mapping)) {
                $mapping = strtoupper(trim($ans->leastOption->least_mapping));
                if (isset($rawScores['graph2_least'][$mapping])) {
                    $rawScores['graph2_least'][$mapping]++;
                }
            }
        }

        // Calculate Graph 3 (Change / Difference: Most - Least)
        foreach (['D', 'I', 'S', 'C'] as $dim) {
            $rawScores['graph3_change'][$dim] = $rawScores['graph1_most'][$dim] - $rawScores['graph2_least'][$dim];
        }

        // Calculate Normalized Scores
        $normalizedScores = [
            'graph1' => $this->normalizeGraph1($rawScores['graph1_most']),
            'graph2' => $this->normalizeGraph2($rawScores['graph2_least']),
            'graph3' => $this->normalizeGraph3($rawScores['graph3_change']),
        ];

        // Classify 3 graphs matching Excel DISC system
        $maskIndex = \App\Data\DiscReferenceData::matchPattern(
            $normalizedScores['graph1']['D'],
            $normalizedScores['graph1']['I'],
            $normalizedScores['graph1']['S'],
            $normalizedScores['graph1']['C']
        );
        $coreIndex = \App\Data\DiscReferenceData::matchPattern(
            $normalizedScores['graph2']['D'],
            $normalizedScores['graph2']['I'],
            $normalizedScores['graph2']['S'],
            $normalizedScores['graph2']['C']
        );
        $mirrorIndex = \App\Data\DiscReferenceData::matchPattern(
            $normalizedScores['graph3']['D'],
            $normalizedScores['graph3']['I'],
            $normalizedScores['graph3']['S'],
            $normalizedScores['graph3']['C']
        );

        $maskProfile = \App\Data\DiscReferenceData::getProfile($maskIndex);
        $coreProfile = \App\Data\DiscReferenceData::getProfile($coreIndex);
        $mirrorProfile = \App\Data\DiscReferenceData::getProfile($mirrorIndex);

        return [
            'raw_scores' => $rawScores,
            'normalized_scores' => $normalizedScores,
            'profile_code' => $mirrorProfile['code'] ?? 'DISC',
            'profile_name' => $mirrorProfile['name'] ?? 'DISC Profile',
            'profile' => [
                'code' => $mirrorProfile['code'] ?? 'DISC',
                'name' => $mirrorProfile['name'] ?? 'DISC Profile',
            ],
            'character_profiles' => [
                'mask' => $maskProfile,
                'core' => $coreProfile,
                'mirror' => $mirrorProfile,
            ],
            'interpretation' => [
                'description' => $mirrorProfile['description'] ?? '',
                'strengths' => implode(', ', array_slice($mirrorProfile['traits'] ?? [], 0, 4)),
                'potential_limitations' => implode(', ', array_slice($mirrorProfile['traits'] ?? [], 4)),
                'communication_style' => ($maskProfile['name'] ?? '') . ': ' . implode(', ', array_slice($maskProfile['traits'] ?? [], 0, 3)),
                'work_style' => ($coreProfile['name'] ?? '') . ': ' . implode(', ', array_slice($coreProfile['traits'] ?? [], 0, 3)),
                'development_suggestions' => $mirrorProfile['jobs'] ?? '-',
                'jobs' => $mirrorProfile['jobs'] ?? '',
                'mask_traits' => $maskProfile['traits'] ?? [],
                'core_traits' => $coreProfile['traits'] ?? [],
                'mirror_traits' => $mirrorProfile['traits'] ?? [],
            ],
        ];
    }

    public function calculateSessionScore(DiscAssessmentSession $session): array
    {
        return $this->processSession($session);
    }

    /**
     * Normalize Graph 1 (Most / Mask)
     */
    public function normalizeGraph1(array $rawMost): array
    {
        $result = [];
        foreach (['D', 'I', 'S', 'C'] as $dim) {
            $raw = max(0, min(20, (int) ($rawMost[$dim] ?? 0)));
            $result[$dim] = (float) (\App\Data\DiscReferenceData::NORM_GRAPH_1[$raw][$dim] ?? 0.0);
        }

        return $result;
    }

    public function normalizeGraph2(array $rawLeast): array
    {
        $result = [];
        foreach (['D', 'I', 'S', 'C'] as $dim) {
            $raw = max(0, min(20, (int) ($rawLeast[$dim] ?? 0)));
            $result[$dim] = (float) (\App\Data\DiscReferenceData::NORM_GRAPH_2[$raw][$dim] ?? 0.0);
        }

        return $result;
    }

    public function normalizeGraph3(array $rawChange): array
    {
        $result = [];
        foreach (['D', 'I', 'S', 'C'] as $dim) {
            $raw = max(-22, min(22, (int) ($rawChange[$dim] ?? 0)));
            $result[$dim] = (float) (\App\Data\DiscReferenceData::NORM_GRAPH_3[$raw][$dim] ?? 0.0);
        }

        return $result;
    }

    /**
     * Classify DISC pattern & retrieve detailed interpretation
     */
    public function classifyProfile(array $graph3Norm, array $graph1Norm): array
    {
        // Rank factors by normalized score
        arsort($graph3Norm);
        $keys = array_keys($graph3Norm);
        $primary = $keys[0];
        $secondary = $keys[1];

        // Primary dominant code
        $code = $primary;
        if ($graph3Norm[$secondary] > 0.0 && ($graph3Norm[$primary] - $graph3Norm[$secondary]) <= 3.5) {
            $code = $primary . $secondary;
        }

        // Try exact match in DB
        $profile = DiscProfile::query()->where('profile_code', $code)->where('is_active', true)->first();
        if (!$profile && strlen($code) > 1) {
            // Try single letter primary
            $profile = DiscProfile::query()->where('profile_code', $primary)->where('is_active', true)->first();
        }

        if ($profile) {
            return [
                'code' => $profile->profile_code,
                'name' => $profile->profile_name,
                'interpretation' => [
                    'description' => $profile->description,
                    'strengths' => $profile->strengths,
                    'potential_limitations' => $profile->potential_limitations,
                    'communication_style' => $profile->communication_style,
                    'work_style' => $profile->work_style,
                    'development_suggestions' => $profile->development_suggestions,
                ],
            ];
        }

        // Fallback default dictionary
        $defaults = self::defaultProfileDictionary($code, $primary);

        return [
            'code' => $code,
            'name' => $defaults['name'],
            'interpretation' => $defaults['interpretation'],
        ];
    }

    public static function defaultProfileDictionary(string $code, string $primary): array
    {
        $dict = [
            'D' => [
                'name' => 'Dominance (The Commander / Driver)',
                'desc' => 'Individu yang tegas, berorientasi hasil, berani mengambil risiko, menyukai tantangan, dan fokus pada efisiensi serta pencapaian target tinggi.',
                'strengths' => 'Kepemimpinan kuat, pengambilan keputusan cepat, berani menghadapi tekanan, gigih mengatasi rintangan.',
                'limitations' => 'Dapat terkesan kurang sabar, menuntut, kurang memperhatikan detail administratif atau perasaan orang lain.',
                'comm' => 'Langsung pada inti (to the point), singkat, tegas, fokus pada sasaran dan hasil konkret.',
                'work' => 'Mandiri, target-driven, menyukai kendali atas proyek dan ruang inovasi yang bebas birokrasi.',
                'dev' => 'Tingkatkan empati, dengarkan masukan anggota tim secara aktif, dan latih kesabaran dalam proses bertahap.',
            ],
            'I' => [
                'name' => 'Influence (The Influencer / Communicator)',
                'desc' => 'Individu yang antusias, ramah, persuasif, optimis, dan membangun jejaring hubungan sosial dengan sangat baik.',
                'strengths' => 'Kemampuan komunikasi verbal sangat baik, memotivasi orang lain, membangun atmosfer kerja positif dan kreatif.',
                'limitations' => 'Terkadang kurang teliti pada detail, mudah terdistraksi ide baru, dan kurang menyukai tugas rutin berulang.',
                'comm' => 'Hangat, ekspresif, persuasif, menyukai diskusi dua arah yang interaktif dan menyenangkan.',
                'work' => 'Kolaboratif, dinamis, menyukai interaksi dengan orang banyak dan proyek bernuansa presentasi/networking.',
                'dev' => 'Tingkatkan manajemen waktu, dokumentasikan detail rencana tertulis, dan selesaikan proyek hingga tuntas.',
            ],
            'S' => [
                'name' => 'Steadiness (The Supporter / Specialist)',
                'desc' => 'Individu yang tenang, setia, sabar, dapat diandalkan, suportif, serta menyukai konsistensi dan harmoni dalam tim.',
                'strengths' => 'Pendengar yang sangat baik, loyalitas tinggi, telaten, menjaga stabilitas tim, konsisten dalam menyelesaikan tugas.',
                'limitations' => 'Cenderung enggan terhadap perubahan mendadak, sulit berkata tidak, dan menghindari konfrontasi langsung.',
                'comm' => 'Tenang, ramah, tulus, menghargai perasaan rekan kerja, dan menggunakan pendekatan non-konfrontatif.',
                'work' => 'Terstruktur, teratur, menyukai prosedur yang jelas dan lingkungan kerja yang kooperatif tanpa konflik.',
                'dev' => 'Latih keberanian menyuarakan opini pribadi secara terbuka, dan belajar beradaptasi lebih cepat terhadap dinamika baru.',
            ],
            'C' => [
                'name' => 'Conscientiousness (The Analyst / Perfectionist)',
                'desc' => 'Individu yang analitis, teliti, sistematis, objektif, berorientasi data dan standar kualitas yang tinggi.',
                'strengths' => 'Akurasi tinggi, pemikiran logis mendalam, teratur, taat prosedur, menghasilkan pekerjaan berkualitas tanpa cela.',
                'limitations' => 'Dapat terjebak dalam analisis berlebih (overthinking), terlalu kritis terhadap ketidaksempurnaan, lambat jika data minim.',
                'comm' => 'Faktual, berbasis data dan fakta, logis, terstruktur, dan tidak menyukai asumsi tanpa bukti.',
                'work' => 'Metodis, fokus pada akurasi, riset, standarisasi mutu, dan kepatuhan terhadap SOP operasional.',
                'dev' => 'Berani mengambil keputusan pada kondisi ketidakpastian data, dan toleran terhadap kesalahan minor yang dapat diperbaiki.',
            ],
            'DI' => [
                'name' => 'Result-Oriented Influencer (The Motivator)',
                'desc' => 'Kombinasi ketegasan dan antusiasme tinggi. Mampu mendorong perubahan besar sekaligus menggerakkan tim dengan energi positif.',
                'strengths' => 'Inisiatif tinggi, persuasif, cepat bertindak, kepemimpinan transformatif.',
                'limitations' => 'Terkadang impulsif dan kurang sabar dalam mengawal detail pelaksanaan teknis.',
                'comm' => 'Bersemangat, langsung pada inti persoalan, inspiratif, dan penuh keyakinan.',
                'work' => 'Dinamis, target tinggi, menyukai proyek ekspansi atau perintisan inisiatif baru.',
                'dev' => 'Terapkan review teratur dan delegasikan pengawasan detail teknis kepada tim pendukung.',
            ],
            'DC' => [
                'name' => 'Creative Analyst (The Architect)',
                'desc' => 'Kombinasi determinasi tinggi dan standar kualitas presisi. Memecahkan masalah rumit dengan solusi sistemik yang teruji.',
                'strengths' => 'Visioner, analitis, mandiri, gigih menetapkan standar keunggulan.',
                'limitations' => 'Ekspektasi sangat tinggi terhadap diri sendiri dan rekan, terkesan berjarak.',
                'comm' => 'Objektif, lugas, fokus pada logika, strategi, dan perbaikan berkelanjutan.',
                'work' => 'Strategis, pemecahan masalah kompleks, arsitektur sistem, dan manajemen kualitas.',
                'dev' => 'Apresiasi kontribusi orang lain secara terbuka dan luangkan waktu untuk relasi informal.',
            ],
            'IS' => [
                'name' => 'Harmonious Communicator (The Counselor)',
                'desc' => 'Kombinasi kehangatan sosial dan empati mendalam. Sangat pandai membina hubungan antarmanusia dan menyelesaikan konflik internal.',
                'strengths' => 'Empati tinggi, komunikatif, menjaga moral tim, setia kawan, mediator ulung.',
                'limitations' => 'Sulit memberikan feedback yang tegas atau kritik keras kepada rekan kerja.',
                'comm' => 'Lembut, menghargai, ramah, membangun kepercayaan, dan penuh penerimaan.',
                'work' => 'People-oriented, customer relations, bimbingan, HR, dan layanan dukungan.',
                'dev' => 'Latih ketegasan asertif saat menetapkan batasan atau menegakkan disiplin kerja.',
            ],
            'SC' => [
                'name' => 'Diligent Specialist (The Practitioner)',
                'desc' => 'Kombinasi ketenangan, ketekunan, dan presisi tinggi. Selalu menyelesaikan tugas dengan kualitas konsisten dan minim kesalahan.',
                'strengths' => 'Dapat diandalkan 100%, metodis, tenang di bawah tekanan, rapi, teliti.',
                'limitations' => 'Menghindari risiko, cenderung pasif dalam mengambil inisiatif perubahan radikal.',
                'comm' => 'Sopan, terukur, hati-hati, berbobot, dan mengutamakan kejelasan tertulis.',
                'work' => 'Operasional berstandar tinggi, kepatuhan regulasi, administrasi keuangan/teknis teratur.',
                'dev' => 'Kembangkan fleksibilitas dalam menghadapi ketidakpastian dan inisiatif kepemimpinan.',
            ],
        ];

        $target = $dict[$code] ?? ($dict[$primary] ?? $dict['D']);

        return [
            'name' => $target['name'],
            'interpretation' => [
                'description' => $target['desc'],
                'strengths' => $target['strengths'],
                'potential_limitations' => $target['limitations'],
                'communication_style' => $target['comm'],
                'work_style' => $target['work'],
                'development_suggestions' => $target['dev'],
            ],
        ];
    }
}
