<?php

namespace Hr\Password;

class Crypt
{
    const METHOD = 'aes-256-ctr';
    const KEY = 'ty532nms';

    /**
     * Encrypts password with specific method and key
     *
     * @param string  $password - plaintext password
     * @param boolean $encode   - base64-encoded if set to true
     * @return string
     */
    public static function encrypt($password, $encode = false)
    {
        $nonceSize = openssl_cipher_iv_length(self::METHOD);
        $nonce = openssl_random_pseudo_bytes($nonceSize);

        $ciphertext = openssl_encrypt(
            $password,
            self::METHOD,
            self::KEY,
            OPENSSL_RAW_DATA,
            $nonce
        );

        if ($encode) {
            return base64_encode($nonce . $ciphertext);
        }

        return $nonce . $ciphertext;
    }

    /**
     * Decrypts a password
     *
     * @param string  $password - ciphertext password
     * @param boolean $encoded  - are we expecting base64-encoded string?
     * @return string
     */
    public static function decrypt($password, $encoded = false)
    {
        if ($encoded) {
            $password = base64_decode($password, true);
            if ($password === false) {
                throw new Exception('Encryption error');
            }
        }

        $nonceSize = openssl_cipher_iv_length(self::METHOD);
        $nonce = mb_substr($password, 0, $nonceSize, '8bit');
        $ciphertext = mb_substr($password, $nonceSize, null, '8bit');

        $plaintext = openssl_decrypt(
            $ciphertext,
            self::METHOD,
            self::KEY,
            OPENSSL_RAW_DATA,
            $nonce
        );

        return $plaintext;
    }
}