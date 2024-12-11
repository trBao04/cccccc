<?php
include 'db.php';
session_start();

// Kiểm tra quyền Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("You do not have access!");
}

// Xử lý thêm học sinh
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $class = $_POST['class'];
    $schedule = $_POST['schedule'];

    // Thêm học sinh vào cơ sở dữ liệu
    $query = "INSERT INTO Students (full_name, class, schedule) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $full_name, $class, $schedule);

    if ($stmt->execute()) {
        echo "<script>alert('New student added successfully!'); window.location='admin.php';</script>";
    } else {
        echo "Errol: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Học Sinh</title>
     <link rel="stylesheet" href="add2.css">
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
        <h1>Add New Student</h1>
        <form method="POST">
            <label for="full_name">Full Name:</label>
            <input type="text" id="full_name" name="full_name" required>
            
            <label for="class">Class:</label>
            <select id="class" name="class" required>
                <option value="" disabled selected>-- Select Class --</option>
                <option value="SE06302">SE06302</option>
                <option value="SE09999">SE09999</option>
                <option value="SE8888">SE8888</option>
                <option value="S007777">S007777</option>
                <option value="SE06666">SE06666</option>
                <option value="SE05555">SE05555</option>
                <option value="SE04444">SE04444</option>
                <option value="SE03333">SE03333</option>
                <option value="SE02222">SE02222</option>
                <option value="SE01111">SE01111</option>
            </select>
            
            <label for="schedule">Class Schedule (Optional):</label>
            <textarea id="schedule" name="schedule" rows="5" placeholder="Enter class schedule if available..."></textarea>
            
            <button type="submit">Add Student</button>
        </form>
        <br>
    </div>
</body>
</html>
<?php
$conn->close();
?>

