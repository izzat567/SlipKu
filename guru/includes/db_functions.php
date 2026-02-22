<?php
/**
 * db_functions.php — Fungsi-fungsi database untuk modul Guru
 * Sync sepenuhnya dengan skema slipku_db
 */

// ─── GURU ─────────────────────────────────────────────────────────────────────

function getGuruById($guru_id) {
    global $conn;
    $guru_id = intval($guru_id);
    $stmt = $conn->prepare("SELECT id, nama, email, no_telefon FROM guru WHERE id = ? LIMIT 1");
    if (!$stmt) return false;
    $stmt->bind_param("i", $guru_id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $row ?: false;
}

// ─── KELAS ────────────────────────────────────────────────────────────────────

function getKelasByGuru($guru_id) {
    global $conn;
    $guru_id = intval($guru_id);
    $sql = "SELECT DISTINCT k.id, k.nama, k.tahun
            FROM kelas k
            LEFT JOIN pengajar pj ON k.id = pj.id_kelas AND pj.id_guru = ? AND pj.status = 'aktif'
            WHERE k.status = 'aktif' AND (k.id_guru = ? OR pj.id IS NOT NULL)
            ORDER BY k.tahun DESC, k.nama ASC";
    $stmt = $conn->prepare($sql);
    if (!$stmt) return [];
    $stmt->bind_param("ii", $guru_id, $guru_id);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $rows;
}

function getAllKelas() {
    global $conn;
    $result = $conn->query("SELECT id, nama, tahun FROM kelas WHERE status = 'aktif' ORDER BY tahun DESC, nama ASC");
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

function getKelasById($id) {
    global $conn;
    $id = intval($id);
    $stmt = $conn->prepare("SELECT * FROM kelas WHERE id = ? LIMIT 1");
    if (!$stmt) return false;
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $row ?: false;
}

// ─── PELAJAR ──────────────────────────────────────────────────────────────────

function getStudentsByClass($kelas_id) {
    global $conn;
    $kelas_id = intval($kelas_id);
    $stmt = $conn->prepare("SELECT * FROM pelajar WHERE id_kelas = ? AND status = 'aktif' ORDER BY nama ASC");
    if (!$stmt) return [];
    $stmt->bind_param("i", $kelas_id);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $rows;
}

function getPelajarByGuru($guru_id, $search = '', $kelas_filter = '', $status_filter = '') {
    global $conn;
    $guru_id = intval($guru_id);

    $sql_kelas = "SELECT DISTINCT k.id FROM kelas k
                  LEFT JOIN pengajar pj ON k.id = pj.id_kelas AND pj.id_guru = ? AND pj.status = 'aktif'
                  WHERE k.status = 'aktif' AND (k.id_guru = ? OR pj.id IS NOT NULL)";
    $st = $conn->prepare($sql_kelas);
    if (!$st) return [];
    $st->bind_param("ii", $guru_id, $guru_id);
    $st->execute();
    $kelas_rows = $st->get_result()->fetch_all(MYSQLI_ASSOC);
    $st->close();

    if (empty($kelas_rows)) return [];
    $kelas_ids   = array_column($kelas_rows, 'id');
    $placeholders = implode(',', array_fill(0, count($kelas_ids), '?'));

    $sql    = "SELECT DISTINCT p.*, k.nama AS kelas_nama, k.tahun
               FROM pelajar p
               JOIN kelas k ON p.id_kelas = k.id
               WHERE p.id_kelas IN ($placeholders)";
    $params = $kelas_ids;
    $types  = str_repeat('i', count($kelas_ids));

    if ($status_filter === 'aktif')         { $sql .= " AND p.status = 'aktif'"; }
    elseif ($status_filter === 'tidak aktif') { $sql .= " AND p.status = 'tidak aktif'"; }
    elseif ($status_filter === 'tamat')     { $sql .= " AND p.status = 'tamat'"; }

    if (!empty($search)) {
        $like = "%$search%";
        $sql .= " AND (p.nama LIKE ? OR p.no_kp LIKE ? OR k.nama LIKE ?)";
        $params[] = $like; $params[] = $like; $params[] = $like;
        $types .= 'sss';
    }
    if (!empty($kelas_filter)) {
        $sql .= " AND k.nama = ?";
        $params[] = $kelas_filter;
        $types .= 's';
    }
    $sql .= " ORDER BY p.nama ASC";

    $stmt = $conn->prepare($sql);
    if (!$stmt) return [];
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $rows;
}

function getPelajarById($id) {
    global $conn;
    $id = intval($id);
    $stmt = $conn->prepare("SELECT p.*, k.nama AS kelas_nama FROM pelajar p LEFT JOIN kelas k ON p.id_kelas = k.id WHERE p.id = ? LIMIT 1");
    if (!$stmt) return false;
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $row ?: false;
}

function getStatistikPelajar($guru_id) {
    global $conn;
    $guru_id = intval($guru_id);
    $sql = "SELECT COUNT(DISTINCT p.id) AS total,
                   SUM(CASE WHEN p.status = 'aktif' THEN 1 ELSE 0 END) AS aktif
            FROM pelajar p
            JOIN kelas k ON p.id_kelas = k.id
            LEFT JOIN pengajar pj ON k.id = pj.id_kelas AND pj.id_guru = ? AND pj.status = 'aktif'
            WHERE k.status = 'aktif' AND (k.id_guru = ? OR pj.id IS NOT NULL)";
    $stmt = $conn->prepare($sql);
    $total = 0; $aktif = 0;
    if ($stmt) {
        $stmt->bind_param("ii", $guru_id, $guru_id);
        $stmt->execute();
        $row   = $stmt->get_result()->fetch_assoc();
        $total = intval($row['total'] ?? 0);
        $aktif = intval($row['aktif'] ?? 0);
        $stmt->close();
    }
    return ['total_pelajar' => $total, 'pelajar_aktif' => $aktif];
}

function checkStudentExists($no_ic, $exclude_id = null) {
    global $conn;
    $no_ic = trim($no_ic);
    $sql   = "SELECT id FROM pelajar WHERE no_kp = ?";
    if ($exclude_id) $sql .= " AND id != " . intval($exclude_id);
    $stmt = $conn->prepare($sql);
    if (!$stmt) return false;
    $stmt->bind_param("s", $no_ic);
    $stmt->execute();
    $count = $stmt->get_result()->num_rows;
    $stmt->close();
    return $count > 0;
}

function tambahPelajar($data) {
    global $conn;
    $nama     = trim($data['nama']);
    $no_kp    = trim($data['no_ic']);
    $jantina  = $data['jantina'];
    $id_kelas = intval($data['id_kelas'] ?? 0);
    $status   = $data['status'] ?? 'aktif';
    $stmt = $conn->prepare("INSERT INTO pelajar (nama, no_kp, jantina, id_kelas, status) VALUES (?,?,?,?,?)");
    if (!$stmt) return false;
    $stmt->bind_param("sssis", $nama, $no_kp, $jantina, $id_kelas, $status);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}

function kemaskiniPelajar($id, $data) {
    global $conn;
    $id       = intval($id);
    $nama     = trim($data['nama']);
    $no_kp    = trim($data['no_ic']);
    $jantina  = $data['jantina'];
    $id_kelas = intval($data['id_kelas'] ?? 0);
    $status   = $data['status'] ?? 'aktif';
    $stmt = $conn->prepare("UPDATE pelajar SET nama=?, no_kp=?, jantina=?, id_kelas=?, status=? WHERE id=?");
    if (!$stmt) return false;
    $stmt->bind_param("sssisi", $nama, $no_kp, $jantina, $id_kelas, $status, $id);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}

function padamPelajar($id) {
    global $conn;
    $id   = intval($id);
    $stmt = $conn->prepare("DELETE FROM pelajar WHERE id=?");
    if (!$stmt) return false;
    $stmt->bind_param("i", $id);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}

function bulkUpdateStudents($ids, $data) {
    global $conn;
    if (empty($ids) || empty($data)) return false;
    $ids = array_map('intval', $ids);
    $ph  = implode(',', array_fill(0, count($ids), '?'));
    $sets = []; $params = []; $types = '';
    foreach ($data as $col => $val) {
        if (in_array($col, ['status','id_kelas'])) {
            $sets[] = "$col = ?"; $params[] = $val;
            $types .= is_int($val) ? 'i' : 's';
        }
    }
    if (empty($sets)) return false;
    $sql    = "UPDATE pelajar SET " . implode(', ', $sets) . " WHERE id IN ($ph)";
    $types .= str_repeat('i', count($ids));
    $params = array_merge($params, $ids);
    $stmt = $conn->prepare($sql);
    if (!$stmt) return false;
    $stmt->bind_param($types, ...$params);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}

// ─── MATAPELAJARAN ────────────────────────────────────────────────────────────

function getSubjectsByGuru($guru_id) {
    global $conn;
    $guru_id = intval($guru_id);
    $sql = "SELECT DISTINCT m.id, m.nama, m.kod, m.tahun
            FROM matapelajaran m
            JOIN pengajar pj ON m.id = pj.id_matapelajaran
            WHERE pj.id_guru = ? AND pj.status = 'aktif' AND m.status = 'aktif'
            ORDER BY m.nama ASC";
    $stmt = $conn->prepare($sql);
    if (!$stmt) return getAllSubjects();
    $stmt->bind_param("i", $guru_id);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return !empty($rows) ? $rows : getAllSubjects();
}

function getAllSubjects() {
    global $conn;
    $result = $conn->query("SELECT id, nama, kod, tahun FROM matapelajaran WHERE status = 'aktif' ORDER BY nama ASC");
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

function getSubjectById($id) {
    global $conn;
    $id   = intval($id);
    $stmt = $conn->prepare("SELECT * FROM matapelajaran WHERE id = ? LIMIT 1");
    if (!$stmt) return false;
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $row ?: false;
}

// ─── PEPERIKSAAN ──────────────────────────────────────────────────────────────

function getAllExams() {
    global $conn;
    $sql = "SELECT p.*, COALESCE(m.nama, 'Semua Subjek') AS nama_subjek
            FROM peperiksaan p LEFT JOIN matapelajaran m ON p.id_matapelajaran = m.id
            WHERE p.status = 'aktif' ORDER BY p.tarikh_mula DESC";
    $result = $conn->query($sql);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

function getExamsByGuru($guru_id) {
    global $conn;
    $guru_id = intval($guru_id);
    $sql = "SELECT DISTINCT p.*, COALESCE(m.nama, 'Semua Subjek') AS nama_subjek
            FROM peperiksaan p LEFT JOIN matapelajaran m ON p.id_matapelajaran = m.id
            WHERE p.status = 'aktif'
              AND (p.id_matapelajaran IS NULL OR p.id_matapelajaran = 0
                   OR p.id_matapelajaran IN (
                       SELECT DISTINCT id_matapelajaran FROM pengajar
                       WHERE id_guru = ? AND status = 'aktif' AND id_matapelajaran IS NOT NULL
                   ))
            ORDER BY p.tarikh_mula DESC";
    $stmt = $conn->prepare($sql);
    if (!$stmt) return getAllExams();
    $stmt->bind_param("i", $guru_id);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return !empty($rows) ? $rows : getAllExams();
}

function getExamById($id) {
    global $conn;
    $id   = intval($id);
    $stmt = $conn->prepare("SELECT p.*, COALESCE(m.nama,'-') AS nama_subjek FROM peperiksaan p LEFT JOIN matapelajaran m ON p.id_matapelajaran = m.id WHERE p.id = ? LIMIT 1");
    if (!$stmt) return false;
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $row ?: false;
}

// ─── MARKAH ───────────────────────────────────────────────────────────────────

function calculateGrade($markah) {
    $m = intval($markah);
    if ($m >= 90) return 'A+';
    if ($m >= 80) return 'A';
    if ($m >= 70) return 'B';
    if ($m >= 60) return 'C';
    if ($m >= 50) return 'D';
    if ($m >= 40) return 'E';
    return 'F';
}

function addMark($data) {
    global $conn;
    $id_pelajar      = intval($data['id_pelajar'] ?? 0);
    $id_perperiksaan = intval($data['id_perperiksaan'] ?? $data['id_peperiksaan'] ?? 0);
    $markah_val      = intval($data['markah'] ?? 0);
    $gred            = !empty($data['gred']) ? $data['gred'] : calculateGrade($markah_val);
    $catatan         = trim($data['catatan'] ?? '');
    $today           = date('Y-m-d');

    if (!$id_pelajar || !$id_perperiksaan) {
        return ['success' => false, 'message' => 'ID pelajar atau peperiksaan tidak sah'];
    }

    $chk = $conn->prepare("SELECT id FROM markah WHERE id_pelajar = ? AND id_perperiksaan = ?");
    if (!$chk) return ['success' => false, 'message' => 'DB error: ' . $conn->error];
    $chk->bind_param("ii", $id_pelajar, $id_perperiksaan);
    $chk->execute();
    $exists = $chk->get_result()->num_rows > 0;
    $chk->close();

    if ($exists) {
        $stmt = $conn->prepare("UPDATE markah SET markah=?, gred=?, catatan=?, tarikh_kemaskini=? WHERE id_pelajar=? AND id_perperiksaan=?");
        if (!$stmt) return ['success' => false, 'message' => 'DB error: ' . $conn->error];
        $stmt->bind_param("isssii", $markah_val, $gred, $catatan, $today, $id_pelajar, $id_perperiksaan);
    } else {
        $stmt = $conn->prepare("INSERT INTO markah (id_pelajar, id_perperiksaan, markah, gred, catatan, tarikh_cipta, tarikh_kemaskini, status) VALUES (?,?,?,?,?,?,?,'aktif')");
        if (!$stmt) return ['success' => false, 'message' => 'DB error: ' . $conn->error];
        $stmt->bind_param("iiissss", $id_pelajar, $id_perperiksaan, $markah_val, $gred, $catatan, $today, $today);
    }

    if ($stmt->execute()) { $stmt->close(); return ['success' => true, 'message' => 'Markah berjaya disimpan']; }
    $err = $conn->error; $stmt->close();
    return ['success' => false, 'message' => 'Ralat: ' . $err];
}

function addMultipleMarks($marks_data) {
    $ok = 0; $errors = [];
    foreach ($marks_data as $d) { $r = addMark($d); if ($r['success']) $ok++; else $errors[] = $r['message']; }
    return ['success' => $ok > 0, 'message' => "$ok markah berjaya disimpan", 'errors' => $errors];
}

function updateMark($mark_id, $data) {
    global $conn;
    $mark_id    = intval($mark_id);
    $markah_val = intval($data['markah'] ?? 0);
    $gred       = !empty($data['gred']) ? $data['gred'] : calculateGrade($markah_val);
    $catatan    = trim($data['catatan'] ?? '');
    $today      = date('Y-m-d');
    $stmt = $conn->prepare("UPDATE markah SET markah=?, gred=?, catatan=?, tarikh_kemaskini=? WHERE id=?");
    if (!$stmt) return ['success' => false, 'message' => 'DB error: ' . $conn->error];
    $stmt->bind_param("isssi", $markah_val, $gred, $catatan, $today, $mark_id);
    if ($stmt->execute()) { $stmt->close(); return ['success' => true, 'message' => 'Markah berjaya dikemaskini']; }
    $err = $conn->error; $stmt->close();
    return ['success' => false, 'message' => 'Ralat: ' . $err];
}

function getMarksByGuru($guru_id, $peperiksaan_id = 0, $kelas_id = 0) {
    global $conn;
    $guru_id        = intval($guru_id);
    $peperiksaan_id = intval($peperiksaan_id);
    $kelas_id       = intval($kelas_id);

    $base = "SELECT m.id, m.id_pelajar, m.markah, m.gred, m.catatan,
                     p.nama AS nama_pelajar, p.no_kp,
                     k.nama AS nama_kelas, k.id AS id_kelas,
                     COALESCE(pp.nama_peperiksaan,'Tiada') AS nama_peperiksaan,
                     COALESCE(pp.id,0) AS id_peperiksaan,
                     COALESCE(mp.nama,'-') AS nama_subjek
              FROM markah m
              JOIN pelajar p ON m.id_pelajar = p.id
              JOIN kelas k ON p.id_kelas = k.id
              LEFT JOIN peperiksaan pp ON m.id_perperiksaan = pp.id
              LEFT JOIN matapelajaran mp ON pp.id_matapelajaran = mp.id
              WHERE m.status = 'aktif'";

    if ($peperiksaan_id) {
        $sql  = $base . " AND m.id_perperiksaan = ? ORDER BY p.nama ASC";
        $stmt = $conn->prepare($sql);
        if (!$stmt) return [];
        $stmt->bind_param("i", $peperiksaan_id);
    } elseif ($kelas_id) {
        $sql  = $base . " AND p.id_kelas = ? ORDER BY p.nama ASC";
        $stmt = $conn->prepare($sql);
        if (!$stmt) return [];
        $stmt->bind_param("i", $kelas_id);
    } else {
        $sql  = $base . " AND (k.id_guru = ? OR k.id IN (SELECT DISTINCT id_kelas FROM pengajar WHERE id_guru = ? AND status = 'aktif')) ORDER BY p.nama ASC";
        $stmt = $conn->prepare($sql);
        if (!$stmt) return [];
        $stmt->bind_param("ii", $guru_id, $guru_id);
    }

    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $rows;
}
