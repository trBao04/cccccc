<?php
include 'db.php';
session_start();

// Kiểm tra quyền Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Bạn không có quyền truy cập!");
}

// Lấy ID học sinh từ URL
$student_id = $_GET['id'] ?? null;
if (!$student_id) {
    die("Không tìm thấy học sinh!");
}

// Xóa học sinh
$query = "DELETE FROM Students WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $student_id);

if ($stmt->execute()) {
    echo "<script>alert('Xóa học sinh thành công!'); window.location='admin.php';</script>";
} else {
    echo "Lỗi: " . $conn->error;
}

$conn->close();
?>
