<?php
// /user/dashboard.php
session_start();
require_once '../includes/header.php';
require_once '../classes/Auth.php';

$db = new Database('localhost', 'library_management', 'root', '');
$auth = new Auth($db);

if (!$auth->isUser()) {
    header("Location: ../../index.php");
    exit();
}
?>

<div class="container-fluid mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Welcome Card -->
            <div class="card shadow mb-4">
                <div class="card-body text-center">
                    <h1 class="card-title text-primary">Welcome, User!</h1>
                    <p class="card-text text-muted">Explore the library and manage your borrowed books with ease.</p>
                </div>
            </div>

            <!-- Dashboard Options -->
            <div class="row">
                <!-- Search Books Card -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-search fa-3x text-info mb-3"></i>
                            <h4 class="card-title">Search Books</h4>
                            <p class="card-text text-muted">Find books by title, author, or genre.</p>
                            <a href="search_books.php" class="btn btn-info">Search Books</a>
                        </div>
                    </div>
                </div>

                <!-- Borrowed Books Card -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-book-reader fa-3x text-success mb-3"></i>
                            <h4 class="card-title">Borrowed Books</h4>
                            <p class="card-text text-muted">View and manage your borrowed books.</p>
                            <a href="borrowed_books.php" class="btn btn-success">View Borrowed Books</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>