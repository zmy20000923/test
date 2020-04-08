<?php




include "redis.php";


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
//头信息
function responseJson($data,$http_statu="200"){
    set_https_status($http_statu);
    return json_encode($data);
}


//检测ip是否超时
function check_ips($api_name){
    $ip=$_SERVER['REMOTE_ADDR'];
    $code=$_REQUEST['code'];//机器码
    $userinfoKey=md5($ip.$code.$api_name);
    $redis= new MyRedis();
    if($redis->setnxString($userinfoKey,1)){
        $redis->expirek($userinfoKey,20);
        return true;
        //第一次访问
    }else{
        echo $num=$redis->getString($userinfoKey);
        if($num>=10){
            //超过限制
            return false;
        }
        //没超过限制自增
        $redis->IncrString($userinfoKey);
        return true;
    }

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
//检测端口
function check_apis(){
    $const_second=60;//一分钟
    $num_count=120;//一分钟一百二十次
    $seek=10;//分成十个窗口
    $seek_second=$const_second/$seek;//每个窗口六秒 
    $seek_num=$num_count/$seek;//每个窗口十二次
    $time_key="accesstime";

    $time=time();//当前时间
    $time_s=date("s",$time);//当前第几秒
    $now_seek=floor($time_s/$seek_second);//当前为第几个时间片
    $redis=new MyRedis();
    if($redis->existsK($time_key)){
        //表示访问过
        $num= $redis->lindexList($time_key,$now_seek);
        if($num>=$seek_num){
            return false;
        }else{
            $redis->lsetList($time_key,$now_seek,$num+1);
            var_dump($redis->lrangeList($time_key,0,-1));
            return true;
        }
    }else{
        //表示没访问过
        $list=[];
        for($i=0;$i<$seek;$i++){
            if($i == $now_seek){
                $list[$i]=1;
            }else{
                $list[$i]=0;
            }
        }
        $redis->rpushList($time_key,$list);
        $redis->expireK($time_key,$const_second);
        return true;

    }
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
check_apis();