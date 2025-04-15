<?php
// Enable error reporting (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);


require_once '../includes/header.php';
require_once '../classes/User.php';
require_once '../classes/Auth.php';

$db = new Database('localhost', 'library_management', 'root', '');
$auth = new Auth($db);

if (!$auth->isAdmin()) {
    header("Location: ../../index.php");
    exit();
}

// Validate and sanitize the user ID from the URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "Invalid user ID.";
    header("Location: manage_users.php");
    exit();
}

$userId = (int)$_GET['id'];
$user = new User($db);

// Fetch user details
$userDetails = $user->getUserById($userId);

if (!$userDetails) {
    $_SESSION['error'] = "User not found.";
    header("Location: manage_users.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $role = trim($_POST['role']);

    // Validate input
    if (empty($name) || empty($email) || empty($role)) {
        $_SESSION['error'] = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email address.";
    } else {
        try {
            // Update user details
            $user->updateUser($userId, $name, $email, $role);
            $_SESSION['success'] = "User updated successfully.";
            header("Location: manage_users.php");
            exit();
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
    }
}
?>

<div class="container-fluid mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <!-- Edit User Card -->
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Edit User</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= $_SESSION['error'] ?>
                            <?php unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="row g-3">
                        <div class="col-md-12">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($userDetails['name']) ?>" required>
                        </div>
                        <div class="col-md-12">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($userDetails['email']) ?>" required>
                        </div>
                        <div class="col-md-12">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="admin" <?= $userDetails['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                <option value="user" <?= $userDetails['role'] === 'user' ? 'selected' : '' ?>>User</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Update User</button>
                            <a href="manage_users.php" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>