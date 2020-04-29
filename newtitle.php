<!DOCTYPE html>
<?php ob_start(); ?>
<html>
    <head>
        <?php require_once 'config.php'; ?>
        <title><?php echo SITENAME; ?></title>
        <style>@import'style.css'</style>
        <link rel="icon" href="./img/icon.png"/>
        <?php
        
        session_start();
        require_once 'header.php';
        require_once 'functions.php';
        ?>    
           
        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script> 
        <script> (adsbygoogle = window.adsbygoogle || []).push({ google_ad_client: "ca-pub-3114985042987806", enable_page_level_ads: true }); </script>
        
    </head>
    
    <body class= "main_frame grid_container">
    <?php require_once 'list.php'; ?>
    
    <div class="content_area">
        
    <?php require 'search-results.php'; ?>
        <div class='desktop-hide'><span id='cats'></span></div>
        
    <?php
    
    
    require_once 'conn.php';
    require_once 'class.phpmailer.php';

        if(isset($_GET['title'])){
            $title= get_get($conn, 'title');
            if($title[0]=='@'){
                $username_in_query = substr($title,1);
                if(isThereUser($conn,$username_in_query)){
                    goUserPage($username_in_query);
                }
                else{
                    echo "There is no such user!\n";
                    die();
                }
            }
            
            
            $title= alt_replace($title);
            $title_id= get_title_id($conn,$title);
            
        if(control_title($conn,$title)==1){
                header("Location: title.php?title_id=$title_id");
            }
        else if(control_title($conn,$title)==2){
            die("This kind of entry cant be, mustnt be.");
        }
        else if(!isset($_SESSION['username'])){
            echo "<br><br>There is no entry like that, lets create it!<br><br>";
            echo "For opening new entry <a href='login.php'><font color=green>you have to login!</font></a><br>";
            include 'feed-ad.php';
        }
        
        }

    if(isset($_SESSION['username'])){
            $username= $_SESSION['username'];
            $email= $_SESSION['email'];
             
            if(isset($_POST['mailactivate'])){
		$success = send_activate_mail($conn, $username, $email); 
                if($success){
                    echo "E-mail activation link <font color='green'>is sent successfully!</font><br>"
                    . "Please check your mailbox.<br>"
                    . "If you dont see e-mail, please check your spam folder!<br>";
                }
                else{
                    echo "E-mail activation link <font color='red'>is couldn't be sent!</font><br>"
                    . "Please try again later!";
                }
                
                
            }
            
       
    if(     isset($_POST['title-next']) &&
            isset($_POST['entry']) &&
            isset($_POST['category'])
            ){
        
        $title= get_post($conn,'title-next');
        if(STARVOTE)
            $stars= get_post($conn,"stars");
        $entry= get_post($conn,"entry");
        $category= get_post($conn,"category");
        $title_id= add_title_and_entry($conn,$title,$stars,$entry,$category);
            
        header("Location: title.php?title_id=$title_id");

        
    }
    
    else{       
        
        $email= $_SESSION['email'];
        $username =$_SESSION['username'];
        $email_check =  email_check($conn, $email);
        $isBanned= isBanned($conn, $username);
        $title_check= title_check($title);
        
        if(STARVOTE){
            $starvote = "How many star?:
            <select name='stars' size='1'>
            <option selected='selected' value='0'>Score</option>  
            <option value='1'>1 (Very Bad)</option>
            <option value='2'>2 (Bad)</option>
            <option value='3'>3 (Avarage)</option>
            <option value='4'>4 (Good)</option>
            <option value='5'>5 (Excellent)</option>  
            </select>
            <br><br>";
        }
        else{
            $starvote = "";
        }


        if($email_check){
            
            if($title_check && !$isBanned){
            echo <<<_END
            <br><br>
            There is no entry like that, lets create it!
            <form method="post" action="newtitle.php">
            <h1>$title</h1>
            <input type='hidden' name='title-next' value='$title'>
            $starvote
      
            Category:
            <select name="category" size='1'>
            <option value='random'>random</option>
            <option value='socialmedia'>socialmedia</option>
            <option value='websites'>websites</option>
            <option value='blogs'>blogs</option>
            <option value='technology'>technology</option>
            <option value='games'>games</option>
            <option value='places'>places</option>
            <option value='businesses'>businesses</option>
            <option value='organizations'>organizations</option>
            <option value='films'>films</option>
            </select>
            <br><br>    
        
            <textarea name="entry" rows='10' spellcheck='false'  required="required"></textarea>
            <br><br>       
            <input id="button" type="submit" value="Submit">
            </form>  
_END;
             include 'fluid-ad.php';
            }
            else if($isBanned){
                echo "You accoount is blocked!<br>";
            }
            else{
                echo "Entry can be maximum 64 characters!<br>";
            }
        }
        

        else{
            echo "<font color='red'>You cant enter comment since you didn't verify your e-mail!</font><br>"
            . "Please verify your e-mail!<br>";
            
            echo <<<_END
            <form action='newtitle.php' method='post'>
            <input type='hidden' name='mailactivate' value='yes'>
            <input type='submit' value='Send activation e-mail'>
            </form>
            
_END;
                    }
        
        }
    }
    
    
    
    ?>
        
 
    
    </div>
    
    </body>
    
</html>


