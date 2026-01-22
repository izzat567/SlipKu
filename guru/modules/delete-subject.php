<?php
// delete-subject.php
session_start();
ob_start();

// Include database connection
require_once __DIR__ . '/../../config/connect.php';

// Set JSON header
header('Content-Type: application/json');

// Default response
$response = [
    'success' => false,
    'message' => '',
    'error' => ''
];

try {
    // Debug: Log session data
    error_log("Delete Subject - Session: " . print_r($_SESSION, true));
    error_log("Delete Subject - Request: " . print_r($_REQUEST, true));

    // Check if user is logged in
    if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
        $response['error'] = 'Sila log masuk semula. Sesi telah tamat.';
        echo json_encode($response);
        exit();
    }

    // Get user ID from session
    $user_id = $_SESSION['user_id'];
    $user_type = $_SESSION['user_type'] ?? 'guru';

    // Check if user is teacher
    if ($user_type !== 'guru') {
        $response['error'] = 'Akses ditolak. Hanya guru dibenarkan.';
        echo json_encode($response);
        exit();
    }

    // Get subject ID - support both GET and POST
    if (isset($_POST['id'])) {
        $subject_id = intval($_POST['id']);
    } elseif (isset($_GET['id'])) {
        $subject_id = intval($_GET['id']);
    } else {
        $response['error'] = 'ID subjek diperlukan.';
        echo json_encode($response);
        exit();
    }

    // Validate subject ID
    if ($subject_id <= 0) {
        $response['error'] = 'ID subjek tidak sah.';
        echo json_encode($response);
        exit();
    }

    // Check confirmation
    $confirm = isset($_POST['confirm']) ? intval($_POST['confirm']) : 
               (isset($_GET['confirm']) ? intval($_GET['confirm']) : 0);

    if ($confirm !== 1) {
        // Jika tidak confirm, return warning
        $response['success'] = false;
        $response['error'] = 'Permintaan tidak disahkan.';
        $response['needs_confirm'] = true;
        $response['subject_id'] = $subject_id;
        
        // Check subject details for confirmation message
        $check_sql = "SELECT nama FROM matapelajaran WHERE id = ? AND status = 1";
        $check_stmt = $database->prepare($check_sql);
        $check_stmt->bind_param("i", $subject_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            $subject = $check_result->fetch_assoc();
            $response['subject_name'] = $subject['nama'];
            $response['message'] = 'Adakah anda pasti mahu memadam subjek "' . $subject['nama'] . '"?';
        }
        
        $check_stmt->close();
        echo json_encode($response);
        exit();
    }

    // Start transaction
    $database->begin_transaction();

    // 1. First check if subject exists and get its name
    $check_sql = "SELECT id, nama, kod, tahun 
                  FROM matapelajaran 
                  WHERE id = ? AND status = 1";
    
    $check_stmt = $database->prepare($check_sql);
    $check_stmt->bind_param("i", $subject_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows === 0) {
        $check_stmt->close();
        $database->rollback();
        
        $response['error'] = 'Subjek tidak ditemukan atau telah dipadam.';
        echo json_encode($response);
        exit();
    }
    
    $subject = $check_result->fetch_assoc();
    $check_stmt->close();

    // Log before deletion
    error_log("Deleting subject - ID: {$subject_id}, Name: {$subject['nama']}, Teacher: {$user_id}");

    // 2. Soft delete: Update status to 0 (instead of actual deletion)
    $update_sql = "UPDATE matapelajaran SET status = 0 WHERE id = ?";
    $update_stmt = $database->prepare($update_sql);
    $update_stmt->bind_param("i", $subject_id);
    
    if (!$update_stmt->execute()) {
        $database->rollback();
        $update_stmt->close();
        
        $response['error'] = 'Gagal memadam subjek: ' . $database->error;
        echo json_encode($response);
        exit();
    }
    
    $update_stmt->close();

    // 3. Delete from subject_details if exists
    // First check if table exists
    $table_check = $database->query("SHOW TABLES LIKE 'subject_details'");
    if ($table_check && $table_check->num_rows > 0) {
        $delete_details = "DELETE FROM subject_details WHERE id_matapelajaran = ?";
        $delete_stmt = $database->prepare($delete_details);
        $delete_stmt->bind_param("i", $subject_id);
        $delete_stmt->execute();
        
        if ($delete_stmt->error) {
            error_log("Error deleting from subject_details: " . $delete_stmt->error);
        }
        
        $delete_stmt->close();
    }

    // 4. Also remove from teacher_subject assignments if table exists
    $table_check2 = $database->query("SHOW TABLES LIKE 'teacher_subject'");
    if ($table_check2 && $table_check2->num_rows > 0) {
        $delete_assign = "DELETE FROM teacher_subject WHERE subject_id = ?";
        $delete_assign_stmt = $database->prepare($delete_assign);
        $delete_assign_stmt->bind_param("i", $subject_id);
        $delete_assign_stmt->execute();
        $delete_assign_stmt->close();
    }

    // 5. Commit transaction
    $database->commit();

    // Success response
    $response['success'] = true;
    $response['message'] = 'Subjek "' . htmlspecialchars($subject['nama']) . '" berjaya dipadam.';
    $response['subject_id'] = $subject_id;
    $response['subject_name'] = $subject['nama'];

    // Log success
    error_log("Subject successfully deleted - ID: {$subject_id}, Name: {$subject['nama']}");

} catch (Exception $e) {
    // Rollback on error
    if (isset($database) && $database) {
        $database->rollback();
    }
    
    // Log error
    error_log("Delete subject error: " . $e->getMessage() . 
              " in " . $e->getFile() . ":" . $e->getLine());
    
    $response['error'] = 'Ralat sistem: ' . $e->getMessage();
}

// Clean output buffer and send JSON
if (ob_get_level() > 0) {
    ob_end_clean();
}

echo json_encode($response);
exit();
?>