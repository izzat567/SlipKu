<?php
session_start();
require_once __DIR__ . '/../../config/connect.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$class_id = $_GET['class_id'] ?? 0;
$guru_id = $_SESSION['guru_id'];

// Verify guru ajar kelas ini
$sql_verify = "SELECT id FROM pengajar WHERE id_guru = ? AND id_kelas = ? AND status = 'aktif'";
$stmt = $conn->prepare($sql_verify);
$stmt->bind_param("ii", $guru_id, $class_id);
$stmt->execute();
$verify = $stmt->get_result();

if ($verify->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

// Get students
$sql = "SELECT 
            p.id,
            p.nama,
            p.no_kp,
            UPPER(LEFT(p.nama, 2)) as initials
        FROM pelajar p
        JOIN pendaftaran_kelas pk ON p.id = pk.id_pelajar
        WHERE pk.id_kelas = ? AND pk.status = 'aktif' AND p.status = 'aktif'
        ORDER BY p.nama";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $class_id);
$stmt->execute();
$result = $stmt->get_result();

$students = [];
while ($row = $result->fetch_assoc()) {
    $students[] = $row;
}

echo json_encode(['success' => true, 'students' => $students]);
$conn->close();
?>