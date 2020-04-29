<?php debug_backtrace() || die ("<h2>Access Denied!</h2> This file is protected and not available to public."); ?>
<?php //conn.php

	 require_once 'config.php';


    $conn = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
    if($conn->connect_error){
       die($conn->connect_error);
    }

	$conn->query("SET NAMES 'utf8'");
	$conn->query("SET CHARACTER SET utf8");

?>
