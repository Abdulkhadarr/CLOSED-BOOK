<?php include "con.php"; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>PSA</title>
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
    <script>
    function pcheck(){
        //alert("hai");
        p1=document.getElementById('pass').value;
        p2=document.getElementById('pass1').value;
        if(p1==p2){
            document.getElementById('x').innerHTML="<font color='green'>Password Matching....</font>";
             return true;           
        }else{
            document.getElementById('x').innerHTML="<font color='red'>Password not Matching....</font>";
             return false;
        }

    }
    </script>
    
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
       

        <!-- Sign Up Start -->
        <div class="container-fluid">
            <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
                <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                    <div class="bg-secondary rounded p-4 p-sm-5 my-4 mx-3">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <a href="index.html" class="">
                                <h3 class="text-primary"><i class="fa fa-lock me-2"></i>Closed Book</h3>
                            </a>
                            <h3>Sign Up</h3>
                        </div>
                        <form action="" method="post" onsubmit="return pcheck();">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="name"  placeholder="jhondoe" required>
                            <label for="floatingText">Name</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" name="email" id="floa" placeholder="name@example.com" required>
                            <label for="floatingInput">Email address</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="mobile" id="floatingInput" placeholder="Mobile number" required>
                            <label for="floatingInput">Mobile Number</label>
                        </div>
                        <div class="form-floating mb-4">
                            <input type="password" class="form-control" name="password" id="pass" placeholder="Password" required>
                            <label for="floatingPassword">Password</label>
                        </div>
                        <div class="form-floating mb-4">
                            <input type="text" class="form-control" id="pass1" onkeyup="return pcheck();" placeholder="Password" required>
                            <label for="floatingPassword1">Retype Password</label>
                            <div id="x"></div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                <label class="form-check-label" for="exampleCheck1">Check me out</label>
                            </div>
                            <a href="">Forgot Password</a>
                        </div>
                        <button type="submit" class="btn btn-primary py-3 w-100 mb-4" name="register"> Sign Up</button>
                        <p class="text-center mb-0">Already have an Account? <a href="index.php">Sign In</a></p>
                    </div>
</form>
                </div>
            </div>
        </div>
        <!-- Sign Up End -->
    </div>


    <?php
if(isset($_POST['register']))
{

    extract($_POST);
    $st=1;
    $s="insert into tbl_register(name,email,mobile) values ('$name','$email','$mobile')";
    $s1="insert into tbl_login values('$email','$password','$st')";
    $q=mysqli_query($con,$s);
    $q1=mysqli_query($con,$s1);
    if($q&&$q1){
        ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js"></script>  
<script>

swal({
    title: "SUCCESS!",
    text: "registered successfully!",
    type: "success"
}).then(function() {
    window.location = "login.php";
});
</script>

        <?php
    }else{
        ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js"></script>  
<script>

swal({
    title: "Error",
    text: "Something went wrong!",
    type: "error"
}).then(function() {
    window.location = "register.php";
});
</script>

        <?php


    }

}


?>
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