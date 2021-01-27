<?php
namespace Home\Controller;
use Think\Controller;

class UserController extends Controller {
	public function __construct() {
		parent::__construct();
		die;
		// header("Content-type:application/json;charset=utf-8");
		//验证后的用户
		// $urlnow = 'http://'.$_SERVER['HTTP_HOST'].__SELF__;
		// $toarr = [ 'info' => 1];
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

			$ag = M('agent')->where(['id' => $this->myself['agentid']])->find();

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

		} else {
			// echo kstinfoapp(2, '用户登录错误！'); //1成功，2token错误，3其它错误, 'id' => 2
			$this->redirect('/Login/getoid');
			die;
		}
	}

	private function tokenok() {
		$res = kdbfind('member', ['states' => 1]);
		$mytoken = strtoupper(md5($res['appid'] . $res['appsec'] . $randstr));
		$this->meminfo = $this->myself = $res;
		$this->myid = $res['id'];
		return true;
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
				echo "<h1>系统检测，您的账户预约异常，请联系在线客服或微信客服dysy9780 快速解绑。</h1>";die;
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
		echo "string";die;

		$dreg = ksess('dreg', 0);

		if ($dreg == 1) {

			if ($this->myself['utype'] > 0) {
				session('dreg', 0);

				$reurl = ksess('reurl', 0);

				session('reurl', '');
				if (empty($reurl)) {

					$this->error('您的微信已激活，一个微信号只能激活一张权益卡，请使用其他微信号激活。', '/User/index');

				} else {
					$this->error('您的微信已激活，一个微信号只能激活一张权益卡，请使用其他微信号激活。', $reurl);

				}

				// $this->redirect('/User/reg');
				die;
			}

			$this->redirect('/User/reg');
			die;
		}

		//附近景区开始

		$this->ptitle = '附近100景区';

		$ulat = $this->myself['lat'];
		$ulng = $this->myself['lng'];
		if ($ulat > 0) {

			$this->juliok = 1;
			$fields = "id,title,intro,img,content,states_name,ordering,addtime,edittime,cateid,keywords,rmk1,isspecial,rmk2,lat,lng,round(6378.138*2*asin(sqrt(pow(sin( (lat*pi()/180-{$ulat}*pi()/180)/2),2)+cos(lat*pi()/180)*cos({$ulat}*pi()/180)* pow(sin( (lng*pi()/180-{$ulng}*pi()/180)/2),2)))*1000) as juli";

			$this->allarr = M('infos')->where(['states_name' => '显示'])->field($fields)->limit(8)->order('juli asc')->select();
		} else {
			$this->juliok = 0;
			$this->allarr = kdbselect('infos', ['states_name' => '显示'], 'ordering asc', 8);

		}

		//附近景区结束
		$this->display();
	}

	public function setloc() {
		$lng = kpost('lng', 0);
		$lat = kpost('lat', 0);
		if ($lng > 0 && $lat > 0) {
			M('member')->where(['id' => $this->myid])->data(['lng' => $lng, 'lat' => $lat])->save();
		}
	}

	public function topay() {
		// if ($this->myself['utype'] < 1) {
		// 	$this->redirect('/User/reg');
		// 	die;
		// }
		// $one = M('config')->where(['cname' => 'app_mon'])->find();

		// $amt = 0 + floatval($one['extra']);

		$amt = 0.01;

		if ($amt < 0.01) {
			$this->error('数据错误');die;
		}

		$darr = array();
		$darr['memid'] = $this->myid;
		$darr['title'] = '油券198元年会会员';
		$darr['qty'] = 1;
		$darr['gunit'] = '次';
		$darr['unitprice'] = $amt;
		$darr['amt'] = $amt;
		$darr['paytype'] = 0;
		$darr['orderno'] = date('YmdHis') . rand(10000000, 99999999);
		$darr['ordertime'] = time();
		// $darr['okstates'] = '';
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
		// echo "aaaa";
		$this->error('数据错误，请重试');
	}

	public function paylist() {
		$this->dbarr = M('payment')->where(['memid' => $this->myid, 'okstates' => 1])->order('id desc')->select();
		$this->display();
	}

	public function bookinglist() {
		$this->dbarr = D('Booking')->where(['memid' => $this->myid])->relation(true)->order('id desc')->select();
		$this->display();
	}
	public function cancelbooking() {
		$id = kget('id', 0);
		if ($id > 0) {
			$darr = array();

			$darr['states'] = 3;

			// $darr['pstates'] = '';
			// $darr['ptime'] = '';
			// $darr['hidden'] = '';
			// $darr['rmk'] = '';
			$darr['edittime'] = time();
			$darr['prmk'] = '用户手工取消预约';

			$save = D('Booking')->where(['memid' => $this->myid, 'id' => $id, 'states' => ['in', '0,4']])->data($darr)->save();
			if ($save) {
				$this->redirect('/User/bookinglist');
				die;
			}

		}
		$this->error('取消失败！', '/User/my');
	}

	public function reg() {
		if ($this->myself['utype'] > 0) {
			$this->redirect('/User/index');
			die;
		}
		if (IS_POST) {
			$darr = array();
			$darr['cardno'] = kpost('cardno', 'a0');
			$darr['pswd'] = kpost('pswd', 'a0');
			// $darr['batchno'] = $hone['batchno'];
			$darr['states'] = 0;
			// $darr['addtime'] = $hone['addtime'];
			// $darr['bindtime'] = $hone['bindtime'];
			$darr['memid'] = 0;
			$hcard = M('card')->where($darr)->find();
			if (!$hcard) {
				F('nnnreger/r' . time(), ['err' => kpost()]);
				$this->error('当前卡号不存在或已绑定！请联系平台管理员核对！');
				die;
			}

			$url = 'http://apis.juhe.cn/idcard/index?key=5891861475cdbf2264b0ce62c2cc85e8&cardno=';

			$idcardno = kpost('idcardno', '');

			$res = curlGet($url . $idcardno);

			$res = json_decode($res, true);

			if ($res['resultcode'] != 200) {
				F('idcarderr/time' . time(), ['post' => kpost()]);
				$this->error('请输入正确的身份证号'); //$res['result']['reason']
			} else {

			}
			$haveit = M('member')->where(['idcardno' => $idcardno])->find();
			if ($haveit) {
				$this->error('此身份证号已绑定，不可重复绑定！');
			}
			$tel = kpost('tel', '');
			if (empty($tel)) {
				$this->error('必须填写手机号！');
			}
			$haveit = M('member')->where(['tel' => $tel])->find();
			if ($haveit) {
				$this->error('此手机号已绑定，不可重复绑定！');
			}

			$mod = M();
			$mod->startTrans();

			// $res3 = $mod->table('app_amountshop')->add(['uid' => 3323, 'amount' => rand(1, 9999)]);

			$darr = array();
			// $darr['id'] = $hone['id'];
			// $darr['cardno'] = $hone['cardno'];
			// $darr['pswd'] = $hone['pswd'];
			// $darr['batchno'] = $hone['batchno'];
			$darr['states'] = 1;
			// $darr['addtime'] = $hone['addtime'];
			$darr['bindtime'] = time();
			$darr['memid'] = $this->myid;

			$scard = $mod->table('app_card')->where(['states' => 0, 'cardno' => $hcard['cardno']])->data($darr)->save();

			// $save = M('card')->where(['states' => 1, 'states' => ['gt', 1], 'addtime' => time(), 'states' => ['between', $min . ',' . $max]])->data($darr)->save();

			$darr = array();
			// $darr['id'] = kpost('id', '0');
			$darr['utype'] = $hcard['ctype'] + 1;
			$darr['cardno'] = kpost('cardno', 'a0');
			$darr['agentid'] = $hcard['agentid'];
			// $darr['title'] = kpost('title', '0');
			// $darr['img'] = kpost('img', '0');
			// $darr['intro'] = kpost('intro', '0');
			// $darr['desc'] = kpost('desc', '0');
			// $darr['content'] = kpost('content', '0');
			$darr['realname'] = kpost('realname', '');
			$darr['tel'] = kpost('tel', '');
			$darr['idcardno'] = kpost('idcardno', '');
			// $darr['unionid'] = kpost('unionid', '0');
			// $darr['openid'] = kpost('openid', '0');
			// $darr['headimgurl'] = kpost('headimgurl', '0');
			// $darr['nickname'] = kpost('nickname', '0');
			$darr['sex'] = kpost('sex', '0');
			// $darr['addtime'] = kpost('addtime', '0');
			// $darr['states'] = kpost('states', '0');
			// $darr['rmk'] = kpost('rmk', '0');
			// $darr['pstates'] = kpost('pstates', '0');
			// $darr['prmk'] = kpost('prmk', '0');
			$darr['validfrom'] = time();
			$darr['validtime'] = $darr['validfrom'] + 365 * 24 * 3600;
			// $darr['lat'] = kpost('lat', '0');
			// $darr['lng'] = kpost('lng', '0');

			$smem = $mod->table('app_member')->where(['id' => $this->myid])->data($darr)->save();

			if ($hcard && $scard && $smem) {
				$mod->commit();
				$this->success('绑定成功', '/User/my');
				die;
			} else {
				$mod->rollback();
			}
			$this->error('绑定失败');
			die;
		}
		$this->display();
	}

	public function intro() {
		// $this->ptitle = '景区详情';
		$this->one = M('config')->where(['cname' => 'busi_intro'])->find();
		$this->display();
	}

	public function zonelist() {
		$searchtxt = kget('searchtxt', '');

		$ver = kget('ver', 0); //0全部1普通2至尊

		$this->ver = $ver;
		$this->issoso = 0;
		if (!empty($searchtxt)) {
			$this->ptitle = '搜索';
			$this->issoso = 1;

			//先搜索省市的，如果是省的，跳到User/addr显示省份，如果是市的，显示所有市景区

			$haspro = M('cates')->where(['states_name' => '显示', 'listid' => ['eq', 0], 'catename' => ['like', '%' . $searchtxt . '%']])->order('id desc')->find(); //找到省的

			if ($haspro) {
				$this->ptitle = '全省景区';

				//-------------从新把本省的全部显示为1页内--------------
				// $allcity = M('cates')->where(['states_name' => '显示', 'listid' => $haspro['id']])->order('id desc')->select();

				$allcity = M('cates')->where(['states_name' => '显示', 'listid' => $haspro['id']])->getField('id,states_name'); //->order('id desc')->select();
				// dump($allcity);
				$allcity = array_keys($allcity);
				// dump($allcity);
				$this->allarr = $allcitya = M('infos')->where(['states_name' => '显示', 'cateid' => ['in', $allcity]])->order('cateid asc')->select();
				// dump($allcitya);
				// die;
				// $allarr = array();
				// foreach ($allcity as $keyaa => $hascity) {
				// 	$subarr = kdbselect('infos', ['states_name' => '显示', 'cateid' => $hascity['id']], 'ordering asc', 300);
				// 	if ($subarr) {
				// 		// $allarr
				// 	}

				// }

				// $allarr;
				$this->display();
				die;
				// $flag=2;
				// $allcity=M('cates')->where(['states_name'=>'显示','listid'=>$haspro['id']])->order('id desc')->select();//找到市的
				$this->redirect('/User/addr?searchtxt=' . $searchtxt);
				die;

			} else {

				$hascity = M('cates')->where(['states_name' => '显示', 'listid' => ['gt', 0], 'catename' => ['like', '%' . $searchtxt . '%']])->order('id desc')->find(); //找到市的
				if ($hascity) {
					// dump($hascity);die;'title' => ['like', '%' . $searchtxt . '%'],
					$this->allarr = kdbselect('infos', ['states_name' => '显示', 'cateid' => $hascity['id']], 'ordering asc', 300);

					$this->display();
					die;
				}

			}

			//以下是除了省市 的，直接查景区名称

			$this->allarr = kdbselect('infos', ['states_name' => '显示', 'title' => ['like', '%' . $searchtxt . '%']], 'ordering asc', 200);
		} else {
			$cityid = kget('cityid', 0);
			if ($cityid > 0) {
				$this->ptitle = kdbfield('cates', ['id' => $cityid], 'catename');
				if ($ver < 1) {
					$this->allarr = kdbselect('infos', ['states_name' => '显示', 'cateid' => $cityid], 'ordering asc', 300);
				}
				if ($ver == 1) {
					$this->allarr = kdbselect('infos', ['states_name' => '显示', 'cateid' => $cityid, 'isspecial' => 0], 'ordering asc', 300);
				}
				if ($ver == 2) {
					$this->allarr = kdbselect('infos', ['states_name' => '显示', 'cateid' => $cityid, 'isspecial' => 1], 'ordering asc', 300);
				}

			} else {
				if ($ver < 1) {
					$this->ptitle = '附近100景区';

					$ulat = $this->myself['lat'];
					$ulng = $this->myself['lng'];
					if ($ulat > 0) {
						$fields = "id,title,intro,img,content,states_name,ordering,addtime,edittime,cateid,keywords,rmk1,isspecial,rmk2,lat,lng,round(6378.138*2*asin(sqrt(pow(sin( (lat*pi()/180-{$ulat}*pi()/180)/2),2)+cos(lat*pi()/180)*cos({$ulat}*pi()/180)* pow(sin( (lng*pi()/180-{$ulng}*pi()/180)/2),2)))*1000) as juli";

						$this->allarr = M('infos')->where(['states_name' => '显示'])->field($fields)->limit(300)->order('juli asc')->select();
					} else {
						$this->allarr = kdbselect('infos', ['states_name' => '显示'], 'ordering asc', 300);

					}

				}
				if ($ver == 1) {
					$this->ptitle = '黄金版/附近100景区';
					// $this->allarr = kdbselect('infos', ['states_name' => '显示', 'isspecial' => 0], 'ordering desc', 300);
					$ulat = $this->myself['lat'];
					$ulng = $this->myself['lng'];
					if ($ulat > 0) {
						$fields = "id,title,intro,img,content,states_name,ordering,addtime,edittime,cateid,keywords,rmk1,isspecial,rmk2,lat,lng,round(6378.138*2*asin(sqrt(pow(sin( (lat*pi()/180-{$ulat}*pi()/180)/2),2)+cos(lat*pi()/180)*cos({$ulat}*pi()/180)* pow(sin( (lng*pi()/180-{$ulng}*pi()/180)/2),2)))*1000) as juli";

						$this->allarr = M('infos')->where(['states_name' => '显示', 'isspecial' => 0])->field($fields)->limit(300)->order('juli asc')->select();
					} else {
						$this->allarr = kdbselect('infos', ['states_name' => '显示', 'isspecial' => 0], 'ordering asc', 300);

					}

				}
				if ($ver == 2) {
					$this->ptitle = '至尊版/附近100景区';
					// $this->allarr = kdbselect('infos', ['states_name' => '显示', 'isspecial' => 1], 'ordering desc', 300);

					$ulat = $this->myself['lat'];
					$ulng = $this->myself['lng'];
					if ($ulat > 0) {
						$fields = "id,title,intro,img,content,states_name,ordering,addtime,edittime,cateid,keywords,rmk1,isspecial,rmk2,lat,lng,round(6378.138*2*asin(sqrt(pow(sin( (lat*pi()/180-{$ulat}*pi()/180)/2),2)+cos(lat*pi()/180)*cos({$ulat}*pi()/180)* pow(sin( (lng*pi()/180-{$ulng}*pi()/180)/2),2)))*1000) as juli";

						$this->allarr = M('infos')->where(['states_name' => '显示', 'isspecial' => 1])->field($fields)->limit(300)->order('juli asc')->select();
					} else {
						$this->allarr = kdbselect('infos', ['states_name' => '显示', 'isspecial' => 1], 'ordering asc', 300);

					}
				}

			}
		}
		$this->display();
	}

}