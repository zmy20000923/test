<?php
class MyRedis{
    private $redis;
    public function __construct(){
        //连接本地的 Redis 服务
        $this->redis = new Redis();
        $this->redis->connect('127.0.0.1', 6379);
        if($this->redis->ping() != 1){
            echo "redis is error";
        }
    }
    //redis设置字符串
    public function setString($k,$v){
        return $this->redis->set($k,$v);
    }

    //redis获取字符串
    public function getString($k){
        return $this->redis->get($k);
    }
    //设置$k过期时间
    public function expireK($k,$time){
        return $this->redis->expire($k,$time);
    }
    //如果没有设置如果有没法设置
    public function setnxString($k,$v){
        return $this->redis->setnx($k,$v);
    }
    //自增
    public function IncrString($k){
        return $this->redis->INCR($k);
    }
    //判断$k是否存在
    public function existsK($k){
        return $this->redis->exists($k);
    }
    //设置列表右
    function rpushList($key,array $arr=[]){
        call_user_func_array([$this->redis,'rPush'],array_merge(array($key),$arr));
    }
    //获取列表中某个元素的值
    function lindexList($key_name,$site){
        return $this->redis->lindex($key_name,$site);
    }
    //修改列表某个元素的值
    function lsetList($key_name,$site,$res){
        return $this->redis->lset($key_name,$site,$res);
    }
    //按偏移量获取列表的值
    function lrangeList($key_name,$star,$stop){
        return $this->redis->lrange($key_name,$star,$stop);
    }
}


