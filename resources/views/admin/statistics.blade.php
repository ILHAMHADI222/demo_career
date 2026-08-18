<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Statistik</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <h1>Statistik Aplikasi</h1>
        <div class="statistics">
            <div class="statistic-item">
                <h2>Total Lowongan</h2>
                <p>{{ $totalJobs }}</p>
            </div>
            <div class="statistic-item">
                <h2>Total Kandidat</h2>
                <p>{{ $totalCandidates }}</p>
            </div>
            <div class="statistic-item">
                <h2>Total Aplikasi</h2>
                <p>{{ $totalApplications }}</p>
            </div>
        </div>
    </div>
</body>
</html>