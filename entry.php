<!DOCTYPE html>
<html>
    <head>
        <?php
        session_start();
        
        require_once 'conn.php';
        require_once 'functions.php';
        require_once 'config.php';
        
        
        
        if(isset($_GET['id'])){
            $entry_id= get_get($conn, 'id');
            $title= get_title_from_entry($conn, $entry_id);
            echo "<title>comment: $title - " . SITENAME . "</title>";
            
        }
        
	        
        ?>
        <style>@import'style.css'</style>
        <link rel="icon" href="./img/icon.png"/>
        <?php 
        require_once 'header.php';
        ?>   


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
        
        <script data-ad-client="ca-pub-3114985042987806" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        
    </head>
    
    <body class = "main_frame grid_container">

    <?php require_once 'list.php'; ?>
    
        
        
    <div class="content_area">    
        <?php require_once 'search-results.php'; ?>
        <div class='desktop-hide'><span id='cats'></span></div>
        <?php

if(isset($_GET['id'])){
    $entry_id= get_get($conn, 'id');
    $title= get_title_from_entry($conn, $entry_id);
    $title_id= get_title_id($conn, $title); 
    
    $query= "SELECT entry,stars,time,username,id FROM entries WHERE id='$entry_id'";
    $result= $conn->query($query);
    
    if(!$result){
        die($conn->connect_error);
    }
     
   $row= $result->fetch_array(MYSQLI_NUM);

        $result->data_seek(0);
        $row= $result->fetch_array(MYSQLI_NUM);
        $entry= $row[0];
        $stars= $row[1];
        $time= $row[2];
        $time= new DateTime($time);       
        $time = $time->format('d-m-Y H:i:s');
        $username= $row[3];
        $entry_id= $row[4];
        
        
        
        require_once 'main-ad.php';
        require_once 'right-ad.php';
        
        echo "<span itemscope itemtype='http://www.schema.org/Review'> "
        . "<a id='title' href='title.php?title_id=$title_id'>"
                . "<span itemprop='itemReviewed'>".$title."</span></a>";
        
        $average_star= find_average_star($conn,$title_id);
        
        for($i=0;$i<$average_star && STARVOTE;$i++){
            echo "<img alt='star' width='20px' src='img/star.png'>";
        }
        
        if($average_star != 0 && STARVOTE)
        	printf("<b>(%.2f)</b><br><br>",$average_star);

        
        echo "<span itemscope itemtype='http://www.schema.org/Rating'><span itemprop='ratingValue' hidden>$stars</span>";
        echo "<span itemprop='bestRating' hidden>5</span></span>";
        
        for($j=0;$j<$stars && STARVOTE;$j++){
            echo "<img alt='star' title='$stars star' width='20px' src='img/star.png'>";
        }
        
        if($stars !=0 && STARVOTE)
        	echo "<span itemprop='reviewRating' value='$stars'></span>";
        echo nl2br("<p id='entry_style' itemprop='reviewBody'>" . add_bkz_url($entry) . "</p>");
        display_edit_delete_area($conn, $entry_id, $username);
        
        
        
        echo "<div><div align='right'><span style='color:green; font-size:9pt' itemprop='datePublished'>$time</span> - <a id='author' href='profile.php?u=$username'>$username</a> - <div class='dropdown'><span class='dropbtn' title='Reportit' href=''> ...</span><div  class='dropdown-content'><a href='reportus.php?EntryNo=$entry_id'>report</a></div></div></div></div><hr>";
        
}
     include 'fluid-ad.php';
        

?>

        
        
        <?php require_once 'footer.php'; ?> 
        
    </div>
 
        
    </body>
    
</html>



