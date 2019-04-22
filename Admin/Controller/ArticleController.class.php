<?php


namespace Admin\Controller;

use Admin\Model\CategoryModel;
use Admin\Model\ArticleModel;
use Frame\Libs\BaseController;

final class ArticleController extends BaseController
{
    public function index()
    {
        $categorys = CategoryModel::getInstance()->categoryList(
            CategoryModel::getInstance()->fetchAll()
        );

        //构建搜索条件
        $where = "2>1";
        if (!empty($_REQUEST['category_id'])) $where .= " AND category_id=".$_REQUEST['category_id'];
        if (!empty($_REQUEST['keyword'])) $where .= " AND title LIKE '%".$_REQUEST['keyword']."%'";

        //构建分页参数
        $pagesize = 5;//每页显示条数
        $page = isset($_GET['page']) ? $_GET['page'] : 1;//当前页码
        $startrow = ($page-1)*$pagesize;//开始行号
        $records = ArticleModel::getInstance()->rowCount($where);//总计录数
        $params = array('c'=>CONTROLLER,'a'=>ACTION);//附加参数
        if (!empty($_REQUEST['category_id'])) $params['category_id'] = $_REQUEST['category_id'];
        if (!empty($_REQUEST['keyword'])) $params['keyword'] = $_REQUEST['keyword'];

        //获取链表查询数据
        $articles = ArticleModel::getInstance()->fetchAllWithJion($where,$startrow,$pagesize);

        //创建分页类对象
        $pageObj = new \Frame\Vendor\Pager($records,$pagesize,$page,$params);
        $pagestr = $pageObj->showPage();

        //向视图赋值
        $this->Smarty->assign(array(
            'categorys' => $categorys,
            'articles'   => $articles,
            'pagestr'   => $pagestr,
        ));
        $this->Smarty->display("Article/index.html");
    }

    public function add()
    {
        $categorys = CategoryModel::getInstance()->categoryList(
            CategoryModel::getInstance()->fetchAll()
        );
        $this->Smarty->assign('categorys',$categorys);
        $this->Smarty->display("Article/add.html");
    }

    public function insert()
    {
        $data['category_id'] = $_POST['category_id'];
        $data['user_id']     = $_SESSION['uid'];
        $data['title']       = $_POST['title'];
        $data['orderby']     = $_POST['orderby'];
        $data['content']     = $_POST['content'];
        $data['top']         = isset($_POST['content']) ? 1 : 0;
        $data['addate']      = time();

        if (ArticleModel::getInstance()->insert($data))
        {
            $this->jump("<h2>新闻添加成功！</h2>","?c=Article");
        }else{
            $this->jump("<h2>新闻添加失败！</h2>","?c=Article");
        }
    }
}