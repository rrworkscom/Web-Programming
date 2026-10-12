<?php
$page_title = "Book List";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/connection.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$keyword = trim($_GET['keyword'] ?? '');

$perPage = 10;
$page = max(1, (int) ($_GET['page'] ?? 1));
$offset = ($page - 1) * $perPage;

$count = $pdo->prepare(
    "SELECT COUNT(*) FROM books
     WHERE title ILIKE :keyword
     OR author ILIKE :keyword"
);

$count->execute([
    'keyword' => '%' . $keyword . '%'
]);

$totalRows = $count->fetchColumn();
$totalPages = max(1, (int) ceil($totalRows / $perPage));

$stmt = $pdo->prepare(
    "SELECT * FROM books
     WHERE title ILIKE :keyword
     OR author ILIKE :keyword
     ORDER BY id DESC
     LIMIT :limit OFFSET :offset"
);

$stmt->bindValue('keyword', '%' . $keyword . '%');
$stmt->bindValue('limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue('offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section>
    <h2>Book List</h2>

    <?php if ($flash): ?>
        <p class="flash flash-<?php echo $flash['type']; ?>">
            <?php echo htmlspecialchars($flash['message']); ?>
        </p>
    <?php endif; ?>

    <form method="GET">
        <div class="search-box">
            <label for="search-input">
                Search Book Title or Author
            </label>

            <input
                type="text"
                id="search-input"
                name="keyword"
                placeholder="Type book title or author..."
                value="<?php echo htmlspecialchars($keyword); ?>">

            <button type="submit">Search</button>
        </div>
    </form>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Year</th>
                    <th>Stock</th>
                    <th>Date Added</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php if (empty($books)): ?>
                    <tr>
                        <td colspan="6">No book data found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($books as $book): ?>
                        <tr>
                            <td>
                                <?php echo htmlspecialchars($book['title']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($book['author']); ?>
                            </td>
                            <td><?php echo $book['year']; ?></td>
                            <td><?php echo $book['stock']; ?></td>
                            <td><?php echo $book['date_added']; ?></td>

                            <td>
                                <a
                                    href="edit.php?id=<?php echo $book['id']; ?>"
                                    class="btn-edit">
                                    Edit
                                </a>

                                <form
                                    class="form-delete"
                                    method="POST"
                                    action="delete.php">

                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?php echo $book['id']; ?>">

                                    <button
                                        type="submit"
                                        class="btn-delete">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <nav class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a
                href="list.php?page=<?php echo $i; ?>&keyword=<?php echo urlencode($keyword); ?>"
                class="<?php echo $i === $page ? 'active' : ''; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>
    </nav>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>