<?php
$cid='';
include "con.php";
session_start();
include "rsa.php";
include "hash.php"; // <-- added for SHA-512 master password verification

if(isset($_GET['s'])){
    $s=1;
}
else{ $s=0; }

if(isset($_GET['ot'])&&isset($_GET['cid'])){
$cid=$_GET['cid'];
    $ot=1;$s=2;$suc=2; $succ=1;
}else{ $ot=0; }
if(isset($_GET['suc'])&&isset($_GET['cid'])){
$cid=$_GET['cid'];
    $suc=1;$s=2;$succ=1;
}else{ $suc=0; }
if(isset($_GET['succ'])&&isset($_GET['img'])){
    $img=$_GET['img'];
    //$id=$_GET['id'];
        $succ=2;$s=2;$suc=0;
    }else{ $succ=0; }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Closed Book</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Roboto:wght@500;700&display=swap" rel="stylesheet"> 
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body >

    <!-- download otp -->
<?php
if(isset($_GET['m'])&& isset($_GET['img'])){
//get the inputs
    $phone = $_GET['m'];
    $img=$_GET['img'];
    //$id=$_GET['id'];
    $s=2;
    
    $randomNumber =mt_rand(1000,9999);
    $_SESSION['otp']=$randomNumber;
    $message = $randomNumber;
    $fields = array(
      "message" => $message,
      "language" => "english",
      "route" => "q",
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
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
   ?>
<script>
   window.location.href="image.php?succ=2&img=<?php echo $img; ?>";
</script>
   <?php
}
}
?>
<?php if($succ==2){ ?>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script>
$(document).ready(function() {
    $('#my').modal('show');
});
</script>  
<?php } ?>
<div class="modal fade" id="my" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-bs-backdrop="static">
<div class="modal-dialog modal-dialog-centered">
  <div class="modal-content bg-secondary text-white">
   
    <div class="modal-header">
      <h5 class="modal-title text-white">Otp has been send to registered mobile number</h5>
      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        <?php
        $email=$_SESSION['email'];
         $s1="select mobile from tbl_register where email='$email'";
         $q1=mysqli_query($con,$s1);
         $r1=mysqli_fetch_array($q1);
         $m=$r1[0];
        ?>
      
    </div>
    <div class="modal-body"> <form action="" method="post">
    <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label" >Enter OTP</label>
        <div class="input-group">
            <input type="text" class="form-control"  name="otp">
            <input type="hidden" class="form-control"  id="m" value="<?php echo $m; ?>">
            <input type="hidden" class="form-control"  id="img"  name="img" value="<?php echo $img; ?>">
        </div>

        <div class="mb-3">
            <button type="button" class="btn btn-secondary" onclick="otpsend1('<?php echo $img; ?>');" >Resend OTP</button>
        </div>
     </div>
    </div>
    <div class="modal-footer">
      <button type="submit" class="btn btn-primary" name="otp2">Submit OTP</button>
    </div>
</form>
  </div>
</div>
</div>

<?php
if(isset($_POST['otp2'])){
  if(isset($_SESSION['otp'])){
    extract($_POST);
    if($_SESSION['otp']==$otp){
        $keys = generateKeys();
        $decryptedtext = decrypt($img, $keys['private']);
        ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js"></script>  
        <script>
        swal({
            title: "SUCCESS!",
            text: "OTP verified",
            type: "success"
        }).then(function() {
            var anchorTag = document.createElement("a");
            anchorTag.href = "<?php echo $decryptedtext; ?>";
            anchorTag.download="download";
            anchorTag.innerText = "Click here";
            document.body.appendChild(anchorTag);
            anchorTag.click();
            anchorTag.style.display = "none";
            window.location.href="image.php"
        });
        </script>
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

<!-- otp -->
<?php
if(isset($_GET['m'])&& isset($_GET['cid'])){
    //get the inputs
    $phone = $_GET['m'];
    $cid=$_GET['cid'];
    $s=2;
    
    $randomNumber =mt_rand(1000,9999);
    $_SESSION['otp']=$randomNumber;
    $message = $randomNumber;
    $fields = array(
      "message" => $message,
      "language" => "english",
      "route" => "q",
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
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
   ?>
<script>
   window.location.href="image.php?suc=1&cid=<?php echo $cid; ?>";
</script>
   <?php
}
}
?>
<?php if($suc==1){ ?>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script>
$(document).ready(function() {
    $('#myMod').modal('show');
});
</script>  
<?php } ?>

<script>
function otpsend(){
      m=document.getElementById('m').value;
      cid=document.getElementById('cid').value;
      window.location.href="image.php?m="+m+"&cid="+cid;

}
function otpsend1(img){
      m=document.getElementById('m').value;
      window.location.href="image.php?m="+m+"&img="+img;
}
</script>

<div class="modal fade" id="myMod" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-bs-backdrop="static">
<div class="modal-dialog modal-dialog-centered">
  <div class="modal-content bg-secondary text-white">
   
    <div class="modal-header">
      <h5 class="modal-title text-white">Otp has been send to registered mobile number</h5>
      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        <?php
        $email=$_SESSION['email'];
         $s1="select mobile from tbl_register where email='$email'";
         $q1=mysqli_query($con,$s1);
         $r1=mysqli_fetch_array($q1);
         $m=$r1[0];
        ?>
      
    </div>
    <div class="modal-body"> <form action="" method="post">
    <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label" >Enter OTP</label>
        <div class="input-group">
            <input type="text" class="form-control"  name="otp">
            <input type="hidden" class="form-control"  id="m" value="<?php echo $m; ?>">
            <input type="hidden" class="form-control"  id="cid"  name="cid" value="<?php echo $cid; ?>">
        </div>

        <div class="mb-3">
          <button type="button" class="btn btn-secondary" onclick="otpsend();" >Resend OTP</button>
        </div>
     </div>
    </div>
    <div class="modal-footer">
      <button type="submit" class="btn btn-primary" name="otp1">Submit OTP</button>
    </div>
</form>
  </div>
</div>
</div>
<?php
if(isset($_POST['otp1'])){
  if(isset($_SESSION['otp'])){
    extract($_POST);
    if($_SESSION['otp']==$otp){
        ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js"></script>  
        <script>
        swal({
          title: "SUCCESS!",
          text: "OTP verified",
          type: "success"
        }).then(function() {
          window.location = "image.php?ot=1&cid=<?php echo $cid; ?>";
        });
        </script>
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

<?php if($s==0){ ?>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script>
$(document).ready(function() {
    $('#myModal1').modal('show');
});
</script>  
<?php } ?>

<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-bs-backdrop="static">
<div class="modal-dialog modal-dialog-centered">
  <div class="modal-content bg-secondary text-white">
   
    <div class="modal-header">
      <h5 class="modal-title text-white">Master Password</h5>
      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body"> <form action="" method="post">
    <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label" >Enter Master Password</label>
        <div class="input-group">
            <input type="password" class="form-control" id="password-input" name="password">
            <button class="btn btn-outline-primary" type="button" id="toggle-password-button" onclick="togglePasswordVisibility()">
              <i class="bi bi-eye-slash" id="eye-icon"></i>
            </button>
        </div>
     </div>
    </div>
    <div class="modal-footer">
      <button type="submit" class="btn btn-primary" name="verify">Verify</button>
    </div>
</form>
  </div>
</div>
</div>

<?php
// =================== MASTER PASSWORD VERIFICATION (SHA-512 + SALT) ===================
// Replaced RSA compare with hashing, everything else untouched.
if(isset($_POST['verify'])){
    $password = $_POST['password'] ?? '';
    $email = $_SESSION['email'];

    // fetch hashed master password and salt
    $s = "SELECT mpassword, salt FROM tbl_master WHERE email='$email'";
    $q = mysqli_query($con,$s);
    if($q && mysqli_num_rows($q) > 0){
        $r = mysqli_fetch_array($q);
        $storedHashHex = $r['mpassword']; // hex string in DB
        $storedSalt    = $r['salt'];      // hex string (as saved)

        // compute candidate hash (hex)
        $calcHex = bin2hex(mySecureHash($password, $storedSalt));

        if (hash_equals($storedHashHex, $calcHex)) {
            // correct master password -> unlock
            ?>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js"></script>  
            <script>
            swal({
                title: "SUCCESS!",
                text: "Unlocked",
                type: "success"
            }).then(function() {
                window.location = "image.php?s=1";
            });
            </script>
            <?php
        } else {
            // incorrect
            ?>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js"></script>  
            <script>
            swal({
                title: "Error",
                text: "Incorrect Master Password!",
                type: "error"
            }).then(function() {
                window.location = "image.php";
            });
            </script>
            <?php
        }
    } else {
        // no master row for this user
        ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js"></script>  
        <script>
        swal({
            title: "Error",
            text: "No Master Password found. Please add it first.",
            type: "error"
        }).then(function() {
            window.location = "master.php";
        });
        </script>
        <?php
    }
}
?>

<script>
  function togglePasswordVisibility() {
    const passwordInput = document.getElementById("password-input");
    const eyeIcon = document.getElementById("eye-icon");
    if (passwordInput.type === "password") {
      passwordInput.type = "text";
      eyeIcon.classList.remove("bi-eye-slash");
      eyeIcon.classList.add("bi-eye");
    } else {
      passwordInput.type = "password";
      eyeIcon.classList.remove("bi-eye");
      eyeIcon.classList.add("bi-eye-slash");
    }
  }
</script>

    <div class="container-fluid position-relative d-flex p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-dark position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->

        <!-- Sidebar Start -->
       <?php include "sidebar.php"; ?>
        <!-- Sidebar End -->

        <!-- Content Start -->
        <div class="content">
            <!-- Navbar Start -->
            <?php include "navbar.php"; ?>
            <!-- Navbar End -->

            <!-- Sale & Revenue Start -->
            <div class="container-fluid pt-4 px-4">

                <div class="bg-secondary text-center rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h6 class="mb-0">Gallery</h6>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ModalForm">
                            Add Images
                        </button>
                        <button type="button" class="btn btn-primary" onclick="otpsend();">
                            Show Images
                        </button>
                        <div class="modal fade" id="ModalForm" tabindex="-1" aria-labelledby="ModalFormLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                              <div class="modal-content bg-secondary text-white">
                                <div class="modal-header">
                                  <h5 class="modal-title text-white">Add Images</h5>
                                  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                  <form action="" method="post" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label for="exampleInputEmail1" class="form-label">Upload Image</label>
                                        <input type="file" name="image" class="form-control">
                                    </div>
                                </div>
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
                        $s="select * from tbl_image where email='$email'";
                        $q=mysqli_query($con,$s);
                        $s1="select mobile from tbl_register where email='$email'";
                        $q1=mysqli_query($con,$s1);
                        $r1=mysqli_fetch_array($q1);
                        $m=$r1[0];
                        if(mysqli_num_rows($q)>0){
                            while($r=mysqli_fetch_array($q)){
                                if( $ot==1){
                                    $keys = generateKeys();
                                    $decryptedtext = decrypt($r[2], $keys['private']);
                                    ?>
                                    <div class="col-lg-4 col-md-12 mb-4 mb-lg-0">
                                        <div class="bg-image hover-overlay ripple shadow-1-strong rounded" data-ripple-color="light">
                                          <img src="<?php echo $decryptedtext; ?>" class="img-fluid" style="object-fit: cover; height: 300px;" />
                                          <a href="#!" data-mdb-toggle="modal" data-mdb-target="#exampleModal1">
                                            <div class="mask" style="background-color: rgba(251, 251, 251, 0.2);"></div>
                                          </a>
                                        </div>

                                        <a href="removeimage.php?id=<?php echo $r[0]; ?>" onclick="return confirm('are you sure want to delete');"><button type="button" class="btn btn-square btn-danger m-2"><i class="fa fa-trash"></i></button></a>
                                        <button type="button" class="btn btn-square btn-primary m-2" onclick="otpsend1('<?php echo $r[2]; ?>');"><i class="fa fa-download"></i></button>
                                    </div>
                                    <?php
                                } else {
                                    ?>
                                    <div class="col-lg-4 col-md-12 mb-4 mb-lg-0">
                                        <div class="bg-image hover-overlay ripple shadow-1-strong rounded" data-ripple-color="light">
                                          <img src="uploads/images.jpg" class="w-100" />
                                          <a href="#!" data-mdb-toggle="modal" data-mdb-target="#exampleModal1">
                                            <div class="mask" style="background-color: rgba(251, 251, 251, 0.2);"></div>
                                          </a>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>

<?php
if(isset($_POST['add'])){
  if (isset($_FILES['image'])) {
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
        
        $s="insert into tbl_image(email,path) values('$email','$ciphertext')";
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
    }
  }
}
?>

            <!-- Widgets End -->
<br><br><br><br><br><br><br>

            <!-- Footer Start -->
           <?php include "footer.php"; ?>
            <!-- Footer End -->
        </div>
        <!-- Content End -->

        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>
   
    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/chart/chart.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>
</html>
