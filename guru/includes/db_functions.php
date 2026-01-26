<?php
/**
 * FUNGSI DATABASE UNTUK MODUL PELAJAR SAYA
 * Versi Disederhanakan dan Dioptimumkan
 */

// ==================== DATABASE CONNECTION ====================
function getDatabaseConnection() {
    static $db = null;
    if ($db === null) {
        $host = 'localhost';
        $username = 'root';
        $password = ''; // XAMPP default kosong
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
 */
function getPelajarByGuru($guru_id, $search = '', $kelas = '', $tahun = '', $status = '', $prestasi = '') {
    $db = getDatabaseConnection();
    
    // Dapatkan kelas yang diajar oleh guru ini
    $sql_kelas = "SELECT kelas_id FROM guru_kelas WHERE guru_id = ?";
    $stmt = $db->prepare($sql_kelas);
    $stmt->bind_param("i", $guru_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $kelas_ids = [];
    while ($row = $result->fetch_assoc()) {
        $kelas_ids[] = $row['kelas_id'];
    }
    $stmt->close();
    
    if (empty($kelas_ids)) {
        return [];
    }
    
    // Query utama
    $sql = "SELECT 
                p.id,
                p.no_matrik,
                p.nama,
                p.kp_lama,
                p.kp_baru,
                p.jantina,
                p.status,
                k.nama as kelas_nama,
                k.tahun,
                COALESCE(AVG(m.markah), 0) as prestasi_purata,
                COALESCE(
                    (SELECT COUNT(*) FROM kehadiran kh 
                     WHERE kh.pelajar_id = p.id AND kh.status = 'hadir'), 
                    0
                ) as hadir_count,
                COALESCE(
                    (SELECT COUNT(*) FROM kehadiran kh 
                     WHERE kh.pelajar_id = p.id), 
                    0
                ) as total_kehadiran
            FROM pelajar p
            JOIN kelas_pelajar kp ON p.id = kp.pelajar_id
            JOIN kelas k ON kp.kelas_id = k.id
            LEFT JOIN markah m ON p.id = m.pelajar_id
            WHERE k.id IN (" . implode(',', array_fill(0, count($kelas_ids), '?')) . ")";
    
    $params = $kelas_ids;
    $types = str_repeat('i', count($kelas_ids));
    
    // Filter status
    $statusMap = [
        'active' => 1,
        'inactive' => 0,
        'graduated' => 2
    ];
    
    if (!empty($status) && isset($statusMap[$status])) {
        $sql .= " AND p.status = ?";
        $params[] = $statusMap[$status];
        $types .= "i";
    } else {
        $sql .= " AND p.status = 1"; // Default: aktif sahaja
    }
    
    // Search filter
    if (!empty($search)) {
        $sql .= " AND (p.nama LIKE ? OR p.no_matrik LIKE ?)";
        $searchTerm = "%$search%";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $types .= "ss";
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
    
    $sql .= " GROUP BY p.id, k.nama, k.tahun";
    $sql .= " ORDER BY p.nama ASC";
    
    try {
        $stmt = $db->prepare($sql);
        if (!$stmt) {
            error_log("Prepare failed: " . $db->error);
            return [];
        }
        
        if (!empty($params)) {
         $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $pelajar = [];
        while ($row = $result->fetch_assoc()) {
            // Kira peratus kehadiran
            $attendance = ($row['total_kehadiran'] > 0) 
                ? round(($row['hadir_count'] / $row['total_kehadiran']) * 100, 1) 
                : 0;
            
            $row['attendance_percentage'] = $attendance;
            $row['prestasi_purata'] = round($row['prestasi_purata'], 1);
            
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
        return [];
    }
}

/**
 * Dapatkan kelas untuk guru
 */
function getKelasByGuru($guru_id) {
    $db = getDatabaseConnection();
    
    $sql = "SELECT 
                k.id,
                k.nama,
                k.tahun,
                k.tarikh_mula,
                k.tarikh_tamat,
                COUNT(DISTINCT kp.pelajar_id) as jumlah_pelajar
            FROM kelas k
            JOIN guru_kelas gk ON k.id = gk.kelas_id
            LEFT JOIN kelas_pelajar kp ON k.id = kp.kelas_id
            WHERE gk.guru_id = ?
            GROUP BY k.id, k.nama, k.tahun
            ORDER BY k.tahun DESC, k.nama ASC";
    
    try {
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $guru_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $kelas = [];
        while ($row = $result->fetch_assoc()) {
            $kelas[] = $row;
        }
        
        $stmt->close();
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
    
    // Dapatkan kelas guru
    $kelas_ids = [];
    $sql_kelas = "SELECT kelas_id FROM guru_kelas WHERE guru_id = ?";
    $stmt = $db->prepare($sql_kelas);
    $stmt->bind_param("i", $guru_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $kelas_ids[] = $row['kelas_id'];
    }
    $stmt->close();
    
    if (empty($kelas_ids)) {
        return [
            'total_pelajar' => 0,
            'pelajar_aktif' => 0,
            'prestasi_purata' => 0,
            'kadar_kehadiran' => 0
        ];
    }
    
    // Query statistik
    $sql = "SELECT 
                COUNT(DISTINCT p.id) as total_pelajar,
                COUNT(DISTINCT CASE WHEN p.status = 1 THEN p.id END) as pelajar_aktif,
                COALESCE(AVG(m.markah), 0) as prestasi_purata,
                COALESCE(
                    AVG(
                        CASE WHEN kh.status = 'hadir' THEN 100 ELSE 0 END
                    ), 0
                ) as kadar_kehadiran
            FROM pelajar p
            JOIN kelas_pelajar kp ON p.id = kp.pelajar_id
            LEFT JOIN markah m ON p.id = m.pelajar_id
            LEFT JOIN kehadiran kh ON p.id = kh.pelajar_id
            WHERE kp.kelas_id IN (" . implode(',', $kelas_ids) . ")
            AND p.status = 1";
    
    $result = $db->query($sql);
    if ($result && $row = $result->fetch_assoc()) {
        return [
            'total_pelajar' => (int)$row['total_pelajar'],
            'pelajar_aktif' => (int)$row['pelajar_aktif'],
            'prestasi_purata' => round($row['prestasi_purata'], 1),
            'kadar_kehadiran' => round($row['kadar_kehadiran'], 1)
        ];
    }
    
    return [
        'total_pelajar' => 0,
        'pelajar_aktif' => 0,
        'prestasi_purata' => 0,
        'kadar_kehadiran' => 0
    ];
}

/**
 * Dapatkan pelajar berdasarkan ID
 */
function getPelajarById($id) {
    $db = getDatabaseConnection();
    
    $sql = "SELECT 
                p.*,
                GROUP_CONCAT(DISTINCT k.nama) as kelas_list,
                GROUP_CONCAT(DISTINCT k.tahun) as tahun_list,
                COALESCE(AVG(m.markah), 0) as purata_markah,
                COUNT(DISTINCT m.id) as bilangan_subjek
            FROM pelajar p
            LEFT JOIN kelas_pelajar kp ON p.id = kp.pelajar_id
            LEFT JOIN kelas k ON kp.kelas_id = k.id
            LEFT JOIN markah m ON p.id = m.pelajar_id
            WHERE p.id = ?
            GROUP BY p.id";
    
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
        }
        
        return $data;
        
    } catch(Exception $e) {
        error_log("Error in getPelajarById: " . $e->getMessage());
        return null;
    }
}

/**
 * Tambah pelajar baru
 */
function tambahPelajar($data) {
    $db = getDatabaseConnection();
    
    // Generate no_matrik (cth: S0012024)
    $year = date('Y');
    $sql_last = "SELECT no_matrik FROM pelajar WHERE no_matrik LIKE 'S%' ORDER BY id DESC LIMIT 1";
    $result = $db->query($sql_last);
    
    if ($result && $row = $result->fetch_assoc()) {
        // Extract number from existing no_matrik (cth: S0152024 → 15)
        preg_match('/S(\d+)/', $row['no_matrik'], $matches);
        $next_num = isset($matches[1]) ? intval($matches[1]) + 1 : 1;
    } else {
        $next_num = 1;
    }
    
    $no_matrik = 'S' . str_pad($next_num, 3, '0', STR_PAD_LEFT) . $year;
    
    // Insert pelajar
    $sql = "INSERT INTO pelajar (no_matrik, nama, kp_lama, kp_baru, jantina, status) 
            VALUES (?, ?, ?, ?, ?, ?)";
    
    try {
        $stmt = $db->prepare($sql);
        $jantina = ($data['jantina'] == 'male') ? 'L' : 'P';
        $status = match($data['status'] ?? 'active') {
            'active' => 1,
            'graduated' => 2,
            default => 0
        };
        
        $stmt->bind_param(
            "sssssi",
            $no_matrik,
            $data['nama'],
            $data['kp_lama'] ?? '',
            $data['kp_baru'] ?? '',
            $jantina,
            $status
        );
        
        $success = $stmt->execute();
        $student_id = $stmt->insert_id;
        $stmt->close();
        
        // Jika ada kelas dipilih, tambah ke kelas_pelajar
        if ($success && isset($data['kelas_id']) && !empty($data['kelas_id'])) {
            $sql_kelas = "INSERT INTO kelas_pelajar (kelas_id, pelajar_id, tarikh_daftar) 
                          VALUES (?, ?, CURDATE())";
            $stmt = $db->prepare($sql_kelas);
            $stmt->bind_param("ii", $data['kelas_id'], $student_id);
            $stmt->execute();
            $stmt->close();
        }
        
        return $success;
        
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
                kp_lama = ?, 
                kp_baru = ?, 
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
            "ssssii",
            $data['nama'],
            $data['kp_lama'] ?? '',
            $data['kp_baru'] ?? '',
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
 * Cari pelajar
 */
function cariPelajar($keyword, $guru_id = null) {
    $db = getDatabaseConnection();
    
    $sql = "SELECT 
                p.*,
                k.nama as kelas_nama,
                k.tahun
            FROM pelajar p
            LEFT JOIN kelas_pelajar kp ON p.id = kp.pelajar_id
            LEFT JOIN kelas k ON kp.kelas_id = k.id
            WHERE (p.nama LIKE ? OR p.no_matrik LIKE ? OR p.kp_lama LIKE ? OR p.kp_baru LIKE ?)
            AND p.status = 1";
    
    if ($guru_id) {
        $sql .= " AND EXISTS (
                    SELECT 1 FROM guru_kelas gk 
                    WHERE gk.kelas_id = k.id AND gk.guru_id = ?
                )";
    }
    
    $sql .= " ORDER BY p.nama LIMIT 50";
    
    try {
        $stmt = $db->prepare($sql);
        $keyword = "%$keyword%";
        
        if ($guru_id) {
            $stmt->bind_param("ssssi", $keyword, $keyword, $keyword, $keyword, $guru_id);
        } else {
            $stmt->bind_param("ssss", $keyword, $keyword, $keyword, $keyword);
        }
        
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

// ==================== HELPER FUNCTIONS ====================

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
}

/**
 * Dapatkan semua kelas untuk dropdown
 */
function getAllKelas($aktif_sahaja = true) {
    $db = getDatabaseConnection();
    
    $sql = "SELECT k.*, 
                   COUNT(kp.pelajar_id) as jumlah_pelajar
            FROM kelas k
            LEFT JOIN kelas_pelajar kp ON k.id = kp.kelas_id";
    
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
?>