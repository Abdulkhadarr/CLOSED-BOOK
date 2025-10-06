<?php
include "con.php";
session_start();
include "rsa.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Closed Book</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Roboto:wght@500;700&display=swap" rel="stylesheet"> 
    
    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid position-relative d-flex p-0">
        <!-- Sidebar -->
        <?php include "sidebar.php"; ?>

        <!-- Content -->
        <div class="content">
            <!-- Navbar -->
            <?php include "navbar.php"; ?>

            <!-- Profile Section -->
            <div class="container-fluid pt-4 px-4">
                <div class="row g-4">

                    <!-- Profile Update -->
                    <div class="col-sm-12 col-xl-6">
                        <div class="bg-secondary rounded h-100 p-4">
                            <h6 class="mb-4">Profile</h6>
                            <?php
                            $ss = $_SESSION['email'];
                            $s = "SELECT * FROM tbl_register WHERE email='$ss'";
                            $q = mysqli_query($con,$s);
                            $r = mysqli_fetch_array($q);
                            ?>
                            <form action="" method="post" id="profileForm">
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control" name="name" value="<?php echo $r['name']; ?>" required>
                                    <input type="hidden" name="id" value="<?php echo $r['uid']; ?>">
                                    <input type="hidden" name="old_name" value="<?php echo $r['name']; ?>">
                                    <input type="hidden" name="old_email" value="<?php echo $r['email']; ?>">
                                    <input type="hidden" name="old_mobile" value="<?php echo $r['mobile']; ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" value="<?php echo $r['email']; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Mobile</label>
                                    <input type="text" class="form-control" name="mobile" value="<?php echo $r['mobile']; ?>" pattern="[0-9]{10}" title="Enter a valid 10-digit mobile number" required>
                                </div>
                                <button type="submit" class="btn btn-primary" name="update">Update</button>
                            </form>
                        </div>
                    </div>

<?php
// Update profile info
if(isset($_POST['update'])){
    $id = $_POST['id'];
    $newName = trim($_POST['name']);
    $newEmail = trim($_POST['email']);
    $newMobile = trim($_POST['mobile']);

    $oldName = $_POST['old_name'];
    $oldEmail = $_POST['old_email'];
    $oldMobile = $_POST['old_mobile'];

    $currentEmail = $_SESSION['email'];

    // ✅ 1. Check if nothing changed
    if($newName === $oldName && $newEmail === $oldEmail && $newMobile === $oldMobile){
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js'></script>
        <script>
        swal({
            title: 'No changes detected',
            text: 'Update did not happen because you did not modify anything.',
            type: 'info'
        }).then(function() {
            window.location = 'profile.php';
        });
        </script>";
        exit();
    }

    // ✅ 2. If email not changed → update only name/mobile
    if($newEmail === $currentEmail){
        $s = "UPDATE tbl_register SET name='$newName', mobile='$newMobile' WHERE uid='$id'";
        $q = mysqli_query($con, $s);

        if($q){
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js'></script>
            <script>
            swal({
                title: 'SUCCESS!',
                text: 'Profile Updated!',
                type: 'success'
            }).then(function() {
                window.location = 'index1.php';
            });
            </script>";
        }
    } else {
        // ✅ 3. Email changed → check duplicates across both tables
        $dupCheck = mysqli_query($con, "
            SELECT email FROM tbl_register WHERE email='$newEmail' AND uid <> '$id'
            UNION
            SELECT email FROM tbl_login WHERE email='$newEmail'
        ");

        if(mysqli_num_rows($dupCheck) > 0){
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js'></script>
            <script>
            swal({
                icon: 'error',
                title: 'Duplicate Email',
                text: 'The email you entered is already registered. Please try another one.'
            }).then(function() {
                window.location = 'profile.php';
            });
            </script>";
        } else {
            // 🚀 Proceed with email update in transaction
            mysqli_begin_transaction($con);

            try {
                mysqli_query($con, "UPDATE tbl_register SET email='$newEmail', name='$newName', mobile='$newMobile' WHERE uid='$id'");
                mysqli_query($con, "UPDATE tbl_login SET email='$newEmail' WHERE email='$currentEmail'");
                mysqli_query($con, "UPDATE tbl_master SET email='$newEmail' WHERE email='$currentEmail'");
                mysqli_query($con, "UPDATE tbl_document SET email='$newEmail' WHERE email='$currentEmail'");
                mysqli_query($con, "UPDATE tbl_image SET email='$newEmail' WHERE email='$currentEmail'");
                mysqli_query($con, "UPDATE tbl_video SET email='$newEmail' WHERE email='$currentEmail'");
                mysqli_query($con, "UPDATE tbl_cred SET email='$newEmail' WHERE email='$currentEmail'");
                mysqli_query($con, "UPDATE tbl_lsession SET email='$newEmail' WHERE email='$currentEmail'");

                mysqli_commit($con);

                session_unset();
                session_destroy();

                echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js'></script>
                <script>
                swal({
                    title: 'Email Updated!',
                    text: 'Your email was updated successfully. Please login again with your new email.',
                    type: 'success'
                }).then(function() {
                    window.location = 'login.php';
                });
                </script>";

            } catch (Exception $e) {
                mysqli_rollback($con);

                echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js'></script>
                <script>
                swal({
                    title: 'Error',
                    text: 'Failed to update email. Please try again!',
                    type: 'error'
                }).then(function() {
                    window.location = 'profile.php';
                });
                </script>";
            }
        }
    }
}
?>

            <!-- Footer -->
            <?php include "footer.php"; ?>
        </div>
    </div>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
