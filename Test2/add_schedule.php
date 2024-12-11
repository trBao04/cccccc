<?php
include 'db.php';
session_start();

// Kiểm tra quyền Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("You do not have access!");
}

// Lấy ID học sinh từ URL
$student_id = $_GET['id'] ?? null;
if (!$student_id) {
    die("No students found!");
}

// Lấy thông tin học sinh
$result = $conn->query("SELECT * FROM Students WHERE id = $student_id");
$student = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $schedule = $_POST['schedule'];

    // Cập nhật lịch học
    $query = "UPDATE Students SET schedule = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $schedule, $student_id);

    if ($stmt->execute()) {
        echo "<script>alert('The schedule has been updated.!'); window.location='admin.php';</script>";
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
    <title>Add/Update Class Schedule</title>
    <!-- <link rel="stylesheet" href="add_schedule1.css"> -->
     <link rel="stylesheet" href="b52.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin Dashboard</h2>
        <a href="admin.php">Home</a>
        <a href="admin.php">List of students</a>
        <a href="add_student.php">Add students</a>
        <a href="login.php">Log Out</a>
    </div>

    <!-- Nội dung chính -->
    <div class="content">
        <h1>Add/Update Student Schedule: <?= htmlspecialchars($student['full_name']) ?></h1>
        <form method="POST">
            <label for="schedule">Class schedule:</label>
            <textarea id="schedule" name="schedule" rows="5" required><?= htmlspecialchars($student['schedule']) ?></textarea>
            <button type="submit">Update</button>
        </form>
    </div>
</body>
</html>
<?php
$conn->close();
?>

