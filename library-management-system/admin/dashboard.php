<?php
// /admin/dashboard.php
session_start();
require_once '../includes/header.php';
require_once '../classes/Auth.php';

$db = new Database('localhost', 'library_management', 'root', '');
$auth = new Auth($db);

if (!$auth->isAdmin()) {
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
                    <h1 class="card-title text-primary">Welcome, Admin!</h1>
                    <p class="card-text text-muted">Manage your library with ease using the options below.</p>
                </div>
            </div>

            <!-- Dashboard Options -->
            <div class="row">
                <!-- Manage Books Card -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-book fa-3x text-success mb-3"></i>
                            <h4 class="card-title">Manage Books</h4>
                            <p class="card-text text-muted">Add, edit, or delete books in the library.</p>
                            <a href="manage_books.php" class="btn btn-success">Go to Books</a>
                        </div>
                    </div>
                </div>

                <!-- Manage Users Card -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-users fa-3x text-primary mb-3"></i>
                            <h4 class="card-title">Manage Users</h4>
                            <p class="card-text text-muted">Add, edit, or delete users in the system.</p>
                            <a href="manage_users.php" class="btn btn-primary">Go to Users</a>
                        </div>
                    </div>
                </div>

                <!-- Book Loan Report Card -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-file-alt fa-3x text-info mb-3"></i>
                            <h4 class="card-title">Book Loan Report</h4>
                            <p class="card-text text-muted">View all book loan records with user and book details.</p>
                            <a href="admin_reports.php" class="btn btn-info">View Report</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>