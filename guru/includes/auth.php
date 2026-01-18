<?php
require_once 'config/connect.php';

class Auth {
    private $db;
    
    public function __construct() {
        global $database;
        $this->db = $database;
    }
    
    // Login guru
    public function loginGuru($email, $password) {
        $sql = "SELECT * FROM guru WHERE email = ? AND status = 1";
        $stmt = $this->db->preparedQuery($sql, [$email]);
        
        if ($stmt) {
            $result = $stmt->get_result();
            
            if ($result->num_rows === 1) {
                $guru = $result->fetch_assoc();
                
                // Verify password (hash)
                if (password_verify($password, $guru['password'])) {
                    // Set session
                    SessionManager::set('guru_id', $guru['id_guru']);
                    SessionManager::set('guru_nama', $guru['nama']);
                    SessionManager::set('guru_email', $guru['email']);
                    SessionManager::set('guru_telefon', $guru['no_telefon']);
                    
                    return [
                        'success' => true,
                        'message' => 'Login berjaya!',
                        'data' => $guru
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => 'Kata laluan salah!'
                    ];
                }
            }
        }
        
        return [
            'success' => false,
            'message' => 'Emel tidak ditemukan atau akaun tidak aktif!'
        ];
    }
    
    // Logout
    public function logout() {
        SessionManager::destroy();
        return [
            'success' => true,
            'message' => 'Log keluar berjaya!'
        ];
    }
    
    // Update profile guru
    public function updateProfile($id, $data) {
        $sql = "UPDATE guru SET nama = ?, email = ?, no_telefon = ? WHERE id_guru = ?";
        $params = [
            $data['nama'],
            $data['email'],
            $data['telefon'],
            $id
        ];
        
        $stmt = $this->db->preparedQuery($sql, $params);
        
        if ($stmt && $stmt->affected_rows > 0) {
            // Update session
            SessionManager::set('guru_nama', $data['nama']);
            SessionManager::set('guru_email', $data['email']);
            SessionManager::set('guru_telefon', $data['telefon']);
            
            return [
                'success' => true,
                'message' => 'Profil berjaya dikemaskini!'
            ];
        }
        
        return [
            'success' => false,
            'message' => 'Gagal mengemaskini profil!'
        ];
    }
    
    // Change password
    public function changePassword($id, $current_password, $new_password) {
        // Get current password hash
        $sql = "SELECT password FROM guru WHERE id_guru = ?";
        $stmt = $this->db->preparedQuery($sql, [$id]);
        
        if ($stmt) {
            $result = $stmt->get_result();
            $guru = $result->fetch_assoc();
            
            // Verify current password
            if (password_verify($current_password, $guru['password'])) {
                // Update to new password
                $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
                $update_sql = "UPDATE guru SET password = ? WHERE id_guru = ?";
                $update_stmt = $this->db->preparedQuery($update_sql, [$new_hash, $id]);
                
                if ($update_stmt && $update_stmt->affected_rows > 0) {
                    return [
                        'success' => true,
                        'message' => 'Kata laluan berjaya ditukar!'
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'message' => 'Kata laluan semasa salah!'
                ];
            }
        }
        
        return [
            'success' => false,
            'message' => 'Gagal menukar kata laluan!'
        ];
    }
}
?>