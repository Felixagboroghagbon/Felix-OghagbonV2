<?php
session_start();
require_once 'includes/header.php';
?>

<div class="container-fluid mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <!-- Login Card -->
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h2 class="mb-0">Library Management System</h2>
                </div>

                <div class="card-body p-4">
                    <!-- Project Details Section -->
                    <div class="text-center mb-4">
                        <h5 class="text-muted"><span style="text-transform:uppercase">Robert Gordon University(School Of Engineering)</span></h5>
                        <ul class="list-unstyled">
                            <li><span style="text-transform:uppercase"><strong>Name:</strong>Felix Agbor Oghagbon</span></li>
                            <li><strong>ID No:</strong> 2324848</li>
                            <li><span style="text-transform:uppercase"><strong>Semester Coursework Assessment:</strong>COMM 007, Intranet System Development</span></li>
                            <li><span style="text-transform:uppercase"><strong>Personal Submission:</strong> 17-04-2025</span></li>
                        </ul>
                    </div>

                    <!-- Login Form -->
                    <form method="POST" action="login.php" class="row g-3">
                        <div class="col-md-12">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="Enter your email" required>
                        </div>

                        <div class="col-md-12">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Enter your password" required>
                        </div>

                        <div class="col-md-12">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select form-select-lg" id="role" name="role" required>
                                <option value="" disabled selected>Select Role</option>
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                        </div>

                        <div class="col-12 text-center mt-4">
                            <button type="submit" class="btn btn-primary btn-lg px-5">Login</button>
                            <a href="register.php" class="btn btn-outline-secondary btn-lg ms-3">Register</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>