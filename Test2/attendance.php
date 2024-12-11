<?php
include 'db.php';
session_start();

// Kiểm tra quyền Giáo viên
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher') {
    die("Bạn không có quyền truy cập!");
}

// Lấy danh sách lớp từ cơ sở dữ liệu
$classes_result = $conn->query("SELECT DISTINCT class FROM Students");
$selected_class = $_GET['class'] ?? null;

// Lấy danh sách học sinh trong lớp được chọn
$students = [];
if ($selected_class) {
    $students_result = $conn->query("SELECT * FROM Students WHERE class = '$selected_class'");
    $students = $students_result->fetch_all(MYSQLI_ASSOC);
}

// Xử lý điểm danh
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $attendance_date = date('Y-m-d'); // Ngày điểm danh
    foreach ($_POST['attendance'] as $student_id => $status) {
        $query = "INSERT INTO Attendance (student_id, date, status) 
                  VALUES (?, ?, ?) 
                  ON DUPLICATE KEY UPDATE status = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isss", $student_id, $attendance_date, $status, $status);
        $stmt->execute();
    }
    echo "<script>alert('Điểm danh thành công!');</script>";
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Điểm danh học sinh</title>
    <link rel="stylesheet" href="attendance012.css">
</head>
<body>
    <div class="sidebar">
        <h2>Teacher Dashboard</h2>
        <a href="teacher.php">Home</a>
        <a href="attendance.php">Student roll call</a>
        <a href="add_scores.php">Add Point</a>
        <a href="login.php">Log Out</a>
    </div>

    <div class="content">
        <h1>Student roll call</h1>

        <!-- Chọn lớp -->
        <form method="GET">
            <label for="class">Select Class:</label>
            <select name="class" id="class" required>
                <option value="">-- Select Class --</option>
                <?php while ($class = $classes_result->fetch_assoc()) { ?>
                    <option value="<?= $class['class'] ?>" <?= ($selected_class === $class['class']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($class['class']) ?>
                    </option>
                <?php } ?>
            </select>
            <button type="submit">View list</button>
        </form>

        <?php if ($selected_class && $students): ?>
            <h2>List of students in class <?= htmlspecialchars($selected_class) ?></h2>
            <form method="POST">
                <table>
                    <tr>
                        <th>STT</th>
                        <th>Full Name</th>
                        <th>Attendance status</th>
                    </tr>
                    <?php foreach ($students as $index => $student): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($student['full_name']) ?></td>
                            <td>
                                <select name="attendance[<?= $student['id'] ?>]" required>
                                    <option value="present">Present</option>
                                    <option value="absent">Absent</option>
                                </select>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <button type="submit">Save attendance</button>
            </form>
        <?php elseif ($selected_class): ?>
            <p>There are no students in this class.</p>
        <?php endif; ?>

        <br>
    </div>
</body>
</html>
<?php
$conn->close();
?>
