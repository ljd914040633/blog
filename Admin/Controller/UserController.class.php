<?php


namespace Admin\Controller;

use Admin\Model\UserModel;
use Frame\Libs\BaseController;

final class UserController extends BaseController
{
    //登录入口方法
    public function login()
    {
        $this->Smarty->display("User/login.html");
    }
    //登录验证方法
    public function loginCheck()
    {
        //获取表单提交值
        $username = $_POST['username'];
        $password = md5($_POST['password']);
        $verify   = strtolower($_POST['verify']);

        //判断验证码是否一致,用户名和密码是否与数据库一致
        $user = UserModel::getInstance()->fetchOne("username='{$username}' AND password='{$password}' AND status!=0");
        if (!$user)
        {
            $this->jump("用户名或密码不正确！","?c=User&a=login");
        }
        if ($verify != $_SESSION['captcha'])
        {
            $this->jump("验证码不正确！","?c=User&a=login");
        }
        //更新用户信息
        $data['last_login_ip']   = $_SERVER['REMOTE_ADDR'];
        $data['last_login_time'] = time();
        $data['login_times']     = $user['login_times']+1;
        UserModel::getInstance()->update($data,$user['id']);
        //将用户信息存入SESSION
        $_SESSION['uid'] = $user['id'];
        $_SESSION['username'] = $username;
        //跳转到后台
        $this->jump("用户{$username}登录成功","./admin.php");
    }
    //退出登录方法
    public function logout()
    {
        unset($_SESSION['username']);
        unset($_SESSION['uid']);
        //删除session文件
        session_destroy();
        $this->jump('退出成功！',"?c=User&a=login");
    }
    
    //首页显示
    public function index()
    {
        //权限验证
        $this->denyAccess();
        $users = UserModel::getInstance()->fetchAll();
        $this->Smarty->assign('users',$users);
        $this->Smarty->display('User/index.html');
    }

    public function delete()
    {
        //权限验证
        $this->denyAccess();
        $id = $_GET['id'];
        if (UserModel::getInstance()->delete($id))
        {
            $this->jump("{$id}数据删除成功！","?c=User");
        }else{
            $this->jump("{$id}数据删除失败！","?c=User");
        }
    }
    
    //调用添加视图方法
    public function add()
    {
        //权限验证
        $this->denyAccess();
        $this->Smarty->display("User/add.html");
    }

    public function insert()
    {
        //权限验证
        $this->denyAccess();
        $data['username']   = $_POST['username'];
        $data['password']   = md5($_POST['password']);
        $data['name']       = $_POST['name'];
        $data['tel']        = $_POST['tel'];
        $data['status']     = $_POST['status'];
        $data['role']       = $_POST['role'];
        $data['addate']     = time();

        if (UserModel::getInstance()->rowCount("username='{$data['username']}'"))
        {
            $this->jump('用户名已存在！',"?c=User");
        }

        if (UserModel::getInstance()->insert($data))
        {
            $this->jump('用户注册成功！',"?c=User");
        }else{
            $this->jump('用户注册失败！',"?c=User");
        }
    }
    //调用修改视图，获取修改数据
    public function edit()
    {
        //权限验证
        $this->denyAccess();
        $id = $_GET['id'];
        $arr = UserModel::getInstance()->fetchOne("id={$id}");
        $this->Smarty->assign('arr',$arr);
        $this->Smarty->display("User/edit.html");
    }
    //修改方法
    public function update()
    {
        //权限验证
        $this->denyAccess();
        $id = $_POST['id'];
        $data['name']     = $_POST['name'];
        $data['tel']      = $_POST['tel'];
        $data['status']   = $_POST['status'];
        $data['role']     = $_POST['role'];

        if (!empty($_POST['password']) && !empty($_POST['confirmpwd']))
        {
            //判断两次输入密码是否一致
            if ($_POST['password']==$_POST['confirmpwd'])
            {
                $data['password'] = md5($_POST['password']);
            }else{
                $this->jump("两次输入的密码不一致！","?c=User&a=edit&id={$id}");
            }
        }

        if (UserModel::getInstance()->update($data,$id))
        {
            $this->jump("{$id}号记录更新成功！","?c=User");
        }else{
            $this->jump("{$id}号记录更新失败！","?c=User");
        }
    }
    
    //验证码方法
    public function captcha()
    {
        $captcha = new \Frame\Vendor\Captcha();
        $_SESSION['captcha'] = $captcha->getCode();
    }
}