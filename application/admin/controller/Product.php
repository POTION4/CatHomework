<?php
namespace app\admin\controller;
use \think\Controller;
use \think\Db;
use think\Validate;

class Product extends Common
{
    public function index()
    {
        $wenben=input('get.wenben');
        if($wenben==null)
        {
            $ret=db('product')->order('enabled','desc')->order('upTime','desc')->paginate(10);
            $count=db('product')->count();
        }
        else
        {
            $ret=Db::table('product')
                    ->where('product_name','like','%'.$wenben.'%')
                    ->order('enabled','desc')
                    ->order('upTime','desc')
                    ->paginate(5);
            $count=Db::table('product')
                    ->where('product_name','like','%'.$wenben.'%')
                    ->count();
        }


        $data=[];
        foreach ($ret as $val)
        {
            $val['zhutu']=json_decode($val['zhutu'],true);
            $data[]=$val;
        }
        //echo $data[1]['zhutu'][0];
        $this->assign('count',$count);
        $this->assign('list',$ret);
        $this->assign('result',$data);
        return view('product-list');
    }

    public function insert()
    {
        $product_name=input('post.product_name');
        $describe=input('post.describe');
        $price=input('post.price');
        $weight=input('post.weight');
        $stock=input('post.stock');
        $permitNo=input('post.permitNo');
        $brand=input('post.brand');
        $producingArea=input('post.producingArea');
        $factoryName=input('post.factoryName');
        $factorySite=input('post.factorySite');
        $factoryTell=input('post.factoryTell');
        $storageMethod=input('post.storageMethod');
        $warrantyPeriod=input('post.warrantyPeriod');
        $zhutu=$_POST['imgs'];
        $content=input('post.content');
        $category=input('post.category');
        
        $zhutu=json_encode($zhutu);
        $rule=[
            'product_name'=>'require',
            'describe'=>'require',
            'price'=>'require',
            'weight'=>'require',
            'stock'=>'require',
            'permitNo'=>'require',
            'brand'=>'require',
            'producingArea'=>'require',
            'factoryName'=>'require',
            'product_name'=>'require',
            'factorySite'=>'require',
            'factoryTell'=>'require',
            'storageMethod'=>'require',
            'warrantyPeriod'=>'require',
            'zhutu'=>'require',
            'category'=>'require',
            'content'=>'require'
        ];
        $msg=[
            'product_name.require'=>'商品名称不能为空',
            'describe.require'=>'商品描述不能为空',
            'category.require'=>'商品类别不能为空',
            'price.require'=>'价格不能为空',
            'weight.require'=>'净含量不能为空',
            'stock.require'=>'库存不能为空',
            'permitNo.require'=>'生产许可证编号不能为空',
            'brand.require'=>'品牌不能为空',
            'producingArea.require'=>'产地不能为空',
            'factoryName.require'=>'厂名不能为空',
            'factorySite.require'=>'厂址不能为空',
            'factoryTell.require'=>'厂家联系方式不能为空',
            'storageMethod.require'=>'储存方式不能为空',
            'warrantyPeriod.require'=>'保质期不能为空',
            'zhutu.require'=>'主图不能为空',
            'content.require'=>'详细内容不能为空'
        ];
        $data=[
            'product_name'=>$product_name,
            'describe'=>$describe,
            'category'=>$category,
            'price'=>$price,
            'weight'=>$weight,
            'stock'=>$stock,
            'permitNo'=>$permitNo,
            'brand'=>$brand,
            'producingArea'=>$producingArea,
            'factoryName'=>$factoryName,
            'factorySite'=>$factorySite,
            'factoryTell'=>$factoryTell,
            'storageMethod'=>$storageMethod,
            'warrantyPeriod'=>$warrantyPeriod,
            'zhutu'=>$zhutu,
            'content'=>$content
        ];
        $validate=new validate($rule,$msg);
        $result=$validate->check($data);
        if(!$result)
            $this->error($validate->getError());

        $date=date("Y-m-d H:i:s");
        $data=[
            'product_name'=>$product_name,
            'describe'=>$describe,
            'category'=>$category,
            'price'=>$price,
            'weight'=>$weight,
            'stock'=>$stock,
            'permitNo'=>$permitNo,
            'brand'=>$brand,
            'producingArea'=>$producingArea,
            'factoryName'=>$factoryName,
            'factorySite'=>$factorySite,
            'factoryTell'=>$factoryTell,
            'storageMethod'=>$storageMethod,
            'warrantyPeriod'=>$warrantyPeriod,
            'zhutu'=>$zhutu,
            'content'=>$content,
            'upTime'=>$date,
            'enabled'=>1
        ];

        $ret=db('product')->insert($data);
        if(!$ret)
            $this->error('发布失败');
        $this->redirect('/admin/product/index');
    }

    public function stop()
    {
        $id=input('get.id');

        $data=[
            'enabled'=>0,
            'upTime'=>null
        ];
       
        $ret=db('product')->where('id',$id)->update($data);
        if(!$ret)
            $this->error("下架失败！");
        $this->success('下架成功','/admin/product/index');
    }

    public function start()
    {
        $id=input('get.id');
        $date=date("Y-m-d H:i:s");
        $data=[
            'enabled'=>1,
            'upTime'=>$date
        ];
       
        $ret=db('product')->where('id',$id)->update($data);
        if(!$ret)
            $this->error("上架失败！");
        $this->success('上架成功','/admin/product/index');
    }

    public function delete()
    {
        $id=input('get.id');
        $ret=db('product')->where('id',$id)->delete();
        if(!$ret)
            $this->error("删除失败！");
        $this->success('删除成功','/admin/product/index');
    }

    public function add()
    {
        return view('product-add');
    }

    public function recommend()
    {
        $wenben=input('get.wenben');
        if($wenben==null)
        {
            $ret=db('product')->where('enabled',1)->order('recommend','desc')->order('upTime','desc')->paginate(10);
            $count=db('product')->where('enabled',1)->count();
        }
        else
        {
            $ret=Db::table('product')
                    ->where('enabled',1)
                    ->where('product_name','like','%'.$wenben.'%')
                    ->order('id','desc')
                    ->paginate(10);
            $count=Db::table('product')
                    ->where('enabled',1)
                    ->where('product_name','like','%'.$wenben.'%')
                    ->count();
        }


        $data=[];
        foreach ($ret as $val)
        {
            $val['zhutu']=json_decode($val['zhutu'],true);
            $data[]=$val;
        }
        //echo $data[1]['zhutu'][0];
        $this->assign('count',$count);
        $this->assign('list',$ret);
        $this->assign('result',$data);
        return view('product-recommend');
    }

    public function bu()
    {
        $id=input('get.id');

        $data=[
            'recommend'=>0
        ];
       
        $ret=db('product')->where('id',$id)->update($data);
        if(!$ret)
            $this->error("不推荐失败！");
        $this->success('不推荐成功','/admin/product/recommend');
    }

    public function tui()
    {
        $id=input('get.id');
        $data=[
            'recommend'=>1
        ];
       
        $ret=db('product')->where('id',$id)->update($data);
        if(!$ret)
            $this->error("不推荐失败！");
        $this->success('不推荐成功','/admin/product/recommend');
    }

    public function edit()
    {
        $id=input('get.id');
        $ret=db('product')->where('id',$id)->select();
        
        $data=[];
        foreach($ret as $val)
        {
            $val['zhutu']=json_decode($val['zhutu'],true);
            $data[]=$val;
        }
        
        $this->assign('list',$ret);
        $this->assign('result',$data);
        return view('product-edit');

    }

    public function update()
    {
        $id=input('get.id');
        $product_name=input('post.product_name');
        $describe=input('post.describe');
        $price=input('post.price');
        $weight=input('post.weight');
        $stock=input('post.stock');
        $permitNo=input('post.permitNo');
        $brand=input('post.brand');
        $producingArea=input('post.producingArea');
        $factoryName=input('post.factoryName');
        $factorySite=input('post.factorySite');
        $factoryTell=input('post.factoryTell');
        $storageMethod=input('post.storageMethod');
        $warrantyPeriod=input('post.warrantyPeriod');
        $zhutu=$_POST['imgs'];
        $content=input('post.content');
        $category=input('post.category');
        
        $zhutu=json_encode($zhutu);
        $rule=[
            'product_name'=>'require',
            'describe'=>'require',
            'price'=>'require',
            'weight'=>'require',
            'stock'=>'require',
            'permitNo'=>'require',
            'brand'=>'require',
            'producingArea'=>'require',
            'factoryName'=>'require',
            'product_name'=>'require',
            'factorySite'=>'require',
            'factoryTell'=>'require',
            'storageMethod'=>'require',
            'warrantyPeriod'=>'require',
            'zhutu'=>'require',
            'category'=>'require',
            'content'=>'require'
        ];
        $msg=[
            'product_name.require'=>'商品名称不能为空',
            'describe.require'=>'商品描述不能为空',
            'category.require'=>'商品类别不能为空',
            'price.require'=>'价格不能为空',
            'weight.require'=>'净含量不能为空',
            'stock.require'=>'库存不能为空',
            'permitNo.require'=>'生产许可证编号不能为空',
            'brand.require'=>'品牌不能为空',
            'producingArea.require'=>'产地不能为空',
            'factoryName.require'=>'厂名不能为空',
            'factorySite.require'=>'厂址不能为空',
            'factoryTell.require'=>'厂家联系方式不能为空',
            'storageMethod.require'=>'储存方式不能为空',
            'warrantyPeriod.require'=>'保质期不能为空',
            'zhutu.require'=>'主图不能为空',
            'content.require'=>'详细内容不能为空'
        ];
        $data=[
            'product_name'=>$product_name,
            'describe'=>$describe,
            'category'=>$category,
            'price'=>$price,
            'weight'=>$weight,
            'stock'=>$stock,
            'permitNo'=>$permitNo,
            'brand'=>$brand,
            'producingArea'=>$producingArea,
            'factoryName'=>$factoryName,
            'factorySite'=>$factorySite,
            'factoryTell'=>$factoryTell,
            'storageMethod'=>$storageMethod,
            'warrantyPeriod'=>$warrantyPeriod,
            'zhutu'=>$zhutu,
            'content'=>$content
        ];
        $validate=new validate($rule,$msg);
        $result=$validate->check($data);
        if(!$result)
            $this->error($validate->getError());

        $date=date("Y-m-d H:i:s");
        $data=[
            'product_name'=>$product_name,
            'describe'=>$describe,
            'category'=>$category,
            'price'=>$price,
            'weight'=>$weight,
            'stock'=>$stock,
            'permitNo'=>$permitNo,
            'brand'=>$brand,
            'producingArea'=>$producingArea,
            'factoryName'=>$factoryName,
            'factorySite'=>$factorySite,
            'factoryTell'=>$factoryTell,
            'storageMethod'=>$storageMethod,
            'warrantyPeriod'=>$warrantyPeriod,
            'zhutu'=>$zhutu,
            'content'=>$content,
            'upTime'=>$date,
            'enabled'=>1
        ];

        $ret=db('product')->where('id',$id)->update($data);
        if(!$ret)
            $this->error('编辑失败');
        $this->redirect('/admin/product/index');
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
