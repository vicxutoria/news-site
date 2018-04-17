<?php
session_start();
require '/srv/database.php';
$user = $_SESSION["user"];
$story_id = $_SESSION["story_id"];
$edittitle = $_POST["new_title"];
$editstory = $_POST["new_story"];

// Updates data in the stories table
$stmt = $mysqli->prepare("UPDATE stories SET title=?, story=? WHERE story_id=?");
if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$stmt->bind_param('ssi', $edittitle, $editstory, $story_id);
$stmt->execute();
$stmt->close();

// Used to report success
$_SESSION["story_edit_success"] = true;
header("Location: userpage.php");
exit;
?>