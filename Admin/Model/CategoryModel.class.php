<?php


namespace Admin\Model;


use Frame\Libs\BaseModel;

final class CategoryModel extends BaseModel
{
    protected $table = 'category';
    //获取无限极分类方法
    public function categoryList($arrs,$level=0,$pid=0)
    {
        //定义静态变量，用于存储每次递归调用找到的数据
        static $categorys = array();

        //循环原始的分类数组
        foreach ($arrs as $arr)
        {
            if ($arr['pid'] == $pid)
            {
                $arr['level'] = $level;
                $categorys[] = $arr;
                //递归调用
                $this->categoryList($arrs,$level+1,$arr['id']);
            }
        }
        return $categorys;
    }
}