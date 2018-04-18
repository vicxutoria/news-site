<?php
// Destroys session and logs user out
session_start();
unset($_SESSION["user"]);
session_destroy();

header("Location: homepage.php");
exit();
?> 