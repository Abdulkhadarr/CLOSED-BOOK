<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "con.php";
session_start();
include "hash.php"; // hashing with salt

$email = $_SESSION['email'] ?? '';
$alert = '';

$check = mysqli_query($con, "SELECT mpassword, salt FROM tbl_master WHERE email='$email'");
if (!$email || mysqli_num_rows($check) === 0) {
  $alert = "
  <script>
    Swal.fire({
      icon: 'warning',
      title: 'No Master Password Found',
      text: 'Please set one first.'
    }).then(() => { window.location = 'master.php'; });
  </script>";
} else {
  $current = mysqli_fetch_assoc($check);
  $storedHashHex = $current['mpassword'];
  $storedSalt = $current['salt'];

  if (isset($_POST['update'])) {
    $oldPass = $_POST['opassword'] ?? '';
    $newPass = $_POST['password'] ?? '';

    // Verify old password
    $oldHashHex = bin2hex(mySecureHash($oldPass, $storedSalt));

    if (!hash_equals($storedHashHex, $oldHashHex)) {
      $alert = "
      <script>
        Swal.fire({
          icon: 'error',
          title: 'Incorrect Password',
          text: 'The old master password you entered is wrong.'
        }).then(() => { window.location = 'master1.php'; });
      </script>";
    } elseif ($newPass === $oldPass) {
      $alert = "
      <script>
        Swal.fire({
          icon: 'warning',
          title: 'Same Password Not Allowed',
          text: 'Master password cannot be the same.'
        }).then(() => { window.location = 'master1.php'; });
      </script>";
    } else {
      // Check duplicates across other users
      $dup = false;
      $others = mysqli_query($con, "SELECT email, mpassword, salt FROM tbl_master WHERE email <> '$email'");
      if ($others) {
        while ($row = mysqli_fetch_assoc($others)) {
          $candidateHex = bin2hex(mySecureHash($newPass, $row['salt']));
          if (hash_equals($row['mpassword'], $candidateHex)) {
            $dup = true;
            break;
          }
        }
      }

      if ($dup) {
        $alert = "
        <script>
          Swal.fire({
            icon: 'error',
            title: 'Password Exists',
            text: 'This master password is already used by another account.'
          }).then(() => { window.location = 'master1.php'; });
        </script>";
      } else {
        $newSaltHex = bin2hex(random_bytes(32));
        $newHashHex = bin2hex(mySecureHash($newPass, $newSaltHex));

        $sql = "UPDATE tbl_master SET mpassword='$newHashHex', salt='$newSaltHex' WHERE email='$email'";
        $ok = mysqli_query($con, $sql);

        if ($ok && mysqli_affected_rows($con) > 0) {
          $alert = "
          <script>
            Swal.fire({
              icon: 'success',
              title: 'Master Password Updated Successfully',
              text: 'Your master password has been updated successfully.'
            }).then(() => { window.location = 'index1.php'; });
          </script>";
        } else {
          $alert = "
          <script>
            Swal.fire({
              icon: 'error',
              title: 'Database Error',
              text: 'Something went wrong. Please try again.'
            }).then(() => { window.location = 'master1.php'; });
          </script>";
        }
      }
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Closed Book</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?= $alert ?>

<div class="container-fluid position-relative d-flex p-0">
  <?php include "sidebar.php"; ?>
  <div class="content">
    <?php include "navbar.php"; ?>
    <div class="container-fluid pt-4 px-4">
      <div class="row g-4">
        <div class="col-sm-12 col-xl-6">
          <div class="bg-secondary rounded h-100 p-4">
            <h6 class="mb-4 text-white">Update Master Password</h6>
            <form action="" method="post">
              <div class="mb-3">
                <label class="form-label text-white">Old Password</label>
                <div class="input-group">
                  <input type="password" class="form-control" id="oldpass" name="opassword" required>
                  <button class="btn btn-outline-primary" type="button" onclick="toggleVisibility('oldpass','eye1')">
                    <i class="bi bi-eye-slash" id="eye1"></i>
                  </button>
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label text-white">New Password</label>
                <div class="input-group">
                  <input type="password" class="form-control" id="newpass" name="password" required>
                  <button class="btn btn-outline-primary" type="button" onclick="toggleVisibility('newpass','eye2')">
                    <i class="bi bi-eye-slash" id="eye2"></i>
                  </button>
                </div>
              </div>
              <button type="submit" class="btn btn-primary" name="update">Update</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <?php include "footer.php"; ?>
  </div>
</div>

<script>
function toggleVisibility(inputId, eyeId) {
  const input = document.getElementById(inputId);
  const eye = document.getElementById(eyeId);
  if (input.type === "password") {
    input.type = "text";
    eye.classList.replace("bi-eye-slash", "bi-eye");
  } else {
    input.type = "password";
    eye.classList.replace("bi-eye", "bi-eye-slash");
  }
}
</script>

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>
