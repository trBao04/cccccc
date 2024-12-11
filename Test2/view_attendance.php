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

// Lấy tình trạng điểm danh
$query_attendance = "SELECT date, status FROM Attendance WHERE student_id = ?";
$stmt_attendance = $conn->prepare($query_attendance);
$stmt_attendance->bind_param("i", $student['id']);
$stmt_attendance->execute();
$attendance_result = $stmt_attendance->get_result();
$attendance_records = $attendance_result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch Sử Điểm Danh</title>
    <link rel="stylesheet" href="view_attendance.css"> <!-- Kết nối file CSS -->
</head>
<body>
    <!-- Sidebar -->
    div class="sidebar">
        <h2>Student Dashboard</h2>
        <a href="student.php">Home</a>
        <a href="view_attendance.php">View Attendance</a>
        <a href="view_score.php">View Point</a>
        <a href="login.php">Log Out</a>
    </div>

    <!-- Content -->
    <div class="content">
        <h2>Lịch Sử Điểm Danh</h2>
        <?php if ($attendance_records): ?>
            <table>
                <thead>
                    <tr>
                        <th>Ngày</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($attendance_records as $record): ?>
                        <tr>
                            <td><?= htmlspecialchars($record['date']) ?></td>
                            <td><?= $record['status'] === 'present' ? 'Có mặt' : 'Vắng mặt' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Không có dữ liệu điểm danh.</p>
        <?php endif; ?>
    </div>
</body>
</html>
