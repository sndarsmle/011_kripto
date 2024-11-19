<?php
/**
 * Algoritma Caesar Cipher
 * - Caesar Cipher menggeser setiap karakter dengan nilai tertentu (key).
 */

// Fungsi untuk enkripsi menggunakan Caesar Cipher
function caesarEncrypt($text, $shift) {
    $result = '';
    $shift = $shift % 26; 
    for ($i = 0; $i < strlen($text); $i++) {
        $char = $text[$i];

        if (ctype_upper($char)) {
            $result .= chr((ord($char) - 65 + $shift) % 26 + 65);
        }
        elseif (ctype_lower($char)) {
            $result .= chr((ord($char) - 97 + $shift) % 26 + 97);
        }
        else {
            $result .= $char;
        }
    }
    return $result;
}

// Fungsi untuk dekripsi menggunakan Caesar Cipher
function caesarDecrypt($text, $shift) {
    return caesarEncrypt($text, 26 - ($shift % 26));
}

/**
 * Algoritma AES Manual
 */

// Fungsi untuk padding agar panjang data sesuai blok 16 byte
function pkcs7Pad($data) {
    $blockSize = 16;
    $paddingSize = $blockSize - (strlen($data) % $blockSize);
    return $data . str_repeat(chr($paddingSize), $paddingSize);
}

// Fungsi untuk menghapus padding setelah dekripsi
function pkcs7Unpad($data) {
    $paddingSize = ord(substr($data, -1));
    return substr($data, 0, -$paddingSize);
}

// Fungsi untuk meng-enkripsi dengan AES manual
function aesEncrypt($key, $data) {
    $data = pkcs7Pad($data); // Padding data
    $key = str_pad($key, 16, "\0"); // Pastikan panjang kunci 16 byte
    $encrypted = '';

    // Proses blok per blok 16 byte
    for ($i = 0; $i < strlen($data); $i += 16) {
        $block = substr($data, $i, 16);
        $encrypted .= $block ^ $key; // XOR sederhana
    }

    return base64_encode($encrypted); // Encode hasil enkripsi
}

// Fungsi untuk mendekripsi dengan AES manual
function aesDecrypt($key, $data) {
    $data = base64_decode($data);
    $key = str_pad($key, 16, "\0"); // Pastikan panjang kunci 16 byte
    $decrypted = '';

    // Proses blok per blok 16 byte
    for ($i = 0; $i < strlen($data); $i += 16) {
        $block = substr($data, $i, 16);
        $decrypted .= $block ^ $key; // XOR sederhana
    }

    return pkcs7Unpad($decrypted); // Hapus padding setelah dekripsi
}

/**
 * Fungsi Super Enkripsi (Gabungan Caesar + AES)
 */

// Fungsi untuk enkripsi super (Caesar + AES)
function encryptMessage($message) {
    $caesarKey = 5; // Pergeseran untuk Caesar Cipher
    $aesKey = "secureaeskey123"; // Kunci untuk AES (16 karakter)

    // Langkah 1: Enkripsi dengan Caesar Cipher
    $caesarEncrypted = caesarEncrypt($message, $caesarKey);

    // Langkah 2: Enkripsi hasil Caesar dengan AES
    $finalEncrypted = aesEncrypt($aesKey, $caesarEncrypted);

    return $finalEncrypted; // Hasil akhir enkripsi
}

// Fungsi untuk dekripsi super (AES + Caesar)
function decryptMessage($encryptedMessage) {
    $caesarKey = 5; // Pergeseran yang sama dengan saat enkripsi
    $aesKey = "secureaeskey123"; // Kunci AES yang sama

    // Langkah 1: Dekripsi dengan AES
    $aesDecrypted = aesDecrypt($aesKey, $encryptedMessage);

    // Langkah 2: Dekripsi hasil AES dengan Caesar Cipher
    $finalDecrypted = caesarDecrypt($aesDecrypted, $caesarKey);

    return $finalDecrypted; // Hasil akhir dekripsi
}
?>
