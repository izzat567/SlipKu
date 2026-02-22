<?php
// result_gred.php — VERSI AKHIR (SlipKu SK Rantau Panjang)
session_start();

if (!isset($_SESSION['result_data'])) {
    header("Location: form_student_gred.php");
    exit();
}

$data     = $_SESSION['result_data'];
$student  = $data['student'];
$exam     = $data['exam'];
$subjects = $data['subjects'];
$summary  = $data['summary'];
$gen_at   = $data['generated_at'] ?? date('d/m/Y H:i:s');

$bulan_ms = ['','Januari','Februari','Mac','April','Mei','Jun','Julai','Ogos','September','Oktober','November','Disember'];
$tarikh_cetak = date('j') . ' ' . $bulan_ms[(int)date('n')] . ' ' . date('Y');

function fmtTarikh($t) {
    if (empty($t)) return '-';
    $ts = strtotime($t);
    return $ts ? date('d/m/Y', $ts) : $t;
}

// Warna untuk ulasan prestasi (hex)
$success_hex = '#10b981';
$info_hex    = '#3b82f6';
$warning_hex = '#f59e0b';
$danger_hex  = '#ef4444';
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.5, user-scalable=yes">
    <title>Slip Keputusan — <?= htmlspecialchars($student['nama']) ?></title>
    <!-- PDF Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --primary-light: #eef2ff;
            --secondary: #7c3aed;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
            --dark-gray: #1f2937;
            --medium-gray: #6b7280;
            --light-gray: #f9fafb;
            --white: #ffffff;
            --border-radius: 24px;
            --card-radius: 20px;
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: linear-gradient(145deg, #f0f5ff 0%, #e6edf7 100%);
            min-height: 100vh;
            padding: 24px 16px;
            color: var(--dark-gray);
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Action Bar */
        .action-bar {
            max-width: 1000px;
            width: 100%;
            margin: 0 auto 24px;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            justify-content: center;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 28px;
            border: none;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
            text-decoration: none;
            color: white;
            font-family: 'Inter', sans-serif;
        }
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
        }
        .btn-pdf   { background: var(--danger); }
        .btn-wa    { background: #25D366; }
        .btn-email { background: var(--info); }
        .btn-back  { background: var(--medium-gray); }

        /* Slip Container */
        .slip {
            max-width: 1000px;
            width: 100%;
            background: var(--white);
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
        }

        /* Header Slip */
        .slip-header {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
            color: white;
            padding: 32px 36px;
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
            border-bottom: 4px solid var(--primary-light);
            position: relative;
            overflow: hidden;
        }
        .slip-header::before {
            content: "🏫";
            position: absolute;
            right: 20px;
            bottom: -10px;
            font-size: 100px;
            opacity: 0.1;
            transform: rotate(-10deg);
            pointer-events: none;
        }
        .logo-circle {
            width: 80px;
            height: 80px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            border: 3px solid rgba(255,255,255,0.4);
            backdrop-filter: blur(4px);
            flex-shrink: 0;
        }
        .school-info h1 {
            font-size: 1.8rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            margin-bottom: 4px;
        }
        .school-info h2 {
            font-size: 1.2rem;
            font-weight: 600;
            opacity: 0.9;
            margin-bottom: 2px;
        }
        .school-info p {
            font-size: 0.9rem;
            opacity: 0.85;
        }
        .header-badge {
            margin-left: auto;
            text-align: right;
            flex-shrink: 0;
            background: rgba(255,255,255,0.1);
            padding: 12px 24px;
            border-radius: 60px;
            backdrop-filter: blur(4px);
            border: 1px solid rgba(255,255,255,0.2);
        }
        .badge-pill {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: block;
            margin-bottom: 4px;
            opacity: 0.9;
        }
        .exam-title {
            font-size: 1.4rem;
            font-weight: 700;
        }

        /* Info Pelajar (Kad dengan grid 2 lajur) */
        .info-card {
            background: var(--light-gray);
            margin: 24px 28px;
            padding: 28px;
            border-radius: var(--card-radius);
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), inset 0 2px 4px rgba(255,255,255,0.8);
            border: 1px solid rgba(255,255,255,0.8);
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px 24px;
        }
        .info-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 12px;
            background: white;
            border-radius: 14px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            transition: var(--transition);
        }
        .info-item:hover {
            transform: translateX(4px);
            box-shadow: 0 4px 6px -1px rgba(79,70,229,0.2);
        }
        .info-icon {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, var(--primary-light), white);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 18px;
            flex-shrink: 0;
        }
        .info-content {
            flex: 1;
        }
        .info-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--medium-gray);
            font-weight: 600;
            margin-bottom: 2px;
        }
        .info-value {
            font-weight: 700;
            color: var(--dark-gray);
            font-size: 0.95rem;
            word-break: break-word;
        }

        /* Jadual */
        .table-section {
            padding: 0 28px 20px;
        }
        .section-title {
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--primary);
            font-weight: 700;
            margin: 24px 0 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .section-title i {
            font-size: 1.2rem;
            background: var(--primary-light);
            padding: 8px;
            border-radius: 12px;
            color: var(--primary);
        }
        .section-title::after {
            content: '';
            flex: 1;
            height: 2px;
            background: linear-gradient(90deg, var(--primary-light), transparent);
        }
        .table-responsive {
            overflow-x: auto;
            border-radius: 16px;
            border: 1px solid #e5e7eb;
            background: white;
        }
        table.gred-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
            min-width: 600px;
        }
        table.gred-table thead tr {
            background: var(--primary-dark);
            color: white;
        }
        table.gred-table th {
            padding: 16px 16px;
            font-weight: 600;
            font-size: 0.85rem;
            text-align: left;
        }
        table.gred-table td {
            padding: 14px 16px;
            border-bottom: 1px solid #e5e7eb;
        }
        table.gred-table tbody tr:hover {
            background: var(--primary-light);
        }

        /* Gred Badges */
        .gred-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 0.8rem;
            min-width: 48px;
            text-align: center;
            color: white;
            text-shadow: 0 1px 2px rgba(0,0,0,0.2);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .gA-plus, .gA { background: #1B5E20; }
        .gA-min       { background: #2E7D32; }
        .gB-plus, .gB { background: #1565C0; }
        .gC-plus, .gC { background: #F9A825; color: #000; text-shadow: none; }
        .gD           { background: #EF6C00; }
        .gE           { background: #C62828; }
        .gF           { background: #8B0000; }

        .text-right { text-align: right; }
        .text-center { text-align: center; }

        /* Ringkasan Cards - Lebih menarik */
        .summary-section {
            padding: 0 28px 28px;
        }
        .summary-cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-top: 16px;
        }
        .sum-card {
            background: white;
            border-radius: 16px;
            padding: 22px 12px;
            text-align: center;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            border: 1px solid #e5e7eb;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }
        .sum-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }
        .sum-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(79,70,229,0.3);
        }
        .sum-card.highlight {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            color: white;
            border: none;
        }
        .sum-card.highlight::before {
            background: white;
        }
        .sum-card .value {
            font-size: 2rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 6px;
        }
        .sum-card .label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--medium-gray);
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }
        .sum-card.highlight .label {
            color: rgba(255,255,255,0.9);
        }

        /* Ulasan Prestasi */
        .remark-card {
            margin: 8px 28px 28px;
            padding: 20px 28px;
            border-radius: 60px 20px 20px 60px;
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
            background: #ecfdf5;
            border-left: 8px solid var(--success);
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }
        .remark-icon {
            font-size: 2.5rem;
            background: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .remark-text p {
            font-weight: 700;
            font-size: 1rem;
        }
        .remark-text small {
            font-size: 0.8rem;
            opacity: 0.8;
            display: block;
            margin-top: 4px;
        }

        /* Tandatangan */
        .signature-row {
            display: flex;
            justify-content: space-between;
            padding: 20px 28px 32px;
            gap: 20px;
            flex-wrap: wrap;
        }
        .signature-box {
            flex: 1;
            min-width: 160px;
            text-align: center;
            background: var(--light-gray);
            padding: 16px 12px 12px;
            border-radius: 20px;
        }
        .signature-line {
            border-bottom: 2px dashed var(--medium-gray);
            margin-bottom: 10px;
            padding-bottom: 40px;
        }
        .signature-box p {
            font-size: 0.75rem;
            color: var(--medium-gray);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        /* Footer */
        .slip-footer {
            background: var(--primary-dark);
            color: rgba(255,255,255,0.7);
            padding: 18px 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            font-size: 0.75rem;
        }
        .watermark {
            font-weight: 700;
            color: rgba(255,255,255,0.9);
        }

        /* Modal (sama) */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(5px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }
        .modal-overlay.open { display: flex; }
        .modal-box {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 32px;
            max-width: 430px;
            width: 90%;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
        }
        .modal-box h3 {
            font-size: 1.5rem;
            margin-bottom: 12px;
            color: var(--primary-dark);
        }
        .modal-box p {
            font-size: 0.9rem;
            color: var(--medium-gray);
            margin-bottom: 20px;
        }
        .modal-input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            font-size: 1rem;
            margin-bottom: 20px;
        }
        .modal-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }
        .btn-sm {
            padding: 10px 20px;
            font-size: 0.9rem;
            border-radius: 40px;
        }

        /* Responsive */
        @media (max-width: 800px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
            .summary-cards {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 600px) {
            .info-card {
                margin: 16px;
                padding: 18px;
            }
            .info-item {
                padding: 6px 10px;
            }
            .info-icon {
                width: 36px;
                height: 36px;
                font-size: 16px;
            }
            .summary-cards {
                grid-template-columns: 1fr;
            }
            .remark-card {
                border-radius: 20px;
                flex-direction: column;
                text-align: center;
                margin: 8px 16px;
            }
            .action-bar .btn {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .slip-header {
                padding: 20px;
                flex-direction: column;
                text-align: center;
            }
            .header-badge {
                margin-left: 0;
                width: 100%;
                text-align: center;
            }
            .signature-row {
                flex-direction: column;
                gap: 16px;
            }
        }

        /* Print style */
        @media print {
            .action-bar, .modal-overlay {
                display: none !important;
            }
            body {
                background: white;
                padding: 0;
            }
            .slip {
                box-shadow: none;
                border-radius: 0;
            }
            .info-item {
                break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <!-- Action Bar -->
    <div class="action-bar">
        <button class="btn btn-pdf"   onclick="downloadPDF()"><i class="fas fa-file-pdf"></i> Download PDF</button>
        <button class="btn btn-wa"    onclick="openModal('modal-wa')"><i class="fab fa-whatsapp"></i> Hantar WhatsApp</button>
        <button class="btn btn-email" onclick="openModal('modal-email')"><i class="fas fa-envelope"></i> Hantar Email</button>
        <a href="form_student_gred.php" class="btn btn-back"><i class="fas fa-arrow-left"></i> Semak Lain</a>
    </div>

    <!-- Slip Keputusan -->
    <div class="slip" id="slip-cetak">
        <!-- Header dengan nama sekolah -->
        <div class="slip-header">
            <div class="logo-circle"><i class="fas fa-graduation-cap"></i></div>
            <div class="school-info">
                <h1>SEKOLAH KEBANGSAAN RANTAU PANJANG</h1>
                <h2>Sistem Pengurusan Slip Peperiksaan — SlipKu</h2>
                <p>Tahun Akademik <?= htmlspecialchars($exam['tahun_akademik']) ?></p>
            </div>
            <div class="header-badge">
                <span class="badge-pill"><i class="fas fa-certificate"></i> Slip Keputusan</span>
                <div class="exam-title"><?= htmlspecialchars($exam['nama_peperiksaan']) ?></div>
            </div>
        </div>

        <!-- Info Pelajar (Grid 2 Lajur) -->
        <div class="info-card">
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-user-graduate"></i></div>
                    <div class="info-content">
                        <div class="info-label">Nama Pelajar</div>
                        <div class="info-value"><?= htmlspecialchars($student['nama']) ?></div>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-users"></i></div>
                    <div class="info-content">
                        <div class="info-label">Kelas</div>
                        <div class="info-value"><?= htmlspecialchars($student['nama_kelas']) ?></div>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-id-card"></i></div>
                    <div class="info-content">
                        <div class="info-label">No. Kad Pengenalan</div>
                        <div class="info-value"><?= htmlspecialchars($student['no_kp']) ?></div>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-pen"></i></div>
                    <div class="info-content">
                        <div class="info-label">Jenis Peperiksaan</div>
                        <div class="info-value"><?= htmlspecialchars($exam['jenis'] ?: $exam['nama_peperiksaan']) ?></div>
                    </div>
                </div>
                <?php if (!empty($exam['tarikh_mula'])): ?>
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-calendar-alt"></i></div>
                    <div class="info-content">
                        <div class="info-label">Tarikh Peperiksaan</div>
                        <div class="info-value"><?= fmtTarikh($exam['tarikh_mula']) ?><?= !empty($exam['tarikh_tamat']) ? ' – '.fmtTarikh($exam['tarikh_tamat']) : '' ?></div>
                    </div>
                </div>
                <?php endif; ?>
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-print"></i></div>
                    <div class="info-content">
                        <div class="info-label">Tarikh Cetak</div>
                        <div class="info-value"><?= $tarikh_cetak ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jadual Mata Pelajaran -->
        <div class="table-section">
            <div class="section-title"><i class="fas fa-clipboard-list"></i> Keputusan Mata Pelajaran</div>
            <div class="table-responsive">
                <table class="gred-table">
                    <thead>
                        <tr>
                            <th style="width:40px">Bil</th>
                            <th style="width:70px">Kod</th>
                            <th>Mata Pelajaran</th>
                            <th style="width:90px" class="text-right">Markah</th>
                            <th style="width:80px" class="text-center">Gred</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($subjects as $i => $s):
                        $gred = $s['markah']['gred'];
                        if ($gred === 'A+') $gc = 'gA-plus';
                        elseif ($gred === 'A') $gc = 'gA';
                        elseif ($gred === 'A-') $gc = 'gA-min';
                        elseif ($gred === 'B+') $gc = 'gB-plus';
                        elseif ($gred === 'B') $gc = 'gB';
                        elseif ($gred === 'C+') $gc = 'gC-plus';
                        elseif ($gred === 'C') $gc = 'gC';
                        elseif ($gred === 'D') $gc = 'gD';
                        elseif ($gred === 'E') $gc = 'gE';
                        else $gc = 'gF';
                    ?>
                        <tr>
                            <td class="text-center" style="color: var(--medium-gray);"><?= $i+1 ?></td>
                            <td><strong><?= htmlspecialchars($s['matapelajaran']['kod']) ?></strong></td>
                            <td><?= htmlspecialchars($s['matapelajaran']['nama']) ?></td>
                            <td class="text-right"><strong><?= $s['markah']['markah'] ?></strong></td>
                            <td class="text-center"><span class="gred-badge <?= $gc ?>"><?= htmlspecialchars($gred) ?></span></td>
                            <td><?= htmlspecialchars($s['markah']['catatan']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Ringkasan -->
        <div class="summary-section">
            <div class="section-title"><i class="fas fa-chart-pie"></i> Ringkasan Keputusan</div>
            <div class="summary-cards">
                <div class="sum-card highlight"><div class="value"><?= htmlspecialchars($summary['average_grade']) ?></div><div class="label"><i class="fas fa-star"></i> Purata Gred</div></div>
                <div class="sum-card"><div class="value"><?= number_format($summary['gpa'],2) ?></div><div class="label"><i class="fas fa-calculator"></i> GPA</div></div>
                <div class="sum-card"><div class="value"><?= $summary['avg_markah'] ?? '-' ?></div><div class="label"><i class="fas fa-percent"></i> Purata Markah</div></div>
                <div class="sum-card"><div class="value"><?= $summary['rank']==='N/A' ? '-' : $summary['rank'] ?></div><div class="label"><i class="fas fa-trophy"></i> Kedudukan Kelas</div></div>
            </div>
        </div>

        <!-- Ulasan Prestasi -->
        <?php
        $g = $summary['gpa'];
        if ($g>=3.67)     {$rc='#ecfdf5';$rb=$success_hex;$rt='#065f46';$icon='🌟';$rm='Tahniah! Prestasi cemerlang. Teruskan usaha!';}
        elseif ($g>=3.00) {$rc='#eff6ff';$rb=$info_hex;$rt='#1e40af';$icon='👍';$rm='Prestasi baik. Tingkatkan lagi usaha anda!';}
        elseif ($g>=2.00) {$rc='#fffbeb';$rb=$warning_hex;$rt='#92400e';$icon='📚';$rm='Prestasi memuaskan. Perlukan lebih latihan.';}
        else              {$rc='#fef2f2';$rb=$danger_hex;$rt='#991b1b';$icon='⚠️';$rm='Perlu peningkatan segera. Berjumpa guru anda.';}
        ?>
        <div class="remark-card" style="background:<?=$rc?>; border-left-color:<?=$rb?>;">
            <div class="remark-icon"><?= $icon ?></div>
            <div class="remark-text">
                <p style="color:<?=$rt?>"><?= $rm ?></p>
                <small style="color:<?=$rt?>">Lulus: <strong><?= $summary['lulus']??'-' ?></strong> | Gagal: <strong><?= $summary['gagal']??0 ?></strong> | Jumlah: <strong><?= $summary['total_subjects'] ?></strong></small>
            </div>
        </div>

        <!-- Tandatangan -->
        <div class="signature-row">
            <div class="signature-box">
                <div class="signature-line"></div>
                <p><i class="fas fa-pen"></i> Ibu Bapa / Penjaga</p>
            </div>
            <div class="signature-box">
                <div class="signature-line"></div>
                <p><i class="fas fa-chalkboard-teacher"></i> Guru Kelas</p>
            </div>
            <div class="signature-box">
                <div class="signature-line"></div>
                <p><i class="fas fa-stamp"></i> Pengetua</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="slip-footer">
            <span><i class="fas fa-clock"></i> Dicetak: <?= $gen_at ?></span>
            <span class="watermark"><i class="fas fa-bolt"></i> SlipKu — SK Rantau Panjang</span>
            <span><i class="fas fa-shield-alt"></i> Dokumen Rasmi Sekolah</span>
        </div>
    </div>

    <!-- Modal WhatsApp -->
    <div class="modal-overlay" id="modal-wa">
        <div class="modal-box">
            <h3><i class="fab fa-whatsapp"></i> Hantar via WhatsApp</h3>
            <p>Masukkan nombor telefon penerima (contoh: 0123456789)</p>
            <input type="tel" class="modal-input" id="wa-num" placeholder="0123456789" maxlength="15">
            <div class="modal-actions">
                <button class="btn btn-back btn-sm" onclick="closeModal('modal-wa')">Batal</button>
                <button class="btn btn-wa   btn-sm" onclick="sendWA()"><i class="fab fa-whatsapp"></i> Hantar</button>
            </div>
        </div>
    </div>

    <!-- Modal Email -->
    <div class="modal-overlay" id="modal-email">
        <div class="modal-box">
            <h3><i class="fas fa-envelope"></i> Hantar via Email</h3>
            <p>Masukkan alamat emel penerima. Slip PDF akan dimuat turun secara automatik.</p>
            <input type="email" class="modal-input" id="email-addr" placeholder="contoh@email.com">
            <div class="modal-actions">
                <button class="btn btn-back btn-sm" onclick="closeModal('modal-email')">Batal</button>
                <button class="btn btn-email btn-sm" onclick="sendEmail()"><i class="fas fa-envelope"></i> Hantar</button>
            </div>
        </div>
    </div>

    <script>
        // Data dari PHP
        const studentName = <?= json_encode($student['nama']) ?>;
        const studentKelas= <?= json_encode($student['nama_kelas']) ?>;
        const studentKP   = <?= json_encode($student['no_kp']) ?>;
        const examName    = <?= json_encode($exam['nama_peperiksaan']) ?>;
        const avgGrade    = <?= json_encode($summary['average_grade']) ?>;
        const gpa         = <?= json_encode($summary['gpa']) ?>;
        const rank        = <?= json_encode($summary['rank']) ?>;
        const totalSub    = <?= json_encode($summary['total_subjects']) ?>;
        const lulus       = <?= json_encode($summary['lulus'] ?? 0) ?>;
        const genAt       = <?= json_encode($gen_at) ?>;

        function downloadPDF() {
            const bar = document.querySelector('.action-bar');
            bar.style.display = 'none';
            const el = document.getElementById('slip-cetak');
            const fn = 'Slip_' + studentName.replace(/\s+/g,'_') + '_' + examName.replace(/\s+/g,'_') + '.pdf';
            html2pdf().set({
                margin: [6,6,6,6],
                filename: fn,
                image: {type:'jpeg',quality:.98},
                html2canvas: {scale:2,useCORS:true,logging:false},
                jsPDF: {unit:'mm',format:'a4',orientation:'portrait'}
            }).from(el).save().then(() => { bar.style.display = ''; });
        }

        function openModal(id)  { document.getElementById(id).classList.add('open'); }
        function closeModal(id) { document.getElementById(id).classList.remove('open'); }
        document.querySelectorAll('.modal-overlay').forEach(el => {
            el.addEventListener('click', e => { if (e.target===el) el.classList.remove('open'); });
        });

        function sendWA() {
            let num = document.getElementById('wa-num').value.trim().replace(/[\s\-\+]/g,'');
            if (!num || num.length < 9) { alert('Sila masukkan nombor telefon yang sah.'); return; }
            if (num.startsWith('0')) num = '6' + num;
            const msg = '🎓 *SLIP KEPUTUSAN PEPERIKSAAN*\n\n' +
                '👤 Nama: *' + studentName + '*\n' +
                '🏫 Kelas: ' + studentKelas + '\n' +
                '🪪 No. KP: ' + studentKP + '\n' +
                '📝 Peperiksaan: ' + examName + '\n\n' +
                '📊 *Ringkasan:*\n' +
                '• Purata Gred : *' + avgGrade + '*\n' +
                '• GPA         : *' + gpa + '*\n' +
                '• Kedudukan Kelas : ' + rank + '\n' +
                '• Mata Pelajaran Lulus: ' + lulus + '/' + totalSub + '\n\n' +
                '_Dihantar melalui Sistem SlipKu (SK Rantau Panjang)_';
            window.open('https://wa.me/' + num + '?text=' + encodeURIComponent(msg), '_blank');
            closeModal('modal-wa');
            setTimeout(downloadPDF, 600);
        }

        function sendEmail() {
            const em = document.getElementById('email-addr').value.trim();
            if (!em || !em.includes('@')) { alert('Sila masukkan alamat emel yang sah.'); return; }
            const subj = 'Slip Keputusan Peperiksaan — ' + studentName + ' (' + examName + ')';
            const body = 'Salam,\n\nBersama-sama ini dikemukakan ringkasan keputusan peperiksaan pelajar:\n\n' +
                '══════════════════════════════\n' +
                '  SLIP KEPUTUSAN PEPERIKSAAN\n' +
                '  SK RANTAU PANJANG\n' +
                '══════════════════════════════\n' +
                'Nama         : ' + studentName + '\n' +
                'Kelas        : ' + studentKelas + '\n' +
                'No. KP       : ' + studentKP + '\n' +
                'Peperiksaan  : ' + examName + '\n\n' +
                'RINGKASAN KEPUTUSAN:\n' +
                '• Purata Gred          : ' + avgGrade + '\n' +
                '• GPA                  : ' + gpa + '\n' +
                '• Kedudukan Kelas      : ' + rank + '\n' +
                '• Mata Pelajaran Lulus : ' + lulus + '/' + totalSub + '\n\n' +
                'Slip keputusan penuh dalam format PDF telah dimuat turun secara automatik.\n' +
                'Sila lampirkan fail PDF tersebut dalam emel ini.\n\n' +
                'Dicetak: ' + genAt + '\n' +
                '══════════════════════════════\n' +
                'Sistem SlipKu — SK Rantau Panjang\n';
            window.location.href = 'mailto:' + em + '?subject=' + encodeURIComponent(subj) + '&body=' + encodeURIComponent(body);
            closeModal('modal-email');
            setTimeout(downloadPDF, 800);
        }

        document.getElementById('wa-num')?.addEventListener('keypress', e => { if(e.key==='Enter') sendWA(); });
        document.getElementById('email-addr')?.addEventListener('keypress', e => { if(e.key==='Enter') sendEmail(); });
    </script>
</body>
</html>