<?php
    
    session_start();

    
    require_once 'conn.php';
    require_once 'functions.php';
    
    if(isset($_SESSION['username']) && isset($_GET['entry_id']) ){  
        $entry_id= get_get($conn, 'entry_id');
        $owner = find_owner_of_entry($conn, $entry_id);
        $current_username= $_SESSION['username'];
        $title_id= get_title_id_from_entry_id($conn, $entry_id);
        $status= find_status_of_member($conn, $current_username);
        
        if( ($current_username == $owner ) || ($status=='admin') ){
            $totalstar = find_totalstar_of_title($conn, $title_id);
            $totalentry= find_totalentry_of_title($conn, $title_id);
            $star_number_of_entry= find_star_number_of_entry($conn, $entry_id);
            
            delete_entry($conn, $entry_id);
            
            subtract_stars($conn, $title_id, $star_number_of_entry,$totalentry,$totalstar);
            

            
            
            $num_pages = ceil($totalentry / 10);
            echo "<meta http-equiv=\"refresh\" content=\"0;URL=title.php?id=$title_id&page=$num_pages\">";
           

            if(find_totalentry_of_title($conn, $title_id) == 0){
                delete_title($conn, $title_id);
                
                echo "<meta http-equiv=\"refresh\" content=\"0;URL=index.php\">";
                
            }
            
            /*echo "<font color='green'>Comment is deleted.</font>";*//*Fat1:no need for comment*/

         }
         else{
             echo "<font color='red'>You are not authorized to delete!</font><br>";
         }
        
    }
    else{
        echo "You must log-in!<br>";
    }
    


?>