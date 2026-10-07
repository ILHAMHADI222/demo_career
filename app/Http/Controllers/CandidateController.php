<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\CandidateDocument;
use App\Models\CandidateEducation;
use App\Models\CandidateExperience;
use App\Models\DocumentType;
use App\Models\Job;
use App\Services\ApplicationService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;
use RuntimeException;

class CandidateController extends Controller
{
    public function profile(): View
    {
        $profile = auth()->user()->candidateProfile()->with(['documents.documentType', 'educations', 'experiences'])->firstOrFail();
        $documentTypes = DocumentType::query()->orderByDesc('is_required')->orderBy('name')->get();
        $uploadedRequiredDocumentTypeIds = $profile->documents
            ->whereIn('status', ['PENDING', 'VERIFIED'])
            ->pluck('document_type_id')
            ->unique();
        $requiredDocumentTypes = $documentTypes->where('is_required', true);
        $missingRequiredDocumentTypes = $requiredDocumentTypes->reject(fn (DocumentType $type) => $uploadedRequiredDocumentTypeIds->contains($type->id));
        $documentsComplete = $missingRequiredDocumentTypes->isEmpty();

        return view('candidate.profile', compact('profile', 'documentTypes', 'requiredDocumentTypes', 'missingRequiredDocumentTypes', 'documentsComplete'));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $profile = auth()->user()->candidateProfile()->firstOrFail();
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'string', 'max:32'],
            'birth_place' => ['required', 'string', 'max:100'],
            'birth_date' => ['required', 'date'],
            'gender' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            'marital_status' => ['required', Rule::in(['Belum Menikah', 'Menikah', 'Cerai Hidup', 'Cerai Mati'])],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'whatsapp' => ['required', 'string', 'max:30'],
            'address' => ['required', 'string'],
            'rt_rw' => ['required', 'string', 'max:50'],
            'village' => ['required', 'string', 'max:100'],
            'district' => ['required', 'string', 'max:100'],
            'province' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'driving_license' => ['nullable', 'string', 'max:50'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'portfolio_url' => ['nullable', 'url', 'max:255'],
            'education_level' => ['required', 'string', 'max:100'],
            'institution' => ['required', 'string', 'max:255'],
            'major' => ['required', 'string', 'max:255'],
            'ipk' => ['required', 'string', 'max:20'],
            'certification' => ['nullable', 'string'],
            'experiences' => ['nullable', 'array'],
            'experiences.*.company_name' => ['nullable', 'string', 'max:255'],
            'experiences.*.position' => ['nullable', 'string', 'max:255'],
            'experiences.*.work_years' => ['nullable', 'string', 'max:50'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'photo_capture' => ['nullable', 'string'],
        ]);

        if (! $profile->photo && ! $request->hasFile('photo') && ! filled($data['photo_capture'] ?? null)) {
            return back()->withErrors(['photo' => 'Pass foto wajib diisi.'])->withInput();
        }

        if (filled($data['photo_capture'] ?? null)) {
            $photoPath = $this->storeCapturedPhoto($data['photo_capture'], $profile->id);
            if ($profile->photo) {
                Storage::disk('public')->delete($profile->photo);
            }
            $data['photo'] = $photoPath;
        }

        if ($request->hasFile('photo')) {
            if ($profile->photo) {
                Storage::disk('public')->delete($profile->photo);
            }
            $data['photo'] = $request->file('photo')->store('candidate-photos/'.$profile->id, 'public');
        } elseif (! filled($data['photo_capture'] ?? null)) {
            // Jangan timpa foto yang sudah ada dengan null jika user tidak mengunggah foto baru
            unset($data['photo']);
        }

        unset($data['photo_capture']);

        // Uppercase text values (except email, URLs, etc.)
        $uppercaseFields = [
            'full_name', 'nik', 'birth_place', 'address', 'rt_rw', 'village',
            'district', 'province', 'city', 'postal_code', 'driving_license'
        ];
        foreach ($uppercaseFields as $field) {
            if (! empty($data[$field]) && is_string($data[$field])) {
                $data[$field] = mb_strtoupper(trim($data[$field]), 'UTF-8');
            }
        }

        $educationData = collect($data)->only(['education_level', 'institution', 'major', 'ipk', 'certification'])->all();
        foreach (['institution', 'major', 'ipk', 'certification'] as $f) {
            if (! empty($educationData[$f]) && is_string($educationData[$f])) {
                $educationData[$f] = mb_strtoupper(trim($educationData[$f]), 'UTF-8');
            }
        }

        $profileData = collect($data)->except(['education_level', 'institution', 'major', 'ipk', 'certification', 'experiences'])->all();

        $profile->update($profileData);
        $profile->educations()->updateOrCreate([], $educationData);

        // Process multiple experiences
        $profile->experiences()->delete();
        $experiencesInput = $request->input('experiences', []);
        if (is_array($experiencesInput)) {
            foreach ($experiencesInput as $exp) {
                $company = trim($exp['company_name'] ?? '');
                $pos = trim($exp['position'] ?? '');
                $years = trim($exp['work_years'] ?? '');
                if ($company !== '' || $pos !== '' || $years !== '') {
                    $profile->experiences()->create([
                        'company_name' => mb_strtoupper($company, 'UTF-8'),
                        'position' => mb_strtoupper($pos, 'UTF-8'),
                        'work_years' => mb_strtoupper($years, 'UTF-8'),
                    ]);
                }
            }
        }

        $profile->recalculateCompletion();

        if ($profile->user && $profile->user->email !== $data['email']) {
            $profile->user->forceFill(['email' => $data['email']])->save();
        }

        return back()->with('success', 'Profil kandidat berhasil diperbarui.');
    }

    public function uploadDocument(Request $request): RedirectResponse
    {
        $profile = auth()->user()->candidateProfile()->firstOrFail();
        $data = $request->validate([
            'document_type_id' => ['required', 'exists:document_types,id'],
            'document' => ['required', 'file', 'mimes:pdf', 'max:5120'],
        ]);

        $file = $request->file('document');
        $path = $file->store('candidate-documents/'.$profile->id, 'public');

        CandidateDocument::query()->create([
            'candidate_profile_id' => $profile->id,
            'document_type_id' => $data['document_type_id'],
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'mime_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'status' => 'PENDING',
        ]);

        $profile->recalculateCompletion();

        return back()->with('success', 'Dokumen berhasil diunggah.');
    }

    public function viewDocument(CandidateDocument $document): StreamedResponse
    {
        abort_unless($document->candidateProfile?->user_id === auth()->id() || auth()->user()->hasAnyRole(['SUPERADMIN', 'ADMIN']), 403);
        abort_unless(Storage::disk('public')->exists($document->file_path), 404);

        return Storage::disk('public')->response($document->file_path, $document->file_name, [
            'Content-Type' => $document->mime_type ?: 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$document->file_name.'"',
        ]);
    }

    public function deleteDocument(CandidateDocument $document): RedirectResponse
    {
        abort_unless($document->candidateProfile?->user_id === auth()->id(), 403);
        Storage::disk('public')->delete($document->file_path);
        $document->delete();
        $document->candidateProfile->recalculateCompletion();

        return back()->with('success', 'Dokumen berhasil dihapus.');
    }

    public function apply(Job $job, ApplicationService $service): RedirectResponse
    {
        $profile = auth()->user()->candidateProfile()->with('documents')->firstOrFail();

        try {
            $application = $service->draft($job, $profile);
            $service->submit($application);
        } catch (RuntimeException $exception) {
            return redirect()->route('candidate.profile')->withErrors(['apply' => $exception->getMessage()]);
        }

        return redirect()->route('dashboard')->with('success', 'Lamaran berhasil dikirim ke HRD.');
    }

    private function storeCapturedPhoto(string $dataUri, int $profileId): string
    {
        if (! str_starts_with($dataUri, 'data:image/')) {
            throw new RuntimeException('Format pass foto dari kamera tidak valid.');
        }

        [$meta, $encodedContent] = explode(',', $dataUri, 2);
        $extension = str_contains($meta, 'image/png') ? 'png' : (str_contains($meta, 'image/webp') ? 'webp' : 'jpg');
        $binaryContent = base64_decode($encodedContent, true);

        if ($binaryContent === false) {
            throw new RuntimeException('Pass foto dari kamera tidak dapat diproses.');
        }

        $path = 'candidate-photos/'.$profileId.'/captured-'.now()->timestamp.'.'.$extension;
        Storage::disk('public')->put($path, $binaryContent);

        return $path;
    }
}
