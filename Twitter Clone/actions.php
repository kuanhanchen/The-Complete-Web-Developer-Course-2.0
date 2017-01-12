<?php

    include("functions.php");

    if ($_GET['action'] == "loginSignup") {
        
        $error = "";
        
        //validate email address and password
        //if no email
        if (!$_POST['email']) {
            
            $error = "An email address is required.";
            
        } else if (!$_POST['password']) {//if no password
            
            $error = "A password is required";
            
        } else if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false) {//if not valid email address
  
            $error = "Please enter a valid email address.";
            
        }
        
        if ($error != "") {
            
            //echo to result
            echo $error;
            exit();
            
        }
        
        //sign user up
        if ($_POST['loginActive'] == "0") {
            
            //our table named as users
            //mysqli_real_escape_string() escapes special characters in a string for use in an SQL statement
            $query = "SELECT * FROM users WHERE email = '". mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";
            $result = mysqli_query($link, $query);

            //mysqli_num_rows($result) returns number of rows in the result set.
            //>0 means there is the same email address in the table
            if (mysqli_num_rows($result) > 0) $error = "That email address is already taken.";

            else {//the new email address is the new one
                
                $query = "INSERT INTO users (`email`, `password`) VALUES ('". mysqli_real_escape_string($link, $_POST['email'])."', '". mysqli_real_escape_string($link, $_POST['password'])."')";
                
                if (mysqli_query($link, $query)) {
                    
                    $_SESSION['id'] = mysqli_insert_id($link);
                    
                    //hash password
                    $query = "UPDATE users SET password = '". md5(md5($_SESSION['id']).$_POST['password']) ."' WHERE id = ".$_SESSION['id']." LIMIT 1";
                    //run the query
                    mysqli_query($link, $query);
                    
                    //echo to result
                    echo 1;
                    
                } else {
                    
                    $error = "Couldn't create user - please try again later";
                    
                }
                
            }
            
        } else {//$_POST['loginActive'] == "1", log user in
            
            $query = "SELECT * FROM users WHERE email = '". mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";
            
            $result = mysqli_query($link, $query);
            
            $row = mysqli_fetch_assoc($result);
                
            if ($row['password'] == md5(md5($row['id']).$_POST['password'])) {
                
                //echo to result
                echo 1;
                
                $_SESSION['id'] = $row['id'];
                
            } else {
                
                //echo to result
                $error = "Could not find that username/password combination. Please try again.";
                
            }
            
        }
        
         if ($error != "") {
            
            //echo to result
            echo $error;
            exit();
            
        }
        
        
    }

    if ($_GET['action'] == 'toggleFollow') {
        
        $query = "SELECT * FROM isFollowing WHERE follower = ". mysqli_real_escape_string($link, $_SESSION['id'])." AND isFollowing = ". mysqli_real_escape_string($link, $_POST['userId'])." LIMIT 1";
        
        $result = mysqli_query($link, $query);
        
        if (mysqli_num_rows($result) > 0) {//means it is following, so changing to be unfollowing
            
            $row = mysqli_fetch_assoc($result);
            
            mysqli_query($link, "DELETE FROM isFollowing WHERE id = ". mysqli_real_escape_string($link, $row['id'])." LIMIT 1");
            
            echo "1";
              
        } else {//means it is unfollowing, so changing to be following
            
            mysqli_query($link, "INSERT INTO isFollowing (follower, isFollowing) VALUES (". mysqli_real_escape_string($link, $_SESSION['id']).", ". mysqli_real_escape_string($link, $_POST['userId']).")");
            
            echo "2";
            
        }
        
    }

    if ($_GET['action'] == 'postTweet') {
        
        if (!$_POST['tweetContent']) {
                    
            echo "Your tweet is empty!";
                    
            } else if (strlen($_POST['tweetContent']) > 140) {
            
            echo "Your tweet is too long!";
            
        } else {
            
            //save content to tweets table
            mysqli_query($link, "INSERT INTO tweets (`tweet`, `userid`, `datetime`) VALUES ('". mysqli_real_escape_string($link, $_POST['tweetContent'])."', ". mysqli_real_escape_string($link, $_SESSION['id']).", NOW())");
            
            echo "1";
            
        }
        
    }

?>