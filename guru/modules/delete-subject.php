<?php
// delete-subject.php - SIMPLE VERSION
session_start();
header('Content-Type: application/json');

// For testing, bypass session check
$_SESSION['user_id'] = 1;
$_SESSION['user_type'] = 'guru';

$response = ['success' => false, 'message' => ''];

// Get subject ID
$subject_id = isset($_GET['id']) ? intval($_GET['id']) : 
              (isset($_POST['id']) ? intval($_POST['id']) : 0);

if ($subject_id > 0) {
    // Connect to database
    require_once __DIR__ . '/../../config/connect.php';
    
    // Simple delete (soft delete)
    $sql = "UPDATE matapelajaran SET status = 0 WHERE id = ?";
    $stmt = $database->prepare($sql);
    $stmt->bind_param("i", $subject_id);
    
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Subjek berjaya dipadam (TEST MODE)';
    } else {
        $response['message'] = 'Error: ' . $database->error;
    }
    $stmt->close();
} else {
    $response['message'] = 'Invalid subject ID';
}

echo json_encode($response);
exit();
?>