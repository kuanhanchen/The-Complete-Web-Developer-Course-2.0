<footer class="footer">

    <div class="container">
        
        <p>&copy; My Website 2016</p>
    
    </div>

</footer>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js" integrity="sha384-vZ2WRJMwsjRMW/8U7i6PWi6AlO1L79snBrmgiDpgIWJ82z8eA5lenwvxbMV1PAh7" crossorigin="anonymous"></script>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="loginModalTitle">Login</h4>
      </div>
      <div class="modal-body">
          <div class="alert alert-danger" id="loginAlert"></div>
        <form>
            <input type="hidden" id="loginActive" name="loginActive" value="1">
          <fieldset class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" placeholder="Email address">
          </fieldset>
          <fieldset class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" placeholder="Password">
          </fieldset>
        </form>
      </div>
      <div class="modal-footer">
          <a id="toggleLogin">Sign up</a>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" id="loginSignupButton" class="btn btn-primary">Login</button>
      </div>
    </div>
  </div>
</div>

<script>

    $("#toggleLogin").click(function() {
        
        //now in Login mode (value=1) and switch to the SignUp mode (value=0)
        if ($("#loginActive").val() == "1") {
            
            $("#loginActive").val("0");
            $("#loginModalTitle").html("Sign Up");
            $("#loginSignupButton").html("Sign Up");
            $("#toggleLogin").html("Login");
            
        } else {//now in SignUp mode (value=0) and switch to the Login mode (value=1)
            
            $("#loginActive").val("1");
            $("#loginModalTitle").html("Login");
            $("#loginSignupButton").html("Login");
            $("#toggleLogin").html("Sign up");
            
        }

    })
    
    $("#loginSignupButton").click(function() {
        
        $.ajax({
            type: "POST",
            url: "actions.php?action=loginSignup",
            data: "email=" + $("#email").val() + "&password=" + $("#password").val() + "&loginActive=" + $("#loginActive").val(),
            success: function(result) {
                if (result == "1") {
                    
                    //redirect to the home page cuz we already be login
                    window.location.assign("http://79.170.44.77/kuanhanchenwebcourse.com/content/twitter/");
                    
                } else {
                    
                    //display an error msg in the login/signup form
                    $("#loginAlert").html(result).show();
                    
                }
            }
            
        })
        
    })

    $(".toggleFollow").click(function() {
        
        var id = $(this).attr("data-userId");
        
        $.ajax({
            type: "POST",
            url: "actions.php?action=toggleFollow",
            data: "userId=" + id,
            success: function(result) {
                
                //if ($_GET['action'] == 'toggleFollow') from actions.php
                //1: mean unfollow now
                if (result == "1") {
                    
                    //find all <a> links having data-userId attribute 
                    $("a[data-userId='" + id + "']").html("Follow");
                    
                } else if (result == "2") {//2: mean unfollow now
                    
                    $("a[data-userId='" + id + "']").html("Unfollow");
                    
                }
            }
            
        })
        
    })
    
    $("#postTweetButton").click(function() {
        
        $.ajax({
            type: "POST",
            url: "actions.php?action=postTweet",
            data: "tweetContent=" + $("#tweetContent").val(),
            success: function(result) {
                
                if (result == "1") {
                    
                    $("#tweetSuccess").show();
                    $("#tweetFail").hide();
                    
                } else if (result != "") {
                    
                    $("#tweetFail").html(result).show();
                    $("#tweetSuccess").hide();
                    
                }
            }
            
        })
        
    })
    
</script>


  </body>
</html>