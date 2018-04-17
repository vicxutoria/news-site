<?php
session_start();
require '/srv/database.php';

$user = $_SESSION["user"];
$title = $_POST["story_title"];
$link = $_POST["story_link"];
$story = $_POST["story"];

// Inserts new story into the stories table
$stmt = $mysqli->prepare("INSERT INTO stories (title, link, story, username) VALUES (?, ?, ?, ?)");
if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}

$stmt->bind_param('ssss', $title, $link, $story, $user);
 
$stmt->execute();

$stmt->close();

// Used for reporting success
$_SESSION["story_success"] = true;
header("Location: storycreation.php");
exit;
?>