<?php

namespace App\Http\Controllers;

use App\Models\DocumentType;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminDocumentTypeController extends Controller
{
    public function index(): View
    {
        return view('admin.document-types.index', [
            'documentTypes' => DocumentType::query()->orderByDesc('is_required')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'is_required' => ['nullable', 'boolean'],
        ]);

        DocumentType::query()->create([
            'code' => Str::upper(Str::slug($data['name'], '_')),
            'name' => $data['name'],
            'is_required' => $request->boolean('is_required'),
        ]);

        return back()->with('success', 'Jenis berkas berhasil ditambahkan.');
    }

    public function update(Request $request, DocumentType $documentType): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:100', Rule::unique('document_types', 'code')->ignore($documentType->id)],
            'is_required' => ['nullable', 'boolean'],
        ]);

        $documentType->update([
            'name' => $data['name'],
            'code' => Str::upper(Str::slug($data['code'], '_')),
            'is_required' => $request->boolean('is_required'),
        ]);

        return back()->with('success', 'Status wajib berkas berhasil diperbarui.');
    }
}
