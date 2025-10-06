<?php
function generatePassword($length) {
  $charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
  $password = "";
  for ($i = 0; $i < $length; $i++) {
    $randomIndex = rand(0, strlen($charset) - 1);
    $password .= $charset[$randomIndex];
  }
  return $password;
}

// Example usage
echo generatePassword(10); // Generates a password with 10 characters

function generatePassword1() {
    $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $lowercase = 'abcdefghijklmnopqrstuvwxyz';
    $specialChars = '!@#$%^&*()_+-={}[]|:;"<>,.?/';
    $minLength = 8;
    
    $password = '';
    $password .= substr(str_shuffle($uppercase), 0, 1);
    $password .= substr(str_shuffle($lowercase), 0, 1);
    $password .= substr(str_shuffle($specialChars), 0, 1);
    $password .= substr(str_shuffle($uppercase . $lowercase . $specialChars), 0, $minLength - 3);
    
    return str_shuffle($password);
  }
  
  // Example usage
  echo generatePassword1(); // Generates a password with minimum length of 8 characters and at least one uppercase, one lowercase, and one special character
  
?>