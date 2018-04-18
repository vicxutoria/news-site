<?php
session_start();
require '/srv/database.php';
$user = $_SESSION["user"];
$comment_id = $_SESSION["comment_id"];
$editedcomments = $_POST["new_comment"];

$stmt = $mysqli->prepare("UPDATE comments SET comment=? WHERE comment_id=?");
if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$stmt->bind_param('si', $editedcomments, $comment_id);
$stmt->execute();
$stmt->close();
$_SESSION["comment_edit_success"] = true;
header("Location: userpage.php");
exit;
?> 