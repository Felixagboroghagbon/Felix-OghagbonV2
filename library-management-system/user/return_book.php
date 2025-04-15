<?php
// /user/return_book.php
session_start();
require_once '../classes/Database.php';
require_once '../classes/Loan.php';
require_once '../classes/Auth.php';

$db = new Database('localhost', 'library_management', 'root', '');
$auth = new Auth($db);
$loan = new Loan($db);

if (!$auth->isUser()) {
    header("Location: ../../index.php");
    exit();
}

$loan_id = $_GET['loan_id'];
$book_id = $_GET['book_id']; // Assuming book_id is passed via query string

$loan->returnBook($loan_id, $book_id);
header("Location: borrowed_books.php");
exit();
?>