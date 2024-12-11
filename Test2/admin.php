<?php
include 'db.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Bạn không có quyền truy cập!");
}

// Lấy danh sách học sinh
$students = $conn->query("SELECT * FROM Students");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="bit.css">
</head>
<body>
<div class="sidebar">
        <h3>Hello Admin</h3>
        <a href="admin.php">Home</a>
        <a href="class.php">Class</a>
        <a href="teacher.php">Teacher</a>
        <a href="criteria.php">Manage evaluation criteria</a>
        <a href="evaluate.php">Internal review management</a>
        <a href="calendar.php">Calendar <span class="badge">17</span></a>
        <a href="login.php">Log Out</a>
    </div>
    <div class="container">
        <h1>Student Management</h1>
        <!-- Table Content -->
        <table>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Class</th>
                <th>Schedule</th>
                <th>Action</th>
            </tr>
            <?php while ($student = $students->fetch_assoc()) { ?>
            <tr>
                <td><?= $student['id'] ?></td>
                <td><?= $student['full_name'] ?></td>
                <td><?= $student['class'] ?></td>
                <td><?= $student['schedule'] ?></td>
                <td>
                    <a href="edit_student.php?id=<?= $student['id'] ?>">Edit</a> |
                    <a href="delete_student.php?id=<?= $student['id'] ?>" onclick="return confirm('Bạn chắc chắn muốn xóa?')">Delete</a> |
                    <a href="add_schedule.php?id=<?= $student['id'] ?>">Add/Update Schedule</a>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>

