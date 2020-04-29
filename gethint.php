 <?php
 ob_start();
 session_start();
 require_once 'conn.php';
 require_once 'functions.php';


$q = get_request($conn,'q');


    if(!empty($q))
    {
          if($q[0]=='@'){
            $isUserSearch = TRUE;
            $q= substr($q,1);
            $query= "SELECT username FROM users "
                    . "WHERE username LIKE '$q%' LIMIT 10";
            $result= $conn->query($query);
        
            if(!$result){
                die($result->error);
            }
            $num_rows= $result->num_rows;
    
        }
        else
        {
            $isUserSearch = FALSE;
            $query= "SELECT title,title_id FROM titles "
                    . "WHERE MATCH(title) AGAINST('$q') OR title LIKE '%$q%' LIMIT 10";
            $result= $conn->query($query);
        
            if(!$result){
                die($result->error);
            }
            $num_rows= $result->num_rows;
            
        }
    }
  
        
    echo "<h2>Search Results:</h2> ";
        
    if($num_rows==0){
    
    	$newtitle_text= "<a href='newtitle.php?title=$q'> <But you can open it if you want!></a>";
    	echo "<p>This kind of entry does not exist! Press enter or click <a style='color:#5882FA' href='newtitle.php?title=$q'> HERE </a> to open it!</p>$newtitle_text";
    }
    
  
	if($isUserSearch){
		for($i=0;$i<$num_rows;$i++){
		    $result->data_seek($i);
		    $row= $result->fetch_array(MYSQLI_NUM);
		    $username_in_query= $row[0];
		    echo '<b>'.($i+1).'</b>: ';   
		    echo "<a href='profile.php?u=$username_in_query'>@$username_in_query</a><br>";
		}
		
	}
	else{
		
		for($i=0;$i<$num_rows;$i++){
		
		    $result->data_seek($i);
		    $row= $result->fetch_array(MYSQLI_NUM);
		    $string= $row[0];
		    $id= $row[1];
		
		    echo "<p><a id='searchresult' href='title.php?title_id=$id'>"
		            . "<b>".($i+1).'</b>'.": $string</a></p>"; 
		}
    }
    
?> 
