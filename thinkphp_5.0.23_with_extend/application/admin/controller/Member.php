<?php
namespace app\admin\controller;
use \think\Controller;
use \think\Db;

class Member extends Common
{
    public function index()
    {
        $wenben=input('get.wenben');
        if($wenben==null)
        {
            $ret=db('user')->order('id','asc')->paginate(10);
        }
        else
        {
            $ret=Db::table('user')
                    ->where('account|name|tell|email','like','%'.$wenben.'%')
                    ->order('id','asc')
                    ->paginate(10);
        }
    	
        $count=count($ret);
        $this->assign('list',$ret);
        $this->assign('count',$count);
        return view('member-list');
    }

    public function stop()
    {
        $id=input('get.id');

        $data=[
            'enabled'=>0
        ];
       
        $ret=db('user')->where('id',$id)->update($data);
        if(!$ret)
            $this->error("停用失败！");
        $this->success('停用成功','/admin/member/index');
    }

    public function start()
    {
        $id=input('get.id');
        $data=[
            'enabled'=>1
        ];
        $ret=db('user')->where('id',$id)->update($data);
        if(!$ret)
            $this->error("启用失败！");
        $this->success('启用成功','/admin/member/index');
    }

    public function delete()
    {
        $id=input('get.id');
        $ret=db('user')->where('id',$id)->delete();
        if(!$ret)
            $this->error("删除失败！");
        $this->success('删除成功','/admin/member/index');
    }
}
