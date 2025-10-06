<?php
function mySecureHash($data, $salt) {
  $iterations = 100000; // number of iterations
  $hashLength = 64; // length of output hash
  
  // generate initial hash value
  $hash = hash('sha512', $salt.$data, true);
  
  // iterate hash function
  for ($i = 0; $i < $iterations; $i++) {
    $hash = hash('sha512', $hash.$salt.$data, true);
  }
  
  // truncate hash to desired length
  $hash = substr($hash, 0, $hashLength);
  
  return $hash;
}
?>