<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['candidate_profile_id', 'document_type_id', 'file_name', 'file_path', 'mime_type', 'file_size', 'status', 'verification_note', 'verified_by', 'verified_at'])]
class CandidateDocument extends Model
{
    use SoftDeletes;

    protected function casts(): array
    {
        return ['verified_at' => 'datetime'];
    }

    public function candidateProfile(): BelongsTo { return $this->belongsTo(CandidateProfile::class); }
    public function documentType(): BelongsTo { return $this->belongsTo(DocumentType::class); }
    public function verifier(): BelongsTo { return $this->belongsTo(User::class, 'verified_by'); }
}
