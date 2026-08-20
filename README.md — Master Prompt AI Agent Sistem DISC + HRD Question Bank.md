# README.md — Master Prompt AI Agent Sistem DISC + HRD Question Bank

## Tambahan Requirement Utama

Sistem harus memiliki dua kelompok akses utama:

```text
HRD / Administrator
        ↓
Kelola Bank Soal
Kelola Kunci Jawaban
Kelola Scoring
Kelola Normalisasi
Kelola Profile
Kelola Interpretasi
Kelola Assessment
Lihat Hasil
Lihat Grafik
Export Laporan

Peserta
        ↓
Mengerjakan Assessment
        ↓
Tidak melihat scoring key
Tidak dapat mengubah hasil
Menerima hasil sesuai permission HRD
```

---

# 42. Role & Permission

Sistem minimal memiliki role:

```text
SUPER_ADMIN
HRD
PARTICIPANT
```

## SUPER_ADMIN

Memiliki akses terhadap:

- konfigurasi aplikasi;
- organisasi;
- user;
- role;
- master DISC;
- bank soal;
- scoring key;
- normalization table;
- profile rules;
- hasil seluruh assessment;
- audit log.

---

## HRD

HRD bertanggung jawab terhadap pengelolaan assessment.

HRD harus dapat:

```text
✓ Membuat bank soal.
✓ Membuat assessment.
✓ Membuat kategori soal.
✓ Membuat pertanyaan.
✓ Membuat empat pilihan jawaban.
✓ Menentukan kunci Most.
✓ Menentukan kunci Least.
✓ Menentukan mapping D/I/S/C/*.
✓ Mengaktifkan/nonaktifkan soal.
✓ Melakukan preview soal.
✓ Mengatur urutan soal.
✓ Mengacak soal.
✓ Mengacak pilihan.
✓ Mengatur durasi assessment.
✓ Memilih peserta.
✓ Melihat progress peserta.
✓ Melihat jawaban peserta.
✓ Melihat raw score.
✓ Melihat Graph 1.
✓ Melihat Graph 2.
✓ Melihat Graph 3.
✓ Melihat profile DISC.
✓ Melihat interpretasi.
✓ Export laporan.
```

HRD **tidak boleh mengubah hasil assessment yang sudah selesai secara manual**.

Apabila master scoring diubah, hasil lama harus tetap menggunakan versi master saat assessment tersebut dikerjakan.

---

# 43. HRD Dashboard

Dashboard HRD harus memiliki menu minimal:

```text
Dashboard

Assessment
├── Daftar Assessment
├── Buat Assessment
├── Peserta
└── Hasil Assessment

Bank Soal
├── Bank Soal
├── Kategori
├── Pertanyaan
├── Scoring Key
├── Normalization
└── Profile Rules

DISC Result
├── Individual Result
├── Graph Analysis
└── Reports

Master Data
├── DISC Profile
├── Interpretation
└── Assessment Version

Security
├── Activity Log
└── Assessment Monitoring
```

---

# 44. Bank Soal

Sistem wajib memiliki fitur:

```text
QUESTION BANK
```

Bank soal digunakan untuk menyimpan seluruh soal DISC.

Bank soal tidak boleh ditanam secara hardcoded di frontend.

Database harus menjadi source of truth.

---

# 45. Struktur Bank Soal

Gunakan konsep:

```text
Question Bank
      ↓
Question
      ↓
4 Options
      ↓
Most Mapping
Least Mapping
```

Contoh:

```text
BANK:
DISC MASTER ISP

QUESTION:
1

OPTION A:
Gampangan, mudah setuju

OPTION B:
Percaya, mudah percaya pada orang

OPTION C:
Petualang, mengambil risiko

OPTION D:
Toleran, menghormati
```

HRD harus dapat melakukan:

```text
Create
Read
Update
Archive
Duplicate
Preview
```

Untuk keamanan data historis, gunakan:

```text
SOFT DELETE
```

bukan hard delete apabila soal pernah digunakan.

---

# 46. Question Bank Database

Tambahkan tabel:

```text
question_banks
```

Struktur:

```text
id
name
code
description

assessment_type

version

status

created_by
created_at

updated_by
updated_at
```

Contoh:

```text
name:
DISC MASTER ISP

code:
DISC-ISP

assessment_type:
DISC

version:
1.0

status:
active
```

---

# 47. Question Database

Gunakan:

```text
disc_questions
```

Struktur:

```text
id

question_bank_id

question_number

category_id

instruction

active

created_by
created_at

updated_by
updated_at
```

---

# 48. Question Option Database

Gunakan:

```text
disc_question_options
```

Kolom:

```text
id

question_id

option_number

option_label

statement

most_mapping

least_mapping

display_order

active
```

Nilai:

```text
most_mapping:
D
I
S
C
*

least_mapping:
D
I
S
C
*
```

---

# 49. HRD Question Editor

HRD harus mempunyai editor soal.

Contoh UI:

```text
QUESTION 1

Pernyataan 1
[ Gampangan, mudah setuju ]

Most Mapping
[ S ]

Least Mapping
[ S ]


Pernyataan 2
[ Percaya, mudah percaya pada orang ]

Most Mapping
[ I ]

Least Mapping
[ I ]


Pernyataan 3
[ Petualang, mengambil risiko ]

Most Mapping
[ * ]

Least Mapping
[ D ]


Pernyataan 4
[ Toleran, menghormati ]

Most Mapping
[ C ]

Least Mapping
[ C ]
```

Kemudian:

```text
[ SAVE DRAFT ]

[ PREVIEW ]

[ PUBLISH ]
```

---

# 50. Pisahkan Soal dan Kunci Jawaban

QUESTION DATA dan SCORING KEY secara logical harus dipisahkan.

Participant API hanya boleh mengembalikan:

```json
{
  "question_id": 1,
  "number": 1,
  "options": [
    {
      "id": 101,
      "text": "..."
    }
  ]
}
```

DILARANG mengembalikan:

```json
{
  "most_mapping": "D",
  "least_mapping": "C"
}
```

Kunci scoring hanya boleh tersedia untuk:

```text
Backend Scoring Engine

dan

HRD yang memiliki permission:
disc.scoring.manage
```

---

# 51. HRD Scoring Key Editor

HRD harus mempunyai halaman khusus:

```text
BANK SOAL
   ↓
SCORING KEY
```

Tampilan dapat berupa:

| No | Opsi | Most | Least |
|---|---|---|---|
| 1 | A | S | S |
| 1 | B | I | I |
| 1 | C | * | D |
| 1 | D | C | C |

HRD dapat melakukan edit hanya apabila memiliki permission:

```text
disc.scoring.manage
```

Setiap perubahan harus tercatat di:

```text
AUDIT LOG
```

---

# 52. Versioning Bank Soal

Setiap bank soal harus memiliki versioning.

Contoh:

```text
DISC-ISP
v1.0

DISC-ISP
v1.1

DISC-ISP
v2.0
```

Assessment harus menyimpan:

```text
question_bank_version
scoring_version
normalization_version
profile_rule_version
```

Jadi:

```text
Assessment Lama
        ↓
Tetap menghasilkan hasil lama

Assessment Baru
        ↓
Menggunakan master terbaru
```

---

# 53. Publish Workflow

Bank soal harus mempunyai status:

```text
DRAFT

REVIEW

PUBLISHED

ARCHIVED
```

Flow:

```text
HRD membuat soal
       ↓
DRAFT
       ↓
Review
       ↓
PUBLISHED
       ↓
Dapat digunakan assessment
```

Assessment production hanya boleh menggunakan:

```text
PUBLISHED
```

question bank.

---

# 54. Duplicate Question Bank

HRD harus dapat melakukan:

```text
Duplicate Question Bank
```

Contoh:

```text
DISC Master v1
        ↓
Duplicate
        ↓
DISC Master v2 Draft
```

Tujuannya agar HRD tidak perlu mengubah master yang sedang digunakan.

---

# 55. Import Soal

Sistem disarankan mendukung:

```text
IMPORT XLSX
```

untuk memindahkan master DISC.

Import harus melakukan validation:

```text
24 questions

4 options/question

valid Most mapping

valid Least mapping

mapping hanya:
D/I/S/C/*
```

Jika data tidak valid:

```text
IMPORT DITOLAK
```

dan tampilkan error secara detail.

---

# 56. Assessment Builder

HRD dapat membuat assessment menggunakan bank soal.

Contoh:

```text
CREATE ASSESSMENT

Nama:
DISC Recruitment Batch 2026

Question Bank:
DISC ISP Master v1

Jumlah Soal:
24

Random Question:
ON/OFF

Random Options:
ON/OFF

Duration:
30 minutes

Start:
...

End:
...

Result Visibility:
HRD ONLY
```

---

# 57. Question Snapshot

Ini requirement sangat penting.

Saat assessment dimulai, sistem harus menyimpan snapshot:

```text
question_snapshot
```

Tujuan:

Jika HRD mengubah wording soal setelah peserta mulai mengerjakan, assessment tersebut tetap menggunakan soal lama.

Snapshot mencakup:

```text
question text

option text

display order

scoring version
```

---

# 58. Participant Assessment UI

Saat peserta mengerjakan soal:

```text
QUESTION 7 OF 24

Pilih satu yang PALING menggambarkan Anda.

Pilih satu yang PALING TIDAK menggambarkan Anda.


Pernyataan A

PALING
○

TIDAK PALING
○


Pernyataan B

PALING
○

TIDAK PALING
○


Pernyataan C

PALING
○

TIDAK PALING
○


Pernyataan D

PALING
○

TIDAK PALING
○
```

Peserta tidak boleh melihat:

```text
D

I

S

C

*

most_mapping

least_mapping

raw score

normalization rule

profile rule
```

---

# 59. Question Text Protection

Assessment participant harus menerapkan perlindungan terhadap penyalinan konten.

Target:

```text
Disable text selection

Disable copy

Disable cut

Disable context menu

Disable drag

Disable print shortcut

Detect suspicious shortcuts

Prevent question text from being easily exported
```

Frontend dapat menerapkan:

```css
.assessment-content {
  user-select: none;
  -webkit-user-select: none;
  -webkit-touch-callout: none;
}
```

Tambahkan handler untuk:

```text
copy

cut

contextmenu

dragstart
```

---

# 60. Keyboard Protection

Selama halaman assessment aktif, sistem dapat mencegah shortcut browser tertentu seperti:

```text
Ctrl + C

Ctrl + A

Ctrl + P

Ctrl + S

Ctrl + U

F12
```

dan kombinasi tertentu yang digunakan untuk membuka developer tools.

Namun AI Agent harus memahami:

> Browser-side keyboard protection hanyalah deterrence dan bukan mekanisme keamanan absolut.

Jangan menganggap pemblokiran shortcut sebagai perlindungan data utama.

---

# 61. Screenshot Protection — Important Limitation

Sistem harus berusaha mengurangi risiko peserta mengambil screenshot.

Namun:

> Web browser biasa tidak dapat menjamin bahwa screenshot sistem operasi dapat diblokir 100%.

Karena screenshot dapat dilakukan melalui:

```text
Windows Snipping Tool

Print Screen

macOS Screenshot

Screen Recording

Browser Extension

Remote Desktop

Kamera HP kedua

Perangkat eksternal
```

Maka requirement tidak boleh ditulis sebagai:

```text
"screenshot must be impossible"
```

Tetapi:

```text
"implement maximum practical screenshot deterrence and traceability"
```

---

# 62. Screenshot Deterrence

Gunakan kombinasi:

```text
Dynamic Watermark

User Identity Watermark

Assessment ID

Timestamp

Session ID

Repeated background watermark

Blur on window focus loss

Fullscreen Mode

Visibility monitoring

Audit logging
```

Contoh watermark:

```text
BUDI SANTOSO

EMP-0172

DISC-ASSESS-2026-0181

20 AUG 2026 13:12:24
```

Watermark tampil berulang di seluruh area assessment.

Tujuannya:

> Jika screenshot bocor, screenshot dapat ditelusuri ke session/peserta.

---

# 63. Dynamic Watermark

Watermark jangan hanya berada di header.

Gunakan overlay:

```text
BUDI • DISC-2026-00182

BUDI • DISC-2026-00182

BUDI • DISC-2026-00182
```

Secara diagonal/transparan di layar.

Watermark harus:

```text
bergerak secara periodik

berubah timestamp

tidak mengganggu keterbacaan soal
```

Jangan memberikan opacity terlalu rendah sehingga tidak terlihat pada screenshot.

---

# 64. Fullscreen Assessment

Sistem dapat meminta peserta masuk:

```text
FULLSCREEN MODE
```

ketika assessment dimulai.

Jika peserta keluar fullscreen:

```text
record event
```

Contoh:

```text
FULLSCREEN_EXIT
```

Jangan otomatis menggagalkan peserta hanya karena satu event kecuali kebijakan HRD secara eksplisit menentukan demikian.

---

# 65. Window Focus Monitoring

Monitor:

```text
visibilitychange

window blur

window focus
```

Catat:

```text
TAB_SWITCH

WINDOW_BLUR

WINDOW_FOCUS

PAGE_HIDDEN
```

Contoh:

```json
{
  "assessment_id": "...",
  "participant_id": "...",
  "event": "TAB_SWITCH",
  "timestamp": "..."
}
```

---

# 66. Blur Protection

Ketika halaman assessment kehilangan focus, sistem dapat:

```text
blur question
```

atau menampilkan:

```text
Assessment sedang tidak aktif.
Kembali ke halaman assessment untuk melanjutkan.
```

Tujuannya mengurangi peluang soal tetap terlihat ketika user berpindah window.

---

# 67. Native Application Option

Jika requirement perusahaan benar-benar membutuhkan proteksi screenshot yang jauh lebih kuat, AI Agent harus menyarankan:

```text
Native Mobile Application

Secure Assessment Browser

Kiosk Mode

Managed Corporate Device
```

Contoh kemampuan native tertentu:

```text
Android FLAG_SECURE
```

dapat mengurangi kemampuan screenshot/screen recording pada perangkat yang didukung.

Tetapi keamanan tetap tidak dapat mencegah seseorang menggunakan kamera perangkat lain.

---

# 68. Question Exposure Reduction

Sistem dapat mengurangi kebocoran bank soal dengan:

```text
1 question per screen

tidak mengirim semua soal sekaligus

backend pagination

short-lived question token

random question order

random option order
```

Contoh:

```text
GET

/api/assessment/session/{session}/question/current
```

Jangan melakukan:

```text
GET ALL 24 QUESTIONS
```

jika keamanan bank soal menjadi prioritas.

---

# 69. Secure Question API

Alur:

```text
Participant
    ↓
Request Current Question
    ↓
Backend Validates Session
    ↓
Return 1 Question
    ↓
Participant Answers
    ↓
Backend Saves
    ↓
Return Next Question
```

Contoh:

```text
POST
/api/assessment/session/{id}/answer
```

Response:

```json
{
  "saved": true,
  "next_question_available": true
}
```

Kemudian frontend meminta pertanyaan berikutnya.

---

# 70. Randomization

HRD dapat mengaktifkan:

```text
Randomize Question

Randomize Options
```

Tetapi randomization harus dilakukan berdasarkan:

```text
assessment_session
```

dan disimpan.

Artinya ketika browser refresh:

```text
urutan tidak boleh berubah.
```

---

# 71. Randomization Mapping

Jika option diacak:

Original:

```text
1
2
3
4
```

Displayed:

```text
3
1
4
2
```

Backend tetap harus mengetahui mapping ke:

```text
original_option_id
```

Scoring tidak boleh berdasarkan posisi visual.

---

# 72. Assessment Security Session

Buat:

```text
assessment_sessions
```

Struktur:

```text
id

assessment_id

participant_id

session_token

status

started_at

last_activity_at

completed_at

ip_address

user_agent

question_order_json

security_score

created_at
```

---

# 73. Assessment Security Events

Gunakan tabel:

```text
assessment_security_events
```

Kolom:

```text
id

assessment_session_id

event_type

metadata_json

occurred_at
```

Event:

```text
TAB_SWITCH

WINDOW_BLUR

FULLSCREEN_EXIT

COPY_ATTEMPT

PRINT_ATTEMPT

CONTEXT_MENU_ATTEMPT

DEVTOOLS_SHORTCUT

SESSION_RECONNECT

MULTIPLE_SESSION
```

---

# 74. HRD Monitoring

HRD harus dapat melihat:

```text
Assessment Monitoring
```

Contoh:

```text
Participant:
Andi

Progress:
17 / 24

Status:
IN PROGRESS

Start:
09:01

Security Events:
3

TAB SWITCH:
2

FULLSCREEN EXIT:
1
```

Ini untuk membantu HRD melakukan review, bukan otomatis menyimpulkan kecurangan.

---

# 75. HRD Answer Review

Setelah assessment selesai, HRD dapat melihat:

```text
ANSWER REVIEW
```

Contoh:

```text
QUESTION 1

Most:
Option B

Least:
Option C

Mapping:

Most:
I

Least:
D
```

Mapping hanya terlihat untuk:

```text
HRD AUTHORIZED
```

dan tidak terlihat participant.

---

# 76. HRD Score Dashboard

Setelah scoring selesai, tampilkan:

```text
DISC SCORE
```

Contoh:

```text
MOST / GRAPH 1

D    8
I    7
S    4
C    3


LEAST / GRAPH 2

D    2
I    5
S    8
C    5


CHANGE / GRAPH 3

D   +6
I   +2
S   -4
C   -2
```

Selain raw score tampilkan normalized score.

---

# 77. DISC Graph

HRD harus dapat melihat grafik:

```text
GRAPH 1

GRAPH 2

GRAPH 3
```

Setiap grafik mempunyai sumbu:

```text
D

I

S

C
```

dan normalized value.

Visual dapat berupa:

```text
Line Chart
```

dengan:

```text
X Axis:
D I S C

Y Axis:
Normalized Score
```

Baseline:

```text
0
```

harus terlihat jelas.

---

# 78. Combined DISC Graph

Sediakan juga:

```text
COMBINED GRAPH
```

yang menampilkan:

```text
Graph 1
Graph 2
Graph 3
```

pada satu chart.

Legend wajib tersedia.

HRD dapat:

```text
show/hide Graph 1

show/hide Graph 2

show/hide Graph 3
```

---

# 79. Graph Data Source

Grafik hanya boleh menggunakan:

```text
normalized scores
```

dari DISC scoring engine.

Jangan melakukan scoring tambahan di frontend.

Frontend menerima:

```json
{
  "graph1": {
    "D": 1.2,
    "I": 2.3,
    "S": -1.1,
    "C": 0.3
  }
}
```

dan hanya merender chart.

---

# 80. HRD Result Page

Halaman hasil:

```text
Participant Information
         ↓
Assessment Information
         ↓
DISC Profile
         ↓
Raw Score
         ↓
Graph 1
         ↓
Graph 2
         ↓
Graph 3
         ↓
Combined Graph
         ↓
Profile Interpretation
         ↓
Strength
         ↓
Potential Limitation
         ↓
Communication Style
         ↓
Work Style
         ↓
Development
```

---

# 81. HRD Result Permission

Tambahkan permission:

```text
disc.bank.view

disc.bank.create

disc.bank.update

disc.bank.publish

disc.bank.archive

disc.scoring.view

disc.scoring.manage

disc.normalization.view

disc.normalization.manage

disc.profile.view

disc.profile.manage

disc.assessment.create

disc.assessment.manage

disc.result.view

disc.result.export

disc.security.monitor
```

Role tidak boleh hanya diperiksa di frontend.

Permission wajib diperiksa backend.

---

# 82. HRD Report

HRD dapat export hasil menjadi:

```text
PDF

XLSX
```

Report individual minimal memuat:

```text
Nama

ID peserta

Tanggal assessment

Profile DISC

Graph 1

Graph 2

Graph 3

Raw Score

Normalized Score

Interpretation

Assessment Version
```

---

# 83. Scoring Visibility

Peserta secara default tidak perlu melihat:

```text
scoring key

Most raw score

Least raw score

Graph 3 raw score

normalization table

profile rules
```

HRD dapat mengatur apakah peserta dapat melihat:

```text
final profile

final graph

interpretation
```

Gunakan setting:

```text
RESULT VISIBILITY

HRD_ONLY

PARTICIPANT_AFTER_COMPLETION

PARTICIPANT_AFTER_HRD_APPROVAL
```

---

# 84. Important Security Principle

AI Agent harus memahami:

> Soal yang harus ditampilkan kepada peserta tidak mungkin dibuat 100% tidak dapat direkam setelah konten terlihat di layar.

Karena itu strategi keamanan harus berlapis:

```text
ACCESS CONTROL
       +
QUESTION DELIVERY CONTROL
       +
NO COPY
       +
NO TEXT SELECTION
       +
WATERMARK
       +
RANDOMIZATION
       +
SESSION SECURITY
       +
FOCUS MONITORING
       +
AUDIT LOG
       +
SECURE BROWSER/NATIVE APP
       ↓
REDUCE QUESTION LEAKAGE
```

Jangan menjanjikan sistem web sebagai:

```text
100% anti screenshot
```

Gunakan istilah:

```text
anti-copy

screenshot deterrence

content protection

traceability

assessment security monitoring
```

---

# 85. Security Must Not Break Assessment

Proteksi keamanan tidak boleh:

```text
menghilangkan jawaban peserta

membuat scoring tidak konsisten

mengubah urutan setelah refresh

memutus assessment tanpa recovery

mengganggu accessibility secara tidak perlu
```

Jawaban harus disimpan secara berkala:

```text
AUTO SAVE
```

setiap jawaban dipilih.

---

# 86. Autosave

Saat peserta memilih:

```text
Most

Least
```

frontend mengirim jawaban ke backend.

Status:

```text
SAVING

SAVED

FAILED
```

Jika gagal:

```text
retry
```

dan jangan pindah ke soal selanjutnya sampai backend mengkonfirmasi.

---

# 87. Immutable Completed Assessment

Ketika peserta menekan:

```text
SUBMIT
```

dan backend berhasil scoring:

```text
status = COMPLETED
```

Setelah itu:

```text
answers immutable

scores immutable

question snapshot immutable

scoring snapshot immutable
```

HRD tidak boleh mengubah jawaban.

Jika assessment harus diulang:

```text
buat assessment attempt baru.
```

---

# 88. Multiple Attempts

Gunakan:

```text
assessment_attempt
```

Contoh:

```text
Andi

Attempt 1
Completed

Attempt 2
Completed
```

Jangan overwrite Attempt 1.

---

# 89. Master Prompt Tambahan untuk AI Agent

Tambahkan instruksi berikut ke Master Instruction:

> Sistem memiliki role HRD yang bertanggung jawab mengelola seluruh lifecycle assessment DISC.
>
> HRD harus memiliki Question Bank untuk menyimpan pertanyaan DISC, empat pilihan per pertanyaan, Most mapping, dan Least mapping.
>
> Question Bank harus versioned, auditable, dan memiliki workflow Draft → Review → Published → Archived.
>
> Hanya bank soal Published yang dapat digunakan untuk assessment production.
>
> HRD dapat membuat dan mengedit soal serta scoring key selama master tersebut belum terkunci oleh versi assessment.
>
> Assessment harus menggunakan question dan scoring snapshot agar perubahan master di masa depan tidak mengubah hasil assessment lama.
>
> Peserta tidak boleh menerima Most mapping, Least mapping, normalization data, profile rules, atau scoring key dari API.
>
> Scoring hanya dilakukan server-side.
>
> Halaman assessment harus menerapkan content protection seperti disable text selection, disable copy, disable context menu, watermark peserta, fullscreen monitoring, tab/window focus monitoring, randomization, dan security audit log.
>
> Jangan mengklaim browser dapat mencegah screenshot 100%. Gunakan maximum practical screenshot deterrence dan traceability. Jika organisasi membutuhkan proteksi lebih kuat, gunakan secure assessment browser, kiosk mode, managed device, atau native application yang mendukung OS-level screenshot protection.
>
> Untuk keamanan bank soal, jangan mengirim seluruh bank soal sekaligus apabila tidak diperlukan. Pertimbangkan pengiriman satu pertanyaan pada satu waktu melalui authenticated assessment session.
>
> Setiap upaya copy, print, tab switch, fullscreen exit, atau security event relevan harus dapat dicatat sebagai audit event tanpa otomatis menyimpulkan kecurangan.
>
> HRD harus dapat melihat raw score D/I/S/C, normalized score, Graph 1, Graph 2, Graph 3, combined graph, profile DISC, dan interpretation.
>
> Grafik hanya merupakan visualisasi hasil deterministic scoring engine. Frontend tidak boleh melakukan perhitungan scoring.
>
> HRD harus dapat melihat answer review beserta mapping Most/Least apabila memiliki permission.
>
> Hasil assessment yang sudah completed bersifat immutable.
>
> Semua perubahan terhadap bank soal, scoring key, normalization table, dan profile rules harus tercatat dalam audit log.

---

# 90. Updated System Architecture

```text
                    ┌──────────────────┐
                    │     SUPER ADMIN  │
                    └─────────┬────────┘
                              │
                    ┌─────────▼────────┐
                    │       HRD        │
                    └─────────┬────────┘
                              │
             ┌────────────────┼────────────────┐
             │                │                │
             ▼                ▼                ▼
       QUESTION BANK      ASSESSMENT       RESULTS
             │                │                │
             ▼                ▼                ▼
       Questions          Participants     Graph 1
       Options            Sessions         Graph 2
       Most Key           Security         Graph 3
       Least Key          Monitoring       Profile
       Versions                             Report
             │
             ▼
      DISC MASTER DATA
             │
             ▼
       SCORING ENGINE
             │
       ┌─────┴─────┐
       │           │
       ▼           ▼
 RAW SCORING   NORMALIZATION
       │           │
       └─────┬─────┘
             ▼
     PROFILE CLASSIFIER
             │
             ▼
         DISC RESULT
             │
       ┌─────┴─────┐
       │           │
       ▼           ▼
 HRD DASHBOARD   REPORT
```

Participant side:

```text
PARTICIPANT
     │
     ▼
SECURE ASSESSMENT SESSION
     │
     ▼
CURRENT QUESTION
     │
     ├── Watermark
     ├── No Copy
     ├── No Text Selection
     ├── Focus Monitoring
     ├── Fullscreen Monitoring
     └── Audit Events
     │
     ▼
ANSWER
     │
     ▼
SERVER AUTOSAVE
     │
     ▼
NEXT QUESTION
     │
     ▼
24 QUESTIONS COMPLETE
     │
     ▼
SUBMIT
     │
     ▼
SERVER SCORING
```

---

# 91. Updated Definition of Done

Selain requirement scoring sebelumnya, sistem belum dianggap selesai jika:

- [ ] HRD memiliki Question Bank.
- [ ] HRD dapat membuat soal.
- [ ] HRD dapat membuat 4 option.
- [ ] HRD dapat menentukan Most mapping.
- [ ] HRD dapat menentukan Least mapping.
- [ ] Question Bank mempunyai versioning.
- [ ] Question Bank mempunyai Draft/Published state.
- [ ] Question snapshot tersedia.
- [ ] Scoring snapshot tersedia.
- [ ] Participant tidak menerima scoring key.
- [ ] API question dilindungi session authentication.
- [ ] Text selection assessment dibatasi.
- [ ] Copy/context menu dibatasi.
- [ ] Dynamic participant watermark tersedia.
- [ ] Fullscreen monitoring tersedia.
- [ ] Tab/window monitoring tersedia.
- [ ] Security events tersimpan.
- [ ] Autosave jawaban tersedia.
- [ ] Randomization tetap konsisten setelah reload.
- [ ] HRD dapat melihat answer review.
- [ ] HRD dapat melihat raw D/I/S/C.
- [ ] HRD dapat melihat normalized D/I/S/C.
- [ ] HRD dapat melihat Graph 1.
- [ ] HRD dapat melihat Graph 2.
- [ ] HRD dapat melihat Graph 3.
- [ ] HRD dapat melihat combined graph.
- [ ] HRD dapat melihat profile.
- [ ] HRD dapat melihat interpretation.
- [ ] HRD dapat export result.
- [ ] Permission diperiksa server-side.
- [ ] Audit log tersedia.
- [ ] Completed assessment immutable.
- [ ] Golden Test terhadap workbook Excel lulus.

---

# 92. Final Development Principle

Prioritas sistem:

```text
1. SCORING ACCURACY

2. MASTER DATA INTEGRITY

3. SECURITY

4. AUDITABILITY

5. QUESTION BANK PROTECTION

6. RESULT REPRODUCIBILITY

7. HRD USABILITY

8. PARTICIPANT EXPERIENCE

9. REPORTING

10. AI INTERPRETATION
```

Prinsip akhir:

> **HRD mengendalikan bank soal, kunci scoring, assessment, dan hasil. Peserta hanya menerima konten soal yang diperlukan untuk mengerjakan assessment. Scoring harus sepenuhnya deterministic di backend. Proteksi soal menggunakan defense-in-depth: access control, controlled delivery, anti-copy, watermark, monitoring, randomization, dan auditability. Screenshot pada browser tidak boleh diklaim dapat dicegah 100%, sehingga sistem harus mengutamakan deterrence dan traceability.**