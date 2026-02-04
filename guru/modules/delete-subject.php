<?php
// delete-subject.php - HARD DELETE VERSION
session_start();
header('Content-Type: application/json');

// PERBAIKAN: Debug info
error_log("DELETE REQUEST - Subject ID: " . ($_POST['id'] ?? $_GET['id'] ?? 'none'));

// PERBAIKAN: Session check untuk guru
if (!isset($_SESSION['guru_id'])) {
    $response = ['success' => false, 'message' => '', 'error' => 'Akses ditolak. Sila login semula.'];
    echo json_encode($response);
    exit();
}

$guru_id = $_SESSION['guru_id'];
$response = ['success' => false, 'message' => '', 'error' => ''];

try {
    // Get subject ID
    $subject_id = isset($_POST['id']) ? intval($_POST['id']) : 
                  (isset($_GET['id']) ? intval($_GET['id']) : 0);
    
    $confirm = isset($_POST['confirm']) ? intval($_POST['confirm']) : 
               (isset($_GET['confirm']) ? intval($_GET['confirm']) : 0);
    
    error_log("Subject ID: $subject_id, Confirm: $confirm, Guru ID: $guru_id");

    if ($subject_id <= 0) {
        $response['error'] = 'ID subjek tidak sah.';
        echo json_encode($response);
        exit();
    }

    // PERBAIKAN: Connect to database dengan path yang betul
    // Dari modules/delete-subject.php ke config/connect.php
    require_once __DIR__ . '/../../config/connect.php';
    
    // Start transaction
    $database->begin_transaction();
    
    // 1. Dapatkan nama subjek untuk message
    $check_sql = "SELECT nama FROM matapelajaran WHERE id = ?";
    $check_stmt = $database->prepare($check_sql);
    $check_stmt->bind_param("i", $subject_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows === 0) {
        $response['error'] = 'Subjek tidak ditemukan.';
        $database->rollback();
        echo json_encode($response);
        exit();
    }
    
    $subject = $check_result->fetch_assoc();
    $subject_name = $subject['nama'];
    $check_stmt->close();
    
    // 2. DELETE sebenar dari matapelajaran
    // PERBAIKAN: Disable foreign key checks sementara untuk hard delete
    $database->query("SET FOREIGN_KEY_CHECKS = 0");
    
    $delete_sql = "DELETE FROM matapelajaran WHERE id = ?";
    $delete_stmt = $database->prepare($delete_sql);
    $delete_stmt->bind_param("i", $subject_id);
    
    if (!$delete_stmt->execute()) {
        $response['error'] = 'Gagal memadam subjek: ' . $delete_stmt->error;
        $database->rollback();
        $database->query("SET FOREIGN_KEY_CHECKS = 1");
        echo json_encode($response);
        exit();
    }
    
    $affected_rows = $delete_stmt->affected_rows;
    $delete_stmt->close();
    
    // 3. Delete dari related tables jika ada
    // Guru mata pelajaran
    try {
        $delete_gmp = "DELETE FROM guru_mata_pelajaran WHERE mata_pelajaran_id = ?";
        $delete_gmp_stmt = $database->prepare($delete_gmp);
        $delete_gmp_stmt->bind_param("i", $subject_id);
        $delete_gmp_stmt->execute();
        $delete_gmp_stmt->close();
    } catch (Exception $e) {
        // Table mungkin tidak ada, continue saja
        error_log("Note: guru_mata_pelajaran table may not exist: " . $e->getMessage());
    }
    
    // Penilaian
    try {
        $delete_penilaian = "DELETE FROM penilaian WHERE mata_pelajaran_id = ?";
        $delete_pen_stmt = $database->prepare($delete_penilaian);
        $delete_pen_stmt->bind_param("i", $subject_id);
        $delete_pen_stmt->execute();
        $delete_pen_stmt->close();
    } catch (Exception $e) {
        // Table mungkin tidak ada, continue saja
        error_log("Note: penilaian table may not exist: " . $e->getMessage());
    }
    
    // PERBAIKAN: Enable foreign key checks kembali
    $database->query("SET FOREIGN_KEY_CHECKS = 1");
    
    // 4. Commit transaction
    $database->commit();
    
    if ($affected_rows > 0) {
        $response['success'] = true;
        $response['message'] = 'Subjek "' . htmlspecialchars($subject_name) . '" berjaya dipadam sepenuhnya dari sistem.';
        
        // Log aktiviti
        error_log("HARD DELETE SUCCESS - Subject ID: $subject_id, Name: $subject_name, Deleted by Guru ID: $guru_id");
    } else {
        $response['error'] = 'Tiada subjek dipadam.';
    }
    
} catch (Exception $e) {
    if (isset($database) && $database) {
        $database->rollback();
        $database->query("SET FOREIGN_KEY_CHECKS = 1"); // Pastikan diaktifkan semula
    }
    $response['error'] = 'Ralat sistem: ' . $e->getMessage();
    error_log("DELETE ERROR: " . $e->getMessage());
}

echo json_encode($response);
exit();
?>