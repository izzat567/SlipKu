<?php
// delete-subject.php - HARD DELETE VERSION
session_start();
header('Content-Type: application/json');

// Debug info
error_log("DELETE REQUEST - Subject ID: " . ($_POST['id'] ?? $_GET['id'] ?? 'none'));

// For testing, bypass session check (temporary)
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
    $_SESSION['user_type'] = 'guru';
}

$response = ['success' => false, 'message' => '', 'error' => ''];

try {
    // Get subject ID
    $subject_id = isset($_POST['id']) ? intval($_POST['id']) : 
                  (isset($_GET['id']) ? intval($_GET['id']) : 0);
    
    $confirm = isset($_POST['confirm']) ? intval($_POST['confirm']) : 
               (isset($_GET['confirm']) ? intval($_GET['confirm']) : 0);
    
    error_log("Subject ID: $subject_id, Confirm: $confirm");

    if ($subject_id <= 0) {
        $response['error'] = 'ID subjek tidak sah.';
        echo json_encode($response);
        exit();
    }

    // Connect to database
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
    $delete_sql = "DELETE FROM matapelajaran WHERE id = ?";
    $delete_stmt = $database->prepare($delete_sql);
    $delete_stmt->bind_param("i", $subject_id);
    
    if (!$delete_stmt->execute()) {
        $response['error'] = 'Gagal memadam subjek: ' . $delete_stmt->error;
        $database->rollback();
        echo json_encode($response);
        exit();
    }
    $delete_stmt->close();
    
    // 3. Delete dari subject_details jika ada
    $table_check = $database->query("SHOW TABLES LIKE 'subject_details'");
    if ($table_check && $table_check->num_rows > 0) {
        $delete_details = "DELETE FROM subject_details WHERE id_matapelajaran = ?";
        $delete_details_stmt = $database->prepare($delete_details);
        $delete_details_stmt->bind_param("i", $subject_id);
        $delete_details_stmt->execute();
        $delete_details_stmt->close();
    }
    
    // 4. Commit transaction
    $database->commit();
    
    $response['success'] = true;
    $response['message'] = 'Subjek "' . htmlspecialchars($subject_name) . '" berjaya dipadam sepenuhnya dari sistem.';
    
    error_log("HARD DELETE SUCCESS - Subject ID: $subject_id, Name: $subject_name");
    
} catch (Exception $e) {
    if (isset($database) && $database) {
        $database->rollback();
    }
    $response['error'] = 'Ralat sistem: ' . $e->getMessage();
    error_log("DELETE ERROR: " . $e->getMessage());
}

echo json_encode($response);
exit();
?>