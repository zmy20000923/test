<?php

class Userapi extends Baseapi{
    //检测登录
  function logindo(){              
    $username= $this->request['username'];
    $pwd= $this->request['pwd'];
    //die;
    if(empty($username) || empty($pwd)){
      return responseJson(['error'=>'9999','msg'=>'user or pwd is no found'],401);
    // set_https_status(401);
    // return json_encode(['error'=>'19000','msg'=>'Visit frequently']);
    }
    $db= new DB();
    $userinfo= $db->select('userinfo',"username='{$username}'");
    $userinfo=isset($userinfo[0]) ? $userinfo[0] : [];

    if(empty($userinfo['username'])){
      return responseJson(['error'=>'999999','msg'=>'user is no found'],401);
    // set_https_status(401);
    // return json_encode(['error'=>'19000','msg'=>'Visit frequently']);
    }
    if($userinfo['pwd']!=md5($pwd)){
      return responseJson(['error'=>'999999','msg'=>'user or pwd is error'],401);
    // set_https_status(401);
    // return json_encode(['error'=>'19000','msg'=>'Visit frequently']);
    }
    //echo 1;
    //保存令牌
    $token= createToken($username,$pwd);
    //$db->update('userinfo',['token'=>$token],"id='{$userinfo['id']}'");
    //print_r($userinfo['username']);die;
    $data=[];
    $data['username']=$userinfo['username'];
    $data['pwd']=$userinfo['pwd'];
    $data['token']=$token;
    //$data= implode(',',$data);
    $data= serialize($data);
    //$redis= new MyRedis();
    var_dump($this->cache->setString($token,$data));
    var_dump($this->cache->expirek($token,30*24*3600));
    //$info=$this->userinfo($token);
    //echo $info;
    return responseJson(['error'=>'0','msg'=>'ok','data'=>$token]);


    
  }


  //注册
  function register(){

    $username= $this->request['username'];
    $pwd= $this->request['pwd'];
    //验证非空
    if(empty($username) || empty($pwd)){
      return responseJson(['error'=>'996666','msg'=>'user or pwd is no empty'],403);
    }
    //查询是否已存在
    $db= new DB();
    $userinfo= $db->select('userinfo',"username='{$username}'");
    $userinfo=isset($userinfo[0]) ? $userinfo[0] : [];
    if(!empty($userinfo['username'])){
      return responseJson(['error'=>'9966666','msg'=>'The username already exists'],403);
    }
    $pwd=md5($pwd);
    $res= $db->ins('userinfo',['username'=>$username,'pwd'=>$pwd]);
    return responseJson(['error'=>'0','msg'=>'ok','data'=>$res]);

  }
  //获取个人信息
  function userinfo(){
    $token= $this->request['token'];
    $time=substr($token,-10);
    echo "1";
    if($time>time()){
      echo "2";
      //$redis= new MyRedis();
      $userinfo= $this->cache->getString($token);
      $userinfo=unserialize($userinfo);
     // print_r($userinfo);die;
  
      if(isset($userinfo['username'])){
        return "当前用户名为".$userinfo['username'];
      }else{
        return responseJson(['error'=>'999999','msg'=>'Token expired'],403);
      }
    }else{
      return responseJson(['error'=>'999999','msg'=>'Token expired'],403);
    }
    
    die;
    
    // $time=substr($token,-10);
    // if($time>time()){
    //   $db= new DB();
    //   $userinfo= $db->select('userinfo',"token='{$token}'");
    //   $userinfo=isset($userinfo[0]) ? $userinfo[0] : [];
    //   if(!empty($userinfo['username'])){
    //     return "当前用户名为".$userinfo['username'];
    //   }
    // }else{
    //   return responseJson(['error'=>'999999','msg'=>'Token expired'],403);
    // }

   
  }
}



