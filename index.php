<!DOCTYPE html>
<?php session_start(); ?>
<?php require_once 'config.php'; ?>

<html lang="en">
    <head>
        <style>@import'style.css'</style>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-130596512-1"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());
        
          gtag('config', 'UA-130596512-1');
        </script>

        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script> 
        <script> (adsbygoogle = window.adsbygoogle || []).push({ google_ad_client: "ca-pub-3114985042987806", enable_page_level_ads: true }); </script>
        
        <script data-ad-client="ca-pub-3114985042987806" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
      
        <title>Comment Zone | Web Directory</title>
        <link rel="icon" href="./img/icon.png"/>
    
	    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>
       
        <?php require_once 'functions.php'; ?>
        <?php require_once 'header.php'; ?>
       
        
        <link rel="canonical" href="https://yorumun.com" />
        
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="title" content="Add URL | Free Web Submission | Web Directory">
   	    <meta name="description" content="Free web directory, share your feedbacks, comments and opinions on websites, socialmedia, blogs, technology, businesses, organizations, games and more.">
        <meta name="keywords" content="online directory, submit url, web directory, free dictionary, search engine submission, free directory submission sites, backlink, user comments, high rank directory, how is, all about web,
                                    BUSINESS directory,add url,free directory,web directory,Submit URL,online directory,search engine submit,search engine submitter,search engine submission,url search engine submission,best search engine submission,top search engine submission,
                                    list of search engine websites,,web search engine submission free,search engine,list of search engines,directory listing,directory submission,Internet DIRECTORY,links directory,
                                    directory submit,submit url to search engines,free directories,web directories,Google Add Url,free web directories,submit to search engines,free web directory,free directory listing,
                                    free search engine submission,search engine directory,Free Directory Submission,submit site to search engines,web directory list,online business directory,submit website to search engines,
                                    add url to search engines,web services directory">
        <meta name="robots" content="index, follow">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="language" content="English">
        <meta name="author" content="comments, comment repository">
        <meta charset="utf-8" data-is-mobile="true">
        
        <meta property="og:site_name" content="yorumun.com">
        <meta property="og:description" content="Info Repo For Everyone | Web Directory | All About Web">
        <meta property="og:url" content="https://yorumun.com">
        <meta property="og:locale" content="en_EN">
        <meta property="og:type" content="article">
        <meta property="og:title" content="Info Repo For Everyone | Web Directory | All About Web">
        <meta property="og:image" content="https://yorumun.com/img/aboutustitle.jpg">
        <meta name="google-site-verification" content="1FcueH4qMkzq6TofVGd9BGCZBvWimmPZBhBFHzuROcg" />

        <script async custom-element="amp-auto-ads" src="https://cdn.ampproject.org/v0/amp-auto-ads-0.1.js"> </script>
        
        
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
        
        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-56P26N8');</script>
        <!-- End Google Tag Manager -->
	    
	    <script data-ad-client="ca-pub-3114985042987806" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        
    </head>
    
   
    <body class = "main_frame grid_container">
        
        
        
        <?php
// THE FOLLOWING BLOCK IS USED TO RETRIEVE AND DISPLAY LINK INFORMATION.
// PLACE THIS ENTIRE BLOCK IN THE AREA YOU WANT THE DATA TO BE DISPLAYED.

// MODIFY THE VARIABLES BELOW:
// The following variable defines whether links are opened in a new window
// (1 = Yes, 0 = No)
$OpenInNewWindow = "1";

// # DO NOT MODIFY ANYTHING ELSE BELOW THIS LINE!
// ----------------------------------------------
$BLKey = "II8C-9SCH-31MC";

if(isset($_SERVER['SCRIPT_URI']) && strlen($_SERVER['SCRIPT_URI'])){
    $_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_URI'].((strlen($_SERVER['QUERY_STRING']))?'?'.$_SERVER['QUERY_STRING']:'');
}

if(!isset($_SERVER['REQUEST_URI']) || !strlen($_SERVER['REQUEST_URI'])){
    $_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'].((isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']))?'?'.$_SERVER['QUERY_STRING']:'');
}

$QueryString  = "LinkUrl=".urlencode(((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on')?'https://':'http://').$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
$QueryString .= "&Key=" .urlencode($BLKey);
$QueryString .= "&OpenInNewWindow=" .urlencode($OpenInNewWindow);


if(intval(get_cfg_var('allow_url_fopen')) && function_exists('readfile')) {
    @readfile("http://www.backlinks.com/engine.php?".$QueryString); 
}
elseif(intval(get_cfg_var('allow_url_fopen')) && function_exists('file')) {
    if($content = @file("http://www.backlinks.com/engine.php?".$QueryString)) 
        print @join('', $content);
}
elseif(function_exists('curl_init')) {
    $ch = curl_init ("http://www.backlinks.com/engine.php?".$QueryString);
    curl_setopt ($ch, CURLOPT_HEADER, 0);
    curl_exec ($ch);

    if(curl_error($ch))
        print "Error processing request";

    curl_close ($ch);
}
else {
    print "It appears that your web host has disabled all functions for handling remote pages and as a result the BackLinks software will not function on your web page. Please contact your web host for more information.";
}
?>
       

    <?php require_once 'list.php'; ?>
        
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-56P26N8"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
        
    
        <div class="content_area">    
            <?php require_once 'search-results.php'; ?>
            <div class='desktop-hide'><span id='cats'></span></div>
            <?php include_once 'main-ad.php'; ?>
            
            <?php include_once 'right-ad.php'; ?>
            
            <amp-auto-ads type="adsense"
              data-ad-client="ca-pub-3114985042987806">
            </amp-auto-ads>
            
            <?php
            
            require_once 'conn.php';
            
            
            display_all_entries($conn,20);
            
            ?>
            
            <?php require_once 'footer.php'; ?>
            <?php include 'feed-ad.php'; ?>
            
            
        </div>
        
    </body>
    
</html>