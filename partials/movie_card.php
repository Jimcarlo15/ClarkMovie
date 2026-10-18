<?php
/**
 * Shared movie card partial.
 * Expects $movie array with keys: id, title, rating, release_year, duration, poster, genre_name
 */

function renderMovieCard(array $movie): void {
    $stars = renderStars($movie['rating']);
    $poster = htmlspecialchars($movie['poster'] ?? '');
    $title  = htmlspecialchars($movie['title']);
    $year   = (int)($movie['release_year'] ?? 0);
    $dur    = (int)($movie['duration']     ?? 0);
    $genre  = htmlspecialchars($movie['genre_name'] ?? 'Unknown');
    $id     = (int)$movie['id'];
    $rating = number_format((float)$movie['rating'], 1);
?>
<div class="movie-card" data-id="<?= $id ?>">
    <a href="<?= BASE_URL ?>/pages/movie_detail.php?id=<?= $id ?>" class="card-link">
        <div class="card-poster">
            <img src="<?= $poster ?>" alt="<?= $title ?>" loading="lazy"
                 onerror="this.src='<?= BASE_URL ?>/assets/images/no-poster.svg'">
            <div class="card-overlay">
                <span class="play-icon">▶</span>
                <p class="overlay-label">Coming Soon</p>
            </div>
            <span class="card-genre"><?= $genre ?></span>
        </div>
        <div class="card-info">
            <h3 class="card-title"><?= $title ?></h3>
            <div class="card-meta">
                <span class="meta-year"><?= $year ?></span>
                <span class="meta-sep">·</span>
                <span class="meta-dur"><?= $dur ?> min</span>
            </div>
            <div class="card-rating">
                <span class="stars"><?= $stars ?></span>
                <span class="rating-num"><?= $rating ?></span>
            </div>
        </div>
    </a>
</div>
<?php
}

function renderStars(float $rating): string {
    $full  = (int)floor($rating / 2);
    $half  = ($rating / 2 - $full) >= 0.4 ? 1 : 0;
    $empty = 5 - $full - $half;
    return str_repeat('★', $full) . str_repeat('⯨', $half) . str_repeat('☆', $empty);
}

