<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" type="text/css" href="website.css" />
        <title>News Site</title>
    </head>
    <body id="main">
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
                
                if($login_success){ ?>
                <span>Welcome, <?php echo $first?>!</span><br><br>
                <form action="userpage.php" method="GET">
                    <input type="submit" value="View Userpage" />
                </form><br>
                <form action="logout.php" method="GET">
                    <input type="submit" value="Logout" />
                </form><br>
                <?php }
                else {
                    header("Location: homepage.php");
                    exit;
                }
                ?>
            </div>
            <div class="column right">
                <?php
                require '/srv/database.php';
                $story_id = (!empty($_SESSION["story_id"]) ? $_SESSION["story_id"] : $_GET["story_id"]);
                $_SESSION["story_id"] = $story_id;
                
                $stmt = $mysqli->prepare("SELECT title, link, story, username, date FROM stories where story_id=?");
                if(!$stmt){
                    printf("Query Prep Failed: %s\n", $mysqli->error);
                    exit;
                }
                $stmt->bind_param('i', $story_id);
                $stmt->execute();
                    
                $stmt->bind_result($titles, $links, $stories, $users, $dates);
                    
                $stmt->fetch();
                
                printf("\t<h2><a href=\"%s\">%s</a></h2>\n",
                        htmlspecialchars($links), htmlspecialchars($titles));
                printf("\t<span class=\"user\">Added by %s on %s</span>\n",
                        htmlspecialchars($users), htmlspecialchars($dates));
                printf("\t<h3>%s</h3>\n",
                        htmlspecialchars($stories));
                
                $stmt->close();
                ?>
            
                <form action="addcomment.php" method="POST">
                    <span>Write your comment here: </span><br>
                    <textarea name="comment" rows="4" cols="150"></textarea><br>
                    <input type="submit" value="Submit Comment" />
                </form><br>
                
                <?php
                $stmt = $mysqli->prepare("select comment, username, time from comments where story_id=? order by time");
                if(!$stmt){
                    printf("Query Prep Failed: %s\n", $mysqli->error);
                    exit;
                }
                
                $stmt->bind_param('i', $story_id);
                $stmt->execute(); 
                
                $stmt->bind_result($comments, $users, $times);
                
                while($stmt->fetch()){
                    printf("\t<p>%s</p>\n", htmlspecialchars($comments));
                    printf("\t<span class=\"user comment\">Written by %s on %s</span><br><br>\n",
                        htmlspecialchars($users), htmlspecialchars($times));
                }
                $_SESSION["user"] = $user;
                ?>
            </div>
        </div>
    </body>
</html>