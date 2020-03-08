<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2019/5/25
 * Time: 20:28
 */

namespace Tools;


class EyRsa
{

    /**
     * 公钥加密
     * @param string $str 要加密的数据
     * @param string $public_key 公钥
     * @return string
     */
    static public function pubEncrypt($str, $public_key) {
        try{
            $encrypted = '';
            $pu_key = openssl_pkey_get_public($public_key);
            openssl_public_encrypt($str, $encrypted, $pu_key);//公钥加密

            $encrypted = base64_encode($encrypted);

            return $encrypted;
        }catch (\Exception $exception){
            throw new \RuntimeException($exception->getMessage());
        }
    }

    /**
     * 私钥解密
     * @param string $str 要解密的数据
     * @param string $private_key 私钥
     * @return string
     */
    static public function priDecrypt($str, $private_key) {

        $decrypted = '';
        $pi_key =  openssl_pkey_get_private($private_key);
        openssl_private_decrypt(base64_decode($str), $decrypted, $pi_key);//私钥解密

        return $decrypted;
    }

    /**
     * 私钥加密
     * @param string $str 要加密的数据
     * @param string $private_key 公钥
     * @return string
     */
    static public function priEncrypt($str, $private_key) {

        $encrypted = '';
        $pi_key = openssl_pkey_get_private($private_key);
        openssl_private_encrypt($str, $encrypted, $pi_key); //私钥加密

        $encrypted = base64_encode($encrypted);

        return $encrypted;
    }

    /**
     * 公钥解密
     * @param string $str 要解密的数据
     * @param string $public_key 公钥
     * @return string
     */
    static public function pubDecrypt($str, $public_key) {

        $decrypted = '';
        $pu_key = openssl_pkey_get_public($public_key);
        openssl_public_decrypt(base64_decode($str), $decrypted, $pu_key);//公钥解密

        return $decrypted;
    }
}