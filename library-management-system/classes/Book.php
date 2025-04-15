<?php
// /classes/Book.php

class Book {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function createBook($title, $author, $isbn, $genre, $quantity) {
        $this->db->insert('books', [
            'title' => $title,
            'author' => $author,
            'isbn' => $isbn,
            'genre' => $genre,
            'quantity' => $quantity
        ]);
    }

    public function getAllBooks() {
        return $this->db->fetchAll("SELECT * FROM books");
    }

    public function getBookById($id) {
        return $this->db->fetchOne("SELECT * FROM books WHERE id = :id", ['id' => $id]);
    }

    public function updateBook($id, $title, $author, $genre, $isbn, $quantity) {
        // Construct an associative array for the data
        $data = [
            'title' => $title,
            'author' => $author,
            'genre' => $genre,
            'isbn' => $isbn,
            'quantity' => $quantity
        ];
    
        // Call the update method with the associative array
        $this->db->update('books', $data, 'id = :id', ['id' => $id]);
    }

    public function deleteBook($id) {
        $this->db->delete('books', 'id = :id', ['id' => $id]);
    }
}
?>