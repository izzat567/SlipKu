<?php
/**
 * FUNGSI DATABASE UNTUK MODUL PELAJAR SAYA
 * Disesuaikan dengan struktur database slipku_db
 */

// Dapatkan koneksi database
function getDatabaseConnection() {
    static $database = null;
    if ($database === null) {
        require_once __DIR__ . '/../../config/connect.php';
        // Since connect.php returns $database, we need to capture it
        $database = include(__DIR__ . '/../../config/connect.php');
    }
    return $database;
}

// Dapatkan pelajar berdasarkan guru
function getPelajarByGuru($guru_id, $search = '', $kelas = '', $tahun = '', $status = '', $prestasi = '') {
    $db = getDatabaseConnection();
    
    // First, get kelas that this guru teaches from guru_kelas table
    $kelasQuery = "SELECT DISTINCT kelas_id FROM guru_kelas WHERE guru_id = ?";
    $stmt = $db->prepare($kelasQuery);
    $stmt->bind_param("i", $guru_id);
    $stmt->execute();
    $kelasResult = $stmt->get_result();
    
    $kelas_ids = [];
    while ($row = $kelasResult->fetch_assoc()) {
        $kelas_ids[] = $row['kelas_id'];
    }
    
    // If teacher has no classes assigned, return empty array
    if (empty($kelas_ids)) {
        return [];
    }
    
    // Build the main query
    $sql = "SELECT 
                p.id,
                p.id_kelas as student_id,
                p.nama,
                p.no_kp,
                p.jantina,
                p.status,
                k.nama as kelas_nama,
                k.tahun,
                COALESCE((SELECT AVG(markah) FROM markah WHERE id_pelajar = p.id), 0) as prestasi_purata,
                COALESCE((SELECT COUNT(*) FROM kehadiran WHERE id_pelajar = p.id AND status = 'hadir'), 0) as jumlah_kehadiran,
                COALESCE((SELECT COUNT(*) FROM kehadiran WHERE id_pelajar = p.id), 1) as total_kehadiran
            FROM pelajar p
            LEFT JOIN kelas k ON p.id_kelas LIKE CONCAT('%', k.nama, '%') OR p.id_kelas LIKE CONCAT(k.nama, '%')
            WHERE p.status = 1";
    
    $params = [];
    $types = "";
    
    // Filter by teacher's classes
    if (!empty($kelas_ids)) {
        $placeholders = implode(',', array_fill(0, count($kelas_ids), '?'));
        $sql .= " AND k.id IN ($placeholders)";
        $params = array_merge($params, $kelas_ids);
        $types .= str_repeat('i', count($kelas_ids));
    }
    
    // Search filter
    if (!empty($search)) {
        $sql .= " AND (p.nama LIKE ? OR p.no_kp LIKE ? OR p.id_kelas LIKE ?)";
        $searchTerm = "%$search%";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $types .= "sss";
    }
    
    // Kelas filter
    if (!empty($kelas)) {
        $sql .= " AND k.nama = ?";
        $params[] = $kelas;
        $types .= "s";
    }
    
    // Tahun filter
    if (!empty($tahun)) {
        $sql .= " AND k.tahun = ?";
        $params[] = $tahun;
        $types .= "i";
    }
    
    // Status filter
    if (!empty($status)) {
        $statusMap = [
            'active' => 1,
            'inactive' => 0,
            'graduated' => 2
        ];
        if (isset($statusMap[$status])) {
            $sql .= " AND p.status = ?";
            $params[] = $statusMap[$status];
            $types .= "i";
        }
    }
    
    $sql .= " ORDER BY p.nama ASC";
    
    try {
        if (!empty($params)) {
            $stmt = $db->prepare($sql);
            $stmt->bind_param($types, ...$params);
        } else {
            $stmt = $db->prepare($sql);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $pelajar = $result->fetch_all(MYSQLI_ASSOC);
        
        // Calculate attendance percentage and apply performance filter
        $filteredPelajar = [];
        foreach ($pelajar as $p) {
            // Calculate attendance percentage
            $attendance = ($p['total_kehadiran'] > 0) 
                ? round(($p['jumlah_kehadiran'] / $p['total_kehadiran']) * 100, 1) 
                : 0;
            
            $p['attendance_percentage'] = $attendance;
            $p['prestasi_purata'] = round($p['prestasi_purata'], 1);
            
            // Apply performance filter
            if (!empty($prestasi)) {
                $performance = $p['prestasi_purata'];
                switch($prestasi) {
                    case 'excellent': 
                        if ($performance >= 85) $filteredPelajar[] = $p;
                        break;
                    case 'good': 
                        if ($performance >= 70 && $performance < 85) $filteredPelajar[] = $p;
                        break;
                    case 'average': 
                        if ($performance >= 60 && $performance < 70) $filteredPelajar[] = $p;
                        break;
                    case 'poor': 
                        if ($performance < 60) $filteredPelajar[] = $p;
                        break;
                    default:
                        $filteredPelajar[] = $p;
                }
            } else {
                $filteredPelajar[] = $p;
            }
        }
        
        return $filteredPelajar;
    } catch(Exception $e) {
        error_log("Error getPelajarByGuru: " . $e->getMessage());
        return [];
    }
}

// Dapatkan kelas yang diajar oleh guru
function getKelasByGuru($guru_id) {
    $db = getDatabaseConnection();
    
    $sql = "SELECT 
                k.*,
                COUNT(p.id) as jumlah_pelajar
            FROM kelas k
            INNER JOIN guru_kelas gk ON k.id = gk.kelas_id
            LEFT JOIN pelajar p ON (p.id_kelas LIKE CONCAT('%', k.nama, '%') OR p.id_kelas LIKE CONCAT(k.nama, '%')) AND p.status = 1
            WHERE gk.guru_id = ?
            GROUP BY k.id
            ORDER BY k.tahun DESC, k.nama ASC";
    
    try {
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $guru_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    } catch(Exception $e) {
        error_log("Error getKelasByGuru: " . $e->getMessage());
        return [];
    }
}

// Dapatkan statistik pelajar untuk guru
function getStatistikPelajar($guru_id) {
    $db = getDatabaseConnection();
    
    // Get teacher's classes
    $kelasQuery = "SELECT DISTINCT kelas_id FROM guru_kelas WHERE guru_id = ?";
    $stmt = $db->prepare($kelasQuery);
    $stmt->bind_param("i", $guru_id);
    $stmt->execute();
    $kelasResult = $stmt->get_result();
    
    $kelas_ids = [];
    while ($row = $kelasResult->fetch_assoc()) {
        $kelas_ids[] = $row['kelas_id'];
    }
    
    if (empty($kelas_ids)) {
        return [
            'total_pelajar' => 0, 
            'pelajar_aktif' => 0, 
            'prestasi_purata' => 0, 
            'kadar_kehadiran' => 0
        ];
    }
    
    $placeholders = implode(',', array_fill(0, count($kelas_ids), '?'));
    
    $sql = "SELECT 
                COUNT(p.id) as total_pelajar,
                SUM(CASE WHEN p.status = 1 THEN 1 ELSE 0 END) as pelajar_aktif,
                COALESCE(AVG(m.markah), 0) as prestasi_purata,
                COALESCE(
                    (SELECT AVG(
                        CASE WHEN kh.status = 'hadir' THEN 100 ELSE 0 END
                    ) FROM kehadiran kh WHERE kh.id_pelajar = p.id), 
                    0
                ) as kadar_kehadiran_avg
            FROM pelajar p
            LEFT JOIN kelas k ON p.id_kelas LIKE CONCAT('%', k.nama, '%') OR p.id_kelas LIKE CONCAT(k.nama, '%')
            LEFT JOIN markah m ON p.id = m.id_pelajar
            WHERE k.id IN ($placeholders)
            AND p.status = 1";
    
    try {
        $stmt = $db->prepare($sql);
        $stmt->bind_param(str_repeat('i', count($kelas_ids)), ...$kelas_ids);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        
        if (!$data) {
            return [
                'total_pelajar' => 0, 
                'pelajar_aktif' => 0, 
                'prestasi_purata' => 0, 
                'kadar_kehadiran' => 0
            ];
        }
        
        return [
            'total_pelajar' => $data['total_pelajar'] ?? 0,
            'pelajar_aktif' => $data['pelajar_aktif'] ?? 0,
            'prestasi_purata' => round($data['prestasi_purata'] ?? 0, 1),
            'kadar_kehadiran' => round($data['kadar_kehadiran_avg'] ?? 0, 1)
        ];
    } catch(Exception $e) {
        error_log("Error getStatistikPelajar: " . $e->getMessage());
        return [
            'total_pelajar' => 0, 
            'pelajar_aktif' => 0, 
            'prestasi_purata' => 0, 
            'kadar_kehadiran' => 0
        ];
    }
}

// Tambah pelajar baru
function tambahPelajar($data) {
    $db = getDatabaseConnection();
    
    // Generate student ID - get last ID from database
    $lastIdQuery = "SELECT id_kelas FROM pelajar ORDER BY id DESC LIMIT 1";
    $result = $db->query($lastIdQuery);
    $lastStudent = $result->fetch_assoc();
    
    if ($lastStudent && preg_match('/S(\d+)/', $lastStudent['id_kelas'], $matches)) {
        $nextNum = intval($matches[1]) + 1;
    } else {
        $nextNum = 1;
    }
    
    $id_kelas = 'S' . str_pad($nextNum, 3, '0', STR_PAD_LEFT) . date('Y');
    
    // Convert gender from 'male'/'female' to 'L'/'P'
    $jantina = ($data['jantina'] == 'male') ? 'L' : 'P';
    
    // Default status
    $status = 1;
    if (isset($data['status'])) {
        $statusMap = [
            'active' => 1,
            'inactive' => 0,
            'graduated' => 2
        ];
        $status = isset($statusMap[$data['status']]) ? $statusMap[$data['status']] : 1;
    }
    
    $sql = "INSERT INTO pelajar (id_kelas, nama, no_kp, jantina, status) 
            VALUES (?, ?, ?, ?, ?)";
    
    try {
        $stmt = $db->prepare($sql);
        return $stmt->execute([
            $id_kelas,
            $data['nama'],
            $data['no_ic'],
            $jantina,
            $status
        ]);
    } catch(Exception $e) {
        error_log("Error tambahPelajar: " . $e->getMessage());
        return false;
    }
}

// Kemaskini pelajar
function kemaskiniPelajar($id, $data) {
    $db = getDatabaseConnection();
    
    // Convert gender from 'male'/'female' to 'L'/'P'
    $jantina = ($data['jantina'] == 'male') ? 'L' : 'P';
    
    // For status
    $status = 1;
    if (isset($data['status'])) {
        $statusMap = [
            'active' => 1,
            'inactive' => 0,
            'graduated' => 2
        ];
        $status = isset($statusMap[$data['status']]) ? $statusMap[$data['status']] : 1;
    }
    
    $sql = "UPDATE pelajar 
            SET nama = ?, no_kp = ?, jantina = ?, status = ?
            WHERE id = ?";
    
    try {
        $stmt = $db->prepare($sql);
        return $stmt->execute([
            $data['nama'],
            $data['no_ic'],
            $jantina,
            $status,
            $id
        ]);
    } catch(Exception $e) {
        error_log("Error kemaskiniPelajar: " . $e->getMessage());
        return false;
    }
}

// Padam pelajar (soft delete - set status to 0)
function padamPelajar($id) {
    $db = getDatabaseConnection();
    
    $sql = "UPDATE pelajar SET status = 0 WHERE id = ?";
    
    try {
        $stmt = $db->prepare($sql);
        return $stmt->execute([$id]);
    } catch(Exception $e) {
        error_log("Error padamPelajar: " . $e->getMessage());
        return false;
    }
}

// Dapatkan maklumat pelajar
function getPelajarById($id) {
    $db = getDatabaseConnection();
    
    $sql = "SELECT 
                p.*,
                k.nama as kelas_nama,
                k.tahun,
                COALESCE((SELECT AVG(markah) FROM markah WHERE id_pelajar = p.id), 0) as prestasi_purata,
                COALESCE((SELECT COUNT(*) FROM kehadiran WHERE id_pelajar = p.id AND status = 'hadir'), 0) as jumlah_kehadiran,
                COALESCE((SELECT COUNT(*) FROM kehadiran WHERE id_pelajar = p.id), 1) as total_kehadiran
            FROM pelajar p
            LEFT JOIN kelas k ON p.id_kelas LIKE CONCAT('%', k.nama, '%') OR p.id_kelas LIKE CONCAT(k.nama, '%')
            WHERE p.id = ?";
    
    try {
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        
        if ($data) {
            // Calculate attendance percentage
            $attendance = ($data['total_kehadiran'] > 0) 
                ? round(($data['jumlah_kehadiran'] / $data['total_kehadiran']) * 100, 1) 
                : 0;
            
            $data['attendance_percentage'] = $attendance;
            $data['prestasi_purata'] = round($data['prestasi_purata'], 1);
            
            // Convert for frontend
            $data['gender'] = ($data['jantina'] == 'L') ? 'male' : 'female';
            $data['status_text'] = $data['status'] == 1 ? 'active' : ($data['status'] == 2 ? 'graduated' : 'inactive');
        }
        
        return $data;
    } catch(Exception $e) {
        error_log("Error getPelajarById: " . $e->getMessage());
        return false;
    }
}

// Dapatkan semua kelas untuk dropdown
function getAllKelas() {
    $db = getDatabaseConnection();
    
    $sql = "SELECT * FROM kelas WHERE status = 1 ORDER BY tahun DESC, nama ASC";
    
    try {
        $result = $db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    } catch(Exception $e) {
        error_log("Error getAllKelas: " . $e->getMessage());
        return [];
    }
}

// Check if student exists by IC
function checkStudentExists($no_ic, $exclude_id = null) {
    $db = getDatabaseConnection();
    
    $sql = "SELECT COUNT(*) as count FROM pelajar WHERE no_kp = ?";
    
    if ($exclude_id) {
        $sql .= " AND id != ?";
    }
    
    try {
        $stmt = $db->prepare($sql);
        
        if ($exclude_id) {
            $stmt->bind_param("si", $no_ic, $exclude_id);
        } else {
            $stmt->bind_param("s", $no_ic);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'] > 0;
    } catch(Exception $e) {
        error_log("Error checkStudentExists: " . $e->getMessage());
        return false;
    }
}

// Get student performance details
function getStudentPerformance($student_id) {
    $db = getDatabaseConnection();
    
    $sql = "SELECT 
                COALESCE(AVG(markah), 0) as average_score,
                COUNT(*) as total_subjects,
                MIN(markah) as min_score,
                MAX(markah) as max_score
            FROM markah 
            WHERE id_pelajar = ?";
    
    try {
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        
        if ($data) {
            $data['average_score'] = round($data['average_score'], 1);
        }
        
        return $data ?: [
            'average_score' => 0, 
            'total_subjects' => 0, 
            'min_score' => 0, 
            'max_score' => 0
        ];
    } catch(Exception $e) {
        error_log("Error getStudentPerformance: " . $e->getMessage());
        return [
            'average_score' => 0, 
            'total_subjects' => 0, 
            'min_score' => 0, 
            'max_score' => 0
        ];
    }
}

// Get student attendance
function getStudentAttendance($student_id) {
    $db = getDatabaseConnection();
    
    $sql = "SELECT 
                COUNT(*) as total_days,
                SUM(CASE WHEN status = 'hadir' THEN 1 ELSE 0 END) as days_present,
                SUM(CASE WHEN status = 'sakit' THEN 1 ELSE 0 END) as days_sick,
                SUM(CASE WHEN status = 'cuti' THEN 1 ELSE 0 END) as days_leave,
                SUM(CASE WHEN status = 'tidak_hadir' THEN 1 ELSE 0 END) as days_absent
            FROM kehadiran 
            WHERE id_pelajar = ?";
    
    try {
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        
        if ($data && $data['total_days'] > 0) {
            $data['attendance_rate'] = round(($data['days_present'] / $data['total_days']) * 100, 1);
        } else {
            $data['attendance_rate'] = 0;
        }
        
        return $data;
    } catch(Exception $e) {
        error_log("Error getStudentAttendance: " . $e->getMessage());
        return null;
    }
}

// Bulk update students
function bulkUpdateStudents($student_ids, $update_data) {
    $db = getDatabaseConnection();
    
    if (empty($student_ids)) {
        return false;
    }
    
    $placeholders = implode(',', array_fill(0, count($student_ids), '?'));
    $types = str_repeat('i', count($student_ids));
    
    $sql = "UPDATE pelajar SET ";
    $params = [];
    $updates = [];
    
    // Build update fields
    if (isset($update_data['status'])) {
        $statusMap = [
            'active' => 1,
            'inactive' => 0,
            'graduated' => 2
        ];
        if (isset($statusMap[$update_data['status']])) {
            $updates[] = "status = ?";
            $params[] = $statusMap[$update_data['status']];
            $types .= 'i';
        }
    }
    
    if (empty($updates)) {
        return false;
    }
    
    $sql .= implode(', ', $updates);
    $sql .= " WHERE id IN ($placeholders)";
    $params = array_merge($params, $student_ids);
    
    try {
        $stmt = $db->prepare($sql);
        return $stmt->execute($params);
    } catch(Exception $e) {
        error_log("Error bulkUpdateStudents: " . $e->getMessage());
        return false;
    }
}

// Bulk delete students
function bulkDeleteStudents($student_ids) {
    $db = getDatabaseConnection();
    
    if (empty($student_ids)) {
        return false;
    }
    
    $placeholders = implode(',', array_fill(0, count($student_ids), '?'));
    
    $sql = "UPDATE pelajar SET status = 0 WHERE id IN ($placeholders)";
    
    try {
        $stmt = $db->prepare($sql);
        return $stmt->execute($student_ids);
    } catch(Exception $e) {
        error_log("Error bulkDeleteStudents: " . $e->getMessage());
        return false;
    }
}