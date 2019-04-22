<?php


namespace Admin\Model;


use Frame\Libs\BaseModel;

final class ArticleModel extends BaseModel
{
    protected $table = 'article';

    //链表查询方法
    public function fetchAllWithJion($where="2>1",$startrow=0,$pagesize=10)
    {
        //构建连表查询的sql语句
        $sql = "SELECT article.*,category.classname,user.name FROM {$this->table} ";
        $sql .= "LEFT JOIN category ON article.category_id=category.id ";
        $sql .= "LEFT JOIN user ON article.user_id=user.id ";
        $sql .= "WHERE {$where} ";
        $sql .= "ORDER BY article.orderby ASC,article.id DESC ";
        $sql .= "LIMIT {$startrow},{$pagesize}";

        return $this->pdo->fetchAll($sql);
    }
}