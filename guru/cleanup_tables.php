<?php
// cleanup_tables.php
$host = "localhost";
$username = "root";
$password = "";
$database = "slipku_db";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<h2>Pembersihan Kolum Berlebihan</h2>";

// 1. BERSIHKAN JADUAL matapelajaran
echo "<h3>1. Membersihkan jadual matapelajaran...</h3>";

// Pindah data dari kolum *_baru ke kolum asal jika kolum asal kosong
$update_data = [
    // Jika kod kosong, ambil dari kod_baru
    "UPDATE matapelajaran SET kod = kod_baru WHERE (kod IS NULL OR kod = '') AND kod_baru IS NOT NULL",
    
    // Jika tahun kosong/null, ambil dari tahun_baru
    "UPDATE matapelajaran SET tahun = tahun_baru WHERE (tahun IS NULL OR tahun = 0) AND tahun_baru IS NOT NULL",
    
    // Jika status kosong, ambil dari status_baru
    "UPDATE matapelajaran SET status = status_baru WHERE (status IS NULL OR status = '') AND status_baru IS NOT NULL"
];

foreach ($update_data as $sql) {
    if ($conn->query($sql) === TRUE) {
        echo "✓ Data dipindahkan: " . substr($sql, 0, 60) . "...<br>";
    } else {
        echo "✗ Error: " . $conn->error . "<br>";
    }
}

// Buang kolum *_baru yang tidak diperlukan
$drop_columns = [
    "ALTER TABLE matapelajaran DROP COLUMN kod_baru",
    "ALTER TABLE matapelajaran DROP COLUMN tahun_baru", 
    "ALTER TABLE matapelajaran DROP COLUMN status_baru"
];

foreach ($drop_columns as $sql) {
    if ($conn->query($sql) === TRUE) {
        echo "✓ Kolum dibuang: " . substr($sql, 28) . "<br>";
    } else {
        echo "✗ Error: " . $conn->error . "<br>";
    }
}

// 2. UPDATE DATA KELAS (ubah "ALPHA", "BETA", "GAMMA" kepada nama betul)
echo "<h3>2. Membetulkan nama kelas...</h3>";

$update_kelas = "UPDATE kelas 
                 SET nama = CONCAT('Tahun ', tahun, ' - ', 
                     CASE 
                         WHEN nama = 'ALPHA' THEN 'Bijak'
                         WHEN nama = 'BETA' THEN 'Cerdas' 
                         WHEN nama = 'GAMMA' THEN 'Pintar'
                         ELSE nama
                     END)
                 WHERE nama IN ('ALPHA', 'BETA', 'GAMMA')";

if ($conn->query($update_kelas) === TRUE) {
    $updated = $conn->affected_rows;
    echo "✓ $updated nama kelas dibetulkan<br>";
} else {
    echo "✗ Error: " . $conn->error . "<br>";
}

// 3. UPDATE STATUS GURU (ubah '1' kepada 'aktif')
echo "<h3>3. Membetulkan status guru...</h3>";

$update_guru_status = "UPDATE guru SET status = 'aktif' WHERE status = '1'";
if ($conn->query($update_guru_status) === TRUE) {
    echo "✓ Status guru dibetulkan<br>";
}

// 4. TAMBAH DATA PENG AJAR (untuk hubungan guru-kelas-matapelajaran)
echo "<h3>4. Menambah data pengajar...</h3>";

// Semak jika jadual pengajar wujud
$check_pengajar = $conn->query("SHOW TABLES LIKE 'pengajar'");
if ($check_pengajar->num_rows == 0) {
    echo "✗ Jadual 'pengajar' tidak wujud. Membuat jadual...<br>";
    
    $create_pengajar = "CREATE TABLE pengajar (
        id INT PRIMARY KEY AUTO_INCREMENT,
        id_kelas INT,
        id_guru INT NOT NULL,
        id_matapelajaran INT NOT NULL,
        tahun_akademik VARCHAR(20),
        status VARCHAR(20) DEFAULT 'aktif'
    )";
    
    if ($conn->query($create_pengajar) === TRUE) {
        echo "✓ Jadual 'pengajar' dibuat<br>";
    }
}

// Masukkan data pengajar contoh
$insert_pengajar = "INSERT IGNORE INTO pengajar (id_kelas, id_guru, id_matapelajaran, tahun_akademik, status) VALUES
    -- Guru Aminah (id:301) mengajar BM (id:401) di kelas 1
    (1, 301, 401, '2024', 'aktif'),
    
    -- Guru Aminah mengajar Sains (id:404) di kelas 1
    (1, 301, 404, '2024', 'aktif'),
    
    -- Guru Rahim (id:302) mengajar Matematik (id:403) di kelas 2
    (2, 302, 403, '2024', 'aktif'),
    
    -- Guru Norhidayah (id:303) mengajar BI (id:402) di kelas 3
    (3, 303, 402, '2024', 'aktif')";

if ($conn->query($insert_pengajar) === TRUE) {
    $inserted = $conn->affected_rows;
    echo "✓ $inserted data pengajar ditambah<br>";
} else {
    echo "✗ Error menambah data pengajar: " . $conn->error . "<br>";
}

// 5. SEMAK STRUKTUR AKHIR
echo "<h3>5. Struktur akhir selepas pembersihan:</h3>";

$tables = ['matapelajaran', 'markah', 'kelas', 'guru', 'pengajar'];
foreach ($tables as $table) {
    echo "<h4>Jadual: $table</h4>";
    
    $result = $conn->query("DESCRIBE $table");
    if ($result) {
        echo "<table border='1'><tr><th>Field</th><th>Type</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row['Field'] . "</td><td>" . $row['Type'] . "</td></tr>";
        }
        echo "</table>";
        
        // Tunjukkan data sample
        $sample = $conn->query("SELECT * FROM $table LIMIT 2");
        if ($sample->num_rows > 0) {
            echo "<p>Data contoh:</p>";
            echo "<table border='1'>";
            
            $first = $sample->fetch_assoc();
            echo "<tr>";
            foreach (array_keys($first) as $col) {
                echo "<th>$col</th>";
            }
            echo "</tr>";
            
            $sample->data_seek(0);
            while ($row = $sample->fetch_assoc()) {
                echo "<tr>";
                foreach ($row as $value) {
                    echo "<td>" . htmlspecialchars($value) . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        }
    } else {
        echo "<p>Jadual tidak wujud</p>";
    }
    echo "<hr>";
}

echo "<h3 style='color: green;'>✅ PEMBERSIHAN SELESAI!</h3>";

$conn->close();
?>