<?php


$a="你好";

class DB{
    private $dbms='mysql';     //数据库类型
    private $host='127.0.0.1'; //数据库主机名
    private $dbName='api';    //使用的数据库
    private $user='root';      //数据库连接用户名
    private $pass='root';          //对应的密码
    private $dbh;
    public function __construct(){
        $dsn="$this->dbms:host=$this->host;dbname=$this->dbName";
        $this->dbh = new PDO($dsn,$this->user,$this->pass); //初始化一个PDO对象
        $this->dbh->query('set names utf8');
    }
    public function ins($table,$data){
        $sql="insert into {$table} set";
        foreach($data as $k=>$v){
            $sql.=" {$k}='{$v}',";
        }
         echo $sql=substr($sql,0,-1);
       return $this->dbh->exec($sql);
    }

    public function select($table,$where='1=1',$order='',$limit='',$debug=0){
        $sql="select * from {$table} where {$where} {$order} {$limit}";
        //echo $sql;die;
        if($debug){
            echo $sql;die;
        }
        $rs= $this->dbh->query($sql);
        $data=[];
        foreach($rs as $k){
            $data[]=$k;
        }
        return $data;

    }

    public function update($table,$data,$where='1=1'){
        $sql="update {$table} set";
        foreach($data as $k=>$v){
            $sql.=" {$k}='{$v}',";
        }
       $sql=substr($sql,0,-1);
        echo $sql.="where {$where}";
    return $this->dbh->exec($sql);
    }

    public function delete($table,$where='1=1'){
        $sql="delete from {$table} where {$where}";
        echo $sql;

        return $this->dbh->exec($sql);
    }
}