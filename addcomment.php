<?php
session_start();
require '/srv/database.php';

$user = (!empty($_SESSION["user"]) ? $_SESSION["user"] : null);
$story_id = (!empty($_SESSION["story_id"]) ? $_SESSION["story_id"] : null);
$comment = (!empty($_POST["comment"]) ? $_POST["comment"] : null);

// Inserts new comment into the comments table
$stmt = $mysqli->prepare("INSERT INTO comments (comment, username, story_id) VALUES (?, ?, ?)");
if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}

$stmt->bind_param('ssi', $comment, $user, $story_id);
$stmt->execute();
$stmt->close();

header("Location: comments.php");
exit;
?>