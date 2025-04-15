<?php
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

$userId = $_SESSION['user_id'];
$loan = new Loan($db);

// Handle returning a book
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['loan_id'])) {
    $loanId = (int)$_POST['loan_id'];

    try {
        $loan->returnBook($loanId);
        $_SESSION['success'] = "Book returned successfully.";
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }

    header("Location: borrowed_books.php");
    exit();
}

// Fetch currently borrowed books for the user (only books that are not returned)
$query = "
    SELECT bl.id AS loan_id, b.title, b.author, bl.borrow_date, bl.due_date 
    FROM book_loans bl
    JOIN books b ON bl.book_id = b.id
    WHERE bl.user_id = :user_id AND bl.returned = FALSE
";
$borrowedBooks = $db->fetchAll($query, ['user_id' => $userId]);
?>

<div class="container-fluid mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Borrowed Books Card -->
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 mt-3">Currently Borrowed Books</h4> <!-- Added mt-3 for spacing -->
                    <a href="search_books.php" class="btn btn-light">Available Books</a>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= $_SESSION['error'] ?>
                            <?php unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success" role="alert">
                            <?= $_SESSION['success'] ?>
                            <?php unset($_SESSION['success']); ?>
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Borrow Date</th>
                                    <th>Due Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($borrowedBooks)): ?>
                                    <?php foreach ($borrowedBooks as $book): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($book['title']) ?></td>
                                            <td><?= htmlspecialchars($book['author']) ?></td>
                                            <td><?= htmlspecialchars(date('F j, Y', strtotime($book['borrow_date']))) ?></td>
                                            <td><?= htmlspecialchars(date('F j, Y', strtotime($book['due_date']))) ?></td>
                                            <td>
                                                <form method="POST" style="display:inline;">
                                                    <input type="hidden" name="loan_id" value="<?= $book['loan_id'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-warning">
                                                        <i class="fas fa-check"></i> Return
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">You have no currently borrowed books.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>