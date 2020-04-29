<div class="mobile-hide" id="list">
    
    
<script>
function loadDoc(category) {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("list_area").innerHTML = this.responseText;
    }
  };
  xhttp.open("POST", "list-content.php?category="+category , true);
  xhttp.send();
}
</script>
        
    
    
<div id="list_area">
<?php

if(isset($_SESSION['precat'])){
    $category= $_SESSION['precat'];
}
else{
    $category= 'today';
}

    echo <<<_END
<script>
loadDoc('$category');
</script>


_END;
?>

</div>



</div>
