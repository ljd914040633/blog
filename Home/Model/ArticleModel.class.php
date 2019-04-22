<?php


namespace Home\Model;


use Frame\Libs\BaseModel;

final class ArticleModel extends BaseModel
{
    protected $table = 'article';

    //获取按文章日期统计数据
    public function fetchAllWithCount()
    {
        $sql ="SELECT date_format(from_unixtime(addate),'%Y年%m月') AS yearmonth,";
        $sql .="count(*) AS article_count FROM {$this->table} ";
        $sql .= "GROUP BY yearmonth ORDER BY yearmonth DESC";

        return $this->pdo->fetchAll($sql);
    }

    //获取文章链表查询数据
    public function fetchAllWithJion($where='2>1',$startrow,$pagesize)
    {
        $sql = "SELECT article.*,user.name,category.classname FROM {$this->table} ";
        $sql .= "LEFT JOIN user ON user.id=article.user_id ";
        $sql .= "LEFT JOIN category ON category.id=article.category_id ";
        $sql .= "WHERE {$where} ";
        $sql .= "ORDER BY article.id DESC ";
        $sql .= "LIMIT {$startrow},{$pagesize}";

        return $this->pdo->fetchAll($sql);
    }
}