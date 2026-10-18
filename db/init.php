<?php
// Database initialization and seeding

$db_path = __DIR__ . '/movies.db';
$pdo = new PDO('sqlite:' . $db_path);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Create tables
$pdo->exec("
    CREATE TABLE IF NOT EXISTS genres (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL UNIQUE
    );

    CREATE TABLE IF NOT EXISTS movies (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        description TEXT,
        release_year INTEGER,
        rating REAL DEFAULT 0,
        duration INTEGER,
        poster TEXT,
        genre_id INTEGER,
        FOREIGN KEY (genre_id) REFERENCES genres(id)
    );
");

// Seed genres
$genres = ['Action', 'Drama', 'Sci-Fi', 'Comedy', 'Horror', 'Romance', 'Thriller', 'Animation'];
$stmt = $pdo->prepare("INSERT OR IGNORE INTO genres (name) VALUES (?)");
foreach ($genres as $g) {
    $stmt->execute([$g]);
}

// Seed movies (using placeholder poster images via picsum or omdbapi-style)
$movies = [
    [
        'title'        => 'Interstellar',
        'description'  => 'A team of explorers travel through a wormhole in space in an attempt to ensure humanity\'s survival as Earth faces an environmental catastrophe that threatens all life.',
        'release_year' => 2014,
        'rating'       => 8.7,
        'duration'     => 169,
        'poster'       => 'https://image.tmdb.org/t/p/w500/gEU2QniE6E77NI6lCU6MxlNBvIx.jpg',
        'genre'        => 'Sci-Fi',
    ],
    [
        'title'        => 'The Dark Knight',
        'description'  => 'When the menace known as the Joker wreaks havoc and chaos on the people of Gotham, Batman must accept one of the greatest psychological and physical tests of his ability to fight injustice.',
        'release_year' => 2008,
        'rating'       => 9.0,
        'duration'     => 152,
        'poster'       => 'https://image.tmdb.org/t/p/w500/qJ2tW6WMUDux911r6m7haRef0WH.jpg',
        'genre'        => 'Action',
    ],
    [
        'title'        => 'Inception',
        'description'  => 'A thief who steals corporate secrets through the use of dream-sharing technology is given the inverse task of planting an idea into the mind of a C.E.O.',
        'release_year' => 2010,
        'rating'       => 8.8,
        'duration'     => 148,
        'poster'       => 'https://image.tmdb.org/t/p/w500/oYuLEt3zVCKq57qu2F8dT7NIa6f.jpg',
        'genre'        => 'Sci-Fi',
    ],
    [
        'title'        => 'The Shawshank Redemption',
        'description'  => 'Two imprisoned men bond over a number of years, finding solace and eventual redemption through acts of common decency.',
        'release_year' => 1994,
        'rating'       => 9.3,
        'duration'     => 142,
        'poster'       => 'https://image.tmdb.org/t/p/w500/q6y0Go1tsGEsmtFryDOJo3dEmqu.jpg',
        'genre'        => 'Drama',
    ],
    [
        'title'        => 'Avengers: Endgame',
        'description'  => 'After the devastating events of Infinity War, the universe is in ruins. The Avengers assemble once more to reverse Thanos\'s actions and restore balance to the universe.',
        'release_year' => 2019,
        'rating'       => 8.4,
        'duration'     => 181,
        'poster'       => 'https://image.tmdb.org/t/p/w500/or06FN3Dka5tukK1e9sl16pB3iy.jpg',
        'genre'        => 'Action',
    ],
    [
        'title'        => 'Forrest Gump',
        'description'  => 'The presidencies of Kennedy and Johnson, the Vietnam War, the Watergate scandal and other historical events unfold through the perspective of an Alabama man with an IQ of 75.',
        'release_year' => 1994,
        'rating'       => 8.8,
        'duration'     => 142,
        'poster'       => 'https://image.tmdb.org/t/p/w500/saHP97rTPS5eLmrLQEcANmKrsFl.jpg',
        'genre'        => 'Drama',
    ],
    [
        'title'        => 'Get Out',
        'description'  => 'A young African-American visits his white girlfriend\'s parents for the weekend, where his simmering uneasiness about their reception of him eventually reaches a terrifying climax.',
        'release_year' => 2017,
        'rating'       => 7.7,
        'duration'     => 104,
        'poster'       => 'https://image.tmdb.org/t/p/w500/tFXcEccSqH6gradorNXSIQJnJ6x.jpg',
        'genre'        => 'Horror',
    ],
    [
        'title'        => 'The Grand Budapest Hotel',
        'description'  => 'A writer encounters the owner of an aging European hotel between the wars and learns of his storied past involving the theft of a priceless Renaissance painting and a battle for an enormous family fortune.',
        'release_year' => 2014,
        'rating'       => 8.1,
        'duration'     => 99,
        'poster'       => 'https://image.tmdb.org/t/p/w500/eWdyYQreja6JGCzqHWXpWHDrrPo.jpg',
        'genre'        => 'Comedy',
    ],
    [
        'title'        => 'Spider-Man: Into the Spider-Verse',
        'description'  => 'Teen Miles Morales becomes the Spider-Man of his universe and must join with five spider-powered individuals from other dimensions to stop a threat for all realities.',
        'release_year' => 2018,
        'rating'       => 8.4,
        'duration'     => 117,
        'poster'       => 'https://image.tmdb.org/t/p/w500/iiZZdoQBEYBv6id8su7ImL0oCbD.jpg',
        'genre'        => 'Animation',
    ],
    [
        'title'        => 'La La Land',
        'description'  => 'While navigating their careers in Los Angeles, a pianist and an actress fall in love while attempting to reconcile their aspirations for the future.',
        'release_year' => 2016,
        'rating'       => 8.0,
        'duration'     => 128,
        'poster'       => 'https://image.tmdb.org/t/p/w500/uDO8zWDhfWwoFdKS4fzkUJt0Rf0.jpg',
        'genre'        => 'Romance',
    ],
    [
        'title'        => 'Parasite',
        'description'  => 'Greed and class discrimination threaten the newly formed symbiotic relationship between the wealthy Park family and the destitute Kim clan.',
        'release_year' => 2019,
        'rating'       => 8.5,
        'duration'     => 132,
        'poster'       => 'https://image.tmdb.org/t/p/w500/7IiTTgloJzvGI1TAYymCfbfl3vT.jpg',
        'genre'        => 'Thriller',
    ],
    [
        'title'        => 'Dune',
        'description'  => 'Feature adaptation of Frank Herbert\'s science fiction novel, about the son of a noble family entrusted with the protection of the most valuable asset and most vital element in the galaxy.',
        'release_year' => 2021,
        'rating'       => 8.0,
        'duration'     => 155,
        'poster'       => 'https://image.tmdb.org/t/p/w500/d5NXSklXo0qyIYkgV94XAgMIckC.jpg',
        'genre'        => 'Sci-Fi',
    ],
];

// Check if already seeded
$count = $pdo->query("SELECT COUNT(*) FROM movies")->fetchColumn();
if ((int)$count === 0) {
    $stmt = $pdo->prepare("INSERT INTO movies (title, description, release_year, rating, duration, poster, genre_id)
        VALUES (?, ?, ?, ?, ?, ?, (SELECT id FROM genres WHERE name = ?))");
    foreach ($movies as $m) {
        $stmt->execute([$m['title'], $m['description'], $m['release_year'], $m['rating'], $m['duration'], $m['poster'], $m['genre']]);
    }
}

return $pdo;

