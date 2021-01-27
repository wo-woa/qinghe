<?php
namespace Home\Controller;
// use Home\LoginController;Login
use Think\Controller;

class IndexController extends Controller {

	public function index() {
		redirect('/Mem/index');
		die;
		echo "string";
		die;

		echo "string";
		dump(M('menu')->select()); //haoyangmao
		die;

		dump(M('admin')->select());

		dump(M2('booking')->order('id asc')->limit(5)->select());
		die;
		dump(M('admin', 'app_', 'db2')->select());
		die;
		echo "string";
		dump(M('admin')->select());
		dump(M2('admin')->select());
		die;
		// echo "微信未绑定";http://wx.1dysy.com

		$this->ctrl = 'index';
		$this->bgimg = 2;
		// $this->actv =1;
		// $this->lunboarr = M('Sys')->where(['iname' => 'lunbo'])->order('ordering asc')->select(); //输出全部

		// $this->mainbtm = kdbfield('sys', ['iname' => 'maintitle'], 'ivalue');
		// $this->lanmuarr = M('Sys')->where(['iname' => 'lanmu', 'remarks' => 1])->order('ordering asc')->getField('id,url,title,ordering'); //输出全部

		// $this->tjarr = M('Sys')->where(['iname' => ['gt', 0], 'remarks' => 1])->order('ordering asc')->select(); //首页推荐的
		// // dump($this->tjarr);die;
		// $this->intro = M('sys')->where(['iname' => 'indexaboutus'])->find();
		$this->display('index');
		// echo "string";
		// $this->redirect('/Vip');

	}

	public function soso() {
		//删除重复景区
		$jing = M('infos')->select();
		foreach ($jing as $k => $val) {

			// unset();
			// $data2 = $val;
			// $data2['proid'] = 409; //新增一个景区，改成省的
			// $data2['cateid'] = $res; //新增一个景区，市改成

			$pro = M('cates')->where(['id' => $val['cateid']])->find();

			if (!$pro) {
				M('infos')->where(['id' => $val['id']])->delete();

			}

		}

		echo "soso";
	}

	public function jobs() {
		if (IS_POST) {

			die;
		}
		$this->display();

	}
	// public function cont() {
	// 	$this->mainbtm = kdbfield('sys', ['iname' => 'maintitle'], 'ivalue');
	// 	$sys = D('Sys'); //输出全部部门
	// 	$parid = I('get.id', 0);
	// 	$this->title = $sys->where(['id' => $parid])->getField('title');

	// 	// $this->allarr = D('Sys')->where(['iname' => $parid])->order('iname asc,ordering desc')->select(); //输出全部

	// 	$count = $sys->where(['iname' => $parid])->count();
	// 	$page = new \Think\Page($count, 10);
	// 	$arr = $sys->where(['iname' => $parid])->limit($page->firstRow . ',' . $page->listRows)->order('ordering desc')->select();
	// 	$show = $page->show();
	// 	$this->assign('page', $show);
	// 	$this->assign('allarr', $arr);
	// 	$p = I('get.p', 1);

	// 	$this->currpage = $p >= 1 ? $p : 1;

	// 	if (empty($arr)) {
	// 		$this->error('暂无内容', '/');die;
	// 	}

	// 	$this->totalpage = $page->totalPages >= 1 ? $page->totalPages : 1;
	// 	// dump($this->totalpage);die;
	// 	$this->display();
	// }
	// public function ad() {
	// 	$this->display();
	// }
	// public function dblike(){
	// 	$kwords = kreq('kwords','');
	// 	$cond['pname'] = ['like' , "%$kwords%"];
	// 	$cond['casno'] = ['like' , "%$kwords%"];
	// 	$cond['purity'] = ['like' , "%$kwords%"];
	// 	$cond['casno'] = ['like' , "%$kwords%"];
	// 	// $cond= array();
	// 	// $cond['unit'] = 'g';
	// 	$cond['_logic'] = 'or';
	// 	$res = D('Products')->relation(true)->where($cond)->select();
	// 	$_GET['p'] = kreq('p','1');;
	// 	dump($_GET);
	// 	dump($res);
	// 	dump(M('products')->_sql());
	// 	// echo M()->getLastSql();
	// 	// echo M('products')->getLastSql();
	// 	// echo $Info->getLastSql();
	// }
	// public function a(){
	// 	$res = $res = D('Products')->where(['id'=>1])->relation(true)->find();
	// 	dump($res);
	// }
}