<?php


namespace Admin\Controller;


use Admin\Model\LinksModel;
use Frame\Libs\BaseController;

final class LinksController extends BaseController
{
    public function index()
    {
        //权限验证
        $this->denyAccess();
        $Links = LinksModel::getInstance()->fetchAll();
        $this->Smarty->assign('Links',$Links);
        $this->Smarty->display("Links/index.html");
    }

    public function delete()
    {
        //权限验证
        $this->denyAccess();
        $id = $_GET['id'];
        if (LinksModel::getInstance()->delete($id))
        {
            $this->jump('删除成功！','?c=Links');
        }else{
            $this->jump('删除失败！','?c=Links');
        }
    }

    public function add()
    {
        //权限验证
        $this->denyAccess();
        $this->Smarty->display("Links/add.html");
    }

    public function insert()
    {
        //权限验证
        $this->denyAccess();
        $data['domain']  = $_POST['domain'];
        $data['url']     = $_POST['url'];
        $data['orderby'] = $_POST['orderby'];

        if (LinksModel::getInstance()->insert($data))
        {
            $this->jump('信息添加成功！',"?c=Links");
        }else{
            $this->jump('信息添加失败！',"?c=Links");
        }
    }

    public function edit()
    {
        //权限验证
        $this->denyAccess();
        $id = $_GET['id'];
        $link = LinksModel::getInstance()->fetchOne("id={$id}");
        $this->Smarty->assign('link',$link);
        $this->Smarty->display("Links/edit.html");
    }

    public function update()
    {
        $id = $_POST['id'];
        $data['domain'] = $_POST['domain'];
        $data['url'] = $_POST['url'];
        $data['orderby'] = $_POST['orderby'];

        if (LinksModel::getInstance()->update($data,$id))
        {
            $this->jump('信息修改成功！',"?c=Links");
        }else{
            $this->jump('信息修改失败！',"?c=Links");
        }
    }
}