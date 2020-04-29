<?php

require_once 'conn.php';
require_once 'functions.php';



if(isset($_GET['email']) && isset($_GET['code'])){
    
    $email= get_get($conn, 'email');
    $code= get_get($conn, 'code');
    
    $activate_code= generate_email_activate_code($conn, $email);
    
    
    if($code == $activate_code){
        
        if(activate_email($conn, $email)){
            session_start();
            $_SESSION['myverify']='true';
            header("Location: login.php");
        	echo "<br>  <center><b><font color='green'>Your account is activated succesfully!!!You will be redirected to website!</font></b><br><br>";
            
        }
    }
}

        

?>