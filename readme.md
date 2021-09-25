# PHP RSA

### Features:
- create public & private key
- encrypt & decrypt
- encrypt long text
- decrypt long text

### Provide Methods:

```php
_create_rsa($path_private_key, $path_public_key);

_encrypt_rsa($public_key, $text);

_decrypt_rsa($private_key, $encrypted);

_big_encrypt_rsa($public_key, $text);

_big_decrypt_rsa($private_key, $encrypted_text);
```

### References:
How to encrypt data in php using Public/Private keys?
https://stackoverflow.com/questions/4629537/how-to-encrypt-data-in-php-using-public-private-keys/35289156#35289156
