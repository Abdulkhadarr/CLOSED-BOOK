<?php
include "con.php";
session_start();
if(!isset($_SESSION['email'])){

    header("Location:404.html");
}else{

    function sendm($phone,$message){
//$phone = $_POST['phone_no'];
//$message = $_POST['message'];
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
      /*echo '<pre>';
      echo $response;*/
      //echo '<b>SMS sent successfully on the number: '.$phone.'</b>';
      //header("refresh:5;url=index.php");
      return true;

    }

}
    
//session_start();
if(isset($_GET['e'])){
$b=$_GET['b'];
$e=$_GET['e'];
$s="update tbl_login set usertype='$b' where email='$e'";
mysqli_query($con,$s);
header("Location:session.php");


}
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


            

            
            <script>
function checkAll() {
    var checkboxes = document.getElementsByName('mobile[]');
    var selectall = document.getElementById('selectall');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = selectall.checked;
    }
}
</script>

            <!-- Recent Sales Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="bg-secondary text-center rounded p-4">
                <form action="" method="post">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                       
                   
                        <div class="input-group">
                                <span class="input-group-text">Content</span>
                                <textarea class="form-control" aria-label="With textarea" name="content" required></textarea>
                            </div>
                        <button type="submit" class="btn btn-outline-primary m-2" name="message">send Message</button></a>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-start align-middle table-bordered table-hover mb-0">
                            <thead>
                                <tr class="text-white">
                                    
                                <th scope="col"><input class="form-check-input" type="checkbox" id="selectall" name="selectall" onclick="checkAll()"> <label for="selectall">Select All</label></th>
                                    
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Mobile</th>
                                    
                                </tr>
                            </thead>
                            <tbody>

                            <?php
                             $s="select name,email,mobile from tbl_register ";
                            $q=mysqli_query($con,$s);
                            while($r=mysqli_fetch_array($q)){

                                ?>
                                <tr>
                                <td><input class="form-check-input" type="checkbox" name="mobile[]" value="<?php echo $r[2]; ?>"></td>    
                                <td><?php echo $r[0]; ?></td><td><?php echo $r[1]; ?></td><td><?php echo $r[2]; ?></td>
                                

                             
                             

                                
                             


                                
                            </tr>
                                <?php


                            }

                            ?>
                                
                            </tbody>
                        </table>
                    </div>
                        </form>
                </div>
            </div>
            <!-- Recent Sales End -->


            <?php 
if(isset($_POST['message'])){

    if(isset($_POST['mobile'])){
        $c=$_POST['content'];
        $m=$_POST['mobile'];
        //print_r($m);
        //echo $c;
        for($i=0;$i<count($m);$i++){
            $xy=$m[$i];
            $t=sendm($m[$i],$c);
            date_default_timezone_set("Asia/Calcutta");
            $t=date('Y-m-d H:i:s');
            $s="insert into tbl_message (mobile,content,datet) values('$xy','$c','$t')";
            mysqli_query($con,$s);
        }
        if($t){
            ?>
        
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js"></script>  
    <script>
    
    swal({
        title: "SUCCESS!",
        text: "Message sent!",
        type: "success"
    }).then(function() {
        window.location = "sendmessage.php";
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
    text: "Please Select any user!",
    type: "error"
}).then(function() {
    window.location = "sendmessage.php";
});
</script>

        <?php
    }

}


?>


            <!-- Footer Start -->
         <?php include "footer.php";?>
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