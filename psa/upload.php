<?php
include "con.php";
session_start();
include "rsa.php";
if(isset($_POST['add'])){
    
//extract($_POST);

    echo "ghjk";
    $file_name = $_FILES['image']['name'];
    $file_size = $_FILES['image']['size'];
    $file_tmp = $_FILES['image']['tmp_name'];
    $file_type = $_FILES['image']['type'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
    $extensions = array("jpg","jpeg","png");
    
    if (in_array($file_ext, $extensions)) {
      if ($file_size < 5000000) {

        $email=$_SESSION['email'];
$keys = generateKeys();
$kk=$keys['private'];
        $upload_path = "uploads/" . $file_name;
        $ciphertext = encrypt($upload_path, $keys['public']);
        
      echo   $s="insert into tbl_image(email,path) values('$email','$ciphertext')";
$q=mysqli_query($con,$s);
if($q){
    move_uploaded_file($file_tmp, $upload_path);
    ?>
        
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js"></script>  
<script>

swal({
    title: "SUCCESS!",
    text: "Image  Added!",
    type: "success"
}).then(function() {
    window.location = "image.php";
});
</script>

        <?php
}else{

    ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js"></script>  
<script>

swal({
title: "Error",
text: "Something Went Wrong please try again!",
type: "error"
}).then(function() {
window.location = "image.php";
});
</script>

    <?php
}


       
       // echo "Image uploaded successfully.";
      } else {
        ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js"></script>  
    <script>
    
    swal({
    title: "Error",
    text: "File size should be less than 5MB.!",
    type: "error"
    }).then(function() {
    window.location = "image.php";
    });
    </script>
    
        <?php
       // echo "File size should be less than 5MB.";
      }
    } else {
        ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js"></script>  
    <script>
    
    swal({
    title: "Error",
    text: "Invalid file type.!",
    type: "error"
    }).then(function() {
    window.location = "image.php";
    });
    </script>
    
        <?php

      //echo "Invalid file type.";
    }
  }

?>