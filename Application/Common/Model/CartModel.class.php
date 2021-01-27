<?php
namespace Common\Model;
use Think\Model\RelationModel;

class CartModel extends RelationModel {
	// protected $_link = [
	// 	'member' => [
	// 		'mapping_type' => self::BELONGS_TO, //要取自己的某个字字段关联，HAS_ONE要取别的表里某字段关联,
	// 		'class_name' => 'member',
	// 		'mapping_name' => 'member', //查出来的子数组的key名,否则用本数组的key leader，一定要加！
	// 		'foreign_key' => 'memid', //BELONGS_TO要取自己的某个字字段关联，HAS_ONE要取别的表里某字段关联
	// 		// 'mapping_order' => 'id desc', //按哪个排序
	// 		// 'mapping_fields' => 'id,realname,tel', //要查询的字段,不写就全部关联
	// 		// 'as_fields' => '' //不写这个就多一个子数组，key就是mapping_name或者leader
	// 	],
	// ];

	public function addone($tp = 0) {
		die;
		//time();date('Ymd');date('YmdHis');date('Y-m-d');$memid,$payid,$proid,$title,$img,$intro,$punit,$oamt,$amt,$num,$tamt,$ordering,$states,$addtime,
		//kpost('memid', '');kpost('payid', '');kpost('proid', '');kpost('title', '');kpost('img', '');kpost('intro', '');kpost('punit', '');kpost('oamt', '');kpost('amt', '');kpost('num', '');kpost('tamt', '');kpost('ordering', '');kpost('states', '');kpost('addtime', '');
		//kget('memid', '');kget('payid', '');kget('proid', '');kget('title', '');kget('img', '');kget('intro', '');kget('punit', '');kget('oamt', '');kget('amt', '');kget('num', '');kget('tamt', '');kget('ordering', '');kget('states', '');kget('addtime', '');

		$darr = array();

		$darr['memid'] = kpost('memid', ''); //$memid;kget('memid', '');
		$darr['payid'] = kpost('payid', ''); //$payid;kget('payid', '');
		$darr['proid'] = kpost('proid', ''); //$proid;kget('proid', '');
		$darr['title'] = kpost('title', ''); //$title;kget('title', '');
		$darr['img'] = kpost('img', ''); //$img;kget('img', '');
		$darr['intro'] = kpost('intro', ''); //$intro;kget('intro', '');
		$darr['punit'] = kpost('punit', ''); //$punit;kget('punit', '');
		$darr['oamt'] = kpost('oamt', ''); //$oamt;kget('oamt', '');
		$darr['amt'] = kpost('amt', ''); //$amt;kget('amt', '');
		$darr['num'] = kpost('num', ''); //$num;kget('num', '');
		$darr['tamt'] = kpost('tamt', ''); //$tamt;kget('tamt', '');
		$darr['ordering'] = kpost('ordering', ''); //$ordering;kget('ordering', '');
		$darr['states'] = kpost('states', ''); //$states;kget('states', '');
		$darr['addtime'] = kpost('addtime', ''); //$addtime;kget('addtime', '');

		$add = $this->data($darr)->add();
		if ($add) {
			if ($tp == 1) {
				return $this->where(['id' => $add])->find();
			}
			return $add;
		}
		return false;
	}

	public function findone($id = 0) {
		//D('Cart')->findone($id);
		return $this->where(['id' => $id])->find();
	}
	public function lists($cond) {
		//D('Cart')->lists($cond);
		return $this->where($cond)->order('id desc')->select();
	}

	public function hasone($uid = -2) {
		die('Cart数据的model出错');
		$uid = 0 + $uid;
		if ($uid > -1) {
			$hascart = $this->where(['uid' => $uid])->find();
			if ($hascart) {
				return $hascart;
			} else {
				$darr = array();
				$darr['memid'] = '';
				$darr['payid'] = '';
				$darr['proid'] = '';
				$darr['title'] = '';
				$darr['img'] = '';
				$darr['intro'] = '';
				$darr['punit'] = '';
				$darr['oamt'] = '';
				$darr['amt'] = '';
				$darr['num'] = '';
				$darr['tamt'] = '';
				$darr['ordering'] = '';
				$darr['states'] = '';
				$darr['addtime'] = '';
				$hascart = $this->data($darr)->add();
				if ($hascart) {
					return $this->where(['uid' => $uid, 'id' => $hascart])->find();
				}
			}
		}
		return false;
	}
	public function delone($id, $cond) {
		//$cond=['id' => $id];
		return $this->where($cond)->delete();
	}

	public function testaaaa($id, $cond) {
		$darr = array();
		//$darr['id'] =  $hone['id'];$darr['memid'] =  $hone['memid'];$darr['payid'] =  $hone['payid'];$darr['proid'] =  $hone['proid'];$darr['title'] =  $hone['title'];$darr['img'] =  $hone['img'];$darr['intro'] =  $hone['intro'];$darr['punit'] =  $hone['punit'];$darr['oamt'] =  $hone['oamt'];$darr['amt'] =  $hone['amt'];$darr['num'] =  $hone['num'];$darr['tamt'] =  $hone['tamt'];$darr['ordering'] =  $hone['ordering'];$darr['states'] =  $hone['states'];$darr['addtime'] =  $hone['addtime'];

		//$save = M('Cart')->where(['states'=>1,'states'=>['gt',1],'addtime'=>time(),'states'=>['between',$min.','.$max]])->data($darr)->save();

		$hone = M('Cart')->where(['states' => 1, 'states' => ['gt', 1], 'addtime' => time(), 'states' => ['between', $min . ',' . $max]])->select();
	}

	public function addcsv() {
		die;
		$fpath = I('post.fpath', 0);
		// if ($fpath === 0) {
		// 	$this->error('要导入数据请先上传CSV文件！');
		// 	return;
		// }
		$csvpath = APP_ROOTPATH . $fpath; //文件绝对路径
		$carr = $this->csvfile($csvpath);

		$time = time();
		if (!empty($carr)) {
			foreach ($carr as $key => $hone) {
				$darr = array();
				$darr['id'] = $hone['id'];
				$darr['memid'] = $hone['memid'];
				$darr['payid'] = $hone['payid'];
				$darr['proid'] = $hone['proid'];
				$darr['title'] = $hone['title'];
				$darr['img'] = $hone['img'];
				$darr['intro'] = $hone['intro'];
				$darr['punit'] = $hone['punit'];
				$darr['oamt'] = $hone['oamt'];
				$darr['amt'] = $hone['amt'];
				$darr['num'] = $hone['num'];
				$darr['tamt'] = $hone['tamt'];
				$darr['ordering'] = $hone['ordering'];
				$darr['states'] = $hone['states'];
				$darr['addtime'] = $hone['addtime'];
				$res = $this->data($darr)->add();

			}

		}

		return $res;

	}
	private function csvfile($fnm) {
		ini_set('max_execution_time', 1200); //运行时间可到20分钟
		ini_set('auto_detect_line_endings', true);
		$fn = fopen($fnm, 'r');
		$res = array();
		while ($arr = fgetcsv($fn, 0, ',')) {
			$num = count($arr);
			for ($i = 0; $i < $num; $i++) {
				$arr[$i] = iconv('gbk', 'utf-8//IGNORE', $arr[$i]);
			}

			// dump($arr);

			if ($num > 1) {
				// $res[] = $arr;
				$subs = array();
				$subs['id'] = $arr[0];
				$subs['memid'] = $arr[1];
				$subs['payid'] = $arr[2];
				$subs['proid'] = $arr[3];
				$subs['title'] = $arr[4];
				$subs['img'] = $arr[5];
				$subs['intro'] = $arr[6];
				$subs['punit'] = $arr[7];
				$subs['oamt'] = $arr[8];
				$subs['amt'] = $arr[9];
				$subs['num'] = $arr[10];
				$subs['tamt'] = $arr[11];
				$subs['ordering'] = $arr[12];
				$subs['states'] = $arr[13];
				$subs['addtime'] = $arr[14];
				$res[] = $subs;

			}
			// for ($i = 0; $i < $num; $i++) {
			// 	$res[] = iconv('gbk', 'utf-8//IGNORE', $arr[$i]);
			// }
		}
		unset($res[0]);
		fclose($fn);
		return $res;
	}

	public function cartinfo($memid, $payid = 0) {
		$darr = array();
		$darr['memid'] = $memid;
		$darr['orderno'] = $memid . '_0';
		$darr['okstates'] = 0;
		if ($payid > 0) {
			$darr['id'] = $payid;
		}

		$hone = M('payment')->where($darr)->find();
		$arr = ['total' => 0, 'carts' => [], 'tamt' => 0, 'payid' => 0 + $hone['id'], 'pay' => $hone];
		if ($hone) {
			$arr['carts'] = $carr = $this->where(['payid' => $hone['id']])->select();
			$arr['total'] = 0 + count($arr['carts']);

			foreach ($carr as $key => $v) {
				$arr['tamt'] += $v['tamt'];
			}
		}
		return $arr;
	}

	public function currorder($memid, $proname) {
		$darr = array();
		$darr['memid'] = $memid;
		$darr['orderno'] = $memid . '_0';

		// $darr['okstates'] = 0;
		$hone = M('payment')->where($darr)->find();
		if (!$hone) {
			$darr = array();
			$darr['memid'] = $memid;
			$darr['title'] = $proname;
			// $darr['qty'] = 1;
			// $darr['gunit'] = '次';
			// $darr['unitprice'] = $amt;
			$darr['amt'] = 0;
			// $darr['paytype'] = 0;
			// $darr['orderno'] = date('YmdHis') . rand(10000000, 99999999);
			$darr['orderno'] = $memid . '_0';

			// $darr['ordertime'] = time();
			$darr['okstates'] = 0;
			// $darr['oktime'] = '';
			// $darr['okorderno'] = '';
			// $darr['hidden'] = '';
			// $darr['extra'] = '';
			// $darr['rmk'] = '';
			// $darr['pstates'] = '';
			// $darr['prmk'] = '';

			$add = M('payment')->data($darr)->add();
			if ($add) {
				$hone = M('payment')->where(['memid' => $memid, 'id' => $add])->find();
			}
		}
		return $hone;
	}

	public function addcart($memid, $hone, $cart, $num) {
		$darr = array();
		// $darr['id'] = $hone['id'];
		$darr['memid'] = $memid;
		$darr['payid'] = $cart['id'];
		$darr['proid'] = $hone['id'];
		$darr['title'] = $hone['title'];
		$darr['img'] = $hone['img'];
		// $darr['intro'] = $hone['intro'];
		$darr['punit'] = $hone['punit'];
		$darr['oamt'] = $hone['oamt'];
		$darr['amt'] = $hone['amt'];
		$darr['num'] = $num < 1 ? 1 : $num;
		$darr['tamt'] = $darr['num'] * $darr['amt'];
		// $darr['ordering'] = $hone['ordering'];
		$darr['states'] = 0;
		$darr['addtime'] = time();

		$add = $this->data($darr)->add();
		$stock = $hone['stock'] - $darr['num'];
		$stock = $stock < 0 ? 0 : $stock;
		M('product')->where(['id' => $hone['id']])->data(['stock' => $stock])->save(); //库存减少
		return $add;

	}

	public function neworder($myid) {
		return;
		$darr = array();
		$darr['memid'] = $myid;
		$darr['title'] = '';
		// $darr['qty'] = 1;
		// $darr['gunit'] = '次';
		// $darr['unitprice'] = $amt;
		$darr['amt'] = 0;
		// $darr['paytype'] = 0;
		$darr['orderno'] = date('YmdHis') . rand(10000000, 99999999);
		$darr['ordertime'] = time();
		$darr['okstates'] = 0;
		// $darr['oktime'] = '';
		// $darr['okorderno'] = '';
		// $darr['hidden'] = '';
		// $darr['extra'] = '';
		// $darr['rmk'] = '';
		// $darr['pstates'] = '';
		// $darr['prmk'] = '';
		$add = M('payment')->data($darr)->add();
		if ($add) {
			session('ordid', $add);
			$this->redirect('/Wxapp/wxpay');
			die;
			// return $this->where(['uid' => $uid, 'id' => $hascart])->find();
		}
	}

}