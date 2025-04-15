<?php
// /admin/delete_book.php
session_start();
require_once '../classes/Database.php';
require_once '../classes/Book.php';
require_once '../classes/Auth.php';

$db = new Database('localhost', 'library_management', 'root', '');
$auth = new Auth($db);
$book = new Book($db);

if (!$auth->isAdmin()) {
    header("Location: ../../index.php");
    exit();
}

$book_id = $_GET['id'];
$book->deleteBook($book_id);
header("Location: manage_books.php");
exit();
?>