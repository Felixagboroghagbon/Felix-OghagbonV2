<?php
// /classes/Database.php

class Database {
    private $pdo;

    /**
     * Constructor to establish a database connection.
     *
     * @param string $host The database host.
     * @param string $dbname The database name.
     * @param string $username The database username.
     * @param string $password The database password.
     */
    public function __construct($host, $dbname, $username, $password) {
        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    /**
     * Get the PDO instance.
     *
     * @return PDO The PDO instance.
     */
    public function getPdo() {
        return $this->pdo;
    }

    /**
     * Begin a database transaction.
     */
    public function beginTransaction() {
        $this->pdo->beginTransaction();
    }

    /**
     * Commit a database transaction.
     */
    public function commit() {
        $this->pdo->commit();
    }

    /**
     * Roll back a database transaction.
     */
    public function rollBack() {
        $this->pdo->rollBack();
    }

    /**
     * Execute a generic SQL query.
     *
     * @param string $sql The SQL query to execute.
     * @param array $params The parameters to bind to the query.
     * @return PDOStatement The executed statement.
     */
    public function query($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    /**
     * Fetch all rows from the database.
     *
     * @param string $sql The SQL query to execute.
     * @param array $params The parameters to bind to the query.
     * @return array An array of rows.
     */
    public function fetchAll($sql, $params = []) {
        return $this->query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Fetch a single row from the database.
     *
     * @param string $sql The SQL query to execute.
     * @param array $params The parameters to bind to the query.
     * @return array|null The fetched row or null if not found.
     */
    public function fetchOne($sql, $params = []) {
        return $this->query($sql, $params)->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Insert data into a table.
     *
     * @param string $table The table name.
     * @param array $data The data to insert as an associative array (column => value).
     * @return string The ID of the last inserted row.
     */
    public function insert($table, $data) {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $this->query($sql, $data);
        return $this->pdo->lastInsertId();
    }

    /**
     * Update data in a table.
     *
     * @param string $table The table name.
     * @param array $data The data to update as an associative array (column => value).
     * @param string $where The WHERE clause.
     * @param array $whereParams The parameters for the WHERE clause.
     */
    public function update($table, $data, $where, $whereParams) {
        // Build the SET clause dynamically
        $set = implode(", ", array_map(fn($key) => "$key = :$key", array_keys($data)));
        
        // Prepare the SQL query
        $sql = "UPDATE $table SET $set WHERE $where";
        
        // Merge the data and whereParams arrays
        $params = array_merge($data, $whereParams);
        
        // Execute the query
        $this->query($sql, $params);
    }

    /**
     * Delete data from a table.
     *
     * @param string $table The table name.
     * @param string $where The WHERE clause.
     * @param array $params The parameters to bind to the query.
     */
    public function delete($table, $where, $params) {
        $sql = "DELETE FROM $table WHERE $where";
        $this->query($sql, $params);
    }

    /**
     * Paginate query results.
     *
     * @param string $query The SQL query or table name.
     * @param int $page The current page number.
     * @param int $limit The number of rows per page.
     * @param string $conditions Optional conditions for filtering.
     * @param array $params Parameters for the query.
     * @return array The paginated results.
     */
    public function paginate($query, $page, $limit = 10, $conditions = "", $params = []) {
        $offset = ($page - 1) * $limit;
    
        // Check if the query is a table name or a full SQL query
        if (strpos(trim($query), 'SELECT') === 0) {
            // If it's a full SQL query, append LIMIT and OFFSET
            $sql = "$query LIMIT :limit OFFSET :offset";
        } else {
            // If it's a table name, build the query dynamically
            $sql = "SELECT * FROM $query";
            if (!empty($conditions)) {
                $sql .= " WHERE $conditions";
            }
            $sql .= " LIMIT :limit OFFSET :offset";
        }
    
        // Prepare and execute the query
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    
        foreach ($params as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
    
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Count the total number of rows matching a query.
     *
     * @param string $query The SQL query or table name.
     * @param string $conditions Optional conditions for filtering.
     * @param array $params Parameters for the query.
     * @return int The total number of rows.
     */
    public function countRows($query, $conditions = "", $params = []) {
        // Check if the query is a table name or a full SQL query
        if (strpos(trim($query), 'SELECT') === 0) {
            // If it's a full SQL query, wrap it in COUNT(*) and assign an alias
            $sql = "SELECT COUNT(*) AS total FROM ($query) AS subquery";
        } else {
            // If it's a table name, build the query dynamically
            $sql = "SELECT COUNT(*) AS total FROM $query";
            if (!empty($conditions)) {
                $sql .= " WHERE $conditions";
            }
        }
    
        $stmt = $this->pdo->prepare($sql);
    
        foreach ($params as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
    
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}