<?php include "con.php"; ?>
<?php
if(isset($_GET['id'])){
  extract($_GET);

 echo $s11="delete from tbl_video where id='$id'";
  $q11=mysqli_query($con,$s11);
  if($q11){
    ?>
        
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js"></script>  
<script>

swal({
    title: "SUCCESS!",
    text: "Details  Deleted!",
    type: "success"
}).then(function() {
    window.location = "video.php";
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
window.location = "video.php";
});
</script>

    <?php
}


}
?>
