<?php

class Mycurl{

    private $ch;
    public function __construct(){
        $this->ch=curl_init(); //初始化
    }
    public function __destruct(){
        curl_close($this->ch); //关闭
    }

    //设置curl访问参数
    public function setDefaultopt($url){
        curl_setopt($this->ch,CURLOPT_URL,$url); //设置参数
        curl_setopt($this->ch,CURLOPT_RETURNTRANSFER,1); //返回原生输出
    } 

    //设置post请求
    public function setoptpost($data){
            curl_setopt($this->ch,CURLOPT_POST,1);//post 方式
            curl_setopt($this->ch,CURLOPT_POSTFIELDS,$data);
            //忽略证书
            curl_setopt($this->ch,CURLOPT_SSL_VERIFYPEER,false);
            curl_setopt($this->ch,CURLOPT_SSL_VERIFYHOST,false);
            //忽略头信息
            curl_setopt($this->ch,CURLOPT_HEADER,0);
            //设置超时时间 10 秒
            curl_setopt($this->ch,CURLOPT_TIMEOUT ,10);
    }

    public function exec($url,$funct="get",$data=''){

        $this->setDefaultopt($url);
        if($funct=="post"){
            $this->setoptpost($data);
        }
      
        $output=curl_exec($this->ch);
        
        if($out_error=curl_error($this->ch)){
            return ['error'=>'yes','output'=>$out_error];
        }
        return ['error'=>'no','output'=>$output];
        
    }
}