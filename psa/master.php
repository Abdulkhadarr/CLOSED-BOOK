<?php 
include "con.php";
session_start();
// include "rsa.php";   // old RSA kept as reference
include "hash.php";  // new SHA-512 hashing
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
</head>
<body>
<div class="container-fluid position-relative d-flex p-0">
    <?php include "sidebar.php"; ?>
    <div class="content">
        <?php include "navbar.php"; ?>

        <!-- Add Master Password -->
        <div class="container-fluid pt-4 px-4">
            <div class="row g-4">
                <div class="col-sm-12 col-xl-6">
                    <div class="bg-secondary rounded h-100 p-4">
                        <h6 class="mb-4">Master Password</h6>
                        <form action="" method="post">
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary" name="add">Add</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

<?php
if (isset($_POST['add'])) {
    $email = $_SESSION['email'];
    $password = $_POST['password'];

    // Check if user already has a master password
    $check = mysqli_query($con, "SELECT * FROM tbl_master WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
        Swal.fire({ icon: 'error', title: 'Error', text: 'You already have a Master Password. Please update it instead.' })
             .then(() => { window.location = 'master1.php'; });
        </script>";
        exit;
    }

    // New hashing with salt
    $salt = bin2hex(random_bytes(32));  
    $hashedPassword = bin2hex(mySecureHash($password, $salt));

    $sql = "INSERT INTO tbl_master(email, mpassword, salt) VALUES('$email', '$hashedPassword', '$salt')";
    $q = mysqli_query($con, $sql);

    if ($q) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
        Swal.fire({ icon: 'success', title: 'SUCCESS!', text: 'Master Password Added!' })
             .then(() => { window.location = 'index1.php'; });
        </script>";
    } else {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
        Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong, please try again!' })
             .then(() => { window.location = 'master.php'; });
        </script>";
    }
}
?>
<?php include "footer.php"; ?>
    </div>
</div>
</body>
</html>
