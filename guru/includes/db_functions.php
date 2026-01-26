<?php
/**
 * FUNGSI DATABASE UNTUK MODUL PELAJAR SAYA
 * Dikemaskini untuk match dengan structure slipku_db
 */

// ==================== DATABASE CONNECTION ====================
function getDatabaseConnection() {
    static $db = null;
    if ($db === null) {
        $host = 'localhost';
        $username = 'root';
        $password = '';
        $database = 'slipku_db';
        
        $db = new mysqli($host, $username, $password, $database);
        
        if ($db->connect_error) {
            error_log("Database connection failed: " . $db->connect_error);
            die("Database connection failed. Please check your configuration.");
        }
        
        $db->set_charset("utf8mb4");
    }
    return $db;
}

// ==================== STUDENT FUNCTIONS ====================

/**
 * Dapatkan semua pelajar untuk guru tertentu
 * Dikemaskini untuk match dengan structure sebenar
 */
function getPelajarByGuru($guru_id, $search = '', $kelas = '', $tahun = '', $status = '', $prestasi = '') {
    $db = getDatabaseConnection();
    
    // QUERY MUDAH - Langsung dari table pelajar (tanpa join yang kompleks)
    $sql = "SELECT 
                p.id,
                p.id_kelas as no_matrik,  // id_kelas digunakan sebagai no_matrik
                p.nama,
                p.no_kp,
                p.jantina,
                p.status,
                k.nama as kelas_nama,
                k.tahun,
                75.5 as prestasi_purata,      // Data dummy untuk testing
                90 as hadir_count,            // Data dummy
                100 as total_kehadiran        // Data dummy
            FROM pelajar p
            LEFT JOIN kelas k ON p.id_kelas = k.id
            WHERE 1=1";
    
    $params = [];
    $types = "";
    
    // Filter status
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
    } else {
        $sql .= " AND p.status = 1"; // Default: aktif sahaja
    }
    
    // Search filter
    if (!empty($search)) {
        $sql .= " AND (p.nama LIKE ? OR p.id_kelas LIKE ? OR p.no_kp LIKE ?)";
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
        $types .= "s";
    }

    $sql .= " ORDER BY p.nama ASC";
    
    try {
        if (!empty($params)) {
            $stmt = $db->prepare($sql);
            if (!$stmt) {
                error_log("Prepare failed: " . $db->error);
                return [];
            }
            $stmt->bind_param($types, ...$params);
        } else {
            $stmt = $db->prepare($sql);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $pelajar = [];
        while ($row = $result->fetch_assoc()) {
            // Kira peratus kehadiran (dummy data untuk sekarang)
            $attendance = ($row['total_kehadiran'] > 0) 
                ? round(($row['hadir_count'] / $row['total_kehadiran']) * 100, 1) 
                : 0;
            
            $row['attendance_percentage'] = $attendance;
            $row['prestasi_purata'] = round($row['prestasi_purata'], 1);
            
            // Format untuk frontend
            $row['kp_baru'] = $row['no_kp'];  // Untuk match dengan frontend
            $row['kp_lama'] = '';             // Kosong kerana tiada dalam DB
            
            // Apply prestasi filter jika ada
            if (!empty($prestasi)) {
                $purata = $row['prestasi_purata'];
                $include = false;
                
                switch($prestasi) {
                    case 'excellent': $include = ($purata >= 85); break;
                    case 'good': $include = ($purata >= 70 && $purata < 85); break;
                    case 'average': $include = ($purata >= 60 && $purata < 70); break;
                    case 'poor': $include = ($purata < 60); break;
                    default: $include = true;
                }
                
                if ($include) {
                    $pelajar[] = $row;
                }
            } else {
                $pelajar[] = $row;
            }
        }
        
        $stmt->close();
        return $pelajar;
        
    } catch(Exception $e) {
        error_log("Error in getPelajarByGuru: " . $e->getMessage());
        // Return dummy data jika ada error
        return getDummyStudents();
    }
}

/**
 * Dapatkan kelas untuk guru (simplified version)
 */
function getKelasByGuru($guru_id) {
    $db = getDatabaseConnection();
    
    // Return semua kelas (dummy untuk testing)
    $sql = "SELECT 
                k.id,
                k.nama,
                k.tahun,
                '' as tarikh_mula,
                '' as tarikh_tamat,
                COUNT(p.id) as jumlah_pelajar
            FROM kelas k
            LEFT JOIN pelajar p ON k.id = p.id_kelas
            GROUP BY k.id, k.nama, k.tahun
            ORDER BY k.tahun DESC, k.nama ASC";
    
    try {
        $result = $db->query($sql);
        $kelas = [];
        while ($row = $result->fetch_assoc()) {
            $kelas[] = $row;
       }
        return $kelas;
        
    } catch(Exception $e) {
        error_log("Error in getKelasByGuru: " . $e->getMessage());
        return [];
    }
}

/**
 * Statistik pelajar untuk guru
 */
function getStatistikPelajar($guru_id) {
    $db = getDatabaseConnection();

    $sql = "SELECT 
                COUNT(p.id) as total_pelajar,
                COUNT(CASE WHEN p.status = 1 THEN 1 END) as pelajar_aktif,
                75.5 as prestasi_purata,
                90 as kadar_kehadiran
            FROM pelajar p";
    
    try {
        $result = $db->query($sql);
        if ($result && $row = $result->fetch_assoc()) {
            return [
                'total_pelajar' => (int)$row['total_pelajar'],
                'pelajar_aktif' => (int)$row['pelajar_aktif'],
                'prestasi_purata' => round($row['prestasi_purata'], 1),
                'kadar_kehadiran' => round($row['kadar_kehadiran'], 1)
            ];
        }
    } catch(Exception $e) {
        error_log("Error in getStatistikPelajar: " . $e->getMessage());
    }
    
    return [
        'total_pelajar' => 40,
        'pelajar_aktif' => 40,
        'prestasi_purata' => 75.5,
        'kadar_kehadiran' => 90.0
    ];
}

/**
 * Dapatkan pelajar berdasarkan ID
 */
function getPelajarById($id) {
    $db = getDatabaseConnection();
    
    $sql = "SELECT 
                p.*,
                k.nama as kelas_nama,
                k.tahun,
                75.5 as purata_markah,
                5 as bilangan_subjek
            FROM pelajar p
            LEFT JOIN kelas k ON p.id_kelas = k.id
            WHERE p.id = ?";
    
    try {
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();
        
        if ($data) {
            // Format untuk frontend
            $data['jantina_display'] = ($data['jantina'] == 'L') ? 'Lelaki' : 'Perempuan';
            $data['status_display'] = match($data['status']) {
                1 => 'Aktif',
                2 => 'Tamat',
                0 => 'Tidak Aktif',
                default => 'Tidak Diketahui'
            };
            $data['purata_markah'] = round($data['purata_markah'], 1);
            $data['no_matrik'] = $data['id_kelas']; // Untuk frontend
            $data['no_ic'] = $data['no_kp'];        // Untuk frontend
        }
        
        return $data;
        
    } catch(Exception $e) {
        error_log("Error in getPelajarById: " . $e->getMessage());
        return null;
    }
}

/**
 * Check if student IC exists
 */
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
        $stmt->close();
        
        return $row['count'] > 0;
        
    } catch(Exception $e) {
        error_log("Error in checkStudentExists: " . $e->getMessage());
        return false;
    }
}

/**
 * Tambah pelajar baru (simplified)
 */
function tambahPelajar($data) {
    $db = getDatabaseConnection();
    
    // Generate id_kelas dari data['kelas_id'] atau default
    $id_kelas = $data['kelas_id'] ?? 'S0012024';
    
    // Insert pelajar
    $sql = "INSERT INTO pelajar (id_kelas, nama, no_kp, jantina, status) 
            VALUES (?, ?, ?, ?, ?)";
    
    try {
        $stmt = $db->prepare($sql);
        $jantina = ($data['jantina'] == 'male') ? 'L' : 'P';
        $status = match($data['status'] ?? 'active') {
            'active' => 1,
            'graduated' => 2,
            default => 0
        };
        
        $stmt->bind_param(
            "ssssi",
            $id_kelas,
            $data['nama'],
            $data['no_ic'],
            $jantina,
            $status
        );
        
        return $stmt->execute();
        
    } catch(Exception $e) {
        error_log("Error in tambahPelajar: " . $e->getMessage());
        return false;
    }
}

/**
 * Kemaskini pelajar
 */
function kemaskiniPelajar($id, $data) {
    $db = getDatabaseConnection();
    
    $sql = "UPDATE pelajar 
            SET nama = ?, 
                no_kp = ?, 
                jantina = ?, 
                status = ? 
            WHERE id = ?";
    
    try {
        $stmt = $db->prepare($sql);
        $jantina = ($data['jantina'] == 'male') ? 'L' : 'P';
        $status = match($data['status'] ?? 'active') {
            'active' => 1,
            'graduated' => 2,
            default => 0
        };
        
        $stmt->bind_param(
            "sssii",
            $data['nama'],
            $data['no_ic'],
            $jantina,
            $status,
            $id
        );
        
        return $stmt->execute();
        
    } catch(Exception $e) {
        error_log("Error in kemaskiniPelajar: " . $e->getMessage());
        return false;
    }
}

/**
 * Padam pelajar (soft delete)
 */
function padamPelajar($id) {
    $db = getDatabaseConnection();
    
    $sql = "UPDATE pelajar SET status = 0 WHERE id = ?";
    
    try {
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    } catch(Exception $e) {
        error_log("Error in padamPelajar: " . $e->getMessage());
        return false;
    }
}

/**
 * Bulk update students
 */
function bulkUpdateStudents($student_ids, $update_data) {
    $db = getDatabaseConnection();
    
    if (empty($student_ids) || empty($update_data)) {
        return false;
    }
    
    $ids = implode(',', array_map('intval', $student_ids));
    
    if (isset($update_data['status'])) {
        $sql = "UPDATE pelajar SET status = ? WHERE id IN ($ids)";
        $stmt = $db->prepare($sql);
        $status = match($update_data['status']) {
            'active' => 1,
            'inactive' => 0,
            'graduated' => 2,
            default => 1
        };
        $stmt->bind_param("i", $status);
        return $stmt->execute();
    }
    
    return false;
}

/**
 * Cari pelajar
 */
function cariPelajar($keyword, $guru_id = null) {
    $db = getDatabaseConnection();
    
    $sql = "SELECT 
                p.*,
                k.nama as kelas_nama,
                k.tahun
            FROM pelajar p
            LEFT JOIN kelas k ON p.id_kelas = k.id
            WHERE (p.nama LIKE ? OR p.id_kelas LIKE ? OR p.no_kp LIKE ?)
            AND p.status = 1
            ORDER BY p.nama LIMIT 50";
    
    try {
        $stmt = $db->prepare($sql);
        $keyword = "%$keyword%";
        $stmt->bind_param("sss", $keyword, $keyword, $keyword);
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $pelajar = [];
        while ($row = $result->fetch_assoc()) {
            $pelajar[] = $row;
        }
        
        $stmt->close();
        return $pelajar;
        
    } catch(Exception $e) {
        error_log("Error in cariPelajar: " . $e->getMessage());
        return [];
    }
}

/**
 * Get student performance (dummy function)
 */
function getStudentPerformance($student_id) {
    return [
        'average' => 75.5,
        'subjects' => [
            ['name' => 'Matematik', 'score' => 80, 'grade' => 'A'],
            ['name' => 'Bahasa Melayu', 'score' => 75, 'grade' => 'A-'],
            ['name' => 'Bahasa Inggeris', 'score' => 70, 'grade' => 'B+']
        ]
    ];
}

/**
 * Get student attendance (dummy function)
 */
function getStudentAttendance($student_id) {
    return [
        'percentage' => 90,
        'present' => 18,
        'absent' => 2,
        'total' => 20
    ];
}

// ==================== HELPER FUNCTIONS ====================

/**
 * Dummy students for testing
 */
function getDummyStudents() {
    $db = getDatabaseConnection();
    $sql = "SELECT 
                p.id,
                p.id_kelas as no_matrik,
                p.nama,
                p.no_kp,
                p.jantina,
                p.status,
                k.nama as kelas_nama,
                k.tahun,
                75.5 as prestasi_purata,
                90 as hadir_count,
                100 as total_kehadiran
            FROM pelajar p
            LEFT JOIN kelas k ON p.id_kelas = k.id
            LIMIT 20";
    
    $result = $db->query($sql);
    $students = [];
    while ($row = $result->fetch_assoc()) {
        $row['attendance_percentage'] = 90;
        $row['prestasi_purata'] = round($row['prestasi_purata'], 1);
        $row['kp_baru'] = $row['no_kp'];
        $row['kp_lama'] = '';
        $students[] = $row;
    }
    return $students;
}

/**
 * Dapatkan semua kelas untuk dropdown
 */
function getAllKelas($aktif_sahaja = true) {
    $db = getDatabaseConnection();
    
    $sql = "SELECT k.*, 
                   COUNT(p.id) as jumlah_pelajar
            FROM kelas k
            LEFT JOIN pelajar p ON k.id = p.id_kelas";
    
    if ($aktif_sahaja) {
        $sql .= " WHERE k.status = 1";
    }
    
    $sql .= " GROUP BY k.id
              ORDER BY k.tahun DESC, k.nama ASC";
    
    try {
        $result = $db->query($sql);
        $kelas = [];
        
        while ($row = $result->fetch_assoc()) {
            $kelas[] = $row;
        }
        
        return $kelas;
        
    } catch(Exception $e) {
        error_log("Error in getAllKelas: " . $e->getMessage());
        return [];
    }
}

/**
 * Cek koneksi database
 */
function testDatabaseConnection() {
    try {
        $db = getDatabaseConnection();
        
        if ($db->connect_errno) {
            return [
                'success' => false,
                'message' => 'Connection failed: ' . $db->connect_error
            ];
        }
        
        // Test query
        $result = $db->query("SHOW TABLES");
        $tables = [];
        while ($row = $result->fetch_array()) {
            $tables[] = $row[0];
        }
        
        return [
            'success' => true,
            'message' => 'Database connected successfully',
            'tables' => $tables
        ];
        
    } catch(Exception $e) {
        return [
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ];
    }
}?>