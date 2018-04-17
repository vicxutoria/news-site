<?php
session_start();
require '/srv/database.php';

$first = (!empty($_POST["first"]) ? $_POST["first"] : null);
$last = (!empty($_POST["last"]) ? $_POST["last"] : null);
$user = (!empty($_POST["user"]) ? $_POST["user"] : null);
$password = (!empty($_POST["password"]) ? $_POST["password"] : null);
$pass_conf = (!empty($_POST["pass_conf"]) ? $_POST["pass_conf"] : null);
$captcha = (!empty($_POST["g-recaptcha-response"]) ? $_POST["g-recaptcha-response"] : null);

// Checks if CAPTCHA has been attempted
if(!$captcha){
	$_SESSION["captcha_fail"] = true;
	header("Location: usercreation.php");
	exit;
}

// Sends CAPTCHA key to google site to verify that user is human
$secretKey = "6LcY8EYUAAAAAOXJ4yYfSEveX_gTg1Hi1W1jHeB6";
$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$captcha);
$responseKeys = json_decode($response,true);

// Used for reporting that CAPTCHA failed
if(intval($responseKeys["success"]) !== 1) {
	$_SESSION["captcha_fail"] = true;
	header("Location: usercreation.php");
	exit;
}

// Collects all usernames stored in database
$stmt = $mysqli->prepare("SELECT username FROM users");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$stmt->execute();

$stmt->bind_result($users);

// Checks if username has already been taken
while($stmt->fetch()){
	if($user === $users){
		$_SESSION["user_fail"] = true;
		header("Location: usercreation.php");
		exit;
	}
}

$stmt->close();

// Used to determine if passwords matched
if($password != $pass_conf){
    $_SESSION["pass_fail"] = true;
    header("Location: usercreation.php");
    exit;
}

// Salts and hashes password given
$pass_hash = password_hash($password, PASSWORD_BCRYPT);

// Inserts user data into user table in database
$stmt = $mysqli->prepare("INSERT INTO users (username, password, first_name, last_name) VALUES (?, ?, ?, ?)");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$stmt->bind_param('ssss', $user, $pass_hash, $first, $last);

$stmt->execute();

$stmt->close();

// Used to report success in creating user
$_SESSION["create_success"] = true;
header("Location: usercreation.php");
exit;

?>