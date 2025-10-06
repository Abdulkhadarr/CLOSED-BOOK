<?php
$cid='';
include "con.php";
session_start();
include "rsa.php";
include "hash.php"; // hashing for master password

if(isset($_GET['s'])){
    $s=1;
} else { $s=0; }

if(isset($_GET['ot'])&&isset($_GET['cid'])){
    $cid=$_GET['cid'];
    $ot=1;$s=2;$suc=2;$succ=1;
}else{ $ot=0; }
if(isset($_GET['suc'])&&isset($_GET['cid'])){
    $cid=$_GET['cid'];
    $suc=1;$s=2;$succ=1;
}else{ $suc=0; }
if(isset($_GET['succ'])&&isset($_GET['img'])){
    $img=$_GET['img'];
    $succ=2;$s=2;$suc=0;
}else{ $succ=0; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Closed Book</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <link href="img/favicon.ico" rel="icon">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
  <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet"/>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
</head>
<body>
<?php
/* ================= OTP FOR DOWNLOAD ================= */
if(isset($_GET['m'])&& isset($_GET['img'])){
    $phone=$_GET['m']; $img=$_GET['img']; $s=2;
    $otp=mt_rand(1000,9999); $_SESSION['otp']=$otp;
    $fields=["variables_values"=>$otp,"route"=>"otp","numbers"=>$phone];
    $curl=curl_init();
    curl_setopt_array($curl,[CURLOPT_URL=>"https://www.fast2sms.com/dev/bulkV2",CURLOPT_RETURNTRANSFER=>true,CURLOPT_CUSTOMREQUEST=>"POST",CURLOPT_POSTFIELDS=>json_encode($fields),CURLOPT_HTTPHEADER=>["authorization: 16cxVht...JF","content-type: application/json"]]);
    curl_exec($curl);curl_close($curl);
    echo "<script>window.location='document.php?succ=2&img=$img';</script>";
}
if($succ==2){ echo "<script src='https://code.jquery.com/jquery-3.4.1.min.js'></script><script>$(function(){ $('#my').modal('show'); });</script>"; }
?>
<!-- OTP Modal for Download -->
<div class="modal fade" id="my" tabindex="-1" data-bs-backdrop="static">
<div class="modal-dialog modal-dialog-centered"><div class="modal-content bg-secondary text-white">
  <div class="modal-header"><h5 class="modal-title">Otp sent to your registered mobile number</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
    <?php $email=$_SESSION['email']; $r1=mysqli_fetch_array(mysqli_query($con,"select mobile from tbl_register where email='$email'")); $m=$r1[0]; ?>
  </div>
  <div class="modal-body"><form method="post">
    <div class="mb-3"><label class="form-label">Enter OTP</label>
      <div class="input-group">
        <input type="text" class="form-control" name="otp">
        <input type="hidden" id="m" value="<?php echo $m; ?>">
        <input type="hidden" id="img" name="img" value="<?php echo $img; ?>">
      </div>
    </div>
    <div class="mb-3"><button type="button" class="btn btn-secondary" onclick="otpsend1('<?php echo $img; ?>');">Resend OTP</button></div>
    <div class="modal-footer"><button type="submit" class="btn btn-primary" name="otp2">Submit OTP</button></div>
  </form></div>
</div></div></div>
<?php
if(isset($_POST['otp2'])){
  if(isset($_SESSION['otp']) && $_SESSION['otp']==$_POST['otp']){
    $keys=generateKeys(); $dec=decrypt($_POST['img'],$keys['private']);
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js'></script>
          <script>swal({title:'SUCCESS!',text:'OTP verified',type:'success'}).then(()=>{var a=document.createElement('a');a.href='$dec';a.download='download';a.click();window.location='document.php';});</script>";
  } else {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js'></script>
          <script>swal({title:'Error',text:'Incorrect OTP!',type:'error'}).then(()=>{window.location='document.php';});</script>";
  }
}
?>

<?php
/* ================= OTP FOR SHOW DOCUMENT ================= */
if(isset($_GET['m'])&& isset($_GET['cid'])){
    $phone=$_GET['m']; $cid=$_GET['cid']; $s=2;
    $otp=mt_rand(1000,9999); $_SESSION['otp']=$otp;
    $fields=["variables_values"=>$otp,"route"=>"otp","numbers"=>$phone];
    $curl=curl_init();
    curl_setopt_array($curl,[CURLOPT_URL=>"https://www.fast2sms.com/dev/bulkV2",CURLOPT_RETURNTRANSFER=>true,CURLOPT_CUSTOMREQUEST=>"POST",CURLOPT_POSTFIELDS=>json_encode($fields),CURLOPT_HTTPHEADER=>["authorization: 16cxVht...JF","content-type: application/json"]]);
    curl_exec($curl);curl_close($curl);
    echo "<script>window.location='document.php?suc=1&cid=$cid';</script>";
}
if($suc==1){ echo "<script src='https://code.jquery.com/jquery-3.4.1.min.js'></script><script>$(function(){ $('#myMod').modal('show'); });</script>"; }
?>
<script>
function otpsend(){m=document.getElementById('m').value;cid=document.getElementById('cid').value;window.location="document.php?m="+m+"&cid="+cid;}
function otpsend1(img){m=document.getElementById('m').value;window.location="document.php?m="+m+"&img="+img;}
</script>
<!-- OTP Modal for Show -->
<div class="modal fade" id="myMod" tabindex="-1" data-bs-backdrop="static">
<div class="modal-dialog modal-dialog-centered"><div class="modal-content bg-secondary text-white">
  <div class="modal-header"><h5 class="modal-title">Otp sent to your registered mobile number</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
    <?php $email=$_SESSION['email']; $r1=mysqli_fetch_array(mysqli_query($con,"select mobile from tbl_register where email='$email'")); $m=$r1[0]; ?>
  </div>
  <div class="modal-body"><form method="post">
    <div class="mb-3"><label class="form-label">Enter OTP</label>
      <div class="input-group"><input type="text" class="form-control" name="otp">
        <input type="hidden" id="m" value="<?php echo $m; ?>">
        <input type="hidden" id="cid" name="cid" value="<?php echo $cid; ?>">
      </div>
    </div>
    <div class="mb-3"><button type="button" class="btn btn-secondary" onclick="otpsend();">Resend OTP</button></div>
    <div class="modal-footer"><button type="submit" class="btn btn-primary" name="otp1">Submit OTP</button></div>
  </form></div>
</div></div></div>
<?php
if(isset($_POST['otp1'])){
  if(isset($_SESSION['otp']) && $_SESSION['otp']==$_POST['otp']){
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js'></script>
          <script>swal({title:'SUCCESS!',text:'OTP verified',type:'success'}).then(()=>{window.location='document.php?ot=1&cid=$cid';});</script>";
  } else {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js'></script>
          <script>swal({title:'Error',text:'Incorrect OTP!',type:'error'}).then(()=>{window.location='document.php';});</script>";
  }
}
?>

<?php /* ================= MASTER PASSWORD ================= */ ?>
<?php if($s==0){ echo "<script src='https://code.jquery.com/jquery-3.4.1.min.js'></script><script>$(function(){ $('#myModal1').modal('show'); });</script>"; } ?>
<div class="modal fade" id="myModal1" tabindex="-1" data-bs-backdrop="static">
<div class="modal-dialog modal-dialog-centered"><div class="modal-content bg-secondary text-white">
  <div class="modal-header"><h5 class="modal-title">Master Password</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
  <div class="modal-body"><form method="post">
    <div class="mb-3"><label class="form-label">Enter Master Password</label>
      <div class="input-group">
        <input type="password" class="form-control" id="password-input" name="password" required>
        <button class="btn btn-outline-primary" type="button" onclick="togglePasswordVisibility()"><i class="bi bi-eye-slash" id="eye-icon"></i></button>
      </div>
    </div>
    <div class="modal-footer"><button type="submit" class="btn btn-primary" name="verify">Verify</button></div>
  </form></div>
</div></div></div>
<?php
if(isset($_POST['verify'])){
  $email=$_SESSION['email']; $plain=$_POST['password'];
  $res=mysqli_query($con,"SELECT mpassword,salt FROM tbl_master WHERE email='$email' LIMIT 1");
  if($res && mysqli_num_rows($res)>0){
    $row=mysqli_fetch_assoc($res); $stored=$row['mpassword']; $salt=$row['salt'];
    $input=bin2hex(mySecureHash($plain,$salt));
    if(hash_equals($stored,$input)){
      echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js'></script>
            <script>swal({title:'SUCCESS!',text:'Unlocked',type:'success'}).then(()=>{window.location='document.php?s=1';});</script>";
    } else {
      echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js'></script>
            <script>swal({title:'Error',text:'Incorrect Master Password!',type:'error'}).then(()=>{window.location='document.php';});</script>";
    }
  }
}
?>
<script>
function togglePasswordVisibility(){
  const inp=document.getElementById("password-input");
  const eye=document.getElementById("eye-icon");
  if(inp.type==="password"){inp.type="text";eye.classList.replace("bi-eye-slash","bi-eye");}
  else{inp.type="password";eye.classList.replace("bi-eye","bi-eye-slash");}
}
</script>

<!-- ================= PAGE CONTENT ================= -->
<div class="container-fluid position-relative d-flex p-0">
<?php include "sidebar.php"; ?>
<div class="content">
<?php include "navbar.php"; ?>
<div class="container-fluid pt-4 px-4"><div class="bg-secondary text-center rounded p-4">
<div class="d-flex align-items-center justify-content-between mb-4">
  <h6 class="mb-0">Gallery</h6>
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ModalForm">Add Document</button>
  <button class="btn btn-primary" onclick="otpsend();">Show Document</button>
</div>
<!-- Add Document Modal -->
<div class="modal fade" id="ModalForm" tabindex="-1"><div class="modal-dialog modal-dialog-centered"><div class="modal-content bg-secondary text-white">
  <div class="modal-header"><h5 class="modal-title">Add Document</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
  <div class="modal-body"><form method="post" enctype="multipart/form-data">
    <div class="mb-3"><label class="form-label">Upload Document</label><input type="file" name="document_file" class="form-control"></div>
    <div class="modal-footer"><button type="submit" class="btn btn-primary" name="add">Add</button></div>
  </form></div>
</div></div></div>

<!-- Document Grid -->
<div class="row">
<?php
$email=$_SESSION['email'];
$q=mysqli_query($con,"select * from tbl_document where email='$email'");
$r1=mysqli_fetch_array(mysqli_query($con,"select mobile from tbl_register where email='$email'")); $m=$r1[0];
if(mysqli_num_rows($q)>0){
  while($r=mysqli_fetch_array($q)){
    if($ot==1){
      $keys=generateKeys(); $dec=decrypt($r[2],$keys['private']);
      echo "<div class='col-lg-4 mb-4'><div class='bg-image hover-overlay ripple shadow-1-strong rounded'>
        <div class='embed-responsive embed-responsive-16by9'><iframe class='embed-responsive-item' src='$dec'></iframe></div></div>
        <a href='removedoc.php?id=$r[0]'><button class='btn btn-danger m-2'><i class='fa fa-trash'></i></button></a>
        <button class='btn btn-primary m-2' onclick=\"otpsend1('$r[2]');\"><i class='fa fa-download'></i></button></div>";
    } else {
      echo "<div class='col-lg-4 mb-4'><img src='uploads/images.jpg' class='w-100'/></div>";
    }
  }
}
?>
</div></div></div>
<?php include "footer.php"; ?>
</div></div>

<?php
/* ================= ADD DOCUMENT ================= */
if(isset($_POST['add'])){
  if(isset($_FILES['document_file'])){
    $file=$_FILES['document_file']; $name=$file['name']; $size=$file['size']; $tmp=$file['tmp_name'];
    $ext=strtolower(pathinfo($name,PATHINFO_EXTENSION));
    $allowed=["doc","docx","pdf","txt"];
    if(in_array($ext,$allowed) && $size<5000000){
      $email=$_SESSION['email']; $keys=generateKeys(); $path="document/".$name;
      $cipher=encrypt($path,$keys['public']);
      if(mysqli_query($con,"insert into tbl_document(email,path) values('$email','$cipher')")){
        move_uploaded_file($tmp,$path);
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js'></script>
              <script>swal({title:'SUCCESS!',text:'Document Added!',type:'success'}).then(()=>{window.location='document.php';});</script>";
      }
    }
  }
}
?>

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>
