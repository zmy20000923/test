<?php

include "common.php";
include "base.php";
include "./cofing/db.php";
include "redis.php";

$m=empty($_REQUEST['m'])?'index':$_REQUEST['m'];
$c=empty($_REQUEST['c'])?'index':$_REQUEST['c'];

if(file_exists($m.'.php')){
    include $m.'.php';
    $api= ucwords($m).'api'; //首字母大写
    if(!check_ips($m.$c)){
        echo responseJson(['error'=>'996666','msg'=>'ip is error'],403);
        die;
    }
    $obj=new $api();
    echo $obj->$c();

    exit();
}

exit('error');

// $redis= new MyRedis();
// $a=[1,2,3,4,5];
// $d= implode(',',$a);
// var_dump($redis->setString('test',$d));
// $s= $redis->getString('test');
// $s=explode(',',$s);
// print_r($s);
// die;
// $redis= new MyRedis();
// $a=['a'=>1,'s'=>2,'c'=>3];
// $d=  serialize($a);
// var_dump($redis->setString('test',$d));
// $s= $redis->getString('test');
// $s=unserialize($s);
// print_r($s);
// die;