<?php
// /classes/User.php

class User {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    /**
     * Create a new user.
     *
     * @param string $name The name of the user.
     * @param string $email The email of the user.
     * @param string $password The plain-text password of the user.
     * @param string $role The role of the user (admin or user).
     * @throws Exception If the email already exists or the password is invalid.
     */
    public function createUser($name, $email, $password, $role) {
        // Validate the password
        if (strlen($password) < 8) {
            throw new Exception("Password must be at least 8 characters long.");
        }

        // Check if the email already exists
        $query = "SELECT COUNT(*) AS count FROM users WHERE email = :email";
        $result = $this->db->fetchOne($query, ['email' => $email]);
        if ($result['count'] > 0) {
            throw new Exception("Email already exists.");
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert the user into the database using the insert() method
        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword,
            'role' => $role
        ];
        $this->db->insert('users', $data); // Pass the table name and data array
    }

    /**
     * Fetch a user by their ID.
     *
     * @param int $id The ID of the user.
     * @return array|null The user data or null if not found.
     */
    public function getUserById($id) {
        $query = "SELECT id, name, email, role FROM users WHERE id = :id";
        return $this->db->fetchOne($query, ['id' => $id]);
    }

    /**
     * Update a user's details.
     *
     * @param int $id The ID of the user to update.
     * @param string $name The new name of the user.
     * @param string $email The new email of the user.
     * @param string $role The new role of the user.
     * @throws Exception If the email already exists for another user.
     */
    public function updateUser($id, $name, $email, $role) {
        // Check if the email already exists for another user
        $query = "SELECT COUNT(*) AS count FROM users WHERE email = :email AND id != :id";
        $result = $this->db->fetchOne($query, ['email' => $email, 'id' => $id]);
        if ($result['count'] > 0) {
            throw new Exception("Email already exists.");
        }

        // Update the user in the database
        $data = [
            'name' => $name,
            'email' => $email,
            'role' => $role
        ];
        $where = "id = :id";
        $whereParams = ['id' => $id];
        $this->db->update('users', $data, $where, $whereParams);
    }

    /**
     * Delete a user by their ID.
     *
     * @param int $id The ID of the user to delete.
     */
    public function deleteUser($id) {
        $where = "id = :id";
        $params = ['id' => $id];
        $this->db->delete('users', $where, $params);
    }

    /**
     * Fetch all users with pagination.
     *
     * @param int $page The current page number.
     * @param int $limit The number of users per page.
     * @return array An array of users.
     */
    public function getUsers($page = 1, $limit = 10) {
        $offset = ($page - 1) * $limit;
        $query = "SELECT id, name, email, role FROM users LIMIT :limit OFFSET :offset";
        $params = [
            'limit' => $limit,
            'offset' => $offset
        ];
        return $this->db->fetchAll($query, $params);
    }

    /**
     * Count the total number of users.
     *
     * @return int The total number of users.
     */
    public function countUsers() {
        $query = "SELECT COUNT(*) AS count FROM users";
        $result = $this->db->fetchOne($query, []);
        return $result['count'];
    }
}