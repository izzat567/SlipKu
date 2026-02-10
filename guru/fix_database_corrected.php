<?php
// fix_database_corrected.php
$host = "localhost";
$username = "root";
$password = "";
$database = "slipku_db";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<h2>Memperbetulkan Struktur Database</h2>";

// 1. SEMAK STRUKTUR SEMASA DULU
echo "<h3>1. Semak struktur semasa jadual matapelajaran...</h3>";
$result = $conn->query("DESCRIBE matapelajaran");
echo "<table border='1'><tr><th>Field</th><th>Type</th></tr>";
while ($row = $result->fetch_assoc()) {
    echo "<tr><td>" . $row['Field'] . "</td><td>" . $row['Type'] . "</td></tr>";
}
echo "</table>";

// 2. BETULKAN JADUAL matapelajaran DENGAN CARA SELAMAT
echo "<h3>2. Membetulkan jadual matapelajaran...</h3>";

// Semak jika kolum 'tahun' sudah wujud
$check_year = $conn->query("SHOW COLUMNS FROM matapelajaran LIKE 'tahun'");
if ($check_year->num_rows > 0) {
    echo "✓ Kolum 'tahun' sudah wujud<br>";
    
    // Jika tahun sudah wujud, mungkin kosong/null
    // Kita update data dari kolum status yang mengandungi "2026   aktif"
    $update_year = "UPDATE matapelajaran 
                    SET tahun = 2026 
                    WHERE (tahun IS NULL OR tahun = 0 OR tahun = '') 
                    AND status LIKE '%2026%'";
    if ($conn->query($update_year) === TRUE) {
        echo "✓ Data tahun telah dikemaskini<br>";
    }
} else {
    // Jika tahun tidak wujud, tambah kolum baru
    echo "✗ Kolum 'tahun' tidak wujud, menambah kolum...<br>";
    $add_year = "ALTER TABLE matapelajaran ADD COLUMN tahun INT";
    if ($conn->query($add_year) === TRUE) {
        echo "✓ Kolum 'tahun' ditambah<br>";
        // Update data tahun
        $conn->query("UPDATE matapelajaran SET tahun = 2026 WHERE status LIKE '%2026%'");
    }
}

// Semak jika kolum 'kod' sudah wujud
$check_kod = $conn->query("SHOW COLUMNS FROM matapelajaran LIKE 'kod'");
if ($check_kod->num_rows > 0) {
    echo "✓ Kolum 'kod' sudah wujud<br>";
    // Pindah data dari 'nod' ke 'kod' jika perlu
    $update_kod = "UPDATE matapelajaran SET kod = nod WHERE (kod IS NULL OR kod = '')";
    $conn->query($update_kod);
} else {
    // Jika kod tidak wujud, tambah dari nod
    echo "✗ Kolum 'kod' tidak wujud, menambah kolum...<br>";
    $add_kod = "ALTER TABLE matapelajaran ADD COLUMN kod VARCHAR(20)";
    if ($conn->query($add_kod) === TRUE) {
        echo "✓ Kolum 'kod' ditambah<br>";
        // Pindah data dari nod ke kod
        $conn->query("UPDATE matapelajaran SET kod = nod");
    }
}

// Semak dan betulkan data status
echo "<h3>3. Membetulkan data status...</h3>";
// Bersihkan status yang mengandungi "2026   aktif"
$clean_status = "UPDATE matapelajaran 
                 SET status = 'aktif' 
                 WHERE status LIKE '%aktif%'";
if ($conn->query($clean_status) === TRUE) {
    echo "✓ Data status dibersihkan<br>";
}

// Buang kolum 'nod' jika tidak diperlukan
echo "<h3>4. Membersihkan kolum yang tidak diperlukan...</h3>";
$check_nod = $conn->query("SHOW COLUMNS FROM matapelajaran LIKE 'nod'");
if ($check_nod->num_rows > 0) {
    $drop_nod = "ALTER TABLE matapelajaran DROP COLUMN nod";
    if ($conn->query($drop_nod) === TRUE) {
        echo "✓ Kolum 'nod' dibuang<br>";
    }
}

// 3. BETULKAN JADUAL markah
echo "<h3>5. Membetulkan jadual markah...</h3>";

// Semak dan buang kolum yang tidak perlu
$columns_to_drop = ['kod', 'nama', 'tahun'];
foreach ($columns_to_drop as $column) {
    $check_col = $conn->query("SHOW COLUMNS FROM markah LIKE '$column'");
    if ($check_col->num_rows > 0) {
        $drop_col = "ALTER TABLE markah DROP COLUMN $column";
        if ($conn->query($drop_col) === TRUE) {
            echo "✓ Kolum '$column' dibuang<br>";
        }
    } else {
        echo "✓ Kolum '$column' sudah tiada<br>";
    }
}

// Betulkan nama kolum id_peperiksaan
$check_peperiksaan = $conn->query("SHOW COLUMNS FROM markah LIKE 'id_peperiksaan'");
if ($check_peperiksaan->num_rows > 0) {
    // Tukar kepada id_perperiksaan (satu 'p')
    $change_col = "ALTER TABLE markah CHANGE COLUMN id_peperiksaan id_perperiksaan INT";
    if ($conn->query($change_col) === TRUE) {
        echo "✓ Nama kolum dibetulkan: id_peperiksaan → id_perperiksaan<br>";
    }
}

// Bersihkan data catatan
$clean_catatan = "UPDATE markah 
                  SET catatan = LEFT(catatan, LOCATE(' ', catatan) - 1) 
                  WHERE catatan LIKE '% %' 
                  AND LENGTH(catatan) > 20";
if ($conn->query($clean_catatan) === TRUE) {
    echo "✓ Data catatan dibersihkan<br>";
}

// 4. BERSIHKAN JADUAL kelas
echo "<h3>6. Membersihkan jadual kelas...</h3>";

// Hapus data dengan 'Delete'
$delete_bad = "DELETE FROM kelas WHERE nama LIKE '%Delete%'";
if ($conn->query($delete_bad) === TRUE) {
    $deleted = $conn->affected_rows;
    echo "✓ $deleted rekod dengan 'Delete' dihapuskan<br>";
}

// Semak jika ada data kelas yang sah
$check_classes = $conn->query("SELECT COUNT(*) as total FROM kelas");
$row = $check_classes->fetch_assoc();
if ($row['total'] == 0) {
    // Masukkan data kelas contoh
    $insert_classes = "INSERT INTO kelas (nama, tahun, status) VALUES 
                      ('3 Bijak', 3, 'aktif'),
                      ('3 Cerdas', 3, 'aktif'),
                      ('3 Pintar', 3, 'aktif'),
                      ('4 Bijak', 4, 'aktif'),
                      ('4 Cerdas', 4, 'aktif'),
                      ('4 Pintar', 4, 'aktif')";
    if ($conn->query($insert_classes) === TRUE) {
        echo "✓ Data kelas contoh dimasukkan<br>";
    }
} else {
    echo "✓ Jadual kelas sudah mempunyai $row[total] rekod<br>";
}

// 5. SEMAK STRUKTUR AKHIR
echo "<h3>7. Struktur akhir jadual:</h3>";

$tables = ['matapelajaran', 'markah', 'kelas', 'guru'];
foreach ($tables as $table) {
    echo "<h4>Jadual: $table</h4>";
    $result = $conn->query("DESCRIBE $table");
    echo "<table border='1'><tr><th>Field</th><th>Type</th><th>Null</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row['Field'] . "</td><td>" . $row['Type'] . "</td><td>" . $row['Null'] . "</td></tr>";
    }
    echo "</table>";
    
    // Tunjukkan data sample
    $sample = $conn->query("SELECT * FROM $table LIMIT 3");
    echo "<p>Contoh data (3 pertama):</p>";
    echo "<table border='1'>";
    if ($sample->num_rows > 0) {
        $first_row = $sample->fetch_assoc();
        echo "<tr>";
        foreach (array_keys($first_row) as $col) {
            echo "<th>$col</th>";
        }
        echo "</tr>";
        
        // Tunjukkan baris pertama
        echo "<tr>";
        foreach ($first_row as $value) {
            echo "<td>" . htmlspecialchars($value) . "</td>";
        }
        echo "</tr>";
    }
    echo "</table><hr>";
}

echo "<h3 style='color: green;'>✅ PEMBETULAN SELESAI!</h3>";

$conn->close();
?>