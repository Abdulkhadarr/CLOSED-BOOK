<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ob_start(); // allow safe header() redirects

$cid = '';
include "con.php";
session_start();
include "rsa.php";   // RSA still used for credentials encryption/decryption
include "hash.php";  // SHA-512 iterative hashing with salt

// Flags for UI state
$s   = 0;   // 0 => lock screen shows master pwd modal; 1/2 => unlocked or in OTP flows
$ot  = 0;   // 1 => OTP verified for a specific row
$suc = 0;   // 1 => show OTP modal after sending

// Current user + phone for OTP
$email = $_SESSION['email'] ?? '';
$m = '';
if ($email) {
    $q1 = mysqli_query($con, "SELECT mobile FROM tbl_register WHERE email='".mysqli_real_escape_string($con,$email)."' LIMIT 1");
    if ($q1 && mysqli_num_rows($q1) > 0) {
        $r1 = mysqli_fetch_assoc($q1);
        $m = $r1['mobile'] ?? '';
    }
}

// Parse GET flags
if (isset($_GET['s']))          $s = 1;
if (isset($_GET['cid']))        $cid = $_GET['cid'];
if (isset($_GET['ot']) && $cid !== '') { $ot = 1; $s = 2; $suc = 2; }
if (isset($_GET['suc']) && $cid !== '') { $suc = 1; $s = 2; }

// -------------------- OTP SEND (GET m & cid) --------------------
if (isset($_GET['m']) && isset($_GET['cid'])) {
    $phone = $_GET['m'];
    $cid   = $_GET['cid'];
    $s     = 2;

    $randomNumber = mt_rand(1000, 9999);
    $_SESSION['otp'] = $randomNumber;

    $fields = array(
        "variables_values" => $randomNumber,
        "route"            => "otp",
        "numbers"          => $phone,
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
        header("Location: adddetail.php?cid=".urlencode($cid)."&otp_err=1");
    } else {
        header("Location: adddetail.php?suc=1&cid=".urlencode($cid));
    }
    exit;
}

// -------------------- OTP SUBMIT (POST) --------------------
if (isset($_POST['otp1'])) {
    $cidPost = $_POST['cid'] ?? '';
    if (isset($_SESSION['otp']) && isset($_POST['otp']) && $_SESSION['otp'] == trim($_POST['otp'])) {
        header("Location: adddetail.php?ot=1&cid=".urlencode($cidPost)."&otp_ok=1");
    } else {
        header("Location: adddetail.php?otp_bad=1");
    }
    exit;
}

// -------------------- MASTER PASSWORD VERIFY (POST) --------------------
if (isset($_POST['verify'])) {
    if (!$email) {
        header("Location: login.php");
        exit;
    }
    $plain = $_POST['password'] ?? '';

    $res = mysqli_query($con, "SELECT mpassword, salt FROM tbl_master WHERE email='".mysqli_real_escape_string($con,$email)."' LIMIT 1");
    if ($res && mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        $storedHashHex = $row['mpassword'];
        $storedSalt    = $row['salt'];
        $inputHashHex  = bin2hex(mySecureHash($plain, $storedSalt));

        if (hash_equals($storedHashHex, $inputHashHex)) {
            header("Location: adddetail.php?s=1&mp_ok=1");
        } else {
            header("Location: adddetail.php?mp_bad=1");
        }
        exit;
    } else {
        header("Location: master.php?need_master=1");
        exit;
    }
}

// -------------------- ADD CREDENTIALS (POST) --------------------
if (isset($_POST['add'])) {
    if (!$email) {
        header("Location: login.php");
        exit;
    }

    $sname    = trim($_POST['sname']    ?? '');
    $username = trim($_POST['username'] ?? '');
    $pwd      = trim($_POST['password'] ?? '');

    $keys    = generateKeys();
    $cipherU = encrypt($username, $keys['public']);
    $cipherP = encrypt($pwd,      $keys['public']);

    $sql = "INSERT INTO tbl_cred(email, sname, username, password)
            VALUES ('".mysqli_real_escape_string($con, $email)."',
                    '".mysqli_real_escape_string($con, $sname)."',
                    '".mysqli_real_escape_string($con, $cipherU)."',
                    '".mysqli_real_escape_string($con, $cipherP)."')";
    $ok = mysqli_query($con, $sql);

    if ($ok) {
        header("Location: adddetail.php?added=1");
    } else {
        header("Location: adddetail.php?adderr=1");
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Closed Book - Credentials</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<?php
// ---------- flash alerts (after redirect) ----------
if (isset($_GET['mp_ok'])) {
    echo "<script>Swal.fire({icon:'success',title:'Unlocked',text:'Master password verified'});</script>";
}
if (isset($_GET['mp_bad'])) {
    echo "<script>
      $(function(){
        Swal.fire({
          icon:'error',
          title:'Incorrect Master Password',
          text:'Please try again'
        }).then(() => {
          $('#myModal1').modal('show');
        });
      });
    </script>";
}
if (isset($_GET['added'])) {
    echo "<script>
      $(function(){
        Swal.fire({
          icon:'success',
          title:'Details Added!',
          text:'Your new credentials have been saved.'
        }).then(() => {
          $('#myModal1').modal('show');
        });
      });
    </script>";
}
if (isset($_GET['adderr'])) {
    echo "<script>Swal.fire({icon:'error',title:'Add failed',text:'Something went wrong.'});</script>";
}
if (isset($_GET['deleted'])) {
    echo "<script>
      $(function(){
        Swal.fire({
          icon:'success',
          title:'Details Deleted!',
          text:'The credential has been removed successfully.'
        }).then(() => {
          $('#myModal1').modal('show');
        });
      });
    </script>";
}
if (isset($_GET['delerr'])) {
    echo "<script>
      $(function(){
        Swal.fire({
          icon:'error',
          title:'Delete failed',
          text:'Something went wrong while deleting!'
        }).then(() => {
          $('#myModal1').modal('show');
        });
      });
    </script>";
}
if (isset($_GET['otp_ok'])) {
    echo "<script>Swal.fire({icon:'success',title:'OTP verified'});</script>";
}
if (isset($_GET['otp_bad'])) {
    echo "<script>Swal.fire({icon:'error',title:'Incorrect OTP'});</script>";
}
if (isset($_GET['otp_err'])) {
    echo "<script>Swal.fire({icon:'error',title:'OTP send failed',text:'Please try again.'});</script>";
}
?>

<?php if ($suc == 1): ?>
<script>$(function(){ $('#myMod').modal('show'); });</script>
<?php endif; ?>

<!-- OTP Modal -->
<div class="modal fade" id="myMod" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-secondary text-white">
      <div class="modal-header">
        <h5 class="modal-title">Otp has been sent to registered mobile number</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form method="post">
          <div class="mb-3">
            <label class="form-label">Enter OTP</label>
            <input type="text" class="form-control" name="otp" required>
            <input type="hidden" id="m"   value="<?php echo htmlspecialchars($m); ?>">
            <input type="hidden" id="cid" name="cid" value="<?php echo htmlspecialchars($cid); ?>">
          </div>
          <div class="mb-3">
            <button type="button" class="btn btn-secondary" onclick="otpsend();">Resend OTP</button>
            <button type="submit" name="otp1" class="btn btn-primary">Submit OTP</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
function otpsend(){
  var m   = document.getElementById('m').value;
  var cid = document.getElementById('cid').value;
  window.location.href = "adddetail.php?m="+encodeURIComponent(m)+"&cid="+encodeURIComponent(cid);
}
</script>

<?php
// Auto-open master modal only on first visit
if ($s == 0 && !isset($_GET['mp_bad']) && !isset($_GET['added']) && !isset($_GET['deleted'])): ?>
<script>$(function(){ $('#myModal1').modal('show'); });</script>
<?php endif; ?>

<!-- Master Password Modal -->
<div class="modal fade" id="myModal1" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-secondary text-white">
      <div class="modal-header">
        <h5 class="modal-title">Master Password</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form method="post">
          <div class="mb-3">
            <label class="form-label">Enter Master Password</label>
            <div class="input-group">
              <input type="password" class="form-control" id="password-input" name="password" required>
              <button class="btn btn-outline-primary" type="button" onclick="togglePassword()">
                <i class="bi bi-eye-slash" id="eye-icon"></i>
              </button>
            </div>
          </div>
          <button type="submit" name="verify" class="btn btn-primary">Verify</button>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
function togglePassword(){
  const inp = document.getElementById('password-input');
  const eye = document.getElementById('eye-icon');
  if (inp.type === 'password'){ inp.type='text'; eye.classList.remove('bi-eye-slash'); eye.classList.add('bi-eye'); }
  else { inp.type='password'; eye.classList.remove('bi-eye'); eye.classList.add('bi-eye-slash'); }
}
</script>

<!-- PAGE CONTENT -->
<div class="container-fluid position-relative d-flex p-0">
  <?php include "sidebar.php"; ?>
  <div class="content">
    <?php include "navbar.php"; ?>

    <div class="container-fluid pt-4 px-4">
      <div class="bg-secondary text-center rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
          <h6 class="mb-0">Credentials</h6>
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ModalForm">Add Credentials</button>
        </div>

        <!-- Add Credentials Modal -->
        <div class="modal fade" id="ModalForm" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-secondary text-white">
              <div class="modal-header">
                <h5 class="modal-title">Add Credentials</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                <form method="post">
                  <div class="mb-3">
                    <input type="text" class="form-control" name="sname" placeholder="Site Name" required>
                  </div>
                  <div class="mb-3">
                    <input type="text" class="form-control" name="username" placeholder="Username" required>
                  </div>
                  <div class="mb-3">
                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                  </div>
                  <button type="submit" class="btn btn-primary" name="add">Add</button>
                </form>
              </div>
            </div>
          </div>
        </div>

        <!-- Credentials Table -->
        <div class="table-responsive mt-4">
          <table class="table text-start align-middle table-bordered table-hover mb-0">
            <thead>
              <tr class="text-white">
                <th>Site Name</th>
                <th>Username</th>
                <th>Password</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if ($email) {
                $q = mysqli_query($con, "SELECT * FROM tbl_cred WHERE email='".mysqli_real_escape_string($con,$email)."'");
                if ($q && mysqli_num_rows($q) > 0) {
                  while ($r = mysqli_fetch_assoc($q)) {
                    $rowCid    = $r['cid'] ?? $r['id'] ?? '';
                    $rowSname  = $r['sname'] ?? '';
                    $rowUserEn = $r['username'] ?? '';
                    $rowPassEn = $r['password'] ?? '';
                    $rowEmail  = $r['email'] ?? '';

                    if ($cid !== '' && $cid == $rowCid && $ot == 1) {
                      $keys = generateKeys();
                      $decUser = decrypt($rowUserEn, $keys['private']);
                      $decPass = decrypt($rowPassEn, $keys['private']);

                      echo "<tr>
                              <td>".htmlspecialchars($rowSname)."</td>
                              <td>".htmlspecialchars($decUser)."</td>
                              <td>".htmlspecialchars($decPass)."</td>
                              <td><button class='btn btn-secondary'>Watching</button></td>
                            </tr>";
                    } else {
                      $m_for_link   = htmlspecialchars($m);
                      $cid_for_link = htmlspecialchars($rowCid);
                      $del_email    = htmlspecialchars($rowEmail);

                      echo "<tr>
                              <td>".htmlspecialchars($rowSname)."</td>
                              <td>********</td>
                              <td>********</td>
                              <td>
                                <a href='adddetail.php?m={$m_for_link}&cid={$cid_for_link}' class='btn btn-primary'>Show</a>
                                <a href='removecred.php?email={$del_email}&cid={$cid_for_link}' class='btn btn-danger' onclick=\"return confirm('Are you sure want to delete?');\">Delete</a>
                              </td>
                            </tr>";
                    }
                  }
                } else {
                  echo "<tr><td colspan='4'>No credentials found.</td></tr>";
                }
              } else {
                echo "<tr><td colspan='4'>Please log in to view credentials.</td></tr>";
              }
              ?>
            </tbody>
          </table>
        </div>

      </div>
    </div>

    <?php include "footer.php"; ?>
  </div>
</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
