<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../partials/movie_card.php';

$pageTitle = 'Browse by Genre';

$genres = $pdo->query("
    SELECT g.*, COUNT(m.id) AS movie_count
    FROM genres g LEFT JOIN movies m ON m.genre_id = g.id
    GROUP BY g.id ORDER BY g.name
")->fetchAll(PDO::FETCH_ASSOC);

$selectedGenre = (int)($_GET['id'] ?? 0);
$genreMovies   = [];
$genreName     = '';

if ($selectedGenre > 0) {
    $gs = $pdo->prepare("SELECT name FROM genres WHERE id = ?");
    $gs->execute([$selectedGenre]);
    $genreName = $gs->fetchColumn();

    $ms = $pdo->prepare("
        SELECT m.*, g.name AS genre_name
        FROM movies m LEFT JOIN genres g ON m.genre_id = g.id
        WHERE m.genre_id = ? ORDER BY m.rating DESC
    ");
    $ms->execute([$selectedGenre]);
    $genreMovies = $ms->fetchAll(PDO::FETCH_ASSOC);
}

$genreIcons = [
    'Action'    => '💥',
    'Drama'     => '🎭',
    'Sci-Fi'    => '🚀',
    'Comedy'    => '😂',
    'Horror'    => '👻',
    'Romance'   => '❤️',
    'Thriller'  => '🔪',
    'Animation' => '🎨',
];

include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../partials/navbar.php';
?>

<main class="main-content container">

    <h1 class="page-heading-title">🎭 Browse by Genre</h1>

    <!-- GENRE PILLS -->
    <div class="genre-pills">
        <?php foreach ($genres as $g): ?>
            <a href="<?= BASE_URL ?>/pages/genre.php?id=<?= $g['id'] ?>"
               class="genre-pill <?= $selectedGenre === (int)$g['id'] ? 'active' : '' ?>">
                <?= $genreIcons[$g['name']] ?? '🎬' ?>
                <?= htmlspecialchars($g['name']) ?>
                <span class="pill-count"><?= $g['movie_count'] ?></span>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- MOVIES IN SELECTED GENRE -->
    <?php if ($selectedGenre > 0): ?>
        <section class="section">
            <div class="section-header">
                <h2 class="section-title">
                    <?= $genreIcons[$genreName] ?? '🎬' ?> <?= htmlspecialchars($genreName) ?>
                </h2>
            </div>
            <?php if (empty($genreMovies)): ?>
                <p class="empty-state">No movies in this genre yet.</p>
            <?php else: ?>
                <div class="movies-grid">
                    <?php foreach ($genreMovies as $movie): renderMovieCard($movie); endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    <?php else: ?>
        <p class="genre-hint">👆 Select a genre above to browse movies.</p>
    <?php endif; ?>

</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>

