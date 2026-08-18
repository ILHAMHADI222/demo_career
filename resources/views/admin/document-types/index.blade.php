<x-layouts.app title="Pengaturan Berkas Wajib - Career Indodata">
    <section class="min-h-screen bg-slate-50 py-10 md:py-14">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-[2rem] bg-slate-950 p-8 text-white shadow-2xl md:p-10">
                <p class="text-xs font-black uppercase tracking-[0.22em] text-orange-300">Superadmin & HRD Control</p>
                <h1 class="mt-4 text-4xl font-black tracking-tight md:text-5xl">Atur berkas yang wajib dikirim kandidat.</h1>
                <p class="mt-4 max-w-3xl text-sm leading-7 text-slate-200">Aktifkan tombol wajib untuk CV, KTP, ijazah, sertifikat, atau dokumen lain. Kandidat tidak bisa mengirim lamaran sebelum semua berkas wajib diunggah.</p>
            </div>

            <div class="mt-8 grid gap-6 lg:grid-cols-[0.8fr_1.2fr]">
                <form method="POST" action="{{ route('admin.document-types.store') }}" class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm md:p-8">
                    @csrf
                    <p class="text-xs font-black uppercase tracking-[0.18em] text-orange-500">Tambah Berkas</p>
                    <h2 class="mt-2 text-2xl font-black text-slate-950">Jenis dokumen baru</h2>
                    <div class="mt-6 space-y-4">
                        <input name="name" value="{{ old('name') }}" placeholder="Contoh: Surat Sehat" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100" required>
                        <label class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                            <span class="text-sm font-black text-slate-700">Jadikan wajib?</span>
                            <input type="checkbox" name="is_required" value="1" class="h-5 w-5 rounded border-slate-300 text-orange-500 focus:ring-orange-500">
                        </label>
                        <button class="w-full rounded-2xl bg-orange-500 px-5 py-3 text-sm font-black text-white shadow-lg shadow-orange-500/20 transition hover:bg-orange-600">Tambah Jenis Berkas</button>
                    </div>
                </form>

                <div class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm md:p-8">
                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.18em] text-orange-500">Daftar Berkas</p>
                        <h2 class="mt-2 text-2xl font-black text-slate-950">Aktif/nonaktifkan status wajib</h2>
                    </div>

                    <div class="mt-6 space-y-3">
                        @foreach($documentTypes as $type)
                            <form method="POST" action="{{ route('admin.document-types.update', $type) }}" class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                @csrf
                                @method('PUT')
                                <div class="grid gap-3 md:grid-cols-[1fr_0.8fr_auto] md:items-center">
                                    <div>
                                        <label class="text-xs font-black uppercase tracking-[0.16em] text-slate-500">Nama Berkas</label>
                                        <input name="name" value="{{ $type->name }}" class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold" required>
                                    </div>
                                    <div>
                                        <label class="text-xs font-black uppercase tracking-[0.16em] text-slate-500">Kode</label>
                                        <input name="code" value="{{ $type->code }}" class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold" required>
                                    </div>
                                    <label class="flex items-center justify-between gap-3 rounded-2xl bg-white px-4 py-3 ring-1 ring-slate-200 md:mt-6">
                                        <span class="text-sm font-black {{ $type->is_required ? 'text-orange-600' : 'text-slate-500' }}">{{ $type->is_required ? 'Wajib aktif' : 'Opsional' }}</span>
                                        <input type="checkbox" name="is_required" value="1" @checked($type->is_required) class="h-5 w-5 rounded border-slate-300 text-orange-500 focus:ring-orange-500">
                                    </label>
                                </div>
                                <button class="mt-3 rounded-xl bg-slate-950 px-4 py-2 text-xs font-black text-white transition hover:bg-orange-500">Simpan Perubahan</button>
                            </form>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
