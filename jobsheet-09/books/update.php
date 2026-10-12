<?php
session_start();
require __DIR__ . '/../includes/connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

$id = $_POST['id'] ?? null;
$title = trim($_POST['title'] ?? '');
$author = trim($_POST['author'] ?? '');
$year = $_POST['year'] ?? '';
$isbn = trim($_POST['isbn'] ?? '');
$stock = $_POST['stock'] ?? '';
$category = $_POST['category'] ?? '';

if (
    !$id || !ctype_digit((string) $id) ||
    $title === '' || $author === '' ||
    $year === '' || !ctype_digit((string) $year) ||
    $isbn === '' ||
    $stock === '' || !ctype_digit((string) $stock) ||
    !in_array($category, ['fiction', 'non-fiction', 'reference'], true)
) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'message' => 'Invalid book data.'
    ];

    header('Location: edit.php?id=' . urlencode((string) $id));
    exit;
}

$stmt = $pdo->prepare(
    "UPDATE books
     SET title = :title,
         author = :author,
         year = :year,
         isbn = :isbn,
         stock = :stock,
         category = :category
     WHERE id = :id"
);

$stmt->execute([
    'title' => $title,
    'author' => $author,
    'year' => (int) $year,
    'isbn' => $isbn,
    'stock' => (int) $stock,
    'category' => $category,
    'id' => (int) $id
]);

$_SESSION['flash'] = [
    'type' => 'success',
    'message' => 'Book updated successfully.'
];

header('Location: list.php');
exit;
?>
