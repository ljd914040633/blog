<?php


namespace Home\Controller;


use Home\Model\ArticleModel;
use Home\Model\CategoryModel;
use Home\Model\LinksModel;
use Frame\Libs\BaseController;

final class IndexController extends BaseController
{
    public function index()
    {
        //获取友情连接
        $links = LinksModel::getInstance()->fetchAll();

        //获取无限极分类数据
        $categorys = CategoryModel::getInstance()->categoryList(
            CategoryModel::getInstance()->fetchAllWithJion()
        );

        //获取按日期分类统计数据
        $dates = ArticleModel::getInstance()->fetchAllWithCount();

        //搜索条件
        $where = '2>1';
        if (isset($_REQUEST['title'])) $where .= " AND title LIKE '%".$_REQUEST['title']."%'";
        if (isset($_GET['category_id'])) $where .= " AND category_id=".$_GET['category_id'];

        //构建分页参数
        $pagesize = 5;//每页显示条数
        $records = ArticleModel::getInstance()->rowCount($where);//总条数
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $startrow = ($page-1)*$pagesize;//起始行号
        $params = array('c'=>CONTROLLER,'a'=>ACTION);
        if (isset($_REQUEST['title'])) $params['title'] = $_REQUEST['title'];
        if (isset($_GET['category_id'])) $params['category_id'] = $_GET['category_id'];

        //获取文章链表查询数据
        $articles = ArticleModel::getInstance()->fetchAllWithJion($where,$startrow,$pagesize);

        //获取分页字符串
        $pageObj = new \Frame\Vendor\Pager($records,$pagesize,$page,$params);
        $pagestr = $pageObj->showPage();

        $this->Smarty->assign(array(
            "links"     => $links,
            'categorys' => $categorys,
            'dates'     => $dates,
            'articles'  => $articles,
            'pagestr'   => $pagestr,
        ));
        $this->Smarty->display("Index/index.html");
    }
}