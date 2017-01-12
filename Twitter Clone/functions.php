<?php

    session_start();

    $link = mysqli_connect("localhost", "cl38-twitter-qg5", "7dxqsy^x.", "cl38-twitter-qg5");

    if (mysqli_connect_errno()) {
        
        print_r(mysqli_connect_error());
        exit();
        
    }

    // press logout button, unset all session data
    if ($_GET['function'] == "logout") {
        
        session_unset();
        
    }

    // The function takes the number of seconds as input and outputs text such as 10 seconds or 1 minute
    function time_since($since) {

        $chunks = array(
            array(60 * 60 * 24 * 365 , 'year'),
            array(60 * 60 * 24 * 30 , 'month'),
            array(60 * 60 * 24 * 7, 'week'),
            array(60 * 60 * 24 , 'day'),
            array(60 * 60 , 'hour'),
            array(60 , 'min'),
            array(1 , 'sec')
        );

        for ($i = 0, $j = count($chunks); $i < $j; $i++) {
            $seconds = $chunks[$i][0];
            $name = $chunks[$i][1];
            if (($count = floor($since / $seconds)) != 0) {
                break;
            }
        }

        $print = ($count == 1) ? '1 '.$name : "$count {$name}s";
        return $print;

    }

    function displayTweets($type) {
        
        //enable to access $link variable inside this function
        global $link;
        
        if ($type == 'public') {//for index.php, display all tweets
            
            $whereClause = "";
                
        } else if ($type == 'isFollowing') {//for timeline.php, only display what u follow
            
            $query = "SELECT * FROM isFollowing WHERE follower = ". mysqli_real_escape_string($link, $_SESSION['id']);
            $result = mysqli_query($link, $query);
            
            $whereClause = "";
            
            while ($row = mysqli_fetch_assoc($result)) {
                
                if ($whereClause == "") $whereClause = "WHERE";
                else $whereClause.= " OR";
                $whereClause.= " userid = ".$row['isFollowing'];
                
            }
            
        } else if ($type == 'yourtweets') {//for yourtweets.php, only display your tweets
            
            $whereClause = "WHERE userid = ". mysqli_real_escape_string($link, $_SESSION['id']);
            
        } else if ($type == 'search') {//for search.php, only display what u search
            
            echo '<p>Showing search results for "'.mysqli_real_escape_string($link, $_GET['q']).'":</p>';
            
            //search "anything(%) + search string + anything(%)"
            $whereClause = "WHERE tweet LIKE '%". mysqli_real_escape_string($link, $_GET['q'])."%'";
            
        } else if (is_numeric($type)) {//clicking one of user email in publicprofiles.php, then displaying this user's tweets
            
            $userQuery = "SELECT * FROM users WHERE id = ".mysqli_real_escape_string($link, $type)." LIMIT 1";
            $userQueryResult = mysqli_query($link, $userQuery);
            $user = mysqli_fetch_assoc($userQueryResult);
            
            echo "<h2>".mysqli_real_escape_string($link, $user['email'])."'s Tweets</h2>";
            
            $whereClause = "WHERE userid = ". mysqli_real_escape_string($link, $type);
            
            
        }
        
        
        $query = "SELECT * FROM tweets ".$whereClause." ORDER BY `datetime` DESC LIMIT 10";
        
        $result = mysqli_query($link, $query);
        
        if (mysqli_num_rows($result) == 0) {
            
            echo "There are no tweets to display.";
            
        } else {
            
            while ($row = mysqli_fetch_assoc($result)) {
                
                $userQuery = "SELECT * FROM users WHERE id = ".mysqli_real_escape_string($link, $row['userid'])." LIMIT 1";
                $userQueryResult = mysqli_query($link, $userQuery);
                $user = mysqli_fetch_assoc($userQueryResult);
                
                //using current time(time()) - timestamp(strtotime($row['datetime'])) to get "xxx ago"
                //make userid be a link(redirect to publicprofile and limit to this userid), then enable to click this link to see this user's tweets
                echo "<div class='tweet'><p><a href='?page=publicprofiles&userid=".$user['id']."'>".$user['email']."</a> <span class='time'>".time_since(time() - strtotime($row['datetime']))." ago</span>:</p>";
                
                echo "<p>".$row['tweet']."</p>";
                
                //follow/unfollow
                echo "<p><a class='toggleFollow' data-userId='".$row['userid']."'>";
                
                $isFollowingQuery = "SELECT * FROM isFollowing WHERE follower = ". mysqli_real_escape_string($link, $_SESSION['id'])." AND isFollowing = ". mysqli_real_escape_string($link, $row['userid'])." LIMIT 1";
                $isFollowingQueryResult = mysqli_query($link, $isFollowingQuery);
                
                //check follow/unfollow
                //means it has been followed, so display as unfollow
                if (mysqli_num_rows($isFollowingQueryResult) > 0) {
                    
                    echo "Unfollow";
                    
                } else {//means it has been unfollowed, so display as follow
                    
                    echo "Follow";
                    
                }
                
                
                echo "</a></p></div>";
                
            }
            
        }
        
        
    }

    function displaySearch() {
        
        echo '<form class="form-inline">
  <div class="form-group">
    <input type="hidden" name="page" value="search">
    <input type="text" name="q" class="form-control" id="search" placeholder="Search">
  </div>
  <button type="submit" class="btn btn-primary">Search Tweets</button>
</form>';
        
        
    }

    function displayTweetBox() {
        
        if ($_SESSION['id'] > 0) {
            
            echo '<div id="tweetSuccess" class="alert alert-success">Your tweet was posted successfully.</div>
            <div id="tweetFail" class="alert alert-danger"></div>
            <div class="form">
                <div class="form-group">
                    <textarea class="form-control" id="tweetContent"></textarea>
                </div>
                <button id="postTweetButton" class="btn btn-primary">Post Tweet</button>
            </div>';
            
            
        }
        
        
    }

    function displayUsers() {
        
        global $link;
        
        $query = "SELECT * FROM users LIMIT 10";
        
        $result = mysqli_query($link, $query);
            
        while ($row = mysqli_fetch_assoc($result)) {
            
            echo "<p><a href='?page=publicprofiles&userid=".$row['id']."'>".$row['email']."</a></p>";
            
        }
        
        
        
    }

?>