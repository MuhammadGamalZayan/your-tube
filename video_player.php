<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$videoId = $_GET['video_id'];
$title = urldecode($_GET['title']);

// Fetch user's progress
$stmt = $pdo->prepare("SELECT progress FROM video_progress WHERE user_id = ? AND video_id = ?");
$stmt->execute([$_SESSION['user_id'], $videoId]);
$row = $stmt->fetch();
$progress = $row ? $row['progress'] : 0;  // Default to 0 if no progress saved
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
</head>
<body>
    <h1><?= htmlspecialchars($title) ?></h1>
    <div id="player"></div>
    <button onclick="window.location.href='index.php'">Back to Videos</button>

    <script>
        // Load YouTube Iframe API script
        var tag = document.createElement('script');
        tag.src = "https://www.youtube.com/iframe_api";
        var firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

        var player;
        var currentTime = <?= $progress ?>;  // Set the saved progress as the initial time

        // This function is called when the API is ready
        function onYouTubeIframeAPIReady() {
            player = new YT.Player('player', {
                height: '360',
                width: '640',
                videoId: '<?= $videoId ?>',
                events: {
                    'onStateChange': onPlayerStateChange,
                    'onReady': onPlayerReady
                }
            });
        }

        // This function is called when the player is ready
        function onPlayerReady(event) {
            event.target.seekTo(currentTime, true);  // Start the video from the saved progress
        }

        // This function is called when the player state changes
        function onPlayerStateChange(event) {
            if (event.data == YT.PlayerState.PAUSED || event.data == YT.PlayerState.ENDED) {
                currentTime = player.getCurrentTime();  // Get the current playback time
                saveProgress(currentTime);  // Save the progress
            }
        }

        // Function to save progress to the server
        function saveProgress(time) {
            fetch('save_progress.php', {
                method: 'POST',
                body: JSON.stringify({
                    video_id: '<?= $videoId ?>',
                    progress: time
                }),
                headers: {
                    'Content-Type': 'application/json'
                }
            });
        }
    </script>
</body>
</html>
