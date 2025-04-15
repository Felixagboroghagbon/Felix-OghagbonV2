<?php
// /user/borrow_book.php
session_start();
require_once '../includes/header.php';
require_once '../classes/Database.php';
require_once '../classes/Loan.php';
require_once '../classes/Auth.php';

$db = new Database('localhost', 'library_management', 'root', '');
$auth = new Auth($db);

if (!$auth->isUser()) {
    header("Location: ../index.php");
    exit();
}

// Validate and sanitize the book ID from the URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "Invalid book ID.";
    header("Location: search_books.php");
    exit();
}

$bookId = (int)$_GET['id'];

// Check if the book exists
$query = "SELECT * FROM books WHERE id = :id";
$book = $db->fetchOne($query, ['id' => $bookId]);

if (!$book) {
    $_SESSION['error'] = "Book not found.";
    header("Location: search_books.php");
    exit();
}

// Borrow the book
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_SESSION['user_id'];
    $loan = new Loan($db);

    try {
        // Check if the user has already borrowed 3 or more books
        $query = "
            SELECT COUNT(*) AS count 
            FROM book_loans 
            WHERE user_id = :user_id AND returned = FALSE
        ";
        $result = $db->fetchOne($query, ['user_id' => $userId]);
        $currentLoans = $result['count'];

        if ($currentLoans >= 3) {
            throw new Exception("You cannot borrow more than 3 books at a time.");
        }

        // Attempt to borrow the book
        $loan->borrowBook($userId, $bookId);
        $_SESSION['success'] = "You have successfully borrowed the book: " . htmlspecialchars($book['title']);
        header("Location: borrowed_books.php");
        exit();
    } catch (Exception $e) {
        // Store the error message in the session
        $_SESSION['error'] = $e->getMessage();

        // Redirect to borrowed_books.php regardless of success or failure
        header("Location: borrowed_books.php");
        exit();
    }
}
?>

<div class="container-fluid mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <!-- Borrow Book Card -->
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Borrow Book</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= $_SESSION['error'] ?>
                            <?php unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>

                    <p class="card-text">Are you sure you want to borrow the following book?</p>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Title:</strong> <?= htmlspecialchars($book['title']) ?></li>
                        <li class="list-group-item"><strong>Author:</strong> <?= htmlspecialchars($book['author']) ?></li>
                        <li class="list-group-item"><strong>Genre:</strong> <?= htmlspecialchars($book['genre']) ?></li>
                        <li class="list-group-item"><strong>ISBN:</strong> <?= htmlspecialchars($book['isbn']) ?></li>
                    </ul>

                    <!-- Borrow Form -->
                    <form method="POST" class="mt-4">
                        <button type="submit" class="btn btn-success">Confirm Borrow</button>
                        <a href="search_books.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>