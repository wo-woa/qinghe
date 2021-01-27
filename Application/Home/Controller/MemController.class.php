<?php
namespace Home\Controller;
use Think\Controller;

class MemController extends Controller {
	public function __construct() {
		parent::__construct();
		$this->assign('aflag', ACTION_NAME);
		$this->assign('cflag', CONTROLLER_NAME);
		// header("Content-type:application/json;charset=utf-8");
		//验证后的用户
		// $urlnow = 'http://'.$_SERVER['HTTP_HOST'].__SELF__;
		// $toarr = [ 'info' => 1];
		$agid = kget('agid', 0);
		if ($agid > 0) {
			session('agid', $agid);
		}
		if ($this->tokenok()) {
			// $this->sys = M('config')->where(['cname' => 'site_name'])->find();

			$sys = M('config')->where(['cname' => 'site_name'])->find();
			$this->sys = $sys;

			// $cateid = $this->myself['proid'];
			// $cates = M('cates')->where(['id' => $cateid, 'listid' => 0])->find();

			// if ($cates) {
			// 	$province = $cates['catename'];
			// } else {
			// 	$province = '未绑定';
			// }

			// $sys['cvalue'] = 1;

			// $ag = M('agent')->where(['id' => $this->myself['agentid']])->find();

			// if ($ag) {
			// 	if (empty($ag['rmk'])) {
			// 		$pagetitle = $sys['cvalue'];
			// 	} else {
			// 		$pagetitle = $ag['rmk'];
			// 	}

			// } else {
			// 	// $ag = M('agent')->where(['id' => 1])->find();
			// 	$pagetitle = '未绑定'; //$sys['cvalue'];

			// 	// $pagetitle = $ag['rmk'] . '/' . $province;
			// }

			// if ($cates) {
			// } else {
			// 	$pagetitle =
			// }

			$this->pagetitle = $pagetitle;

			$ag = M('agent')->where(['openid' => $this->myself['openid']])->find();
			if ($ag) {
				$this->shareid = $ag['id'];
				if ($ag['alevel'] == 10 or $ag['alevel'] == 11) {
					$ag['level'] = '一级代理';
				}
				if ($ag['alevel'] == 20) {
					$ag['level'] = '二级代理';
				}
				if ($ag['states'] == 0) {
					$ag['states_name'] = '审核中';
				}
				if ($ag['states'] == 1) {
					$ag['states_name'] = '正常';
				}
				if ($ag['states'] == 2) {
					$ag['states_name'] = '冻结';
				}
				$this->aginfo = $ag;

				if (ACTION_NAME == 'my' or ACTION_NAME == 'todraw') {
					$this->funds = D('Funds')->findone($ag['id']);
				}
			} else {
				$this->shareid = 0;
			}

			D('Fundlog')->todo();

		} else {
			// echo kstinfoapp(2, '用户登录错误！'); //1成功，2token错误，3其它错误, 'id' => 2
			$this->redirect('/Login/getoid');
			die;
		}
	}

	private function tokenok() {
		// $url = 'http://hym2mp.freeer.cn/api/cross/coupon/list?token=hymv2.2022&openid=oZKREw2ltf4_YhwJkI_D_Vy0gYr0';
		// $res = curlget($url);
		// echo ($res);
        $res = kdbfind('member', ['states' => 1]);
        $mytoken = strtoupper(md5($res['appid'] . $res['appsec'] . $randstr));
        $this->meminfo = $this->myself = $res;
        $this->myid = $res['id'];
        return true;

		$istest = F('test');
		if ($istest == 1) {
			$res = kdbfind('member', ['states' => 1]);
			$mytoken = strtoupper(md5($res['appid'] . $res['appsec'] . $randstr));
			$this->meminfo = $this->myself = $res;
			$this->myid = $res['id'];
			return true;
		}

		// $url = 'http://hym2mp.freeer.cn/api/cross/user/create?token=hymv2.2022&openid=' . $res['openid'];
		// $okuser = curlget($url); //建涛创建openid
		// dump($okuser);

		// $hone = M('payment')->where(['memid' => $this->myid, 'okstates' => 1])->find();
		// dump($hone);
		// $url = 'http://hym2mp.freeer.cn/api/cross/coupon/create?token=hymv2.2022&openid=' . $res['openid'] . '&order_id=' . $hone['okorderno'];
		// $ordersok = curlpost($url, []);
		// dump($ordersok);

		// $url = 'http://hym2mp.freeer.cn/api/cross/coupon/list?token=hymv2.2022&openid=' . $res['openid'];
		// $res = curlget($url);
		// echo ($res);
		// die;

		// $token = kpost('token', '');
		// // $token = I('server.HTTP_TOKEN', 'aa22.34');
		// if (empty($token)) {
		// 	return false;
		// }

		// $randstr = kpost('randstr', '');
		// if (empty($randstr)) {
		// 	return false;
		// }

		// $appid = kpost('appid', '');
		// if (empty($appid)) {
		// 	return false;
		// }

		$openid = ksess('openid', 'a0b');
		$res = kdbfind('member', ['openid' => $openid]); //, 'states' => 1
		if ($res) {
			if ($res['states'] == 2) {
				echo "<h1>系统检测，您的账户异常</h1>";die;
			}
			if ($res['states'] == 1) {

				$this->meminfo = $this->myself = $res;
				$this->myid = $res['id'];
				session('users_id', $res['id']);
				return true;
			}
		}
		return false;
	}

	public function index() {
		// dump($this->myself);

		$searchtxt = kget('searchtxt', '');
		if (!empty($searchtxt)) {
			$this->allarr = M('product')->where(['states' => 1, 'stock' => ['gt', 0], 'title' => ['like', '%' . $searchtxt . '%']])->order('ordering asc')->select();

		} else {
			$tp = kget('tp', 0);
			if ($tp < 1) {
				$this->allarr = M('product')->where(['states' => 1, 'stock' => ['gt', 0]])->order('ordering asc')->select();

			} else if ($tp == 1) {
				$this->allarr = M('product')->where(['states' => 1, 'istop' => 1, 'stock' => ['gt', 0]])->order('ordering asc')->select();

			} else if ($tp == 2) {
				$this->allarr = M('product')->where(['states' => 1, 'stock' => ['gt', 0]])->order('addtime asc')->select();

			} else if ($tp == 3) {
				$this->allarr = M('product')->where(['states' => 1, 'stock' => ['gt', 0]])->order('stock desc')->select();

			} else if ($tp == 4) {
				$this->allarr = M('product')->where(['states' => 1, 'stock' => ['gt', 0]])->order('amt asc')->select();

			}
		}
		$this->shtitle = '清和商城';
		// $this->shdesc = $hone['intro'];
		// $this->shimg = WEBROOTURL . $hone['img'];
		$this->shlink = WEBROOTURL . '/Mem/index/agid/' . $this->shareid;
		$this->display();
	}
	public function one() {
		$this->ptitle = '产品详情';

		$this->one = $hone = M('product')->where(['states' => 1, 'stock' => ['gt', 0], 'id' => kget('id', 0)])->find();
		if (!$hone) {
			$this->redirect('/Mem');die;
		}

		// $this->offwork = $offwork = M('config')->where(['cname' => 'sys_offwork'])->find();

		// if (IS_POST) {

		// 	$this->tobook($this->one, $offwork);
		// 	die;
		// }
		$this->display();
	}
	public function onebuy() {
		$this->ptitle = '产品详情';
		$this->one = $hone = M('product')->where(['states' => 1, 'stock' => ['gt', 0], 'id' => kget('id', 0)])->find();
		if (!$hone) {
			$this->redirect('/Mem');die;
		}

		$this->shtitle = $hone['title'];
		$this->shdesc = $hone['intro'];
		$this->shimg = WEBROOTURL . $hone['img'];
		$this->shlink = WEBROOTURL . '/Mem/onebuy/id/' . $hone['id'] . 'agid/' . $this->shareid;

		// $this->offwork = $offwork = M('config')->where(['cname' => 'sys_offwork'])->find();

		// if (IS_POST) {

		// 	$this->tobook($this->one, $offwork);
		// 	die;
		// }
		$this->display();
	}
	public function buyone() {
		$this->ptitle = '购买产品';

		$this->one = $hone = M('product')->where(['states' => 1, 'stock' => ['gt', 0], 'id' => kget('id', 0)])->find();
		if (!$hone) {
			$this->redirect('/');die;
		}

		// $this->offwork = $offwork = M('config')->where(['cname' => 'sys_offwork'])->find();

		// if (IS_POST) {

		// 	$this->tobook($this->one, $offwork);
		// 	die;
		// }
		$this->display();
	}

	public function addcart() {
		$id = kpost('id', 0);
		$num = kpost('num', 0);
		if ($id > 0) {
			$hone = M('product')->where(['id' => $id, 'stock' => ['gt', 0]])->find();
			if ($hone) {
				$cart = D('Cart')->currorder($this->myid, $hone['title']);
				$res = D('Cart')->addcart($this->myid, $hone, $cart, $num);

			}

			//$res = M(CONTROLLER_NAME)->where(['id' => $id])->data()->save();
		}
		$this->ajaxReturn(['status' => $res ? 1 : 2]);
	}

	public function delone() {
		$id = kpost('id', 0);
		$res = M('Cart')->where(['id' => $id, 'states' => 0, 'memid' => $this->myid])->delete();
		$this->ajaxReturn(['status' => $res ? 1 : 2]);
	}
	public function my() {
		$this->display('Mem/my');
	}
	public function carts() {
		$this->maddr = $hone = D('Addr')->findone($this->myid);
		if ($hone) {
			$this->hasaddr = 1;
		} else {
			$this->hasaddr = 0;
		}
		$this->display();
	}
	public function addr() {
		$this->maddr = $hone = D('Addr')->findone($this->myid);
		if ($hone) {
			$this->hasaddr = 1;
		} else {
			$this->hasaddr = 0;
		}
		if (IS_POST) {
			$res = D('Addr')->addone($this->myid);
			$this->ajaxReturn(['status' => $res ? 1 : 2]);
			die;

		}
		$this->display();
	}
	public function addnum() {
		$id = kpost('id', 0);
		$hone = M('Cart')->where(['id' => $id, 'states' => 0, 'memid' => $this->myid])->find();
		if ($hone) {
			$darr = array();
			// $darr['id'] = $hone['id'];
			// $darr['memid'] = $memid;
			// $darr['payid'] = $cart['id'];
			// $darr['proid'] = $hone['id'];
			// $darr['title'] = $hone['title'];
			// $darr['img'] = $hone['img'];
			// $darr['intro'] = $hone['intro'];
			// $darr['punit'] = $hone['punit'];
			// $darr['oamt'] = $hone['oamt'];
			// $darr['amt'] = $hone['amt'];
			$darr['num'] = 1 + $hone['num'];
			$darr['tamt'] = $darr['num'] * $hone['amt'];
			$res = M('Cart')->where(['id' => $id, 'states' => 0, 'memid' => $this->myid])->data($darr)->save();
		}
		$this->ajaxReturn(['status' => $res ? 1 : 2]);
	}
	public function subnum() {
		$id = kpost('id', 0);
		$hone = M('Cart')->where(['id' => $id, 'states' => 0, 'memid' => $this->myid])->find();
		if ($hone) {
			$darr = array();
			// $darr['id'] = $hone['id'];
			// $darr['memid'] = $memid;
			// $darr['payid'] = $cart['id'];
			// $darr['proid'] = $hone['id'];
			// $darr['title'] = $hone['title'];
			// $darr['img'] = $hone['img'];
			// $darr['intro'] = $hone['intro'];
			// $darr['punit'] = $hone['punit'];
			// $darr['oamt'] = $hone['oamt'];
			// $darr['amt'] = $hone['amt'];
			$num = $hone['num'] - 1;
			$darr['num'] = $num;
			$darr['tamt'] = $darr['num'] * $hone['amt'];
			if ($num < 1) {
				$res = M('Cart')->where(['id' => $id, 'states' => 0, 'memid' => $this->myid])->delete();

			} else {
				$res = M('Cart')->where(['id' => $id, 'states' => 0, 'memid' => $this->myid])->data($darr)->save();

			}
		}
		$this->ajaxReturn(['status' => $res ? 1 : 2]);
	}

	public function topay() {

		$payid = kpost('payid', 0);
		$rmk = kpost('rmk', '');

		$carts = D('Cart')->cartinfo($this->myid, $payid);

		// $cond = array();
		// $cond['id'] = $payid;
		// $cond['memid'] = $this->myid;
		// $cond['okstates'] = 0;
		// $cond['orderno'] = $cond['memid'] . '_0';
		// $hone = M('payment')->where($cond)->find();
		// if (!$hone) {
		// 	F('payerr/' . time(), $cond);
		// 	$this->error('数据错误，无此订单。请重试。');
		// 	die;
		// }

		// if ($this->myself['utype'] < 1) {
		// 	$this->redirect('/User/reg');
		// 	die;
		// }
		// $one = M('config')->where(['cname' => 'app_mon'])->find();

		// $amt = 0 + floatval($one['extra']);

		// $amt = 0.01;

		$amt = $carts['tamt'];

		if ($amt < 0.01) {
			F('payerr/' . time(), $carts);
			// $this->error('数据错误。请重试。');
			$this->ajaxReturn(['status' => 2, 'info' => '数据错误。请重试。']);

			die;
		}

		$darr = array();
		// $darr['memid'] = $this->myid;
		// $darr['title'] = '油券198元年会会员';
		// $darr['qty'] = 1;
		// $darr['gunit'] = '次';
		// $darr['unitprice'] = $amt;
		$darr['amt'] = $amt;
		// $darr['paytype'] = 0;
		$darr['orderno'] = date('YmdHis') . rand(10000000, 99999999);
		$darr['ordertime'] = time();
		$darr['okstates'] = 3;
		// $darr['oktime'] = '';
		// $darr['okorderno'] = '';
		// $darr['hidden'] = '';
		// $darr['extra'] = '';
		$darr['rmk'] = $rmk;
		// $darr['pstates'] = '';
		// $darr['prmk'] = '';

		$darr['parid'] = $this->myself['parid'];
		$darr['granid'] = $this->myself['granid'];

		$cond = array();
		$cond['id'] = $payid;
		$cond['memid'] = $this->myid;
		$cond['okstates'] = 0;
		$cond['orderno'] = $cond['memid'] . '_0';

		$add = M('payment')->where($cond)->data($darr)->save();
		if ($add) {
			session('ordid', $payid);
			$this->ajaxReturn(['status' => $add ? 1 : 2, 'info' => '请支付']);

			// $this->redirect('/Wxapp/wxpay');
			die;
			// return $this->where(['uid' => $uid, 'id' => $hascart])->find();
		}

		// $darr = array();
		// $darr['memid'] = $this->myid;
		// $darr['title'] = '油券198元年会会员';
		// $darr['qty'] = 1;
		// $darr['gunit'] = '次';
		// $darr['unitprice'] = $amt;
		// $darr['amt'] = $amt;
		// $darr['paytype'] = 0;
		// $darr['orderno'] = date('YmdHis') . rand(10000000, 99999999);
		// $darr['ordertime'] = time();
		// // $darr['okstates'] = '';
		// // $darr['oktime'] = '';
		// // $darr['okorderno'] = '';
		// // $darr['hidden'] = '';
		// // $darr['extra'] = '';
		// // $darr['rmk'] = '';
		// // $darr['pstates'] = '';
		// // $darr['prmk'] = '';
		// $add = M('payment')->data($darr)->add();
		// if ($add) {
		// 	session('ordid', $add);
		// 	$this->redirect('/Wxapp/wxpay');
		// 	die;
		// 	// return $this->where(['uid' => $uid, 'id' => $hascart])->find();
		// }
		// echo "aaaa";
		// $this->error('数据错误，请重试');
		$this->ajaxReturn(['status' => 2, 'info' => '数据错误。请重试。']);

	}
	public function gopay() {
		$payid = kget('id', 0);
		session('ordid', $payid);
		$this->redirect('/Wxapp/wxpay');

	}

	public function orders() {
		$id = $this->myself['id'];
		$tp = kget('tp', 0);
		if ($tp < 1) {
			$cond = ['memid' => $id, 'okstates' => ['in', '3,1,2']];
		}
		if ($tp == 1) {
			$cond = ['memid' => $id, 'okstates' => 3];

		}
		if ($tp == 2) {
			$cond = ['memid' => $id, 'okstates' => 1];

		}
		if ($tp == 3) {
			$cond = ['memid' => $id, 'pstates' => 1];

		}
		$arr = D('Payment')->where($cond)->relation(true)->order('id desc')->limit(50)->select();

		foreach ($arr as $key => $v) {
			// $num = M('member')->where(['parid' => $v['id']])->count();
			$dbarr = $v['cart'];
			$title = '';
			foreach ($dbarr as $key2 => $db) {
				$title .= $db['title'] . $db['amt'] . $db['punit'] . 'x' . $db['num'] . '=' . $db['tamt'] . '元';
			}
			$arr[$key]['title'] = $title;

			if ($v['okstates'] == 3) {
				$arr[$key]['states_name'] = '待支付';

			}
			if ($v['okstates'] == 2) {
				$arr[$key]['states_name'] = '支付失败';

			}
			if ($v['okstates'] == 1) {
				$arr[$key]['states_name'] = '已付款';
			}

			if ($v['memid'] > 0) {
				$cates = M('addr')->where(['memid' => $v['memid']])->find();
				$arr[$key]['useraddr'] = $cates['realname'] . $cates['tel'] . '<br>' . $cates['addr'];
			} else {
				$arr[$key]['useraddr'] = '-';
			}

		}
		$this->allarr = $arr;
		$this->display();
	}

}