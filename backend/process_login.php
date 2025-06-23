<?php
session_start();
require_once 'connection.php';

$role = $_POST['role'];
$username = $_POST['username'];
$password = $_POST['password'];

if ($role === 'Admin') {
    $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ? AND password = ?");
} else {
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
}

$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $_SESSION['username'] = $username;
    $_SESSION['role'] = $role;

    if ($role === 'Admin') {
        header("Location: admin_dashboard.php");
    } else {
        header("Location: ../frontend/index.php");
    }
    exit();
} else {
    echo "<script>
        alert('Invalid username or password!');
        window.location.href = '../frontend/login.php';
    </script>";
    exit();
}
?>
