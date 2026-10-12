<?php
session_start();
require __DIR__ . '/../includes/connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

$id = $_POST['id'] ?? null;

if ($id && ctype_digit((string) $id)) {
    $stmt = $pdo->prepare(
        "DELETE FROM books WHERE id = :id"
    );

    $stmt->execute([
        'id' => (int) $id
    ]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => 'Book deleted successfully.'
        ];
    } else {
        $_SESSION['flash'] = [
            'type' => 'error',
            'message' => 'Book not found.'
        ];
    }
} else {
    $_SESSION['flash'] = [
        'type' => 'error',
        'message' => 'Invalid book ID.'
    ];
}

header('Location: list.php');
exit;
?>
