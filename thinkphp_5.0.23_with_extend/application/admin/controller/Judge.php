<?php
namespace app\admin\controller;
use \think\Db;

class Judge extends Common
{
	public function index()
	{
		$wenben=input('get.wenben');
        if($wenben==null)
        {
            $ret=db('judge')->order('deliverytime','desc')->paginate(10);
        }
        else
        {
            $ret=Db::table('judge')
                    ->where('order_id|name|product_name','like','%'.$wenben.'%')
                    ->order('deliverytime','desc')
                    ->paginate(10);
        }

		$count=count($ret);
		$this->assign('count',$count);
		$this->assign('list',$ret);
		return view('judge');
	}

	public function detail()
	{
		$id=input('get.id');
		
		$ret=db('judge')->where('id',$id)->find();
		$this->assign('list',$ret);
		return view('judge-detail');
	}

	public function reply()
	{
		$id=input('get.id');
		$reply=input('get.reply');
		$data=[
			'reply'=>$reply
		];
		$ret=db('judge')->where('id',$id)->update($data);
		if(!$ret)
			$this->error('回复失败');
		$this->redirect('/admin/judge/index');
	}
}