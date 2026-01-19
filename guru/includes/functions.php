<?php
require_once 'config/connect.php';

class GuruFunctions {
    private $db;
    
    public function __construct() {
        global $database;
        $this->db = $database;
    }
    
    // Dapatkan statistik guru
    public function getDashboardStats($guru_id) {
        $stats = [];
        
        // Jumlah pelajar
        $sql = "SELECT COUNT(DISTINCT p.id) as total 
                FROM pelajar p 
                INNER JOIN kelas k ON p.id_kelas LIKE CONCAT('%', k.nama, '%') 
                WHERE k.tahun IN (5,6)";
        $result = $this->db->query($sql);
        $stats['total_pelajar'] = $result->fetch_assoc()['total'] ?? 0;
        
        // Jumlah ujian belum dinilai
        $sql = "SELECT COUNT(*) as total FROM peperiksaan WHERE status = 1";
        $result = $this->db->query($sql);
        $stats['ujian_belum_dinilai'] = $result->fetch_assoc()['total'] ?? 0;
        
        // Jumlah subjek
        $sql = "SELECT COUNT(*) as total FROM matapelajaran WHERE status = 1";
        $result = $this->db->query($sql);
        $stats['total_subjek'] = $result->fetch_assoc()['total'] ?? 0;
        
        // Jumlah kelas
        $sql = "SELECT COUNT(*) as total FROM kelas WHERE tahun IN (5,6) AND status = 1";
        $result = $this->db->query($sql);
        $stats['total_kelas'] = $result->fetch_assoc()['total'] ?? 0;
        
        return $stats;
    }
    
    // Dapatkan senarai kelas yang dikendalikan
    public function getKelasSaya() {
        $sql = "SELECT * FROM kelas WHERE tahun IN (5,6) AND status = 1 ORDER BY tahun, nama";
        $result = $this->db->query($sql);
        
        $kelas = [];
        while ($row = $result->fetch_assoc()) {
            $kelas[] = $row;
        }
        
        return $kelas;
    }
    
    // Dapatkan senarai pelajar berdasarkan kelas
    public function getPelajarByKelas($kelas_nama) {
        $sql = "SELECT * FROM pelajar 
                WHERE id_kelas LIKE ? AND status = 1 
                ORDER BY nama";
        $stmt = $this->db->preparedQuery($sql, ["%$kelas_nama%"]);
        
        $pelajar = [];
        if ($stmt) {
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $pelajar[] = $row;
            }
        }
        
        return $pelajar;
    }
    
    // Dapatkan semua pelajar
    public function getAllPelajar() {
        $sql = "SELECT p.*, 
                SUBSTRING(p.id_kelas, 1, 2) as kelas 
                FROM pelajar p 
                WHERE p.status = 1 
                ORDER BY p.nama";
        $result = $this->db->query($sql);
        
        $pelajar = [];
        while ($row = $result->fetch_assoc()) {
            $pelajar[] = $row;
        }
        
        return $pelajar;
    }
    
    // Dapatkan senarai subjek
    public function getSubjekSaya() {
        $sql = "SELECT * FROM matapelajaran 
                WHERE tahun LIKE '%6%' OR tahun LIKE '%5%' 
                AND status = 1 
                ORDER BY nama";
        $result = $this->db->query($sql);
        
        $subjek = [];
        while ($row = $result->fetch_assoc()) {
            $subjek[] = $row;
        }
        
        return $subjek;
    }
    
    // Tambah markah pelajar
    public function tambahMarkah($data) {
        $sql = "INSERT INTO markah 
                (id_pelajar, id_perperiksaan, markah, gred, catatan, tarikh_cipta, tarikh_kemaskini, status) 
                VALUES (?, ?, ?, ?, ?, CURDATE(), CURDATE(), 1)";
        
        $params = [
            $data['id_pelajar'],
            $data['id_peperiksaan'],
            $data['markah'],
            $data['gred'],
            $data['catatan'] ?? ''
        ];
        
        $stmt = $this->db->preparedQuery($sql, $params);
        
        if ($stmt && $stmt->affected_rows > 0) {
            return [
                'success' => true,
                'message' => 'Markah berjaya ditambah!',
                'id' => $this->db->getLastInsertId()
            ];
        }
        
        return [
            'success' => false,
            'message' => 'Gagal menambah markah!'
        ];
    }
    
    // Semak markah pelajar
    public function getMarkahPelajar($pelajar_id = null, $peperiksaan_id = null) {
        $sql = "SELECT m.*, p.nama as nama_pelajar, pe.nama_peperiksaan, mat.nama as nama_subjek
                FROM markah m
                INNER JOIN pelajar p ON m.id_pelajar = p.id
                INNER JOIN peperiksaan pe ON m.id_perperiksaan = pe.id
                INNER JOIN matapelajaran mat ON pe.id_matapelajaran = mat.id
                WHERE m.status = 1";
        
        $params = [];
        
        if ($pelajar_id) {
            $sql .= " AND m.id_pelajar = ?";
            $params[] = $pelajar_id;
        }
        
        if ($peperiksaan_id) {
            $sql .= " AND m.id_perperiksaan = ?";
            $params[] = $peperiksaan_id;
        }
        
        $sql .= " ORDER BY m.tarikh_kemaskini DESC";
        
        $stmt = $this->db->preparedQuery($sql, $params);
        
        $markah = [];
        if ($stmt) {
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $markah[] = $row;
            }
        }
        
        return $markah;
    }
    
    // Dapatkan senarai peperiksaan
    public function getPeperiksaan() {
        $sql = "SELECT p.*, m.nama as nama_matapelajaran
                FROM peperiksaan p
                LEFT JOIN matapelajaran m ON p.id_matapelajaran = m.id
                WHERE p.status = 1
                ORDER BY p.tarikh_mula DESC";
        $result = $this->db->query($sql);
        
        $peperiksaan = [];
        while ($row = $result->fetch_assoc()) {
            $peperiksaan[] = $row;
        }
        
        return $peperiksaan;
    }
    
    // Jana laporan prestasi
    public function janaLaporanPrestasi($tahun_akademik, $jenis_peperiksaan = null) {
        $sql = "SELECT 
                k.nama as kelas,
                p.nama as nama_pelajar,
                AVG(m.markah) as purata_markah,
                COUNT(m.id) as jumlah_ujian
                FROM pelajar p
                LEFT JOIN markah m ON p.id = m.id_pelajar
                LEFT JOIN peperiksaan pe ON m.id_perperiksaan = pe.id
                LEFT JOIN kelas k ON p.id_kelas LIKE CONCAT('%', k.nama, '%')
                WHERE pe.tahun_akademik = ? 
                AND p.status = 1";
        
        $params = [$tahun_akademik];
        
        if ($jenis_peperiksaan) {
            $sql .= " AND pe.jenis = ?";
            $params[] = $jenis_peperiksaan;
        }
        
        $sql .= " GROUP BY p.id, k.nama
                ORDER BY k.nama, purata_markah DESC";
        
        $stmt = $this->db->preparedQuery($sql, $params);
        
        $laporan = [];
        if ($stmt) {
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $laporan[] = $row;
            }
        }
        
        return $laporan;
    }
    
    // Kemaskini markah
    public function kemaskiniMarkah($id, $data) {
        $sql = "UPDATE markah 
                SET markah = ?, gred = ?, catatan = ?, tarikh_kemaskini = CURDATE() 
                WHERE id = ?";
        
        $params = [
            $data['markah'],
            $data['gred'],
            $data['catatan'] ?? '',
            $id
        ];
        
        $stmt = $this->db->preparedQuery($sql, $params);
        
        if ($stmt && $stmt->affected_rows > 0) {
            return [
                'success' => true,
                'message' => 'Markah berjaya dikemaskini!'
            ];
        }
        
        return [
            'success' => false,
            'message' => 'Gagal mengemaskini markah!'
        ];
    }
    
    // Padam markah (soft delete)
    public function padamMarkah($id) {
        $sql = "UPDATE markah SET status = 0 WHERE id = ?";
        $stmt = $this->db->preparedQuery($sql, [$id]);
        
        if ($stmt && $stmt->affected_rows > 0) {
            return [
                'success' => true,
                'message' => 'Markah berjaya dipadam!'
            ];
        }
        
        return [
            'success' => false,
            'message' => 'Gagal memadam markah!'
        ];
    }
}
?>