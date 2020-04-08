<?php
include_once "curl.php";
//模拟客户端
//生成access_token
function CreateAccessToken($data){

    $data[]="sign=asd@12321";

    //print_r($data);
    sort($data,SORT_STRING);
    $str=implode('&',$data);
    //echo $str;
    $access_token=md5($str);
    return $access_token;

}

function Test(){
    $curl= new Mycurl();
    $url="http://aip-test.com/index.php";
    $time=time();
    $access_token=CreateAccessToken(["time=".$time,'url='.$url]);
    $url= $url."?time=".$time."&access_token=".$access_token;
    $res= $curl->exec($url);
    print_r($res);
}
function Tests(){
    $curl= new Mycurl();
    $url="http://aip-test.com/index.php";
    $time=time();
    $access_token=CreateAccessToken(["time=".$time,'url='.$url,'id=1']);
    $url= $url."?id=1&time=".$time."&access_token=".$access_token;
    $res= $curl->exec($url);
    print_r($res);
}

Tests();
// $url="http://aip-test.com/client.php";
// $time=time();
// CreateAccessToken(["time=".$time,'url='.$url]);
