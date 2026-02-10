<?php
// check_guru_data.php
$conn = new mysqli("localhost", "root", "", "slipku_db");

echo "<h2>Semak Data Guru dalam Database</h2>";

// 1. Semak jadual guru
$result = $conn->query("DESCRIBE guru");
echo "<h3>Struktur jadual 'guru':</h3>";
echo "<table border='1'><tr><th>Field</th><th>Type</th></tr>";
while ($row = $result->fetch_assoc()) {
    echo "<tr><td>" . $row['Field'] . "</td><td>" . $row['Type'] . "</td></tr>";
}
echo "</table>";

// 2. Semak data guru
echo "<h3>Data guru dalam database:</h3>";
$data = $conn->query("SELECT * FROM guru ORDER BY id");
echo "<table border='1'><tr><th>ID</th><th>Nama</th><th>Email</th><th>Password</th><th>Status</th></tr>";

while ($row = $data->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row['id'] . "</td>";
    echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
    echo "<td>" . htmlspecialchars($row['password']) . "</td>";
    echo "<td>" . $row['status'] . "</td>";
    echo "</tr>";
}
echo "</table>";

// 3. Semak guru dengan email 'guru@demo.com'
echo "<h3>Semak guru@demo.com:</h3>";
$demo = $conn->query("SELECT * FROM guru WHERE email = 'guru@demo.com'");
if ($demo->num_rows > 0) {
    $row = $demo->fetch_assoc();
    echo "<p>Ditemui: " . $row['nama'] . " (Status: " . $row['status'] . ")</p>";
    echo "<p>Password dalam database: '" . $row['password'] . "'</p>";
    echo "<p>Password yang dijangka: 'demo123'</p>";
    
    if ($row['password'] === 'demo123') {
        echo "<p style='color: green;'>✅ Password TEPAT!</p>";
    } else {
        echo "<p style='color: red;'>❌ Password TIDAK TEPAT! Password dalam DB: '" . $row['password'] . "'</p>";
    }
} else {
    echo "<p style='color: red;'>❌ Email 'guru@demo.com' TIDAK DITEMUI dalam database!</p>";
}

$conn->close();
?>