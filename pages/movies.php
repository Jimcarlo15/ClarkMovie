<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../partials/movie_card.php';

$pageTitle = 'All Movies';

$search  = trim($_GET['search']  ?? '');
$sort    = $_GET['sort']    ?? 'rating';
$genre   = (int)($_GET['genre'] ?? 0);
$page    = max(1, (int)($_GET['page'] ?? 1));
$perPage = 8;
$offset  = ($page - 1) * $perPage;

// Build WHERE clause
$where  = ['1=1'];
$params = [];
if ($search !== '') {
    $where[]  = "m.title LIKE :search";
    $params[':search'] = '%' . $search . '%';
}
if ($genre > 0) {
    $where[]  = "m.genre_id = :genre";
    $params[':genre'] = $genre;
}
$whereSQL = implode(' AND ', $where);

// ORDER BY
$orderMap = ['rating' => 'm.rating DESC', 'year' => 'm.release_year DESC', 'title' => 'm.title ASC'];
$orderSQL = $orderMap[$sort] ?? 'm.rating DESC';

// Total count
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM movies m WHERE $whereSQL");
$countStmt->execute($params);
$total = (int)$countStmt->fetchColumn();
$totalPages = (int)ceil($total / $perPage);

// Fetch movies
$stmt = $pdo->prepare("
    SELECT m.*, g.name AS genre_name
    FROM movies m LEFT JOIN genres g ON m.genre_id = g.id
    WHERE $whereSQL ORDER BY $orderSQL
    LIMIT :limit OFFSET :offset
");
foreach ($params as $k => $v) $stmt->bindValue($k, $v);
$stmt->bindValue(':limit',  $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset,  PDO::PARAM_INT);
$stmt->execute();
$movies = $stmt->fetchAll(PDO::FETCH_ASSOC);

// All genres for filter
$genres = $pdo->query("SELECT * FROM genres ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

// Build query string helper
function buildQuery(array $overrides): string {
    $base = array_filter([
        'search' => trim($_GET['search'] ?? ''),
        'sort'   => $_GET['sort']   ?? '',
        'genre'  => $_GET['genre']  ?? '',
        'page'   => $_GET['page']   ?? '',
    ]);
    return '?' . http_build_query(array_merge($base, $overrides));
}

include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../partials/navbar.php';
?>

<main class="main-content container">

    <div class="page-heading">
        <h1>🎞 All Movies</h1>
        <?php if ($search): ?>
            <p class="search-result-label">Results for: <em>"<?= htmlspecialchars($search) ?>"</em> — <?= $total ?> found</p>
        <?php endif; ?>
    </div>

    <!-- FILTERS -->
    <div class="filters">
        <form method="GET" class="filter-form">
            <select name="genre" onchange="this.form.submit()">
                <option value="0">All Genres</option>
                <?php foreach ($genres as $g): ?>
                    <option value="<?= $g['id'] ?>" <?= $genre === (int)$g['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($g['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <select name="sort" onchange="this.form.submit()">
                <option value="rating" <?= $sort === 'rating' ? 'selected' : '' ?>>Sort: Top Rated</option>
                <option value="year"   <?= $sort === 'year'   ? 'selected' : '' ?>>Sort: Newest</option>
                <option value="title"  <?= $sort === 'title'  ? 'selected' : '' ?>>Sort: A–Z</option>
            </select>
            <?php if ($search): ?>
                <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
            <?php endif; ?>
        </form>
    </div>

    <!-- GRID -->
    <?php if (empty($movies)): ?>
        <div class="empty-state">
            <p>😕 No movies found. Try a different search or filter.</p>
            <a href="<?= BASE_URL ?>/pages/movies.php" class="btn btn-primary">Clear Filters</a>
        </div>
    <?php else: ?>
        <div class="movies-grid">
            <?php foreach ($movies as $movie): renderMovieCard($movie); endforeach; ?>
        </div>

        <!-- PAGINATION -->
        <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="<?= BASE_URL . '/pages/movies.php' . buildQuery(['page' => $page - 1]) ?>" class="page-btn">← Prev</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="<?= BASE_URL . '/pages/movies.php' . buildQuery(['page' => $i]) ?>"
                   class="page-btn <?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="<?= BASE_URL . '/pages/movies.php' . buildQuery(['page' => $page + 1]) ?>" class="page-btn">Next →</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    <?php endif; ?>

</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>

