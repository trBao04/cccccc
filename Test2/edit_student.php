<?php
include 'db.php';
session_start();

// Kiểm tra quyền Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Bạn không có quyền truy cập!");
}

// Lấy ID học sinh từ URL
$student_id = $_GET['id'] ?? null;
if (!$student_id) {
    die("Không tìm thấy học sinh!");
}

// Lấy thông tin học sinh
$result = $conn->query("SELECT * FROM Students WHERE id = $student_id");
$student = $result->fetch_assoc();
if (!$student) {
    die("Không tìm thấy học sinh!");
}

// Xử lý cập nhật thông tin
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $class = $_POST['class'];

    $query = "UPDATE Students SET full_name = ?, class = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssi", $full_name, $class, $student_id);

    if ($stmt->execute()) {
        echo "<script>alert('Cập nhật thông tin thành công!'); window.location='admin.php';</script>";
    } else {
        echo "Lỗi: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student Information</title>
    <!-- <link rel="stylesheet" href="edit_student1.css"> -->
    <link rel="stylesheet" href="edit113.css">
</head>
<body>
<div class="sidebar">
    <h2>Admin Dashboard</h2>
    <ul>
        <li><a href="admin.php">List of students</a></li>
        <li><a href="add_student.php">Add Student</a></li>
        <li><a href="logout.php">Log Out</a></li>
    </ul>
</div>
<div class="main-content">
    <h1>Edit Student Information</h1>
    <form method="POST">
        <label for="full_name">Full Name:</label>
        <input type="text" id="full_name" name="full_name" value="<?= htmlspecialchars($student['full_name']) ?>" required>

        <label for="class">Class:</label>
        <select id="class" name="class" required>
            <option value="" disabled>-- Select Class --</option>
            <option value="SE06302" <?= $student['class'] == 'SE06302' ? 'selected' : '' ?>>SE06302</option>
            <option value="SE09999" <?= $student['class'] == 'SE09999' ? 'selected' : '' ?>>SE09999</option>
            <!-- Các lớp khác -->
        </select>

        <button type="submit">Update</button>
    </form>
    <br>
</div>

</body>
</html>

