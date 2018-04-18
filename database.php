<?php
// Connects the site to the news_site database
$mysqli = new mysqli('localhost', 'news_user', 'news_pass', 'news_site');

if($mysqli->connect_errno) {
	printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
}
?> 