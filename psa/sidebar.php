<div class="sidebar pe-4 pb-3">
    <nav class="navbar bg-secondary navbar-dark">
        <a href="index.html" class="navbar-brand mx-4 mb-3">
            <h3 class="text-primary"><i class="fa fa-lock me-2"></i>Closed Book</h3>
        </a>
        <div class="d-flex align-items-center ms-4 mb-4">
            <div class="position-relative">
                <img class="rounded-circle" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
            </div>
            <div class="ms-3">
                <h6 class="mb-0">
                    <?php 
                        echo $em = $_SESSION['email']; 
                        $s = "SELECT name FROM tbl_register WHERE email='$em'";
                        $q = mysqli_query($con, $s);
                        $r = mysqli_fetch_array($q);
                    ?>
                </h6>
                <span><?php echo $r[0]; ?></span>
            </div>
        </div>

        <div class="navbar-nav w-100">
            <a href="index1.php" class="nav-item nav-link active">
                <i class="fa fa-tachometer-alt me-2"></i>Dashboard
            </a>

            <!-- Master Password Dropdown -->
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="far fa-file-alt me-2"></i>Master Password
                </a>
                <div class="dropdown-menu bg-transparent border-0">
                    <a href="master.php" class="dropdown-item">Add</a>
                    <?php
                    $check = mysqli_query($con, "SELECT * FROM tbl_master WHERE email='$em'");
                    if (mysqli_num_rows($check) == 0) {
                        // No master password → Show alert instead of update
                        echo "
                        <a href='#' class='dropdown-item' onclick=\"Swal.fire({
                            icon: 'warning',
                            title: 'No Master Password Found',
                            text: 'You don\\'t have a master password to update. Please set one first.'
                        }).then(() => { window.location = 'master.php'; });\">Update</a>";
                    } else {
                        // Already has one → Go to update page
                        echo "<a href='master1.php' class='dropdown-item'>Update</a>";
                    }
                    ?>
                </div>
            </div>

            <a href="pg.php" class="nav-item nav-link"><i class="fa fa-th me-2"></i>GENR Password</a>
            <a href="adddetail.php" class="nav-item nav-link"><i class="fa fa-keyboard me-2"></i>Add Credentials</a>
            <a href="image.php" class="nav-item nav-link"><i class="fa fa-images me-2"></i>Image</a>
            <a href="document.php" class="nav-item nav-link"><i class="fa fa-file me-2"></i>Document</a>
            <a href="video.php" class="nav-item nav-link"><i class="fa fa-video me-2"></i>Video</a>
        </div>
    </nav>
</div>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
