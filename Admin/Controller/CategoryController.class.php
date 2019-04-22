<?php


namespace Admin\Controller;


use Admin\Model\CategoryModel;
use Frame\Libs\BaseController;

final class CategoryController extends BaseController
{
    public function index()
    {
        //权限验证
        $this->denyAccess();
        //获取原始分类数据
        $categorys = CategoryModel::getInstance()->fetchAll();
        //获取无限极分类数据
        $categorys = CategoryModel::getInstance()->categoryList($categorys);
        $this->Smarty->assign('categorys',$categorys);
        $this->Smarty->display("Category/index.html");
    }

    public function add()
    {
        //权限验证
        $this->denyAccess();
        //获取原始分类数据
        //获取无限极分类数据
        $categorys = CategoryModel::getInstance()->categoryList(CategoryModel::getInstance()->fetchAll());
        $this->Smarty->assign('categorys',$categorys);
        $this->Smarty->display("Category/add.html");
    }

    public function insert()
    {
        //权限验证
        $this->denyAccess();
        $data['classname'] = $_POST['classname'];
        $data['orderby'] = $_POST['orderby'];
        $data['pid'] = $_POST['pid'];

        if (CategoryModel::getInstance()->insert($data))
        {
            $this->jump('分类添加成功！','?c=Category');
        }else{
            $this->jump('分类添加失败！','?c=Category');
        }
    }

    public function edit()
    {
        //权限验证
        $this->denyAccess();
        $id = $_GET['id'];
        $arr = CategoryModel::getInstance()->fetchOne("id={$id}");
        //获取无限极分类数据
        $categorys = CategoryModel::getInstance()->fetchAll();
        $categorys = CategoryModel::getInstance()->categoryList($categorys);
        $this->Smarty->assign('categorys',$categorys);
        $this->Smarty->assign('arr',$arr);
        $this->Smarty->display("Category/edit.html");
    }

    public function update()
    {
        //权限验证
        $this->denyAccess();
        $id = $_POST['id'];
        $data['classname'] = $_POST['classname'];
        $data['orderby'] = $_POST['orderby'];
        $data['pid'] = $_POST['pid'];

        if (CategoryModel::getInstance()->update($data,$id))
        {
            $this->jump("{$id}名称修改成功！","?c=Category");
        }else{
            $this->jump("<h2>{$id}名称修改失败</h2>！","?c=Category");
        }
    }

    public function delete()
    {
        $id = $_GET['id'];
        if (CategoryModel::getInstance()->delete($id))
        {
            $this->jump("{$id}号删除成功！","?c=Category");
        }
    }
}