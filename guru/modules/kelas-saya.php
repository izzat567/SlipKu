<?php  
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// ------------------------------------------------------------
// CARA 1: GUNA MULTIPLE PATH CHECKING
// ------------------------------------------------------------
$possible_paths = [
    __DIR__ . '/../config/connect.php',
    __DIR__ . '/../../config/connect.php',
    dirname(__DIR__) . '/config/connect.php',
    dirname(dirname(__DIR__)) . '/config/connect.php',
    $_SERVER['DOCUMENT_ROOT'] . '/dashboard/SlipKu/config/connect.php',
    'C:/xampp/htdocs/dashboard/SlipKu/config/connect.php'
];

$connected = false;
foreach ($possible_paths as $path) {
    if (file_exists($path)) {
        require_once $path;
        $connected = true;
        break;
    }
}

if (!$connected) {
    die("<h3>ERROR: Cannot find connect.php</h3>
         <p>Please check the file path. Run <a href='debug-path.php'>debug-path.php</a> to debug.</p>");
}

// Check login dengan lebih ketat
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: ../login-guru.php');
    exit();
}

$guru_id = $_SESSION['guru_id'];
$current_page = 'kelas-saya.php';

// Get teacher info for avatar
$initials = '';
if (isset($_SESSION['guru_nama'])) {
    $name_parts = explode(' ', $_SESSION['guru_nama']);
    foreach ($name_parts as $part) {
        if (!empty($part)) {
            $initials .= strtoupper(substr($part, 0, 1));
        }
    }
    $initials = substr($initials, 0, 2);
}

// ------------------------------------------------------------
// GET KELAS - Hanya kelas yang diajar oleh guru ini
// ------------------------------------------------------------
$classes = [];
$total_murid_keseluruhan = 0;
$total_prestasi = 0;

try {
    // GUNA $conn (dari connect.php) atau $database (bergantung pada config)
    $db = isset($conn) ? $conn : (isset($database) ? $database : null);
    
    if (!$db) {
        throw new Exception("Database connection not found");
    }
    
    $sql = "SELECT 
            k.id, 
            k.nama,
            k.tingkatan,
            k.tahun,
            COUNT(DISTINCT pk.id_pelajar) as total_murid,
            COALESCE(AVG(m.markah), 0) as average_performance
        FROM pengajar pj
        JOIN kelas k ON pj.id_kelas = k.id
        LEFT JOIN pendaftaran_kelas pk ON k.id = pk.id_kelas AND pk.status = 'aktif'
        LEFT JOIN pelajar p ON pk.id_pelajar = p.id AND p.status = 'aktif'
        LEFT JOIN peperiksaan pe ON k.id = pe.id_kelas
        LEFT JOIN markah m ON pe.id = m.id_peperiksaan AND m.status = 'aktif'
        WHERE pj.id_guru = ? 
            AND pj.status = 'aktif' 
            AND k.status = 'aktif'
        GROUP BY k.id, k.nama, k.tingkatan, k.tahun
        ORDER BY k.tingkatan, k.nama";
    
    $stmt = $db->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $guru_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $total_murid = (int)($row['total_murid'] ?? 0);
            $avg_performance = round((float)($row['average_performance'] ?? 0), 1);
            
            $classes[] = [
                'id' => $row['id'],
                'nama' => $row['nama'],
                'tingkatan' => $row['tingkatan'] ?? '',
                'tahun' => $row['tahun'] ?? date('Y'),
                'total_murid' => $total_murid,
                'average_performance' => $avg_performance
            ];
            
            $total_murid_keseluruhan += $total_murid;
            $total_prestasi += $avg_performance;
        }
        $stmt->close();
    } else {
        error_log("Error preparing query: " . $db->error);
    }
} catch (Exception $e) {
    error_log("Exception in kelas-saya.php: " . $e->getMessage());
    echo "<!-- Debug Error: " . $e->getMessage() . " -->";
}

// Calculate totals for stats
$totalClasses = count($classes);
$totalStudents = $total_murid_keseluruhan;
$avgPerformance = $totalClasses > 0 ? round($total_prestasi / $totalClasses, 1) : 0;

// Get total active students (untuk sidebar badge)
$total_students = 0;
try {
    if (isset($db)) {
        $sql_students = "SELECT COUNT(*) as total FROM pelajar WHERE status = 'aktif'";
        $stmt_students = $db->prepare($sql_students);
        $stmt_students->execute();
        $result = $stmt_students->get_result();
        $total_students = $result->fetch_assoc()['total'] ?? 0;
        $stmt_students->close();
    }
} catch (Exception $e) {
    $total_students = 0;
}

// Get unmarked exams (untuk badge di sidebar)
$unmarked_count = 0;
try {
    if (isset($db)) {
        $sql_unmarked = "SELECT COUNT(*) as total 
                         FROM markah m
                         JOIN peperiksaan p ON m.id_peperiksaan = p.id
                         JOIN pengajar pj ON p.id_matapelajaran = pj.id_matapelajaran 
                            AND p.id_kelas = pj.id_kelas
                         WHERE pj.id_guru = ? 
                            AND (m.gred IS NULL OR m.gred = '')
                            AND m.status = 'aktif'
                            AND p.status = 'aktif'
                            AND pj.status = 'aktif'";
        $stmt_unmarked = $db->prepare($sql_unmarked);
        $stmt_unmarked->bind_param("i", $guru_id);
        $stmt_unmarked->execute();
        $result = $stmt_unmarked->get_result();
        $unmarked_count = $result->fetch_assoc()['total'] ?? 0;
        $stmt_unmarked->close();
    }
} catch (Exception $e) {
    $unmarked_count = 0;
}

// Get subjects count (untuk sidebar badge)
$subjek_count = 0;
try {
    if (isset($db)) {
        $sql_subjek = "SELECT COUNT(DISTINCT id_matapelajaran) as total 
                       FROM pengajar 
                       WHERE id_guru = ? AND status = 'aktif'";
        $stmt_subjek = $db->prepare($sql_subjek);
        $stmt_subjek->bind_param("i", $guru_id);
        $stmt_subjek->execute();
        $result = $stmt_subjek->get_result();
        $subjek_count = $result->fetch_assoc()['total'] ?? 0;
        $stmt_subjek->close();
    }
} catch (Exception $e) {
    $subjek_count = 0;
}

// Get teacher info for display
$teacher_name = $_SESSION['guru_nama'] ?? 'Guru';
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelas Saya - SlipKu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- [CSS sama seperti dalam kod asal - pastikan untuk salin semua CSS dari file asal] -->
</head>
<body>
    <!-- [HTML Header, Sidebar, Main Content sama seperti dalam kod asal] -->
    
    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-title">
                <h2>Kelas saya 🏫</h2>
                <p>Urus dan pantau semua kelas yang anda kendalikan</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-secondary" onclick="window.location.reload()">
                    <i class="fas fa-sync-alt"></i>
                    Muat Semula
                </button>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="quick-stats">
            <div class="stat-card">
                <div class="stat-icon classes">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div class="stat-info">
                    <h3>JUMLAH KELAS</h3>
                    <div class="stat-value"><?php echo $totalClasses; ?></div>
                    <div class="stat-change">
                        Kelas yang anda ajar
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon students">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div class="stat-info">
                    <h3>JUMLAH PELAJAR</h3>
                    <div class="stat-value"><?php echo $totalStudents; ?></div>
                    <div class="stat-change">
                        Dalam kelas anda
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon performance">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-info">
                    <h3>PRESTASI PURATA</h3>
                    <div class="stat-value"><?php echo number_format($avgPerformance, 1); ?>%</div>
                    <div class="stat-change">
                        Keseluruhan kelas
                    </div>
                </div>
            </div>
        </div>

        <!-- Class Table -->
        <div class="class-table-container">
            <table id="classTable">
                <thead>
                    <tr>
                        <th>KELAS</th>
                        <th>TAHUN</th>
                        <th>PELAJAR</th>
                        <th>PRESTASI</th>
                        <th>TINDAKAN</th>
                    </tr>
                </thead>
                <tbody id="classTableBody">
                    <?php if (empty($classes)): ?>
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <i class="fas fa-chalkboard-teacher"></i>
                                <h3>Tiada Kelas Ditemui</h3>
                                <p>Anda belum ditugaskan untuk mengajar mana-mana kelas.</p>
                                <p style="font-size: 13px; color: var(--medium-gray); margin-top: 10px;">
                                    Sila hubungi pentadbir sistem untuk tugasan kelas.
                                </p>
                            </div>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($classes as $class): ?>
                        <?php
                        // Determine performance class and color
                        $avgPerf = $class['average_performance'];
                        $performanceClass = 'performance-average';
                        $performanceWidth = '0%';
                        $performanceColor = '';
                        
                        if ($avgPerf >= 80) {
                            $performanceClass = 'performance-excellent';
                            $performanceWidth = '90%';
                            $performanceColor = 'var(--success)';
                        } elseif ($avgPerf >= 70) {
                            $performanceClass = 'performance-good';
                            $performanceWidth = '75%';
                            $performanceColor = '#3b82f6';
                        } elseif ($avgPerf >= 60) {
                            $performanceClass = 'performance-average';
                            $performanceWidth = '60%';
                            $performanceColor = 'var(--warning)';
                        } elseif ($avgPerf >= 50) {
                            $performanceClass = 'performance-poor';
                            $performanceWidth = '40%';
                            $performanceColor = 'var(--danger)';
                        } elseif ($avgPerf > 0) {
                            $performanceWidth = '20%';
                            $performanceColor = 'var(--danger)';
                        }
                        ?>
                        <tr>
                            <td>
                                <div class="class-info-cell">
                                    <div class="class-icon">
                                        <i class="fas fa-chalkboard-teacher"></i>
                                    </div>
                                    <div class="class-details">
                                        <div class="class-name"><?php echo htmlspecialchars($class['nama']); ?></div>
                                        <div class="class-subject">Tingkatan <?php echo htmlspecialchars($class['tingkatan']); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($class['tahun']); ?></td>
                            <td style="font-weight: 600;">
                                <?php echo $class['total_murid']; ?> pelajar
                            </td>
                            <td>
                                <div class="performance-cell">
                                    <div class="performance-bar">
                                        <div class="performance-fill <?php echo $performanceClass; ?>" 
                                             style="width: <?php echo $performanceWidth; ?>; 
                                                    background: <?php echo $performanceColor; ?>;">
                                        </div>
                                    </div>
                                    <div class="performance-value">
                                        <?php echo number_format($avgPerf, 1); ?>%
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="action-cell">
                                    <button class="action-btn view" onclick="viewClass(<?php echo $class['id']; ?>, '<?php echo htmlspecialchars($class['nama']); ?>')">
                                        <i class="fas fa-eye"></i>
                                        Lihat
                                    </button>
                                    <button class="action-btn edit" onclick="editClass(<?php echo $class['id']; ?>)">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Footer Info -->
        <div style="background: var(--white); border-radius: var(--border-radius); padding: 15px; margin-top: 20px; text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
            <p style="color: var(--medium-gray); font-size: 13px;">
                <i class="fas fa-info-circle"></i> 
                Menunjukkan <?php echo $totalClasses; ?> kelas yang anda kendalikan dengan jumlah <?php echo $totalStudents; ?> pelajar.
            </p>
        </div>
    </main>

    <!-- Modal for Class Details -->
    <div class="modal" id="classModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Maklumat Kelas</h3>
                <button class="modal-close" onclick="closeModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="class-detail-header" style="text-align: center; margin-bottom: 25px;">
                    <div class="class-icon" style="width: 80px; height: 80px; margin: 0 auto 15px;">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <h3 id="classNameDetail" style="font-size: 24px; color: var(--primary); margin-bottom: 5px;">Loading...</h3>
                    <p id="classLevelDetail" style="color: var(--medium-gray); font-size: 16px;"></p>
                </div>
                
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 25px;">
                    <div style="background: var(--light-gray); padding: 15px; border-radius: 12px;">
                        <div style="font-size: 13px; color: var(--medium-gray); margin-bottom: 5px;">Guru Kelas</div>
                        <div style="font-weight: 600; color: var(--dark-gray);" id="classTeacherDetail"><?php echo htmlspecialchars($_SESSION['guru_nama'] ?? 'Guru'); ?></div>
                    </div>
                    <div style="background: var(--light-gray); padding: 15px; border-radius: 12px;">
                        <div style="font-size: 13px; color: var(--medium-gray); margin-bottom: 5px;">Tahun</div>
                        <div style="font-weight: 600; color: var(--dark-gray);" id="classYearDetail">Loading...</div>
                    </div>
                    <div style="background: var(--light-gray); padding: 15px; border-radius: 12px;">
                        <div style="font-size: 13px; color: var(--medium-gray); margin-bottom: 5px;">Prestasi Purata</div>
                        <div style="font-weight: 600; color: var(--dark-gray);" id="classPerformanceDetail">Loading...</div>
                    </div>
                    <div style="background: var(--light-gray); padding: 15px; border-radius: 12px;">
                        <div style="font-size: 13px; color: var(--medium-gray); margin-bottom: 5px;">Jumlah Pelajar</div>
                        <div style="font-weight: 600; color: var(--dark-gray);" id="classStudentsDetail">Loading...</div>
                    </div>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <h4 style="font-size: 16px; margin-bottom: 15px; color: var(--dark-gray);">Senarai Pelajar</h4>
                    <div id="studentListModal" style="max-height: 300px; overflow-y: auto;">
                        <div style="text-align: center; padding: 30px;">
                            <i class="fas fa-spinner fa-spin" style="font-size: 32px; color: var(--primary);"></i>
                            <p style="color: var(--medium-gray); margin-top: 15px;">Memuatkan senarai pelajar...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // DOM Elements
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const classModal = document.getElementById('classModal');

        // PHP data passed to JavaScript
        const classesData = <?php echo json_encode($classes); ?>;
        const teacherName = "<?php echo isset($_SESSION['guru_nama']) ? htmlspecialchars($_SESSION['guru_nama']) : 'Guru'; ?>";

        // Toggle Sidebar
        function toggleSidebar() {
            sidebar.classList.toggle('sidebar-active');
            if (window.innerWidth <= 1024) {
                mainContent.classList.toggle('full-width');
            }
        }

        // Close Sidebar on Mobile
        function closeSidebar() {
            if (window.innerWidth <= 1024) {
                sidebar.classList.remove('sidebar-active');
                mainContent.classList.remove('full-width');
            }
        }

        // View class details
        function viewClass(classId, className) {
            const classData = classesData.find(c => c.id == classId);
            
            if (classData) {
                // Update modal content with real data
                document.getElementById('modalTitle').textContent = 'Maklumat Kelas: ' + className;
                document.getElementById('classNameDetail').textContent = className;
                document.getElementById('classLevelDetail').textContent = `Tingkatan ${classData.tingkatan || ''}`;
                document.getElementById('classTeacherDetail').textContent = teacherName;
                document.getElementById('classYearDetail').textContent = classData.tahun || '2026';
                document.getElementById('classPerformanceDetail').textContent = classData.average_performance ? 
                    classData.average_performance.toFixed(1) + '%' : 'Tiada data';
                document.getElementById('classStudentsDetail').textContent = `${classData.total_murid} pelajar`;
                
                // Load student list
                loadStudentList(classId);
                
                // Show modal
                classModal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        }

        // Load student list via AJAX
        function loadStudentList(classId) {
            const studentListDiv = document.getElementById('studentListModal');
            
            // Show loading
            studentListDiv.innerHTML = `
                <div style="text-align: center; padding: 30px;">
                    <i class="fas fa-spinner fa-spin" style="font-size: 32px; color: var(--primary);"></i>
                    <p style="color: var(--medium-gray); margin-top: 15px;">Memuatkan senarai pelajar...</p>
                </div>
            `;
            
            // AJAX request to get students
            fetch(`ajax/get-students-by-class.php?class_id=${classId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.students.length > 0) {
                        let html = '<div style="display: flex; flex-direction: column; gap: 10px;">';
                        data.students.forEach(student => {
                            html += `
                                <div style="display: flex; align-items: center; gap: 15px; padding: 12px; background: var(--light-gray); border-radius: 10px;">
                                    <div style="width: 40px; height: 40px; background: var(--primary-light); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--primary); font-weight: 600;">
                                        ${student.initials || 'P'}
                                    </div>
                                    <div style="flex: 1;">
                                        <div style="font-weight: 600; color: var(--dark-gray);">${student.nama}</div>
                                        <div style="font-size: 12px; color: var(--medium-gray);">No. KP: ${student.no_kp || 'Tiada'}</div>
                                    </div>
                                    <button class="action-btn view" onclick="viewStudent(${student.id})" style="padding: 6px 12px;">
                                        <i class="fas fa-eye"></i> Lihat
                                    </button>
                                </div>
                            `;
                        });
                        html += '</div>';
                        studentListDiv.innerHTML = html;
                    } else {
                        studentListDiv.innerHTML = `
                            <div style="text-align: center; padding: 40px;">
                                <i class="fas fa-user-graduate" style="font-size: 48px; color: #ccc; margin-bottom: 15px;"></i>
                                <p style="color: var(--medium-gray);">Tiada pelajar dalam kelas ini.</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    studentListDiv.innerHTML = `
                        <div style="text-align: center; padding: 40px;">
                            <i class="fas fa-exclamation-triangle" style="font-size: 48px; color: var(--warning); margin-bottom: 15px;"></i>
                            <p style="color: var(--medium-gray);">Ralat memuatkan data pelajar.</p>
                            <p style="font-size: 12px; margin-top: 10px;">Sila cuba sebentar lagi.</p>
                        </div>
                    `;
                });
        }

        // View student details
        function viewStudent(studentId) {
        
        window.location.href = `pelajar-detail.php?id=${studentId}`;
        }

        // Edit class
        function editClass(classId) {
        
        window.location.href = `edit-kelas.php?id=${classId}`;
        }

        // Close modal
        function closeModal() {
            classModal.classList.remove('active');
            document.body.style.overflow = '';
        }

        // Reload data
        function muatSemulaData() {
            location.reload();
        }

        // Setup event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle sidebar
            if (menuToggle) {
                menuToggle.addEventListener('click', toggleSidebar);
            }
            
            // Close sidebar when clicking on sidebar items on mobile
            document.querySelectorAll('.sidebar-item').forEach(item => {
                item.addEventListener('click', closeSidebar);
            });
            
            // Close sidebar on window resize
            window.addEventListener('resize', closeSidebar);
            
            // Close modal when clicking outside
            if (classModal) {
                classModal.addEventListener('click', function(event) {
                    if (event.target === classModal) {
                        closeModal();
                    }
                });
            }
            
            // Close modal with Escape key
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    closeModal();
                }
            });
        });
    </script>
</body>
</html>
<?php
// Close database connections
if (isset($stmt)) $stmt->close();
if (isset($stmt_students)) $stmt_students->close();
if (isset($stmt_unmarked)) $stmt_unmarked->close();
if (isset($stmt_subjek)) $stmt_subjek->close();
$conn->close();
?>