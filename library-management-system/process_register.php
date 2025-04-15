<?php
// process_register.php
session_start();
require_once 'classes/Database.php';
require_once 'classes/User.php';

// Initialize Database and User classes
$db = new Database('localhost', 'library_management', 'root', '');
$user = new User($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Validate input (basic validation)
    if (empty($name) || empty($email) || empty($password)) {
        echo "<script>alert('All fields are required.'); window.location.href='register.php';</script>";
        exit();
    }

    // Check if the email already exists
    $existingUser = $user->getUserByEmailAndRole($email, $role);
    if ($existingUser) {
        echo "<script>alert('Email already registered.'); window.location.href='register.php';</script>";
        exit();
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert the new user into the database
    $user->createUser($name, $email, $hashedPassword, $role); // Pass the hashed password here

    // Redirect to the login page
    echo "<script>alert('Registration successful! Please log in.'); window.location.href='index.php';</script>";
    exit();
}
?>