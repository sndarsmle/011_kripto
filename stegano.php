<?php

function encryptMessage($message, $key) {
    $method = 'AES-256-CBC';
    $key = hash('sha256', $key, true);
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));
    $encrypted = openssl_encrypt($message, $method, $key, OPENSSL_RAW_DATA, $iv);
    return base64_encode($iv . $encrypted);
}

function decryptMessage($encryptedMessage, $key) {
    $method = 'AES-256-CBC';
    $key = hash('sha256', $key, true);
    $data = base64_decode($encryptedMessage);
    $iv = substr($data, 0, openssl_cipher_iv_length($method));
    $encrypted = substr($data, openssl_cipher_iv_length($method));
    return openssl_decrypt($encrypted, $method, $key, OPENSSL_RAW_DATA, $iv);
}

function hideMessageInImage($coverImagePath, $message, $outputImagePath) {
    $image = imagecreatefromstring(file_get_contents($coverImagePath));
    if (!$image) {
        return false;
    }

    $binaryMessage = '';
    foreach (str_split($message) as $char) {
        $binaryMessage .= str_pad(decbin(ord($char)), 8, '0', STR_PAD_LEFT);
    }
    $binaryMessage .= str_repeat('0', 8); // Terminator

    $width = imagesx($image);
    $height = imagesy($image);
    $pixelIndex = 0;

    for ($y = 0; $y < $height; $y++) {
        for ($x = 0; $x < $width; $x++) {
            if ($pixelIndex < strlen($binaryMessage)) {
                $rgb = imagecolorat($image, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;

                $b = ($b & 0xFE) | $binaryMessage[$pixelIndex++];
                $newColor = imagecolorallocate($image, $r, $g, $b);
                imagesetpixel($image, $x, $y, $newColor);
            }
        }
    }

    $success = imagepng($image, $outputImagePath);
    imagedestroy($image);
    return $success;
}

function extractMessageFromImage($steganographyImagePath) {
    $image = imagecreatefromstring(file_get_contents($steganographyImagePath));
    if (!$image) {
        return false;
    }

    $width = imagesx($image);
    $height = imagesy($image);
    $binaryMessage = '';

    for ($y = 0; $y < $height; $y++) {
        for ($x = 0; $x < $width; $x++) {
            $rgb = imagecolorat($image, $x, $y);
            $b = $rgb & 0xFF;
            $binaryMessage .= ($b & 1);
        }
    }

    $message = '';
    for ($i = 0; $i < strlen($binaryMessage); $i += 8) {
        $char = chr(bindec(substr($binaryMessage, $i, 8)));
        if ($char === "\0") {
            break;
        }
        $message .= $char;
    }

    imagedestroy($image);
    return $message;
}
?>
