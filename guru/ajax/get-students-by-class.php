<?php
session_start();
require_once __DIR__ . '/../../config/connect.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$class_id = intval($_GET['class_id'] ?? 0);
$guru_id = $_SESSION['guru_id'];

// Verify guru teaches this class
$stmt = $conn->prepare("SELECT id FROM pengajar WHERE id_guru = ? AND id_kelas = ? AND status = 'aktif'");
$stmt->bind_param("ii", $guru_id, $class_id);
$stmt->execute();
if ($stmt->get_result()->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Tidak dibenarkan']);
    exit();
}
$stmt->close();

// Get students directly from pelajar.id_kelas (no pendaftaran_kelas table)
$sql = "SELECT p.id, p.nama, p.no_kp,
            UPPER(CONCAT(SUBSTRING_INDEX(p.nama, ' ', 1), ' ', IF(LOCATE(' ', p.nama) > 0, SUBSTRING(p.nama, LOCATE(' ', p.nama)+1, 1), ''))) as initials
        FROM pelajar p
        WHERE p.id_kelas = ? AND p.status = 'aktif'
        ORDER BY p.nama";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $class_id);
$stmt->execute();
$result = $stmt->get_result();

$students = [];
while ($row = $result->fetch_assoc()) {
    $names = explode(' ', trim($row['nama']));
    $init = '';
    foreach ($names as $n) if (!empty($n)) $init .= strtoupper(substr($n, 0, 1));
    $students[] = ['id' => $row['id'], 'nama' => $row['nama'], 'no_kp' => $row['no_kp'], 'initials' => substr($init, 0, 2)];
}

echo json_encode(['success' => true, 'students' => $students]);
?>
