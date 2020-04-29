<!DOCTYPE html>
    <?php session_start(); ?>

    <html>
        <head>
            <?php require_once 'config.php'; ?>
            <title><?php echo "Messages -" . SITENAME; ?></title>
            <style>@import'style.css'</style>
            <link rel="icon" href="./img/icon.png"/>
            <?php 
              if(!isset($_SESSION['username'])){
                header("Location: index.php");
                die('You dont have access!<br>');
              }

            ?>
            <?php require_once 'functions.php'; ?>
            <?php require_once 'header.php'; ?>
          
            
            
	    <meta name="title" content="Yorumun">
      	    <meta name="description" content="Share your feedbacks, comments and opinions on websites, socialmedia, blogs, technology, businesses, organizations, games and more.">
            <meta name="keywords" content="how is it, how, website, internet, hosting, place, business, blog, comment, user comments, user feedback">
            <meta name="robots" content="index, follow">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
            <meta name="language" content="English">
            <meta name="author" content="comments, comment repository">
                      
            
            
          
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
        
        <body class="main_frame grid_container">
            <?php
              if(isset($_POST['message']) && isset($_GET['u']) && isset($_SESSION['username'])){
                require_once 'MessageClass.php';
                require_once 'conn.php';
                $message = new Message;
                $current_user_id = get_user_id($conn, $_SESSION['username']);

                $the_message = add_bkz_url(get_post($conn, 'message'));
                $receiver_user_id = get_get($conn, 'u');

                if(isThereUserById($conn, $receiver_user_id) && $receiver_user_id != $current_user_id){
                  $message->send($current_user_id, $receiver_user_id, $the_message);
                }
              }

            ?>
            <?php require_once 'list.php'; ?>
                
            
            <div class="content_area" style = "padding-top: 30px;">    

                <?php require_once 'search-results.php'; ?>
                <div class='desktop-hide'><span id='cats'></span></div>
                <?php include_once 'main-ad.php'; ?>
                <?php include_once 'right-ad.php'; ?>
                
                <div class="senders">
                <?php

                  if(isset($_SESSION['username'])){
                    require_once 'MessageClass.php';
                    require_once 'conn.php';
                    $message = new Message;

                    $current_user_id = get_user_id($conn, $_SESSION['username']);

                    $user_ids = $message->get_conversation_user_ids($current_user_id);

                  }
                ?>

                  <?php 
                    if(isset($_GET['delete'])){
                      $delete_conversation_user_id = get_get($conn, 'delete');

                      $message->delete_conversation($current_user_id, $delete_conversation_user_id);

                    }

                  ?>

                  <?php if(!isset($_GET['u'])): ?>
                  <?php $flag = false; ?>

                    <?php foreach($user_ids as $object): ?>
                    
                        
                          <?php
                            if($object->sender_id != $current_user_id){
                              if($message->isThereUnDeletedMessageInConversation($current_user_id, $object->sender_id) ){
                                $receiver_id = $object->sender_id;
                                $last_message = $message->get_last_message_in_conversation($current_user_id, $object->sender_id);
                                echo "<div><div><a href ='message.php?u=$object->sender_id'>" . get_username_by_id($conn, $object->sender_id) . "</a><br>";
                                echo substr($last_message->message, 0, 10) . '...';
                                echo "<p style='color:green; font-size:9pt' align = 'right'> $last_message->time</p></div>";
                                $flag = true;
                              }

                              
                            }

                            else{
                              if($message->isThereUnDeletedMessageInConversation($current_user_id, $object->receiver_id) ) {
                                $receiver_id = $object->receiver_id;
                                $last_message = $message->get_last_message_in_conversation($current_user_id, $object->receiver_id);
                                echo "<div><div><a href ='message.php?u=$object->receiver_id'>" . get_username_by_id($conn, $object->receiver_id) . "</a><br>";
                                echo substr($last_message->message, 0, 10) . '...';
                                echo "<p style='color:green; font-size:9pt' align = 'right'> $last_message->time</p></div>";
                                $flag = true;
                              }

                            }
                            if(isset($receiver_id)){
                              echo '<div align = "right">';
                              echo '<a href = "message.php?delete=' . $receiver_id . '" >Delete</a>';
                              echo '</div></div>';
                              unset($receiver_id);
                            }
                            if(isset($sender_id)){
                              echo '<div align = "right">';
                              echo '<a href = "message.php?delete=' . $sender_id . '" >Delete</a>';
                              echo '</div></div>';
                              unset($sender_id);
                            }

                          ?>

                    <?php endforeach; ?>
                    <?php echo !$flag ? 'you have no messages.<br>' : ''; ?>
                  </div>

                <?php else: ?>

                  <?php 
                    $user_id = get_get($conn, 'u');
                    $user_name = get_username_by_id($conn, $user_id);

                    echo "<h3><a href = 'profile.php?u=$user_name'>$user_name</a></h3>";

                    $conversation = $message->get_conversation($current_user_id, $user_id);
                  ?>


                  <?php foreach($conversation as $the_message): ?>
                    <div>
                        <?php
                            $user_name_w = get_username_by_id($conn, $the_message->sender_id);
                            
                            if($the_message->receiver_id == $current_user_id){
                            	
                              $message->mark_message_as_read($the_message->mid);
                              echo nl2br("<p  >$the_message->message<br>
                                          <span style='color:green; font-size:9pt'>$the_message->time - $user_name_w</span>
                                    </p>");
                            }
                            else{
                       
            
                            
                              echo nl2br("<p align= 'right'>$the_message->message<br>
                                                      <span style='color:green; font-size:9pt'>$the_message->time- $user_name_w</span>
                                    </p>");
                            }
                        ?>
                    </div>

                  <?php endforeach; ?>
                <?php endif; ?>

                <br><br>
                
                <?php if(isset($_GET['u'])): ?>  
                  <form action="message.php?u=<?php echo $user_id;?>" method="post">
                    <textarea name="message" rows="10" style="height:175px; font-size:12pt" ></textarea><br><br>
                    <input id="button" type="submit" value="Send">
                    
                  </form>
                <?php include 'fluid-ad.php'; endif; ?>
                
                <?php require_once 'footer.php'; ?>
            </div>
           
            
        </body>
        
    </html>



