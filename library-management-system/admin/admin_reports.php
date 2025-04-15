<?php
session_start();
require_once '../includes/header.php';
require_once '../classes/Database.php';
require_once '../classes/Auth.php';

$db = new Database('localhost', 'library_management', 'root', '');
$auth = new Auth($db);

if (!$auth->isAdmin()) {
    header("Location: ../index.php");
    exit();
}

// Fetch all books that are not returned
$query = "
    SELECT bl.id AS loan_id, b.title, b.author, b.genre, u.name AS user_name, u.email AS user_email, 
           bl.borrow_date, bl.due_date 
    FROM book_loans bl
    JOIN books b ON bl.book_id = b.id
    JOIN users u ON bl.user_id = u.id
    WHERE bl.returned = FALSE
";
$unreturnedBooks = $db->fetchAll($query);
?>

<div class="container-fluid mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Admin Reports Card -->
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Unreturned Books Report</h4>
                </div>
                <div class="card-body">
                    <?php if (!empty($unreturnedBooks)): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>User Name</th>
                                        <th>User Email</th>
                                        <th>Book Title</th>
                                        <th>Author</th>
                                        <th>Genre</th>
                                        <th>Borrow Date</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($unreturnedBooks as $index => $book): ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td><?= htmlspecialchars($book['user_name']) ?></td>
                                            <td><?= htmlspecialchars($book['user_email']) ?></td>
                                            <td><?= htmlspecialchars($book['title']) ?></td>
                                            <td><?= htmlspecialchars($book['author']) ?></td>
                                            <td><?= htmlspecialchars($book['genre']) ?></td>
                                            <td><?= htmlspecialchars(date('F j, Y', strtotime($book['borrow_date']))) ?></td>
                                            <td><?= htmlspecialchars(date('F j, Y', strtotime($book['due_date']))) ?></td>
                                            <td><span class="badge bg-danger">Not Returned</span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info" role="alert">
                            All books have been returned. No outstanding loans found.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>