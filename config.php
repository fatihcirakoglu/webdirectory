<?php debug_backtrace() || die ("<h2>Access Denied!</h2> This file is protected and not available to public."); ?>
<?php

/*
 * With this page you can make all settings to set up this script.
 */	

//DATABASE SETTINGS

define('DB_NAME', 'yourdatabsename'); // Database name

define('DB_PASS', 'yourdatabsepassword'); // Database  password

define('DB_USER', 'usernameforyourdatabase'); // Database Username

define('DB_HOST', 'yourdatabasehostname'); //Database Host name




//SITE SETTINGS
define('SITENAME', 'yoursite.com'); // Website Name
define('SITEADDR', 'https://yoursite.com');
// Write your website address with http and no slash at the end.
define("STARVOTE",true);


// MAIL SETTINGS

define('MAILADDR', 'noreply@yoursite.com');
define('MAILPASS', 'mailpassword');
define('MAILHOST', 'mail.yoursite.com');
define('MAILPORT', '2525');



// FOOTER MESSAGE
$FOOTERMESSAGE = "Copyright © Yorumun.com , developed by Fcr"; 
$PERSONALMESSAGE = "<br><br>Home of internet volunteers";


//USER SETTING
define('MIN_USERNAME_CHAR_NUM', 1); //Min number of characters that users will have



// GOOGLE CAPTCHA OPTIONS
$GCAPTCHA_OPEN = false; // If you want to use google captcha, change it as true
$captchaSecretCode = 'yourkey'; // If GCAPTCHA_OPEN is true, please write your captchaSecretCode here
$dataSiteKey = 'yourkey';



?>
