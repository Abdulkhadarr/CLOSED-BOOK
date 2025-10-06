<?php
// Step 1: Generate and send the OTP
$otp = mt_rand(1000, 9999); // Generate a 4-digit random number
// Send the OTP to the user's mobile phone or email address
// You can use third-party services like Twilio, Nexmo, or SendGrid to send SMS or email

// Step 2: Ask the user to enter the OTP
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $entered_otp = $_POST['otp'];
  
  // Step 3: Validate the OTP entered by the user
  if ($otp == $entered_otp) {
    // Step 4: Log the user in
    // Redirect the user to the dashboard or home page
    header('Location: dashboard.php');
    exit();
  } else {
    // Display an error message
    echo 'Invalid OTP. Please try again.';
  }
}


?>