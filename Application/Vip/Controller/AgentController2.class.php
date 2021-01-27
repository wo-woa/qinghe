<?php
namespace Vip\Controller;
class AgentController extends IndexController {
	public function __construct() {
		parent::__construct();
		//验证后的用户
		$this->assign('flag', ACTION_NAME);
		$this->assign('ctrlflag', CONTROLLER_NAME);
		$this->etitle = '代理';
	}
	public function delone($id = 0) {
		// die('删除功能暂禁用，您可以禁用代理来代替删除功能。');
		if ($id > 0) {
			$res = M(CONTROLLER_NAME)->where(['id' => $id])->delete();
		}
		$this->ajaxReturn(['status' => $res ? 1 : 2]);
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
		$dbn = strtolower($dbn);
		$this->display($vi);
	}
	public function addsave($dbn = CONTROLLER_NAME) {
		F('viplogs/' . $dbn . '_' . (kpost('id', 0) > 0 ? kpost('id', 0) : '0') . '_' . time(), kpost());
		$mod = D($dbn);
		$op = I('post.op');
		$ret = $mod->create();
		if (!$ret) {
			exit($mod->getError());
		}
		switch ($op) {
		case 'add':
			$res = $mod->add();
			if ($res) {
				D('Ads')->addad($res);
			}
			break;
		case 'save':
			$res = $mod->save();
			break;
		}
		echo '<script>this.parent.location.reload();</script>';die;
	}
	public function all() {
		$this->display();
	}

	public function dblike() {
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
		$cond['extra'] = ['like', "%$kwords%"];
		$cond['rmk'] = ['like', "%$kwords%"];

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
		// $gameid = $keyarr['gameid'];
		// $cateid = $keyarr['ptype'];

		// if ($cateid > 0) {
		// 	$cond = ['cateid' => $cateid];
		// } else {

		// }
		$cond = [];

		$kwords = $keyarr['ksearch'];

		if (!empty($kwords)) {

			$cond['username'] = ['like', "%$kwords%"];

			// $cond['_logic'] = 'or';
		}
		$mod = D(CONTROLLER_NAME);
		$count = $mod->where($cond)->count();
		$page = new \Think\Page($count, $limit);

		if (!empty($field)) {
			$arr = $mod->where($cond)->limit($page->firstRow . ',' . $page->listRows)->order($field . '  ' . $order)->select();
		} else {
			$arr = $mod->where($cond)->limit($page->firstRow . ',' . $page->listRows)->order('id desc')->select();
		}

		$card = M('agent');

		foreach ($arr as $key => $v) {
			// if ($v['cateid'] > 0) {
			// 	$cates = M('cate')->find($v['cateid']);
			// 	$arr[$key]['cateid'] = $cates['cname'];
			// } else {
			// 	$arr[$key]['cateid'] = '-';
			// }
			// $arr[$key]['istop'] = $arr[$key]['istop'] > 0 ? '推荐' : '-';
			$arr[$key]['addtime'] = date('Y-m-d H:i', $arr[$key]['addtime']);
			if ($v['states'] == 1) {
				$arr[$key]['states'] = layspan('正常');
			}
			if ($v['states'] == 2) {
				$arr[$key]['states'] = '冻结';
			}
			if ($v['states'] == 0) {
				$arr[$key]['states'] = '-';
			}

			$ok = 0 + $card->where(['agentid' => $v['id'], 'states' => ['eq', 1]])->count();
			$total = 0 + $card->where(['agentid' => $v['id'], 'states' => ['gt', -1]])->count();
			$arr[$key]['cardnum'] = $ok . ' / ' . $total;

			// $arr[$key]['img'] = '<img src="' . $arr[$key]['img'] . '" alt="' . $arr[$key]['title'] . '" style="width:50px;height:40px;">';

		}

		echo json_encode(['data' => $arr, 'count' => $count, 'msg' => '查询成功', 'code' => 0]);

	}

}