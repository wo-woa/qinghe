<?php
namespace Vip\Controller;
class AgentController extends IndexController {
	public function __construct() {
		parent::__construct();
		//验证后的用户
		$this->assign('flag', ACTION_NAME);
		$this->assign('ctrlflag', CONTROLLER_NAME);
		$this->etitle = '代理';
		D('Agent')->addlevel();
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
		$darr['username'] = kpost('username', '');
		$darr['pswd'] = kpost('pswd', '');
		$darr['realname'] = kpost('realname', '');
		$darr['tel'] = kpost('tel', '');
		$darr['zone'] = kpost('zone', '');
		$darr['title'] = kpost('title', '');
		$darr['img'] = kpost('img', '');
		$darr['intro'] = kpost('intro', '');
		$darr['desc'] = kpost('desc', '');
		$darr['content'] = kpost('content', '');
		$darr['addtime'] = kpost('addtime', '');
		$darr['states'] = kpost('states', '');
		$darr['openid'] = kpost('openid', '');
		$darr['nickname'] = kpost('nickname', '');
		$darr['parid'] = kpost('parid', '');
		$darr['extra'] = kpost('extra', '');
		$darr['rmk'] = kpost('rmk', '');
		$darr['alevel'] = kpost('alevel', '');

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
		$this->utp = kget('cateid', 0);
		$this->display();
	}

	public function dblike() {
		die;
		$mod = D(CONTROLLER_NAME);
		$kwords = kreq('kwords', 'a.2.3514');

		$cond['id'] = ['like', "%$kwords%"];
		$cond['username'] = ['like', "%$kwords%"];
		$cond['pswd'] = ['like', "%$kwords%"];
		$cond['realname'] = ['like', "%$kwords%"];
		$cond['tel'] = ['like', "%$kwords%"];
		$cond['zone'] = ['like', "%$kwords%"];
		$cond['title'] = ['like', "%$kwords%"];
		$cond['img'] = ['like', "%$kwords%"];
		$cond['intro'] = ['like', "%$kwords%"];
		$cond['desc'] = ['like', "%$kwords%"];
		$cond['content'] = ['like', "%$kwords%"];
		$cond['addtime'] = ['like', "%$kwords%"];
		$cond['states'] = ['like', "%$kwords%"];
		$cond['openid'] = ['like', "%$kwords%"];
		$cond['nickname'] = ['like', "%$kwords%"];
		$cond['parid'] = ['like', "%$kwords%"];
		$cond['extra'] = ['like', "%$kwords%"];
		$cond['rmk'] = ['like', "%$kwords%"];
		$cond['alevel'] = ['like', "%$kwords%"];

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

		$where = [];

		$utp = $keyarr['utp'];
		if ($utp > 0) {
			$where = ['parid' => $utp];
		}
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
			$cond['username'] = ['like', "%$kwords%"];
			$cond['pswd'] = ['like', "%$kwords%"];
			$cond['realname'] = ['like', "%$kwords%"];
			$cond['tel'] = ['like', "%$kwords%"];
			$cond['zone'] = ['like', "%$kwords%"];
			$cond['title'] = ['like', "%$kwords%"];
			$cond['img'] = ['like', "%$kwords%"];
			$cond['intro'] = ['like', "%$kwords%"];
			$cond['desc'] = ['like', "%$kwords%"];
			$cond['content'] = ['like', "%$kwords%"];
			$cond['addtime'] = ['like', "%$kwords%"];
			$cond['states'] = ['like', "%$kwords%"];
			$cond['openid'] = ['like', "%$kwords%"];
			$cond['nickname'] = ['like', "%$kwords%"];
			$cond['parid'] = ['like', "%$kwords%"];
			$cond['extra'] = ['like', "%$kwords%"];
			$cond['rmk'] = ['like', "%$kwords%"];
			$cond['alevel'] = ['like', "%$kwords%"];
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
			// $issub = $arr[$key]['parid'] > 0;

			if ($v['alevel'] !== 20) {
				$arr[$key]['nums'] = 0 + M('agent')->where(['parid' => $v['id']])->count();
			} else {
				$arr[$key]['nums'] = 0;
			}

			if ($v['parid'] > 0) {
				$cates = M('agent')->where(['id' => $v['parid']])->find();
				$arr[$key]['parid'] = $cates['realname'];
			} else {
				$arr[$key]['parid'] = '-';
			}
			// $arr[$key]['istop'] = $arr[$key]['istop'] > 0 ? '推荐' : '-';
			$arr[$key]['addtime'] = date('Y-m-d H:i', $arr[$key]['addtime']);
			if ($v['states'] == 1) {
				$arr[$key]['states'] = layspan('正常', 'danger');
			}
			if ($v['states'] == 2) {
				$arr[$key]['states'] = '禁用';
			}
			if ($v['states'] == 0) {
				$arr[$key]['states'] = layspan('审核中', 'aaa');
			}

			if ($v['alevel'] == 10) {
				$arr[$key]['alevela'] = layspan('一级代理', 'danger');
			}
			if ($v['alevel'] == 20) {
				$arr[$key]['alevela'] = '二级代理';
			}
			if ($v['alevel'] == 11) {
				$arr[$key]['alevela'] = layspan('二级升一级', 'aaa');
			}
			$arr[$key]['img'] = empty($arr[$key]['img']) ? '' : '<img src="' . $arr[$key]['img'] . '" style="width:50px;height:40px;">';

			$fund = D('Funds')->findone($v['id']);
			$amt = $fund['amt'];
			$arr[$key]['fund'] = $amt;

		}

		echo json_encode(['data' => $arr, 'count' => $count, 'msg' => '查询成功', 'code' => 0]);

	}

	public function csvup() {
		die;
		if (IS_POST) {
			$res = D('Agent')->addcsv();
		}
		$this->ajaxReturn(['status' => $res ? 1 : 2]);
	}

}