<?php
namespace Home\Controller;
// use Home\LoginController;Login
use Think\Controller;

class LoginController extends Controller {
	public function __construct() {
		parent::__construct();
		// $this->ptitle = kdbfield('sys', ['iname' => 'maintitle'], 'ivalue');
		// $this->actv = 1;
	}

	public function easy($cb = '') {
		require_once THINK_PATH . 'vendor/autoload.php';
		$config = [
			'app_id' => SYS_APPID,
			'secret' => SYS_APPSEC,
			// 'key' => '',
			'response_type' => 'array',
			'oauth' => [
				'scopes' => ['snsapi_userinfo'],
				'callback' => $cb,
			],
		];

		$app = \EasyWeChat\Factory::officialAccount($config);
		return $app;
	}
	public function getoid($url = '') {
		$url = kget('url', '');
		$cb = '/Login/setoid?url=' . $url;
		// F('url' . time() . rand(1, 99999), $url);
		$this->easy($cb)->oauth->redirect()->send();
	}
	// public function index() {
	// 	$this->easy()->oauth->redirect()->send();
	// }
	public function setoid($url = '') {
		try {
			$res = $this->easy()->oauth->user()->toArray();
			if (isset($res['original'])) {

				$openid = $res['original']['openid'];
				$hone = 0 + M('member')->where(['openid' => $openid])->count('id');
				if ($hone > 0) {
					session('openid', $openid);
					$url = empty($url) ? '/Mem/index' : $url;
					$this->redirect($url);
					die;
				}
				$res = D('Member')->addopenid($res['original']);
				if ($res) {
					session('openid', $openid);

					// $url = 'http://hym2mp.freeer.cn/api/cross/user/create?token=hymv2.2022&openid=' . $openid;
					// $res = curlget($url); //建涛创建openid

					$url = empty($url) ? '/Mem/index' : $url;
					$this->redirect($url);
					die;
				}
			}
			echo "微信授权登录错误，请重试！";
			die;

		} catch (\Exception $e) {
			echo "微信授权错误，请重试！";
		}
	}

	public function agcfm() {
		$tp = kget('tp', 0);
		$this->easy('/Login/addag/tp/' . $tp)->oauth->redirect()->send();
	}

	public function addag() {
		try {
			$res = $this->easy()->oauth->user()->toArray();
			if (isset($res['original'])) {
				$openid = $res['original']['openid'];
				$wxinfo = $res['original'];
				$tp = kget('tp', 0);

				D('Agent')->addkf($openid, $wxinfo['nickname'], $wxinfo['headimgurl'], $tp);
				die;
				// $hone = 0 + M('member')->where(['openid' => $openid])->count('id');
				// if ($hone > 0) {
				//     session('openid', $openid);

				//     $this->redirect('/User/index');
				//     die;
				// }
				// $res = D('Member')->addopenid($res['original']);
				// if ($res) {
				//     session('openid', $openid);
				//     $this->redirect('/User/index');
				//     die;
				// }
			}
			echo '<h1 style="padding:20px;font-size:30px;text-align:center;color:red;">微信授权登录错误，请重试下！</h1>';
			die;

		} catch (\Exception $e) {
			echo '<h1 style="padding:20px;font-size:30px;text-align:center;color:blue;">微信授权错误，请重试！</h1>';
		}
	}

}