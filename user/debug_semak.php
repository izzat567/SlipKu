<?php
// debug_semak.php
// ============================================================
// FAIL DEBUG SEMENTARA — Padam selepas selesai debug
// Upload fail ini ke server, kemudian ubah action form kepada
// action="debug_semak.php" dalam form_student_gred.php
// ============================================================
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Style
echo '<!DOCTYPE html><html><head><meta charset="UTF-8">
<title>Debug SlipKu</title>
<style>
body{font-family:monospace;background:#1e1e1e;color:#d4d4d4;padding:20px;}
h2{color:#569cd6;}h3{color:#4ec9b0;}
.ok{color:#6a9955;font-weight:bold;}
.err{color:#f44747;font-weight:bold;}
.warn{color:#dcdcaa;font-weight:bold;}
.box{background:#252526;border:1px solid #444;padding:15px;margin:10px 0;border-radius:6px;}
table{border-collapse:collapse;width:100%;}
td,th{border:1px solid #555;padding:8px 12px;text-align:left;}
th{background:#333;color:#9cdcfe;}
</style></head><body>';

echo '<h2>🔍 DEBUG — SlipKu Semak Keputusan</h2>';

// ============================================================
// STEP 1: Semak POST data
// ============================================================
echo '<h3>STEP 1: Data dari Form (POST)</h3><div class="box">';
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST)) {
    echo '<span class="err">❌ Tiada POST data! Pastikan form submit dengan method="post"</span>';
} else {
    echo '<table><tr><th>Field</th><th>Nilai</th><th>Status</th></tr>';
    $fields = ['nama','no_kp','kelas','id_kelas','examType'];
    foreach ($fields as $f) {
        $val = $_POST[$f] ?? '';
        $ok  = !empty($val);
        echo "<tr><td>$f</td><td>" . htmlspecialchars($val) . "</td><td>" . ($ok ? '<span class="ok">✅ Ada</span>' : '<span class="err">❌ KOSONG</span>') . "</td></tr>";
    }
    echo '</table>';
}
echo '</div>';

// ============================================================
// STEP 2: Sambungan Database
// ============================================================
echo '<h3>STEP 2: Sambungan Database</h3><div class="box">';

// Cuba cari config
$paths = [
    __DIR__ . '/config/connect.php',
    __DIR__ . '/../config/connect.php',
    dirname(__DIR__) . '/config/connect.php',
];

$config_found = false;
foreach ($paths as $p) {
    if (file_exists($p)) {
        echo "Config ditemui: <span class='ok'>$p</span><br>";
        require_once $p;
        $config_found = true;
        break;
    }
}

if (!$config_found) {
    echo '<span class="err">❌ config/connect.php TIDAK DIJUMPAI!</span><br>';
    echo '<span class="warn">Path yang dicuba:</span><br>';
    foreach ($paths as $p) echo "&nbsp;&nbsp;→ $p<br>";
    
    // Cuba sambung terus tanpa config
    echo '<br><span class="warn">Cuba sambung terus...</span><br>';
    $conn = mysqli_connect('localhost', 'root', '', 'slipku_db');
}

if (!isset($conn) || !$conn) {
    echo '<span class="err">❌ Sambungan GAGAL: ' . mysqli_connect_error() . '</span>';
} else {
    echo '<span class="ok">✅ Sambungan berjaya!</span><br>';
    
    // Semak database
    $db = mysqli_select_db($conn, 'slipku_db');
    echo $db ? '<span class="ok">✅ Database slipku_db OK</span>' : '<span class="err">❌ Database tidak boleh dipilih</span>';
}
echo '</div>';

if (!isset($conn) || !$conn) {
    echo '</body></html>';
    exit();
}

// ============================================================
// STEP 3: Semak Tables
// ============================================================
echo '<h3>STEP 3: Semak Tables dalam Database</h3><div class="box">';
$tables = ['pelajar','kelas','markah','matapelajaran','peperiksaan'];
echo '<table><tr><th>Table</th><th>Status</th><th>Bilangan Rekod</th></tr>';
foreach ($tables as $t) {
    $r = mysqli_query($conn, "SELECT COUNT(*) as c FROM `$t`");
    if ($r) {
        $row = mysqli_fetch_assoc($r);
        echo "<tr><td>$t</td><td><span class='ok'>✅ Ada</span></td><td>{$row['c']} rekod</td></tr>";
    } else {
        echo "<tr><td>$t</td><td><span class='err'>❌ Tidak wujud / error</span></td><td>-</td></tr>";
    }
}
echo '</table></div>';

// ============================================================
// STEP 4: Semak Column dalam table markah
// ============================================================
echo '<h3>STEP 4: Struktur Table markah</h3><div class="box">';
$r = mysqli_query($conn, "DESCRIBE `markah`");
if ($r) {
    echo '<table><tr><th>Column</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>';
    $cols = [];
    while ($row = mysqli_fetch_assoc($r)) {
        $cols[] = $row['Field'];
        $highlight = in_array($row['Field'], ['id','id_pelajar','id_perperiksaan','id_matapelajaran']) 
            ? 'style="color:#9cdcfe;font-weight:bold;"' : '';
        echo "<tr><td $highlight>{$row['Field']}</td><td>{$row['Type']}</td><td>{$row['Null']}</td><td>{$row['Key']}</td><td>{$row['Default']}</td><td>{$row['Extra']}</td></tr>";
    }
    echo '</table><br>';
    
    // Semak column kritikal
    $required = ['id_pelajar', 'id_perperiksaan', 'markah', 'gred', 'status'];
    $optional = ['id_matapelajaran'];
    foreach ($required as $c) {
        echo in_array($c, $cols) 
            ? "<span class='ok'>✅ Column '$c' ada</span><br>"
            : "<span class='err'>❌ Column '$c' TIADA!</span><br>";
    }
    foreach ($optional as $c) {
        echo in_array($c, $cols)
            ? "<span class='ok'>✅ Column '$c' ada</span><br>"
            : "<span class='warn'>⚠️ Column '$c' belum ditambah (perlu jalankan patch)</span><br>";
    }
} else {
    echo '<span class="err">❌ Tidak boleh baca struktur markah: ' . mysqli_error($conn) . '</span>';
}
echo '</div>';

// ============================================================
// STEP 5: Cari Pelajar
// ============================================================
if (!empty($_POST['nama'])) {
    echo '<h3>STEP 5: Cari Pelajar</h3><div class="box">';
    
    $nama    = strtoupper(trim(mysqli_real_escape_string($conn, $_POST['nama'])));
    $no_kp   = str_replace('-', '', trim(mysqli_real_escape_string($conn, $_POST['no_kp'])));
    $kelas   = strtoupper(trim(mysqli_real_escape_string($conn, $_POST['kelas'])));
    $examType= trim(mysqli_real_escape_string($conn, $_POST['examType']));
    
    echo "Nama dicari: <span class='warn'>$nama</span><br>";
    echo "IC (clean): <span class='warn'>$no_kp</span><br>";
    echo "Kelas: <span class='warn'>$kelas</span><br>";
    echo "Jenis Exam: <span class='warn'>$examType</span><br><br>";
    
    // Query pelajar
    $sql = "SELECT p.*, k.nama as nama_kelas FROM pelajar p 
            LEFT JOIN kelas k ON p.id_kelas = k.id
            WHERE p.nama = '$nama' 
            AND REPLACE(p.no_kp, '-', '') = '$no_kp'
            LIMIT 1";
    echo "Query: <span style='color:#ce9178'>$sql</span><br><br>";
    
    $r = mysqli_query($conn, $sql);
    if (!$r) {
        echo '<span class="err">❌ Query error: ' . mysqli_error($conn) . '</span>';
    } elseif (mysqli_num_rows($r) === 0) {
        echo '<span class="err">❌ Pelajar TIDAK DIJUMPAI dengan nama dan IC tersebut</span><br><br>';
        
        // Cari pelajar dengan nama sahaja
        $r2 = mysqli_query($conn, "SELECT id, nama, no_kp, status FROM pelajar WHERE nama LIKE '%$nama%' LIMIT 5");
        echo '<span class="warn">Pelajar dengan nama hampir sama:</span><br>';
        echo '<table><tr><th>ID</th><th>Nama</th><th>No KP</th><th>Status</th></tr>';
        while ($row = mysqli_fetch_assoc($r2)) {
            echo "<tr><td>{$row['id']}</td><td>{$row['nama']}</td><td>{$row['no_kp']}</td><td>{$row['status']}</td></tr>";
        }
        echo '</table>';
    } else {
        $pelajar = mysqli_fetch_assoc($r);
        echo '<span class="ok">✅ Pelajar DIJUMPAI!</span><br>';
        echo '<table><tr><th>Field</th><th>Nilai</th></tr>';
        foreach ($pelajar as $k => $v) {
            echo "<tr><td>$k</td><td>" . htmlspecialchars($v ?? 'NULL') . "</td></tr>";
        }
        echo '</table><br>';
        
        // Semak kelas
        $kelas_db = strtoupper(trim($pelajar['nama_kelas'] ?? ''));
        if ($kelas_db === $kelas) {
            echo "<span class='ok'>✅ Kelas sepadan: DB='$kelas_db' = Input='$kelas'</span><br>";
        } else {
            echo "<span class='err'>❌ Kelas TIDAK SEPADAN: DB='$kelas_db' ≠ Input='$kelas'</span><br>";
        }
        
        // Cari peperiksaan
        echo '<br><span class="warn">Cari peperiksaan jenis: ' . $examType . '</span><br>';
        $sql_pe = "SELECT * FROM peperiksaan WHERE jenis = '$examType' AND status = 'aktif' ORDER BY id DESC LIMIT 5";
        $r_pe = mysqli_query($conn, $sql_pe);
        if (mysqli_num_rows($r_pe) === 0) {
            echo '<span class="err">❌ Peperiksaan tidak dijumpai!</span><br>';
            $all = mysqli_query($conn, "SELECT id, nama_peperiksaan, jenis, status FROM peperiksaan");
            echo '<span class="warn">Semua peperiksaan dalam DB:</span><br>';
            echo '<table><tr><th>ID</th><th>Nama</th><th>Jenis</th><th>Status</th></tr>';
            while ($row = mysqli_fetch_assoc($all)) {
                echo "<tr><td>{$row['id']}</td><td>{$row['nama_peperiksaan']}</td><td>{$row['jenis']}</td><td>{$row['status']}</td></tr>";
            }
            echo '</table>';
        } else {
            while ($pe = mysqli_fetch_assoc($r_pe)) {
                echo "<span class='ok'>✅ Peperiksaan dijumpai: ID={$pe['id']}, Nama={$pe['nama_peperiksaan']}</span><br>";
                
                // Cari markah
                $id_pe = $pe['id'];
                $id_pl = $pelajar['id'];
                echo "<br><span class='warn'>Cari markah: id_pelajar=$id_pl, id_perperiksaan=$id_pe</span><br>";
                
                $sql_m = "SELECT * FROM markah WHERE id_pelajar = $id_pl AND id_perperiksaan = $id_pe";
                $r_m = mysqli_query($conn, $sql_m);
                if (!$r_m) {
                    echo '<span class="err">❌ Query markah error: ' . mysqli_error($conn) . '</span><br>';
                } elseif (mysqli_num_rows($r_m) === 0) {
                    echo '<span class="err">❌ Tiada markah dijumpai untuk kombinasi ini!</span><br>';
                    
                    // Semak semua markah pelajar ini
                    $r_all = mysqli_query($conn, "SELECT * FROM markah WHERE id_pelajar = $id_pl");
                    $count = mysqli_num_rows($r_all);
                    echo "<span class='warn'>Jumlah markah pelajar ini (semua peperiksaan): $count</span><br>";
                    if ($count > 0) {
                        echo '<table><tr><th>id</th><th>id_pelajar</th><th>id_perperiksaan</th><th>markah</th><th>gred</th><th>status</th></tr>';
                        while ($row = mysqli_fetch_assoc($r_all)) {
                            echo "<tr>";
                            foreach ($row as $k => $v) echo "<td>" . htmlspecialchars($v ?? 'NULL') . "</td>";
                            echo "</tr>";
                        }
                        echo '</table>';
                    }
                } else {
                    echo '<span class="ok">✅ Markah dijumpai: ' . mysqli_num_rows($r_m) . ' rekod</span><br>';
                    echo '<table><tr><th>id</th><th>id_pelajar</th><th>id_perperiksaan</th><th>id_matapelajaran</th><th>markah</th><th>gred</th><th>status</th></tr>';
                    while ($row = mysqli_fetch_assoc($r_m)) {
                        echo "<tr>";
                        foreach ($row as $k => $v) echo "<td>" . htmlspecialchars($v ?? 'NULL') . "</td>";
                        echo "</tr>";
                    }
                    echo '</table>';
                }
            }
        }
    }
    echo '</div>';
}

// ============================================================
// STEP 6: Papar semua pelajar (untuk semak nama betul)
// ============================================================
echo '<h3>STEP 6: Semua Pelajar dalam Database</h3><div class="box">';
$r = mysqli_query($conn, "SELECT p.id, p.nama, p.no_kp, k.nama as kelas, p.status FROM pelajar p LEFT JOIN kelas k ON p.id_kelas = k.id ORDER BY p.id LIMIT 20");
echo '<table><tr><th>ID</th><th>Nama</th><th>No KP</th><th>Kelas</th><th>Status</th></tr>';
while ($row = mysqli_fetch_assoc($r)) {
    echo "<tr><td>{$row['id']}</td><td>{$row['nama']}</td><td>{$row['no_kp']}</td><td>{$row['kelas']}</td><td>{$row['status']}</td></tr>";
}
echo '</table></div>';

mysqli_close($conn);
echo '<br><hr><p style="color:#666">Debug selesai. <strong style="color:#f44747">Padam fail ini selepas selesai debug!</strong></p>';
echo '</body></html>';
?>