<nav class="navbar">
    <div class="container nav-inner">
        <a href="<?= BASE_URL ?>/index.php" class="logo">
            🎬 <span>Clark</span>Movie
        </a>
        <ul class="nav-links">
            <li><a href="<?= BASE_URL ?>/index.php" class="<?= (basename($_SERVER['PHP_SELF']) === 'index.php') ? 'active' : '' ?>">Home</a></li>
            <li><a href="<?= BASE_URL ?>/pages/movies.php" class="<?= (basename($_SERVER['PHP_SELF']) === 'movies.php') ? 'active' : '' ?>">Movies</a></li>
            <li><a href="<?= BASE_URL ?>/pages/genre.php" class="<?= (basename($_SERVER['PHP_SELF']) === 'genre.php') ? 'active' : '' ?>">Genres</a></li>
        </ul>
        <div class="nav-search">
            <form action="<?= BASE_URL ?>/pages/movies.php" method="GET">
                <input type="text" name="search" placeholder="Search movies..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                <button type="submit">🔍</button>
            </form>
        </div>
    </div>
</nav>

