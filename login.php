<?php
session_start();
require '/srv/database.php';

$user = $_POST["user"];
$pass_guess = $_POST["password"];

// Collects all info needed to verify user
$stmt = $mysqli->prepare("SELECT count(*), password, first_name FROM users WHERE username=?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$stmt->bind_param('s', $user);
$stmt->execute();

$stmt->bind_result($count, $pass_hash, $first);
$stmt->fetch();

// If user exists and hashed password matches the one entered, user is logged in
if($count == 1 && password_verify($pass_guess, $pass_hash)){
    $_SESSION["user"] = $user;
    $_SESSION["first"] = $first;
    $_SESSION["login_success"] = true;
    header("Location: homepage.php");
    exit;
}
// Either username or password was incorrect
else{
	$_SESSION["login_fail"] = true;
    header("Location: userlogin.php");
    exit;
}
?>