<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <link rel="stylesheet" type="text/css" href="website.css" />
        <title>Login</title>
    </head>
    <body id="main">
        <!-- Link to Home Page included in all html pages -->
        <h1>
            <a style="color: #F128B7; text-decoration: none" href="homepage.php">
                    News Site
            </a>
        </h1>
        <div>
            <!-- Collects login info -->
            <form action="login.php" method="POST">
                <label>Enter Username: <input type="text" name="user" /></label><br><br>
                <label>Enter Password: <input type="password" name="password" /></label><br><br>
                <input type="submit" value="Log In" /><br><br>
            </form>
            
            <?php
            session_start();
            $login_fail = $_SESSION["login_fail"];
            
            // Reports if login failed
            if($login_fail){
                echo "Invalid username or password.";
                session_destroy();
            }
            ?>
        </div>
    </body>
</html> 