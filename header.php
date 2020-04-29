<!DOCTYPE html>

<?php
  require_once 'config.php';
  require_once 'functions.php';

  if($_SERVER['PHP_SELF'] != '/login.php'){
      $_SESSION['lastpage'] = "$_SERVER[REQUEST_URI]";
  }
?>
<header>
    
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="content-language" content="en-US" >
    <meta name="language" content="English">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="online directory, submit url, web directory, free dictionary, search engine submission, free directory submission sites, backlink, user comments, high rank directory, how is, all about web,
                                    BUSINESS directory,add url,free directory,web directory,Submit URL,online directory,search engine submit,search engine submitter,search engine submission,url search engine submission,best search engine submission,top search engine submission,
                                    list of search engine websites,,web search engine submission free,search engine,list of search engines,directory listing,directory submission,Internet DIRECTORY,links directory,
                                    directory submit,submit url to search engines,free directories,web directories,Google Add Url,free web directories,submit to search engines,free web directory,free directory listing,
                                    free search engine submission,search engine directory,Free Directory Submission,submit site to search engines,web directory list,online business directory,submit website to search engines,
                                    add url to search engines,web services directory">
    <meta name="author" content="comments, comment reprository">
    

    
    <link href="https://fonts.googleapis.com/css?family=Bree+Serif|Poppins|Ubuntu+Condensed" rel="stylesheet"> 
       
    <link rel="icon" href="./img/icon.png"/>
        
<script>
    function loadCats() {
      var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById("cats").innerHTML = this.responseText;
        }
      };
      xhttp.open("POST", "cats-mobile.php", true);
      xhttp.send();
    }


    function loadDocMobile(category) {
      var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById("cats").innerHTML = this.responseText;
        }
      };
      xhttp.open("POST", "list-content.php?category="+category , true);
      xhttp.send();
    }

</script>

      

<div class = "main_frame">
    
	<div class="headerlogo">
        
        <a href="/"><img alt='logo' src="img/logo.png" height='35px' title="Info Repo For Everyone | Internet Directory | All About Web"></a>
        <br>
        <a href='#top' class='desktop-hide' onclick='loadCats()'>category</a>
        </div>  

        
        <link rel="icon" href="./img/icon.png"/>
        <div class="searcharea">
            <form action="newtitle.php" method='get'> 
                
            <input  id="searchbox" name='title' placeholder="search|add me..title,#entry,@user" type="text"  autocomplete="off"
                   onkeyup="showHint(this.value);$('html, body').animate({ scrollTop: 0 }, 'fast');">
            </form>
        </div>
        
        
        
        
        
<?php require_once 'cats.php'; ?>

        
      <div class="membership">   
      <?php
      

      
      
      if(!isset($_SESSION['username'])){
           echo <<<_END
        <a rel='nofollow' id='login' href="login.php"><b>login</b></a>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <a rel='nofollow' id='register' href="register.php"><b>register</b></a>     
_END;
           
      }
      
      else{
          $current_username= $_SESSION['username'];

          require_once 'MessageClass.php';
          require_once 'conn.php';


          $message = new Message;
          $user_id = get_user_id($conn, $current_username);

          if($message->isThereNewMessage($user_id)){
            $color = '#80C14B';
            $icon = 'img/paper-plane-green.png';
          }
          else{
            $color = 'black';
            $icon = 'img/paper-plane.png';
          }

          echo <<< _END

          <b><a id='general_font' href='profile.php?u=$current_username'>
              <img width='20px' src='img/profile.png'>
                  <span class = "mobile-hide">$current_username</span></a></b>
                  &nbsp;&nbsp;
          <b><a style = "color: $color;" id='general_font' href='message.php'>
              <img width='20px' src="$icon">
                  <span class='mobile-hide'>message</span></a></b>
                  &nbsp;&nbsp;
          <b><a id='general_font' href='logout.php'><img title='logout' width='20px' src='img/logout.png'></a></b>


_END;
      }   
      
      
      
        ?>        
        </div>
        
</header>

