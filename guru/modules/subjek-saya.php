<?php
session_start();
ob_start();

if (!isset($_SESSION['guru_id'])) {
    header('Location: ../login-guru.php');
    exit();
}

$guru_id = $_SESSION['guru_id'];
$current_page = 'subjek-saya.php';

require_once __DIR__ . '/../../config/connect.php';

$error_message = '';
$success_message = '';
$subjects = [];
$kelas_options = [];

// ==================== HANDLE POST/GET REQUESTS ====================

// 1. GET STUDENTS AND MARKS (JSON)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_students_marks') {
    header('Content-Type: application/json');
    $subject_id = intval($_GET['subject_id'] ?? 0);
    $guru_id = $_SESSION['guru_id'];

    if (!$subject_id) {
        echo json_encode(['error' => 'ID subjek tidak sah']);
        exit();
    }

    try {
        // Dapatkan kelas bagi subjek ini yang diajar oleh guru
        $kelas_sql = "SELECT k.id, k.nama 
                      FROM kelas k
                      INNER JOIN pengajar p ON k.id = p.id_kelas
                      WHERE p.id_matapelajaran = ? AND p.id_guru = ? AND p.status = 'aktif'";
        $stmt = $conn->prepare($kelas_sql);
        if (!$stmt) throw new Exception("SQL error: " . $conn->error);
        $stmt->bind_param("ii", $subject_id, $guru_id);
        $stmt->execute();
        $kelas_result = $stmt->get_result();
        $kelas_ids = [];
        $kelas_names = [];
        while ($row = $kelas_result->fetch_assoc()) {
            $kelas_ids[] = $row['id'];
            $kelas_names[$row['id']] = $row['nama'];
        }
        $stmt->close();

        if (empty($kelas_ids)) {
            echo json_encode(['error' => 'Tiada kelas dijumpai untuk subjek ini']);
            exit();
        }

        // Dapatkan pelajar dalam kelas-kelas tersebut
        $placeholders = implode(',', array_fill(0, count($kelas_ids), '?'));
        $pelajar_sql = "SELECT p.id, p.nama, p.no_kp, p.id_kelas 
                        FROM pelajar p
                        WHERE p.id_kelas IN ($placeholders) AND p.status = 'aktif'
                        ORDER BY p.nama";
        $stmt = $conn->prepare($pelajar_sql);
        if (!$stmt) throw new Exception("SQL error: " . $conn->error);
        $stmt->bind_param(str_repeat('i', count($kelas_ids)), ...$kelas_ids);
        $stmt->execute();
        $pelajar = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        // Dapatkan markah untuk pelajar-pelajar ini berdasarkan subjek
        $marks = [];
        if (!empty($pelajar)) {
            $pelajar_ids = array_column($pelajar, 'id');
            $placeholders2 = implode(',', array_fill(0, count($pelajar_ids), '?'));
            
            $marks_sql = "SELECT m.id_pelajar, m.markah, m.gred, p.nama_peperiksaan AS ujian 
                          FROM markah m
                          INNER JOIN peperiksaan p ON m.id_peperiksaan = p.id
                          WHERE m.id_pelajar IN ($placeholders2) AND p.id_matapelajaran = ?";
            $types = str_repeat('i', count($pelajar_ids)) . 'i';
            $params = array_merge($pelajar_ids, [$subject_id]);
            $stmt = $conn->prepare($marks_sql);
            if ($stmt) {
                $stmt->bind_param($types, ...$params);
                $stmt->execute();
                $marks_result = $stmt->get_result();
                while ($row = $marks_result->fetch_assoc()) {
                    $marks[$row['id_pelajar']][] = [
                        'ujian' => $row['ujian'],
                        'markah' => $row['markah'],
                        'gred' => $row['gred']
                    ];
                }
                $stmt->close();
            }
        }

        $output = [
            'subject_id' => $subject_id,
            'kelas' => $kelas_names,
            'students' => []
        ];
        foreach ($pelajar as $p) {
            $output['students'][] = [
                'id' => $p['id'],
                'name' => $p['nama'],
                'ic' => $p['no_kp'],
                'class' => $kelas_names[$p['id_kelas']],
                'marks' => $marks[$p['id']] ?? []
            ];
        }

        echo json_encode($output);
        exit();
    } catch (Exception $e) {
        echo json_encode(['error' => 'Ralat server: ' . $e->getMessage()]);
        exit();
    }
}

// 2. GET SUBJECT DETAILS FOR EDIT (JSON)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_subject_details') {
    header('Content-Type: application/json');
    $subject_id = intval($_GET['subject_id'] ?? 0);
    $guru_id = $_SESSION['guru_id'];

    if (!$subject_id) {
        echo json_encode(['error' => 'ID subjek tidak sah']);
        exit();
    }

    try {
        // Ambil data subjek
        $subjek_sql = "SELECT id, nod, nama, tahun FROM matapelajaran WHERE id = ? AND status = 'aktif'";
        $stmt = $conn->prepare($subjek_sql);
        if (!$stmt) throw new Exception("SQL error: " . $conn->error);
        $stmt->bind_param("i", $subject_id);
        $stmt->execute();
        $subjek = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$subjek) {
            echo json_encode(['error' => 'Subjek tidak ditemui']);
            exit();
        }

        // Ambil kelas yang diajar oleh guru untuk subjek ini (ambil yang pertama, andaikan satu kelas)
        $kelas_sql = "SELECT id_kelas FROM pengajar WHERE id_matapelajaran = ? AND id_guru = ? AND status = 'aktif' LIMIT 1";
        $stmt = $conn->prepare($kelas_sql);
        $stmt->bind_param("ii", $subject_id, $guru_id);
        $stmt->execute();
        $kelas_result = $stmt->get_result();
        $kelas_id = $kelas_result->fetch_assoc()['id_kelas'] ?? null;
        $stmt->close();

        echo json_encode([
            'id' => $subjek['id'],
            'code' => $subjek['nod'],
            'name' => $subjek['nama'],
            'year' => $subjek['tahun'],
            'kelas_id' => $kelas_id
        ]);
        exit();
    } catch (Exception $e) {
        echo json_encode(['error' => 'Ralat server: ' . $e->getMessage()]);
        exit();
    }
}

// 3. UPDATE SUBJEK
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_subject') {
    $subject_id = intval($_POST['subject_id'] ?? 0);
    $nama = trim($_POST['subject_name'] ?? '');
    $kod = trim($_POST['subject_code'] ?? '');
    $tahun = trim($_POST['subject_year'] ?? '');
    $id_kelas = intval($_POST['kelas_id'] ?? 0);

    if (empty($subject_id) || empty($nama) || empty($kod) || empty($tahun) || $id_kelas == 0) {
        $error_message = "Sila isi semua medan dan pilih kelas!";
    } else {
        // Cek duplikasi kod (nod) kecuali subjek ini sendiri
        $check = $conn->prepare("SELECT id FROM matapelajaran WHERE nod = ? AND id != ?");
        $check->bind_param("si", $kod, $subject_id);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $error_message = "Kod subjek '$kod' sudah digunakan oleh subjek lain!";
        } else {
            $conn->begin_transaction();
            try {
                // Update matapelajaran
                $update = $conn->prepare("UPDATE matapelajaran SET nod = ?, nama = ?, tahun = ? WHERE id = ?");
                $update->bind_param("sssi", $kod, $nama, $tahun, $subject_id);
                $update->execute();
                $update->close();

                // Dapatkan rekod pengajar sedia ada untuk guru dan subjek ini
                $pengajar_sql = "SELECT id, id_kelas FROM pengajar WHERE id_matapelajaran = ? AND id_guru = ? AND status = 'aktif'";
                $pstmt = $conn->prepare($pengajar_sql);
                $pstmt->bind_param("ii", $subject_id, $guru_id);
                $pstmt->execute();
                $pengajar = $pstmt->get_result()->fetch_assoc();
                $pstmt->close();

                if ($pengajar) {
                    // Jika kelas berbeza, update rekod pengajar
                    if ($pengajar['id_kelas'] != $id_kelas) {
                        $update_pengajar = $conn->prepare("UPDATE pengajar SET id_kelas = ? WHERE id = ?");
                        $update_pengajar->bind_param("ii", $id_kelas, $pengajar['id']);
                        $update_pengajar->execute();
                        $update_pengajar->close();
                    }
                } else {
                    // Jika tiada rekod pengajar (mungkin berlaku?), insert baru
                    $tahun_akademik = '2025/2026';
                    $status_pengajar = 'aktif';
                    $insert_pengajar = $conn->prepare("INSERT INTO pengajar (id_kelas, id_guru, id_matapelajaran, tahun_akademik, status) VALUES (?, ?, ?, ?, ?)");
                    $insert_pengajar->bind_param("iiiss", $id_kelas, $guru_id, $subject_id, $tahun_akademik, $status_pengajar);
                    $insert_pengajar->execute();
                    $insert_pengajar->close();
                }

                $conn->commit();
                $success_message = "Subjek '$nama' berjaya dikemaskini!";
                header("Location: subjek-saya.php?success=3&name=" . urlencode($nama));
                exit();
            } catch (Exception $e) {
                $conn->rollback();
                $error_message = "Gagal kemaskini subjek: " . $e->getMessage();
            }
        }
        $check->close();
    }
}

// 4. TAMBAH SUBJEK
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_subject') {
    $nama = trim($_POST['subject_name'] ?? '');
    $kod = trim($_POST['subject_code'] ?? '');
    $tahun = trim($_POST['subject_year'] ?? '');
    $id_kelas = intval($_POST['kelas_id'] ?? 0);

    if (empty($nama) || empty($kod) || empty($tahun) || $id_kelas == 0) {
        $error_message = "Sila isi semua medan dan pilih kelas!";
    } else {
        // Cek duplikasi kod (nod)
        $check = $conn->prepare("SELECT id FROM matapelajaran WHERE nod = ?");
        $check->bind_param("s", $kod);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $error_message = "Kod subjek '$kod' sudah wujud!";
        } else {
            $conn->begin_transaction();
            try {
                // Dapatkan senarai kolum dalam matapelajaran untuk memastikan kita hantar semua required field
                $columns_query = "SHOW COLUMNS FROM matapelajaran";
                $columns_result = $conn->query($columns_query);
                $insert_fields = ['nod', 'nama', 'tahun', 'status'];
                $insert_values = [$kod, $nama, $tahun, 'aktif'];
                
                while ($col = $columns_result->fetch_assoc()) {
                    if ($col['Field'] == 'id') continue;
                    if ($col['Null'] == 'NO' && $col['Default'] === null && !in_array($col['Field'], $insert_fields)) {
                        if (strpos($col['Type'], 'int') !== false) {
                            $insert_fields[] = $col['Field'];
                            $insert_values[] = 0;
                        } else {
                            $insert_fields[] = $col['Field'];
                            $insert_values[] = '';
                        }
                    }
                }

                $placeholders = implode(',', array_fill(0, count($insert_fields), '?'));
                $fields = implode(',', $insert_fields);
                $sql1 = "INSERT INTO matapelajaran ($fields) VALUES ($placeholders)";
                $stmt1 = $conn->prepare($sql1);
                if (!$stmt1) throw new Exception("Prepare failed: " . $conn->error);
                
                $types = str_repeat('s', count($insert_values));
                $stmt1->bind_param($types, ...$insert_values);
                $stmt1->execute();
                $subject_id = $conn->insert_id;
                $stmt1->close();

                $tahun_akademik = '2025/2026';
                $status_pengajar = 'aktif';
                $stmt2 = $conn->prepare("INSERT INTO pengajar (id_kelas, id_guru, id_matapelajaran, tahun_akademik, status) VALUES (?, ?, ?, ?, ?)");
                $stmt2->bind_param("iiiss", $id_kelas, $guru_id, $subject_id, $tahun_akademik, $status_pengajar);
                $stmt2->execute();
                $stmt2->close();

                $conn->commit();
                $success_message = "Subjek '$nama' berjaya ditambah!";
                header("Location: subjek-saya.php?success=1&name=" . urlencode($nama));
                exit();
            } catch (Exception $e) {
                $conn->rollback();
                $error_message = "Gagal tambah subjek: " . $e->getMessage();
            }
        }
        $check->close();
    }
}

// 5. SOFT DELETE SUBJEK (UPDATE STATUS)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_subject_status') {
    header('Content-Type: application/json');
    $subject_id = intval($_POST['id'] ?? 0);
    $guru_id = $_SESSION['guru_id'];

    if (!$subject_id) {
        echo json_encode(['success' => false, 'error' => 'ID subjek tidak sah']);
        exit();
    }

    try {
        $check = $conn->prepare("SELECT id FROM pengajar WHERE id_matapelajaran = ? AND id_guru = ? AND status = 'aktif'");
        $check->bind_param("ii", $subject_id, $guru_id);
        $check->execute();
        if ($check->get_result()->num_rows === 0) {
            echo json_encode(['success' => false, 'error' => 'Subjek tidak wujud atau bukan hak anda']);
            exit();
        }
        $check->close();

        $conn->begin_transaction();
        $upd1 = $conn->prepare("UPDATE matapelajaran SET status = 'tidak' WHERE id = ?");
        $upd1->bind_param("i", $subject_id);
        $upd1->execute();
        $upd1->close();

        $upd2 = $conn->prepare("UPDATE pengajar SET status = 'tidak' WHERE id_matapelajaran = ? AND id_guru = ?");
        $upd2->bind_param("ii", $subject_id, $guru_id);
        $upd2->execute();
        $upd2->close();

        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Subjek telah dinyahaktifkan']);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'error' => 'Ralat database: ' . $e->getMessage()]);
    }
    exit();
}

// ==================== MESEJ DARI URL ====================
if (isset($_GET['success']) && $_GET['success'] == '1') {
    $subject_name = $_GET['name'] ?? '';
    $success_message = "Subjek '$subject_name' berjaya ditambah!";
} elseif (isset($_GET['success']) && $_GET['success'] == '3') {
    $subject_name = $_GET['name'] ?? '';
    $success_message = "Subjek '$subject_name' berjaya dikemaskini!";
}
if (isset($_GET['error']) && $_GET['error'] == '1') {
    $error_message = $_GET['msg'] ?? 'Ralat tidak diketahui';
}

// ==================== AMBIL SENARAI SEMUA KELAS AKTIF UNTUK DROPDOWN ====================
try {
    $sql_kelas = "SELECT id, nama FROM kelas WHERE status = 'aktif' ORDER BY nama";
    $result_kelas = $conn->query($sql_kelas);
    
    if (!$result_kelas || $result_kelas->num_rows == 0) {
        $sql_kelas = "SELECT id, nama FROM kelas WHERE status = 1 ORDER BY nama";
        $result_kelas = $conn->query($sql_kelas);
    }
    if (!$result_kelas || $result_kelas->num_rows == 0) {
        $sql_kelas = "SELECT id, nama FROM kelas ORDER BY nama";
        $result_kelas = $conn->query($sql_kelas);
    }

    if ($result_kelas) {
        while ($row = $result_kelas->fetch_assoc()) {
            $kelas_options[] = $row;
        }
    } else {
        error_log("Gagal ambil kelas: " . $conn->error);
    }
} catch (Exception $e) {
    error_log("Gagal ambil kelas: " . $e->getMessage());
}

// ==================== AMBIL DATA SUBJEK YANG DIKAITKAN DENGAN GURU ====================
try {
    if (!$conn) throw new Exception("Database connection failed");

    $sql = "SELECT m.* 
            FROM matapelajaran m
            INNER JOIN pengajar p ON m.id = p.id_matapelajaran
            WHERE p.id_guru = ? AND m.status = 'aktif' AND p.status = 'aktif'
            GROUP BY m.id
            ORDER BY m.nama";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $guru_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Dapatkan senarai kelas untuk subjek ini
            $kelas_list = [];
            $sql_kelas_subjek = "SELECT k.id, k.nama
                                  FROM kelas k
                                  INNER JOIN pengajar p ON k.id = p.id_kelas
                                  WHERE p.id_matapelajaran = ? AND p.status = 'aktif'";
            $stmt_kelas = $conn->prepare($sql_kelas_subjek);
            $stmt_kelas->bind_param("i", $row['id']);
            $stmt_kelas->execute();
            $res_kelas = $stmt_kelas->get_result();
            while ($k = $res_kelas->fetch_assoc()) {
                $kelas_list[] = $k['id'];
            }
            $stmt_kelas->close();

            $subjects[] = [
                'id' => 'SUB' . str_pad($row['id'], 3, '0', STR_PAD_LEFT),
                'db_id' => $row['id'],
                'name' => $row['nama'],
                'code' => $row['nod'],
                'year' => $row['tahun'],
                'type' => 'core',
                'description' => '',
                'books' => '',
                'notes' => '',
                'classes' => $kelas_list,
                'averagePerformance' => 70 + ($row['id'] % 25),
                'attendanceRate' => 85 + ($row['id'] % 10),
                'syllabusProgress' => 30 + ($row['id'] % 70),
                'status' => $row['status']
            ];
        }
        $result->free();
    }
    $stmt->close();
} catch (Exception $e) {
    error_log("Database error: " . $e->getMessage());
}

if (!is_array($subjects)) $subjects = [];

// Inisial untuk avatar
$initials = '';
if (isset($_SESSION['guru_nama'])) {
    $parts = explode(' ', $_SESSION['guru_nama']);
    foreach ($parts as $p) if (!empty($p)) $initials .= strtoupper(substr($p, 0, 1));
    $initials = substr($initials, 0, 2);
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subjek Saya - SlipKu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #4f46e5; --primary-dark: #4338ca; --primary-light: #eef2ff; --secondary: #7c3aed; --success: #10b981; --warning: #f59e0b; --danger: #ef4444; --info: #3b82f6; --dark-gray: #1f2937; --medium-gray: #6b7280; --light-gray: #f9fafb; --white: #ffffff; --border-radius: 12px; --transition: all 0.3s ease; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); color: var(--dark-gray); line-height: 1.6; min-height: 100vh; overflow-x: hidden; }
        .header { background: var(--white); box-shadow: 0 4px 20px rgba(0,0,0,0.08); position: fixed; top: 0; left: 0; right: 0; z-index: 1000; padding: 0 30px; }
        .header-container { display: flex; align-items: center; justify-content: space-between; padding: 15px 0; }
        .logo { display: flex; align-items: center; gap: 15px; text-decoration: none; }
        .logo-icon { width: 45px; height: 45px; background: linear-gradient(135deg, var(--primary), var(--secondary)); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--white); font-size: 22px; }
        .logo-text h1 { font-size: 24px; font-weight: 800; color: var(--primary); margin-bottom: 2px; }
        .logo-text p { font-size: 12px; color: var(--medium-gray); font-weight: 500; }
        .menu-toggle { display: none; background: none; border: none; font-size: 24px; color: var(--primary); cursor: pointer; padding: 10px; border-radius: 8px; }
        .menu-toggle:hover { background: var(--primary-light); }
        .sidebar { background: var(--white); box-shadow: 0 4px 20px rgba(0,0,0,0.08); position: fixed; left: 0; top: 85px; bottom: 0; width: 260px; padding: 30px 0; overflow-y: auto; z-index: 900; transition: var(--transition); }
        .sidebar-section { margin-bottom: 30px; padding: 0 25px; }
        .sidebar-title { font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: var(--medium-gray); margin-bottom: 15px; font-weight: 600; }
        .sidebar-item { display: flex; align-items: center; gap: 15px; padding: 12px 20px; color: var(--medium-gray); text-decoration: none; border-radius: 12px; margin: 5px 0; transition: var(--transition); }
        .sidebar-item:hover { background: var(--light-gray); color: var(--primary); transform: translateX(5px); }
        .sidebar-item.active { background: linear-gradient(135deg, var(--primary), var(--secondary)); color: white; box-shadow: 0 8px 25px rgba(79,70,229,0.3); }
        .sidebar-item i { width: 20px; font-size: 16px; }
        .badge { background: var(--danger); color: white; font-size: 10px; padding: 2px 8px; border-radius: 10px; margin-left: auto; }
        .main-content { margin-left: 260px; margin-top: 85px; padding: 30px; transition: var(--transition); }
        .page-header { margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px; }
        .page-title h2 { font-size: 32px; font-weight: 800; color: var(--dark-gray); margin-bottom: 10px; background: linear-gradient(135deg, var(--primary), var(--secondary)); -webkit-background-clip: text; background-clip: text; color: transparent; }
        .page-title p { color: var(--medium-gray); font-size: 16px; }
        .btn { padding: 12px 24px; border-radius: 12px; font-weight: 600; font-size: 14px; cursor: pointer; transition: var(--transition); display: inline-flex; align-items: center; justify-content: center; gap: 10px; border: none; }
        .btn-primary { background: linear-gradient(135deg, var(--primary), var(--secondary)); color: var(--white); box-shadow: 0 8px 25px rgba(79,70,229,0.3); }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 12px 30px rgba(79,70,229,0.4); }
        .btn-secondary { background: var(--white); color: var(--dark-gray); border: 2px solid #e5e7eb; }
        .btn-secondary:hover { background: var(--light-gray); transform: translateY(-2px); }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px,1fr)); gap: 25px; margin-bottom: 30px; }
        .stat-card { background: var(--white); border-radius: var(--border-radius); padding: 25px; box-shadow: 0 8px 25px rgba(0,0,0,0.08); display: flex; align-items: center; gap: 20px; transition: var(--transition); }
        .stat-card:hover { border: 2px solid var(--primary); transform: translateY(-5px); }
        .stat-icon { width: 70px; height: 70px; border-radius: 15px; display: flex; align-items: center; justify-content: center; font-size: 28px; color: white; }
        .stat-icon.books { background: linear-gradient(135deg,#6366f1,#8b5cf6); }
        .stat-icon.students { background: linear-gradient(135deg,#10b981,#34d399); }
        .stat-icon.performance { background: linear-gradient(135deg,#f59e0b,#fbbf24); }
        .stat-icon.progress { background: linear-gradient(135deg,#ef4444,#f87171); }
        .stat-info h3 { font-size:14px; color:var(--medium-gray); margin-bottom:8px; font-weight:500; }
        .stat-value { font-size:32px; font-weight:800; color:var(--dark-gray); line-height:1; margin-bottom:5px; }
        .subjects-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px,1fr)); gap: 25px; margin-bottom: 40px; }
        .subject-card { background: var(--white); border-radius: var(--border-radius); overflow: hidden; box-shadow: 0 8px 25px rgba(0,0,0,0.08); transition: var(--transition); border: 2px solid transparent; }
        .subject-card:hover { border-color: var(--primary); transform: translateY(-5px); box-shadow: 0 12px 30px rgba(0,0,0,0.12); }
        .subject-header { background: linear-gradient(135deg, var(--primary), var(--secondary)); color: white; padding: 20px; position: relative; }
        .subject-code { position: absolute; top: 15px; right: 15px; background: rgba(255,255,255,0.2); padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; }
        .subject-title { font-size: 20px; font-weight: 700; margin-bottom: 5px; }
        .subject-body { padding: 20px; }
        .subject-info { display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 13px; color: var(--medium-gray); }
        .subject-classes { margin: 15px 0; }
        .subject-classes span { font-weight: 600; color: var(--dark-gray); display: block; margin-bottom: 8px; font-size: 14px; }
        .class-tags { display: flex; flex-wrap: wrap; gap: 8px; }
        .class-tag { background: var(--primary-light); padding: 6px 12px; border-radius: 20px; font-size: 12px; color: var(--primary-dark); font-weight: 500; }
        .subject-metrics { display: grid; grid-template-columns: repeat(2,1fr); gap: 15px; margin: 20px 0; padding: 15px; background: var(--light-gray); border-radius: 10px; }
        .metric-item { text-align: center; }
        .metric-value { font-size: 20px; font-weight: 700; color: var(--primary); line-height:1; }
        .metric-label { font-size: 11px; color: var(--medium-gray); margin-top:5px; font-weight:500; text-transform:uppercase; }
        .subject-footer { display: flex; gap: 10px; padding-top: 15px; border-top: 1px solid #e5e7eb; }
        .btn-icon { padding: 10px 15px; border-radius: 8px; border: none; background: var(--light-gray); color: var(--dark-gray); cursor: pointer; transition: var(--transition); display: flex; align-items: center; justify-content: center; gap: 8px; font-size: 13px; flex: 1; font-weight: 500; }
        .btn-icon:hover { background: var(--primary); color: white; transform: translateY(-2px); }
        .alert-message { padding: 15px 20px; border-radius: var(--border-radius); margin-bottom: 25px; font-weight: 600; display: flex; align-items: center; gap: 12px; animation: slideIn 0.3s ease; }
        @keyframes slideIn { from { opacity:0; transform:translateY(-10px); } to { opacity:1; transform:translateY(0); } }
        .alert-message.success { background: rgba(16,185,129,0.1); border:2px solid var(--success); color: var(--success); }
        .alert-message.error { background: rgba(239,68,68,0.1); border:2px solid var(--danger); color: var(--danger); }
        .no-subjects { grid-column: 1/-1; text-align: center; padding: 50px; background: var(--white); border-radius: var(--border-radius); box-shadow: 0 8px 25px rgba(0,0,0,0.08); }
        .no-subjects-icon { font-size: 72px; color: var(--primary-light); margin-bottom: 20px; opacity:0.5; }
        .modal { display: none; position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5); backdrop-filter: blur(5px); z-index:1100; align-items:center; justify-content:center; animation: fadeIn 0.3s ease; }
        @keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
        .modal-content { background: var(--white); border-radius: var(--border-radius); width: 90%; max-width: 600px; max-height: 90vh; overflow-y: auto; animation: slideUp 0.4s ease; box-shadow: 0 20px 60px rgba(0,0,0,0.2); }
        @keyframes slideUp { from { transform:translateY(50px); opacity:0; } to { transform:translateY(0); opacity:1; } }
        .modal-header { padding: 25px 30px; border-bottom:1px solid #e5e7eb; display:flex; justify-content:space-between; align-items:center; background: linear-gradient(135deg, var(--primary), var(--secondary)); color:white; border-radius: var(--border-radius) var(--border-radius) 0 0; }
        .modal-header h3 { font-size:22px; font-weight:700; }
        .modal-close { background: rgba(255,255,255,0.2); border:none; color:white; font-size:20px; cursor:pointer; padding:8px; border-radius:8px; width:40px; height:40px; display:flex; align-items:center; justify-content:center; transition: var(--transition); }
        .modal-close:hover { background: rgba(255,255,255,0.3); transform: rotate(90deg); }
        .modal-body { padding:30px; }
        .form-group { margin-bottom:20px; }
        .form-row { display:flex; gap:20px; margin-bottom:20px; }
        .form-row .form-group { flex:1; margin-bottom:0; }
        .form-label { display:block; margin-bottom:8px; font-weight:600; color: var(--dark-gray); font-size:14px; }
        .form-label.required:after { content: " *"; color: var(--danger); }
        .form-input, .form-select, .form-textarea { width:100%; padding:14px 16px; border:2px solid #e5e7eb; border-radius:10px; font-size:15px; transition: var(--transition); background: var(--white); color: var(--dark-gray); }
        .form-input:focus, .form-select:focus, .form-textarea:focus { outline:none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(79,70,229,0.1); }
        .form-actions { display:flex; gap:15px; justify-content:flex-end; margin-top:30px; padding-top:20px; border-top:1px solid #e5e7eb; }

        .modal.large .modal-content { max-width: 800px; }
        .students-table { width:100%; border-collapse: collapse; margin-top:15px; }
        .students-table th, .students-table td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #e5e7eb; }
        .students-table th { background: var(--primary-light); font-weight: 600; color: var(--primary-dark); }
        .students-table tr:hover { background: #f9fafb; }
        .marks-cell { max-width: 200px; }
        .mark-item { background: #eef2ff; display: inline-block; padding: 3px 8px; border-radius: 15px; margin: 2px; font-size:12px; }

        @media (max-width:1024px) { .sidebar { transform:translateX(-100%); } .sidebar.active { transform:translateX(0); } .main-content { margin-left:0; } .menu-toggle { display:block; } }
        @media (max-width:768px) { .stats-grid { grid-template-columns:repeat(2,1fr); } .subjects-grid { grid-template-columns:1fr; } .form-row { flex-direction:column; } }
        @media (max-width:576px) { .stats-grid { grid-template-columns:1fr; } }
    </style>
</head>
<body>
<header class="header">
    <div class="header-container">
        <button class="menu-toggle" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
        <a href="dashboard-guru.php" class="logo">
            <div class="logo-icon"><i class="fas fa-graduation-cap"></i></div>
            <div class="logo-text"><h1>SlipKu</h1><p>Subjek Saya</p></div>
        </a>
        <div class="user-profile" style="display:flex; align-items:center; gap:10px;">
            <div class="user-avatar" style="width:45px; height:45px; background:var(--primary-light); border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:bold; color:var(--primary);"><?php echo $initials; ?></div>
            <div class="user-info">
                <h4 style="font-size:16px;"><?php echo isset($_SESSION['guru_nama']) ? htmlspecialchars($_SESSION['guru_nama']) : 'Guru'; ?></h4>
                <p style="font-size:12px; color:var(--medium-gray);"><?php echo isset($_SESSION['guru_role']) ? htmlspecialchars($_SESSION['guru_role']) : 'Guru'; ?></p>
            </div>
        </div>
    </div>
</header>

    <!-- Sidebar - Include dari includes/sidebar.php -->
    <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

<main class="main-content" id="mainContent">
    <div class="page-header">
        <div class="page-title">
            <h2><i class="fas fa-book"></i> Subjek Saya</h2>
            <p>Urus dan pantau semua subjek yang anda ajar</p>
        </div>
        <div class="header-actions" style="display:flex; gap:10px;">
            <button class="btn btn-primary" onclick="openAddSubjectModal()"><i class="fas fa-plus"></i> Tambah Subjek</button>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="stats-grid">
        <?php
        $totalStudents = 0;
        foreach ($subjects as $s) {
            foreach ($s['classes'] as $cid) {
                $q = $conn->prepare("SELECT COUNT(*) as tot FROM pelajar WHERE id_kelas = ? AND status='aktif'");
                if ($q) {
                    $q->bind_param("i", $cid);
                    $q->execute();
                    $totalStudents += $q->get_result()->fetch_assoc()['tot'] ?? 0;
                    $q->close();
                }
            }
        }
        $avgPerf = count($subjects) ? array_sum(array_column($subjects, 'averagePerformance')) / count($subjects) : 0;
        $avgProg = count($subjects) ? array_sum(array_column($subjects, 'syllabusProgress')) / count($subjects) : 0;
        ?>
        <div class="stat-card"><div class="stat-icon books"><i class="fas fa-book-open"></i></div><div class="stat-info"><h3>Jumlah Subjek</h3><div class="stat-value"><?php echo count($subjects); ?></div></div></div>
        <div class="stat-card"><div class="stat-icon students"><i class="fas fa-users"></i></div><div class="stat-info"><h3>Jumlah Pelajar</h3><div class="stat-value"><?php echo $totalStudents; ?></div></div></div>
        <div class="stat-card"><div class="stat-icon performance"><i class="fas fa-chart-line"></i></div><div class="stat-info"><h3>Purata Prestasi</h3><div class="stat-value"><?php echo number_format($avgPerf,1) . '%'; ?></div></div></div>
        <div class="stat-card"><div class="stat-icon progress"><i class="fas fa-tasks"></i></div><div class="stat-info"><h3>Kemajuan Sukatan</h3><div class="stat-value"><?php echo number_format($avgProg,1) . '%'; ?></div></div></div>
    </div>

    <!-- Subjects Grid -->
    <?php if (empty($subjects)): ?>
        <div class="no-subjects">
            <div class="no-subjects-icon"><i class="fas fa-book"></i></div>
            <h3>Tiada Subjek Dijumpai</h3>
            <p>Anda belum menambah sebarang subjek. Mulakan dengan menambah subjek pertama anda.</p>
            <button class="btn btn-primary" onclick="openAddSubjectModal()"><i class="fas fa-plus"></i> Tambah Subjek Pertama</button>
        </div>
    <?php else: ?>
        <div class="subjects-grid" id="subjectsGrid">
            <?php foreach ($subjects as $subject): 
                $bil_pelajar = 0;
                foreach ($subject['classes'] as $cid) {
                    $q = $conn->prepare("SELECT COUNT(*) as tot FROM pelajar WHERE id_kelas = ? AND status='aktif'");
                    if ($q) {
                        $q->bind_param("i", $cid);
                        $q->execute();
                        $bil_pelajar += $q->get_result()->fetch_assoc()['tot'] ?? 0;
                        $q->close();
                    }
                }
            ?>
            <div class="subject-card" data-id="<?php echo $subject['db_id']; ?>">
                <div class="subject-header">
                    <div class="subject-code"><?php echo htmlspecialchars($subject['code']); ?></div>
                    <h3 class="subject-title"><?php echo htmlspecialchars($subject['name']); ?></h3>
                </div>
                <div class="subject-body">
                    <div class="subject-info">
                        <span><i class="fas fa-calendar"></i> Tahun <?php echo htmlspecialchars($subject['year']); ?></span>
                        <span><i class="fas fa-tag"></i> Teras</span>
                    </div>
                    <div class="subject-classes">
                        <span>Kelas:</span>
                        <div class="class-tags">
                            <?php if (!empty($subject['classes'])): ?>
                                <?php foreach ($subject['classes'] as $cid): 
                                    $qn = $conn->prepare("SELECT nama FROM kelas WHERE id = ?");
                                    if ($qn) {
                                        $qn->bind_param("i", $cid);
                                        $qn->execute();
                                        $nama_kelas = $qn->get_result()->fetch_assoc()['nama'] ?? 'Kelas '.$cid;
                                        $qn->close();
                                    } else {
                                        $nama_kelas = 'Kelas '.$cid;
                                    }
                                ?>
                                    <span class="class-tag"><?php echo htmlspecialchars($nama_kelas); ?></span>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span class="class-tag">Belum Ditetapkan</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="subject-metrics">
                        <div class="metric-item"><div class="metric-value"><?php echo $bil_pelajar; ?></div><div class="metric-label">Pelajar</div></div>
                        <div class="metric-item"><div class="metric-value"><?php echo $subject['averagePerformance']; ?>%</div><div class="metric-label">Prestasi</div></div>
                    </div>
                    <div class="subject-footer">
                        <button class="btn-icon" onclick="viewSubject(<?php echo $subject['db_id']; ?>)"><i class="fas fa-eye"></i> Lihat</button>
                        <button class="btn-icon" onclick="editSubject(<?php echo $subject['db_id']; ?>)"><i class="fas fa-edit"></i> Edit</button>
                        <button class="btn-icon" onclick="deleteSubject(<?php echo $subject['db_id']; ?>, '<?php echo htmlspecialchars($subject['name']); ?>')"><i class="fas fa-trash"></i> Nyahaktif</button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Alert Messages -->
    <?php if ($success_message): ?>
        <div class="alert-message success" id="successMessage"><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success_message); ?></div>
    <?php endif; ?>
    <?php if ($error_message): ?>
        <div class="alert-message error" id="errorMessage"><i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error_message); ?></div>
    <?php endif; ?>
</main>

<!-- ========== MODALS ========== -->

<!-- Modal Tambah Subjek -->
<div class="modal" id="addSubjectModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Tambah Subjek Baru</h3>
            <button class="modal-close" onclick="closeAddSubjectModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <form id="addSubjectForm" method="POST" action="">
                <input type="hidden" name="action" value="add_subject">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Nama Subjek</label>
                        <input type="text" class="form-input" name="subject_name" placeholder="Contoh: Matematik" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label required">Kod Subjek</label>
                        <input type="text" class="form-input" name="subject_code" placeholder="Contoh: MAT01" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Tahun</label>
                        <select class="form-select" name="subject_year" required>
                            <option value="">Pilih Tahun</option>
                            <option value="2026">2026</option>
                            <option value="2025">2025</option>
                            <option value="2024">2024</option>
                            <option value="2023">2023</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label required">Kelas</label>
                        <select class="form-select" name="kelas_id" required>
                            <option value="">Pilih Kelas</option>
                            <?php foreach ($kelas_options as $kelas): ?>
                                <option value="<?php echo $kelas['id']; ?>"><?php echo htmlspecialchars($kelas['nama']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeAddSubjectModal()">Batal</button>
                    <button type="submit" class="btn btn-primary" id="addSubmitBtn"><i class="fas fa-save"></i> Simpan Subjek</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Subjek -->
<div class="modal" id="editSubjectModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit Subjek</h3>
            <button class="modal-close" onclick="closeEditSubjectModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <form id="editSubjectForm" method="POST" action="">
                <input type="hidden" name="action" value="update_subject">
                <input type="hidden" name="subject_id" id="edit_subject_id">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Nama Subjek</label>
                        <input type="text" class="form-input" id="edit_subject_name" name="subject_name" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label required">Kod Subjek</label>
                        <input type="text" class="form-input" id="edit_subject_code" name="subject_code" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Tahun</label>
                        <select class="form-select" id="edit_subject_year" name="subject_year" required>
                            <option value="">Pilih Tahun</option>
                            <option value="2026">2026</option>
                            <option value="2025">2025</option>
                            <option value="2024">2024</option>
                            <option value="2023">2023</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label required">Kelas</label>
                        <select class="form-select" id="edit_kelas_id" name="kelas_id" required>
                            <option value="">Pilih Kelas</option>
                            <?php foreach ($kelas_options as $kelas): ?>
                                <option value="<?php echo $kelas['id']; ?>"><?php echo htmlspecialchars($kelas['nama']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeEditSubjectModal()">Batal</button>
                    <button type="submit" class="btn btn-primary" id="editSubmitBtn"><i class="fas fa-save"></i> Kemaskini</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Lihat Pelajar & Markah (besar) -->
<div class="modal large" id="viewStudentsModal" style="display: none;">
    <div class="modal-content" style="max-width: 800px;">
        <div class="modal-header">
            <h3>Senarai Pelajar & Markah</h3>
            <button class="modal-close" onclick="closeViewModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body" id="studentsMarksContent">
            <div style="text-align: center; padding: 30px;">
                <i class="fas fa-spinner fa-spin fa-3x"></i>
                <p>Memuatkan data...</p>
            </div>
        </div>
    </div>
</div>

<script>
// Toggle sidebar
function toggleSidebar() { document.getElementById('sidebar').classList.toggle('active'); }

// Modal Tambah Subjek
function openAddSubjectModal() {
    <?php if (empty($kelas_options)): ?>
        alert('Tiada kelas dalam sistem. Sila hubungi admin untuk menambah kelas.');
        return;
    <?php endif; ?>
    document.getElementById('addSubjectModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function closeAddSubjectModal() {
    document.getElementById('addSubjectModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Modal Edit Subjek
function editSubject(id) {
    <?php if (empty($kelas_options)): ?>
        alert('Tiada kelas dalam sistem. Sila hubungi admin untuk menambah kelas.');
        return;
    <?php endif; ?>
    // Fetch data subjek
    fetch('?action=get_subject_details&subject_id=' + id)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert('Ralat: ' + data.error);
                return;
            }
            document.getElementById('edit_subject_id').value = data.id;
            document.getElementById('edit_subject_name').value = data.name;
            document.getElementById('edit_subject_code').value = data.code;
            document.getElementById('edit_subject_year').value = data.year;
            document.getElementById('edit_kelas_id').value = data.kelas_id;
            document.getElementById('editSubjectModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        })
        .catch(err => {
            alert('Ralat mengambil data: ' + err);
        });
}
function closeEditSubjectModal() {
    document.getElementById('editSubjectModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Modal Lihat Pelajar & Markah
function viewSubject(subjectId) {
    document.getElementById('viewStudentsModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
    document.getElementById('studentsMarksContent').innerHTML = '<div style="text-align: center; padding: 30px;"><i class="fas fa-spinner fa-spin fa-3x"></i><p>Memuatkan data...</p></div>';

    fetch('?action=get_students_marks&subject_id=' + subjectId)
        .then(response => response.text())
        .then(text => {
            try {
                const data = JSON.parse(text);
                if (data.error) {
                    document.getElementById('studentsMarksContent').innerHTML = '<p class="error" style="color:var(--danger); padding:20px;">' + data.error + '</p>';
                    return;
                }
                let html = '<h4>Kelas: ' + Object.values(data.kelas).join(', ') + '</h4>';
                html += '<table class="students-table">';
                html += '<thead><tr><th>Nama</th><th>No. KP</th><th>Kelas</th><th>Markah</th></tr></thead><tbody>';
                data.students.forEach(student => {
                    let marksHtml = '';
                    if (student.marks.length > 0) {
                        student.marks.forEach(m => {
                            marksHtml += `<span class="mark-item">${escapeHtml(m.ujian)}: ${m.markah} (${m.gred})</span> `;
                        });
                    } else {
                        marksHtml = 'Tiada markah';
                    }
                    html += `<tr>
                        <td>${escapeHtml(student.name)}</td>
                        <td>${escapeHtml(student.ic)}</td>
                        <td>${escapeHtml(student.class)}</td>
                        <td class="marks-cell">${marksHtml}</td>
                    </tr>`;
                });
                html += '</tbody></table>';
                document.getElementById('studentsMarksContent').innerHTML = html;
            } catch (e) {
                console.error('JSON parse error:', e, 'Response text:', text);
                document.getElementById('studentsMarksContent').innerHTML = '<p class="error" style="color:var(--danger); padding:20px;">Ralat memproses data: ' + e.message + '<br><pre>' + text.substring(0,200) + '...</pre></p>';
            }
        })
        .catch(error => {
            document.getElementById('studentsMarksContent').innerHTML = '<p class="error" style="color:var(--danger); padding:20px;">Ralat: ' + error + '</p>';
        });
}

function closeViewModal() {
    document.getElementById('viewStudentsModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Soft delete (update status)
function deleteSubject(id, name) {
    if (!confirm('⚠️ NYAHAKTIFKAN SUBJEK\n\nSubjek: "' + name + '"\nID: ' + id + '\n\nTindakan ini akan menukar status subjek kepada "tidak aktif". Anda boleh mengaktifkannya semula kemudian. Teruskan?')) return;
    const btn = event.target.closest('.btn-icon');
    const original = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
    btn.disabled = true;

    fetch('', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'action=update_subject_status&id=' + id
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            const card = btn.closest('.subject-card');
            if (card) {
                card.style.opacity = '0.5';
                card.style.pointerEvents = 'none';
                setTimeout(() => card.remove(), 1000);
            } else {
                location.reload();
            }
        } else {
            showAlert('error', 'Gagal: ' + (data.error || 'Ralat'));
            btn.innerHTML = original;
            btn.disabled = false;
        }
    })
    .catch(err => {
        showAlert('error', 'Ralat rangkaian: ' + err);
        btn.innerHTML = original;
        btn.disabled = false;
    });
}

function showAlert(type, msg) {
    let div = document.createElement('div');
    div.className = 'alert-message ' + type;
    div.innerHTML = '<i class="fas fa-' + (type==='success'?'check-circle':'exclamation-circle') + '"></i> ' + msg;
    document.querySelector('.main-content').prepend(div);
    setTimeout(() => div.remove(), 5000);
}

// Auto-hide messages
setTimeout(() => { document.querySelectorAll('.alert-message').forEach(m => m.remove()); }, 5000);

// Form validation untuk tambah subjek
document.getElementById('addSubjectForm')?.addEventListener('submit', function(e) {
    let valid = true;
    this.querySelectorAll('[required]').forEach(f => { if (!f.value.trim()) { f.style.borderColor = 'var(--danger)'; valid = false; } });
    if (!valid) { e.preventDefault(); alert('Sila isi semua ruangan yang diperlukan!'); }
    else { document.getElementById('addSubmitBtn').innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...'; }
});

// Form validation untuk edit subjek
document.getElementById('editSubjectForm')?.addEventListener('submit', function(e) {
    let valid = true;
    this.querySelectorAll('[required]').forEach(f => { if (!f.value.trim()) { f.style.borderColor = 'var(--danger)'; valid = false; } });
    if (!valid) { e.preventDefault(); alert('Sila isi semua ruangan yang diperlukan!'); }
    else { document.getElementById('editSubmitBtn').innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengemaskini...'; }
});

// Tutup modal jika klik luar
window.onclick = function(e) {
    if (e.target.classList.contains('modal')) {
        e.target.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

// Escape key
document.addEventListener('keydown', function(e) { if (e.key === 'Escape') { document.querySelectorAll('.modal').forEach(m => m.style.display='none'); document.body.style.overflow='auto'; } });

// Helper escape HTML
function escapeHtml(unsafe) {
    return unsafe.replace(/[&<>"']/g, function(m) {
        if(m === '&') return '&amp;';
        if(m === '<') return '&lt;';
        if(m === '>') return '&gt;';
        if(m === '"') return '&quot;';
        if(m === "'") return '&#039;';
        return m;
    });
}
</script>
</body>
</html>