<?php
define('DS',DIRECTORY_SEPARATOR);
define('ROOT_PATH',getcwd().DS);//根目录
define('APP_PATH',ROOT_PATH."Admin".DS);//平台应用目录


//包含框架初始类文件
require_once ROOT_PATH."Frame".DS."Frame.class.php";
//调用框架初始化方法
\Frame\Frame::run();