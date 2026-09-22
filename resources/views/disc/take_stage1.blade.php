<x-layouts.app :title="'Tahap 1: Deret Hitung - ' . $assessment->title">
    <section class="min-h-screen bg-[#f0efe9] px-4 pb-12 pt-6 sm:pt-8 text-[#322d2a] sm:px-6 lg:px-8">
        <div class="mx-auto max-w-4xl">

            {{-- HEADER BAR: STAGE INFO, AUTOSAVE STATUS & TIMER --}}
            <div class="sticky top-20 z-30 mb-5 overflow-hidden rounded-[24px] border border-white bg-white/95 p-4 shadow-md backdrop-blur">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-600 text-xs font-bold text-white shadow-xs">1</span>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-blue-600">Sub-Tes 1 dari 3</span>
                                <span class="text-[10px] text-[#6f6b67]">• 25 Soal Deret Angka (Opsional / Bisa Di-skip)</span>
                            </div>
                            <h2 class="text-sm font-semibold text-[#322d2a]">DERET HITUNG (POLA BILANGAN)</h2>
                        </div>
                    </div>

                    {{-- AUTOSAVE BADGE & TIMER --}}
                    <div class="flex flex-wrap items-center gap-3">
                        <div id="saveStatusIndicator" class="hidden items-center gap-1.5 rounded-full bg-emerald-50 border border-emerald-200 px-3 py-1 text-[11px] font-medium text-emerald-700 transition">
                            <span class="inline-block h-2 w-2 rounded-full bg-emerald-500"></span>
                            <span id="saveStatusText">Tersimpan otomatis</span>
                        </div>

                        <div id="timerContainer" class="flex items-center gap-2 rounded-full border border-rose-200 bg-rose-50 px-3.5 py-1.5 text-xs font-bold text-rose-700 shadow-xs">
                            <span>⏱ Sisa Waktu:</span>
                            <span id="timerDisplay" class="font-mono text-sm">--:--</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- INSTRUCTION BANNER --}}
            <div class="mb-5 rounded-[20px] border border-blue-200 bg-blue-50/80 p-4 text-xs text-blue-950">
                <div class="flex items-start gap-2.5">
                    <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-blue-600 text-[10px] font-bold text-white">ℹ</span>
                    <div>
                        <p class="font-bold text-blue-950">Petunjuk Pengerjaan Sub-Tes 1 (Deret Hitung):</p>
                        <p class="mt-1 text-[11px] leading-relaxed text-blue-900">
                            Ketikkan <strong>2 angka kelanjutan pola berikutnya</strong> pada kotak isian Angka Ke-1 dan Angka Ke-2. 
                            <strong>Jawaban tidak wajib diisi semua (bisa dilewati / di-skip)</strong>. Saat waktu habis atau tombol selesai ditekan, jawaban terakhir yang Anda isi akan tersimpan otomatis dan dinilai langsung.
                        </p>
                    </div>
                </div>
            </div>

            {{-- FORM SOAL DERET HITUNG --}}
            <form id="stage1Form" method="POST" action="{{ route('disc.assessment.stage1.submit', $session->session_token) }}">
                @csrf

                <div class="space-y-4">
                    @foreach($questions as $index => $q)
                        <div class="question-card rounded-[24px] border border-white bg-white p-5 shadow-sm transition hover:border-blue-300" data-question-id="{{ $q->id }}">
                            <div class="flex items-center justify-between border-b border-[#322d2a]/10 pb-2.5">
                                <div class="flex items-center gap-2">
                                    <span class="rounded-full bg-blue-50 border border-blue-200 px-2.5 py-0.5 text-[11px] font-bold text-blue-700">
                                        Soal No. {{ $index + 1 }} dari {{ count($questions) }}
                                    </span>
                                    <span class="text-[11px] text-[#6f6b67]">Lanjutkan pola bilangan:</span>
                                </div>
                                <span class="skip-badge text-[10px] font-medium text-slate-400 bg-slate-100 px-2 py-0.5 rounded-md">
                                    {{ !empty($savedAnswers[$q->id]['ans_1']) || !empty($savedAnswers[$q->id]['ans_2']) ? 'Terisi' : 'Belum diisi' }}
                                </span>
                            </div>

                            <div class="mt-3.5 rounded-[16px] border border-blue-100 bg-[#f8fafc] p-4 text-center sm:text-left">
                                <p class="text-[11px] uppercase tracking-wider font-semibold text-[#6f6b67] mb-1">Barisan Pola Deret:</p>
                                <p class="font-mono text-lg sm:text-xl font-bold tracking-wider text-[#1e293b]">
                                    {{ $q->question_text }}
                                </p>
                            </div>

                            <div class="mt-4 grid gap-3 sm:grid-cols-2">
                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-wider text-blue-950">
                                        Jawaban Angka Ke-1 <span class="text-[10px] font-normal text-slate-400">(Opsional)</span>
                                    </label>
                                    <input
                                        type="text"
                                        name="answers[{{ $q->id }}][ans_1]"
                                        value="{{ $savedAnswers[$q->id]['ans_1'] ?? '' }}"
                                        placeholder="Ketik angka ke-1..."
                                        class="stage-input mt-1.5 w-full rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] px-3.5 py-2.5 font-mono text-sm font-bold text-[#322d2a] outline-none transition focus:border-blue-500 focus:bg-white"
                                    >
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-wider text-blue-950">
                                        Jawaban Angka Ke-2 <span class="text-[10px] font-normal text-slate-400">(Opsional)</span>
                                    </label>
                                    <input
                                        type="text"
                                        name="answers[{{ $q->id }}][ans_2]"
                                        value="{{ $savedAnswers[$q->id]['ans_2'] ?? '' }}"
                                        placeholder="Ketik angka ke-2..."
                                        class="stage-input mt-1.5 w-full rounded-[14px] border border-[#322d2a]/15 bg-[#f0efe9] px-3.5 py-2.5 font-mono text-sm font-bold text-[#322d2a] outline-none transition focus:border-blue-500 focus:bg-white"
                                    >
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- SUBMIT BAR --}}
                <div class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-3 rounded-[24px] border border-white bg-white p-5 shadow-sm">
                    <p class="text-xs text-[#6f6b67]">
                        Soal yang dikosongkan akan dilewati (skip). Jawaban terakhir akan disimpan otomatis.
                    </p>
                    <button type="button" onclick="openStage1Modal()" class="inline-flex items-center gap-2 rounded-full bg-blue-600 px-6 py-3 text-xs font-semibold text-white shadow-sm transition hover:bg-blue-700">
                        <span>Selesai Tahap 1 & Lanjut ke Tahap 2</span>
                        <span>→</span>
                    </button>
                </div>
            </form>

        </div>
    </section>

    {{-- MODAL KONFIRMASI TAHAP 1 --}}
    <div id="stage1Modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 p-4 backdrop-blur-sm">
        <div class="w-full max-w-md rounded-[32px] bg-white p-6 sm:p-8 shadow-2xl text-center">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-blue-100 text-2xl text-blue-600 font-bold">
                1
            </div>
            <h3 class="mt-4 text-lg font-bold text-[#322d2a]">Lanjut ke Tahap 2 (Analogi Verbal)?</h3>
            <p class="mt-2 text-xs text-[#6f6b67] leading-relaxed">
                Apakah Anda yakin ingin menyelesaikan <strong>Sub-Tes 1 (Deret Hitung)</strong> dan melanjutkan ke <strong>Sub-Tes 2 (Analogi Verbal)</strong>?
            </p>
            <div id="modalSummary" class="mt-3 rounded-2xl bg-blue-50/70 border border-blue-100 p-3 text-xs text-blue-950 font-medium">
                Memeriksa jawaban...
            </div>
            <p class="mt-2 text-[11px] text-amber-700 bg-amber-50 rounded-xl p-2.5 border border-amber-200">
                ⚠️ Setelah berpindah ke Tahap 2, nilai Deret Hitung langsung tercatat dan Anda tidak dapat mengubahnya lagi.
            </p>

            <div class="mt-6 flex items-center justify-center gap-3">
                <button type="button" onclick="closeStage1Modal()" class="rounded-full bg-[#f0efe9] px-5 py-2.5 text-xs font-semibold text-[#6f6b67] hover:text-[#322d2a]">
                    Batal / Lanjutkan Mengerjakan
                </button>
                <button type="button" onclick="submitStage1Form()" class="rounded-full bg-blue-600 px-6 py-2.5 text-xs font-semibold text-white shadow-md hover:bg-blue-700">
                    Ya, Simpan & Lanjut ke Tahap 2 →
                </button>
            </div>
        </div>
    </div>

    <script>
        const targetEndTime = {{ $targetTimestampMs }};
        const timerDisplay = document.getElementById('timerDisplay');
        const timerContainer = document.getElementById('timerContainer');
        const form = document.getElementById('stage1Form');
        const draftUrl = "{{ route('disc.assessment.stage1.draft', $session->session_token) }}";
        const csrfToken = "{{ csrf_token() }}";
        let autoSubmitted = false;
        let saveTimeout = null;

        // Debounced Auto-save Draft Function
        function triggerAutoSave() {
            const saveIndicator = document.getElementById('saveStatusIndicator');
            const saveText = document.getElementById('saveStatusText');
            if (saveIndicator) {
                saveIndicator.classList.remove('hidden');
                saveIndicator.classList.add('flex');
                if (saveText) saveText.textContent = 'Menyimpan...';
            }

            clearTimeout(saveTimeout);
            saveTimeout = setTimeout(() => {
                const formData = new FormData(form);
                fetch(draftUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (saveText) saveText.textContent = 'Tersimpan otomatis ✓';
                })
                .catch(() => {
                    if (saveText) saveText.textContent = 'Disimpan lokal';
                });
            }, 800);
        }

        // Attach input listeners for live updates
        document.querySelectorAll('.stage-input').forEach(input => {
            input.addEventListener('input', function() {
                triggerAutoSave();
                updateCardStatus(this.closest('.question-card'));
            });
        });

        function updateCardStatus(card) {
            if (!card) return;
            const inputs = card.querySelectorAll('.stage-input');
            let hasValue = false;
            inputs.forEach(inp => {
                if (inp.value.trim() !== '') hasValue = true;
            });
            const badge = card.querySelector('.skip-badge');
            if (badge) {
                if (hasValue) {
                    badge.textContent = 'Terisi ✓';
                    badge.className = 'skip-badge text-[10px] font-medium text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-md';
                } else {
                    badge.textContent = 'Belum diisi';
                    badge.className = 'skip-badge text-[10px] font-medium text-slate-400 bg-slate-100 px-2 py-0.5 rounded-md';
                }
            }
        }

        function countAnswered() {
            const cards = document.querySelectorAll('.question-card');
            let answered = 0;
            cards.forEach(card => {
                const inputs = card.querySelectorAll('.stage-input');
                let hasVal = false;
                inputs.forEach(inp => {
                    if (inp.value.trim() !== '') hasVal = true;
                });
                if (hasVal) answered++;
            });
            return { answered, total: cards.length };
        }

        function openStage1Modal() {
            const stats = countAnswered();
            const modalSummary = document.getElementById('modalSummary');
            if (modalSummary) {
                const skipped = stats.total - stats.answered;
                modalSummary.innerHTML = `<strong>${stats.answered} dari ${stats.total} Soal</strong> telah terisi` + 
                    (skipped > 0 ? ` (${skipped} soal dilewati / skip).` : ` (Semua soal terisi).`);
            }
            document.getElementById('stage1Modal').classList.remove('hidden');
            document.getElementById('stage1Modal').classList.add('flex');
        }

        function closeStage1Modal() {
            document.getElementById('stage1Modal').classList.add('hidden');
            document.getElementById('stage1Modal').classList.remove('flex');
        }

        function submitStage1Form() {
            closeStage1Modal();
            form.submit();
        }

        function updateTimer() {
            const now = Date.now();
            const diffSeconds = Math.max(0, Math.floor((targetEndTime - now) / 1000));

            if (diffSeconds <= 0) {
                timerDisplay.textContent = '00:00';
                if (!autoSubmitted) {
                    autoSubmitted = true;
                    // Auto submit immediately without browser alerts
                    form.submit();
                }
                return;
            }

            const minutes = Math.floor(diffSeconds / 60);
            const seconds = diffSeconds % 60;
            timerDisplay.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;

            if (diffSeconds <= 60) {
                timerContainer.classList.add('animate-pulse');
            } else {
                timerContainer.classList.remove('animate-pulse');
            }
        }

        updateTimer();
        const timerInterval = setInterval(updateTimer, 500);
    </script>
</x-layouts.app>
