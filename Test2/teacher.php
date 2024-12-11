<?php
include 'db.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher') {
    die("Bạn không có quyền truy cập!");
}

// Lấy danh sách học sinh
$students = $conn->query("SELECT * FROM Students");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Teacher Dashboard</title>
    <link rel="stylesheet" href="teacher012.css">
</head>
<body>
<div class="sidebar">
        <h2>Teacher Dashboard</h2>
        <a href="teacher.php">Home</a>
        <a href="attendance.php">Attendance</a>
        <a href="add_scores.php">Add Score</a>
        <a href="login.php">Logout</a>
    </div>

    <div class="content">
        <h1>Student Management</h1>
        <table>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Class</th>
                <th>Schedule</th>
            </tr>
            <?php while ($student = $students->fetch_assoc()) { ?>
            <tr>
                <td><?= $student['id'] ?></td>
                <td><?= $student['full_name'] ?></td>
                <td><?= $student['class'] ?></td>
                <td><?= $student['schedule'] ?></td>
            </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
