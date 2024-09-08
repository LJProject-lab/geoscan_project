<?php
function encryptData($data) {
    $encryption_key = 'pCC0axLnQJpZv2NKV5WOZQp+roDG2Sf5Ero7m7ieCiY=';

    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $encrypted = openssl_encrypt($data, 'aes-256-cbc', $encryption_key, 0, $iv);
    return base64_encode($encrypted . '::' . $iv);
}

function decryptData($data) {
    $encryption_key = 'pCC0axLnQJpZv2NKV5WOZQp+roDG2Sf5Ero7m7ieCiY=';

    list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
    return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv);
}
