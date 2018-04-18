<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <link rel="stylesheet" type="text/css" href="website.css" />
        <title>Home Page</title>
    </head>
    <body id="main">
        <!-- Link to Home Page included in all html pages -->
        <h1>
            <a style="color: #F128B7; text-decoration: none" href="homepage.php">
                News Site
            </a>
        </h1>
        <div class="row">
            <!-- Places user action buttons on the left -->
            <div class="column left">
                <?php
                session_start();
                $login_success = (!empty($_SESSION["login_success"]) ? $_SESSION["login_success"] : false);
                $user = (!empty($_SESSION["user"]) ? $_SESSION["user"] : null);
                $first =(!empty( $_SESSION["first"]) ? $_SESSION["first"] : null);
                
                // Checks if a user is logged in and displays different buttons based on user or non-user
                // User view
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
                
                // Non-user view
                else { ?>
                <form action="userlogin.php" method="GET">
                    <input type="submit" value="Log In" />
                </form><br>
                <form action="usercreation.php" method="GET">
                    <input type="submit" value="Create New User" />
                </form><br>
                <br><br>
                <?php } ?>
            </div>
            
            <!-- Places linked stories on the right -->
            <div class="column right">
                <?php
                require '/srv/database.php';
                
                // Queries the database for story information and displays it
                $stmt = $mysqli->prepare("SELECT story_id, title, link, story, username, date FROM stories ORDER BY date DESC");
                if(!$stmt){
                    printf("Query Prep Failed: %s\n", $mysqli->error);
                    exit;
                }
                
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
                <!-- Creates a button with the story id of that loop in order to direct the user to that story's comments -->
                <form action="comments.php" method="GET">
                    <button name="story_id" type="submit" <?php echo "value=$story_ids"; ?>>Show Comments</button><br><br><br>
                </form>
                <?php
                }
                
                $stmt->close();
                ?>
            </div>
        </div>
    </body>
</html> 