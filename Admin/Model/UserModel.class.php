<?php


namespace Admin\Model;

//引入基础模型类
use Frame\Libs\BaseModel;

final class UserModel extends BaseModel
{
    //私有保存数据表的属性
    protected $table = 'user';
}