<?php


namespace Frame\Libs;

//抽象基础模型类
use Frame\Vendor\PDOWrapper;

abstract class BaseModel
{
    //私有静态保存模型类对象的属性
    private static $arrModelObj = array();
    //受保护的保存PDO对象的属性
    protected $pdo = null;

    //公共构造方法
    public function __construct()
    {
        //创建PDOWrapper类的对象
        $this->pdo = new PDOWrapper();
    }

    //公共静态单例工厂模型创建方法
    public static function getInstance()
    {
        //获取静态方法调用的类名，及后期静态延迟绑定
        $className = get_called_class();

        //判断当前模型类对象是否存在
        if (empty(self::$arrModelObj[$className]))
        {
            self::$arrModelObj[$className] = new $className;
        }
        //存在则返回
        return self::$arrModelObj[$className];
    }

    //获取多行数据
    public function fetchAll()
    {
        $sql = "SELECT * FROM {$this->table}";
        return $this->pdo->fetchAll($sql);
    }

    //获取单行数据
    public function fetchOne($where="2>1")
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$where} LIMIT 1";
        return $this->pdo->fetchOne($sql);
    }

    public function insert($data)
    {
        $keys = '';
        $values = '';
        foreach ($data as $key=>$value)
        {
            $keys .= "$key,";
            $values .= "'$value',";
        }
        $keys = rtrim($keys,',');
        $values = rtrim($values,',');
        $sql = "INSERT INTO {$this->table}({$keys}) VALUES ({$values})";
        return $this->pdo->exec($sql);
    }

    public function update($data,$id)
    {
        $str = '';
        foreach ($data as $k=>$v)
        {
            $str .= "$k='$v',";
        }
        $str = rtrim($str,',');
        $sql = "UPDATE {$this->table} SET {$str} WHERE id={$id}";
        return $this->pdo->exec($sql);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id={$id}";
        return $this->pdo->exec($sql);
    }

    //获取统计数据
    public function rowCount($where="2>1")
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$where}";
        return $this->pdo->rowCount($sql);
    }
}