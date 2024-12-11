<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    // Chèn vào bảng Users trước
    $query = "INSERT INTO Users (username, password, role) VALUES ('$username', '$password', '$role')";
    if ($conn->query($query)) {
        // Lấy ID vừa chèn vào bảng Users
        $user_id = $conn->insert_id;

        if ($role == 'student') {
            // Nếu vai trò là student, chèn thêm vào bảng Students
            $full_name = $_POST['full_name'];
            $class = $_POST['class'];
            $schedule = $_POST['schedule'];

            $queryStudent = "INSERT INTO Students (user_id, full_name, class, schedule) VALUES ('$user_id', '$full_name', '$class', '$schedule')";
            if ($conn->query($queryStudent)) {
                echo "Đăng ký thành công và thêm vào bảng Students! <a href='login.php'>Đăng nhập</a>";
            } else {
                echo "Lỗi khi thêm vào bảng Students: " . $conn->error;
            }
        } else {
            echo "Đăng ký thành công! <a href='login.php'>Đăng nhập</a>";
        }
    } else {
        echo "Lỗi: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký</title>
    <link rel="stylesheet" href="register102.css">
</head>
<body>
<div class="container">
    <h1>Register</h1>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <select name="role" onchange="toggleStudentFields(this.value)">
            <option value="student">Student</option>
            <option value="teacher">Teacher</option>
            <option value="admin">Admin</option>
        </select><br>
        <div id="studentFields" style="display: none;">
            <input type="text" name="full_name" placeholder="Họ và tên"><br>
            <input type="text" name="class" placeholder="Lớp"><br>
            <textarea name="schedule" placeholder="Thời khóa biểu"></textarea><br>
        </div>
        <button type="submit">Register</button>
    </form>
    <a href="login.php">Already have an account? Sign in now</a>
</div>

<script>
    function toggleStudentFields(role) {
        document.getElementById('studentFields').style.display = (role === 'student') ? 'block' : 'none';
    }
</script>
</body>
</html>



