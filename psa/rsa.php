<?php
function generateKeys() {
    $p = 41;
    $q = 43;
    $n = $p * $q;
    $phi = ($p - 1) * ($q - 1);
    $e = 79;
    while (gcd($e, $phi) != 1) {
        $e++;
    }
    $d = modInverse($e, $phi);
    return array('public' => array('e' => $e, 'n' => $n), 'private' => array('d' => $d, 'n' => $n));
}

function encrypt($plaintext, $publicKey) {
    $e = $publicKey['e'];
    $n = $publicKey['n'];
    $ciphertext = '';
    for ($i = 0; $i < strlen($plaintext); $i++) {
        $char = ord($plaintext[$i]);
        $cipher = bcpowmod($char, $e, $n);
        $ciphertext .= $cipher . ' ';
    }
    return trim($ciphertext);
}

function decrypt($ciphertext, $privateKey) {
    $d = $privateKey['d'];
    $n = $privateKey['n'];
    $plaintext = '';
    $ciphers = explode(' ', $ciphertext);
    foreach ($ciphers as $cipher) {
        if (!empty($cipher)) {
            $char = bcpowmod($cipher, $d, $n);
            $plaintext .= chr($char);
        }
    }
    return $plaintext;
}

function gcd($a, $b) {
    while ($b != 0) {
        $t = $b;
        $b = $a % $b;
        $a = $t;
    }
    return $a;
}

function modInverse($a, $m) {
    $a = $a % $m;
    for ($x = 1; $x < $m; $x++) {
        if (($a * $x) % $m == 1) {
            return $x;
        }
    }
    return 1;
}

// Example usage
//$keys = generateKeys();
//$plaintext = 'HELLO WORLD';
//$ciphertext = encrypt($plaintext, $keys['public']);
//echo "Ciphertext: " . $ciphertext . "\n";
//$ciphertext='651 1426 1226 56 762 1454';
//$decryptedtext = decrypt($ciphertext, $keys['private']);
//echo "Decrypted text: " . $decryptedtext . "\n";


?>