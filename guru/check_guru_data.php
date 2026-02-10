<?php
// fix_class_data.php
$host = "localhost";
$username = "root";
$password = "danialdev";
$database = "slipku_db";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<h2>Membetulkan Data Kelas dan Sambungan</h2>";

// 1. BETULKAN NAMA KELAS YANG SALAH ("ALPHA", "BETA", "GAMMA")
echo "<h3>1. Membetulkan nama kelas yang tidak betul...</h3>";

$fix_class_names = [
    // Tahun 1
    "UPDATE kelas SET nama = '1 Bijak' WHERE id = 1 AND nama = 'ALPHA' AND tahun = 2026",
    "UPDATE kelas SET nama = '1 Cerdas' WHERE id = 2 AND nama = 'BETA' AND tahun = 2026",
    "UPDATE kelas SET nama = '1 Pintar' WHERE id = 3 AND nama = 'GAMMA' AND tahun = 2026",
    
    // Tahun 2  
    "UPDATE kelas SET nama = '2 Bijak' WHERE id = 4 AND nama = 'ALPHA' AND tahun = 2026",
    "UPDATE kelas SET nama = '2 Cerdas' WHERE id = 5 AND nama = 'BETA' AND tahun = 2026",
    "UPDATE kelas SET nama = '2 Pintar' WHERE id = 6 AND nama = 'GAMMA' AND tahun = 2026",
    
    // Tahun 3
    "UPDATE kelas SET nama = '3 Bijak' WHERE id = 7 AND nama = 'ALPHA' AND tahun = 2026",
    "UPDATE kelas SET nama = '3 Cerdas' WHERE id = 8 AND nama = 'BETA' AND tahun = 2026", 
    "UPDATE kelas SET nama = '3 Pintar' WHERE id = 9 AND nama = 'GAMMA' AND tahun = 2026",
    
    // Tahun 4
    "UPDATE kelas SET nama = '4 Bijak' WHERE id = 10 AND nama = 'ALPHA' AND tahun = 2026",
    "UPDATE kelas SET nama = '4 Cerdas' WHERE id = 11 AND nama = 'BETA' AND tahun = 2026",
    "UPDATE kelas SET nama = '4 Pintar' WHERE id = 12 AND nama = 'GAMMA' AND tahun = 2026",
    
    // Tahun 5
    "UPDATE kelas SET nama = '5 Bijak' WHERE id = 13 AND nama = 'ALPHA' AND tahun = 2026",
    "UPDATE kelas SET nama = '5 Cerdas' WHERE id = 14 AND nama = 'BETA' AND tahun = 2026",
    "UPDATE kelas SET nama = '5 Pintar' WHERE id = 15 AND nama = 'GAMMA' AND tahun = 2026",
    
    // Tahun 6
    "UPDATE kelas SET nama = '6 Bijak' WHERE id = 16 AND nama = 'ALPHA' AND tahun = 2026",
    "UPDATE kelas SET nama = '6 Cerdas' WHERE id = 17 AND nama = 'BETA' AND tahun = 2026",
    "UPDATE kelas SET nama = '6 Pintar' WHERE id = 18 AND nama = 'GAMMA' AND tahun = 2026"
];

foreach ($fix_class_names as $sql) {
    if ($conn->query($sql) === TRUE) {
        if ($conn->affected_rows > 0) {
            echo "✓ " . substr($sql, 20, 40) . "...<br>";
        }
    }
}

// 2. TAMBAH DATA PELAJAR CONTOH
echo "<h3>2. Menambah data pelajar contoh...</h3>";

// Semak jika ada data pelajar
$check_pelajar = $conn->query("SELECT COUNT(*) as total FROM pelajar");
$pelajar_count = $check_pelajar->fetch_assoc()['total'];

if ($pelajar_count == 0) {
    $insert_pelajar = "INSERT INTO pelajar (id_kelas, nama, no_kp, jantina, status) VALUES
        -- Kelas 3 Bijak (id:7)
        (7, 'ALI BIN AHMAD', '010101011234', 'Lelaki', 'aktif'),
        (7, 'SITI BINTI RAHMAN', '020202021234', 'Perempuan', 'aktif'),
        (7, 'AHMAD BIN KASSIM', '030303031234', 'Lelaki', 'aktif'),
        
        -- Kelas 3 Cerdas (id:8)
        (8, 'FATIMAH BINTI HASSAN', '040404041234', 'Perempuan', 'aktif'),
        (8, 'RAHMAN BIN ISMAIL', '050505051234', 'Lelaki', 'aktif'),
        
        -- Kelas 3 Pintar (id:9)
        (9, 'NOR HIDAYAH BINTI OMAR', '060606061234', 'Perempuan', 'aktif'),
        (9, 'ZULKIFLI BIN ABDULLAH', '070707071234', 'Lelaki', 'aktif')";
    
    if ($conn->query($insert_pelajar) === TRUE) {
        echo "✓ Data pelajar contoh ditambah<br>";
    }
} else {
    echo "✓ Database sudah mempunyai $pelajar_count data pelajar<br>";
}

// 3. UPDATE ID_GURU DALAM JADUAL KELAS (assign guru ke kelas)
echo "<h3>3. Menetapkan guru kepada kelas...</h3>";

$assign_teachers = [
    // Kelas 3 Bijak (id:7) -> Guru Aminah (id:301)
    "UPDATE kelas SET id_guru = 301 WHERE id = 7",
    
    // Kelas 3 Cerdas (id:8) -> Guru Rahim (id:302)  
    "UPDATE kelas SET id_guru = 302 WHERE id = 8",
    
    // Kelas 3 Pintar (id:9) -> Guru Norhidayah (id:303)
    "UPDATE kelas SET id_guru = 303 WHERE id = 9",
    
    // Kelas 4 Bijak (id:10) -> Guru Maniam (id:304)
    "UPDATE kelas SET id_guru = 304 WHERE id = 10",
    
    // Kelas 4 Cerdas (id:11) -> Guru Sarah (id:305)
    "UPDATE kelas SET id_guru = 305 WHERE id = 11",
    
    // Kelas 4 Pintar (id:12) -> Guru Aziz (id:306)
    "UPDATE kelas SET id_guru = 306 WHERE id = 12"
];

foreach ($assign_teachers as $sql) {
    if ($conn->query($sql) === TRUE) {
        echo "✓ " . substr($sql, 7, 40) . "...<br>";
    }
}

// 4. TAMBAH DATA PEPERIKSAAN CONTOH
echo "<h3>4. Menambah data peperiksaan contoh...</h3>";

$check_peperiksaan = $conn->query("SELECT COUNT(*) as total FROM perperiksaan");
$peperiksaan_count = $check_peperiksaan->fetch_assoc()['total'];

if ($peperiksaan_count == 0) {
    $insert_peperiksaan = "INSERT INTO perperiksaan (id_matapelajaran, tahun_akademik, nama_perperiksaan, tarikh_mula, tarikh_tamat, jenis, status) VALUES
        -- Ujian Bahasa Melayu
        (401, '2024', 'Ujian Bulanan 1 BM', '2024-03-01', '2024-03-01', 'Ujian Bulanan', 'aktif'),
        
        -- Ujian Matematik  
        (403, '2024', 'Ujian Bulanan 1 MAT', '2024-03-02', '2024-03-02', 'Ujian Bulanan', 'aktif'),
        
        -- Ujian Sains
        (404, '2024', 'Ujian Bulanan 1 SNS', '2024-03-03', '2024-03-03', 'Ujian Bulanan', 'aktif')";
    
    if ($conn->query($insert_peperiksaan) === TRUE) {
        echo "✓ Data peperiksaan contoh ditambah<br>";
    }
} else {
    echo "✓ Database sudah mempunyai $peperiksaan_count data peperiksaan<br>";
}

// 5. SEMAK DATA TERKINI
echo "<h3>5. Data terkini selepas pembetulan:</h3>";

// Semak kelas
echo "<h4>Kelas dengan guru:</h4>";
$kelas_result = $conn->query("
    SELECT k.id, k.nama, k.tahun, k.status, 
           g.nama as nama_guru, g.email
    FROM kelas k
    LEFT JOIN guru g ON k.id_guru = g.id
    WHERE k.status = 'aktif'
    ORDER BY k.tahun, k.nama
    LIMIT 6
");

echo "<table border='1'><tr><th>ID</th><th>Kelas</th><th>Tahun</th><th>Guru</th><th>Email</th><th>Status</th></tr>";
while ($row = $kelas_result->fetch_assoc()) {
    echo "<tr>
        <td>{$row['id']}</td>
        <td>{$row['nama']}</td>
        <td>{$row['tahun']}</td>
        <td>" . ($row['nama_guru'] ?? '-') . "</td>
        <td>" . ($row['email'] ?? '-') . "</td>
        <td>{$row['status']}</td>
    </tr>";
}
echo "</table>";

// Semak guru
echo "<h4>Senarai Guru:</h4>";
$guru_result = $conn->query("SELECT id, nama, email, status FROM guru WHERE status = 'aktif' ORDER BY id");
echo "<table border='1'><tr><th>ID</th><th>Nama</th><th>Email</th><th>Status</th></tr>";
while ($row = $guru_result->fetch_assoc()) {
    echo "<tr>
        <td>{$row['id']}</td>
        <td>{$row['nama']}</td>
        <td>{$row['email']}</td>
        <td>{$row['status']}</td>
    </tr>";
}
echo "</table>";

// Semak pengajar
echo "<h4>Hubungan Pengajar (Guru-Matapelajaran-Kelas):</h4>";
$pengajar_result = $conn->query("
    SELECT p.id, 
           g.nama as guru_nama,
           m.nama as mata_pelajaran,
           k.nama as kelas,
           p.tahun_akademik,
           p.status
    FROM pengajar p
    JOIN guru g ON p.id_guru = g.id
    JOIN matapelajaran m ON p.id_matapelajaran = m.id
    LEFT JOIN kelas k ON p.id_kelas = k.id
    ORDER BY g.nama
");

if ($pengajar_result->num_rows > 0) {
    echo "<table border='1'><tr><th>ID</th><th>Guru</th><th>Matapelajaran</th><th>Kelas</th><th>Tahun</th><th>Status</th></tr>";
    while ($row = $pengajar_result->fetch_assoc()) {
        echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['guru_nama']}</td>
            <td>{$row['mata_pelajaran']}</td>
            <td>" . ($row['kelas'] ?? '-') . "</td>
            <td>{$row['tahun_akademik']}</td>
            <td>{$row['status']}</td>
        </tr>";
    }
    echo "</table>";
} else {
    echo "<p>Tiada data pengajar. Sila tambah data dalam jadual 'pengajar'.</p>";
}

echo "<h3 style='color: green;'>✅ DATA KELAS DAN GURU TELAH DIBETULKAN!</h3>";

$conn->close();
?>