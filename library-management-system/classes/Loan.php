<?php

class Loan {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    /**
     * Borrow a book for a user.
     *
     * @param int $userId The ID of the user borrowing the book.
     * @param int $bookId The ID of the book being borrowed.
     * @throws Exception If the user has already borrowed 3 or more books, or if the book is unavailable.
     */
    public function borrowBook($userId, $bookId) {
        // Check if the user has already borrowed 3 or more books
        $query = "
            SELECT COUNT(*) AS count 
            FROM book_loans 
            WHERE user_id = :user_id AND returned = FALSE
        ";
        $result = $this->db->fetchOne($query, ['user_id' => $userId]);
        $currentLoans = $result['count'];

        if ($currentLoans >= 3) {
            throw new Exception("You cannot borrow more than 3 books at a time.");
        }

        // Check if the book exists and has available copies
        $query = "SELECT id, title, quantity FROM books WHERE id = :id";
        $book = $this->db->fetchOne($query, ['id' => $bookId]);

        if (!$book) {
            throw new Exception("Book not found.");
        }

        if ($book['quantity'] <= 0) {
            throw new Exception("The book is currently unavailable.");
        }

        // Start a transaction to ensure atomicity
        try {
            $this->db->beginTransaction();

            // Decrease the book's quantity
            $updateQuery = "UPDATE books SET quantity = quantity - 1 WHERE id = :id AND quantity > 0";
            $this->db->query($updateQuery, ['id' => $bookId]);

            // Verify that the quantity was updated
            $updatedBook = $this->db->fetchOne("SELECT quantity FROM books WHERE id = :id", ['id' => $bookId]);
            if ($updatedBook['quantity'] < 0) {
                throw new Exception("Failed to update book quantity.");
            }

            // Insert the loan record
            $data = [
                'user_id' => $userId,
                'book_id' => $bookId,
                'borrow_date' => date('Y-m-d'),
                'due_date' => date('Y-m-d', strtotime('+7 days')),
                'returned' => false
            ];
            $this->db->insert('book_loans', $data);

            // Commit the transaction
            $this->db->commit();
        } catch (Exception $e) {
            // Rollback the transaction on error
            $this->db->rollBack();
            throw new Exception("Failed to borrow the book: " . $e->getMessage());
        }
    }

    /**
     * Mark a borrowed book as returned.
     *
     * @param int $loanId The ID of the loan record to mark as returned.
     * @throws Exception If the loan record does not exist or is already returned.
     */
    public function returnBook($loanId) {
        // Start a transaction to ensure atomicity
        try {
            $this->db->beginTransaction();

            // Check if the loan exists and is not already returned
            $query = "
                SELECT bl.id AS loan_id, b.id AS book_id 
                FROM book_loans bl
                JOIN books b ON bl.book_id = b.id
                WHERE bl.id = :id AND bl.returned = FALSE
            ";
            $loan = $this->db->fetchOne($query, ['id' => $loanId]);

            if (!$loan) {
                throw new Exception("Loan record not found or already returned.");
            }

            // Increase the book's quantity
            $updateQuery = "UPDATE books SET quantity = quantity + 1 WHERE id = :id";
            $this->db->query($updateQuery, ['id' => $loan['book_id']]);

            // Mark the book as returned
            $data = [
                'returned' => true,
                'return_date' => date('Y-m-d')
            ];
            $this->db->update('book_loans', $data, 'id = :id', ['id' => $loanId]);

            // Commit the transaction
            $this->db->commit();
        } catch (Exception $e) {
            // Rollback the transaction on error
            $this->db->rollBack();
            throw new Exception("Failed to return the book: " . $e->getMessage());
        }
    }

    /**
     * Get all borrowed books for a specific user.
     *
     * @param int $userId The ID of the user.
     * @return array An array of borrowed books with details.
     */
    public function getBorrowedBooksByUser($userId) {
        $query = "
            SELECT bl.id AS loan_id, b.title, b.author, bl.borrow_date, bl.due_date, bl.returned 
            FROM book_loans bl
            JOIN books b ON bl.book_id = b.id
            WHERE bl.user_id = :user_id
        ";
        return $this->db->fetchAll($query, ['user_id' => $userId]);
    }
}