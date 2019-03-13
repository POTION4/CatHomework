<?php
namespace app\admin\controller;
use \think\Controller;
use \think\Db;

class OrderManage extends Common
{
    public function index()
    {
        $wenben=input('get.wenben');
        if($wenben==null)
        {
            $ret=db('order')->where('condition',1)->order('ordertime','desc')->paginate(10);
        }
        else
        {
            $ret=Db::table('order')
                    ->where('name|tell|address','like','%'.$wenben.'%')
                    ->where('condition',1)
                    ->order('id','asc')
                    ->paginate(10);
        }
    	$data=[];
        foreach ($ret as $val)
        {
            $val['content']=json_decode($val['content'],true);
            $data[]=$val;
        }
    	$count=count($ret);
        $this->assign('result',$data);
        $this->assign('list',$ret);
        $this->assign('count',$count);
        return view('order');
    }

    public function payment()
    {
        $wenben=input('get.wenben');
        if($wenben==null)
        {
            $ret=db('order')->where('condition',0)->order('ordertime','desc')->paginate(10);
        }
        else
        {
            $ret=Db::table('order')
                    ->where('name|tell|address','like','%'.$wenben.'%')
                    ->where('condition',0)
                    ->order('id','asc')
                    ->paginate(10);
        }
        $data=[];
        foreach ($ret as $val)
        {
            $val['content']=json_decode($val['content'],true);
            $data[]=$val;
        }
        $count=count($ret);
        $this->assign('result',$data);
        $this->assign('list',$ret);
        $this->assign('count',$count);
        return view('payment');
    }

    public function fa()
    {
    	$id=input('get.id');
    	$date=date("Y-m-d H:i:s");
    	
    	$data=[
    		'condition'=>2,
    		'deliverytime'=>$date
    	];
    	$ret=db('order')->where('id',$id)->update($data);
    	if(!$ret)
            $this->error('更新失败！');
        $this->success('更新成功','/admin/order_manage/index');
    }
	public function delete()
    {
    	$id=input('get.id');
    	echo $id;
    	$ret=db('order')->where('id',$id)->delete();
    	if(!$ret)
            $this->error('删除失败！');
        $this->success('删除成功','/admin/order_manage/index');
    }

    public function shipped()
    {
        $wenben=input('get.wenben');
        if($wenben==null)
        {
           $ret=db('order')->where('condition','<>',1)->where('condition','<>',0)->order('id','desc')->paginate(10);
        }
        else
        {
            $ret=Db::table('order')
                    ->where('name|tell|address','like','%'.$wenben.'%')
                    ->where('condition','<>',1)
                    ->where('condition','<>',0)
                    ->order('id','asc')
                    ->paginate(10);
        }
        $data=[];
        foreach ($ret as $val)
        {
            $val['content']=json_decode($val['content'],true);
            $data[]=$val;
        }

    	
    	$count=count($ret);
        $this->assign('result',$data);
        $this->assign('list',$ret);
        $this->assign('count',$count);
    	return view('shipped');
    }
}
