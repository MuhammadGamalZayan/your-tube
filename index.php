<?php
session_start();
require 'db.php';

$userName = '';
if (isset($_SESSION['user_id'])) {
    // Fetch the user's name
    $stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();

    if ($user) {
        // If the user exists, assign the username
        $userName = $user['username'];
    } else {
        // If no user is found, handle the error or redirect
        session_destroy();
        header("Location: login.php");
        exit();
    }
}

// Fetch all videos (you can replace this with actual video data from a database)
$videos = [
    ['id' => 'dQw4w9WgXcQ', 'title' => 'Video 1'],
    ['id' => 'M7lc1UVf-VE', 'title' => 'Video 2'],
    ['id' => '9bZkp7q19f0', 'title' => 'Video 3']
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Library</title>
</head>
<body>
    <h1>Welcome to the Video Library</h1>
    
    <?php if ($userName): ?>
        <p>Hello, <?= htmlspecialchars($userName) ?>! <a href="logout.php">Logout</a></p>
        <h2>Your Videos</h2>
    <?php else: ?>
        <p><a href="login.php">Login</a> or <a href="register.php">Register</a></p>
    <?php endif; ?>
    
    <ul>
        <?php foreach ($videos as $video): ?>
            <li>
                <a href="video_player.php?video_id=<?= $video['id'] ?>&title=<?= urlencode($video['title']) ?>"><?= htmlspecialchars($video['title']) ?></a>
                <?php if ($userName): ?>
                    <p>Progress: <span id="progress_<?= $video['id'] ?>">Loading...</span></p>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
