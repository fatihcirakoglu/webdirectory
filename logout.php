<?php
require_once 'config.php';

session_start();		
require_once"conn.php";
require_once 'functions.php';


$username = $_SESSION['username'];
$query= "UPDATE users SET onlinestatus='0' WHERE username='$username'";
$result= $conn->query($query);
if(!$result){
    die($conn->error);
}


$_SESSION= array();
setcookie(session_name(),'',time()-60*60,'/');
//header("Location: {$_SERVER['HTTP_REFERER']}");
if(isset($_SERVER['HTTP_REFERER'])) {
    
  header("Location: ".$_SERVER['HTTP_REFERER']);  
   }
else
{
  $SITEADDR = SITEADDR;
  header("Location: $SITEADDR");
}


session_destroy();  


?>
