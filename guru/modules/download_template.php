<?php
// download_template.php
session_start();

// Check login
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login-guru.php');
    exit();
}

// Set headers for CSV download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=template_import_pelajar.csv');

// Create output stream
$output = fopen('php://output', 'w');

// Add UTF-8 BOM for Excel compatibility
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// Add headers
fputcsv($output, ['Nama', 'No_KP', 'Jantina', 'ID_Kelas']);

// Add sample data
fputcsv($output, ['Ahmad bin Ali', '030101-14-1234', 'L', '1']);
fputcsv($output, ['Siti binti Abu', '030102-02-5678', 'P', '1']);
fputcsv($output, ['Muhammad bin Hassan', '030103-01-9012', 'L', '2']);

// Close the output stream
fclose($output);
exit();
?>