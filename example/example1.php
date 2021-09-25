<?php

require __DIR__ . '/../vendor/autoload.php';

// _create_rsa(__DIR__ . '/priv_key', __DIR__ . '/pub_key');

// $count_char = 501;
$count_char = 1024 * 5;

$chars = [];
for ($i = 0; $i < $count_char; $i++) {
    $chars[] = 'Y';
}
$text = implode('', $chars);

$public_key = file_get_contents(__DIR__ . '/pub_key');
$encrypted_text = _big_encrypt_rsa($public_key, $text);
var_dump($encrypted_text);

$private_key = file_get_contents(__DIR__ . '/priv_key');
$plain_text = _big_decrypt_rsa($private_key, $encrypted_text);
var_dump($plain_text);
