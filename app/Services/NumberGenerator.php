<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class NumberGenerator
{
    public function candidateNumber(): string
    {
        return $this->generate('candidate_profiles', 'candidate_number', 'CAN');
    }

    public function applicationNumber(): string
    {
        return $this->generate('applications', 'application_number', 'APP');
    }

    public function jobCode(): string
    {
        return $this->generate('jobs', 'job_code', 'JOB');
    }

    private function generate(string $table, string $column, string $prefix): string
    {
        $year = now()->format('Y');
        $latest = DB::table($table)
            ->where($column, 'like', "{$prefix}-{$year}-%")
            ->lockForUpdate()
            ->max($column);

        $sequence = $latest ? ((int) substr($latest, -6)) + 1 : 1;

        return sprintf('%s-%s-%06d', $prefix, $year, $sequence);
    }
}
