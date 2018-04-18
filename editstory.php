<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" type="text/css" href="website.css" />
        <title>News Site</title>
    </head>
    <body id="main">
         <!-- Places user action buttons on the left -->
        <h1>
            <a style="color: #F128B7; text-decoration: none" href="homepage.php">
                News Site
            </a>
        </h1>
        <div class="row">
            <div class="column left">
                <?php
                session_start();
                $login_success = (!empty($_SESSION["login_success"]) ? $_SESSION["login_success"] : false);
                $user = (!empty($_SESSION["user"]) ? $_SESSION["user"] : null);
                $first =(!empty( $_SESSION["first"]) ? $_SESSION["first"] : null);
                
                // Checks to see if a user is logged in
                if($login_success){ ?>
                <span>Welcome, <?php echo $first?>!</span><br><br>
                <form action="userpage.php" method="GET">
                    <input type="submit" value="View Userpage" />
                </form><br>
                <form action="logout.php" method="GET">
                    <input type="submit" value="Logout" />
                </form><br>
                <?php }
                
                // Kicks non-users to the homepage
                else {
                    header("Location: homepage.php");
                    exit;
                }
                ?>
            </div>
            <div class="column right">
                <?php
                require '/srv/database.php';
                $story_id = $_POST["story_id"];
                $_SESSION["story_id"] = $story_id;
                
                if(!$login_success){
                header("Location: homepage.php");
                exit;
                }
                
                // Gets story data to display it in text box
                $stmt = $mysqli->prepare("select title, link, story from stories where story_id=? and username=?");
                if(!$stmt){
                    printf("Query Prep Failed: %s\n", $mysqli->error);
                    exit;
                }
                $stmt->bind_param('is', $story_id, $user);
                
                $stmt->execute();
                
                $stmt->bind_result($titles, $links, $stories);
                $stmt->fetch();
                ?>
                <form action="storyedit.php" method="POST">
                    <label>Edit Story Title: <input type="text" name="new_title" <?php echo "value='$titles'"; ?> required /></label><br><br>
                    <span>Edit Story Text: </span><br>
                    <textarea name="new_story" rows="4" cols="150"><?php echo "$stories"; ?></textarea><br><br>
                    <input type="submit" value="Save Story" /><br><br>
                </form>
                <?php
                $stmt->close();
                ?>
            </div>
        </div>
    </body>
</html> 