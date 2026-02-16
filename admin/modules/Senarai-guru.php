<?php
// Senarai-guru.php - Eksport data guru ke format CSV

include '../../config/connect.php';

// Tetapkan header untuk memuat turun CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=senarai_guru.csv');

// Buka output stream
$output = fopen('php://output', 'w');

// Tambah BOM UTF-8 untuk aksara khas (huruf besar, dll.)
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// Tulis baris tajuk
fputcsv($output, array('NAMA GURU', 'EMAIL', 'NO. TELEFON', 'STATUS'));

// Query data guru
$sql = "SELECT nama, email, no_telefon, status FROM guru ORDER BY nama";
$result = mysqli_query($conn, $sql);

// Tulis setiap baris data
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, $row);
}

fclose($output);
exit;
?>