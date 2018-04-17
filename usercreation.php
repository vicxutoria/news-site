<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <link rel="stylesheet" type="text/css" href="website.css" />
        <title>Create User</title>
    </head>
    <body id="main">
        <!-- Link to Home Page included in all html pages -->
        <h1>
            <a style="color: #F128B7; text-decoration: none" href="homepage.php">
                News Site
            </a>
        </h1>
        <div>
            <!-- Collects user info -->
            <form action="creation.php" method="POST">
                <label>Enter First Name: <input type="text" name="first" required /></label><br><br>
                <label>Enter Last Name: <input type="text" name="last" required /></label><br><br>
                <label>Enter Desired Username: <input type="text" name="user" required /></label><br><br>
                <label>Enter Password: <input type="password" name="password" required /></label><br><br>
                <label>Confirm Password: <input type="password" name="pass_conf" required /></label><br><br>
                <!-- <label>Enter E-mail: <input type="email" name="email" /></label><br><br> -->
                <div class="g-recaptcha" data-sitekey="6LcY8EYUAAAAAJqhe28SdswP_6bGFlxMshDoEefC"></div><br><br>
                <input type="submit" value="Create User!" /><br><br>
            </form>
            <?php
            session_start();
            $captcha_fail = (!empty($_SESSION["captcha_fail"]) ? $_SESSION["captcha_fail"] : null);
            $user_fail = (!empty($_SESSION["user_fail"]) ? $_SESSION["user_fail"] : null);
            $pass_fail = (!empty($_SESSION["pass_fail"]) ? $_SESSION["pass_fail"] : null);
            $create_success = (!empty($_SESSION["create_success"]) ? $_SESSION["create_success"] : null);
            
            // Reports if CAPTCHA failed
            if($captcha_fail){
                echo "Please check the CAPTCHA form.";
            }
            // Reports if username taken
            elseif($user_fail){
                echo "Username already taken.";
            }
            // Reports if password confirmation did not match
            elseif($pass_fail){
                echo "Passwords entered do not match.";
            }
            // Reports if user was successfully created
            elseif($create_success){
                echo "User created! Log in through the homepage!";
            }
            
            session_destroy();
            ?>
        </div>
        <script src='https://www.google.com/recaptcha/api.js'></script>
    </body>
</html>