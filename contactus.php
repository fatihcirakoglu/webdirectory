<!DOCTYPE html>
<?php session_start(); ?>
<?php require_once 'config.php'; ?>
<html>
    <head>
        <title>ContactUs - <?php echo SITENAME; ?></title>
        <style>        
        @import'style.css'
        </style>
        <link rel="icon" href="./img/icon.png"/>
        <script src='https://www.google.com/recaptcha/api.js'></script>
        <?php
        require_once 'config.php';
        require_once 'header.php';
        require_once 'functions.php';
        $SITEADDR = SITEADDR;
	    if	(isset($_SESSION['username'])) {
		    header("Location: $SITEADDR");
	    }
        ?>
        
         <?php
        require_once 'header.php';
        ?>
        
          <script src="https://code.jquery.com/jquery-2.2.0.min.js" type="text/javascript"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        
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
            isset($_POST["email"]) &&
            isset($_POST["subject"])
        ){
            
            
            $subject= get_post($conn,"subject");
            $email= get_post($conn,"email");
            
           
    	    $subject= $_POST['subject'];
	        $email = $_POST['email'];
	        $message = $_POST['message'];
          
          
        
                   	
            	
            if($GCAPTCHA_OPEN) {
            $response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$captchaSecretCode."&response=".$_POST['g-recaptcha-response']."&remoteip=".$_SERVER['REMOTE_ADDR']), true);

            	 if($response['success']){
                	$success = send_contactus_mail($conn,$email,$subject,$message);
            	}
            	 else{
                	die("You couldnt pass spam control!<br>");
            	}
            }
            else{
                $success = send_contactus_mail($conn,$email,$subject,$message);
            }
            
        
        
            
        if($success){
            
            echo "<font color='green'>Your message is sent successfully!</font><br>" 
            . "Thanks for messaging us!<br>"
            . "We will try to reply back as soon as early!<br>";
        }
        else{
            echo "<font color='red'>Your message couldnt be sent successfully!</font><br>"
            . "Please try again later!";
        }
        
        
            
        }


        echo <<<_END
        
        
     
   <form  method="post" action="contactus.php">
   
        <pre>

<br>
E-Mail:
<input style="font-size:12pt; width: 100%;" type="email" name="email" required="required" placeholder="Your email..">   
<br>
Subject:
<input style="font-size:12pt; width: 100%;" type="text" name="subject" required="required" placeholder="Subject..">

<label for="message">Message:</label>
<textarea  id="message" name="message" placeholder="Write something.." style="height:250px; font-size:12pt"></textarea>
<br>
_END;
	if($GCAPTCHA_OPEN){
		echo "<div class='g-recaptcha' data-sitekey='$dataSiteKey'></div>";
	}
	echo <<<_END
<input id="button" type="submit" value="Submit">
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



