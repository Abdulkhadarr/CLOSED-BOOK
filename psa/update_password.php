<?php
include "con.php";
session_start();

// Show PHP errors (for debugging)
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Closed Book - Update Password</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
<div class="container-fluid position-relative d-flex p-0">
    <!-- Sidebar -->
    <?php include "sidebar.php"; ?>

    <!-- Content -->
    <div class="content">
        <!-- Navbar -->
        <?php include "navbar.php"; ?>

        <!-- Update Password -->
        <div class="container-fluid pt-4 px-4">
            <div class="row g-4">
                <div class="col-sm-12 col-xl-6">
                    <div class="bg-secondary rounded h-100 p-4">
                        <h6 class="mb-4">Update Login Password</h6>
                        <form action="" method="post">
                            <div class="mb-3">
                                <label class="form-label">Old Password</label>
                                <input type="password" class="form-control" name="old_password" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">New Password</label>
                                <input type="password" class="form-control" name="new_password" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" name="confirm_password" required>
                            </div>
                            <button type="submit" class="btn btn-primary" name="update_pass">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

<?php
if(isset($_POST['update_pass'])){
    $email = $_SESSION['email'] ?? null;
    $old = trim($_POST['old_password']);
    $new = trim($_POST['new_password']);
    $confirm = trim($_POST['confirm_password']);

    if(!$email){
        echo "<script>alert('Session expired. Please log in again.'); window.location='login.php';</script>";
        exit;
    }

    // Step 1: Verify old password
    $check = mysqli_query($con, "SELECT * FROM tbl_login WHERE email='$email' AND password='$old'");
    if(mysqli_num_rows($check) > 0){

        // Step 2: Prevent same password
        if($old === $new){
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
            Swal.fire({
                icon: 'warning',
                title: 'Same Password Not Allowed',
                text: 'Your new password cannot be the same as the old password.'
            }).then(() => { window.location = 'update_password.php'; });
            </script>";
            exit;
        }

        // Step 3: Confirm match
        if($new !== $confirm){
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
            Swal.fire({
                icon: 'warning',
                title: 'Mismatch!',
                text: 'New password and confirmation do not match.'
            });
            </script>";
            exit;
        }

        // Step 4: Prevent duplicate across tbl_login only (excluding current user)
        $dupCheck = mysqli_query($con, "SELECT email FROM tbl_login WHERE password='$new' AND email!='$email'");
        if($dupCheck && mysqli_num_rows($dupCheck) > 0){
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
            Swal.fire({
                icon: 'error',
                title: 'Password Exists',
                text: 'This password is already used by another account. Please choose another one.'
            }).then(() => { window.location = 'update_password.php'; });
            </script>";
            exit;
        }

        // Step 5: Update
        $update = mysqli_query($con, "UPDATE tbl_login SET password='$new' WHERE email='$email'");
        if($update){
            session_unset();
            session_destroy();

            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
            Swal.fire({
                icon: 'success',
                title: 'Updated!',
                text: 'Your login password has been updated. Please log in again.'
            }).then(() => {
                window.location = 'login.php';
            });
            </script>";
        }

    } else {
        // Wrong old password
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
        Swal.fire({
            icon: 'error',
            title: 'Wrong Password',
            text: 'Your old password is incorrect.'
        });
        </script>";
    }
}
?>
        <br><br><br>
        <?php include "footer.php"; ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>

