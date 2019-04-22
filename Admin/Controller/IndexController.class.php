<?php


namespace Admin\Controller;

//引入基础控制器
use Frame\Libs\BaseController;

final class IndexController extends BaseController
{
    //首页显示
    public function index()
    {
        $this->denyAccess();
        $this->Smarty->display("Index/index.html");
    }

    public function top()
    {
        $this->denyAccess();
        $this->Smarty->display("Index/top.html");
    }
    public function main()
    {
        $this->denyAccess();
        $this->Smarty->assign('phpVersion',PHP_VERSION);
        $this->Smarty->assign('phpOs',php_uname('s'));
        $this->Smarty->display("Index/main.html");
    }
    public function left()
    {
        $this->denyAccess();
        $this->Smarty->display("Index/left.html");
    }
    public function center()
    {
        $this->denyAccess();
        $this->Smarty->display("Index/center.html");
    }
}