<!DOCTYPE html>
<html>
    <head>
        <?php require_once 'config.php'; ?>
        <title>Sign up - <?php echo SITENAME; ?></title>
        <style>        
        @import'style.css'
        </style>
        <link rel="icon" href="./img/icon.png"/>
        <script src='https://www.google.com/recaptcha/api.js'></script>
        <?php 
        require_once 'header.php';
        require_once 'functions.php';
	    if	(isset($_SESSION['username'])) {
		    header("Location: $SITEADDR");
	    }
        ?>   
        
        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script> 
        <script> (adsbygoogle = window.adsbygoogle || []).push({ google_ad_client: "ca-pub-3114985042987806", enable_page_level_ads: true }); </script>
        
        
    </head>
    
    <body class = "main_frame grid_container">
    <?php require_once 'list.php'; ?>

    <div class="content_area">   

        
        <?php require_once 'search-results.php'; ?>
        <div class='desktop-hide'><span id='cats'></span></div>
         <?php include_once 'main-ad.php'; ?>
        <?php include_once 'right-ad.php'; ?>
        
        <?php
        require_once 'class.phpmailer.php';
       
        require_once'conn.php';

  
        
        if(
                isset($_POST["username"]) &&
                isset($_POST["email"]) &&
                isset($_POST["password"]) &&
                isset($_POST["password2"]) &&
                isset($_POST["terms"])
                ){
            
            
            $username= get_post($conn,"username");
            $email= get_post($conn,"email");
            $password= get_post($conn,"password");
            $password2= get_post($conn,"password2");
            
            
            $query= "SELECT username FROM users WHERE username='$username'";
            $result= $conn->query($query);
            $result->data_seek(0);
            $flag=0;
            if($result->num_rows>0){
                echo "<font color='red'>This username is already used, please try another!</font><br>";
                $flag++;

            }
            
            
            if(!is_valid_username($username)){
                echo "<font color='red'>There cant be any username like that</font> Please try another!<br>";
                $flag++;
            }
            
            $query= "SELECT email FROM users WHERE email='$email'";
            $result= $conn->query($query);
            $result->data_seek(0);
            
            if($result->num_rows>0){
                echo "<font color='red'>This email is already used, try another!</font><br>";
                $flag++;

            }
            
            
            if($password!= $password2){
                echo "<font color='red'>Passwords do not match!</font><br>";
                $flag++;
            }
            
            
            if($flag==0){
            	
            	
            if($GCAPTCHA_OPEN) {
            $response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$captchaSecretCode."&response=".$_POST['g-recaptcha-response']."&remoteip=".$_SERVER['REMOTE_ADDR']), true);

            	 if($response['success']){
                	$success =  add_user($conn,$username, $password, $email);
            	}
            	 else{
                	die("You couldnt pass spam control!<br>");
            	}
            }
            else{
                $success =  add_user($conn,$username, $password, $email);
            }

            
            if($success==FALSE){
                
                echo "User registration is not successfull!<br>"
                . $conn->error."<br><br>";
                
            }
            else{
                echo "<font color='green'>Your registration is successfull!</font><br>";
                //require_once 'includes/google-registration-follower.php';

            }
            
        $success = send_activate_mail($conn, $username,$email);
        
            
        if($success){
            
            echo "Activation link is sent to your e-mail!<br>"
            . "Please click this link to activate your account!<br>"
            . "E-mail can be entered your spam folder, please check!<br>";
        }
        else{
            echo "<font color='red'>Activation link couldnt be sent to yout e-mail!!</font><br>"
            . "Please, try again later!";
        }
        
        
            }
        }


        echo <<<_END
   <form  method="post" action="register.php">
        <pre>
<br>
Username:
<input type="text" name="username" pattern="[A-Za-z0-9- -]+"
title='Username can include english characters and numbers, and can be max 40 characters!'
required="required">
<br>
E-mail:
<input type="email" name="email" required="required">   
<br>
Password:
<input type="password" name="password" required="required">
<br>
Password (again):
<input type="password" name="password2" required="required"><br>
<input type="checkbox" name="terms" required="required">I agree to Yorumun.com’s <a id='general_font' href="terms.php" onclick="this.target='_blank'"><font face="'Ubuntu Condensed', sans-serif" size="3" color=#5882FA>Privacy Policy</font></a><br> and<a href="terms.php" onclick="this.target='_blank'"><font face="'Ubuntu Condensed', sans-serif" size="3" color=#5882FA> Terms of Use</font></a><br><br/>
_END;
	if($GCAPTCHA_OPEN){
		echo "<div class='g-recaptcha' data-sitekey='$dataSiteKey'></div>";
	}
	echo <<<_END
<input id="button" type="submit" value="Sign up">
        </pre>
    </form>  
       
_END;
        include 'fluid-ad.php';
        $conn->close(); 
        
        
        ?>  
    
        
    <?php require_once 'footer.php'; ?> 
        
        

    </div>
    </body>
    
</html>



