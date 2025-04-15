<?php
// login.php
session_start();
require_once 'classes/Database.php';
require_once 'classes/Auth.php';

// Initialize Database and Auth classes
$db = new Database('localhost', 'library_management', 'root', '');
$auth = new Auth($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Authenticate the user
    if ($auth->login($email, $password, $role)) {
        if ($auth->isAdmin()) {
            header("Location: admin/dashboard.php");
        } else {
            header("Location: user/dashboard.php");
        }
        exit();
    } else {
        echo "<script>alert('Invalid email, password, or role.'); window.location.href='index.php';</script>";
    }
}
?>