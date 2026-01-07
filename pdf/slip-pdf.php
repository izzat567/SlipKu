<?php
require 'vendor/autoload.php'; // ikut lokasi sebenar projek

use Dompdf\Dompdf;

$dompdf = new Dompdf();

// Ambil template HTML
ob_start();
include 'template/slip_template.php'; // ikut lokasi sebenar
$html = ob_get_clean();

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Download PDF terus ke komputer
$dompdf->stream("slip_keputusan_2026.pdf", [
    "Attachment" => true // true = terus download
]);
