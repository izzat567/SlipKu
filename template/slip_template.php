<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Keputusan Peperiksaan Akhir Tahun 2026</title>
    <style>
        /* Reset dan dasar */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 14px;
            line-height: 1.5;
            color: #000;
            background-color: #f0f0f0;
            padding: 20px;
        }
        
        /* Kertas A4 dengan watermark sekolah */
        .official-document {
            width: 21cm;
            min-height: 29.7cm;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            position: relative;
            padding: 2cm;
            border: 1px solid #ccc;
            page-break-after: always;
        }
        
        /* Watermark sekolah */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            color: rgba(0, 50, 100, 0.08);
            font-weight: bold;
            white-space: nowrap;
            pointer-events: none;
            z-index: 1;
        }
        
        /* Header dengan lambang sekolah */
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px double #003366;
            padding-bottom: 15px;
            position: relative;
            z-index: 2;
        }
        
        .school-badge {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #003366, #0066cc);
            border-radius: 50%;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
            font-weight: bold;
            border: 3px solid #cc9900;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
        }
        
        .school-badge::before {
            content: "SKBK";
            text-align: center;
            line-height: 1.2;
        }
        
        .document-title {
            font-size: 22px;
            font-weight: bold;
            color: #003366;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 8px;
        }
        
        .school-name {
            font-size: 18px;
            font-weight: bold;
            color: #cc0000;
            margin-bottom: 5px;
        }
        
        .school-address {
            font-size: 14px;
            color: #666;
            font-style: italic;
        }
        
        /* Garis rasmi dengan logo Kementerian Pendidikan */
        .official-line {
            text-align: center;
            margin: 20px 0;
            position: relative;
            z-index: 2;
        }
        
        .official-line::before,
        .official-line::after {
            content: "";
            display: inline-block;
            width: 100px;
            height: 2px;
            background: #003366;
            vertical-align: middle;
            margin: 0 15px;
        }
        
        /* Maklumat murid dalam format rasmi */
        .student-info-section {
            margin: 25px 0;
            padding: 15px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-left: 4px solid #003366;
            position: relative;
            z-index: 2;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        
        .info-table td {
            padding: 8px 10px;
            vertical-align: top;
        }
        
        .info-label {
            font-weight: bold;
            color: #003366;
            width: 180px;
        }
        
        .info-value {
            border-bottom: 1px dotted #999;
            padding-bottom: 3px;
        }
        
        /* Jadual keputusan format rasmi */
        .results-section {
            margin: 30px 0;
            position: relative;
            z-index: 2;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #003366;
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #cc9900;
        }
        
        .results-table {
            width: 100%;
            border-collapse: collapse;
            border: 2px solid #003366;
            margin-top: 10px;
            font-size: 13px;
        }
        
        .results-table thead {
            background-color: #003366;
            color: white;
        }
        
        .results-table th {
            padding: 12px 8px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #002244;
        }
        
        .results-table tbody tr {
            border-bottom: 1px solid #ddd;
        }
        
        .results-table tbody tr:nth-child(even) {
            background-color: #f8f8f8;
        }
        
        .results-table td {
            padding: 10px 8px;
            text-align: center;
            border: 1px solid #ccc;
        }
        
        .subject-cell {
            text-align: left;
            font-weight: bold;
            color: #003366;
        }
        
        .mark-cell {
            font-weight: bold;
            font-size: 14px;
        }
        
        .grade-cell {
            font-weight: bold;
        }
        
        .grade-A {
            color: #006600;
        }
        
        .grade-B {
            color: #cc6600;
        }
        
        .grade-C {
            color: #cc0000;
        }
        
        /* Purata keseluruhan format rasmi */
        .overall-result {
            margin: 25px 0;
            padding: 15px;
            background-color: #eef5ff;
            border: 1px solid #b3d1ff;
            border-left: 5px solid #003366;
            position: relative;
            z-index: 2;
        }
        
        .overall-result-title {
            font-weight: bold;
            color: #003366;
            font-size: 15px;
            margin-bottom: 8px;
        }
        
        .overall-result-value {
            font-size: 24px;
            font-weight: bold;
            color: #cc0000;
            text-align: center;
            padding: 10px;
            background-color: white;
            border: 2px solid #003366;
            border-radius: 5px;
            display: inline-block;
            min-width: 150px;
            margin-top: 10px;
        }
        
        /* Tandatangan rasmi */
        .signature-section {
            margin-top: 60px;
            position: relative;
            z-index: 2;
        }
        
        .signature-container {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
        }
        
        .signature-box {
            text-align: center;
            width: 45%;
        }
        
        .signature-line {
            width: 80%;
            height: 1px;
            background-color: #000;
            margin: 40px auto 10px;
            position: relative;
        }
        
        .signature-line::after {
            content: "";
            position: absolute;
            top: -8px;
            left: 0;
            right: 0;
            height: 16px;
            border-bottom: 1px solid #000;
        }
        
        .signature-title {
            font-weight: bold;
            color: #003366;
            margin-bottom: 5px;
        }
        
        .signature-name {
            font-weight: bold;
            margin-top: 5px;
        }
        
        .signature-stamp {
            color: #cc0000;
            font-style: italic;
            font-size: 12px;
            margin-top: 3px;
        }
        
        /* Cap rasmi sekolah */
        .official-stamp {
            text-align: center;
            margin-top: 30px;
            padding: 10px;
            position: relative;
            z-index: 2;
        }
        
        .stamp-box {
            display: inline-block;
            padding: 15px 30px;
            border: 3px dashed #cc0000;
            border-radius: 10px;
            font-weight: bold;
            color: #003366;
            background-color: rgba(255, 255, 255, 0.9);
        }
        
        /* Footer dengan tarikh dan nombor rujukan */
        .document-footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #ccc;
            text-align: center;
            color: #666;
            font-size: 12px;
            position: relative;
            z-index: 2;
        }
        
        .document-date {
            font-weight: bold;
            color: #003366;
            margin-bottom: 5px;
        }
        
        .document-reference {
            font-style: italic;
            color: #666;
        }
        
        /* Untuk cetakan */
        @media print {
            body {
                background: white;
                padding: 0;
                margin: 0;
            }
            
            .official-document {
                width: 100%;
                min-height: 100vh;
                box-shadow: none;
                border: none;
                padding: 1.5cm;
                margin: 0;
            }
            
            .watermark {
                opacity: 0.1;
            }
            
            .no-print {
                display: none;
            }
        }
        
        /* Nota kaki */
        .footnote {
            font-size: 11px;
            color: #666;
            margin-top: 20px;
            font-style: italic;
            position: relative;
            z-index: 2;
        }
    </style>
</head>
<body>
    <div class="official-document">
        <!-- Watermark rasmi -->
        <div class="watermark">SAH DAN RASMI</div>
        
        <!-- Header dengan lambang sekolah -->
        <div class="header">
            <!-- <div class="school-badge"></div> -->
            <h1 class="document-title">SLIP KEPUTUSAN PEPERIKSAAN AKHIR TAHUN</h1>
            <h2 class="school-name">SEKOLAH KEBANGSAAN RANTAU PANJANG</h2>
            <p class="school-address">Taman Sulu Bestari, 45500 Batang Berjuntai, Selangor</p>
            
            <div class="official-line">
                <span>KEMENTERIAN PENDIDIKAN MALAYSIA</span>
            </div>
        </div>
        
        <!-- Maklumat Murid -->
        <div class="student-info-section">
            <table class="info-table">
                <tr>
                    <td class="info-label">NAMA MURID</td>
                    <td class="info-value">: AHMAD BIN ALI</td>
                    <td class="info-label">KELAS</td>
                    <td class="info-value">: TAHUN 4 AR-RAZI</td>
                </tr>
                <tr>
                    <td class="info-label">NO. MYKID</td>
                    <td class="info-value">: 070310-01-5678</td>
                    <td class="info-label">SESI</td>
                    <td class="info-value">: 2025 / 2026</td>
                </tr>
                <tr>
                    <td class="info-label">ANGKA GILIRAN</td>
                    <td class="info-value">: SKBK/2026/04/0123</td>
                    <td class="info-label">TARIKH LAHIR</td>
                    <td class="info-value">: 10 MAC 2007</td>
                </tr>
            </table>
        </div>
        
        <!-- Keputusan Peperiksaan -->
        <div class="results-section">
            <h3 class="section-title">KEPUTUSAN PEPERIKSAAN AKHIR TAHUN 2026</h3>
            
            <table class="results-table">
                <thead>
                    <tr>
                        <th width="50">BIL.</th>
                        <th>MATA PELAJARAN</th>
                        <th width="120">MARKAH (%)</th>
                        <th width="100">GRED</th>
                        <th width="150">PENCAPAIAN</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1.</td>
                        <td class="subject-cell">BAHASA MELAYU</td>
                        <td class="mark-cell">85</td>
                        <td class="grade-cell grade-A">A</td>
                        <td>CEPEMERLANGAN</td>
                    </tr>
                    <tr>
                        <td>2.</td>
                        <td class="subject-cell">BAHASA INGGERIS</td>
                        <td class="mark-cell">82</td>
                        <td class="grade-cell grade-A">A</td>
                        <td>CEPEMERLANGAN</td>
                    </tr>
                    <tr>
                        <td>3.</td>
                        <td class="subject-cell">MATEMATIK</td>
                        <td class="mark-cell">78</td>
                        <td class="grade-cell grade-B">B</td>
                        <td>KEPUJIAN</td>
                    </tr>
                    <tr>
                        <td>4.</td>
                        <td class="subject-cell">SAINS</td>
                        <td class="mark-cell">88</td>
                        <td class="grade-cell grade-A">A</td>
                        <td>CEPEMERLANGAN</td>
                    </tr>
                    <tr>
                        <td>5.</td>
                        <td class="subject-cell">PENDIDIKAN ISLAM</td>
                        <td class="mark-cell">90</td>
                        <td class="grade-cell grade-A">A</td>
                        <td>CEPEMERLANGAN</td>
                    </tr>
                    <tr>
                        <td>6.</td>
                        <td class="subject-cell">SEJARAH</td>
                        <td class="mark-cell">76</td>
                        <td class="grade-cell grade-B">B</td>
                        <td>KEPUJIAN</td>
                    </tr>
                    <tr>
                        <td>7.</td>
                        <td class="subject-cell">REKA BENTUK DAN TEKNOLOGI</td>
                        <td class="mark-cell">80</td>
                        <td class="grade-cell grade-A">A</td>
                        <td>CEPEMERLANGAN</td>
                    </tr>
                    <tr>
                        <td>8.</td>
                        <td class="subject-cell">PENDIDIKAN JASMANI DAN KESIHATAN</td>
                        <td class="mark-cell">92</td>
                        <td class="grade-cell grade-A">A</td>
                        <td>CEPEMERLANGAN</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Purata Keseluruhan -->
        <div class="overall-result">
            <div class="overall-result-title">PURATA KESELURUHAN MARKAH:</div>
            <div class="overall-result-value">83.7%</div>
            
            <div style="margin-top: 15px;">
                <span class="overall-result-title">KEDUDUKAN DALAM KELAS:</span> 
                <span style="font-weight: bold; color: #003366;">KE-5 DARIPADA 40 MURID</span>
            </div>
            
            <div style="margin-top: 10px;">
                <span class="overall-result-title">PRESTASI KESELURUHAN:</span> 
                <span style="font-weight: bold; color: #006600;">SANGAT MEMUASKAN</span>
            </div>
        </div>
        
        <!-- Tandatangan Rasmi -->
        <div class="signature-section">
            <div class="signature-container">
                <div class="signature-box">
                    <div class="signature-title">TANDATANGAN GURU KELAS</div>
                    <div class="signature-line"></div>
                    <div class="signature-name">PN. SITI NORHALIZA BINTI MOHD ALI</div>
                    <div class="signature-stamp">GURU KELAS TAHUN 4 AR-RAZI</div>
                </div>
                
                <div class="signature-box">
                    <div class="signature-title">TANDATANGAN GURU BESAR</div>
                    <div class="signature-line"></div>
                    <div class="signature-name">EN. MOHD RAZALI BIN ISMAIL</div>
                    <div class="signature-stamp">GURU BESAR SK BUKIT KUCHING</div>
                </div>
            </div>
        </div>
        
        <!-- Cop Rasmi Sekolah -->
        <div class="official-stamp">
            <div class="stamp-box">
                DISAHKAN DAN DIPERAKUI<br>
                SEKOLAH KEBANGSAAN BUKIT KUCHING
            </div>
        </div>
        
        <!-- Footer dokumen -->
        <div class="document-footer">
            <div class="document-date">TARIKH DIKELUARKAN: 15 DISEMBER 2026</div>
            <div class="document-reference">No. Rujukan: SKBK/PA/2026/01234</div>
        </div>
        
        <!-- Nota kaki -->
        <div class="footnote">
            <p>** Slip keputusan ini adalah dokumen rasmi sekolah. Sila simpan untuk tujuan rujukan dan pendaftaran tahun hadapan.</p>
            <p>** Sebarang pertanyaan atau pembetulan hendaklah dimajukan kepada Pejabat Sekolah dalam tempoh 7 hari dari tarikh dikeluarkan.</p>
        </div>
        

</body>
</html>