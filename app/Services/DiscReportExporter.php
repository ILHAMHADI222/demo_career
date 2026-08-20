<?php

namespace App\Services;

use App\Models\DiscAssessmentSession;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DiscReportExporter
{
    /**
     * Export single session DISC result as CSV
     */
    public function exportSessionCsv(DiscAssessmentSession $session): StreamedResponse
    {
        $filename = 'DISC_Result_' . ($session->participant->name ?? 'Candidate') . '_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($session) {
            $handle = fopen('php://output', 'w');
            // Add UTF-8 BOM
            fputs($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['LAPORAN HASIL ASSESMEN DISC INDIVIDUAL']);
            fputcsv($handle, ['PT INDODATA SOLUSI PERSADA - CAREER HALLONET']);
            fputcsv($handle, []);

            // Participant info
            fputcsv($handle, ['Nama Peserta', $session->participant->name ?? '-']);
            fputcsv($handle, ['Email', $session->participant->email ?? '-']);
            fputcsv($handle, ['No. Kandidat', $session->participant->candidateProfile->candidate_number ?? '-']);
            fputcsv($handle, ['Judul Asesmen', $session->assessment->title ?? '-']);
            fputcsv($handle, ['Kode Asesmen', $session->assessment->code ?? '-']);
            fputcsv($handle, ['Versi Bank Soal', $session->assessment->question_bank_version ?? '1.0']);
            fputcsv($handle, ['Percobaan (Attempt)', $session->attempt_number]);
            fputcsv($handle, ['Waktu Mulai', $session->started_at ? $session->started_at->format('d/m/Y H:i:s') : '-']);
            fputcsv($handle, ['Waktu Selesai', $session->completed_at ? $session->completed_at->format('d/m/Y H:i:s') : '-']);
            fputcsv($handle, ['Indeks Keamanan', $session->security_score . '/100']);
            fputcsv($handle, []);

            // DISC Summary
            fputcsv($handle, ['PROFIL DISC UTAMA', $session->disc_profile_code, $session->disc_profile_name]);
            fputcsv($handle, []);

            // Score Table
            fputcsv($handle, ['TABEL SKOR DISC']);
            fputcsv($handle, ['Dimensi / Faktor', 'Graph 1 (Most / Mask)', 'Graph 2 (Least / Core)', 'Graph 3 (Change / Mirror)', 'Normalized G1', 'Normalized G2', 'Normalized G3']);

            $raw = $session->raw_scores_json ?? [];
            $norm = $session->normalized_scores_json ?? [];

            foreach (['D', 'I', 'S', 'C'] as $dim) {
                fputcsv($handle, [
                    $dim,
                    $raw['graph1_most'][$dim] ?? 0,
                    $raw['graph2_least'][$dim] ?? 0,
                    $raw['graph3_change'][$dim] ?? 0,
                    $norm['graph1'][$dim] ?? 0,
                    $norm['graph2'][$dim] ?? 0,
                    $norm['graph3'][$dim] ?? 0,
                ]);
            }
            fputcsv($handle, ['* (Neutral)', $raw['graph1_most']['*'] ?? 0, $raw['graph2_least']['*'] ?? 0, '-', '-', '-', '-']);
            fputcsv($handle, []);

            // Interpretations
            $interp = $session->interpretation_json ?? [];
            fputcsv($handle, ['DESKRIPSI PROFIL', $interp['description'] ?? '-']);
            fputcsv($handle, ['KEKUATAN UTAMA', $interp['strengths'] ?? '-']);
            fputcsv($handle, ['POTENSI KETERBATASAN', $interp['potential_limitations'] ?? '-']);
            fputcsv($handle, ['GAYA KOMUNIKASI', $interp['communication_style'] ?? '-']);
            fputcsv($handle, ['GAYA KERJA', $interp['work_style'] ?? '-']);
            fputcsv($handle, ['SARAN PENGEMBANGAN', $interp['development_suggestions'] ?? '-']);
            fputcsv($handle, []);

            // Detailed answers audit
            fputcsv($handle, ['RINCIAN AUDIT JAWABAN PESERTA']);
            fputcsv($handle, ['No', 'Pilihan Most (Paling)', 'Kunci Most', 'Pilihan Least (Tidak Paling)', 'Kunci Least']);

            $answers = $session->answers()->with(['question', 'mostOption', 'leastOption'])->get()->sortBy('question.question_number');
            foreach ($answers as $ans) {
                fputcsv($handle, [
                    $ans->question->question_number ?? '-',
                    '[' . ($ans->mostOption->option_label ?? '-') . '] ' . ($ans->mostOption->statement ?? '-'),
                    $ans->mostOption->most_mapping ?? '-',
                    '[' . ($ans->leastOption->option_label ?? '-') . '] ' . ($ans->leastOption->statement ?? '-'),
                    $ans->leastOption->least_mapping ?? '-',
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }
}
