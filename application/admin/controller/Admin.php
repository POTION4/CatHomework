<?php
namespace app\admin\controller;
use \think\Controller;
class Index extends Common
{
    public function index()
    {
    	
        return view('index');
    }

    public function test()
    {
    	
        return view('test');
    }
}
