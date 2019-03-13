<?php
namespace app\admin\controller;
use \think\Db;
use \think\Controller;
class AdminManage extends Common
{
    public function add()
    {
    	
        return view('admin-add');
    }

    public function insert()
    {
    	$adminName=input('post.adminName');
    	$password=input('post.password');
    	$name=input('post.name');
    	$sex=input('post.sex');
    	$phone=input('post.phone');
    	$email=input('post.email');
    	$remark=input('post.remark');

    	$data=[
    		'account'=>$adminName,
    		'pwd'=>md5($password),
    		'name'=>$name,
    		'sex'=>$sex,
    		'tell'=>$phone,
    		'email'=>$email,
    		'remark'=>$remark
    	];
    	$ret=db('adminuser')->insert($data);
    	if(!$ret)
    		$this->error('添加失败！');
    	$this->success('添加成功','/admin/admin_manage/alist');
    }

    public function alist()
    {
        $wenben=input('get.wenben');
        if($wenben==null)
        {
            $ret=db('adminuser')->order('id','asc')->paginate(10);
        }
        else
        {
            $ret=Db::table('adminuser')
                    ->where('name','like','%'.$wenben.'%')
                    ->order('id','asc')
                    ->paginate(10);
        }
        $count=count($ret);
        $this->assign('list',$ret);
        $this->assign('count',$count);
        return view('admin-list');
    }

    public function stop()
    {
        $id=input('get.id');

        $data=[
            'enabled'=>0
        ];
       
        $ret=db('adminuser')->where('id',$id)->update($data);
        if(!$ret)
            $this->error("停用失败！");
        $this->success('停用成功','/admin/admin_manage/alist');
    }

    public function start()
    {
        $id=input('get.id');
        $data=[
            'enabled'=>1
        ];
        $ret=db('adminuser')->where('id',$id)->update($data);
        if(!$ret)
            $this->error("启用失败！");
        $this->success('启用成功','/admin/admin_manage/alist');
    }

    public function delete()
    {
        $id=input('get.id');
        $ret=db('adminuser')->where('id',$id)->delete();
        if(!$ret)
            $this->error("删除失败！");
        $this->success('删除成功','/admin/admin_manage/alist');
    }

    public function select()
    {
        $id=input('get.id');
        $ret=db('adminuser')->where('id',$id)->find();

        
        $this->assign('list',$ret);

        return view('admin-update');
    }

    public function update()
    {
        $id=input('get.id');
        $password=input('post.password');
        $name=input('post.name');
        $sex=input('post.sex');
        $phone=input('post.phone');
        $email=input('post.email');
        $remark=input('post.remark');

        $data=[
            'pwd'=>md5($password),
            'name'=>$name,
            'sex'=>$sex,
            'tell'=>$phone,
            'email'=>$email,
            'remark'=>$remark
        ];
        $ret=db('adminuser')->where('id',$id)->update($data);
        if(!$ret)
            $this->error('更新失败！');
        $this->success('更新成功','/admin/admin_manage/alist');
    }
}
