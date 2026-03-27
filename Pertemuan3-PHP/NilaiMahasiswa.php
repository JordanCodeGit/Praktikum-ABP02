<?php

// =============================================
//  DATA MAHASISWA (Array Asosiatif)
// =============================================
$mahasiswa = [
    [
        "nama"        => "Jordan",
        "nim"         => "2311102139",
        "nilai_tugas" => 85,
        "nilai_uts"   => 78,
        "nilai_uas"   => 80,
    ],
    [
        "nama"        => "Angkawijaya",
        "nim"         => "2311102140",
        "nilai_tugas" => 60,
        "nilai_uts"   => 55,
        "nilai_uas"   => 50,
    ],
    [
        "nama"        => "Jong",
        "nim"         => "2311102141",
        "nilai_tugas" => 95,
        "nilai_uts"   => 92,
        "nilai_uas"   => 97,
    ],
    [
        "nama"        => "Jordan Jong",
        "nim"         => "2311102142",
        "nilai_tugas" => 72,
        "nilai_uts"   => 68,
        "nilai_uas"   => 74,
    ],
    [
        "nama"        => "Jong Jordan",
        "nim"         => "2311102143",
        "nilai_tugas" => 40,
        "nilai_uts"   => 45,
        "nilai_uas"   => 38,
    ],
];

// =============================================
//  FUNCTION: Hitung Nilai Akhir
//  Dynamic weighting with default values
// =============================================
function hitungNilaiAkhir($tugas, $uts, $uas, $bobotTugas = 0.30, $bobotUts = 0.35, $bobotUas = 0.35) {
    return ((float)$tugas * $bobotTugas) + ((float)$uts * $bobotUts) + ((float)$uas * $bobotUas);
}

// =============================================
//  FUNCTION: Tentukan Grade
// =============================================
function tentukanGrade($nilai) {
    if ($nilai >= 85) {
        return "A";
    } elseif ($nilai >= 75) {
        return "B";
    } elseif ($nilai >= 65) {
        return "C";
    } elseif ($nilai >= 55) {
        return "D";
    } else {
        return "E";
    }
}

// =============================================
//  FUNCTION: Status Kelulusan
// =============================================
function statusKelulusan($nilai) {
    return ($nilai >= 60) ? "Lulus" : "Tidak Lulus";
}

// =============================================
//  PROSES DATA (Loop)
// =============================================
$totalNilai         = 0;
$nilaiTertinggi     = 0;
$mahasiswaTertinggi = []; // Array to handle potential ties

foreach ($mahasiswa as &$mhs) {
    $na = hitungNilaiAkhir($mhs["nilai_tugas"], $mhs["nilai_uts"], $mhs["nilai_uas"]);
    $mhs["nilai_akhir"] = round($na, 2);
    $mhs["grade"]       = tentukanGrade($na);
    $mhs["status"]      = statusKelulusan($na);

    $totalNilai += $na;

    // Logic to capture highest score and handle ties
    if ($na > $nilaiTertinggi) {
        $nilaiTertinggi = $na;
        $mahasiswaTertinggi = [$mhs["nama"]];
    } elseif ($na == $nilaiTertinggi) {
        $mahasiswaTertinggi[] = $mhs["nama"];
    }
}
unset($mhs);

$jumlahMahasiswa  = count($mahasiswa);
// Prevent Division by Zero Error
$rataRataKelas    = ($jumlahMahasiswa > 0) ? round($totalNilai / $jumlahMahasiswa, 2) : 0;
$nilaiTertinggi   = round($nilaiTertinggi, 2);
$namaTertinggiStr = implode(", ", $mahasiswaTertinggi);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Penilaian Mahasiswa</title>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:        #0b0f1a;
            --surface:   #131929;
            --border:    #1e2d45;
            --accent:    #3b82f6;
            --accent2:   #06b6d4;
            --gold:      #f59e0b;
            --green:     #10b981;
            --red:       #ef4444;
            --text:      #e2e8f0;
            --muted:     #64748b;
            --radius:    12px;
        }

        body {
            font-family: 'Sora', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            padding: 40px 20px;
            background-image:
                radial-gradient(ellipse 60% 40% at 80% 10%, rgba(59,130,246,.12) 0%, transparent 60%),
                radial-gradient(ellipse 40% 30% at 10% 80%, rgba(6,182,212,.08) 0%, transparent 60%);
        }

        .container { max-width: 1100px; margin: 0 auto; }

        /* ── Header ── */
        header { text-align: center; margin-bottom: 48px; }
        header .badge {
            display: inline-block;
            font-family: 'JetBrains Mono', monospace;
            font-size: .72rem;
            letter-spacing: .15em;
            color: var(--accent2);
            border: 1px solid rgba(6,182,212,.35);
            border-radius: 999px;
            padding: 4px 16px;
            margin-bottom: 16px;
            text-transform: uppercase;
        }
        header h1 {
            font-size: clamp(1.8rem, 4vw, 2.8rem);
            font-weight: 700;
            line-height: 1.15;
            background: linear-gradient(135deg, #e2e8f0 30%, var(--accent2));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        header p {
            margin-top: 10px;
            color: var(--muted);
            font-size: .9rem;
        }

        /* ── Stat Cards ── */
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 36px;
        }
        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px 24px;
            position: relative;
            overflow: hidden;
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
        }
        .stat-card.blue::before  { background: linear-gradient(90deg, var(--accent), var(--accent2)); }
        .stat-card.gold::before  { background: linear-gradient(90deg, var(--gold), #f97316); }
        .stat-card.green::before { background: linear-gradient(90deg, var(--green), #34d399); }

        .stat-label {
            font-size: .75rem;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: var(--muted);
            margin-bottom: 8px;
        }
        .stat-value {
            font-family: 'JetBrains Mono', monospace;
            font-size: 2rem;
            font-weight: 600;
            line-height: 1;
        }
        .stat-card.blue  .stat-value { color: var(--accent2); }
        .stat-card.gold  .stat-value { color: var(--gold); }
        .stat-card.green .stat-value { color: var(--green); }

        .stat-sub {
            margin-top: 6px;
            font-size: .8rem;
            color: var(--muted);
        }

        /* ── Table ── */
        .table-wrapper {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
        }
        .table-header {
            padding: 18px 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .table-header span {
            font-size: .8rem;
            color: var(--muted);
        }
        .dot { width: 8px; height: 8px; border-radius: 50%; }
        .dot-blue  { background: var(--accent); }
        .dot-cyan  { background: var(--accent2); }
        .dot-green { background: var(--green); }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: .88rem;
        }
        thead th {
            padding: 12px 20px;
            text-align: left;
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: var(--muted);
            background: rgba(255,255,255,.02);
            border-bottom: 1px solid var(--border);
        }
        thead th.center { text-align: center; }

        tbody tr {
            border-bottom: 1px solid rgba(30,45,69,.6);
            transition: background .15s;
        }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: rgba(59,130,246,.05); }

        td { padding: 14px 20px; vertical-align: middle; }
        td.center { text-align: center; }

        .nim-code {
            font-family: 'JetBrains Mono', monospace;
            font-size: .78rem;
            color: var(--muted);
        }
        .nama-text { font-weight: 600; }

        .score-val {
            font-family: 'JetBrains Mono', monospace;
            font-size: .92rem;
        }

        /* Grade badges */
        .grade-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px; height: 32px;
            border-radius: 8px;
            font-family: 'JetBrains Mono', monospace;
            font-size: .9rem;
            font-weight: 600;
        }
        .g-A { background: rgba(16,185,129,.15); color: #34d399; border: 1px solid rgba(16,185,129,.3); }
        .g-B { background: rgba(59,130,246,.15);  color: #60a5fa; border: 1px solid rgba(59,130,246,.3); }
        .g-C { background: rgba(245,158,11,.15);  color: #fbbf24; border: 1px solid rgba(245,158,11,.3); }
        .g-D { background: rgba(249,115,22,.15);  color: #fb923c; border: 1px solid rgba(249,115,22,.3); }
        .g-E { background: rgba(239,68,68,.15);   color: #f87171; border: 1px solid rgba(239,68,68,.3); }

        /* Status */
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 999px;
            font-size: .78rem;
            font-weight: 600;
        }
        .status-pill::before { content:''; width:6px; height:6px; border-radius:50%; }
        .lulus     { background:rgba(16,185,129,.12); color:#34d399; border:1px solid rgba(16,185,129,.25); }
        .lulus::before     { background: #34d399; }
        .tidak-lulus { background:rgba(239,68,68,.12); color:#f87171; border:1px solid rgba(239,68,68,.25); }
        .tidak-lulus::before { background:#f87171; }

        /* ── Keterangan Bobot ── */
        .keterangan {
            margin-top: 24px;
            padding: 16px 20px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            font-size: .8rem;
            color: var(--muted);
        }
        .keterangan strong { color: var(--text); }

        footer {
            margin-top: 40px;
            text-align: center;
            font-size: .78rem;
            color: var(--muted);
        }
    </style>
</head>
<body>
<div class="container">

    <header>
        <div class="badge">Made by &middot; Jordan Angkawijaya | 2311102139</div>
        <h1>Rekap Penilaian Mahasiswa</h1>
        <p>Perhitungan nilai akhir, grade, dan status kelulusan</p>
    </header>

    <div class="stats">
        <div class="stat-card blue">
            <div class="stat-label">Total Mahasiswa</div>
            <div class="stat-value"><?= $jumlahMahasiswa ?></div>
            <div class="stat-sub">Terdaftar di kelas ini</div>
        </div>
        <div class="stat-card gold">
            <div class="stat-label">Rata-rata Kelas</div>
            <div class="stat-value"><?= $rataRataKelas ?></div>
            <div class="stat-sub">Grade <?= tentukanGrade($rataRataKelas) ?></div>
        </div>
        <div class="stat-card green">
            <div class="stat-label">Nilai Tertinggi</div>
            <div class="stat-value"><?= $nilaiTertinggi ?></div>
            <div class="stat-sub"><?= htmlspecialchars($namaTertinggiStr) ?></div>
        </div>
    </div>

    <div class="table-wrapper">
        <div class="table-header">
            <div class="dot dot-blue"></div>
            <div class="dot dot-cyan"></div>
            <div class="dot dot-green"></div>
            <span>&nbsp;Data Nilai Seluruh Mahasiswa</span>
        </div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Mahasiswa</th>
                    <th class="center">Tugas</th>
                    <th class="center">UTS</th>
                    <th class="center">UAS</th>
                    <th class="center">Nilai Akhir</th>
                    <th class="center">Grade</th>
                    <th class="center">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Loop untuk menampilkan seluruh data
                foreach ($mahasiswa as $no => $mhs):
                    $statusClass = ($mhs["status"] === "Lulus") ? "lulus" : "tidak-lulus";
                ?>
                <tr>
                    <td class="nim-code"><?= $no + 1 ?></td>
                    <td>
                        <div class="nama-text"><?= htmlspecialchars($mhs["nama"]) ?></div>
                        <div class="nim-code"><?= htmlspecialchars($mhs["nim"]) ?></div>
                    </td>
                    <td class="center score-val"><?= $mhs["nilai_tugas"] ?></td>
                    <td class="center score-val"><?= $mhs["nilai_uts"] ?></td>
                    <td class="center score-val"><?= $mhs["nilai_uas"] ?></td>
                    <td class="center">
                        <strong class="score-val"><?= $mhs["nilai_akhir"] ?></strong>
                    </td>
                    <td class="center">
                        <span class="grade-badge g-<?= $mhs["grade"] ?>">
                            <?= $mhs["grade"] ?>
                        </span>
                    </td>
                    <td class="center">
                        <span class="status-pill <?= $statusClass ?>">
                            <?= $mhs["status"] ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="keterangan">
        <span><strong>Bobot:</strong> Tugas 30% &bull; UTS 35% &bull; UAS 35%</span>
        <span><strong>Grade:</strong> A &ge;85 &bull; B &ge;75 &bull; C &ge;65 &bull; D &ge;55 &bull; E &lt;55</span>
        <span><strong>Kelulusan:</strong> Nilai Akhir &ge; 60</span>
    </div>

    <footer>
        &copy; <?= date("Y") ?> Sistem Penilaian Akademik &mdash; Developed by Jordan Angkawijaya (2311102139)
    </footer>

</div>
</body>
</html>