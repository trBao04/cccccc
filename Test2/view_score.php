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
// Lấy điểm
$query_scores = "SELECT s.subject_id, sub.name AS subject_name, s.score, s.date_added 
                 FROM scores s 
                 JOIN Subjects sub ON s.subject_id = sub.id 
                 WHERE s.student_id = ?";
$stmt_scores = $conn->prepare($query_scores);
$stmt_scores->bind_param("i", $student['id']);
$stmt_scores->execute();
$scores_result = $stmt_scores->get_result();
$scores_records = $scores_result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="view_score.css">
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

    <!-- Content -->
    <div class="content">
        <h2>Learning Outcomes</h2>
        <?php if ($scores_records): ?>
            <table>
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Point</th>
                        <th>Date Added</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($scores_records as $score): ?>
                        <tr>
                            <td><?= htmlspecialchars($score['subject_name']) ?></td>
                            <td><?= htmlspecialchars($score['score']) ?></td>
                            <td><?= htmlspecialchars($score['date_added']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Không có dữ liệu điểm số.</p>
        <?php endif; ?>
    </div>
</body>
</html>