<!DOCTYPE html>
<?php ob_start(); ?>
<html>
    <head>
        
        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script> 
        <script> (adsbygoogle = window.adsbygoogle || []).push({ google_ad_client: "ca-pub-3114985042987806", enable_page_level_ads: true }); </script>
        
        <?php
      	  
        require_once 'class.phpmailer.php';
        require_once 'config.php';
        require_once 'conn.php';
        require_once 'functions.php';
      	
      	session_start();	
      	  
        if(isset($_GET['title_id'])){
            $title_id= get_get($conn, 'title_id');
            $title= get_title($conn, $title_id);
            echo "<title>$title - " . SITENAME . "</title>";
            
        }
        else if(isset($_GET['id'])){
            $title_id= get_get($conn,'id');
            $title= get_title($conn, $title_id);
            echo "<title>$title - " . SITENAME . "</title>";
        }
        
	        
        ?>
 
        <style>@import'style.css'</style>
        <link rel="icon" href="./img/icon.png"/>
        <?php 
        require_once 'header.php';
        require_once 'list.php';
        //require_once 'footer.php';
        ?>    
        
         <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>
    
    
        
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
        
        <script data-ad-client="ca-pub-3114985042987806" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        
    </head>
    
    <body class = "main_frame grid_container">
    
    <?php require_once 'list.php'; ?>
    
    <div class="content_area">
<?php require_once 'search-results.php'; ?>
        <div class='desktop-hide'><span id='cats'></span></div>

<?php
        $num_pages = 1;
    
        if(isset($_SESSION['username'])){
            $username= $_SESSION['username'];
            $email= $_SESSION['email'];
             
            if(isset($_POST['mailactivate'])){
		$success = send_activate_mail($conn, $username,$email);       	
                if($success){
                    echo "Email activation link <font color='green'>is sent successfully!</font><br>"
                    . "Please check your mail box!<br>"
                    . "If you cant see e-mail, dont forget looking your spam folder!<br>";
                }
                else{
                    echo "Email activation link <font color='red'>is not sent successfully!</font><br>"
                    . "Please try again later!";
                }
                
                
            }

}



if(isset($_GET['title_id']) || isset($_GET['id'])){
    
    if(isset($_GET['fromlist'])){
        $fromlist= "yes";
    }
    else{
        $fromlist = "no";
    }
    //$title_id= get_get($conn,'title_id');
    $query= "SELECT title,category FROM titles WHERE title_id='$title_id'";
    $result= $conn->query($query);
    if(!$result){
        die($conn->error);
    }
    
    $result->data_seek(0);
        

    $row= $result->fetch_array(MYSQLI_NUM);
    $title= $row[0];
    $category= $row[1];
    
    
    
        if(isset($_SESSION['username'])){
            $current_username= $_SESSION['username'];
                      if(isset($_POST['entry'])){
            if(!isset($_POST['stars']))
            	$stars = 0;
           	else{
            	$stars= get_post($conn, 'stars');
            
		        if($stars > 5 || $stars < 0)	
		        	die();
            }
            
            
            $entry= get_post($conn,'entry');
            
            add_entry($conn,$title_id,$entry,$stars,$category,$current_username);
            
            
            $num_pages= ceil(find_totalentry_of_title($conn, $title_id) / 10) ;

            
           header("Location: title.php?id=$title_id&page=$num_pages");
            
            
            $query= "SELECT totalstar,totalentry FROM titles WHERE title_id='$title_id'";
            $result= $conn->query($query);
            
            if(!$result){
                die($conn->error);
            }
            
            $result->data_seek(0);
            $row= $result->fetch_array(MYSQLI_NUM);
            
            
            $totalstar= $row[0];
            $totalentry= $row[1];
            
            $lastupdate = new DateTime();
            $lastupdate=  $lastupdate->format('Y-m-d H:i:s');
            
            
            $totalstar+= $stars;
            $totalentry++;
            
            if($stars != 0)
            {
		        $query= "UPDATE titles SET lastupdate='$lastupdate' , totalstar='$totalstar', totalentry='$totalentry' WHERE title_id='$title_id' ";
		        $result= $conn->query($query);
		        if(!$result){
		            die($conn->error);
		        }
            
            }
            
        }
            
            
        }

    if(isset($_GET['page'])){
        $page= get_get($conn,'page');
        $page_last= $page*10;
        $page_first= $page_last-10;
    } 
    else{
        $page=1;
        $page_last= $page*10;
        $page_first= $page_last-10;
    }
    
    
        
    if(isset($_GET['fromlist']) && $fromlist == "yes"){
        
        $total_entry_of_title = find_totalentry_of_title($conn, $title_id);
        $target_page = ceil($total_entry_of_title / 10);
        header("Location: title.php?id=$title_id&page=$target_page");
        
    }
    else{

        $query= "SELECT entry,stars,time,username,id FROM entries WHERE title_id='$title_id'"
        . " ORDER BY time ASC LIMIT $page_first,$page_last";       
    }
    
    $result= $conn->query($query);
    
    if(!$result){
        die($conn->error);
    }
    
    $num_rows= $result->num_rows;
        
        require_once 'main-ad.php';
        require_once 'right-ad.php';
    
        echo "<span itemscope itemtype='http://www.schema.org/AggregateRating'><a id='title' href='title.php?id=$title_id'><span itemprop='itemReviewed'>".$title."</span></a>";
        
        
        $average_star= find_average_star($conn,$title_id);
        $reviewCount= find_reviewCount($conn,$title_id);
        
        echo "<span itemprop='reviewCount' hidden>$reviewCount</span>";
        echo "<span itemprop='bestRating' hidden>5</span>";
        for($i=0;$i<$average_star && STARVOTE;$i++){
            echo "<img alt='star' width='20px' src='img/star.png'>";
        }
        
        if($average_star != 0 && STARVOTE)
        	printf("<b>(<span itemprop='ratingValue'>%.2f</span>)</b></span>",$average_star);
        
        
        page_nav($conn,$fromlist,$title_id,$page);
        
        if($num_rows==0){
        	        echo "<font color='red'>there is no new entry today, click titles to see former ones.</font>";
        }       
                
    for($i=0;$i<$num_rows;$i++){
        
        $result->data_seek($i);
        $row= $result->fetch_array(MYSQLI_NUM);
        $entry= $row[0];
        $stars= $row[1];
        $time = isset($row[2]) ? $row[2] : false;   
        $time= new DateTime($time);       
        $time = $time->format('d-m-Y H:i:s');
        $username = isset($row[3]) ? $row[3] : false;   
        $entry_id = isset($row[4]) ? $row[4] : false;   
        for($j=0;$j<$stars && STARVOTE;$j++){
            echo "<img alt='star' title='$stars star' width='15px' src='img/star.png'>";
        }
        echo "<span itemscope itemtype='http://www.schema.org/Review'> ";
        echo "<span itemprop='itemReviewed' hidden>$title</span>";
        echo nl2br("<p id='entry_style' itemprop='reviewBody'>" . add_bkz_url($entry) . "</p>");
        display_edit_delete_area($conn,$entry_id,$username); 
        echo "<div><div align='right'><span style='color:green; font-size:9pt' itemprop='datePublished'>$time</span> - <a id='author' href='profile.php?u=$username'>$username</a> - <div class='dropdown'><span class='dropbtn' title='Reportit' href=''> ...</span><div  class='dropdown-content'><a href='reportus.php?EntryNo=$entry_id'>report</a></div></div></div></div><hr>";
    
    }
    

        page_nav($conn,$fromlist,$title_id,$page);
      


    if(isset($_SESSION['username'])){
        
        $email= $_SESSION['email'];
        $username= $_SESSION['username'];
        $email_check = email_check($conn,$email);
        $isBanned = isBanned($conn, $username);
        if($email_check && !$isBanned){
        
        	if(!STARVOTE)
        		$starvote = "";
        	else
        		$starvote = 
        		"
            How many stars?:
	    <select required='required' name='stars' size='1'>
	    <option selected='selected' value='0'>Score</option>  
            <option value='1'>1 (Very Bad)</option>
            <option value='2'>2 (Bad)</option>
            <option value='3'>3 (Avarage)</option>
            <option value='4'>4 (Good)</option>
            <option value='5'>5 (Excellent)</option>  
            </select>
	    <br>";
		        
        
            echo <<<_END
            <form method='post' action='title.php?id=$title_id'>
            $starvote
                        
            <textarea name="entry" rows='10' spellcheck='false' required='required'></textarea>
            <br>
            <input id="button" type="submit" value="Submit">
            </form>    
_END;
            include 'fluid-ad.php';   
        }
        
        else if($isBanned){
            echo "Your account is blocked!<br>";
        }
        
        else{
            
            echo "<font color='red'>You cant enter comment since you didnt verify your e-mail!</font><br>"
            . "Please verify your e-mail account.<br>";
            
            echo <<<_END
            <form action='title.php' method='post'>
            <input type='hidden' name='mailactivate' value='yes'>
            <input type='submit' value='Send activation e-mail'>
            </form>
_END;
        }
    }
    else{
        echo "For entering a comment, "
        . "<a href='login.php'><font color=green>you have to login!</font><br></a>";
        include 'fluid-ad.php';
    }
}
        
    ?>
        <?php require_once 'footer.php'; ?>
        
        
    </div>
    
    </body>
    
</html>


