<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/partials/movie_card.php';

$pageTitle = 'Home';

// Hero: highest rated movie
$hero = $pdo->query("
    SELECT m.*, g.name AS genre_name
    FROM movies m LEFT JOIN genres g ON m.genre_id = g.id
    ORDER BY m.rating DESC LIMIT 1
")->fetch(PDO::FETCH_ASSOC);

// Featured (top 6 by rating)
$featured = $pdo->query("
    SELECT m.*, g.name AS genre_name
    FROM movies m LEFT JOIN genres g ON m.genre_id = g.id
    ORDER BY m.rating DESC LIMIT 6
")->fetchAll(PDO::FETCH_ASSOC);

// Latest (by release_year)
$latest = $pdo->query("
    SELECT m.*, g.name AS genre_name
    FROM movies m LEFT JOIN genres g ON m.genre_id = g.id
    ORDER BY m.release_year DESC LIMIT 4
")->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/partials/header.php';
include __DIR__ . '/partials/navbar.php';
?>

<!-- HERO -->
<?php if ($hero): ?>
<section class="hero" style="--bg: url('<?= htmlspecialchars($hero['poster']) ?>')">
    <div class="hero-overlay"></div>
    <div class="container hero-content">
        <span class="hero-badge"><?= htmlspecialchars($hero['genre_name'] ?? '') ?></span>
        <h1 class="hero-title"><?= htmlspecialchars($hero['title']) ?></h1>
        <div class="hero-meta">
            <span>⭐ <?= number_format($hero['rating'], 1) ?></span>
            <span>·</span>
            <span><?= $hero['release_year'] ?></span>
            <span>·</span>
            <span><?= $hero['duration'] ?> min</span>
        </div>
        <p class="hero-desc"><?= htmlspecialchars(substr($hero['description'], 0, 200)) ?>…</p>
        <div class="hero-actions">
            <a href="<?= BASE_URL ?>/pages/movie_detail.php?id=<?= $hero['id'] ?>" class="btn btn-primary">More Info</a>
            <span class="btn btn-secondary disabled">▶ Watch (Coming Soon)</span>
        </div>
    </div>
</section>
<?php endif; ?>

<main class="main-content container">

    <!-- FEATURED -->
    <section class="section">
        <div class="section-header">
            <h2 class="section-title">🔥 Top Rated</h2>
            <a href="<?= BASE_URL ?>/pages/movies.php" class="section-link">See all →</a>
        </div>
        <div class="movies-grid">
            <?php foreach ($featured as $movie): renderMovieCard($movie); endforeach; ?>
        </div>
    </section>

    <!-- LATEST -->
    <section class="section">
        <div class="section-header">
            <h2 class="section-title">🆕 Latest Releases</h2>
            <a href="<?= BASE_URL ?>/pages/movies.php?sort=year" class="section-link">See all →</a>
        </div>
        <div class="movies-grid movies-grid--4">
            <?php foreach ($latest as $movie): renderMovieCard($movie); endforeach; ?>
        </div>
    </section>

</main>

<?php include __DIR__ . '/partials/footer.php'; ?>

