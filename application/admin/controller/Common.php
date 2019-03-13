<?php
namespace app\admin\controller;
use \think\Controller;


class Common extends Controller
{
	public function _initialize()
	{
		if(session('admin_id')==NULL)
		{
			$this->error('请登录','/admin/login/login');
		}
		if(time()-session('logintime')>60*20)
		{
			$this->error('登录超时');
		}
		session('logintime',time());
	}
}