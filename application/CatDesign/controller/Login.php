<?php
namespace app\CatDesign\controller;
use db;
class Login extends \think\Controller
{
    public function login()
    {
        return view('login');
    }
    public function check()
    {
		$name = input('post.name');
		$pwd = input('post.pwd');

		$ret = db('account')->where("name = '{$name}' and pwd = '{$pwd}'")->find();


		if($ret == false){
			$this->error("账号不匹配");
		}
/*		session('account_id',$ret['id']);
		session('name',$ret['name']);
		session('logintime',time());*/

		$this->success("登录成功",'CatDesign/index/index');
    }
}