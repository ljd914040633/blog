<?php
//前端配置 
return array(
    // 数据库配置
    'db_type'   => 'mysql',
    'db_host'   => 'localhost',
    'db_name'   => 'blog',
    'db_user'   => 'root',
    'db_pass'   => 'root',
    'db_port'   => '3306',
    'charset'   => 'utf8',

    // 默认路由参数
    'default_platform'     => 'Admin',//默认平台
    'default_controller'   => 'Index',//默认控制器
    'default_action'      => 'index',//默认行为
);