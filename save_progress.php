<?php
session_start();
require 'db.php';

$data = json_decode(file_get_contents("php://input"));
$videoId = $data->video_id;
$progress = $data->progress;
$userId = $_SESSION['user_id'];

if (!isset($userId)) {
    echo json_encode(["status" => "error", "message" => "User not logged in"]);
    exit;
}

// Save progress to the database
$stmt = $pdo->prepare("REPLACE INTO video_progress (user_id, video_id, progress) VALUES (?, ?, ?)");
$stmt->execute([$userId, $videoId, $progress]);

echo json_encode(["status" => "success"]);
?>
