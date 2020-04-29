<!DOCTYPE html>
<html>
    <head>
        <style>@import'style.css'</style>
        <?php require_once 'config.php'; ?>
        <title><?php echo SITENAME; ?></title>
        <link rel="icon" href="./img/icon.png"/>
        <?php 
        require_once 'functions.php';
        ?>    

        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script> 
        <script> (adsbygoogle = window.adsbygoogle || []).push({ google_ad_client: "ca-pub-3114985042987806", enable_page_level_ads: true }); </script>
        
    </head>
    
    <body class = "main_frame grid_container">
    <?php require_once 'header.php'; ?>
    <?php require_once 'list.php'; ?>
        
        
    <div class="content_area">    
        <?php require_once 'search-results.php'; ?>
        <div class='desktop-hide'><span id='cats'></span></div>
        <?php include_once 'main-ad.php'; ?>
        <?php include_once 'right-ad.php'; ?>
        
        
        <?php

	require_once 'conn.php'; 
	require_once 'class.phpmailer.php';



if(isset($_POST['password']) && isset($_POST['password2']) && isset($_POST['email']) && isset($_POST['username'])  && isset($_POST['hashcode'])){
    
    $password= get_post($conn, 'password');
    $password2= get_post($conn,'password2');
    $email= get_post($conn, 'email');
    
    $username = get_post($conn, 'username');
    $hashcode= get_post($conn, 'hashcode');
    $reset_code= generate_password_reset_code($conn,$username, $email);
    
    if($hashcode != $reset_code){
    	die("Girilen hashcode doğru değil!");
    }
    
    if($password == $password2){
        
        $result= change_password($conn,$email,$password);
        
        if(result){
            echo "<font color='green'><br><br>Your password is updated successfully, go to login page for login!</font>";
            
        }
        else{
            echo "<font color='red'><br><br>Password update is failed :(</font>";
        }
       
    }
    
    else{
        echo "<font color='red'>Passwords dont match!</font><br>";
    }
    
}

else if(isset($_GET['email']) && isset($_GET['u']) && isset($_GET['code'])){
    $email= get_get($conn,'email');
    $username= get_get($conn, 'u');
    $code= get_get($conn, 'code');
    
    $reset_code = generate_password_reset_code($conn,$username, $email);
    
    
    if($code == $reset_code){
        
        echo <<<_END
        
<form action='password-reset.php' method='post'>
<pre>
New password:
<input type='password' name='password' required='required'>
New password (verify):
<input type='password' name='password2' required='required'>
<input type='hidden' name = 'hashcode' value='$code'>
<input type='hidden' name='email' value='$email'>
<input type='hidden' name='username' value='$username'>
<input type='submit' value='Reset'>
</pre>
</form>
                
_END;
           
        
    }
    
    
}



else if(isset($_POST['email'])){
    
    $email= get_post($conn, 'email');
    $username=get_username($conn,$email);
    
    $check_email= check_email($conn,$email);
    
    if($check_email){
        $success = send_reset_pass_mail($conn,$username,$email);
        
        if($success){
        echo "<br>If you entered correct e-mail, password reset link will be sent to your mail!<br>"
        . "E-mail can be entered to your spam folder, please check!<br>";
        }
        else{
            echo "<br>Password reset link couldnt be sent to your e-mail!<font color='red'> couldnt be sent to your e-mail!</font>.<br>"
            . "Please try again later!";
        }
    }
    else{
        echo "<br>If you entered correct e-mail, password reset link will be sent to your mail!<br>"
        . "E-mail can be entered to your spam folder, please check!<br>";
    }
}



else{
    
    echo <<<_END
<form method='post' action='password-reset.php'>
<pre>

E-mail address:
<input type='email' name='email'>
<br>
<input id="button" type='submit' value='Send'>
</pre>
</form>

_END;
    
}
     include 'fluid-ad.php';



 
        
        
        $conn->close();

?>
        
        
        
        
        
        
        <?php require_once 'footer.php'; ?>
    </div>
 
        
    </body>
    
</html>



