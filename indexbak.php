<?php
include_once './cofing/db.php';

//$id= intval($_GET['id']);
//print_r($id);die;

// if(!CheckAccessToken()){
//     set_https_status(400);
//     echo json_encode(['error'=>'100001','msg'=>'access_token is error']);
//     exit;
// }

$users=[
    ['id'=>1,'name'=>'a'],
    ['id'=>2,'name'=>'b'],
    ['id'=>3,'name'=>'c'],
    ['id'=>4,'name'=>'d']
];
// $data=$_POST;

// print_r($data);die;
//  $data=file_get_contents("php://input");
//  parse_str(urldecode($data),$data);
//  print_r($data);die;
//$method=$_SERVER;
//print_r($method);die;
$method=$_SERVER['REQUEST_METHOD'];
switch($method){
    case 'GET'; //获取数据
       echo getUser($users);
    break;
    // case 'POST';//新增数据
    //     echo  addtUser($users);
    // break;

    case 'PUT';//更新数据
        echo updateUser($users);
    break;

    case 'DELETE';//删除数据
        echo deleteUser($users);
    break;
}
//查询
function getUser($users){
    // if(!check_ip()){
    //     set_https_status(401);
    //     return json_encode(['error'=>'19000','msg'=>'Visit frequently']);
    // }
    // if(!check_api('getUser')){
    //    set_https_status(401);
    //      return json_encode(['error'=>'19000','msg'=>'Visit frequently']);
    //     }
    $id=isset($_GET['id'])?intval($_GET['id']):0;
   
    if($id>0){
        $db= new DB();
         $data=$db->select('user',"id=$id");
         //print_r($sele);die;
        // foreach($users as $user){
        //     if($user['id'] == $id){
        //         $data[]=$user;
        //     }
        // }
    }else{
        $data=$users;
    }
    if(isset($data[0]['id'])){
        set_https_status(200);
        return json_encode(['error_code'=>'0','msg'=>'ok','res'=>$data]);
    }else{
        set_https_status(400);
        return json_encode(['error'=>'100001','msg'=>'no found user info']);
    }
}
//添加
function addtUser($users){
    $data=$_POST;
    $flag=false;
    if($data){
       $db= new DB();
       $res=$db->ins('user',$data);
       $flag=true;
    }
    if($flag){
        set_https_status(200);
        return json_encode(['error_code'=>'0','msg'=>'ok','res'=>$res]);
    }else{
        set_https_status(400);
        return json_encode(['error_code'=>'100001','msg'=>'no update:no fount user by id']);
    }

}
//修改
function updateUser($users){
    $data=file_get_contents("php://input");
    parse_str(urldecode($data),$data);
    print_r($data);die;
    $id=isset($data['id'])?intval($data['id']):0;
    $flag=false;
    if($id>0){
        $db= new DB();
       $res=$db->update('user',['name'=>$data['name']],'id=$id');
       $flag=true;
        // foreach($users as &$v){
        //     if($v['id'] == $data['id']){
        //        $v['name'] = $data['name'];
        //         $flag=true;
        //     }
        // }
    }
    if($flag){
        set_https_status(200);
        return json_encode(['error_code'=>'0','msg'=>'ok','res'=>$res]);
    }else{
        set_https_status(400);
        return json_encode(['error_code'=>'100001','msg'=>'no update:no fount user by id']);
    }

}
//删除
function deleteUser($users){
    $data=file_get_contents("php://input");
    parse_str(urldecode($data),$data);
    //print_r($data);
    $id=isset($data['id'])?intval($data['id']):0;
    $flag=false;
    if($id>0){
        $db= new DB();
        $res= $db->delete('user',"id=$id");
        $flag=true;
        // foreach($users as $k=>$v){
        //     if($users[$k]['id'] == $data['id']){
        //         unset($users[$k]);
        //        //$users[$k]='';
        //         $flag=true;
        //     }
        // }
    }
    if($flag){
        set_https_status(200);
        return json_encode(['error_code'=>'0','msg'=>'ok','res'=>$res]);
    }else{
        set_https_status(400);
        return json_encode(['error_code'=>'100001','msg'=>'no update:no fount user by id']);
    }
}
//头信息方法
function set_https_status($num) { 
    $http = array ( 
    100 => "HTTP/1.1 100 Continue", 
    101 => "HTTP/1.1 101 Switching Protocols", 
    200 => "HTTP/1.1 200 OK", 
    201 => "HTTP/1.1 201 Created", 
    202 => "HTTP/1.1 202 Accepted", 
    203 => "HTTP/1.1 203 Non-Authoritative Information", 
    204 => "HTTP/1.1 204 No Content", 
    205 => "HTTP/1.1 205 Reset Content", 
    206 => "HTTP/1.1 206 Partial Content", 
    300 => "HTTP/1.1 300 Multiple Choices", 
    301 => "HTTP/1.1 301 Moved Permanently", 
    302 => "HTTP/1.1 302 Found", 
    303 => "HTTP/1.1 303 See Other", 
    304 => "HTTP/1.1 304 Not Modified", 
    305 => "HTTP/1.1 305 Use Proxy", 
    307 => "HTTP/1.1 307 Temporary Redirect", 
    400 => "HTTP/1.1 400 Bad Request", 
    401 => "HTTP/1.1 401 Unauthorized", 
    402 => "HTTP/1.1 402 Payment Required", 
    403 => "HTTP/1.1 403 Forbidden", 
    404 => "HTTP/1.1 404 Not Found", 
    405 => "HTTP/1.1 405 Method Not Allowed", 
    406 => "HTTP/1.1 406 Not Acceptable", 
    407 => "HTTP/1.1 407 Proxy Authentication Required", 
    408 => "HTTP/1.1 408 Request Time-out", 
    409 => "HTTP/1.1 409 Conflict", 
    410 => "HTTP/1.1 410 Gone", 
    411 => "HTTP/1.1 411 Length Required", 
    412 => "HTTP/1.1 412 Precondition Failed", 
    413 => "HTTP/1.1 413 Request Entity Too Large", 
    414 => "HTTP/1.1 414 Request-URI Too Large", 
    415 => "HTTP/1.1 415 Unsupported Media Type", 
    416 => "HTTP/1.1 416 Requested range not satisfiable", 
    417 => "HTTP/1.1 417 Expectation Failed", 
    500 => "HTTP/1.1 500 Internal Server Error", 
    501 => "HTTP/1.1 501 Not Implemented", 
    502 => "HTTP/1.1 502 Bad Gateway", 
    503 => "HTTP/1.1 503 Service Unavailable", 
    504 => "HTTP/1.1 504 Gateway Time-out"  
    ); 
    header($http[$num]); 
} 

//检测access_token
function CheckAccessToken(){
    $data=[];
    $data[]="sign=asd@12321";
    $data[]="url=http://{$_SERVER['SERVER_NAME']}".$_SERVER['SCRIPT_NAME'];
    foreach($_GET as $k=>$v){
        if($k!='access_token'){
            $data[]="{$k}={$v}";
        }
    }
    sort($data,SORT_STRING);
    $str=implode('&',$data);
    $access_token=md5($str);
    if($access_token == $_REQUEST['access_token'] &&(time()-$_REQUEST['time'])<10  &&(time()-$_REQUEST['time'])>=0){
        return true;
    }
    return false;
}

//检测ip是否超时
function check_ip(){
    $user_access_info=[];
    $user_access_info['ip']=ip2long($_SERVER['REMOTE_ADDR']);
    $user_access_info['ip']= $user_access_info['ip']>0 ? $user_access_info['ip']: 0- $user_access_info['ip'];
    $user_access_info['access_time']=time();
    $user_access_info['api_name']='getUser';

        $db= new DB();
         $data=$db->select('user_access_nums',"ip={$user_access_info['ip']} and api_name='{$user_access_info['api_name']}'");
        $data=isset($data[0])?$data[0]:[];

         if(empty($data['ip'])){
            $user_access_info['nums']=1;
            $db->ins('user_access_nums',$user_access_info);
            return true;
             //记录用户信息

         }else if(time()-$data['access_time']>20 || (time()-$data['access_time']<=20 && $data['nums'] < 5)){
           
                $user_access_info['nums']=time()-$data['access_time']>20 ? 1 :$data['nums']+1;
              
                 //更新数据库
                 $db->update('user_access_nums',$user_access_info,"ip={$user_access_info['ip']} and api_name='{$user_access_info['api_name']}'");

                 return true;
             }
             
        return false;
}
//check_ip();
//检测端口
function check_api($api_name){
    $time=60;//1分钟
    $access_count=20;//1分钟访问的次数
   $min_time=$time/10; //分10份，最小时间单位6秒
   $min_time_access_num=$access_count/10; //每个时间单位允许访问的次数2次
   
    $db= new DB();
    $data=[];
    $data['api_name']=$api_name;
    //查询传进来的api_name
    $access_history=$db->select('user_api_nums',"api_name='{$api_name}'");
    $data['access_time']=time();
    $access_history=isset($access_history[0]) ? $access_history[0] : [];
    //获取当前是第几秒
    $second = time() - strtotime(date("Y-m-d H:i")) + 1;//获取当前是第几秒
     echo $second_seek=ceil($second/$min_time);//获取当前时间分片
   // echo "/n";
    $second_seek_nums=$second_seek*$min_time_access_num;//获取当当前时间分片的最大访问次数
     $data['nums']=($second_seek-1)*$min_time_access_num+1;//当前时间应存的访问次数
    print_r($access_history);
    
    if(empty($access_history['api_name'])){
        print_r($data);
        //没有查到说明可以访问
        //$data['nums']=($second-1) * $min_time_access_num+1;//说明是第一次访问 获取当前这一秒的第一次访问
        $db->ins('user_api_nums',$data);
        return true;
    }else{
        //获取查询到的数据秒数和时间片
        $history_time_second=$access_history['access_time'] - strtotime(date("Y-m-d H:i")) + 1;//当前秒数
        $history_second_seek=ceil($history_time_second/$min_time);//时间片
            if($history_second_seek == $second_seek){
                if($access_history['nums']>=$second_seek_nums){
                    return false;
                }
                $data['nums']=$access_history['nums'] + 1;
            }
            //不是一个时间片的情况
            $db->update('user_api_nums',$data,"api_name='{$api_name}'");
            return true;
    }

}

//检测登录
function logindo(){
     $username= $_POST['username'];
     $pwd= $_POST['pwd'];

   if(empty($username) || empty($pwd)){
    set_https_status(401);
    return json_encode(['error'=>'19000','msg'=>'Visit frequently']);
   }
   $db= new DB();
   $userinfo= $db->select('userinfo',"username='{$username}'");
   $userinfo=isset($userinfo[0]) ? $userinfo[0] : [];
   
   if(empty($userinfo['username'])){
    set_https_status(401);
    return json_encode(['error'=>'19000','msg'=>'Visit frequently']);
   }
   if($userinfo['pwd']!=md5($pwd)){
    set_https_status(401);
    return json_encode(['error'=>'19000','msg'=>'Visit frequently']);
   }
   echo 1;
   //保存令牌
   $token= createToken($username,$pwd);
   $db->update('userinfo',['token'=>$token],"id='{$userinfo['id']}'");
   set_https_status(200);
   return json_encode(['error'=>'0','msg'=>'ok','data'=>$token]);
}
//生成token
function createToken($username,$pwd){
    return md5($username.$pwd).(time()+30*24*3600);
}

//验证token 
function checkToken($token){
    $time=substr($token,-10);
    //验证有效期
    if($time>time()){
        //在有效期内
        $db= new DB();
        $userinfo= $db->select('userinfo',"token='{$token}'");
        $userinfo=isset($userinfo[0]) ? $userinfo[0] : [];
        //判断token是否匹配
        if(isset($userinfo['id'])){
            return true;
        }
    }
    return false;

}
//echo logindo();
$token="d9f6e636e369552839e7bb8057aeb8da1586666026";
var_dump(checkToken($token));
