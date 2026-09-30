<?php

namespace App\Services;

use App\Data\DiscReferenceData;
use App\Models\DiscAssessmentSession;
use Carbon\Carbon;
use DOMDocument;
use DOMXPath;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use ZipArchive;

class DiscReportExporter
{
    /**
     * Export single session DISC result as authentic Excel (.xlsx) exactly matching master template
     */
    public function exportSessionExcel(DiscAssessmentSession $session): BinaryFileResponse
    {
        $session->loadMissing([
            'participant.candidateProfile',
            'assessment',
            'answers.question',
            'answers.mostOption',
            'answers.leastOption',
        ]);

        $templatePath = storage_path('app/disc_template.xlsx');
        if (!file_exists($templatePath)) {
            $backupPath = base_path('20260916 - KHAERUL UMAM - HASIL DISC.xlsx');
            if (file_exists($backupPath)) {
                copy($backupPath, $templatePath);
            } else {
                abort(500, 'DISC Master template file not found.');
            }
        }

        $tempDir = storage_path('app/temp');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $tempFile = $tempDir . '/DISC_' . $session->id . '_' . uniqid() . '.xlsx';
        copy($templatePath, $tempFile);

        $zip = new ZipArchive();
        if ($zip->open($tempFile) !== true) {
            abort(500, 'Unable to open XLSX archive.');
        }

        // Candidate details
        $participant = $session->participant;
        $profile = $participant?->candidateProfile;

        $name = strtoupper(trim($participant->name ?? 'KANDIDAT'));
        $gender = 'LAKI-LAKI';
        if ($profile && !empty($profile->gender)) {
            $g = strtoupper(trim($profile->gender));
            $gender = ($g === 'FEMALE' || $g === 'PEREMPUAN') ? 'PEREMPUAN' : 'LAKI-LAKI';
        }

        $age = '25 TAHUN';
        if ($profile && !empty($profile->birth_date)) {
            $years = Carbon::parse($profile->birth_date)->age;
            $age = $years . ' TAHUN';
        }

        $testDate = $session->completed_at ? $session->completed_at->format('d-M-y') : now()->format('d-M-y');
        $testDateFull = $session->completed_at ? $session->completed_at->translatedFormat('d F Y') : now()->translatedFormat('d F Y');

        // Answers map: question_number (1..24) => ['most' => opt_num (1..4), 'least' => opt_num (1..4)]
        $answersMap = [];
        foreach ($session->answers as $ans) {
            $qNum = (int) ($ans->question->question_number ?? 0);
            if ($qNum >= 1 && $qNum <= 24) {
                $answersMap[$qNum] = [
                    'most' => $ans->mostOption ? (int) $ans->mostOption->option_number : 0,
                    'least' => $ans->leastOption ? (int) $ans->leastOption->option_number : 0,
                ];
            }
        }

        // Calculate scores via DiscScoringEngine
        $scoringEngine = app(DiscScoringEngine::class);
        $scoreResult = $scoringEngine->calculateSessionScore($session);

        $rawScores = $scoreResult['raw_scores'];
        $normScores = $scoreResult['normalized_scores'];

        $maskIndex = DiscReferenceData::matchPattern(
            $normScores['graph1']['D'],
            $normScores['graph1']['I'],
            $normScores['graph1']['S'],
            $normScores['graph1']['C']
        );
        $coreIndex = DiscReferenceData::matchPattern(
            $normScores['graph2']['D'],
            $normScores['graph2']['I'],
            $normScores['graph2']['S'],
            $normScores['graph2']['C']
        );
        $mirrorIndex = DiscReferenceData::matchPattern(
            $normScores['graph3']['D'],
            $normScores['graph3']['I'],
            $normScores['graph3']['S'],
            $normScores['graph3']['C']
        );

        $maskProfile = DiscReferenceData::getProfile($maskIndex);
        $coreProfile = DiscReferenceData::getProfile($coreIndex);
        $mirrorProfile = DiscReferenceData::getProfile($mirrorIndex);

        // 1. Process sheet1.xml (DISC Test)
        $sheet1Xml = $zip->getFromName('xl/worksheets/sheet1.xml');
        $doc1 = new DOMDocument();
        $doc1->loadXML($sheet1Xml);
        $xpath1 = new DOMXPath($doc1);
        $xpath1->registerNamespace('x', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');

        $this->setCellInlineStr($doc1, $xpath1, 'F2', $name);
        $this->setCellInlineStr($doc1, $xpath1, 'F3', $age);
        $this->setCellInlineStr($doc1, $xpath1, 'F4', $gender);
        $this->setCellInlineStr($doc1, $xpath1, 'F5', $testDateFull);

        // Clear existing checkmarks and set candidate's answers
        for ($q = 1; $q <= 24; $q++) {
            $startRow = 8 + (($q - 1) % 8) * 6;
            $colMost = $q <= 8 ? 'B' : ($q <= 16 ? 'I' : 'P');
            $colLeast = $q <= 8 ? 'D' : ($q <= 16 ? 'K' : 'R');

            for ($opt = 1; $opt <= 4; $opt++) {
                $row = $startRow + ($opt - 1);
                $this->clearCellVal($xpath1, $colMost . $row);
                $this->clearCellVal($xpath1, $colLeast . $row);
            }

            if (isset($answersMap[$q])) {
                $chosenMost = $answersMap[$q]['most'];
                $chosenLeast = $answersMap[$q]['least'];
                if ($chosenMost >= 1 && $chosenMost <= 4) {
                    $rowM = $startRow + ($chosenMost - 1);
                    $this->setCellInlineStr($doc1, $xpath1, $colMost . $rowM, 'X');
                }
                if ($chosenLeast >= 1 && $chosenLeast <= 4) {
                    $rowL = $startRow + ($chosenLeast - 1);
                    $this->setCellInlineStr($doc1, $xpath1, $colLeast . $rowL, 'X');
                }
            }
        }
        $zip->addFromString('xl/worksheets/sheet1.xml', $doc1->saveXML());

        // 2. Process sheet2.xml (Input)
        $sheet2Xml = $zip->getFromName('xl/worksheets/sheet2.xml');
        $doc2 = new DOMDocument();
        $doc2->loadXML($sheet2Xml);
        $xpath2 = new DOMXPath($doc2);
        $xpath2->registerNamespace('x', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');

        $this->setCellInlineStr($doc2, $xpath2, 'D2', $name);
        $this->setCellInlineStr($doc2, $xpath2, 'D3', $age);
        $this->setCellInlineStr($doc2, $xpath2, 'K2', $gender);
        $this->setCellInlineStr($doc2, $xpath2, 'K3', $testDate);

        $zip->addFromString('xl/worksheets/sheet2.xml', $doc2->saveXML());

        // 3. Process sheet3.xml (Result)
        $sheet3Xml = $zip->getFromName('xl/worksheets/sheet3.xml');
        $doc3 = new DOMDocument();
        $doc3->loadXML($sheet3Xml);
        $xpath3 = new DOMXPath($doc3);
        $xpath3->registerNamespace('x', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');

        // Header info
        $this->setCellInlineStr($doc3, $xpath3, 'I4', $name);
        $this->setCellInlineStr($doc3, $xpath3, 'I5', $age);
        $this->setCellInlineStr($doc3, $xpath3, 'I6', $gender);
        $this->setCellInlineStr($doc3, $xpath3, 'I7', $testDate);

        // Matrix Raw scores: Row 10 (Most), Row 11 (Least), Row 12 (Change)
        $this->setCellFormulaVal($doc3, $xpath3, 'H10', (string) ($rawScores['graph1_most']['D'] ?? 0));
        $this->setCellFormulaVal($doc3, $xpath3, 'I10', (string) ($rawScores['graph1_most']['I'] ?? 0));
        $this->setCellFormulaVal($doc3, $xpath3, 'J10', (string) ($rawScores['graph1_most']['S'] ?? 0));
        $this->setCellFormulaVal($doc3, $xpath3, 'K10', (string) ($rawScores['graph1_most']['C'] ?? 0));
        $this->setCellFormulaVal($doc3, $xpath3, 'L10', (string) ($rawScores['graph1_most']['*'] ?? 0));
        $this->setCellFormulaVal($doc3, $xpath3, 'M10', '24');

        $this->setCellFormulaVal($doc3, $xpath3, 'H11', (string) ($rawScores['graph2_least']['D'] ?? 0));
        $this->setCellFormulaVal($doc3, $xpath3, 'I11', (string) ($rawScores['graph2_least']['I'] ?? 0));
        $this->setCellFormulaVal($doc3, $xpath3, 'J11', (string) ($rawScores['graph2_least']['S'] ?? 0));
        $this->setCellFormulaVal($doc3, $xpath3, 'K11', (string) ($rawScores['graph2_least']['C'] ?? 0));
        $this->setCellFormulaVal($doc3, $xpath3, 'L11', (string) ($rawScores['graph2_least']['*'] ?? 0));
        $this->setCellFormulaVal($doc3, $xpath3, 'M11', '24');

        $this->setCellFormulaVal($doc3, $xpath3, 'H12', (string) ($rawScores['graph3_change']['D'] ?? 0));
        $this->setCellFormulaVal($doc3, $xpath3, 'I12', (string) ($rawScores['graph3_change']['I'] ?? 0));
        $this->setCellFormulaVal($doc3, $xpath3, 'J12', (string) ($rawScores['graph3_change']['S'] ?? 0));
        $this->setCellFormulaVal($doc3, $xpath3, 'K12', (string) ($rawScores['graph3_change']['C'] ?? 0));

        // Normalized scores
        $this->setCellFormulaVal($doc3, $xpath3, 'BB7', (string) ($normScores['graph1']['D'] ?? 0));
        $this->setCellFormulaVal($doc3, $xpath3, 'BC7', (string) ($normScores['graph1']['I'] ?? 0));
        $this->setCellFormulaVal($doc3, $xpath3, 'BD7', (string) ($normScores['graph1']['S'] ?? 0));
        $this->setCellFormulaVal($doc3, $xpath3, 'BE7', (string) ($normScores['graph1']['C'] ?? 0));

        $this->setCellFormulaVal($doc3, $xpath3, 'BB9', (string) ($normScores['graph2']['D'] ?? 0));
        $this->setCellFormulaVal($doc3, $xpath3, 'BC9', (string) ($normScores['graph2']['I'] ?? 0));
        $this->setCellFormulaVal($doc3, $xpath3, 'BD9', (string) ($normScores['graph2']['S'] ?? 0));
        $this->setCellFormulaVal($doc3, $xpath3, 'BE9', (string) ($normScores['graph2']['C'] ?? 0));

        $this->setCellFormulaVal($doc3, $xpath3, 'BB11', (string) ($normScores['graph3']['D'] ?? 0));
        $this->setCellFormulaVal($doc3, $xpath3, 'BC11', (string) ($normScores['graph3']['I'] ?? 0));
        $this->setCellFormulaVal($doc3, $xpath3, 'BD11', (string) ($normScores['graph3']['S'] ?? 0));
        $this->setCellFormulaVal($doc3, $xpath3, 'BE11', (string) ($normScores['graph3']['C'] ?? 0));

        // Pattern match indices in CY
        $this->setCellFormulaVal($doc3, $xpath3, 'CY7', (string) $maskIndex);
        $this->setCellFormulaVal($doc3, $xpath3, 'CY9', (string) $coreIndex);
        $this->setCellFormulaVal($doc3, $xpath3, 'CY11', (string) $mirrorIndex);

        // Gambaran Karakter: Mask Public Self (Col B, Rows 44 to 56)
        $this->setCellFormulaVal($doc3, $xpath3, 'B44', $maskProfile['name']);
        for ($i = 0; $i < 12; $i++) {
            $row = 45 + $i;
            $val = $maskProfile['traits'][$i] ?? '0';
            $this->setCellFormulaVal($doc3, $xpath3, 'B' . $row, $val);
        }

        // Core Private Self (Col L, Rows 44 to 56)
        $this->setCellFormulaVal($doc3, $xpath3, 'L44', $coreProfile['name']);
        for ($i = 0; $i < 12; $i++) {
            $row = 45 + $i;
            $val = $coreProfile['traits'][$i] ?? '0';
            $this->setCellFormulaVal($doc3, $xpath3, 'L' . $row, $val);
        }

        // Mirror Perceived Self (Col B, Rows 60 to 72)
        $this->setCellFormulaVal($doc3, $xpath3, 'B60', $mirrorProfile['name']);
        for ($i = 0; $i < 12; $i++) {
            $row = 61 + $i;
            $val = $mirrorProfile['traits'][$i] ?? '0';
            $this->setCellFormulaVal($doc3, $xpath3, 'B' . $row, $val);
        }

        // Deskripsi Kepribadian (Row 77, Col B)
        $this->setCellFormulaVal($doc3, $xpath3, 'B77', $mirrorProfile['description'] ?? '');

        $zip->addFromString('xl/worksheets/sheet3.xml', $doc3->saveXML());
        $zip->close();

        $cleanName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $participant->name ?? 'KANDIDAT');
        $datePrefix = $session->completed_at ? $session->completed_at->format('Ymd') : now()->format('Ymd');
        $downloadFilename = "{$datePrefix} - {$cleanName} - HASIL DISC.xlsx";

        return response()->download($tempFile, $downloadFilename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    private function setCellInlineStr(DOMDocument $doc, DOMXPath $xpath, string $cellRef, string $value): void
    {
        $nodes = $xpath->query('//x:c[@r="' . $cellRef . '"]');
        if ($nodes->length > 0) {
            $cell = $nodes->item(0);
            $cell->setAttribute('t', 'inlineStr');
            while ($cell->firstChild) {
                $cell->removeChild($cell->firstChild);
            }
            $is = $doc->createElementNS('http://schemas.openxmlformats.org/spreadsheetml/2006/main', 'is');
            $t = $doc->createElementNS('http://schemas.openxmlformats.org/spreadsheetml/2006/main', 't');
            $t->textContent = $value;
            $is->appendChild($t);
            $cell->appendChild($is);
        }
    }

    private function clearCellVal(DOMXPath $xpath, string $cellRef): void
    {
        $nodes = $xpath->query('//x:c[@r="' . $cellRef . '"]');
        if ($nodes->length > 0) {
            $cell = $nodes->item(0);
            $cell->removeAttribute('t');
            while ($cell->firstChild) {
                $cell->removeChild($cell->firstChild);
            }
        }
    }

    private function setCellFormulaVal(DOMDocument $doc, DOMXPath $xpath, string $cellRef, string $val): void
    {
        $nodes = $xpath->query('//x:c[@r="' . $cellRef . '"]');
        if ($nodes->length > 0) {
            $cell = $nodes->item(0);
            $v = $cell->getElementsByTagNameNS('http://schemas.openxmlformats.org/spreadsheetml/2006/main', 'v')->item(0);
            if (!$v) {
                $v = $doc->createElementNS('http://schemas.openxmlformats.org/spreadsheetml/2006/main', 'v');
                $cell->appendChild($v);
            }
            $v->textContent = $val;
        }
    }

    /**
     * Export single session DISC result as CSV matching master Result sheet format
     */
    public function exportSessionCsv(DiscAssessmentSession $session): StreamedResponse
    {
        $session->loadMissing([
            'participant.candidateProfile',
            'answers.question',
            'answers.mostOption',
            'answers.leastOption',
        ]);

        $participant = $session->participant;
        $profile = $participant?->candidateProfile;

        $name = strtoupper($participant->name ?? ($session->candidate_name ?? 'KANDIDAT'));
        $gender = 'LAKI-LAKI';
        if ($profile && !empty($profile->gender)) {
            $gender = (strtoupper($profile->gender) === 'FEMALE' || strtoupper($profile->gender) === 'PEREMPUAN') ? 'PEREMPUAN' : 'LAKI-LAKI';
        }
        $age = '25 TAHUN';
        if ($profile && !empty($profile->birth_date)) {
            $years = \Carbon\Carbon::parse($profile->birth_date)->age;
            $age = $years . ' TAHUN';
        }
        $testDate = $session->completed_at ? $session->completed_at->format('d-M-y') : ($session->created_at ? $session->created_at->format('d-M-y') : now()->format('d-M-y'));

        $raw = $session->raw_scores_json ?? [];
        $norm = $session->normalized_scores_json ?? [];

        // In case raw / norm scores need calculation
        if (empty($raw) || empty($norm)) {
            $engine = app(\App\Services\DiscScoringEngine::class);
            $scores = $engine->calculateScores($raw['graph1_most'] ?? [], $raw['graph2_least'] ?? []);
            $raw = $scores['raw_scores'];
            $norm = $scores['normalized_scores'];
        }

        $d1 = $raw['graph1_most']['D'] ?? 0;
        $i1 = $raw['graph1_most']['I'] ?? 0;
        $s1 = $raw['graph1_most']['S'] ?? 0;
        $c1 = $raw['graph1_most']['C'] ?? 0;
        $star1 = $raw['graph1_most']['*'] ?? 0;
        $tot1 = array_sum($raw['graph1_most'] ?? [0]);

        $d2 = $raw['graph2_least']['D'] ?? 0;
        $i2 = $raw['graph2_least']['I'] ?? 0;
        $s2 = $raw['graph2_least']['S'] ?? 0;
        $c2 = $raw['graph2_least']['C'] ?? 0;
        $star2 = $raw['graph2_least']['*'] ?? 0;
        $tot2 = array_sum($raw['graph2_least'] ?? [0]);

        $d3 = $raw['graph3_change']['D'] ?? ($d1 - $d2);
        $i3 = $raw['graph3_change']['I'] ?? ($i1 - $i2);
        $s3 = $raw['graph3_change']['S'] ?? ($s1 - $s2);
        $c3 = $raw['graph3_change']['C'] ?? ($c1 - $c2);

        $norm1_d = number_format($norm['graph1']['D'] ?? 0, 1);
        $norm1_i = number_format($norm['graph1']['I'] ?? 0, 1);
        $norm1_s = number_format($norm['graph1']['S'] ?? 0, 1);
        $norm1_c = number_format($norm['graph1']['C'] ?? 0, 1);

        $norm2_d = number_format($norm['graph2']['D'] ?? 0, 1);
        $norm2_i = number_format($norm['graph2']['I'] ?? 0, 1);
        $norm2_s = number_format($norm['graph2']['S'] ?? 0, 1);
        $norm2_c = number_format($norm['graph2']['C'] ?? 0, 1);

        $norm3_d = number_format($norm['graph3']['D'] ?? 0, 1);
        $norm3_i = number_format($norm['graph3']['I'] ?? 0, 1);
        $norm3_s = number_format($norm['graph3']['S'] ?? 0, 1);
        $norm3_c = number_format($norm['graph3']['C'] ?? 0, 1);

        $maskIndex = \App\Data\DiscReferenceData::matchPattern((float)($norm['graph1']['D'] ?? 0), (float)($norm['graph1']['I'] ?? 0), (float)($norm['graph1']['S'] ?? 0), (float)($norm['graph1']['C'] ?? 0));
        $coreIndex = \App\Data\DiscReferenceData::matchPattern((float)($norm['graph2']['D'] ?? 0), (float)($norm['graph2']['I'] ?? 0), (float)($norm['graph2']['S'] ?? 0), (float)($norm['graph2']['C'] ?? 0));
        $mirrorIndex = \App\Data\DiscReferenceData::matchPattern((float)($norm['graph3']['D'] ?? 0), (float)($norm['graph3']['I'] ?? 0), (float)($norm['graph3']['S'] ?? 0), (float)($norm['graph3']['C'] ?? 0));

        $maskProfile = \App\Data\DiscReferenceData::getProfile($maskIndex);
        $coreProfile = \App\Data\DiscReferenceData::getProfile($coreIndex);
        $mirrorProfile = \App\Data\DiscReferenceData::getProfile($mirrorIndex);

        $cleanName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $participant->name ?? 'KANDIDAT');
        $datePrefix = $session->completed_at ? $session->completed_at->format('Ymd') : now()->format('Ymd');
        $filename = "{$datePrefix} - {$cleanName} - HASIL DISC.csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use (
            $name, $age, $gender, $testDate,
            $d1, $i1, $s1, $c1, $star1, $tot1,
            $d2, $i2, $s2, $c2, $star2, $tot2,
            $d3, $i3, $s3, $c3,
            $norm1_d, $norm1_i, $norm1_s, $norm1_c,
            $norm2_d, $norm2_i, $norm2_s, $norm2_c,
            $norm3_d, $norm3_i, $norm3_s, $norm3_c,
            $maskProfile, $coreProfile, $mirrorProfile
        ) {
            $handle = fopen('php://output', 'w');
            // Add UTF-8 BOM
            fputs($handle, "\xEF\xBB\xBF");

            // Header D I S C
            fputcsv($handle, ['D I S C']);
            fputcsv($handle, ['Personality System Graph Page']);
            fputcsv($handle, []);

            // Personal metadata
            fputcsv($handle, ['Name', ':', $name]);
            fputcsv($handle, ['Age', ':', $age]);
            fputcsv($handle, ['Gender', ':', $gender]);
            fputcsv($handle, ['Tgl. Tes', ':', $testDate]);
            fputcsv($handle, []);

            // Raw Score Matrix Table
            fputcsv($handle, ['TABEL SKOR MENTAH (RAW SCORES)']);
            fputcsv($handle, ['Line', 'D', 'I', 'S', 'C', '*', 'tot']);
            fputcsv($handle, ['1', $d1, $i1, $s1, $c1, $star1, $tot1]);
            fputcsv($handle, ['2', $d2, $i2, $s2, $c2, $star2, $tot2]);
            fputcsv($handle, ['3', $d3, $i3, $s3, $c3, '', '']);
            fputcsv($handle, []);

            // Normalized Scores
            fputcsv($handle, ['TABEL SKOR TERNORMALISASI (NORMALIZED SCORES)']);
            fputcsv($handle, ['Line', 'D', 'I', 'S', 'C', 'Pola Kepribadian']);
            fputcsv($handle, ['Graph 1 (Mask Public Self)', $norm1_d, $norm1_i, $norm1_s, $norm1_c, $maskProfile['name'] ?? '']);
            fputcsv($handle, ['Graph 2 (Core Private Self)', $norm2_d, $norm2_i, $norm2_s, $norm2_c, $coreProfile['name'] ?? '']);
            fputcsv($handle, ['Graph 3 (Mirror Perceived Self)', $norm3_d, $norm3_i, $norm3_s, $norm3_c, $mirrorProfile['name'] ?? '']);
            fputcsv($handle, []);

            // Gambaran Karakter
            fputcsv($handle, ['GAMBARAN KARAKTER']);
            fputcsv($handle, [
                'No',
                'Mask Public Self (' . ($maskProfile['name'] ?? '') . ')',
                'Core Private Self (' . ($coreProfile['name'] ?? '') . ')',
                'Mirror Perceived Self (' . ($mirrorProfile['name'] ?? '') . ')',
            ]);

            $traitsMask = $maskProfile['traits'] ?? [];
            $traitsCore = $coreProfile['traits'] ?? [];
            $traitsMirror = $mirrorProfile['traits'] ?? [];

            $maxTraits = max(count($traitsMask), count($traitsCore), count($traitsMirror), 12);
            $rowNum = 1;
            for ($idx = 0; $idx < $maxTraits; $idx++) {
                $tMask = $traitsMask[$idx] ?? '';
                $tCore = $traitsCore[$idx] ?? '';
                $tMirror = $traitsMirror[$idx] ?? '';

                if ($tMask === '0') $tMask = '';
                if ($tCore === '0') $tCore = '';
                if ($tMirror === '0') $tMirror = '';

                if ($tMask === '' && $tCore === '' && $tMirror === '') {
                    continue;
                }

                fputcsv($handle, [$rowNum++, $tMask, $tCore, $tMirror]);
            }
            fputcsv($handle, []);

            // Deskripsi Kepribadian
            fputcsv($handle, ['DESKRIPSI KEPRIBADIAN (Berdasarkan Pola Mirror: ' . ($mirrorProfile['name'] ?? '') . ')']);
            fputcsv($handle, [$mirrorProfile['description'] ?? '']);
            fputcsv($handle, []);

            // Rekomendasi Area Profesi / Jabatan (Job Match)
            fputcsv($handle, ['REKOMENDASI AREA PROFESI / JABATAN (JOB MATCH)']);
            fputcsv($handle, [$mirrorProfile['jobs'] ?? '']);

            fclose($handle);
        }, 200, $headers);
    }
}
