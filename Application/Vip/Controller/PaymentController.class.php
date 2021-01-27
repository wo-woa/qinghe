<?php
namespace Vip\Controller;
class PaymentController extends IndexController {
	public function __construct() {
		parent::__construct();
		//验证后的用户
		$this->assign('flag', ACTION_NAME);
		$this->assign('ctrlflag', CONTROLLER_NAME);
		$this->etitle = '支付订单';
	}
	public function delone($id = 0) {
		if ($id > 0) {
			$hone = M(CONTROLLER_NAME)->where(['id' => $id])->find();
			F('vipdel/' . CONTROLLER_NAME . '_' . ($id > 0 ? $id : '0') . '_' . time(), ['data' => $hone, 'admin' => $this->myself]);
			$res = M(CONTROLLER_NAME)->where(['id' => $id])->delete();
		}
		$this->ajaxReturn(['status' => $res ? 1 : 2]);
	}
	public function setnew($id = 0) {
		if ($id > 0) {
			$item = kpost('item', '');
			//$res = M(CONTROLLER_NAME)->where(['id' => $id])->data()->save();
		}
		$this->ajaxReturn(['status' => $res ? 1 : 2]);
	}
	public function allset() {
		die;
		$ids = kpost('ids', '');
		if (!empty($ids)) {
			$res = M(CONTROLLER_NAME)->where(['id' => ['in', $ids]])->data(['states' => 1])->save();
		}
		$this->ajaxReturn(['status' => $res ? 1 : 2]);
	}
	public function allset2() {
		die;
		$ids = kpost('ids', '');
		if (!empty($ids)) {
			$res = M(CONTROLLER_NAME)->where(['id' => ['in', $ids]])->data(['states' => 0])->save();
		}
		$this->ajaxReturn(['status' => $res ? 1 : 2]);
	}
	public function savenew() {
		echo '404';
		die;
		$id = kget('id', 0);
		$this->dbid = $id;
		if ($id > 0) {
			$this->dbres = M(CONTROLLER_NAME)->where(['id' => $id])->find();
		} else {
			$def = array();
			$def['id'] = 0;

			$this->dbres = $def;
		}
		$dbn = strtolower(CONTROLLER_NAME);
		$this->display($vi);
	}

	public function form($vi = 'form', $id = 0) {
		$this->dbid = $id;
		if ($id > 0) {
			$this->dbres = M(CONTROLLER_NAME)->where(['id' => $id])->find();
		} else {
			$def = array();
			$def['id'] = 0;
			$def['states'] = 1;

			$this->dbres = $def;
		}
		//$dbn = strtolower($dbn);
		$this->display($vi);
	}
	public function addsave() {
		F('vipaddsave/' . $dbn . '_' . (kpost('id', 0) > 0 ? kpost('id', 0) : '0') . '_' . time(), ['data' => kpost(), 'admin' => $this->myself]);

		$mod = D(CONTROLLER_NAME);
		$op = I('post.op');
		$ret = $mod->create();
		if (!$ret) {
			exit($mod->getError());
		}
		switch ($op) {
		case 'add':
			$res = $mod->add();
			break;
		case 'save':
			$res = $mod->save();
			break;
		}
		echo '<script>this.parent.location.reload();</script>';die;
		$id = kpost('id', 0);

		$darr = array();
		$darr['memid'] = kpost('memid', '');
		$darr['proid'] = kpost('proid', '');
		$darr['title'] = kpost('title', '');
		$darr['qty'] = kpost('qty', '');
		$darr['gunit'] = kpost('gunit', '');
		$darr['unitprice'] = kpost('unitprice', '');
		$darr['amt'] = kpost('amt', '');
		$darr['paytype'] = kpost('paytype', '');
		$darr['orderno'] = kpost('orderno', '');
		$darr['ordertime'] = kpost('ordertime', '');
		$darr['okstates'] = kpost('okstates', '');
		$darr['oktime'] = kpost('oktime', '');
		$darr['okorderno'] = kpost('okorderno', '');
		$darr['hidden'] = kpost('hidden', '');
		$darr['extra'] = kpost('extra', '');
		$darr['rmk'] = kpost('rmk', '');
		$darr['pstates'] = kpost('pstates', '');
		$darr['prmk'] = kpost('prmk', '');
		$darr['parid'] = kpost('parid', '');
		$darr['granid'] = kpost('granid', '');

		$mod = D(CONTROLLER_NAME);

		$op = I('post.op');
		if ($op == 'add' && $id == 0) {
			$res = $mod->data($darr)->add();
		}
		if ($op == 'save') {
			$res = $mod->where(['id' => $id])->data($darr)->save();
		}

		echo '<script>this.parent.location.reload();</script>';die;
	}
	public function all() {
		$this->utp = kget('utp', -2); //Url上面的urltype用于查询分类
		$this->display();
	}

	public function dblike() {
		die;
		$mod = D(CONTROLLER_NAME);
		$kwords = kreq('kwords', 'a.2.3514');

		$cond['id'] = ['like', "%$kwords%"];
		$cond['memid'] = ['like', "%$kwords%"];
		$cond['proid'] = ['like', "%$kwords%"];
		$cond['title'] = ['like', "%$kwords%"];
		$cond['qty'] = ['like', "%$kwords%"];
		$cond['gunit'] = ['like', "%$kwords%"];
		$cond['unitprice'] = ['like', "%$kwords%"];
		$cond['amt'] = ['like', "%$kwords%"];
		$cond['paytype'] = ['like', "%$kwords%"];
		$cond['orderno'] = ['like', "%$kwords%"];
		$cond['ordertime'] = ['like', "%$kwords%"];
		$cond['okstates'] = ['like', "%$kwords%"];
		$cond['oktime'] = ['like', "%$kwords%"];
		$cond['okorderno'] = ['like', "%$kwords%"];
		$cond['hidden'] = ['like', "%$kwords%"];
		$cond['extra'] = ['like', "%$kwords%"];
		$cond['rmk'] = ['like', "%$kwords%"];
		$cond['pstates'] = ['like', "%$kwords%"];
		$cond['prmk'] = ['like', "%$kwords%"];
		$cond['parid'] = ['like', "%$kwords%"];
		$cond['granid'] = ['like', "%$kwords%"];

		// $cond= array();
		// $cond['unit'] = 'g';->relation(true)
		$cond['_logic'] = 'or';

		$res1 = $mod->where($cond)->select();
		//dump($mod->_sql());

		$conda = ['_logic' => 'AND', 'states' => 1, $cond];
		//dump($conda);

		//$res2 = $mod->where($conda)->select();
		// $_GET['p'] = kreq('p', '1');

		//dump($res2);
	}

	public function showall() {
		$limit = kget('limit', 10);
		$field = kget('field', '');
		$order = kget('order', '');
		$_GET['p'] = kget('page', 1);

		$keyarr = kget('key');
		// $ptype = $keyarr['ptype'];
		// if ($ptype > 0) {
		// 	$cond = ['cateid' => $ptype];
		// } else {

		// }

		$where = ['okstates' => 1];

		//$utp = $keyarr['utp'];
		//if ($utp > -1) {
		//	$where = ['states' => $utp];
		//}
		//if ($utp == -1) {
		//	$where = ['states' => 0];
		//}

		//if ($utp == 100) {
		//	$where = [];
		//}

		$kwords = $keyarr['ksearch'];
		if (!empty($kwords)) {
			$cond = [];
			$cond['id'] = $kwords;
			$cond['id'] = ['like', "%$kwords%"];
			$cond['memid'] = ['like', "%$kwords%"];
			$cond['proid'] = ['like', "%$kwords%"];
			$cond['title'] = ['like', "%$kwords%"];
			$cond['qty'] = ['like', "%$kwords%"];
			$cond['gunit'] = ['like', "%$kwords%"];
			$cond['unitprice'] = ['like', "%$kwords%"];
			$cond['amt'] = ['like', "%$kwords%"];
			$cond['paytype'] = ['like', "%$kwords%"];
			$cond['orderno'] = ['like', "%$kwords%"];
			$cond['ordertime'] = ['like', "%$kwords%"];
			$cond['okstates'] = ['like', "%$kwords%"];
			$cond['oktime'] = ['like', "%$kwords%"];
			$cond['okorderno'] = ['like', "%$kwords%"];
			$cond['hidden'] = ['like', "%$kwords%"];
			$cond['extra'] = ['like', "%$kwords%"];
			$cond['rmk'] = ['like', "%$kwords%"];
			$cond['pstates'] = ['like', "%$kwords%"];
			$cond['prmk'] = ['like', "%$kwords%"];
			$cond['parid'] = ['like', "%$kwords%"];
			$cond['granid'] = ['like', "%$kwords%"];
			$cond['_logic'] = 'or';

			$where['_logic'] = 'AND';
			$where[] = $cond;

			$where['id'] = ['gt', 0];

			//$where = ['_logic' => 'AND',  'id' => , $cond];//'states' => 1,
		}

		// $vartime = kget('ftime', 0);
		// if ($ftp == 2) {
		// 	$timearr = explode(' - ', $vartime);
		// 	$ftime = strtotime($timearr[0]);
		// 	$ttime = strtotime($timearr[1]); // + 3600 * 24 - 1;
		// }
		// if ($ftp == 1) {
		// 	$ttime = strtotime(date('Ymd', time() + 3600 * 24)) - 1;
		// 	$ftime = strtotime(date('Ymd', time()));
		// }
		//$tstr = $ftime . ',' . $ttime;
		//$this->tstr = $tstr;
		//$this->timestr = $timestr = date('Y-m-d H:i:s', $ftime) . ' - ' . date('Y-m-d H:i:s', $ttime);

		$mod = D(CONTROLLER_NAME);
		$count = $mod->where($where)->count();
		$page = new \Think\Page($count, $limit);

		if (!empty($field)) {
			$arr = $mod->where($where)->relation(true)->limit($page->firstRow . ',' . $page->listRows)->order($field . '  ' . $order)->select();
		} else {
			$arr = $mod->where($where)->relation(true)->limit($page->firstRow . ',' . $page->listRows)->order('id desc')->select();
		}

		// if (!empty($field)) {
		// 	if ($cateid == -1) {
		// 		$order = 'itemid asc,id asc';
		// 		$field = '';

		// 	}
		// 	$arr = $mod->where($cond)->relation(true)->limit($page->firstRow . ',' . $page->listRows)->order($field . '  ' . $order)->select();
		// } else {
		// 	$newor = $cateid == 0 ? 'bookday asc,id asc' : 'bookday desc,id desc';
		// 	if ($cateid == -1) {
		// 		$newor = 'itemid asc,id asc';
		// 	}

		// 	$arr = $mod->where($cond)->relation(true)->limit($page->firstRow . ',' . $page->listRows)->order($newor)->select();
		// }

		foreach ($arr as $key => $v) {
			if ($v['memid'] > 0) {
				$cates = M('addr')->where(['memid' => $v['memid']])->find();
				$arr[$key]['memid'] = $cates['realname'] . '<br>' . $cates['tel'] . '<br>' . $cates['addr'];
			} else {
				$arr[$key]['memid'] = '-';
			}
			// dump($v);
			$dbarr = $v['cart'];
			$title = '';
			foreach ($dbarr as $key2 => $db) {
				$title .= $db['title'] . $db['amt'] . $db['punit'] . 'x' . $db['num'] . '=' . $db['tamt'] . '元';
			}

			$arr[$key]['title'] = $title;
			$arr[$key]['pstates'] = $arr[$key]['pstates'] == 1 ? '已结算' : '-';
			// $arr[$key]['okstates'] = $arr[$key]['okstates'] == 1 ? '已支付' : '-';
			$arr[$key]['oktime'] = $v['amt'] . '<br>' . '已支付' . '<br>' . date('Y-m-d H:i', $arr[$key]['oktime']) . $v['okorderno'];

			$arr[$key]['parid'] = $v['agent']['realname'] . '<br>' . $v['agent']['tel'];

			// if ($v['states'] == 1) {
			// 	$arr[$key]['states'] = layspan('正常','danger');
			// }
			// if ($v['states'] == 2) {
			// 	$arr[$key]['states'] = '禁用';
			// }
			// if ($v['states'] == 0) {
			// 	$arr[$key]['states'] = layspan('审核中','aaa');
			// }
			// $arr[$key]['img'] =empty($arr[$key]['img'])? '' : '<img src="' . $arr[$key]['img'] . '" style="width:50px;height:40px;">';

		}

		echo json_encode(['data' => $arr, 'count' => $count, 'msg' => '查询成功', 'code' => 0]);

	}

	public function csvup() {
		die;
		if (IS_POST) {
			$res = D('Payment')->addcsv();
		}
		$this->ajaxReturn(['status' => $res ? 1 : 2]);
	}

}