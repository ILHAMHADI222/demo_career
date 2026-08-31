<!DOCTYPE html>
<html lang="id" class="select-none">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sub-Tes 3: Tes Kepribadian - {{ $assessment->title }}</title>
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

        /* Subtle Dynamic Watermark */
        .watermark-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            pointer-events: none;
            z-index: 1;
            opacity: 0.035;
            display: flex;
            flex-wrap: wrap;
            align-content: space-around;
            justify-content: space-around;
            transform: rotate(-15deg) scale(1.1);
            overflow: hidden;
            font-family: monospace;
            font-weight: 700;
            font-size: 13px;
            color: #0f0e12;
            transition: transform 10s ease-in-out;
        }

        /* Custom Radio Buttons */
        .custom-radio {
            appearance: none;
            -webkit-appearance: none;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            cursor: pointer;
            position: relative;
            outline: none;
            transition: all 0.2s ease;
        }
        .custom-radio-most {
            border: 2px solid #93c5fd;
            background-color: #ffffff;
        }
        .custom-radio-most:hover {
            border-color: #3b82f6;
            background-color: #eff6ff;
        }
        .custom-radio-most:checked {
            border-color: #2563eb;
            background-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.25);
        }
        .custom-radio-most:checked::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #ffffff;
            font-size: 12px;
            font-weight: bold;
        }

        .custom-radio-least {
            border: 2px solid #fca5a5;
            background-color: #ffffff;
        }
        .custom-radio-least:hover {
            border-color: #ef4444;
            background-color: #fef2f2;
        }
        .custom-radio-least:checked {
            border-color: #dc2626;
            background-color: #dc2626;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.25);
        }
        .custom-radio-least:checked::after {
            content: '✕';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #ffffff;
            font-size: 11px;
            font-weight: bold;
        }
    </style>
</head>
<body class="min-h-screen bg-[#f0efe9] font-sans text-slate-900 antialiased relative" oncontextmenu="return false;">

    {{-- DYNAMIC WATERMARK OVERLAY (Subtle Background) --}}
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
    <header class="sticky top-0 z-40 border-b border-white/70 bg-white/95 shadow-sm backdrop-blur-md">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-3 sm:px-6">
            <div class="flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-[#322d2a] font-mono text-sm font-bold text-white shadow-sm">
                    DISC
                </span>
                <div>
                    <h1 class="text-sm font-bold text-[#322d2a] sm:text-base">TES KEPRIBADIAN (MOST & LEAST)</h1>
                    <p class="text-xs text-slate-500">{{ auth()->user()->name }} • Sub-Tes 3</p>
                </div>
            </div>

            <div class="flex items-center gap-4">
                {{-- AUTOSAVE STATUS BADGE --}}
                <div id="saveStatus" class="hidden items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 sm:flex">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                    <span>Tersimpan</span>
                </div>

                {{-- UNTIMED BADGE --}}
                <div class="flex items-center gap-2 rounded-full border border-purple-200 bg-purple-50 px-3 py-1.5 text-xs font-semibold text-purple-800 shadow-sm">
                    <span>✨ Tanpa Batas Waktu</span>
                </div>
            </div>
        </div>
    </header>

    {{-- MAIN ASSESSMENT CONTAINER --}}
    <main class="relative z-20 mx-auto max-w-4xl px-4 py-8 sm:px-6">

        {{-- PROGRESS BAR & PALETTE --}}
        <div class="mb-6 rounded-3xl border border-white/80 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between text-xs font-semibold text-slate-600 mb-2">
                <span>Progres Pengerjaan: <strong id="progressText" class="text-[#f97316]">0 / 24 Soal Terisi Lengkap</strong></span>
                <span id="progressPercent" class="font-mono font-bold text-slate-800">0%</span>
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

        {{-- QUESTION CARD --}}
        <div id="questionCard" class="overflow-hidden rounded-[40px] border border-white/90 bg-white p-6 shadow-xl shadow-slate-900/5 sm:p-10 transition-all duration-200">
            <div class="border-b border-slate-100 pb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-orange-50 px-3.5 py-1 text-xs font-bold text-[#f97316]">
                        <span class="h-2 w-2 rounded-full bg-[#f97316]"></span>
                        Pertanyaan <span id="displayQuestionNumber">1</span> dari 24
                    </span>
                    <p class="mt-2 text-xs font-medium text-slate-600" id="displayInstruction">
                        Pilih satu pernyataan <strong>PALING</strong> dan satu pernyataan <strong>PALING TIDAK</strong> menggambarkan diri Anda.
                    </p>
                </div>

                {{-- STATUS PILL OF CURRENT QUESTION --}}
                <div id="questionStatusPill" class="flex items-center gap-2 text-xs">
                    <span id="badgeMost" class="rounded-full bg-slate-100 px-2.5 py-1 font-semibold text-slate-500">Paling (M): Belum</span>
                    <span id="badgeLeast" class="rounded-full bg-slate-100 px-2.5 py-1 font-semibold text-slate-500">Tidak (L): Belum</span>
                </div>
            </div>

            {{-- OPTIONS MATRIX TABLE --}}
            <div class="mt-6">
                <div class="grid grid-cols-12 gap-2 border-b border-slate-200 bg-slate-50 p-3.5 text-xs font-bold uppercase tracking-wider text-slate-700 rounded-2xl">
                    <div class="col-span-8 sm:col-span-8">Karakteristik / Pernyataan</div>
                    <div class="col-span-2 sm:col-span-2 text-center text-blue-700 flex flex-col items-center">
                        <span>PALING (M)</span>
                        <span class="text-[10px] font-normal text-blue-500">Pilih 1</span>
                    </div>
                    <div class="col-span-2 sm:col-span-2 text-center text-rose-700 flex flex-col items-center">
                        <span>TIDAK (L)</span>
                        <span class="text-[10px] font-normal text-rose-500">Pilih 1</span>
                    </div>
                </div>

                <div id="optionsList" class="mt-3 space-y-2">
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
                        class="hidden rounded-full bg-emerald-600 px-6 py-2.5 text-xs font-bold text-white shadow-lg shadow-emerald-600/25 transition hover:bg-emerald-700"
                    >
                        ✓ Selesai & Kirim Jawaban
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
        let currentOptionsData = [];
        let selectedMost = null;
        let selectedLeast = null;
        let timerInterval = null;
        let timeRemaining = {{ (int) ($remainingSeconds ?? (($session->assessment->duration_minutes ?: 10) * 60)) }};
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

        // Watermark subtle shift
        setInterval(() => {
            const wm = document.getElementById('watermarkOverlay');
            if (wm) {
                const rot = -15 + (Math.random() * 4 - 2);
                wm.style.transform = `rotate(${rot}deg) scale(1.1)`;
            }
        }, 10000);

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

                if (data.completed && data.redirect) {
                    window.location.href = data.redirect;
                    return;
                }

                currentIndex = data.index;
                totalQuestions = data.total_questions;
                currentQuestionId = data.question_id;
                currentOptionsData = data.options || [];
                selectedMost = data.current_answer?.most_option_id || null;
                selectedLeast = data.current_answer?.least_option_id || null;

                if (data.answered_indices && Array.isArray(data.answered_indices)) {
                    answeredQuestionsMap = {};
                    data.answered_indices.forEach(idx => answeredQuestionsMap[idx] = true);
                }

                if (data.time_remaining_seconds !== undefined && timerInterval === null) {
                    startTimer(data.time_remaining_seconds);
                }

                document.getElementById('displayQuestionNumber').innerText = data.question_number;
                document.getElementById('displayInstruction').innerText = data.instruction || 'Pilih satu pernyataan PALING dan satu pernyataan PALING TIDAK menggambarkan diri Anda.';

                renderOptions();
                updateQuestionStatusBadges();

                // Update UI state
                document.getElementById('prevBtn').disabled = (currentIndex === 0);

                if (currentIndex === totalQuestions - 1) {
                    document.getElementById('nextBtn').classList.add('hidden');
                    document.getElementById('submitBtn').classList.remove('hidden');
                } else {
                    document.getElementById('nextBtn').classList.remove('hidden');
                    document.getElementById('submitBtn').classList.add('hidden');
                }

                updateProgressBar();
                updateNavigationPalette();
                showSaveStatus('Tersimpan', 'emerald');
            } catch (err) {
                console.error(err);
                showSaveStatus('Koneksi terganggu', 'rose');
            }
        }

        function renderOptions() {
            const container = document.getElementById('optionsList');
            container.innerHTML = '';

            currentOptionsData.forEach((opt) => {
                const isMost = (selectedMost == opt.id);
                const isLeast = (selectedLeast == opt.id);

                const row = document.createElement('div');
                let rowBg = 'border border-slate-100 bg-white hover:bg-slate-50/80';
                if (isMost) rowBg = 'border border-blue-200 bg-blue-50/70 ring-1 ring-blue-300';
                if (isLeast) rowBg = 'border border-rose-200 bg-rose-50/70 ring-1 ring-rose-300';

                row.className = `grid grid-cols-12 gap-2 items-center p-3.5 rounded-2xl transition-all duration-150 ${rowBg}`;
                row.id = `optRow_${opt.id}`;

                row.innerHTML = `
                    <div class="col-span-8 sm:col-span-8 flex items-start gap-3">
                        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-xl ${isMost ? 'bg-blue-600 text-white' : (isLeast ? 'bg-rose-600 text-white' : 'bg-slate-100 text-slate-700')} text-xs font-bold shadow-xs">
                            ${opt.label}
                        </span>
                        <div>
                            <p class="text-xs sm:text-sm text-slate-800 leading-relaxed font-medium">${opt.statement}</p>
                            ${isMost ? '<span class="inline-block mt-1 text-[11px] font-bold text-blue-700">✓ Paling Menggambarkan Diri (Most)</span>' : ''}
                            ${isLeast ? '<span class="inline-block mt-1 text-[11px] font-bold text-rose-700">✕ Paling Tidak Menggambarkan (Least)</span>' : ''}
                        </div>
                    </div>
                    <div class="col-span-2 sm:col-span-2 flex justify-center">
                        <input
                            type="radio"
                            name="most_choice"
                            value="${opt.id}"
                            ${isMost ? 'checked' : ''}
                            onchange="selectMost(${opt.id})"
                            class="custom-radio custom-radio-most"
                            title="Paling (Most)"
                        >
                    </div>
                    <div class="col-span-2 sm:col-span-2 flex justify-center">
                        <input
                            type="radio"
                            name="least_choice"
                            value="${opt.id}"
                            ${isLeast ? 'checked' : ''}
                            onchange="selectLeast(${opt.id})"
                            class="custom-radio custom-radio-least"
                            title="Paling Tidak (Least)"
                        >
                    </div>
                `;
                container.appendChild(row);
            });
        }

        function updateQuestionStatusBadges() {
            const bMost = document.getElementById('badgeMost');
            const bLeast = document.getElementById('badgeLeast');

            const optMost = currentOptionsData.find(o => o.id == selectedMost);
            const optLeast = currentOptionsData.find(o => o.id == selectedLeast);

            if (optMost) {
                bMost.className = 'rounded-full bg-blue-100 px-3 py-1 font-bold text-blue-700';
                bMost.innerText = `Paling (M): ${optMost.label} ✓`;
            } else {
                bMost.className = 'rounded-full bg-slate-100 px-3 py-1 font-semibold text-slate-500';
                bMost.innerText = 'Paling (M): Belum';
            }

            if (optLeast) {
                bLeast.className = 'rounded-full bg-rose-100 px-3 py-1 font-bold text-rose-700';
                bLeast.innerText = `Tidak (L): ${optLeast.label} ✕`;
            } else {
                bLeast.className = 'rounded-full bg-slate-100 px-3 py-1 font-semibold text-slate-500';
                bLeast.innerText = 'Tidak (L): Belum';
            }
        }

        async function selectMost(optionId) {
            if (selectedLeast == optionId) {
                selectedLeast = null; // Auto uncheck Least on same row
            }
            selectedMost = optionId;
            renderOptions();
            updateQuestionStatusBadges();
            await autosave();
        }

        async function selectLeast(optionId) {
            if (selectedMost == optionId) {
                selectedMost = null; // Auto uncheck Most on same row
            }
            selectedLeast = optionId;
            renderOptions();
            updateQuestionStatusBadges();
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
                if (res.ok && (data.saved || data.success)) {
                    if (selectedMost && selectedLeast) {
                        answeredQuestionsMap[currentIndex] = true;
                        showSaveStatus('Lengkap & Tersimpan ✓', 'emerald');
                    } else {
                        delete answeredQuestionsMap[currentIndex];
                        showSaveStatus('Tersimpan Sebagian', 'blue');
                    }
                    updateProgressBar();
                    updateNavigationPalette();
                } else if (data.expired && data.redirect_url) {
                    window.location.href = data.redirect_url;
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
            badge.className = `flex items-center gap-1.5 rounded-full px-3.5 py-1 text-xs font-semibold bg-${color}-50 text-${color}-700 border border-${color}-200`;
            badge.innerHTML = `<span class="h-2 w-2 rounded-full bg-${color}-500"></span><span>${text}</span>`;
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
            document.getElementById('progressText').innerText = `${answeredCount} / 24 Soal Terisi Lengkap`;
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
                    btn.className = 'flex h-7 w-7 items-center justify-center rounded-lg border border-emerald-300 bg-emerald-100 text-xs font-mono font-bold text-emerald-800';
                } else {
                    btn.className = 'flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-xs font-mono font-bold text-slate-700 hover:border-[#f97316]';
                }
            }
        }

        function startTimer(seconds) {
            timeRemaining = Math.max(0, Math.floor(seconds));
            updateTimerDisplay();

            if (timerInterval) clearInterval(timerInterval);

            timerInterval = setInterval(() => {
                timeRemaining--;
                updateTimerDisplay();

                if (timeRemaining <= 0) {
                    clearInterval(timerInterval);
                    handleAutoCloseTimeout();
                }
            }, 1000);
        }

        function updateTimerDisplay() {
            const total = Math.max(0, Math.floor(timeRemaining));
            const m = Math.floor(total / 60);
            const s = total % 60;
            const timerEl = document.getElementById('timerDisplay');
            if (timerEl) {
                timerEl.innerText = `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
                if (total <= 120) {
                    timerEl.className = 'font-mono text-sm font-bold text-rose-500 animate-pulse';
                } else {
                    timerEl.className = 'font-mono text-sm font-bold text-[#f97316]';
                }
            }
        }

        async function handleAutoCloseTimeout() {
            document.querySelectorAll('input[type="radio"], button').forEach(el => el.disabled = true);

            const summary = document.getElementById('submitSummaryText');
            summary.innerHTML = `<span class="text-rose-600 font-bold">Waktu 10 menit pengerjaan telah habis!</span><br>Sistem sedang mengunci jawaban Anda secara otomatis...`;
            document.getElementById('submitModal').classList.remove('hidden');
            document.getElementById('submitModal').classList.add('flex');

            showSaveStatus('Waktu habis, mengunci...', 'rose');

            try {
                const res = await fetch(`${BASE_API}/submit`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    body: JSON.stringify({ force_timeout: true })
                });
                const data = await res.json();
                if (data.redirect_url) {
                    window.location.href = data.redirect_url;
                } else {
                    window.location.reload();
                }
            } catch (err) {
                window.location.reload();
            }
        }

        function confirmSubmit() {
            const answeredCount = Object.keys(answeredQuestionsMap).length;
            const summary = document.getElementById('submitSummaryText');
            if (answeredCount < 24) {
                summary.innerHTML = `<span class="text-rose-600 font-bold">Perhatian: Anda baru menjawab ${answeredCount} dari 24 soal secara lengkap.</span><br>Harap lengkapi pilihan Most dan Least di semua butir soal sebelum submit.`;
            } else {
                summary.innerHTML = `Anda telah menjawab seluruh <strong>24 butir soal</strong> secara lengkap. Apakah Anda yakin ingin mengakhiri asesmen?`;
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
                    },
                    body: JSON.stringify({ force_timeout: false })
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
        });
    </script>
</body>
</html>
