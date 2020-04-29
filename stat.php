<!DOCTYPE html>
<?php session_start(); ?>
<?php require_once 'config.php'; ?>

<html>
    <head>
        <title>Statistics - <?php echo SITENAME; ?></title>
        <style>@import'style.css'</style>
        <link rel="icon" href="./img/icon.png"/>
        <?php
        require_once 'header.php';
        ?>
        
        
	<meta name="title" content="Yorumun">
        <meta name="description" content="Share your experiences and opinions about everything">
        <meta name="keywords" content="experiences, user experience, ccomplaints, websites, films, technology, blogs, games, places, businesses, organizations, games, comments, user comments, user feedbacks">
        <meta name="robots" content="index, follow">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="language" content="English">
        <meta name="author" content="comments, comment repository">
           
        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script> 
        <script> (adsbygoogle = window.adsbygoogle || []).push({ google_ad_client: "ca-pub-3114985042987806", enable_page_level_ads: true }); </script>
        
    </head>
    
    <body class = "main_frame grid_container">

    <?php require_once 'list.php'; ?>
    
        
    <div class="content_area">    
        <?php require_once 'search-results.php'; ?>
        <div class='desktop-hide'><span id='cats'></span></div>
        <?php include_once 'main-ad.php'; ?>
        <br><br><br>
        <?php include_once 'right-ad.php'; ?>
        <?php
    
    

require_once 'conn.php';
require_once 'functions.php';

$query= "SELECT COUNT(*) FROM users";
$result= $conn->query($query);
if(!$result){
    die($conn->error);
}
$row= $result->fetch_array(MYSQLI_NUM);

echo "<p>Registered users: ".$row[0]."</p>";



$query= "SELECT COUNT(*) FROM titles";
$result= $conn->query($query);
if(!$result){
    die($conn->error);
}
$row= $result->fetch_array(MYSQLI_NUM);

echo "<p>Entry number: ".$row[0]."</p>";






$query= "SELECT COUNT(*) FROM entries";
$result= $conn->query($query);
if(!$result){
    die($conn->error);
}
$row= $result->fetch_array(MYSQLI_NUM);

echo "<p>Comment number: ".$row[0]."</p>";


$last_user= last_user($conn);
echo "<p>Last user: <a href='profile.php?u=$last_user'>$last_user</a></p>";

$query="SELECT * FROM users WHERE onlinestatus='1'";
$result= $conn->query($query);
if(!$result){
    die($conn->error);
}
$num_rows= $result->num_rows;
echo "<p>Online Users: ".$num_rows."</p>";
include 'fluid-ad.php';




$conn->close();



?>
        <?php require_once 'footer.php'; ?> 
        
    </div>
 
        
    </body>
    
</html>



