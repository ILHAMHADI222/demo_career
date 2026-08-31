<x-layouts.app :title="'Tahap 2: Analogi Verbal - ' . $assessment->title">
    <section class="min-h-screen bg-[#f0efe9] px-4 pb-12 pt-6 sm:pt-8 text-[#322d2a] sm:px-6 lg:px-8">
        <div class="mx-auto max-w-4xl">

            @if(session('stage_success'))
                <div class="mb-5 rounded-[20px] border border-emerald-200 bg-emerald-50 p-4 text-xs text-emerald-800 flex items-center gap-2 shadow-xs">
                    <span class="font-bold">✓</span>
                    <span>{{ session('stage_success') }}</span>
                </div>
            @endif

            {{-- HEADER BAR: STAGE INFO & TIMER --}}
            <div class="sticky top-20 z-30 mb-5 overflow-hidden rounded-[24px] border border-white bg-white/95 p-4 shadow-md backdrop-blur">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-600 text-xs font-bold text-white shadow-xs">2</span>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-600">Sub-Tes 2 dari 3</span>
                                <span class="text-[10px] text-[#6f6b67]">• 50 Soal Analogi Kata</span>
                            </div>
                            <h2 class="text-sm font-semibold text-[#322d2a]">ANALOGI VERBAL (KORELASI MAKNA KATA)</h2>
                        </div>
                    </div>

                    {{-- TIMER --}}
                    <div class="flex items-center gap-3">
                        <div id="timerContainer" class="flex items-center gap-2 rounded-full border border-rose-200 bg-rose-50 px-3.5 py-1.5 text-xs font-bold text-rose-700">
                            <span>⏱ Sisa Waktu:</span>
                            <span id="timerDisplay" class="font-mono text-sm">--:--</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- INSTRUCTION BANNER --}}
            <div class="mb-5 rounded-[20px] border border-emerald-200 bg-emerald-50/80 p-4 text-xs text-emerald-950">
                <div class="flex items-start gap-2.5">
                    <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-600 text-[10px] font-bold text-white">ℹ</span>
                    <div>
                        <p class="font-bold text-emerald-950">Petunjuk Pengerjaan Sub-Tes 2 (Analogi Verbal):</p>
                        <p class="mt-1 text-[11px] leading-relaxed text-emerald-900">
                            Pilihlah <strong>satu kata dari opsi A, B, C, atau D</strong> yang memiliki hubungan / korelasi analogi makna yang paling setara untuk melengkapi pasangan kata yang rumpang.
                        </p>
                    </div>
                </div>
            </div>

            {{-- FORM SOAL ANALOGI VERBAL --}}
            <form id="stage2Form" method="POST" action="{{ route('disc.assessment.stage2.submit', $session->session_token) }}">
                @csrf

                <div class="space-y-4">
                    @foreach($questions as $index => $q)
                        <div class="rounded-[24px] border border-white bg-white p-5 shadow-sm transition hover:border-emerald-300">
                            <div class="flex items-center justify-between border-b border-[#322d2a]/10 pb-2.5">
                                <div class="flex items-center gap-2">
                                    <span class="rounded-full bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 text-[11px] font-bold text-emerald-700">
                                        Soal No. {{ $index + 1 }} dari {{ count($questions) }}
                                    </span>
                                    <span class="text-[11px] text-[#6f6b67]">Pilihlah kata yang tepat:</span>
                                </div>
                            </div>

                            <div class="mt-3.5 rounded-[16px] border border-emerald-100 bg-[#f8fafc] p-4 text-center sm:text-left">
                                <p class="text-[11px] uppercase tracking-wider font-semibold text-[#6f6b67] mb-1">Pertanyaan Analogi:</p>
                                <p class="text-base sm:text-lg font-bold text-[#1e293b]">
                                    {{ $q->question_text }}
                                </p>
                            </div>

                            <div class="mt-4 grid gap-2.5 sm:grid-cols-2">
                                @foreach($q->options_json as $key => $optText)
                                    <label class="flex items-center gap-3 rounded-[16px] border border-[#322d2a]/15 bg-[#fbfaf8] p-3.5 text-xs cursor-pointer transition hover:border-emerald-500 hover:bg-emerald-50/50 has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50 has-[:checked]:ring-1 has-[:checked]:ring-emerald-500">
                                        <input
                                            type="radio"
                                            name="answers[{{ $q->id }}]"
                                            value="{{ $key }}"
                                            @checked(($savedAnswers[$q->id] ?? '') === $key)
                                            required
                                            class="h-4 w-4 text-emerald-600 focus:ring-emerald-500"
                                        >
                                        <span class="inline-flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-white text-xs font-bold text-[#322d2a] shadow-xs border border-[#322d2a]/15">{{ $key }}</span>
                                        <span class="text-sm font-medium text-[#1e293b]">{{ $optText }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- SUBMIT BAR --}}
                <div class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-3 rounded-[24px] border border-white bg-white p-5 shadow-sm">
                    <p class="text-xs text-[#6f6b67]">
                        Pastikan seluruh soal telah dijawab sebelum lanjut ke tes kepribadian.
                    </p>
                    <button type="button" onclick="openStage2Modal()" class="inline-flex items-center gap-2 rounded-full bg-emerald-600 px-6 py-3 text-xs font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                        <span>Selesai Tahap 2 & Lanjut ke Tahap 3</span>
                        <span>→</span>
                    </button>
                </div>
            </form>

        </div>
    </section>

    {{-- MODAL KONFIRMASI TAHAP 2 --}}
    <div id="stage2Modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 p-4 backdrop-blur-sm">
        <div class="w-full max-w-md rounded-[32px] bg-white p-6 sm:p-8 shadow-2xl text-center">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-emerald-100 text-2xl text-emerald-600 font-bold">
                2
            </div>
            <h3 class="mt-4 text-lg font-bold text-[#322d2a]">Lanjut ke Tahap 3 (Tes Kepribadian)?</h3>
            <p class="mt-2 text-xs text-[#6f6b67] leading-relaxed">
                Apakah Anda yakin ingin menyelesaikan <strong>Sub-Tes 2 (Analogi Verbal)</strong> dan melanjutkan ke <strong>Sub-Tes 3 (Tes Kepribadian)</strong>?
            </p>
            <p class="mt-2 text-[11px] text-amber-700 bg-amber-50 rounded-xl p-2.5 border border-amber-200">
                ⚠️ Setelah berpindah ke Tahap 3, jawaban Sub-Tes 1 dan 2 akan dikunci secara permanen.
            </p>

            <div class="mt-6 flex items-center justify-center gap-3">
                <button type="button" onclick="closeStage2Modal()" class="rounded-full bg-[#f0efe9] px-5 py-2.5 text-xs font-semibold text-[#6f6b67] hover:text-[#322d2a]">
                    Batal / Cek Lagi
                </button>
                <button type="button" onclick="submitStage2Form()" class="rounded-full bg-emerald-600 px-6 py-2.5 text-xs font-semibold text-white shadow-md hover:bg-emerald-700">
                    Ya, Lanjut ke Tahap 3 →
                </button>
            </div>
        </div>
    </div>

    <script>
        const targetEndTime = {{ $targetTimestampMs }};
        const timerDisplay = document.getElementById('timerDisplay');
        const timerContainer = document.getElementById('timerContainer');
        const form = document.getElementById('stage2Form');
        let autoSubmitted = false;

        function openStage2Modal() {
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            document.getElementById('stage2Modal').classList.remove('hidden');
            document.getElementById('stage2Modal').classList.add('flex');
        }

        function closeStage2Modal() {
            document.getElementById('stage2Modal').classList.add('hidden');
            document.getElementById('stage2Modal').classList.remove('flex');
        }

        function submitStage2Form() {
            closeStage2Modal();
            form.submit();
        }

        function updateTimer() {
            const now = Date.now();
            const diffSeconds = Math.max(0, Math.floor((targetEndTime - now) / 1000));

            if (diffSeconds <= 0) {
                timerDisplay.textContent = '00:00';
                if (!autoSubmitted) {
                    autoSubmitted = true;
                    alert('Waktu Sub-Tes 2 (Analogi Verbal) telah habis! Jawaban Anda akan disimpan dan berpindah ke Sub-Tes 3.');
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
