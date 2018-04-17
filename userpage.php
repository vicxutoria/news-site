<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <link rel="stylesheet" type="text/css" href="website.css" />
        <title>User Page</title>
    </head>
    <body id="main">
        <!-- Link to Home Page included in all html pages -->
        <h1>
            <a style="color: #F128B7; text-decoration: none" href="homepage.php">
                News Site
            </a>
        </h1>
        <p>
            <!-- Status reporter for story and comment actions -->
            <?php
            session_start();
            $story_edit = (!empty($_SESSION["story_edit_success"]) ? $_SESSION["story_edit_success"] : false);
            $story_delete = (!empty($_SESSION["story_delete_success"]) ? $_SESSION["story_delete_success"] : false);
            $comment_edit = (!empty($_SESSION["comment_edit_success"]) ? $_SESSION["comment_edit_success"] : false);
            $comment_delete = (!empty($_SESSION["comment_delete_success"]) ? $_SESSION["comment_delete_success"] : false);
            if($story_edit){
                echo "Story edited successfully";
                unset($_SESSION["story_edit_success"]);
            }
            elseif($story_delete){
                echo "Story deleted successfully";
                unset($_SESSION["story_delete_success"]);
            }
            elseif($comment_edit){
                echo "Comment edited successfully";
                unset($_SESSION["comment_edit_success"]);
            }
            elseif($comment_delete){
                echo "Comment deleted successfully";
                unset($_SESSION["comment_delete_success"]);
            }
            ?>
        </p>
        <div class="row">
            <!-- Places user action buttons on the left -->
            <div class="column left">
                <?php
                $login_success = (!empty($_SESSION["login_success"]) ? $_SESSION["login_success"] : false);
                $user = (!empty($_SESSION["user"]) ? $_SESSION["user"] : null);
                $first =(!empty( $_SESSION["first"]) ? $_SESSION["first"] : null);
                $page = (!empty($_GET["comments"]) ? $_GET["comments"] : "false");
                
                // Checks to see if a user is logged in
                if($login_success){ ?>
                <span>Welcome, <?php echo $first?>!</span><br><br>
                <form action="homepage.php" method="GET">
                    <input type="submit" value="View Homepage" />
                </form><br>
                <form action="userpage.php" method="GET">
                    <button name="comments" type="submit" value=false>View Stories</button><br><br>
                    <button name="comments" type="submit" value=true>View Comments</button>
                </form><br>
                <form action="storycreation.php" method="GET">
                    <input type="submit" value="Add Story" />
                </form><br>
                <form action="logout.php" method="GET">
                    <input type="submit" value="Logout" />
                </form><br>
                <?php }
                // Kicks non-user to homepage
                else {
                    header("Location: homepage.php");
                    exit;
                }
                ?>
            </div>
            <div class="column right">
                <?php
                require '/srv/database.php';
                
                // Stories Display
                if($page === "false"){
                    $stmt = $mysqli->prepare("SELECT story_id, title, link, story, username, date FROM stories WHERE username=? ORDER BY date DESC");
                    if(!$stmt){
                        printf("Query Prep Failed: %s\n", $mysqli->error);
                        exit; 
                    }
                    
                    $stmt->bind_param('s', $user);
                    $stmt->execute();
                    
                    $stmt->bind_result($story_ids, $titles, $links, $stories, $users, $dates);
                    
                    while($stmt->fetch()){
                        printf("\t<h2><a href=\"%s\">%s</a></h2>\n",
                               htmlspecialchars($links), htmlspecialchars($titles));
                        printf("\t<span class=\"user\">Added by %s on %s</span>\n",
                               htmlspecialchars($users), htmlspecialchars($dates));
                        printf("\t<h3>%s</h3>\n",
                           htmlspecialchars($stories));
                    ?>
                    <form action="comments.php" method="GET">
                        <button name="story_id" type="submit" <?php echo "value=$story_ids"; ?>>Show Comments</button><br>
                    </form>
                    <form action="editstory.php" method="POST">
                        <button name="story_id" type="submit" <?php echo "value=$story_ids"; ?>>Edit Story</button><br>
                    </form>
                    <form action="deletestory.php" method="POST">
                        <button name="story_id" type="submit" <?php echo "value=$story_ids"; ?>>Delete Story</button><br><br><br>
                    </form>
                    <?php
                    }
                    
                    $stmt->close();
                }
                
                // Comments Display
                else{
                    $stmt = $mysqli->prepare("SELECT comment_id, comment, story_id, time FROM comments WHERE username=? ORDER BY time DESC");
                    if(!$stmt){
                        printf("Query Prep Failed: %s\n", $mysqli->error);
                        exit;
                    }
                    
                    $stmt->bind_param('s', $user);
                    $stmt->execute();
                    
                    $stmt->bind_result($comment_ids, $comments, $story_ids, $times);
                    
                    while($stmt->fetch()){
                        printf("\t<h3>%s</h3>\n", htmlspecialchars($comments));
                        printf("\t<span class=\"user\">Written by %s on %s</span><br>\n",
                               htmlspecialchars($user), htmlspecialchars($times));
                        echo "<br>";
                    ?>
                    <form action="comments.php" method="GET">
                        <button name="story_id" type="submit" <?php echo "value=$story_ids"; ?>>Show Other Comments</button><br>
                    </form>
                    <form action="editcom.php" method="POST">
                        <button name="comment_id" type="submit" <?php echo "value=$comment_ids"; ?>>Edit Comment</button><br>
                    </form>
                    <form action="deletecom.php" method="POST">
                        <button name="comment_id" type="submit" <?php echo "value=$comment_ids"; ?>>Delete comment</button><br><br><br>
                    </form>
                    <?php
                    }
                    
                    $stmt->close();
                }
                ?>
            </div>
        </div>
    </body>
</html>