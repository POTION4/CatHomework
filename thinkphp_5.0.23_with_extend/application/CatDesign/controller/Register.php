<?php
namespace app\CatDesign\controller;
use \think\Validate;
use db;

class Register extends \think\Controller
{
    public function register()
    {
        return view('register');
    }
    public function insert()
    {
    	$name = input('post.name','','strip_tags');
		$email = input('post.email','','htmlspecialchars');
		$pwd = input('post.pwd');
		$repwd = input('post.repwd');

		$rul = [
			'name' => 'require|min:1',
			'email' => 'require|min:3',
			'pwd' => 'require|min:6',
			'repwd' => 'require|confirm:pwd'
		];

		$msg = [
			'name.require'=>'名字不能为空',
			'email.require'=>'邮箱必须大于三位',
			'pwd.min' => '密码大于6位',
			'repwd.confirm' => '两次密码不一致'
		];

		$data = [
			'name' => $name,
			'email' => $email,
			'pwd' => $pwd,
			'repwd' => $repwd
		];

		$validate = new Validate($rul,$msg);
		$result = $validate->check($data);
		if(! $result){
			$this -> error($validate->getError());
		}

		$ret = db('account')->insert($data);
		if($ret ==false){
			$this->error('注册失败');
		}
		$this->success('注册成功');
	
	}
}
