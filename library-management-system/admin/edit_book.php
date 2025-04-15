<?php

require_once '../includes/header.php';
require_once '../classes/Book.php';
require_once '../classes/Auth.php';

$db = new Database('localhost', 'library_management', 'root', '');
$auth = new Auth($db);

if (!$auth->isAdmin()) {
    header("Location: ../../index.php");
    exit();
}

// Validate and sanitize the book ID from the URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "Invalid book ID.";
    header("Location: manage_books.php");
    exit();
}

$bookId = (int)$_GET['id'];
$book = new Book($db);

// Fetch book details
$bookDetails = $book->getBookById($bookId);

if (!$bookDetails) {
    $_SESSION['error'] = "Book not found.";
    header("Location: manage_books.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $genre = trim($_POST['genre']);
    $isbn = trim($_POST['isbn']);
    $quantity = (int)$_POST['quantity'];

    // Validate input
    if (empty($title) || empty($author) || empty($genre) || empty($isbn) || $quantity <= 0) {
        $_SESSION['error'] = "All fields are required and quantity must be greater than zero.";
    } else {
        try {
            // Update book details
            $book->updateBook($bookId, $title, $author, $genre, $isbn, $quantity);
            $_SESSION['success'] = "Book updated successfully.";
            header("Location: manage_books.php");
            exit();
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
    }
}
?>

<div class="container-fluid mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Edit Book Card -->
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Edit Book</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= $_SESSION['error'] ?>
                            <?php unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="row g-3">
                        <div class="col-md-6">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($bookDetails['title']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="author" class="form-label">Author</label>
                            <input type="text" class="form-control" id="author" name="author" value="<?= htmlspecialchars($bookDetails['author']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="genre" class="form-label">Genre</label>
                            <input type="text" class="form-control" id="genre" name="genre" value="<?= htmlspecialchars($bookDetails['genre']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="isbn" class="form-label">ISBN</label>
                            <input type="text" class="form-control" id="isbn" name="isbn" value="<?= htmlspecialchars($bookDetails['isbn']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" value="<?= htmlspecialchars($bookDetails['quantity']) ?>" min="1" required>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Update Book</button>
                            <a href="manage_books.php" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>