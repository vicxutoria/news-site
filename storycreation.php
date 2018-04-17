<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <link rel="stylesheet" type="text/css" href="website.css" />
        <title>Create Story</title>
    </head>
    <body id="main">
        <!-- Link to Home Page included in all html pages -->
        <h1>
            <a style="color: #F128B7; text-decoration: none" href="homepage.php">
                News Site
            </a>
        </h1>
        <div>
            <!-- Collects info on story being submitted -->
            <form action="stories.php" method="POST">
                <label>Enter Story Title: <input type="text" name="story_title" required /></label><br><br>
                <label>Enter Story Link: <input type="url" name="story_link" required /></label><br><br>
                <span >Enter Story Text: </span><br>
                <textarea name="story" rows="4" cols="150"></textarea><br><br>
                <input type="submit" value="Save Story" /><br><br>
            </form>
            <?php
            session_start();
            $login_success = (!empty($_SESSION["login_success"]) ? $_SESSION["login_success"] : false);
            $user = $_SESSION["user"];
            $story_success = $_SESSION["story_success"];
            
            // Kicks non-users trying to add stories
            if(!$login_success){
                header("Location: homepage.php");
                exit;
            }
            // Reports if story was successfully added
            if($story_success){
                echo "Story added!";
                unset($_SESSION["story_success"]);
            }
            ?>
        </div>
    </body>
</html>