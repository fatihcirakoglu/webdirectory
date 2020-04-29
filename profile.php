<!DOCTYPE html>
<html>
    <head>
        <?php
        
        require_once 'config.php';
	session_start();		
        require_once"conn.php";
        require_once 'functions.php';

        
        
        if(isset($_GET['u'])){
            $username = get_get($conn,'u');        
        
            echo "<title>$username - " . SITENAME . "</title>";
        }
        
        
        
        ?>
        <style>@import'style.css'</style>
        <link rel="icon" href="./img/icon.png"/>
        <?php 
        require_once 'header.php';
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


if(isset($_GET['u'])){
    
    $username= get_get($conn, 'u');
    
    
    if(isset($_POST['willBannedUser'])){
        $willBannedUser = get_post($conn, 'willBannedUser');
        ban($conn,$willBannedUser);
        
        echo $willBannedUser." named user is blocked!<br>";
    }
    
    if(isset($_POST['willUnBannedUser'])){
        $willUnBannedUser = get_post($conn, 'willUnBannedUser');
        unBan($conn,$willUnBannedUser);
        
        echo $willUnBannedUser." named user's block is removed!<br>";
    }    
    
    
    if(check_username($conn,$username)){
        $number_of_titles= number_of_titles($conn, $username);
        $number_of_entries = number_of_entries($conn, $username);
        echo "<h1>$username</h1>";
        echo "<span style='color: gray' title='comment number'>$number_of_entries - </span>";
        echo "<span style='color: gray' title='entry number opened'>$number_of_titles </span>";
        echo "<br>";

            if(isset($_SESSION['username'])){

                if($username != $_SESSION['username']){
                    $user_id = get_user_id($conn, $username);
                    echo <<<_END
                    <form action= 'message.php?u=$user_id' method='post'>

                    <input id='banButton' type='submit' value='SendMessage'>
                    </form>
_END;
                }


        include 'fluid-ad.php';

        $current_username = $_SESSION['username'];
        $status= find_status_of_member($conn, $current_username);
        $status_of_the_member= find_status_of_member($conn, $username);
        if(($status == 'admin') && ($status_of_the_member!='admin')){
            if(!isBanned($conn,$username)){
                echo <<<_END
                <form action= 'profile.php?u=$username' method='post'>

                <input type='hidden' name='willBannedUser' value='$username'>
                <input id='banButton' type='submit' value='Block'>
                </form>
_END;
            }
            else{
                echo "This user is blocked!<br>";
                echo <<<_END
                <form action='profile.php?u=$username' method='post'>

                <input type='hidden' name='willUnBannedUser' value='$username'>
                <input id='banButton' type='submit' value='Remove Block'>
                </form>
_END;
                
            }
        }
    }
        
        display_last_entries_of_user($conn,$username,20);
        include 'fluid-ad.php';

    }
    
    
    
    require_once 'footer.php';
    
    
}



?>
    </div>
    
    </body>
    
</html>



