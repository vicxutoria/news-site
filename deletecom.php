<?php
session_start();
require '/srv/database.php';
$user = $_SESSION["user"];
$comment_id = $_POST["comment_id"];

$stmt = $mysqli->prepare("DELETE FROM comments WHERE comment_id=?");
if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$stmt->bind_param('i', $comment_id);
$stmt->execute();
$stmt->close();
$_SESSION["comment_delete_success"] = true;
header("Location: userpage.php");
exit;
?>