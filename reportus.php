<!DOCTYPE html>
<?php session_start(); ?>
<html>
    <head>
        <?php require_once 'config.php'; ?>
        <title>ReportUs - <?php echo SITENAME; ?></title>
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
        
       
        if(isset($_GET["EntryNo"]))
        {
           $Entryid= $_GET['EntryNo'];
           $Title= get_title_from_entry($conn, $Entryid);
           $subjectMessage = "Report about entry numbered:'$Entryid' in the title:'$Title'";
        }
        else
        {
           $subjectMessage = "";
        }
       
        
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
                	$success = send_reportus_mail($conn,$email,$subject,$message);
            	}
            	 else{
                	die("You couldnt pass spam control! Please try again later!!<br>");
            	}
            }
            else{
                $success = send_reportus_mail($conn,$email,$subject,$message);
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
        
     

        
   <form  action="reportus.php" method="post" >
   
        <pre>
 
<br>
E-Mail:
<input style="font-size:12pt; width: 100%;" type="email" name="email" required="required" placeholder="Your email..">   
<br>
Subject:
<input style="color:dark; width: 100%; font-size:12pt" type="text" name="subject"  value="$subjectMessage" >
 
<label style="color:dark; width: 100%; font-size:12pt" for="message" >Message:</label>
<textarea id="message" name="message" placeholder="Write your feedbacks regarding the selected entry. Please explain the details about your complaint!" style="height:250px; font-size:12pt"></textarea>
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



