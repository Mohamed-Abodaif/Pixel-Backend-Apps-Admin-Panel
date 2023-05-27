<?php
namespace App\Classes;

use Exception;
use PDO;

class phpCipher {
    private static $method = 'AES-256-CBC'; //Name of OpenSSL Cipher 
    private static $key_length = 32; //128 bits

    static function encrypt(string $key, string $data) : string
    {
        try {
            if(strlen($data) == 0 || $data == null) return "";
            $secret = phpCipher::RandomString(32);
            $iv_key = openssl_random_pseudo_bytes(16, $secret);
            if (strlen($key) < phpCipher::$key_length) {
                $key = str_pad($key, phpCipher::$key_length, "0"); //0 pad to len 16
            } else if (strlen($key) > phpCipher::$key_length) {
                $key = substr($key, 0, phpCipher::$key_length); //truncate to 16 bytes
            }
            $encData = base64_encode(openssl_encrypt($data, phpCipher::$method, $key, OPENSSL_RAW_DATA, $iv_key));
            $encodedIV = base64_encode($iv_key);
            return  $encData.":".$encodedIV;
        } catch (Exception $e) {}
        return "";
    }


    static function decrypt(string $key, string $data) : string
    {
        try {
            if(strlen($data) == 0 || $data == null) return "";
            if (strlen($key) < phpCipher::$key_length) {
                $key = str_pad("$key", phpCipher::$key_length, "0"); //0 pad to len 16
            } else if (strlen($key) > phpCipher::$key_length) {
                $key = substr($key, 0, phpCipher::$key_length); //truncate to 16 bytes
            }
            $parts = explode(':', $data); //Separate Encrypted data from iv.
            $encStr = base64_decode($parts[0]);
            $binIv = hex2bin($parts[1]);
            //echo gettype($binIv);
            return openssl_decrypt($encStr, phpCipher::$method, $key, OPENSSL_RAW_DATA, $binIv);
        } catch (Exception $e) {
            echo "errpr";
            echo $e;
        }
        return "ddd";
    }
    
    //random string bydefault 32 chars
    private static function RandomString(int $length = 32) : string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxy/_+.#-()=@&zABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}
