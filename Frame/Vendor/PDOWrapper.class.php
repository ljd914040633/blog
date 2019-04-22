<?php

//命名空间
namespace Frame\Vendor;
use \PDO;
use \Exception;
use \PDOException;

//定义最终的PDOWrapper类
final class PDOWrapper
{
    //私有数据库配置信息
    private $db_type;
    private $db_host;
    private $db_port;
    private $db_name;
    private $db_user;
    private $db_pass;
    private $charset;
    private $pdo = null;

    //
    public function __construct()
    {
        $this->db_type = $GLOBALS['config']['db_type'];
        $this->db_host = $GLOBALS['config']['db_host'];
        $this->db_port = $GLOBALS['config']['db_port'];
        $this->db_name = $GLOBALS['config']['db_name'];
        $this->db_user = $GLOBALS['config']['db_user'];
        $this->db_pass = $GLOBALS['config']['db_pass'];
        $this->charset = $GLOBALS['config']['charset'];
        $this->createPDO();//创建PDO对象
        $this->setErrorMode();//设置PDO报错模式
    }

    //私有创建PDO对象
    private function createPDO()
    {
        try{
            $dsn = "{$this->db_type}:dbname={$this->db_name};host={$this->db_host};";
            $dsn .= "port={$this->db_port};charset={$this->charset}";
            $this->pdo = new PDO($dsn,$this->db_user,$this->db_pass);
        }catch (Exception $e)
        {
            echo "<h2>PDO对象创建失败！</h2>";
            echo "错误编号：".$e->getCode().'<br>';
            echo "错误行号：".$e->getLine().'<br>';
            echo "错误文件：".$e->getFile().'<br>';
            echo "错误信息：".$e->getMessage();
        }
    }

    //私有PDO报错方式
    private function setErrorMode()
    {
        //设置PDO报错方式为异常模式
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    }

    //公共执行非select语句方法
    public function exec($sql)
    {
        try{
            return $this->pdo->exec($sql);
        }catch (PDOException $e)
        {
            $this->showError($e);
        }
    }
    
    //获取单行数据
    public function fetchOne($sql)
    {
        try {
            $PDOStatement = $this->pdo->query($sql);
            return $PDOStatement->fetch(PDO::FETCH_ASSOC);
        }catch(PDOException $e)
        {
            $this->showError($e);
        }
    }

    //获取多行数据
    public function fetchAll($sql)
    {
        try{
            $PDOStatement = $this->pdo->query($sql);
            return $PDOStatement->fetchAll(PDO::FETCH_ASSOC);
        }catch (PDOException $e)
        {
            $this->showError($e);
        }
    }
    
    //获取记录数
    public function rowCount($sql)
    {
        try{
            $PDOStatement = $this->pdo->query($sql);
            return $PDOStatement->rowCount();
        }catch (PDOException $e)
        {
            $this->showError($e);
        }
    }
    
    //私有错误显示方法
    private function showError($e)
    {
        echo "<h2>执行sql语句失败！</h2>";
        echo "错误编号：".$e->getCode().'<br>';
        echo "错误行号：".$e->getLine().'<br>';
        echo "错误文件：".$e->getFile().'<br>';
        echo "错误信息：".$e->getMessage();
    }
}