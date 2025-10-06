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
          <script>  function generatePassword(length) {
    var lowercase = "abcdefghijklmnopqrstuvwxyz";
    var uppercase = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    var digits = "0123456789";
    var specials = "!@#$%^&*()_+~`|}{[]\:;?><,./-=";
    var password = "";

    // Generate one character of each type
    password += lowercase.charAt(Math.floor(Math.random() * lowercase.length));
    password += uppercase.charAt(Math.floor(Math.random() * uppercase.length));
    password += digits.charAt(Math.floor(Math.random() * digits.length));
    password += specials.charAt(Math.floor(Math.random() * specials.length));

    // Generate remaining characters
    var remainingChars = length - 4;
    var allChars = lowercase + uppercase + digits + specials;
    for (var i = 0; i < remainingChars; i++) {
        var randomIndex = Math.floor(Math.random() * allChars.length);
        password += allChars[randomIndex];
    }

    // Shuffle the characters
    password = password.split('').sort(function(){return 0.5-Math.random()}).join('');

    document.getElementById("password").value=password;
}

// Example usage:
//var password = generatePassword(12);
//console.log(password); // Output: "d0$AaF!tGm#I"


function copyToClipboard() {
    var copyText = document.getElementById("password");
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    document.execCommand("copy");
    //alert("Copied the password: " + copyText.value);
}


  </script>

            <!-- Sale & Revenue Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="row g-4">
            <div class="col-sm-12 col-xl-6">
                        <div class="bg-secondary rounded h-100 p-4">
                            <h6 class="mb-4">Generate  Password</h6>
                           
                            <form action="" method="post">
                                <div class="mb-3">
                                    <label for="exampleInputEmail1" class="form-label">Password</label>
                                    <input type="text" class="form-control" id="password"
                                        aria-describedby="emailHelp" name="password1">
                                    <div id="emailHelp" class="form-text">We'll never share your password with anyone else.
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                <button type="button" onclick="generatePassword(12)" class="btn btn-primary" >Generate</button></div>
                                <button type="button"  onclick="copyToClipboard()" class="btn btn-success">Copy</button>
                            </form>
                        </div>
                    </div>
</div></div>

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