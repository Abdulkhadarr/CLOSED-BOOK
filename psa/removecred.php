<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "con.php";
session_start();

if (isset($_GET['email']) && isset($_GET['cid'])) {
    $email = mysqli_real_escape_string($con, $_GET['email']);
    $cid   = mysqli_real_escape_string($con, $_GET['cid']);

    $sql = "DELETE FROM tbl_cred WHERE email='$email' AND cid='$cid'";
    $q   = mysqli_query($con, $sql);

    if ($q) {
        // Redirect back with success flag
        header("Location: adddetail.php?deleted=1");
        exit;
    } else {
        // Redirect back with error flag
        header("Location: adddetail.php?delerr=1");
        exit;
    }
} else {
    // Invalid request → redirect back safely
    header("Location: adddetail.php?delerr=1");
    exit;
}
?>
