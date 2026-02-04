<?php 


function createAdmin($conn, $nama, $email, $katalaluan, $status = '1') {

    if (empty($nama) || empty($katalaluan)) {
        throw new Exception("Name and password are required");
    }
    
    // Validate email if provided
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email format");
    }
    
    // Check if email already exists (using mysqli_real_escape_string for safety)
    if (!empty($email)) {
        $escapedEmail = mysqli_real_escape_string($conn, $email);
        $checkQuery = "SELECT id FROM admins WHERE email = '$escapedEmail'";
        $checkResult = mysqli_query($conn, $checkQuery);
        
        if ($checkResult && mysqli_num_rows($checkResult) > 0) {
            mysqli_free_result($checkResult);
            throw new Exception("Email already exists");
        }
        if ($checkResult) {
            mysqli_free_result($checkResult);
        }
    }
    
    // Hash the password
    $hashedPassword = password_hash($katalaluan, PASSWORD_DEFAULT);
    
    // Generate current timestamp for creation date
    $tarikh_cipta = date('Y-m-d H:i:s');
    
    // Get next available ID
    $result = mysqli_query($conn, "SELECT MAX(id) as max_id FROM admins");
    $row = mysqli_fetch_assoc($result);
    $newId = (!empty($row['max_id']) ? $row['max_id'] + 1 : 1);
    if ($result) {
        mysqli_free_result($result);
    }
    
    // Escape all user inputs
    $escapedEmail = mysqli_real_escape_string($conn, $email);
    $escapedNama = mysqli_real_escape_string($conn, $nama);
    $escapedKatalaluan = mysqli_real_escape_string($conn, $hashedPassword);
    $escapedTarikhCipta = mysqli_real_escape_string($conn, $tarikh_cipta);
    $escapedStatus = intval($status);
    
    // Create and execute insert query
    $query = "INSERT INTO admins (id, email, nama, katalaluan, tarikh_cipta, status) 
              VALUES ($newId, '$escapedEmail', '$escapedNama', '$escapedKatalaluan', '$escapedTarikhCipta', $escapedStatus)";
    
    if (mysqli_query($conn, $query)) {
        return true;
    } else {
        throw new Exception("Failed to create admin: " . mysqli_error($conn));
    }
}

function createPelajar($conn, $id_kelas, $nama, $no_kp, $jantina){
    // Escape inputs (basic protection)
    $id_kelas = mysqli_real_escape_string($conn, $id_kelas);
    $nama = mysqli_real_escape_string($conn, $nama);
    $no_kp = mysqli_real_escape_string($conn, $no_kp);
    $jantina = mysqli_real_escape_string($conn, $jantina);
    
    $sql = "INSERT INTO pelajar (id_kelas, nama, no_kp, jantina, status) 
            VALUES ('$id_kelas', '$nama', '$no_kp', '$jantina', 'aktif')";
    
    $result = mysqli_query($conn, $sql);
    
    if (!$result) {
        // Get the error
        $error = mysqli_error($conn);
        error_log("SQL Error: " . $error);
        
        return [
            'success' => false, 
            'message' => 'Ralat sistem: Gagal mencipta pelajar',
            'sql_error' => $error // For debugging only
        ];
    }
    
    $inserted_id = mysqli_insert_id($conn);
    
    return [
        'success' => true, 
        'message' => 'Pelajar berjaya dicipta',
        'id' => $inserted_id
    ];
}

// Test function to debug the issue:
function testCreateAdmin($conn) {
    
    // Enable error reporting
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    
    try {
        $result = createAdmin($conn, 'admin', 'admin@mail.com', '1234', 1);
        
        if ($result['success']) {
            echo "Success! Admin ID: " . $result['admin_id'];
        } else {
            echo "Error: " . $result['message'];
        }
    } catch (Exception $e) {
        echo "Exception: " . $e->getMessage();
    }
    
    mysqli_close($conn);
}
