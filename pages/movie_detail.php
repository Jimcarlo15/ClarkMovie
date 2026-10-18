<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../partials/movie_card.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: ' . BASE_URL . '/pages/movies.php');
    exit;
}

$stmt = $pdo->prepare("
    SELECT m.*, g.name AS genre_name
    FROM movies m LEFT JOIN genres g ON m.genre_id = g.id
    WHERE m.id = ?
");
$stmt->execute([$id]);
$movie = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$movie) {
    header('Location: ' . BASE_URL . '/pages/movies.php');
    exit;
}

$pageTitle = $movie['title'];

// Related movies (same genre, exclude current)
$related = $pdo->prepare("
    SELECT m.*, g.name AS genre_name
    FROM movies m LEFT JOIN genres g ON m.genre_id = g.id
    WHERE m.genre_id = ? AND m.id != ?
    LIMIT 4
");
$related->execute([$movie['genre_id'], $id]);
$relatedMovies = $related->fetchAll(PDO::FETCH_ASSOC);

function renderStars(float $r): string {
    $full  = (int)floor($r / 2);
    $half  = ($r / 2 - $full) >= 0.4 ? 1 : 0;
    $empty = 5 - $full - $half;
    return str_repeat('★', $full) . str_repeat('⯨', $half) . str_repeat('☆', $empty);
}

include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../partials/navbar.php';
?>

<main class="main-content container">

    <a href="javascript:history.back()" class="back-link">← Back</a>

    <div class="detail-wrapper">

        <!-- POSTER SIDE -->
        <div class="detail-poster">
            <img src="<?= htmlspecialchars($movie['poster']) ?>"
                 alt="<?= htmlspecialchars($movie['title']) ?>"
                 onerror="this.src='<?= BASE_URL ?>/assets/images/no-poster.svg'">
            <div class="detail-play-badge">
                <span class="play-icon-lg">▶</span>
                <span>Not Available Yet</span>
            </div>
        </div>

        <!-- INFO SIDE -->
        <div class="detail-info">
            <span class="detail-genre-badge"><?= htmlspecialchars($movie['genre_name'] ?? 'Unknown') ?></span>
            <h1 class="detail-title"><?= htmlspecialchars($movie['title']) ?></h1>

            <div class="detail-meta-row">
                <span class="meta-chip">📅 <?= $movie['release_year'] ?></span>
                <span class="meta-chip">⏱ <?= $movie['duration'] ?> min</span>
                <span class="meta-chip rating-chip">⭐ <?= number_format($movie['rating'], 1) ?> / 10</span>
            </div>

            <div class="detail-stars">
                <?= renderStars($movie['rating']) ?>
                <span class="rating-label"><?= number_format($movie['rating'], 1) ?> out of 10</span>
            </div>

            <!-- Rating bar -->
            <div class="rating-bar-wrap">
                <div class="rating-bar" style="--pct: <?= ($movie['rating'] / 10) * 100 ?>%"></div>
            </div>

            <h2 class="detail-section-label">Overview</h2>
            <p class="detail-desc"><?= htmlspecialchars($movie['description']) ?></p>

            <div class="detail-actions">
                <span class="btn btn-primary disabled">▶ Watch (Coming Soon)</span>
                <a href="<?= BASE_URL ?>/pages/movies.php?genre=<?= $movie['genre_id'] ?>" class="btn btn-outline">
                    More <?= htmlspecialchars($movie['genre_name'] ?? '') ?>
                </a>
            </div>
        </div>
    </div>

    <!-- RELATED -->
    <?php if (!empty($relatedMovies)): ?>
    <section class="section">
        <div class="section-header">
            <h2 class="section-title">Related Movies</h2>
        </div>
        <div class="movies-grid movies-grid--4">
            <?php foreach ($relatedMovies as $m): renderMovieCard($m); endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>

