<?php
namespace app\admin\controller;
use \think\Controller;
use think\captcha\Captcha;
use think\Validate;

class Login extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function select()
    {
    	$name=input('post.name');
    	$pwd=input('post.pwd');
    	$code=input('post.code');
    	$rule=[
    		'name'=>'require',
    		'pwd'=>'require',
    		'code'=>'require'
    	];
    	$msg=[
    		'name.require'=>'账号不能为空',
    		'pwd.require'=>'密码不能为空',
    		'code.require'=>'验证码不能为空'
    	];
    	$date=[
    		'name'=>$name,
    		'pwd'=>$pwd,
    		'code'=>$code
    	];

    	$validate=new validate($rule,$msg);
    	$result=$validate->check($date);
    	if(!$result)
    		$this->error($validate->getError());
    	if(!captcha_check($code)){
			 //验证失败
				$this->error("验证码不正确！");
		}

		$data=[
    		'account'=>$name,
    		'pwd'=>md5($pwd),
            'enabled'=>1
    	];
		$ret=db('adminuser')->where($data)->find();
		if(!$ret)
			$this->error('登录失败');

		session('admin_id',$ret['id']);
		session('account',$name);
		session('logintime',time());
		$this->success('登录成功','admin/index/index');
    }

    public function verify()
    {
    	$config =    [
		    // 验证码字体大小
		    'fontSize'    =>    30,    
		    // 验证码位数
		    'length'      =>    3,   
		    // 关闭验证码杂点
		    'useNoise'    =>    false, 
		];
		$captcha = new Captcha($config);
		return $captcha->entry();
    }
}