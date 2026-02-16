<?php

include '../../config/connect.php';

// Tetapkan header untuk memaksa muat turun fail CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=senarai_pelajar.csv');

// Buka output stream
$output = fopen('php://output', 'w');

// Tambahkan BOM UTF-8 supaya aksara khas (seperti nama berhuruf besar) dapat dibaca dengan betul dalam Excel
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// Tulis baris tajuk (header) dalam CSV
fputcsv($output, array('NAMA PELAJAR', 'JANTINA', 'KELAS', 'NO. KP'));

// Query untuk mendapatkan data pelajar (sama seperti dalam pengurusan-pelajar.php)
$sql = "
SELECT pelajar.nama, pelajar.jantina, kelas.nama AS kelas, pelajar.no_kp
FROM pelajar
JOIN kelas ON pelajar.id_kelas = kelas.id
ORDER BY pelajar.nama
";

$result = mysqli_query($conn, $sql);

// Loop setiap baris dan tulis ke CSV
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, $row);
}

// Tutup stream
fclose($output);
exit;

?>
