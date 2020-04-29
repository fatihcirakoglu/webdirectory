<!DOCTYPE html>
<?php 
    session_start();
    ob_start("ob_gzhandler");
?>
<?php require_once 'config.php'; ?>
<html>
    <head>
        <title>Edit - <?php echo SITENAME; ?></title>
        <style>@import'style.css'</style>
        <link rel="icon" href="./img/icon.png"/>
        <?php
        require_once 'header.php';
        require_once 'functions.php';
        ?>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="language" content="English">
	    <meta name="description" content="Everything about internet, comments">
        <meta name="keywords" content="web site, review internet media, internet directory">
           
        <script>
        function deleteFunc(entry_id) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
        document.getElementById("delete_area_"+entry_id).innerHTML = this.responseText;
        }
        };
        xhttp.open("POST", "delete.php?entry_id="+entry_id , true);
        xhttp.send();
        }
        </script>
        
        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script> 
        <script> (adsbygoogle = window.adsbygoogle || []).push({ google_ad_client: "ca-pub-3114985042987806", enable_page_level_ads: true }); </script>
        
    </head>
    
    <body class = "main_frame grid_container">
    <?php require_once 'list.php'; ?>
    
        
        
    <div class="content_area">    
        <?php require_once 'search-results.php'; ?>
        <div class='desktop-hide'><span id='cats'></span></div>
                
        
        <?php
        require_once 'conn.php';

        
        if(isset($_SESSION['username'])){
            
            if(     isset($_POST['entry_id']) &&  
                    isset($_POST['stars']) && 
                    isset($_POST['entry'])){
                
                
                $entry_id= get_post($conn, 'entry_id');
                $stars= get_post($conn,'stars');
                $entry= get_post($conn,'entry');
                
                $success= update_entry($conn,$entry_id,$stars,$entry);
                
                if($success){
                    $title_id = get_title_id_from_entry_id($conn, $entry_id);
                    $num_pages = ceil(find_totalentry_of_title($conn, $title_id) / 10);
                    header("Location: title.php?id=$title_id&page=$num_pages");
                }
                else{
                    echo "Your comment <font color='red'>couldn't be updated.</font><br>"
                    . "Try again later.<br>";
                }
                
            }
            
            
            $current_username= $_SESSION['username'];
            $status= find_status_of_member($conn, $current_username);
            if(isset($_GET['id'])){
                $entry_id = get_get($conn,'id');
                $owner= find_owner_of_entry($conn, $entry_id);
                
                if( ($current_username == $owner ) || ($status=='admin') ){
                    display_edit_area($conn,$entry_id);
                }
                else{
                    echo "<font color='red'>You cant edit this comment.</font><br>";
                }
            
            }
            
        }
        else{
            echo "You must login.";
        }
        
        
        ?>
        <?php require_once 'footer.php'; ?> 
        
    </div>
 
        
    </body>
    
</html>


