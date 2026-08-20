<!DOCTYPE html>
<html lang="id" class="select-none">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Asesmen DISC - {{ $assessment->title }}</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    @endif
    <style>
        /* Anti-Copy and Content Protection */
        body, .assessment-secure-container {
            -webkit-user-select: none !important;
            -moz-user-select: none !important;
            -ms-user-select: none !important;
            user-select: none !important;
            -webkit-touch-callout: none !important;
        }

        /* Moving Dynamic Watermark */
        .watermark-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            pointer-events: none;
            z-index: 9999;
            opacity: 0.14;
            display: flex;
            flex-wrap: wrap;
            align-content: space-around;
            justify-content: space-around;
            transform: rotate(-20deg) scale(1.2);
            overflow: hidden;
            font-family: monospace;
            font-weight: 700;
            font-size: 13px;
            color: #0f0e12;
            transition: transform 10s ease-in-out;
        }
    </style>
</head>
<body class="min-h-screen bg-[#f0efe9] font-sans text-slate-900 antialiased" oncontextmenu="return false;">

    {{-- DYNAMIC WATERMARK OVERLAY --}}
    <div id="watermarkOverlay" class="watermark-overlay">
        @for ($i = 0; $i < 20; $i++)
            <div class="p-8 text-center leading-tight">
                <div>{{ strtoupper(auth()->user()->name) }}</div>
                <div>{{ auth()->user()->candidateProfile->candidate_number ?? 'ID-'.auth()->id() }}</div>
                <div>{{ $session->session_token }}</div>
                <div class="watermark-time">{{ now()->format('Y-m-d H:i') }}</div>
            </div>
        @endfor
    </div>

    {{-- HEADER BAR --}}
    <header class="sticky top-0 z-40 border-b border-white/70 bg-white/90 shadow-sm backdrop-blur-md">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-3 sm:px-6">
            <div class="flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-[#322d2a] font-mono text-sm font-bold text-white shadow-sm">
                    DISC
                </span>
                <div>
                    <h1 class="text-sm font-bold text-[#322d2a] sm:text-base">{{ $assessment->title }}</h1>
                    <p class="text-xs text-slate-500">{{ auth()->user()->name }} • Attempt #{{ $session->attempt_number }}</p>
                </div>
            </div>

            <div class="flex items-center gap-4">
                {{-- AUTOSAVE STATUS BADGE --}}
                <div id="saveStatus" class="hidden items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 sm:flex">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                    <span>Tersimpan</span>
                </div>

                {{-- COUNTDOWN TIMER --}}
                <div class="flex items-center gap-2 rounded-2xl bg-[#322d2a] px-4 py-2 text-white shadow-md">
                    <span class="text-xs text-slate-300">⏱ Sisa Waktu:</span>
                    <span id="timerDisplay" class="font-mono text-sm font-bold text-[#f97316]">--:--</span>
                </div>
            </div>
        </div>
    </header>

    {{-- MAIN ASSESSMENT CONTAINER --}}
    <main class="mx-auto max-w-4xl px-4 py-8 sm:px-6">

        {{-- PROGRESS BAR --}}
        <div class="mb-6 rounded-3xl border border-white/80 bg-white p-4 shadow-sm">
            <div class="flex items-center justify-between text-xs font-semibold text-slate-600 mb-2">
                <span>Progres Pengerjaan: <strong id="progressText" class="text-[#f97316]">0 / 24 Soal</strong></span>
                <span id="progressPercent">0%</span>
            </div>
            <div class="h-2.5 w-full overflow-hidden rounded-full bg-slate-100">
                <div id="progressBarFill" class="h-full bg-[#f97316] transition-all duration-300" style="width: 0%"></div>
            </div>

            {{-- QUESTION NUMBER PALETTE JUMP --}}
            <div class="mt-4 flex flex-wrap gap-1.5 justify-center">
                @for ($n = 0; $n < 24; $n++)
                    <button
                        type="button"
                        onclick="jumpToQuestion({{ $n }})"
                        id="navBtn_{{ $n }}"
                        class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-xs font-mono font-bold text-slate-700 transition hover:border-[#f97316] hover:text-[#f97316]"
                    >
                        {{ $n + 1 }}
                    </button>
                @endfor
            </div>
        </div>

        {{-- QUESTION CARD (LOADED VIA AJAX) --}}
        <div id="questionCard" class="overflow-hidden rounded-[40px] border border-white/80 bg-white p-6 shadow-xl shadow-slate-900/5 sm:p-10 transition-all duration-200">
            <div class="border-b border-slate-100 pb-4 flex items-center justify-between">
                <div>
                    <span class="rounded-full bg-orange-50 px-3 py-1 text-xs font-bold text-[#f97316]">
                        Pertanyaan <span id="displayQuestionNumber">1</span> dari 24
                    </span>
                    <p class="mt-2 text-xs font-medium text-slate-600" id="displayInstruction">
                        Pilih satu yang PALING dan satu yang PALING TIDAK menggambarkan Anda.
                    </p>
                </div>
            </div>

            {{-- OPTIONS MATRIX TABLE --}}
            <div class="mt-6">
                <div class="grid grid-cols-12 gap-2 border-b border-slate-200 bg-slate-50/70 p-3 text-xs font-bold uppercase tracking-wider text-slate-700 rounded-2xl">
                    <div class="col-span-8 sm:col-span-8">Pernyataan</div>
                    <div class="col-span-2 sm:col-span-2 text-center text-blue-700">PALING (M)</div>
                    <div class="col-span-2 sm:col-span-2 text-center text-rose-700">TIDAK (L)</div>
                </div>

                <div id="optionsList" class="mt-2 divide-y divide-slate-100">
                    {{-- Rendered dynamically by JS --}}
                </div>
            </div>

            {{-- NAVIGATION CONTROLS --}}
            <div class="mt-8 flex items-center justify-between border-t border-slate-100 pt-6">
                <button
                    type="button"
                    id="prevBtn"
                    onclick="prevQuestion()"
                    class="rounded-full border border-slate-200 bg-slate-50 px-5 py-2.5 text-xs font-bold text-slate-700 transition hover:bg-slate-100 disabled:opacity-30 disabled:cursor-not-allowed"
                >
                    ← Sebelumnya
                </button>

                <div class="flex items-center gap-3">
                    <button
                        type="button"
                        id="nextBtn"
                        onclick="nextQuestion()"
                        class="rounded-full bg-[#322d2a] px-6 py-2.5 text-xs font-bold text-white transition hover:bg-black"
                    >
                        Selanjutnya →
                    </button>

                    <button
                        type="button"
                        id="submitBtn"
                        onclick="confirmSubmit()"
                        class="rounded-full bg-emerald-600 px-6 py-2.5 text-xs font-bold text-white shadow-lg shadow-emerald-600/25 transition hover:bg-emerald-700"
                    >
                        ✓ Selesai & Kirim
                    </button>
                </div>
            </div>
        </div>

    </main>

    {{-- SUBMIT CONFIRMATION MODAL --}}
    <div id="submitModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 p-4 backdrop-blur-sm">
        <div class="w-full max-w-md rounded-[36px] bg-white p-8 shadow-2xl text-center">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-emerald-100 text-2xl text-emerald-600">
                ✓
            </div>
            <h3 class="mt-4 text-xl font-bold text-[#322d2a]">Kirim Jawaban Asesmen?</h3>
            <p class="mt-2 text-xs text-slate-500 leading-relaxed" id="submitSummaryText">
                Pastikan Anda telah mengisi seluruh 24 butir soal dengan lengkap. Jawaban yang sudah dikirim tidak dapat diubah kembali.
            </p>

            <div class="mt-6 flex items-center justify-center gap-3">
                <button type="button" onclick="closeSubmitModal()" class="rounded-full px-5 py-2.5 text-xs font-bold text-slate-600 hover:text-slate-900">
                    Periksa Kembali
                </button>
                <button type="button" onclick="executeSubmit()" class="rounded-full bg-emerald-600 px-6 py-2.5 text-xs font-bold text-white shadow-md hover:bg-emerald-700">
                    Ya, Kirim Sekarang
                </button>
            </div>
        </div>
    </div>

    {{-- JAVASCRIPT ENGINE --}}
    <script>
        const SESSION_TOKEN = "{{ $session->session_token }}";
        const BASE_API = "{{ url('/api/disc/session/' . $session->session_token) }}";
        const CSRF_TOKEN = "{{ csrf_token() }}";

        let currentIndex = 0;
        let totalQuestions = 24;
        let currentQuestionId = null;
        let selectedMost = null;
        let selectedLeast = null;
        let timerInterval = null;
        let timeRemaining = {{ $session->assessment->duration_minutes * 60 }};
        let answeredQuestionsMap = {};

        // Security listeners
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                logSecurityEvent('TAB_SWITCH', { hidden: true });
            } else {
                logSecurityEvent('WINDOW_FOCUS', { focused: true });
            }
        });

        window.addEventListener('blur', () => {
            logSecurityEvent('WINDOW_BLUR', { blur: true });
        });

        document.addEventListener('keydown', (e) => {
            if ((e.ctrlKey || e.metaKey) && ['c', 'v', 'x', 'a', 'p', 's', 'u'].includes(e.key.toLowerCase())) {
                e.preventDefault();
                logSecurityEvent('COPY_ATTEMPT', { key: e.key });
                return false;
            }
            if (['F12'].includes(e.key) || (e.ctrlKey && e.shiftKey && ['I', 'J', 'C'].includes(e.key.toUpperCase()))) {
                e.preventDefault();
                logSecurityEvent('DEVTOOLS_SHORTCUT', { key: e.key });
                return false;
            }
        });

        // Periodic watermark motion
        setInterval(() => {
            const wm = document.getElementById('watermarkOverlay');
            if (wm) {
                const rot = -20 + (Math.random() * 4 - 2);
                wm.style.transform = `rotate(${rot}deg) scale(1.2)`;
            }
        }, 8000);

        function logSecurityEvent(eventType, metadata = {}) {
            fetch(`${BASE_API}/security-event`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify({ event_type: eventType, metadata })
            }).catch(() => {});
        }

        async function loadQuestion(index) {
            showSaveStatus('Memuat...', 'blue');
            try {
                const res = await fetch(`${BASE_API}/question?index=${index}`);
                const data = await res.json();

                if (data.completed) {
                    window.location.href = data.redirect;
                    return;
                }

                currentIndex = data.index;
                totalQuestions = data.total_questions;
                currentQuestionId = data.question_id;
                selectedMost = data.current_answer?.most_option_id || null;
                selectedLeast = data.current_answer?.least_option_id || null;

                document.getElementById('displayQuestionNumber').innerText = data.question_number;
                document.getElementById('displayInstruction').innerText = data.instruction || 'Pilih satu yang PALING dan satu yang PALING TIDAK menggambarkan Anda.';

                // Render options
                const container = document.getElementById('optionsList');
                container.innerHTML = '';

                data.options.forEach((opt, idx) => {
                    const row = document.createElement('div');
                    row.className = 'grid grid-cols-12 gap-2 items-center p-3.5 hover:bg-slate-50/80 rounded-2xl transition';
                    row.innerHTML = `
                        <div class="col-span-8 sm:col-span-8 flex items-start gap-3">
                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-slate-100 text-xs font-bold text-slate-700">
                                ${opt.label}
                            </span>
                            <span class="text-xs sm:text-sm text-slate-800 leading-relaxed font-medium">${opt.statement}</span>
                        </div>
                        <div class="col-span-2 sm:col-span-2 flex justify-center">
                            <input
                                type="radio"
                                name="most_choice"
                                value="${opt.id}"
                                ${selectedMost == opt.id ? 'checked' : ''}
                                onchange="selectMost(${opt.id})"
                                class="h-5 w-5 text-blue-600 focus:ring-blue-500 cursor-pointer"
                            >
                        </div>
                        <div class="col-span-2 sm:col-span-2 flex justify-center">
                            <input
                                type="radio"
                                name="least_choice"
                                value="${opt.id}"
                                ${selectedLeast == opt.id ? 'checked' : ''}
                                onchange="selectLeast(${opt.id})"
                                class="h-5 w-5 text-rose-600 focus:ring-rose-500 cursor-pointer"
                            >
                        </div>
                    `;
                    container.appendChild(row);
                });

                // Update UI state
                document.getElementById('prevBtn').disabled = (currentIndex === 0);
                updateNavigationPalette();
                showSaveStatus('Tersimpan', 'emerald');
            } catch (err) {
                console.error(err);
                showSaveStatus('Koneksi terganggu', 'rose');
            }
        }

        async function selectMost(optionId) {
            if (selectedLeast == optionId) {
                alert('Pilihan PALING (Most) dan PALING TIDAK (Least) tidak boleh pada baris yang sama.');
                loadQuestion(currentIndex);
                return;
            }
            selectedMost = optionId;
            await autosave();
        }

        async function selectLeast(optionId) {
            if (selectedMost == optionId) {
                alert('Pilihan PALING (Most) dan PALING TIDAK (Least) tidak boleh pada baris yang sama.');
                loadQuestion(currentIndex);
                return;
            }
            selectedLeast = optionId;
            await autosave();
        }

        async function autosave() {
            showSaveStatus('Menyimpan...', 'amber');
            try {
                const res = await fetch(`${BASE_API}/answer`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    body: JSON.stringify({
                        question_id: currentQuestionId,
                        most_option_id: selectedMost,
                        least_option_id: selectedLeast,
                    })
                });
                const data = await res.json();
                if (res.ok && data.saved) {
                    showSaveStatus('Tersimpan', 'emerald');
                    if (selectedMost && selectedLeast) {
                        answeredQuestionsMap[currentIndex] = true;
                    }
                    updateProgressBar();
                    updateNavigationPalette();
                } else {
                    showSaveStatus(data.error || 'Gagal menyimpan', 'rose');
                }
            } catch (e) {
                showSaveStatus('Koneksi terputus', 'rose');
            }
        }

        function showSaveStatus(text, color) {
            const badge = document.getElementById('saveStatus');
            if (!badge) return;
            badge.className = `flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold bg-${color}-50 text-${color}-700`;
            badge.innerHTML = `<span class="h-1.5 w-1.5 rounded-full bg-${color}-500"></span><span>${text}</span>`;
        }

        function prevQuestion() {
            if (currentIndex > 0) {
                loadQuestion(currentIndex - 1);
            }
        }

        function nextQuestion() {
            if (currentIndex < totalQuestions - 1) {
                loadQuestion(currentIndex + 1);
            }
        }

        function jumpToQuestion(index) {
            loadQuestion(index);
        }

        function updateProgressBar() {
            const answeredCount = Object.keys(answeredQuestionsMap).length;
            document.getElementById('progressText').innerText = `${answeredCount} / 24 Soal`;
            const pct = Math.round((answeredCount / 24) * 100);
            document.getElementById('progressPercent').innerText = `${pct}%`;
            document.getElementById('progressBarFill').style.width = `${pct}%`;
        }

        function updateNavigationPalette() {
            for (let i = 0; i < 24; i++) {
                const btn = document.getElementById(`navBtn_${i}`);
                if (!btn) continue;
                if (i === currentIndex) {
                    btn.className = 'flex h-7 w-7 items-center justify-center rounded-lg border-2 border-[#f97316] bg-[#322d2a] text-xs font-mono font-bold text-white shadow-sm';
                } else if (answeredQuestionsMap[i]) {
                    btn.className = 'flex h-7 w-7 items-center justify-center rounded-lg border border-emerald-300 bg-emerald-50 text-xs font-mono font-bold text-emerald-700';
                } else {
                    btn.className = 'flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-xs font-mono font-bold text-slate-700 hover:border-[#f97316]';
                }
            }
        }

        function startTimer(seconds) {
            timeRemaining = seconds;
            updateTimerDisplay();

            timerInterval = setInterval(() => {
                timeRemaining--;
                updateTimerDisplay();

                if (timeRemaining <= 0) {
                    clearInterval(timerInterval);
                    alert('Waktu pengerjaan asesmen telah habis. Sistem akan otomatis mengirimkan jawaban Anda.');
                    executeSubmit();
                }
            }, 1000);
        }

        function updateTimerDisplay() {
            const m = Math.floor(timeRemaining / 60);
            const s = timeRemaining % 60;
            document.getElementById('timerDisplay').innerText = `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
        }

        function confirmSubmit() {
            const answeredCount = Object.keys(answeredQuestionsMap).length;
            const summary = document.getElementById('submitSummaryText');
            if (answeredCount < 24) {
                summary.innerHTML = `<span class="text-rose-600 font-bold">Perhatian: Anda baru menjawab ${answeredCount} dari 24 soal.</span><br>Harap lengkapi semua soal sebelum submit.`;
            } else {
                summary.innerHTML = `Anda telah menjawab seluruh <strong>24 butir soal</strong> dengan lengkap. Apakah Anda yakin ingin mengakhiri asesmen?`;
            }
            document.getElementById('submitModal').classList.remove('hidden');
            document.getElementById('submitModal').classList.add('flex');
        }

        function closeSubmitModal() {
            document.getElementById('submitModal').classList.add('hidden');
            document.getElementById('submitModal').classList.remove('flex');
        }

        async function executeSubmit() {
            showSaveStatus('Mengirim & Menilai...', 'blue');
            try {
                const res = await fetch(`${BASE_API}/submit`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    }
                });
                const data = await res.json();
                if (res.ok && data.success) {
                    window.location.href = data.redirect_url;
                } else {
                    alert(data.error || 'Terjadi kendala saat mengirimkan asesmen. Pastikan semua soal terisi.');
                    closeSubmitModal();
                }
            } catch (err) {
                alert('Gagal menghubungi server. Periksa koneksi internet Anda.');
                closeSubmitModal();
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', () => {
            loadQuestion(0);
            startTimer(timeRemaining);
        });
    </script>
</body>
</html>
