<?php
namespace Home\Controller;

class AgentController extends MemController {
	public function __construct() {
		parent::__construct();
	}

	public function todraw() {
		if (IS_POST) {
			$amt = kpost('amt', 0);
			if ($amt < 0.01 or $amt > $this->funds['amt']) {
				$this->ajaxReturn(['status' => 2, 'info' => '金额错误']);

			}
			$res = D('Fundlog')->todraw($this->aginfo['id'], $amt);
			$this->ajaxReturn(['status' => $res ? 1 : 2, 'info' => '操作成功']);
			die;

		}
		$this->display();
	}
	public function myags() {
		$agid = kget('agid', $this->aginfo['id']);
		$arr = M('agent')->where(['parid' => $agid])->order('id desc')->limit(50)->select();

		foreach ($arr as $key => $v) {
			$num = M('member')->where(['parid' => $v['id']])->count();
			$arr[$key]['usernum'] = 0 + $num;
		}
		$this->allarr = $arr;
		$this->display();
	}
	public function myusers() {
		$agid = kget('agid', $this->aginfo['id']);
		$arr = M('member')->where(['parid' => $agid])->order('id desc')->limit(50)->select();

		// foreach ($arr as $key => $v) {
		// 	$num = M('member')->where(['parid' => $v['id']])->count();
		// 	$arr[$key]['usernum'] = 0 + $num;
		// }
		$this->allarr = $arr;
		$this->display();
	}

	public function mydraws() {
		$agid = kget('agid', $this->aginfo['id']);
		$arr = M('fundlog')->where(['agentid' => $agid, 'ifdraw' => 1])->order('id desc')->limit(50)->select();

		foreach ($arr as $key => $v) {
			// $num = M('member')->where(['parid' => $v['id']])->count();
			if ($v['states'] == 0) {
				$arr[$key]['states_name'] = '审核中';

			}if ($v['states'] == 2) {
				$arr[$key]['states_name'] = '提现失败';

			}if ($v['states'] == 1) {
				$arr[$key]['states_name'] = '提现成功';

			}
		}
		$this->allarr = $arr;
		$this->display();
	}

	public function myorders() {
		$agid = kget('agid', $this->aginfo['id']);
		$arr = D('Fundlog')->where(['agentid' => $agid, 'ifdraw' => 0])->relation(true)->order('id desc')->limit(50)->select();

		foreach ($arr as $key => $v) {
			// $num = M('member')->where(['parid' => $v['id']])->count();
			if ($v['states'] == 0) {
				$arr[$key]['states_name'] = '审核中';

			}if ($v['states'] == 2) {
				$arr[$key]['states_name'] = '提现失败';

			}if ($v['states'] == 1) {
				$arr[$key]['states_name'] = '提现成功';

			}
		}
		$this->allarr = $arr;
		$this->display();
	}

}