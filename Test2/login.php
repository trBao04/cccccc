<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM Users WHERE username='$username'");
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'admin') {
            header("Location: admin.php");
        } elseif ($user['role'] === 'teacher') {
            header("Location: teacher.php");
        } elseif ($user['role'] === 'student') {
            header("Location: student.php");
        }
    } else {
        echo "Tên đăng nhập hoặc mật khẩu không đúng!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="login102.css">
</head>
<body>
<div class="login-container">
    <h1>Login</h1>
    <form method="POST" class="form-container">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Login</button>
        <a href="register.php" class="register-link">Register</a>
    </form>
</div>
</body>
</html>
