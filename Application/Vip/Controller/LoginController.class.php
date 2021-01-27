<?php
namespace Vip\Controller;
use Think\Controller;

class LoginController extends Controller {
	public function __construct() {
		parent::__construct();
		//验证后的用户
		// $this->admin_user = I('admin_user',0);
		// if(!($this->admin_user > 0)){
		// 	$this->redirect('/Viplogin/index');
		// }

	}
	public function index() {
		if (IS_POST) {
			// $err = I('session.errortimes', 0);
			// if ($err > 3) {
			// 	$this->error('用户名或密码错误超过3次，本IP禁用登录！');
			// 	die;
			// }
			// if (!kverify(I('post.proving'))) {
			// 	$this->error('验证码错误，请重试！');
			// 	exit;
			// }

			if (ksess('vcode', '3.5') !== I('post.proving')) {
				$this->error('验证码错误，请重试！');
				exit;
			}

			$cond['username'] = I('post.username', '2.2');
			$cond['pswd'] = I('post.pswd', '2.2');
			$res = M('admin')->where($cond)->find();
			if ($res) {

				session('admin_user', $res['id']);
				session('admin_userinfo', $res);
				session('admin_type', 0);
				if ($res['username'] == 'aaaaa') {
					session('admin_type', 1);
				}

				$this->redirect('/Vip/index');
				return;
			} else {
				$cond['username'] = I('post.username', '2.a2');
				$cond['pswd'] = I('post.pswd', '2.b2');
				$hasag = M('agent')->where($cond)->find();
				if ($hasag) {
					session('agentid', $hasag['id']);
					$this->redirect('/Vip/User/index');
					return;
				}
				session('admin_user', 0);
				// session('errortimes', $err + 1);
			}
			$this->error('用户名或密码错误！', '', 2);
		}
		$this->display('new');
	}
	//用户退出
	public function uquits() {
		session(null);
		$this->redirect('index');
	}

	public function upload() {

	}
}