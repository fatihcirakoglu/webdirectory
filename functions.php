<?php debug_backtrace() || die ("<h2>Access Denied!</h2> This file is protected and not available to public."); ?>
<?php

        require_once 'config.php';
        require_once 'conn.php';
        require_once 'class.phpmailer.php';
        
        function isThereUser($conn,$username_in_query){
            $query= "SELECT username FROM users WHERE username='$username_in_query'";
            $result = $conn->query($query);
            if(!$result){
                die($conn->error);
            }
            $num_rows= $result->num_rows;
            if($num_rows==0){
                return FALSE;
            }
            else{
                return TRUE;
            }
            
        }

        function get_title_id_from_entry_id($conn,$entry_id){
            $query= "SELECT title_id FROM entries WHERE id='$entry_id'";
            $result= $conn->query($query);
            if(!$result){
                die($conn->error);
            }
            $result->data_seek(0);
            $row= $result->fetch_array(MYSQLI_NUM);
            
            $title_id= $row[0];
            
            return $title_id;
            
        }
        
        function isThereUserById($conn,$user_id){
            $query= "SELECT username FROM users WHERE id='$user_id'";
            $result = $conn->query($query);
            if(!$result){
                die($conn->error);
            }
            $num_rows= $result->num_rows;
            if($num_rows==0){
                return FALSE;
            }
            else{
                return TRUE;
            }
            
        }
        
        function goUserPage($username){
            
            header("Location: ".SITEADDR."/profile.php?u=$username");
        }
        function last_user($conn){
            $query= "SELECT username FROM users ORDER BY id DESC LIMIT 1";
            
            $result= $conn->query($query);
            
            $row= $result->fetch_array(MYSQLI_NUM)[0];
            
            return $row;
            
        }

        function get_request($conn,$string){
            $string= $_REQUEST[$string];
            if(get_magic_quotes_gpc()){
                $string= stripslashes($string);
            }
            $string= $conn->real_escape_string($string);
            $string= strip_tags($string);
            return $string;
        }
        
        function display_edit_area($conn,$entry_id){
            
            $query= "SELECT stars,entry FROM entries WHERE id='$entry_id'";
            $result = $conn->query($query);
            
            if(!$result){
                die($conn->error);
            }
            
            
            $row= $result->fetch_array(MYSQLI_ASSOC);
            
            $stars= $row['stars'];
            $entry= $row['entry'];
            
            
            echo <<<_END
<form method='post' action='edit.php'>
<pre>
_END;



if(STARVOTE){
    echo "How many stars?:<select name='stars'>";
}

            for($i=0;$i<=5 && STARVOTE;$i++){
                if($i == 0 && $stars == 0){
                    echo "<option selected value='$i'>Score</option>";
                }
                else if($i == 0){
                    echo "<option value='$i'>Score</option>";
                }
                else if($i == $stars){
                    echo "<option selected value='$i'>$i</option>";
                }
                else{
                    echo "<option value='$i'>$i</option>";
                }
            }
            echo <<<_END
            </select>
            
<textarea name="entry" rows='10' spellcheck='false' required="required">$entry</textarea>
<input type='hidden' name='entry_id' value='$entry_id'>
<input id="button" type='submit' value='Update'>
</pre>
</form>
            
_END;
            
            
            
        }
        

        
    function find_star_number_of_entry($conn,$entry_id){
        $query= "SELECT stars FROM entries WHERE id='$entry_id'";
        
        $result= $conn->query($query);
        
        if(!$result){
            die($conn->error);
        }
        
        $row= $result->fetch_array(MYSQLI_NUM);
        
        $stars= $row[0];
        
        return $stars;
        
    }


        function update_average_stars($conn,$ex_stars,$stars,$title_id){
            if($stars == 0){
                return true;
            }

            $query= "SELECT totalstar FROM titles WHERE title_id='$title_id'";
            $result= $conn->query($query);
            
            if(!$result){
                die($conn->error);
            }
            
            $row= $result->fetch_array(MYSQLI_ASSOC);
            
            $totalstar= $row['totalstar'];
            
            $totalstar-=$ex_stars;
            $totalstar+=$stars;
            
            $query="UPDATE titles SET totalstar='$totalstar' WHERE title_id='$title_id'";
            $result= $conn->query($query);
            
            if(!$result){
                echo $conn->error.'<br>';
                return FALSE;
            }
            else{
                return TRUE;
            }
            
        }
        

        
        function get_get($conn,$string){
        
            return filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING)[$string];
        }
        
        
            function get_post($conn,$string){
		        return filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING)[$string];
		   	}
        

        
        
        function activate_email($conn,$email){
            
            $query= "UPDATE users SET emailcheck='yes' WHERE email='$email'";
            $result= $conn->query($query);
            
            if(!$result){
                die($conn->error);
            }
                       
            return TRUE;
        }


        function display_edit_delete_area($conn,$entry_id,$username){            

            
            if(isset($_SESSION['username'])){
                $current_username= $_SESSION['username'];
                $status = find_status_of_member($conn,$current_username);
                
                if(($_SESSION['username']==$username) || ($status=='admin')){

                    echo "<div class='edit_delete_area'>"
                    . "<div align='right' id='delete_area_$entry_id'>"
                           . "<a style='margin-right: 20px' id='element' href='edit.php?id=$entry_id'>"
                            . "<img title='edit comment' height='25px'  src='img/edit.png'></a>"                            
                            . "<a href=\"javascript:if(window.confirm('Are you sure to delete?')) deleteFunc($entry_id);\">"
                        . "<img title='delete comment' width='20px'  src='img/delete.png'></a>"
                           . "</div>"
                            . "</div>";
                }
            
            }
            
        }
        

        
        function find_num_pages($conn,$title_id,$fromlist){
            if($fromlist=='yes'){
                $query= "SELECT COUNT(*) FROM entries WHERE title_id='$title_id' AND DATE(`time`) = CURDATE()"; 
            }
            else{
                $query= "SELECT COUNT(*) FROM entries WHERE title_id='$title_id'";               
            }
            
            $result= $conn->query($query);
            if(!$result){
                die($conn->error);
            }
            $row= $result->fetch_array(MYSQLI_NUM);
            
            $number= $row[0];
            
            return $number;
            
        }
        
        
        
        function page_nav($conn,$fromlist,$title_id,$page){
        $num_pages= find_num_pages($conn,$title_id,$fromlist);
        $num_pages/=10;
        $num_pages= ceil($num_pages);
            
            echo <<< _END
    <div align='right'>
        <form method="get" action="title.php">
            <input type='hidden' name='id' value='$title_id'>
            <select name='page' size='1' onchange='if(this.value != 0) { this.form.submit(); }'>
_END;
    
    for($i=1;$i<=$num_pages;$i++){
        if($i==$page){
            echo "<option selected='selected' value='$i'>$i</option>";
        }
        else{
            echo "<option value='$i'>$i</option>";
        }
    }
    
    echo <<<_END
            </select>
        </form>
    </div>
_END;
    
    
            
        }

        
        
        
        
        function send_activate_mail($conn, $username,$email){
            
            $activate_code = generate_email_activate_code($conn, $email);
                $text= "
Hello ".$username.",

The user account ".$username." has been created on ".SITEADDR.". 
To confirm your e-mail and to activate your profile, please follow the following URL:

".SITEADDR."/verify.php?email=$email&code=$activate_code

if you didn't create an account on this website, please ignore this e-mail.

Regards.
Directory Staff

".SITEADDR."
                ";
				$mail = new PHPMailer();

				$mail->IsSMTP();                                // send via SMTP
                $mail->SMTPSecure = 'tls';
				$mail->Host     = MAILHOST; // SMTP servers
                $mail->Port= MAILPORT;
				$mail->SMTPAuth = true;     // turn on SMTP authentication
				$mail->Username = MAILADDR;  // SMTP username
				$mail->Password = MAILPASS; // SMTP password
				$mail->From     = MAILADDR; // smtp kullanÄ±cÄ± adÄ±nÄ±z ile aynÄ± olmalÄ±
				$mail->Fromname = "Admin - ".SITEADDR."";
				$mail->AddAddress("$email","$username");
				$mail->Subject= "Yorumun.com Profile Activation";
				$mail->Body= $text;

				if(!$mail->Send())
				{
					
					echo "Mailer Error: " . $mail->ErrorInfo;
					return FALSE;
				}
				else{

					return TRUE;

					}
        }
        
          
            
        function send_contactus_mail($conn,$email,$subject,$message){            
           
		$email = $_POST['email'];
		$subject = $_POST['subject'];
		$message = $_POST['message'];
            
              
		$mail = new PHPMailer();

		$mail->IsSMTP();                                // send via SMTP
		$mail->SMTPSecure = 'tls';
		$mail->Host     = MAILHOST; // SMTP servers
		$mail->Port= MAILPORT;
		$mail->SMTPAuth = true;     // turn on SMTP authentication
		$mail->Username = MAILADDR;  // SMTP username
		$mail->Password = MAILPASS; // SMTP password
		$mail->From     = MAILADDR; // smtp kullanÄ±cÄ± adÄ±nÄ±z ile aynÄ± olmalÄ±
		$mail->Fromname = SITENAME;
		$mail->isHTML(true);
		$mail->Subject= $subject;
		$mail->Body = "
	        <html>
	            <body>
	                <table style='width:600px;'>
	                    <tbody>              
	                        <tr>
	                            <td style='width:150px'><strong>Email: </strong></td>
	                            <td style='width:400px'>$email</td>
	                        </tr>
	                        <tr>
	                            <td style='width:150px'><strong>Subject: </strong></td>
	                            <td style='width:400px'>$subject</td>
	                        </tr>
	                        <tr>
	                            <td style='width:150px'><strong>Message: </strong></td>
	                            <td style='width:400px'>$message</td>
	                        </tr>
	                    </tbody>
	                </table>
	            </body>
	        </html>
	        ";
	        // HTML Message Ends here
			        
	        $mail->AddAddress('support@yorumun.com');
				
		if(!$mail->Send())
		{
					
			echo "Mailer Error: " . $mail->ErrorInfo;
			return FALSE;
		}
		else{

			return TRUE;

			}
                    
        }

    
    
    function send_reportus_info_mail($conn,$email,$subject){  
        $text= "
Hello,
<br><br>
We have received your feedback regarding the following subject: ".$subject." on ".SITEADDR.". <br>
We will review your feedbacks and return back to you.<br>
<br>
if you didn't create this request on this website, please ignore this e-mail.<br>
<br>
Regards.<br>
Directory Staff
<br>
".SITEADDR."
                ";       
           
        $mail = new PHPMailer();

		$mail->IsSMTP();                                // send via SMTP
		$mail->SMTPSecure = 'tls';
		$mail->Host     = MAILHOST; // SMTP servers
		$mail->Port= MAILPORT;
		$mail->SMTPAuth = true;     // turn on SMTP authentication
		$mail->Username = MAILADDR;  // SMTP username
		$mail->Password = MAILPASS; // SMTP password
		$mail->From     = MAILADDR; // smtp kullanÄ±cÄ± adÄ±nÄ±z ile aynÄ± olmalÄ±
		$mail->Fromname = SITENAME;
		$mail->isHTML(true);
		$mail->Subject= "Thanks for your feedbacks - Yorumun.com";
		$mail->Body = $text;
           
        $mail->AddAddress("{$email}");
				
		if(!$mail->Send())
		{
					
			echo "Mailer Error: " . $mail->ErrorInfo;
			return FALSE;
		}
		else{
		    
            return TRUE;
                    
        }
}

        
        function send_reportus_mail($conn,$email,$subject,$message){            
           
		$email = $_POST['email'];
		$subject = $_POST['subject'];
		$message = $_POST['message'];
              
		$mail = new PHPMailer();

		$mail->IsSMTP();                                // send via SMTP
		$mail->SMTPSecure = 'tls';
		$mail->Host     = MAILHOST; // SMTP servers
		$mail->Port= MAILPORT;
		$mail->SMTPAuth = true;     // turn on SMTP authentication
		$mail->Username = MAILADDR;  // SMTP username
		$mail->Password = MAILPASS; // SMTP password
		$mail->From     = MAILADDR; // smtp kullanÄ±cÄ± adÄ±nÄ±z ile aynÄ± olmalÄ±
		$mail->Fromname = SITENAME;
		$mail->isHTML(true);
		$mail->Subject= $subject;
		$mail->Body = "
	        <html>
	            <body>
	                <table style='width:600px;'>
	                    <tbody>              
	                        <tr>
	                            <td style='width:150px'><strong>Email: </strong></td>
	                            <td style='width:400px'>$email</td>
	                        </tr>
	                        <tr>
	                            <td style='width:150px'><strong>Subject: </strong></td>
	                            <td style='width:400px'>$subject</td>
	                        </tr>
	                        <tr>
	                            <td style='width:150px'><strong>Message: </strong></td>
	                            <td style='width:400px'>$message</td>
	                        </tr>
	                    </tbody>
	                </table>
	            </body>
	        </html>
	        ";
	        // HTML Message Ends here
			        
	        $mail->AddAddress('support@yorumun.com');
				
		if(!$mail->Send())
		{
					
			echo "Mailer Error: " . $mail->ErrorInfo;
			return FALSE;
		}
		else{
		    send_reportus_info_mail($conn,$email,$subject);
            return TRUE;
                    
        }
}
           
        
        function add_entry($conn,$title_id,$entry,$stars,$category,$current_username){
        	$entry = trim($entry);
        	
        	if(empty($entry)){
        		echo 'entry boş olamaz.<br>' ;
        		return false;
        	}
        	
            if(!isset($_SESSION['last_update']) || time() >= $_SESSION['last_update']+3){

                $query= "INSERT INTO entries(title_id,entry,stars,category,username)"
                    . "VALUES('$title_id','$entry','$stars','$category','$current_username')";
                $result= $conn->query($query);
            
                if(!$result){
                    die($conn->error);
                }
                
                //updateSitemap($title_id);
                $_SESSION['last_update'] = time();

                $query= "UPDATE titles SET lastupdate = CURRENT_TIMESTAMP() WHERE title_id = '$title_id'";
                $result= $conn->query($query);
            
                if(!$result){
                    die($conn->error);
                }
                
            }
           
            
            return true;
            
        }
        
        
        function find_average_star($conn,$title_id){
            $query= "SELECT totalstar,totalentry FROM titles WHERE title_id='$title_id'";
            $result= $conn->query($query);
            
            if(!$result){
                die($conn->error);
            }
            $row= $result->fetch_array(MYSQLI_NUM);
            
            $totalstar= $row[0];
            $totalentry= $row[1];
            
            if($totalentry == 0){
                return 0;
            }

            $average_star= $totalstar/$totalentry;
            
            return $average_star;
            
        }
        

            
            
            function find_reviewCount($conn,$title_id){
                
                $query= "SELECT COUNT(*) FROM entries WHERE title_id='$title_id'";
                $result= $conn->query($query);
                if(!$result){
                    die($conn->error);
                }
                
                $row= $result->fetch_array(MYSQLI_NUM);
                $number= $row[0];
                
                return $number;
            }



        

        

        
        
        function add_user($conn,$username,$password,$email){
            $password= encryptPass($password);
            
            $query= "INSERT INTO users(username,password,email,emailcheck) "
                    . "VALUES('$username','$password','$email','no')";
            
            $result= $conn->query($query);
            if(!$result){
                echo $conn->error;
                return FALSE;
            }
            else{
                return TRUE;
            }
        }
        

 
        
        
        
        function is_valid_username($username){

			if(!preg_match('/^[a-zA-Z0-9]{'. MIN_USERNAME_CHAR_NUM . ',}$/', $username) || empty($username)) {
    			return false;
			}
			return true;
            
        }

        function number_of_titles($conn,$username){
            $query="SELECT COUNT(*) FROM titles WHERE username='$username'";
            
            $result= $conn->query($query);
            if(!$result){
                die($conn->error);
                
            }
            
            $row= $result->fetch_array(MYSQLI_NUM);
            $number= $row[0];
            
            return $number;
            
            
        }

        function number_of_entries($conn,$username){
            $query="SELECT COUNT(*) FROM entries WHERE username='$username'";
            
            $result= $conn->query($query);
            if(!$result){
                die($conn->error);
                
            }
            
            $row= $result->fetch_array(MYSQLI_NUM);
            $number= $row[0];
            
            return $number;
            
        }
        

        function ban($conn, $willBannedUser){
            $query= "UPDATE users SET banned='yes' WHERE username='$willBannedUser'";
            $result= $conn->query($query);
            
            if(!$result){
                die($conn->error);
            }
        }
        
        function unBan($conn, $willUnBannedUser){
            $query= "UPDATE users SET banned=NULL WHERE username='$willUnBannedUser'";
            $result= $conn->query($query);
            
            if(!$result){
                die($conn->error);
            }
        }

        function isBanned($conn,$username){
            $query= "SELECT banned FROM users WHERE username='$username'";
            $result= $conn->query($query);
            $row = $result->fetch_array(MYSQLI_NUM);
            
            $isBanned= $row[0];
            if($isBanned    == 'yes'){
                return TRUE;
            }
            else{
                return FALSE;
            }
            
        }
        
        function display_last_entries_of_user($conn,$username,$number){
            $query= "SELECT id,title_id FROM entries WHERE username='$username' ORDER BY time DESC LIMIT $number";
            $result= $conn->query($query);
            
            if(!$result){
                die($conn->error);
            }
     
            $num_rows= $result->num_rows;
            
            echo "<h2>Latest comments</h2>";
            
            
            if(!$num_rows){
                echo "<p>There is no comment!</p>";
            }
            
            else{
                for($i=0;$i<$num_rows;$i++){
                    $result->data_seek($i);
                    $row= $result->fetch_array(MYSQLI_NUM);
                    $entry_id= $row[0];
   
                    $title_id= $row[1];
                    $title= get_title($conn, $title_id);
                    echo "<a id='general_font' rel='nofollow' href='entry.php?id=$entry_id'>$title</a><br>";
                }
            }
            
        }
        
        
            function get_title_from_entry($conn,$entry_id){
            $query= "SELECT title_id FROM entries WHERE id='$entry_id'";
            $result= $conn->query($query);
            if(!$result){
                die($conn->error);
            }
            
            $row= $result->fetch_array(MYSQLI_NUM);
            $title_id= $row[0];
            
            
            $query= "SELECT title FROM titles WHERE title_id='$title_id'";
            $result= $conn->query($query);
            if(!$result){
                die($conn->error);
            }            
            $row= $result->fetch_array(MYSQLI_NUM);
            $title= $row[0];
            
            return $title;
            
        }
        
        
        
        
        function check_username($conn,$username){
            $query= "SELECT username FROM users WHERE username='$username'";
            $result =$conn->query($query);
            if(!$result){
                die($conn->error);
            }
            
            $num_rows= $result->num_rows;
            
            if($num_rows>0){
                return TRUE;
            }
            else{
                return FALSE;
            }
            
        }


        
        
        function send_reset_pass_mail($conn,$username,$email){            
            $code= generate_password_reset_code($conn,$username,$email);

            $text=  "Password reset link: https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]?u=$username&email=$email&code=$code";
                $text= "
Hello ".$username.",

We have received a password reset request from user: ".$username." on ".SITEADDR.". 
To confirm and reset your password, please follow the following URL:

https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]?u=$username&email=$email&code=$code

if you didn't request a password reset request on this website, please ignore this e-mail.

Regards.
Directory Staff

".SITEADDR."
                ";
              
				$mail = new PHPMailer();

				$mail->IsSMTP();                                // send via SMTP
                $mail->SMTPSecure = 'tls';
				$mail->Host     = MAILHOST; // SMTP servers
                $mail->Port= MAILPORT;
				$mail->SMTPAuth = true;     // turn on SMTP authentication
				$mail->Username = MAILADDR;  // SMTP username
				$mail->Password = MAILPASS; // SMTP password
				$mail->From     = MAILADDR; // smtp kullanÄ±cÄ± adÄ±nÄ±z ile aynÄ± olmalÄ±
				$mail->Fromname = SITENAME;
				$mail->AddAddress("$email","$username");
				$mail->Subject= "Password Reset";
				$mail->Body= $text;

				if(!$mail->Send())
				{
					
					echo "Mailer Error: " . $mail->ErrorInfo;
					return FALSE;
				}
				else{

					return TRUE;

					}
                    
        }
        
        
        
        function get_username($conn,$email){
            $query= "SELECT username FROM users WHERE email='$email'" ;
            $result= $conn->query($query);
            if(!$result){
                die($conn->error);
            }
            
            $row= $result->fetch_array(MYSQLI_NUM);
            $username= $row[0];
            
            
            return $username;
            
        }

        function get_username_by_id($conn,$id){
            $query= "SELECT username FROM users WHERE id='$id'" ;
            $result= $conn->query($query);
            if(!$result){
                die($conn->error);
            }
            
            $row= $result->fetch_array(MYSQLI_NUM);
            $username= $row[0];
            
            
            return $username;
            
        }


        function get_user($conn, $email){
            $query= "SELECT * FROM users WHERE email='$email'" ;
            $result= $conn->query($query);
            if(!$result){
                die($conn->error);
            }
            
            $row= $result->fetch_array(MYSQLI_ASSOC);            
            
            return $row;
            
        }


        function get_user_id($conn,$username){
            $query= "SELECT id FROM users WHERE username='$username'" ;
            $result= $conn->query($query);
            if(!$result){
                die($conn->error);
            }
            
            $row= $result->fetch_array(MYSQLI_NUM);
            $user_id= $row[0];
            
            
            return $user_id;
            
        }
        
        
        function generate_password_reset_code($conn,$username,$email){
            $user_hashed_pass = get_user($conn, $email)['password'];

            $code= hash('ripemd128',$username.$email.$user_hashed_pass);
            
            return $code;
        }
        
        
        function change_password($conn,$email,$password){
            $password= encryptPass($password);
            
            $query= "UPDATE users SET password='$password' WHERE email='$email'";
            $result= $conn->query($query);
            
            if(!$result){
                die($conn->error);
            }
            
            return TRUE;
        }
        
        
        
        function check_email($conn,$email){
            
            $query= "SELECT * FROM users WHERE email='$email'";
            $result= $conn->query($query);
            if(!$result){
                die($conn->error);
            }
            
            $num_rows= $result->num_rows;
            if($num_rows>0){
                return TRUE;
            }
            else{
            	return FALSE;
            }
            
            
        }

    
        function title_check($title){
            if(strlen($title)>64){
                return FALSE;
            }
            else{
                return TRUE;
            }
        }
        
        
        function add_title_and_entry($conn,$title,$stars,$entry,$category){
            if($stars == 0){
                $totalentry = 0;
            }
            else{
                $totalentry = 1;
            }

        	if(empty($title)){
        		die('Header cant be empty!');
        	}
            if(empty($entry)){
                die('Entry cant be empty!');
            }
            
            $lastupdate = new DateTime();
            $lastupdate=  $lastupdate->format('Y-m-d H:i:s');
            
            $username= $_SESSION['username'];
            $query="INSERT INTO titles(title,category,username,lastupdate,totalstar,totalentry)"
                    . " VALUES('$title','$category','$username','$lastupdate','$stars',$totalentry)";
            $result= $conn->query($query);
            if(!$result){
                die($conn->error);
            }
            $insert_id= $conn->insert_id;
            
            
            $title_id= $insert_id;
            

            add_entry($conn, $title_id, $entry, $stars, $category, $username);
            
            return $title_id;
            
        }
        
        
        
        
        function control_title($conn,$title){
            $query= "SELECT title FROM titles WHERE title='$title'";
            $result= $conn->query($query);
            if(!$result){
                die($conn->error);
            }
            
            $num_rows= $result->num_rows;
            if($num_rows>=1){
                return 1;
            }
            
            
            
            $characters= array('.',',','!','^','#','$','%','&','/','{','(',')','[',']','=','}','?',
                '*','-','+','"','\'','<','>','|',':',';',' ');
            
            
            foreach ($characters as $item){
                if($item==$title){
                    return 2;
                }
            }
            
            
            if($title==''){
                return 2;
            }
            

            
        }
        
        function get_title_id($conn,$title){
            $query= "SELECT title_id FROM titles WHERE title='$title'";
            $result= $conn->query($query);
            if(!$result){
                die($conn->error);
            }
            //$result->data_seek(0);
            $row= $result->fetch_array(MYSQLI_NUM);
            $title_id= $row[0];
            
            return $title_id;
            
        }
        
        
        function alt_replace($string){
       $search = array(
      chr(0xC2) . chr(0xA0), // c2a0; Alt+255; Alt+0160; Alt+511; Alt+99999999;
      chr(0xC2) . chr(0x90), // c290; Alt+0144
      chr(0xC2) . chr(0x9D), // cd9d; Alt+0157
      chr(0xC2) . chr(0x81), // c281; Alt+0129
      chr(0xC2) . chr(0x8D), // c28d; Alt+0141
      chr(0xC2) . chr(0x8F), // c28f; Alt+0143
      chr(0xC2) . chr(0xAD), // cdad; Alt+0173
      chr(0xAD)
   );
   $string = str_replace($search, '', $string);
   return trim($string);
}
 


        function generate_email_activate_code($conn, $email){
            
			$user = get_user($conn, $email);
            
            $activate_code= hash('ripemd128',$email . $user['password']);
            
            return $activate_code; 
        }
 

        
        function email_check($conn,$email){
            
            $query= "SELECT emailcheck FROM users WHERE email='$email'";
            $result= $conn->query($query);
          
            $row= $result->fetch_array(MYSQLI_NUM);
            
            if(!$result){
                die($conn->error);
            }
            
            $result->data_seek(0);
            
            if($row[0]=='yes'){
                return TRUE;
            }
            else{
                return FALSE;
            }
            
        }

        
        function logIn($username,$email){
            session_start();
            $_SESSION['username']= $username;
            $_SESSION['email']= $email;
        }
        
          
      
        function encryptPass($password){
            $password= hash('ripemd128',$password);
            return $password;            
        }

        
        function display_all_entries($conn,$number){
            
        echo "<h1>Last $number Comment</h1>";    
            
            $query= "SELECT entry,stars,time,username,title_id,id FROM entries"
        . " ORDER BY time DESC LIMIT $number"; 
        
            $result= $conn->query($query);
            
            if(!$result){
                die($conn->error);
            }
            
            $num_rows= $result->num_rows;
            
            for($i=0;$i<$num_rows;$i++){
                
               
                
                $result->data_seek($i);
                $row= $result->fetch_array(MYSQLI_NUM);
                $entry= $row[0];
                $stars= $row[1];
                $time= $row[2];
                $time= new DateTime($time);       
                $time = $time->format('d-m-Y H:i:s');
                $username= $row[3];
                $title_id= $row[4];
                $title= get_title($conn,$title_id);
                $entry_id= $row[5];

              
                
                echo "<span itemscope itemtype='http://www.schema.org/Review'>";
                
                echo "<a itemprop='itemReviewed' id='mini_title' href='title.php?id=$title_id'>$title</a>";
                
                for($j=0;$j<$stars && STARVOTE;$j++){
                        echo "<img alt='star' title='$stars star' width='20px' src='img/star.png'>";
                }
                
                
                
                        echo nl2br("<p id='entry_style' itemprop='reviewBody'>"  . add_bkz_url($entry) . "</p>");
                        
                        
                        //printf("<p itemprop='reviewBody'>%s</p>", $entry);
                        display_edit_delete_area($conn, $entry_id, $username);
                        
                        
                        echo "<div><div align='right'><span style='color:green; font-size:9pt' itemprop='datePublished'>$time</span> - <a id='author' href='profile.php?u=$username'>$username</a> - <div class='dropdown'><span class='dropbtn' title='Reportit' href=''> ...</span><div  class='dropdown-content'><a href='reportus.php?EntryNo=$entry_id'>report</a></div></div></div></div><hr>";
                        
                        if( ($i != 0)  && ($i%5 == 0) )
                        {
                            include 'fluid-ad.php';
                            echo "<hr>";
                        }
                }
        
        }
        
        
        function get_title($conn,$title_id){
                $query= "SELECT title FROM titles WHERE title_id='$title_id'";
                $result= $conn->query($query);
                if(!$result){
                    die($conn->error);
                }
                $result->data_seek(0);
                $row= $result->fetch_array(MYSQLI_NUM);
                
                $title= $row[0];
                
                return $title;
            }
        
        
 
        
        function update_entry($conn,$entry_id,$stars,$entry){
            $ex_stars = find_star_number_of_entry($conn, $entry_id);
            $query= "UPDATE entries SET stars='$stars',entry='$entry' WHERE id='$entry_id'";
            $result= $conn->query($query);
            
            $title_id= get_title_id_from_entry_id($conn, $entry_id);
            $result= update_average_stars($conn,$ex_stars,$stars,$title_id);
            
            
            if($result){
                return TRUE;
            }
            else{
                return FALSE;
            }
            
            
        }
        


    function delete_entry($conn, $entry_id){
        $query= "DELETE FROM entries WHERE id='$entry_id'";
        $result= $conn->query($query);
        
        if(!$result){
            die($conn->error);
        }
        
    }
    
    function find_totalstar_of_title($conn,$title_id){
        $query= "SELECT totalstar FROM titles WHERE title_id='$title_id'";
        $result= $conn->query($query);
        if(!$result){
            die($conn->error);
        }
        $row= $result->fetch_array(MYSQLI_NUM);
        $totalstar= $row[0];
        return $totalstar;
    }
    
    function find_totalentry_of_title($conn,$title_id){
        $query= "SELECT COUNT(*) FROM entries WHERE title_id='$title_id'";
        $result= $conn->query($query);
        if(!$result){
            die($conn->error);
        }
        $row= $result->fetch_array(MYSQLI_NUM);
        $totalentry= $row[0];
        return $totalentry;
    }
    
    function subtract_stars($conn,$title_id,$star_number_of_entry,$totalentry,$totalstar){

        if($star_number_of_entry == 0){
            return true;
        }
        
        $new_totalstar= $totalstar-$star_number_of_entry;
        
        $query= "UPDATE titles SET totalstar='$new_totalstar' WHERE title_id='$title_id'";
        $result= $conn->query($query);
        if(!$result){
            die($conn->error);
        }
        
        
        if($totalentry>0){
            $new_totalentry= $totalentry-1;
        
            $query= "UPDATE titles SET totalentry='$new_totalentry' WHERE title_id='$title_id'";
            $result= $conn->query($query);
            if(!$result){
                die($conn->error);
            }
        }
        
        if($new_totalentry <1){
            
            delete_title($conn, $title_id);
            
        }
        
        
        
    }
    
    function delete_title($conn,$title_id){
        $query= "DELETE FROM titles WHERE title_id='$title_id'";
        $result= $conn->query($query);
        if(!$result){
            die($conn->error);
        }
        
    }
    

    

    function find_owner_of_entry($conn,$entry_id){
        
        $query= "SELECT username FROM entries WHERE id='$entry_id'";
        $result= $conn->query($query);
        if(!$result){
            die($conn->error);
        }
        
        $row= $result->fetch_array(MYSQLI_NUM);
        
        $owner = $row[0];
        
        return $owner;
        
    }
    function find_status_of_member($conn,$username){
        $query= "SELECT status FROM users WHERE username='$username'";
            
        $result= $conn->query($query);
        if(!$result){
            die($conn->error);
        }
            
        $row= $result->fetch_array(MYSQLI_NUM);
        $status= $row[0];
             
        return $status;
    }
    
    
    function add_bkz_url($entry){
        
        $template = '@(http)?(s)?(://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@';
        $new_value = '<a style="color:#5882FA;font-size:16px" href="http$2://$4" target="_blank" title="$0">$0</a>';
        $last_change = preg_replace($template, $new_value, $entry);
        
        
        $template = '/\(bkz: (.*?)\)/';
        $new_value = '(bkz: <a class = "entry_url" href="newtitle.php?title=${1}">${1}</a>)';
        $last_change = preg_replace($template, $new_value, $last_change);
    
    
        $template = '/`(.*?)`/';
        $new_value = '<a class = "entry_url" href="newtitle.php?title=${1}">${1}</a>';
        $last_change = preg_replace($template, $new_value, $last_change);
      

        $template = "/\[(.*?) (.*?)\]/";
        $new_value = '<a target = "_blank" class = "entry_url" href="${1}">${2}</a>';
        $last_change = preg_replace($template, $new_value, $last_change);
        
        return $last_change;
    }
    
    
    function get_entry_number_in_today($conn, $title_id){
        $query= "SELECT COUNT(*) FROM entries WHERE title_id = '$title_id' AND DATE(time) = CURDATE()" ;
        $result= $conn->query($query);
        if(!$result){
            die($conn->error);
        }
        
        $row= $result->fetch_array(MYSQLI_NUM);
        
        $number= $row[0];
        
        return $number;
    }


    
?>

