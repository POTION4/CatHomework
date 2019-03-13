<?php
namespace app\admin\controller;
use \think\Controller;
use think\Validate;
use \think\Db;

class Picture extends Common
{
    public function index()
    {
        $wenben=input('get.wenben');
        if($wenben==null)
        {
            $ret=db('picture')->order('id','desc')->paginate(5);
            $count=db('picture')->count();
        }
        else
        {
            $ret=Db::table('picture')
                    ->where('title','like','%'.$wenben.'%')
                    ->order('id','desc')
                    ->paginate(5);
            $count=Db::table('picture')
                    ->where('title','like','%'.$wenben.'%')
                    ->count();
        }
        $data=[];
        foreach ($ret as $val)
        {
            $val['img']=json_decode($val['img'],true);
            $data[]=$val;
        }
        //dump($data);
        $count=count($ret);
        $this->assign('conut',$count);
        $this->assign('list',$data);
        $this->assign('page',$ret);
    	
        return view('picture-list');
    }

    public function stop()
    {
        $id=input('get.id');

        $data=[
            'enabled'=>0
        ];
       
        $ret=db('picture')->where('id',$id)->update($data);
        if(!$ret)
            $this->error("停用失败！");
        $this->success('停用成功','/admin/picture/index');
    }

    public function start()
    {
        $id=input('get.id');
        $data=[
            'enabled'=>1
        ];
        $ret=db('picture')->where('id',$id)->update($data);
        if(!$ret)
            $this->error("启用失败！");
        $this->success('启用成功','/admin/picture/index');
    }

    public function delete()
    {
        $id=input('get.id');
        $ret=db('picture')->where('id',$id)->delete();
        if(!$ret)
            $this->error("删除失败！");
        $this->success('删除成功','/admin/picture/index');
    }

    public function add()
    {

    	return view('picture-add');
    }

    public function insert()
    {
    	$title=input('post.title');
    	$imgurl=input('post.imgurl');
    	$paixu=input('post.paixu');
    	$img=input('post.img');
        $img=json_encode($img);
    	$rule=[
    		'title'=>'require',
    		'imgurl'=>'require',
    		'paixu'=>'require',
            'img'=>'require'
    	];
    	$msg=[
    		'title.require'=>'标题不能为空',
    		'imgurl.require'=>'超链接不能为空',
    		'paixu.require'=>'排序值不能为空',
            'img.require'=>'没有选择轮播图图片'
    	];
    	$data=[
    		'title'=>$title,
    		'imgurl'=>$imgurl,
    		'paixu'=>$paixu,
            'img'=>$img
    	];
    	$validate=new validate($rule,$msg);
    	$result=$validate->check($data);
    	if(!$result)
    		$this->error($validate->getError());
		
    	$ret=db('picture')->insert($data);
    	if(!$ret)
    		$this->error('添加失败');
    	$this->success('添加成功','/admin/picture/index');
    }

    public function select()
    {
        $id=input('get.id');
        $ret=db('picture')->where('id',$id)->find();
        $this->assign('list',$ret);

        return view('picture-edit');
    }

    public function update()
    {
        $id=input('get.id');
        $title=input('post.title');
        $imgurl=input('post.imgurl');
        $paixu=input('post.paixu');
        $img=input('post.img');
        $rule=[
            'title'=>'require',
            'imgurl'=>'require',
            'paixu'=>'require',
            'img'=>'require'
        ];
        $msg=[
            'title.require'=>'标题不能为空',
            'imgurl.require'=>'超链接不能为空',
            'paixu.require'=>'排序值不能为空',
            'img.require'=>'请重新选择图片',
        ];
        $data=[
            'title'=>$title,
            'imgurl'=>$imgurl,
            'paixu'=>$paixu,
            'img'=>$img
        ];
        $validate=new validate($rule,$msg);
        $result=$validate->check($data);
        if(!$result)
            $this->error($validate->getError());
        
        $ret=db('picture')->where('id',$id)->update($data);
        if(!$ret)
            $this->error('添加失败');
        $this->success('添加成功','/admin/picture/index');
    }

    public function upimg()
    {
    // 获取表单上传文件 例如上传了001.jpg
	    $file = request()->file('image');
	    
	    // 移动到框架应用根目录/public/uploads/ 目录下
	    if($file){
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
        if($info){
            // 成功上传后 获取上传信息
            // 输出 jpg
            
            $img_src="/uploads/{$info->getSaveName()}";
            echo $img_src;
        }else{
            // 上传失败获取错误信息
            $this->error($file->getError());
        }
    }

    

}
}
