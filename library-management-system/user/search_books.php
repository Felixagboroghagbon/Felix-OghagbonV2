<?php
// /user/search_books.php
session_start();
require_once '../includes/header.php';
require_once '../classes/Database.php';
require_once '../classes/Auth.php';

$db = new Database('localhost', 'library_management', 'root', '');
$auth = new Auth($db);

if (!$auth->isUser()) {
    header("Location: ../../index.php");
    exit();
}

$searchTerm = '';
$books = [];
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10; // Number of books per page

// Handle search form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $searchTerm = trim($_POST['search']);

    if (!empty($searchTerm)) {
        $_SESSION['search_term'] = $searchTerm; // Store search term in session
    } else {
        unset($_SESSION['search_term']); // Clear search term from session if input is empty
    }

    header("Location: search_books.php?page=1"); // Redirect to reset pagination
    exit();
}

// Handle "Clear Search" action
if (isset($_GET['clear_search'])) {
    unset($_SESSION['search_term']); // Clear the search term from the session
    header("Location: search_books.php?page=1"); // Redirect to reset the page
    exit();
}

// Retrieve search term from session (if it exists)
if (isset($_SESSION['search_term'])) {
    $searchTerm = $_SESSION['search_term'];
}

// Fetch paginated books based on search term or all books if no search term
if (!empty($searchTerm)) {
    $query = "
        SELECT * FROM books 
        WHERE title LIKE :search OR author LIKE :search OR genre LIKE :search
    ";
    $params = ['search' => "%$searchTerm%"];
} else {
    $query = "SELECT * FROM books";
    $params = [];
}

$books = $db->paginate($query, $page, $limit, '', $params);
$totalRecords = $db->countRows($query, '', $params);
$totalPages = ceil($totalRecords / $limit);
?>

<div class="container-fluid mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Search Form Card -->
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Search Books</h4>
                </div>
                <div class="card-body">
                    <form method="POST" class="row g-3">
                        <div class="col-md-8">
                            <label for="search" class="form-label">Search by Title, Author, or Genre</label>
                            <input type="text" class="form-control form-control-lg" id="search" name="search" placeholder="Enter search term" value="<?= htmlspecialchars($searchTerm) ?>" required>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary btn-lg w-100 me-2">Search</button>
                            <a href="?clear_search=true" class="btn btn-secondary btn-lg w-100">Clear Search</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- All Books or Search Results Table -->
            <?php if (!empty($books)): ?>
                <div class="card shadow mb-4">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><?= !empty($searchTerm) ? 'Search Results' : 'All Available Books' ?></h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Genre</th>
                                    <th>ISBN</th>
                                    <th>Quantity</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($books as $book): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($book['title']) ?></td>
                                        <td><?= htmlspecialchars($book['author']) ?></td>
                                        <td><?= htmlspecialchars($book['genre']) ?></td>
                                        <td><?= htmlspecialchars($book['isbn']) ?></td>
                                        <td><?= htmlspecialchars($book['quantity']) ?></td>
                                        <td>
                                            <a href="borrow_book.php?id=<?= $book['id'] ?>" class="btn btn-sm btn-success">
                                                <i class="fas fa-book"></i> Borrow
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= ($i === $page) ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php elseif ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
                <div class="alert alert-warning text-center" role="alert">
                    No books found matching your search term.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>