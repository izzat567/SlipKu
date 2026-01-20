<?php
// process_subject.php
require_once __DIR__ . '/../../config/connect.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'add_subject':
            // Add subject logic
            $nama = trim($_POST['subject_name']);
            $kod = trim($_POST['subject_code']);
            $tahun = $_POST['subject_year'];
            $jenis = $_POST['subject_type'] ?? 'core';
            $penerangan = $_POST['subject_description'] ?? '';
            $buku_teks = $_POST['subject_textbook'] ?? '';
            
            // Check if subject code already exists
            $check_sql = "SELECT id FROM matapelajaran WHERE kod = ?";
            $check_stmt = $database->prepare($check_sql);
            $check_stmt->bind_param("s", $kod);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();
            
            if ($check_result->num_rows > 0) {
                $response['message'] = "Kod subjek sudah wujud!";
            } else {
                // Insert into database
                $sql = "INSERT INTO matapelajaran (kod, nama, tahun, status) VALUES (?, ?, ?, 1)";
                $stmt = $database->prepare($sql);
                $stmt->bind_param("sss", $kod, $nama, $tahun);
                
                if ($stmt->execute()) {
                    $subject_id = $database->insert_id;
                    
                    // Insert details
                    $details_sql = "INSERT INTO subject_details (id_matapelajaran, jenis, penerangan, buku_teks) 
                                   VALUES (?, ?, ?, ?)";
                    $details_stmt = $database->prepare($details_sql);
                    $details_stmt->bind_param("isss", $subject_id, $jenis, $penerangan, $buku_teks);
                    $details_stmt->execute();
                    $details_stmt->close();
                    
                    $response['success'] = true;
                    $response['message'] = "Subjek berjaya ditambah!";
                    $response['subject_id'] = $subject_id;
                } else {
                    $response['message'] = "Gagal menambah subjek: " . $database->error;
                }
                $stmt->close();
            }
            $check_stmt->close();
            break;
            
        case 'edit_subject':
            // Edit subject logic
            $subject_id = $_POST['subject_id'];
            $nama = trim($_POST['subject_name']);
            $kod = trim($_POST['subject_code']);
            $tahun = $_POST['subject_year'];
            $jenis = $_POST['subject_type'];
            $penerangan = $_POST['subject_description'] ?? '';
            $buku_teks = $_POST['subject_textbook'] ?? '';
            
            $sql = "UPDATE matapelajaran SET nama = ?, kod = ?, tahun = ? WHERE id = ?";
            $stmt = $database->prepare($sql);
            $stmt->bind_param("sssi", $nama, $kod, $tahun, $subject_id);
            
            if ($stmt->execute()) {
                // Update or insert details
                $check_details = "SELECT id FROM subject_details WHERE id_matapelajaran = ?";
                $check_stmt = $database->prepare($check_details);
                $check_stmt->bind_param("i", $subject_id);
                $check_stmt->execute();
                $check_result = $check_stmt->get_result();
                
                if ($check_result->num_rows > 0) {
                    $update_sql = "UPDATE subject_details SET jenis = ?, penerangan = ?, buku_teks = ? 
                                   WHERE id_matapelajaran = ?";
                    $update_stmt = $database->prepare($update_sql);
                    $update_stmt->bind_param("sssi", $jenis, $penerangan, $buku_teks, $subject_id);
                    $update_stmt->execute();
                    $update_stmt->close();
                } else {
                    $insert_sql = "INSERT INTO subject_details (id_matapelajaran, jenis, penerangan, buku_teks) 
                                   VALUES (?, ?, ?, ?)";
                    $insert_stmt = $database->prepare($insert_sql);
                    $insert_stmt->bind_param("isss", $subject_id, $jenis, $penerangan, $buku_teks);
                    $insert_stmt->execute();
                    $insert_stmt->close();
                }
                $check_stmt->close();
                
                $response['success'] = true;
                $response['message'] = "Subjek berjaya dikemaskini!";
            } else {
                $response['message'] = "Gagal mengemaskini subjek: " . $database->error;
            }
            $stmt->close();
            break;
            
        case 'delete_subject':
            $subject_id = $_POST['subject_id'];
            
            $sql = "UPDATE matapelajaran SET status = 0 WHERE id = ?";
            $stmt = $database->prepare($sql);
            $stmt->bind_param("i", $subject_id);
            
            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = "Subjek berjaya dipadam!";
            } else {
                $response['message'] = "Gagal memadam subjek: " . $database->error;
            }
            $stmt->close();
            break;
            
        default:
            $response['message'] = "Tindakan tidak dikenali";
    }
}

echo json_encode($response);
?>