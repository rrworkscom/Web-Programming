<?php
require __DIR__ . '/includes/connection.php';

$json = file_get_contents(
    __DIR__ . '/../jobsheet-06/data/books.json'
);

$books = json_decode($json, true);

$stmt = $pdo->prepare(
    "INSERT INTO books (title, author, year, stock, category)
     VALUES (:title, :author, :year, :stock, :category)"
);

foreach ($books as $book) {
    $stmt->execute([
        'title' => $book['title'],
        'author' => $book['author'],
        'year' => $book['year'],
        'stock' => $book['stock'],
        'category' => $book['category']
    ]);
}

echo "Migration completed.";