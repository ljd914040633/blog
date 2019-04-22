<?php


namespace Frame\Libs;
use \Frame\Vendor\Smarty;

abstract class BaseController
{
    //受保护的保存Smarty对象的属性
    protected $Smarty = null;
    //公共构造方法
    public function __construct()
    {
        //创建Smarty对象
        $Smarty = new Smarty();
        //Smarty配置
        $Smarty->left_delimiter = "<{";
        $Smarty->right_delimiter = "}>";
        $Smarty->setTemplateDir(VIEW_PATH);
        $Smarty->setCompileDir(sys_get_temp_dir().DS."c".DS);
        $this->Smarty = $Smarty;
    }

    //用户访问权限验证
    protected function denyAccess()
    {
        //判断用户是否登录
        if (empty($_SESSION['username']))
        {
            $this->jump("你还没有登录！","?c=User&a=login");
        }
    }

    //跳转方法
    public function jump($message,$url='?',$time=3)
    {
        $this->Smarty->assign('message',$message);
        $this->Smarty->assign('url',$url);
        $this->Smarty->assign('time',$time);
        $this->Smarty->display("Public/jump.html");
        die;
    }
}