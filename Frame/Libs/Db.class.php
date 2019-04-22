<?php
//定义命名空间
namespace Frame\Libs;

//定义最终的单例的数据库工具类
final class Db
{
	//私有的静态的保存对象的属性
	private static $obj = NULL;

	//私有的数据库配置信息
	private $db_host; //主机名
	private $db_port; //端口号
	private $db_user; //用户名
	private $db_pass; //密码
	private $db_name; //数据库名
	private $charset; //字符集

	//私有的构造方法：阻止类外new对象
	private function __construct()
	{
		$this->db_host = $GLOBALS['config']['db_host'];
		$this->db_port = $GLOBALS['config']['db_port'];
		$this->db_user = $GLOBALS['config']['db_user'];
		$this->db_pass = $GLOBALS['config']['db_pass'];
		$this->db_name = $GLOBALS['config']['db_name'];
		$this->charset = $GLOBALS['config']['charset'];
		$this->connectDb(); //连接MySQL服务器
		$this->selectDb();  //选择数据库
		$this->setCharset();//设置字符集
	}

	//私有的克隆方法：阻止类外clone对象
	private function __clone() {}

	//公共的静态的创建对象的方法
	public static function getInstance()
	{
		//判断当前类的对象是否存在
		if(!self::$obj instanceof self)
		{
			//如果当前类的对象不存在，则创建并保存它
			self::$obj = new self();
		}
		//返回当前类的对象
		return self::$obj;
	}

	//私有的连接数据库的方法
	private function connectDb()
	{
		if(!@mysql_connect($this->db_host.":".$this->db_port,$this->db_user,$this->db_pass))
			die("PHP连接MySQL失败！");
	}

	//私有的选择数据库的方法
	private function selectDb()
	{
		if(!mysql_select_db($this->db_name))
			die("选择数据库{$this->db_name}失败！");
	}

	//私有的设置字符集的方法
	private function setCharset()
	{
		$this->exec("set names {$this->charset}");
	}

	//公共的执行SQL语句的方法：insert、update、delete、set等
	//例如：$sql = "SEleCT * FROM student"
	//该方法返回结果必须是布尔值
	public function exec($sql)
	{
		//将SQL语句转成全小写
		$sql = strtolower($sql);
		//判断SQL语句是不是SELECT语句
		if(substr($sql,0,6)=='select')
		{
			die("exec方法不能执行SELECT语句！");
		}
		//执行SQL语句，并返回布尔值
		return mysql_query($sql);
	}

	//私有的执行SQL语句的方法：select
	//该方法返回结果是结果集资源
	private function query($sql)
	{
		//将SQL语句转成全小写
		$sql = strtolower($sql);
		//判断SQL语句是不是SELECT语句
		if(substr($sql,0,6)!='select')
		{
			die("query方法不能执行非SELECT语句！");
		}
		//执行SQL语句，并返回结果集资源
		return mysql_query($sql);		
	}

	//获取单行数据，即返回一维数组
	public function fetchOne($sql,$type=3)
	{
		//执行SQL语句，并返回结果集
		$result = $this->query($sql);

		//数值和常量对应关系
		$types = array(
			1 => MYSQL_NUM,
			2 => MYSQL_BOTH,
			3 => MYSQL_ASSOC,
		);

		//从结果集获取一行数据，并返回
		return mysql_fetch_array($result,$types[$type]);
	}

	//获取多行数据，即返回二维数组
	public function fetchAll($sql,$type=3)
	{
		//执行SQL语句，并返回结果集
		$result = $this->query($sql);

		//数值和常量对应关系
		$types = array(
			1 => MYSQL_NUM,
			2 => MYSQL_BOTH,
			3 => MYSQL_ASSOC,
		);

		//循环从结果集中取出所有行数据，并返回
		while($row = mysql_fetch_array($result,$types[$type]))
		{
			$arrs[] = $row;
		}

		//返回二维数组
		return $arrs;
	}

	//获取记录数
	public function rowCount($sql)
	{
		//执行SQL语句，并返回结果集
		$result = $this->query($sql);
		//返回记录数
		return mysql_num_rows($result);
	}

	//析构方法
	public function __destruct()
	{
		mysql_close(); //关闭数据库连接
	}
}