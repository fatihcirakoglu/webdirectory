<!DOCTYPE html>
<?php ob_start(); ?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=big5">
        <?php require_once 'config.php'; ?>
        <title>Login - <?php echo SITENAME; ?></title>
        <style>@import'style.css'</style>
        <link rel="icon" href="./img/icon.png"/>
        <?php

        require_once 'header.php';
        require_once 'functions.php';
        ?>    
           
        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script> 
        <script> (adsbygoogle = window.adsbygoogle || []).push({ google_ad_client: "ca-pub-3114985042987806", enable_page_level_ads: true }); </script>
        
        <script data-ad-client="ca-pub-3114985042987806" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        
    </head>
    
    <body class = "main_frame grid_container">
    <?php require_once 'list.php'; ?>
    
    <div class="content_area">    

            
        
        
        <?php require_once 'search-results.php'; ?>
        <div class='desktop-hide'><span id='cats'></span></div>
        <?php include_once 'main-ad.php'; ?>
        <?php include_once 'right-ad.php'; ?>
        
        <?php

        require_once"conn.php";
        

        if(     isset($_POST["email"]) &&
                isset($_POST["password"])
                ){
            
            $email= get_post($conn,"email");
            $password= get_post($conn,"password");
            
            $password= encryptPass($password);
            
            $query= "SELECT password FROM users WHERE email='$email'";
            $result= $conn->query($query);
            $result->data_seek(0);
            $real_pass= $result->fetch_assoc()['password'];
            
            
            
            if($real_pass!=$password){
                echo"<font color='red'>Email or password is wrong!</font><br>";
            }
            
            
            else{
                $query= "SELECT username FROM users WHERE email='$email'";
                $result= $conn->query($query);
                $result->data_seek(0);
                $username= $result->fetch_assoc()['username'];
                
                logIn($username,$email);

            }
        }
 
        
    if(!isset($_SESSION['username'])){
        
      
           session_start();
           if( $_SESSION['myverify'] == 'true')
           {
               session_destroy();
               echo"<br><font color='green'>Thanks for your confirmation!<br>Please login to continue!</font><br>";
           }
                
                
            

          
            
        
        
        echo <<<_END
<pre>
<form method="post" action="login.php">
E-mail:
<input type="email" name="email" autocomplete='on' autofocus='autofocus' required="required">
<br>        
Password:
<input type="password" name="password" required="required"><br>
<input id="button" type="submit" value="Login">       
</form>  
<a href='password-reset.php'>Forgot password?</a> | <a href='register.php'>Sign up</a>
</pre>  
_END;
      include 'fluid-ad.php';
    }
    else{
        echo "<font color='green'>Login is completed successfully!</font><br>";
        
        $query= "UPDATE users SET onlinestatus='1' WHERE username='$username'";
        $result= $conn->query($query);
        if(!$result){
            die($conn->error);
        }
        
        header('Location: index.php');
        
        
    }
    
    $conn->close();
    
    
        ?>  
        
    <?php require_once 'footer.php'; ?> 
        
    </div>
    </body>
    
</html>


