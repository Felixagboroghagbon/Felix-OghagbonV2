<?php
session_start();
require_once '../includes/header.php';
require_once '../classes/User.php';
require_once '../classes/Auth.php';

$db = new Database('localhost', 'library_management', 'root', '');
$auth = new Auth($db);

if (!$auth->isAdmin()) {
    header("Location: ../index.php");
    exit();
}

// Validate and sanitize the user ID from the URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "Invalid user ID.";
    header("Location: manage_users.php");
    exit();
}

$userId = (int)$_GET['id'];
$user = new User($db);

try {
    // Delete the user
    $user->deleteUser($userId);
    $_SESSION['success'] = "User deleted successfully.";
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
}

header("Location: manage_users.php");
exit();
?>