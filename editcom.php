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
                $login_success = (!empty($_SESSION["login_success"]) ? $_SESSION["login_success"] : null);
                $user = (!empty($_SESSION["user"]) ? $_SESSION["user"] : null);
                $first =(!empty( $_SESSION["first"]) ? $_SESSION["first"] : null);
                
                // Checks to see if a user is logged in
                if($login_success){ ?>
                <span>Welcome, <?php echo $first?>!</span><br><br>
                <form action="userpage.php" method="GET">
                    <input type="submit" value="View Userpage" />
                </form><br>
                <form action="storycreation.php" method="GET">
                    <input type="submit" value="Add Story" />
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
                $storyid = $_POST["story_id"];
                $comment_id = $_POST["comment_id"];
                $_SESSION["comment_id"] = $comment_id;
                
                // Gets comment in order to display it in the text box
                $stmt = $mysqli->prepare("SELECT comment FROM comments WHERE comment_id=?");
                if(!$stmt){
                    printf("Query Prep Failed: %s\n", $mysqli->error);
                    exit;
                }
                $stmt->bind_param('i', $comment_id);
                $stmt->execute();
                
                $stmt->bind_result($comments);
                $stmt->fetch();
                ?>
                <form action="editcomment.php" method="POST">
                    <span>Edit Comment: </span><br>
                    <textarea name="new_comment" rows="4" cols="150"><?php echo "$comments" ?></textarea><br><br>
                    <input type="submit" value="Submit Comment" /><br><br>
                </form>
                <?php
                $stmt->close();
                ?>
            </div>
        </div>
    </body>
</html>