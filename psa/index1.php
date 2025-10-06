<?php
include "con.php";
session_start();
if(!isset($_SESSION['email'])){

header("Location:index.php");
}else{
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

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
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
                <div class="row g-4">
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-images fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2">Total Images</p>

                                <?php
                                $email=$_SESSION['email'];
                                $s="select count(*) from tbl_image where email='$email'";
                                $q=mysqli_query($con,$s);
                                if(mysqli_num_rows($q)>0){
                                $r=mysqli_fetch_array($q);
                                $x=$r[0];
                            }else{$x=0;}

                                ?>
                                <h6 class="mb-0"><?php echo $x;?></h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-file fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2">Total Documents</p>
                                <?php
                                $email=$_SESSION['email'];
                                $s="select count(*) from tbl_document where email='$email'";
                                $q=mysqli_query($con,$s);
                                if(mysqli_num_rows($q)>0){
                                $r=mysqli_fetch_array($q);
                                $x=$r[0];
                            }else{$x=0;}

                                ?>
                                <h6 class="mb-0"><?php echo $x; ?></h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-video fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2">Total Videos</p>
                                <?php
                                $email=$_SESSION['email'];
                                $s="select count(*) from tbl_video where email='$email'";
                                $q=mysqli_query($con,$s);
                                if(mysqli_num_rows($q)>0){
                                $r=mysqli_fetch_array($q);
                                $x=$r[0];
                            }else{$x=0;}

                                ?>
                                <h6 class="mb-0"><?php echo $x; ?></h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-eye fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2">Total Credentials</p>
                                <?php
                                $email=$_SESSION['email'];
                                $s="select count(*) from tbl_cred where email='$email'";
                                $q=mysqli_query($con,$s);
                                if(mysqli_num_rows($q)>0){
                                $r=mysqli_fetch_array($q);
                                $x=$r[0];
                            }else{$x=0;}

                                ?>
                                <h6 class="mb-0"><?php echo $x; ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Sale & Revenue End -->


            <!-- Sales Chart Start -->
           


            <!-- Recent Sales Start -->
           

           


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

<?php
}
?>