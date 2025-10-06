<?php

function encrypt($data, $key) {
    // Generate a random initialization vector (IV)
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    
    // Encrypt the data using AES-256-CBC with the provided key and IV
    $encrypted = openssl_encrypt($data, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
    
    // Combine the encrypted data and IV into a single string
    $result = base64_encode($iv . $encrypted);
    
    return $result;
  }
  function decrypt($data, $key) {
    // Decode the base64-encoded string
    $data = base64_decode($data);
    
    // Extract the IV and encrypted data from the string
    $iv = substr($data, 0, openssl_cipher_iv_length('aes-256-cbc'));
    $encrypted = substr($data, openssl_cipher_iv_length('aes-256-cbc'));
    
    // Decrypt the data using AES-256-CBC with the provided key and IV
    $decrypted = openssl_decrypt($encrypted, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
    
    return $decrypted;
  }
  $key = openssl_random_pseudo_bytes(32);
    
?>