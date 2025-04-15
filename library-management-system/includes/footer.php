<?php
// Dynamically get the current year
$currentYear = date('Y');
?>

<footer class="bg-dark text-white py-4 mt-5">
    <div class="container">
        <div class="row justify-content-between align-items-center">
            <!-- Left Section: Copyright -->
            <div class="col-md-6 text-center text-md-start">
                <p class="mb-0">&copy; <?= $currentYear ?> Library Management System. All rights reserved. Felix Oghagbon</p>
            </div>

            <!-- Right Section: Social Media Icons -->
            <div class="col-md-6 text-center text-md-end">
                <a href="#" class="text-white me-3" target="_blank">
                    <i class="fab fa-facebook fa-lg"></i>
                </a>
                <a href="#" class="text-white me-3" target="_blank">
                    <i class="fab fa-twitter fa-lg"></i>
                </a>
                <a href="#" class="text-white me-3" target="_blank">
                    <i class="fab fa-instagram fa-lg"></i>
                </a>
                <a href="#" class="text-white" target="_blank">
                    <i class="fab fa-linkedin fa-lg"></i>
                </a>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap JS and Popper.js (if not already included in header.php) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>