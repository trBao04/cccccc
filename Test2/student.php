<?php
session_start();
include 'db.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    die("Bạn không có quyền truy cập!");
}

$user_id = $_SESSION['user_id'];

// Lấy thông tin học sinh
$query_student = "SELECT * FROM Students WHERE user_id = ?";
$stmt = $conn->prepare($query_student);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    die("Không tìm thấy thông tin học sinh!");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Thông tin cá nhân</title>
    <link rel="stylesheet" href="student102.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Student Dashboard</h2>
        <a href="student.php">Home</a>
        <a href="view_attendance.php">View Attendance</a>
        <a href="view_score.php">View Point</a>
        <a href="login.php">Log Out</a>
    </div>

    <!-- Nội dung chính -->
    <div class="content">
    <table>
        <caption>Personal Information</caption>
        <thead>
            <tr>
                <th>Category</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Full Name</td>
                <td><?= htmlspecialchars($student['full_name']) ?></td>
            </tr>
            <tr>
                <td>Class</td>
                <td><?= htmlspecialchars($student['class']) ?></td>
            </tr>
            <tr>
                <td>Class schedule</td>
                <td><?= nl2br(htmlspecialchars($student['schedule'])) ?></td>
            </tr>
        </tbody>
    </table>
</div>
</body>
</html>
<?php
$conn->close();
?>
