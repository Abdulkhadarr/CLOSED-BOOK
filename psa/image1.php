
<html>
    <body>
        
<?php
session_start();

if(isset($_GET['ot1'])&&isset($_GET['img'])){
   // $keys = generateKeys();
    //$decryptedtext = decrypt($_GET['img'], $keys['private']);
    $suc=0;$succ=1;$s=0;
// Set the file name and path


//header("Location:image.php");

?>
        
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js"></script>  
<script>

swal({
title: "SUCCESS!",
text: "Otp Verified",
type: "success"
}).then(function() {
   window.location.href="image.php?ot=1&cid=";
});
</script>



<?php
exit;

    
    
    }
?>



<?php
if(isset($_POST['otp2'])){
  if(isset($_SESSION['otp'])){
extract($_POST);
//echo $otp;

if($_SESSION['otp']==$otp){
   // $succ=0;$suc=2;
   //$file =$img;
   //header('Content-Type: application/octet-stream');
   //readfile($file);
    ?>
        
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js"></script>  
<script>

swal({
title: "SUCCESS!",
text: "OTP verified",
type: "success"
}).then(function() {
    var anchorTag = document.createElement("a");
anchorTag.href = "<?php echo $img; ?>";
anchorTag.download="download";
anchorTag.innerText = "Click here";
document.body.appendChild(anchorTag);

// simulate a click on the anchor tag
anchorTag.click();

// hide the anchor tag
anchorTag.style.display = "none";
window.location.href="image.php"
});
</script>

    <?php


// Set the headers for download
//header('Content-Description: File Transfer');
//header('Content-Type: application/octet-stream');
//header('Content-Disposition: attachment; filename="'.basename($file).'"');
//header('Content-Length: ' . filesize($file));
//header('Cache-Control: private');
//header('Pragma: public');

//flush();
//ob_start();
// Output the image file

?>

<?php
}else{
    ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js"></script>  
<script>

swal({
title: "Error",
text: "Incorrect OTP!",
type: "error"
}).then(function() {
window.location = "image.php";
});
</script>

    <?php
    
  }

  }else{
    ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js"></script>  
<script>

swal({
title: "Error",
text: "Incorrect OTP sessions!",
type: "error"
}).then(function() {
window.location = "image.php";
});
</script>

    <?php
    
  }


}



?>

</body>
</html>