<?php
class Baseapi{
    protected $db=null;
    protected $cache=null;
    protected $request=[];
    public function __construct(){
        $this->db = new DB();
        $this->cache= new MyRedis();
        $method=$_SERVER['REQUEST_METHOD'];
        switch($method){
            case 'PUT';//更新数据
            case 'DELETE';//删除数据
            parse_str(urldecode(file_get_contents("php://input")),$this->request);
            break;
        }
        if(!empty($_REQUEST)){
            foreach($_REQUEST as $k=>$v){
                $this->request[$k]=$v;                
            }
        }
    }
}