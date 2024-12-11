<?php
include 'db.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher') {
    die("You do not have access!");
}

// Lấy danh sách học sinh và môn học
$students = $conn->query("SELECT id, full_name FROM Students");
$subjects = $conn->query("SELECT id, name FROM Subjects");

// Xử lý form thêm điểm
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'];
    $subject_id = $_POST['subject_id'];
    $score = $_POST['score'];
    $created_by = $_SESSION['user_id']; // Lấy ID giáo viên từ session

    // Thêm điểm vào cơ sở dữ liệu
    $stmt = $conn->prepare("INSERT INTO scores (student_id, subject_id, score, created_by, date_added) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("iiii", $student_id, $subject_id, $score, $created_by);
    
    if ($stmt->execute()) {
        echo "<p>Add success points!</p>";
    } else {
        echo "<p>Errol!!!: " . $conn->error . "</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Điểm</title>
    <link rel="stylesheet" href="addcorres102.css">
</head>
<body>
    <div class="sidebar">
        <h2>Teacher Dashboard</h2>
        <a href="teacher.php">Home</a>
        <a href="attendance.php">Student roll call</a>
        <a href="add_scores.php">Add points</a>
        <a href="login.php">Log Out</a>
    </div>

    <div class="content">
        <h1>Extra Points for Students</h1>
        <form method="POST">
            <label for="student_id">Student:</label>
            <select name="student_id" id="student_id" required>
                <option value="">-- Select Student --</option>
                <?php while ($student = $students->fetch_assoc()) { ?>
                    <option value="<?= $student['id'] ?>"><?= htmlspecialchars($student['full_name']) ?></option>
                <?php } ?>
            </select>

            <label for="subject_id">Subject:</label>
            <select name="subject_id" id="subject_id" required>
                <option value="">-- Select Subject --</option>
                <?php while ($subject = $subjects->fetch_assoc()) { ?>
                    <option value="<?= $subject['id'] ?>"><?= htmlspecialchars($subject['name']) ?></option>
                <?php } ?>
            </select>

            <label for="score">Point:</label>
            <input type="number" name="score" id="score" min="0" max="100" step="0.01" required>

            <button type="submit">Add Point</button>
        </form>
        <br>
    </div>
</body>
</html>
