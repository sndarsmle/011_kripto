<?php
// Fungsi untuk enkripsi file menggunakan AES
function aesEncryptFile($filePath, $key) {
    $key = substr(hash('sha256', $key, true), 0, 32); // Kunci 32 byte
    $iv = random_bytes(16); // IV 16 byte
    $fileContent = file_get_contents($filePath);
    $encryptedData = openssl_encrypt($fileContent, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
    $finalData = $iv . $encryptedData;
    return $finalData;
}

function aesDecryptFile($filePath, $key) {
    $key = substr(hash('sha256', $key, true), 0, 32); // Kunci 32 byte
    $fileContent = file_get_contents($filePath);
    $iv = substr($fileContent, 0, 16); // Ekstrak IV
    $encryptedData = substr($fileContent, 16); // Ekstrak data terenkripsi
    $decryptedData = openssl_decrypt($encryptedData, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
    return $decryptedData;
}
?>
