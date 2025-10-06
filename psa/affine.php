<?php
function affineEncrypt($text, $a, $b) {
    $result = '';
    $text = strtoupper($text);
    for ($i = 0; $i < strlen($text); $i++) {
        $char = $text[$i];
        if (ctype_alpha($char)) {
            $ascii = ord($char) - 65;
            $newAscii = ($a * $ascii + $b) % 26;
            $newChar = chr($newAscii + 65);
            $result .= $newChar;
        } else {
            $result .= $char;
        }
    }
    return $result;
}

function affineDecrypt($text, $a, $b) {
    $result = '';
    $aInverse = modInverse($a, 26);
    $text = strtoupper($text);
    for ($i = 0; $i < strlen($text); $i++) {
        $char = $text[$i];
        if (ctype_alpha($char)) {
            $ascii = ord($char) - 65;
            $newAscii = ($aInverse * ($ascii - $b + 26)) % 26;
            $newChar = chr($newAscii + 65);
            $result .= $newChar;
        } else {
            $result .= $char;
        }
    }
    return $result;
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
/*
$plaintext = 'HELLO WORLD';
$a = 5;
$b = 8;

$ciphertext = affineEncrypt($plaintext, $a, $b);
echo "Ciphertext: " . $ciphertext . "\n";

$decryptedtext = affineDecrypt($ciphertext, $a, $b);
echo "Decrypted text: " . $decryptedtext . "\n";
*/
?>
