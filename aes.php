<?php
class Aes{
    private $method="AES-128-CBC";//加密方式
    private $key="zhangaihou@_zaih";//密钥只能是16位
    private $iv="zhangaihou@_zaih";//偏移量
    //加密
    public function encrypt($p_string){
        $s_string= openssl_encrypt($p_string,$this->method,$this->key,OPENSSL_RAW_DATA,$this->iv);//进行加密并返回结果
        return base64_encode($s_string);//返回结果和iv
    }
    //解密
    public function decrypt($s_string){
    
        return openssl_decrypt(base64_decode($s_string),$this->method,$this->key,OPENSSL_RAW_DATA,$this->iv);
    }
}

$obj=new Aes();
$sign= $obj->encrypt('asfewouhiwhgosjif;sodhglidshlgids');
var_dump($sign);
echo '\n';
echo $obj->decrypt($sign);
