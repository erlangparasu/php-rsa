<?php

function _create_rsa($path_private_key, $path_public_key)
{
    if (file_exists($path_private_key) && is_file($path_private_key)) {
        throw new Exception("Error private key file already exists", 1);
    }

    if (file_exists($path_public_key) && is_file($path_public_key)) {
        throw new Exception("Error public key file already exists", 1);
    }

    // Configuration settings for the key
    $config = array(
        'digest_alg' => 'sha512',
        'private_key_bits' => 4096,
        'private_key_type' => OPENSSL_KEYTYPE_RSA,
    );

    // Create the private and public key
    $res = openssl_pkey_new($config);

    // Extract the private key into $private_key
    openssl_pkey_export($res, $private_key);
    file_put_contents($path_private_key, $private_key);

    // Extract the public key into $public_key
    $public_key = openssl_pkey_get_details($res);
    $public_key = $public_key['key'];
    file_put_contents($path_public_key, $public_key);
}

function _encrypt_rsa($public_key, $text)
{
    // Something to encrypt
    // $text = 'This is the text to encrypt';
    // echo "This is the original text: $text\n\n";

    // Encrypt using the public key
    $success = openssl_public_encrypt($text, $encrypted, $public_key);

    if ($success) {
        $encrypted_hex = bin2hex($encrypted);

        // echo "This is the encrypted text: $encrypted_hex\n\n";

        return $encrypted_hex;
    }

    throw new Exception("Error encrypt failed", 1);
}

function _decrypt_rsa($private_key, $encrypted)
{
    // Decrypt the data using the private key
    $success = openssl_private_decrypt($encrypted, $decrypted, $private_key);

    // echo "This is the decrypted text: $decrypted\n\n";

    if ($success) {
        return $decrypted;
    }

    throw new Exception("Error decrypt failed", 1);
}

function _big_encrypt_rsa($public_key, $text)
{
    // $public_key = file_get_contents(__DIR__ . '/pub_key');
    $plain_chunks = mb_str_split($text, 400);

    $encrypted_chunks = [];
    foreach ($plain_chunks as $part) {
        $encrypted_hex = _encrypt_rsa($public_key, $part);
        $encrypted_chunks[] = $encrypted_hex;
    }
    $encrypted_text = implode('.', $encrypted_chunks);
    // var_dump(mb_strlen($encrypted_text));

    return $encrypted_text;
}

function _big_decrypt_rsa($private_key, $encrypted_text)
{
    // $private_key = file_get_contents(__DIR__ . '/priv_key');
    $encrypted_chunks = explode('.', $encrypted_text);
    $plain_chunks = [];
    foreach ($encrypted_chunks as $encrypted_hex) {
        $plain_part = _decrypt_rsa($private_key, hex2bin($encrypted_hex));
        $plain_chunks[] = $plain_part;
    }
    $plain_text = implode('', $plain_chunks);
    // var_dump(mb_strlen($plain_text));

    return $plain_text;
}
