<?php
//声明命名空间
namespace Frame;

//定义最终的框架初始类
final class Frame
{
    //公共的静态的初始化方法
    public static function run()
    {
        self::initCharset();//初始化字符集
        self::initConfig();//初始化配置文件
        self::initRoute();//路有参数
        self::initConst();//常量定义
        self::initAutoLoad();//自动加载
        self::initDispatch();//请求分发
    }

    //私有静态初始化字符集
    private static function initCharset()
    {
        header("Content-Type:text/html; Charset=utf-8");
        session_start();
    }

    //初始化配置文件
    private static function initConfig()
    {
        $GLOBALS['config'] = require_once APP_PATH."Conf".DS."Config.php";
    }

    //私有静态路有参数
    private static function initRoute()
    {
        $p = $GLOBALS['config']['default_platform'];
        $c = isset($_GET['c']) ? $_GET['c'] : $GLOBALS['config']['default_controller'];//控制器
        $a = isset($_GET['a']) ? $_GET['a'] : $GLOBALS['config']['default_action'];//动作参数
        define("PLAT",$p);
        define("CONTROLLER",$c);
        define("ACTION",$a);
    }
    
    //私有静态常量定义
    private static function initConst()
    {
        define('FRAME_PATH',ROOT_PATH.'Frame'.DS);//Frame目录
        define('VIEW_PATH',APP_PATH.'View'.DS);//视图目录
    }
    
    //私有静态自动加载
    private static function initAutoLoad()
    {
        spl_autoload_register(function ($className){
            //构建文件真实路径
            $fileName = ROOT_PATH.str_replace('\\',DS,$className).'.class.php';
            //如果类文件存在，则包含
            if(file_exists($fileName)) {
                require_once $fileName;
            }
        });
    }

    //私有静态请求分发
    private static function initDispatch()
    {
        //构建动态的控制器类名称
        $controllerClassNmae = '\\'.PLAT.'\\'.'Controller'.'\\'.CONTROLLER.'Controller';
        //创建控制器类对象
        $controllerObj = new $controllerClassNmae();
        $action = ACTION;
        $controllerObj->$action();
    }
}


