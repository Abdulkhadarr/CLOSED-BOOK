<?php
$cid='';
include "con.php";
session_start();
include "rsa.php";
include "hash.php"; // added for hashing master password

if(isset($_GET['s'])){
    $s=1;
}
else{ $s=0; }

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
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
<!-- ================= OTP FOR DOWNLOAD ================= -->
<?php
if(isset($_GET['m'])&& isset($_GET['img'])){
    $phone = $_GET['m'];
    $img=$_GET['img'];
    $s=2;
    
    $randomNumber =mt_rand(1000,9999);
    $_SESSION['otp']=$randomNumber;
    $message = $randomNumber;

    $fields = array(
        "variables_values" => $message,
        "route" => "otp",
        "numbers" => $phone,
    );

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://www.fast2sms.com/dev/bulkV2",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($fields),
        CURLOPT_HTTPHEADER => array(
            "authorization: 16cxVhtCKcHqKYny46VkgGB4dl8IZV4IZkQeyOk6XsIjHAdHqtJ9z28pGfJF",
            "accept: */*",
            "cache-control: no-cache",
            "content-type: application/json"
        ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    ?>
    <script>window.location.href="video.php?succ=2&img=<?php echo $img; ?>";</script>
    <?php
}
?>

<?php if($succ==2){ ?>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script>$(document).ready(function() { $('#my').modal('show'); });</script>
<?php } ?>

<div class="modal fade" id="my" tabindex="-1" data-bs-backdrop="static">
<div class="modal-dialog modal-dialog-centered">
  <div class="modal-content bg-secondary text-white">
    <div class="modal-header">
      <h5 class="modal-title">Otp has been sent to registered mobile number</h5>
      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      <?php
        $email=$_SESSION['email'];
        $s1="select mobile from tbl_register where email='$email'";
        $q1=mysqli_query($con,$s1);
        $r1=mysqli_fetch_array($q1);
        $m=$r1[0];
      ?>
    </div>
    <div class="modal-body">
      <form method="post">
        <div class="mb-3">
          <label class="form-label">Enter OTP</label>
          <input type="text" class="form-control" name="otp">
          <input type="hidden" id="m" value="<?php echo $m; ?>">
          <input type="hidden" id="img" name="img" value="<?php echo $img; ?>">
        </div>
        <button type="button" class="btn btn-secondary" onclick="otpsend1('<?php echo $img; ?>');">Resend OTP</button>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" name="otp2">Submit OTP</button>
        </div>
      </form>
    </div>
  </div>
</div>
</div>

<?php
if(isset($_POST['otp2'])){
  if(isset($_SESSION['otp'])){
    $otp=$_POST['otp'];
    $img=$_POST['img'];
    if($_SESSION['otp']==$otp){
        $keys = generateKeys();
        $decryptedtext = decrypt($img, $keys['private']);
        ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js"></script>
        <script>
        swal({title:"SUCCESS!",text:"OTP verified",type:"success"}).then(function(){
            var a=document.createElement("a");
            a.href="<?php echo $decryptedtext; ?>";
            a.download="download";
            document.body.appendChild(a);a.click();a.remove();
            window.location="video.php";
        });
        </script>
        <?php
    }else{
        ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js"></script>
        <script>swal({title:"Error",text:"Incorrect OTP!",type:"error"}).then(()=>{window.location="video.php";});</script>
        <?php
    }
  }
}
?>

<!-- ================= OTP FOR SHOW VIDEO ================= -->
<?php
if(isset($_GET['m'])&& isset($_GET['cid'])){
    $phone = $_GET['m'];
    $cid=$_GET['cid'];
    $s=2;

    $randomNumber =mt_rand(1000,9999);
    $_SESSION['otp']=$randomNumber;
    $message = $randomNumber;

    $fields = array(
        "variables_values" => $message,
        "route" => "otp",
        "numbers" => $phone,
    );

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://www.fast2sms.com/dev/bulkV2",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($fields),
        CURLOPT_HTTPHEADER => array(
            "authorization: YOUR_FAST2SMS_KEY",
            "content-type: application/json"
        ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    ?>
    <script>window.location.href="video.php?suc=1&cid=<?php echo $cid; ?>";</script>
    <?php
}
?>

<?php if($suc==1){ ?>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script>$(document).ready(function() { $('#myMod').modal('show'); });</script>
<?php } ?>

<div class="modal fade" id="myMod" tabindex="-1" data-bs-backdrop="static">
<div class="modal-dialog modal-dialog-centered">
  <div class="modal-content bg-secondary text-white">
    <div class="modal-header">
      <h5 class="modal-title">Otp has been sent to registered mobile number</h5>
      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      <?php
        $email=$_SESSION['email'];
        $s1="select mobile from tbl_register where email='$email'";
        $q1=mysqli_query($con,$s1);
        $r1=mysqli_fetch_array($q1);
        $m=$r1[0];
      ?>
    </div>
    <div class="modal-body">
      <form method="post">
        <label class="form-label">Enter OTP</label>
        <input type="text" class="form-control" name="otp">
        <input type="hidden" id="m" value="<?php echo $m; ?>">
        <input type="hidden" id="cid" name="cid" value="<?php echo $cid; ?>">
        <button type="button" class="btn btn-secondary" onclick="otpsend();">Resend OTP</button>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" name="otp1">Submit OTP</button>
        </div>
      </form>
    </div>
  </div>
</div>
</div>

<?php
if(isset($_POST['otp1'])){
  if(isset($_SESSION['otp'])){
    $otp=$_POST['otp'];
    if($_SESSION['otp']==$otp){
        ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js"></script>
        <script>swal({title:"SUCCESS!",text:"OTP verified",type:"success"}).then(()=>{window.location="video.php?ot=1&cid=<?php echo $cid; ?>";});</script>
        <?php
    }else{
        ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js"></script>
        <script>swal({title:"Error",text:"Incorrect OTP!",type:"error"}).then(()=>{window.location="video.php";});</script>
        <?php
    }
  }
}
?>

<script>
function otpsend(){
  var m=document.getElementById('m').value;
  var cid=document.getElementById('cid').value;
  window.location="video.php?m="+m+"&cid="+cid;
}
function otpsend1(img){
  var m=document.getElementById('m').value;
  window.location="video.php?m="+m+"&img="+img;
}
</script>

<!-- ================= MASTER PASSWORD ================= -->
<?php if($s==0){ ?>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script>$(document).ready(function() { $('#myModal1').modal('show'); });</script>
<?php } ?>

<div class="modal fade" id="myModal1" tabindex="-1" data-bs-backdrop="static">
<div class="modal-dialog modal-dialog-centered">
  <div class="modal-content bg-secondary text-white">
    <div class="modal-header">
      <h5 class="modal-title">Master Password</h5>
      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
      <form method="post">
        <label class="form-label">Enter Master Password</label>
        <div class="input-group">
          <input type="password" class="form-control" id="password-input" name="password">
          <button class="btn btn-outline-primary" type="button" onclick="togglePasswordVisibility()">
            <i class="bi bi-eye-slash" id="eye-icon"></i>
          </button>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" name="verify">Verify</button>
        </div>
      </form>
    </div>
  </div>
</div>
</div>

<?php
if(isset($_POST['verify'])){
    $email=$_SESSION['email'];
    $plain=$_POST['password'];

    $s="SELECT mpassword, salt FROM tbl_master WHERE email='$email'";
    $q=mysqli_query($con,$s);
    $r=mysqli_fetch_array($q);

    if($r){
        $storedHash=$r['mpassword'];
        $storedSalt=$r['salt'];
        $inputHash=bin2hex(mySecureHash($plain,$storedSalt));

        if(hash_equals($storedHash,$inputHash)){
            ?>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js"></script>
            <script>swal({title:"SUCCESS!",text:"Unlocked",type:"success"}).then(()=>{window.location="video.php?s=1";});</script>
            <?php
        }else{
            ?>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js"></script>
            <script>swal({title:"Error",text:"Incorrect Master Password!",type:"error"}).then(()=>{window.location="video.php";});</script>
            <?php
        }
    }
}
?>

<script>
function togglePasswordVisibility() {
  const inp=document.getElementById("password-input");
  const eye=document.getElementById("eye-icon");
  if(inp.type==="password"){ inp.type="text"; eye.classList.replace("bi-eye-slash","bi-eye"); }
  else{ inp.type="password"; eye.classList.replace("bi-eye","bi-eye-slash"); }
}
</script>

<!-- ================= PAGE CONTENT ================= -->
<div class="container-fluid position-relative d-flex p-0">
  <?php include "sidebar.php"; ?>
  <div class="content">
    <?php include "navbar.php"; ?>
    <div class="container-fluid pt-4 px-4">
      <div class="bg-secondary text-center rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
          <h6 class="mb-0">Gallery</h6>
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ModalForm">Add Video</button>
          <button class="btn btn-primary" onclick="otpsend();">Show Video</button>
        </div>

        <div class="modal fade" id="ModalForm" tabindex="-1">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-secondary text-white">
              <div class="modal-header">
                <h5 class="modal-title">Add Video</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                <form method="post" enctype="multipart/form-data">
                  <input type="file" name="video" class="form-control">
                  <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" name="add">Add</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <?php
          $email=$_SESSION['email'];
          $s="select * from tbl_video where email='$email'";
          $q=mysqli_query($con,$s);

          $s1="select mobile from tbl_register where email='$email'";
          $q1=mysqli_query($con,$s1);
          $r1=mysqli_fetch_array($q1);
          $m=$r1[0];

          if(mysqli_num_rows($q)>0){
            while($r=mysqli_fetch_array($q)){
              if($ot==1){
                $keys=generateKeys();
                $decryptedtext=decrypt($r['path'],$keys['private']);
                ?>
                <div class="col-lg-4 mb-4">
                  <video class="w-100" controls controlsList="nodownload">
                    <source src="<?php echo $decryptedtext; ?>" type="video/mp4">
                  </video>
                  <a href="removevideo.php?id=<?php echo $r['id']; ?>" class="btn btn-danger m-2">Delete</a>
                  <button class="btn btn-primary m-2" onclick="otpsend1('<?php echo $r['path']; ?>');">Download</button>
                </div>
                <?php
              } else {
                ?>
                <div class="col-lg-4 mb-4">
                  <img src="uploads/images.jpg" class="w-100">
                </div>
                <?php
              }
            }
          }
          ?>
        </div>
      </div>
    </div>
    <?php include "footer.php"; ?>
  </div>
</div>

<?php
if(isset($_POST['add'])){
  if(isset($_FILES['video'])){
    $file_name=$_FILES['video']['name'];
    $file_size=$_FILES['video']['size'];
    $file_tmp=$_FILES['video']['tmp_name'];
    $file_ext=strtolower(pathinfo($file_name,PATHINFO_EXTENSION));

    $extensions=array("mp4","avi","wmv","mov");
    if(in_array($file_ext,$extensions)){
      if($file_size<5000000){
        $email=$_SESSION['email'];
        $upload_path="video/".$file_name;

        $keys=generateKeys();
        $cipher=encrypt($upload_path,$keys['public']);

        $s="insert into tbl_video(email,path) values('$email','$cipher')";
        $q=mysqli_query($con,$s);
        if($q){
          move_uploaded_file($file_tmp,$upload_path);
          ?>
          <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js"></script>
          <script>swal({title:"SUCCESS!",text:"Video Added!",type:"success"}).then(()=>{window.location="video.php";});</script>
          <?php
        }
      }
    }
  }
}
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
