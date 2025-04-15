<?php
// /classes/Auth.php

class Auth {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function login($email, $password, $role) {
        $user = $this->db->fetchOne("SELECT * FROM users WHERE email = :email AND role = :role", ['email' => $email, 'role' => $role]);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            return true;
        }
        return false;
    }

    public function logout() {
        session_destroy();
    }

    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public function isAdmin() {
        return $this->isLoggedIn() && $_SESSION['role'] === 'admin';
    }

    public function isUser() {
        return $this->isLoggedIn() && $_SESSION['role'] === 'user';
    }
}
?>