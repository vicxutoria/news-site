<?php
session_start();
require '/srv/database.php';
$user = $_SESSION["user"];
$story_id = $_POST["story_id"];

$stmt = $mysqli->prepare("DELETE FROM comments WHERE story_id=?");
if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$stmt->bind_param('i', $story_id);
$stmt->execute();
$stmt->close();

// Removes story data from stories table
$stmt = $mysqli->prepare("DELETE FROM stories WHERE story_id=?");
if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$stmt->bind_param('i', $story_id);
$stmt->execute();
$stmt->close();

// Reports success
$_SESSION["story_delete_success"] = true;
header("Location: userpage.php");
exit;
?> 