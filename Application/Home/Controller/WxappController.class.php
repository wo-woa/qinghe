<?php
namespace Home\Controller;
// use Home\LoginController;Login
use Think\Controller;

class WxappController extends Controller {
	// use AppmemwxappTrait;

	public function __construct() {
		parent::__construct();
		// $this->ptitle = '微信支付';
		// // F('wxapp/post_' . ACTION_NAME . rand(1, 999999999), kpost());
		// // F('wxapp/get_' . ACTION_NAME . rand(1, 999999999), kget());
		// $data = array();
		// $data["appid"] = 'ab';
		// $data["mch_id"] = 'a23';
		// $data["key"] = 'cbeeb33efdc50963129f48fec261ceee';
		// $this->wxconf = $data;
	}

	// public function easywxpay() {
	// 	require_once THINK_PATH . 'vendor/autoload.php';
	// 	$config = [
	// 		'app_id' => SYS_APPID,
	// 		// 'secret' => WX_SEC,
	// 		'mch_id' => '1562384861',
	// 		'key' => '20191113wwwtxynpcom20191113wwwtx',
	// 		// 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
	// 		// 'cert_path' => THINK_PATH . 'vendor/cert/apiclient_cert.pem',
	// 		// 'key_path' => THINK_PATH . 'vendor/cert/apiclient_key.pem',
	// 		'log' => [
	// 			'level' => 'debug',
	// 			'file' => RUNTIME_PATH . 'Wechatlogpay/' . date('YmdH') . '.log',
	// 		],
	// 	];
	// 	$app = \EasyWeChat\Factory::payment($config);
	// 	return $app;
	// }

	public function pretest($arr = []) {
		die;
		// $result:
		//{
		//    "return_code": "SUCCESS",
		//    "return_msg": "OK",
		//    "appid": "wx2421b1c4390ec4sb",
		//    "mch_id": "10000100",
		//    "nonce_str": "IITRi8Iabbblz1J",
		//    "openid": "oUpF8uMuAJO_M2pxb1Q9zNjWeSs6o",
		//    "sign": "7921E432F65EB8ED0CE9755F0E86D72F2",
		//    "result_code": "SUCCESS",
		//    "prepay_id": "wx201411102639507cbf6ffd8b0779950874",
		//    "trade_type": "JSAPI"
		//}
		$arr = [
			'body' => '测试支付',
			'out_trade_no' => 'test0001',
			'total_fee' => 1,
			// 'spbill_create_ip' => '123.12.12.123', // 可选，如不传该参数，SDK 将会自动获取相应 IP 地址
			'notify_url' => WEBROOTURL . '/Wxapp/noti',
			'trade_type' => 'JSAPI', // 请对应换成你的支付方式对应的值类型
			'openid' => 'oBTWouLyLO1bmZTpirWMkYzCBg78',
		];
		// $res = $this->easywxpay()->order->unify($arr);
		// dump($res);
		// echo "jssdk:";

		$this->mon = 1;
		$this->json = $this->easywxpay()->jssdk->bridgeConfig('wx2621045396844337bfc66f9e4005399321');
		// dump($res);

		// die;

		$this->display('Wxapp/pay');
	}

	public function indexindex() {
		echo "aaaeee";
	}
	public function wxpay() {
		$id = ksess('ordid', 0);
		if ($id > 0) {
			$arr = M('Payment')->where(['id' => $id, 'okstates' => 3])->find();
			if ($arr) {
				$this->mon = ktofen($arr['amt']);
				$this->json = D('Payment')->prepay($arr);
				$this->display('Wxapp/pay');
				die;
			}
		}
	}
	public function noti() {
		$url = WEBROOTURL . '/Wxapp/noti';
		F('notiurl', $url);
		ini_set('max_execution_time', 30);
		D('Payment')->noti();
	}
	// public function refnoti() {
	// 	ini_set('max_execution_time', 30);
	// 	D('Payment')->refnoti();
	// }
	// public function todo() {
	// 	ini_set('max_execution_time', 4800);
	// 	D('Team')->todo_clear48();
	// 	D('Payment')->todo_refund();
	// 	echo "succ";
	// }
	// 	public function wxappnotify() {
	// 		$xml = file_get_contents("php://input");
	// 		//Log::write('WeiXinBuy_Notify---'.$xml, Log::INFO,Log::FILE);
	// 		$data = $this->xml2array($xml);
	// 		$flag = $this->validate($data);

// 		if ($flag) {
	// 			$order['states'] = 1; //0新增1成功2失败
	// 		} else {
	// 			$order['states'] = 2; //0新增1成功2失败
	// 		}

// 		if ($flag) {

// 			if ($data['result_code'] === 'SUCCESS' && $data['return_code'] === 'SUCCESS') {
	// 				$darr = array();
	// 				$darr['states'] = 1;
	// 				$darr['payorderno'] = $data['transaction_id'];
	// 				$darr['paytime'] = time();
	// 				// $darr['ymd'] = date('Ymd', $darr['oktime']);

// 				$res = M('recharge')->where(['states' => 0, 'paytype' => 1, 'myorderno' => $data['out_trade_no']])->data($darr)->save();
	// 				if (!$res) {
	// 					F('wxapp/aaaerr_' . time() . rand(1, 99999), $data);
	// 				}
	// 			} else {
	// 				$darr = array();
	// 				// $darr['okorderno'] = $trade_no;
	// 				// $darr['okpost'] = kjson($data);
	// 				// $darr['validity'] = 0;
	// 				$darr['states'] = 2;
	// 				$darr['paytime'] = time();
	// 				// $darr['ymd'] = date('Ymd', $darr['oktime']);
	// 				// $darr['pstates'] = 0;
	// 				M('recharge')->where(['states' => 0, 'paytype' => 1, 'myorderno' => $data['out_trade_no']])->data($darr)->save();
	// 			}

// 			F('wxapp/checkok_' . time(), $data);
	// 			$this->response_back("SUCCESS", "OK");
	// 		} else {
	// 			F('wxapp/err_check' . time(), $data);
	// 			F('wxapp/err_' . ACTION_NAME . rand(1, 999999999), kpost());
	// 			$this->response_back("FAIL", "签名失败");
	// 		}
	// 	}

// 	// {"appid":"wx435a3ad4732a25a0","mch_id":"1404696302","nonce_str":"mji7fdhq9ugr0ekwxs6o1z38tl5cbyvn","body":"\u8d2d\u4e703\u5f20\u623f\u5361","out_trade_no":"2017052490840","total_fee":1200,"spbill_create_ip":"61.52.139.200","notify_url":"http:\/\/47.92.95.25\/admin\/PayApi\/WeiXinBuy_Notify","trade_type":"APP"}

// 	public function topay() {
	// 		// die('数据错，联系技术');

// 		$total_amount = 0.01;

// 		$orderno = date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
	// 		F('wxapp/myorderno_' . $orderno, $orderno);

// 		$body = "测试";

// 		$data = array();
	// 		$data["appid"] = $this->wxconf['appid'];
	// 		$data["mch_id"] = $this->wxconf['mch_id'];
	// 		$data["nonce_str"] = $this->getNonceStr();
	// 		$data["body"] = $body;
	// 		$data["out_trade_no"] = $orderno;
	// 		$data["total_fee"] = $total_amount * 100;
	// 		$data["spbill_create_ip"] = $this->get_client_ip();
	// 		$data["notify_url"] = WEBROOTURL . '/Wxapp/WeiXinBuy_Notify';
	// 		$data["trade_type"] = 'APP';
	// 		$response = $this->post('https://api.mch.weixin.qq.com/pay/unifiedorder', $data);

// 		//插入订单记录(未支付)
	// 		// $orderModel = model('Recharge');
	// 		// $params['paytype'] = 1; //1微信2支付宝3后台
	// 		// $params['memtype'] = 2; //会员类型1代理2会员
	// 		// $params['memid'] = $userid; //用户ID
	// 		// $params['orderno'] = $orderno; //订单号我
	// 		// $params['states'] = 0; //0新增1成功2失败
	// 		// $params['pstates'] = 0; //0新1平台已处理2需处理
	// 		// $params['unitprice'] = $total_amount; //单价
	// 		// $params['quantity'] = $subject; //数量
	// 		// $params['amount'] = $total_amount; //金额元
	// 		// $params['addtime'] = time(); //添加时间
	// 		// $params['ipost'] = json_encode($data); //发出数据
	// 		// $params['rmk'] = $body; //备注
	// 		// $orderModel->save($params);

// 		// dump($response);

// 		$data = array();
	// 		$data["appid"] = $this->wxconf['appid'];
	// 		$data["partnerid"] = $this->wxconf['mch_id'];
	// 		$data["noncestr"] = $this->getNonceStr();
	// 		$data["prepayid"] = $response["prepay_id"];
	// 		$data["package"] = 'Sign=WXPay';
	// 		$data["timestamp"] = time();
	// 		$data["sign"] = $this->sign($data);
	// 		// $data["body"] = $response["body"];
	// 		// $data["body"] = $response["body"];

// 		// $data["out_trade_no"] = $orderno;
	// 		// $data["total_fee"] = $total_amount * 100;

// 		// $res = array_merge($response, $data);

// 		// // dump($res);

// 		// $data = array();
	// 		// $data["appid"] = $this->wxconf['appid'];
	// 		// $response[''];

// 		echo kstinfoapp(1, '微信支付提交数据', $data);
	// 		die;

// 		// header("Access-Control-Allow-Origin: *");
	// 		// return json($response);
	// 	}
	// //_testok
	// 	public function WeiXinBuy_Notify() {
	// 		$xml = file_get_contents("php://input");
	// 		//Log::write('WeiXinBuy_Notify---'.$xml, Log::INFO,Log::FILE);
	// 		$data = $this->xml2array($xml);
	// 		$flag = $this->validate($data);

// 		if ($flag) {
	// 			$order['states'] = 1; //0新增1成功2失败
	// 		} else {
	// 			$order['states'] = 2; //0新增1成功2失败
	// 		}

// 		if ($flag) {
	// 			F('wxapp/checkok_' . time(), $data);
	// 			$this->response_back("SUCCESS", "OK");
	// 		} else {
	// 			F('wxapp/checkerr_' . time(), $data);
	// 			$this->response_back("FAIL", "签名失败");
	// 		}
	// 	}
	// 	public function WeiXinBuy_Notify222() {
	// 		$xml = file_get_contents("php://input");
	// 		//Log::write('WeiXinBuy_Notify---'.$xml, Log::INFO,Log::FILE);
	// 		$data = $this->xml2array($xml);
	// 		$flag = $this->validate($data);

// 		//$userid = $data["userid"];
	// 		$total_fee = $data["total_fee"];
	// 		$out_trade_no = $data["out_trade_no"];
	// 		$trade_no = $data["transaction_id"];

// 		//修改订单记录状态
	// 		$orderModel = model("Recharge");
	// 		$ordergemsnum = $orderModel->where('orderno', $out_trade_no)->value('quantity');
	// 		$userid = $orderModel->where('orderno', $out_trade_no)->value('memid');
	// 		if ($flag) {
	// 			$order['states'] = 1; //0新增1成功2失败
	// 		} else {
	// 			$order['states'] = 2; //0新增1成功2失败
	// 		}
	// 		$order['oktime'] = time(); //确认时间
	// 		$order['ymd'] = date('Ymd'); //确认年月日
	// 		$order['okpost'] = json_encode($data); //回调数据
	// 		$order['okorderno'] = $trade_no; //回调数据
	// 		$orderModel->where("orderno", $out_trade_no)->update($order);
	// 		if ($flag) {
	// 			//给用户添加房卡
	// 			$userModel = model("Users");
	// 			$usergemsnum = $userModel->where('userid', $userid)->value('gems');
	// 			$user['gems'] = $usergemsnum + $ordergemsnum;
	// 			$userModel->where("userid", $userid)->update($user);
	// 			$this->response_back("SUCCESS", "OK");
	// 		} else {
	// 			$this->response_back("FAIL", "签名失败");
	// 		}
	// 	}

// 	/**
	// 	 * 验证数据签名
	// 	 * @param $data 数据数组
	// 	 * @return 数据校验结果
	// 	 */
	// 	public function validate($data) {
	// 		if (!isset($data["sign"])) {
	// 			return false;
	// 		}
	// 		$sign = $data["sign"];
	// 		unset($data["sign"]);
	// 		return $this->sign($data) == $sign;
	// 	}

// 	/**
	// 	 * 响应微信支付后台通知
	// 	 * @param string $return_code 返回状态码 SUCCESS/FAIL
	// 	 * @param $return_msg  返回信息
	// 	 */
	// 	public function response_back($return_code = "SUCCESS", $return_msg = null) {
	// 		$data = array();
	// 		$data["return_code"] = $return_code;
	// 		if ($return_msg) {
	// 			$data["return_msg"] = $return_msg;
	// 		}
	// 		$xml = $this->array2xml($data);
	// 		print $xml;
	// 	}

// 	private function getNonceStr() {
	// 		return substr(str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789"), 0, 32);
	// 	}

// 	private function post($url, $data, $cert = false) {
	// 		if (!isset($data['sign'])) {
	// 			$data["sign"] = $this->sign($data);
	// 		}

// 		$xml = $this->array2xml($data);
	// 		$ch = curl_init();
	// 		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	// 		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	// 		curl_setopt($ch, CURLOPT_POST, 1);
	// 		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
	// 		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	// 		curl_setopt($ch, CURLOPT_URL, $url);
	// 		// if ($cert == true)
	// 		// {
	// 		//     //使用证书：cert 与 key 分别属于两个.pem文件
	// 		//     curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
	// 		//     curl_setopt($ch, CURLOPT_SSLCERT, $this->_config['sslcertPath']);
	// 		//     curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'PEM');
	// 		//     curl_setopt($ch, CURLOPT_SSLKEY, $this->_config['sslkeyPath']);
	// 		// }
	// 		$content = curl_exec($ch);
	// 		$array = $this->xml2array($content);
	// 		return $array;
	// 	}

// 	private function get_client_ip() {
	// 		if (!empty($_SERVER['REMOTE_ADDR'])) {
	// 			$ip = $_SERVER['REMOTE_ADDR'];
	// 		} else {
	// 			$ip = defined('PHPUNIT_RUNNING') ? '127.0.0.1' : gethostbyname(gethostname());
	// 		}

// 		return filter_var($ip, FILTER_VALIDATE_IP) ?: '127.0.0.1';
	// 	}

// 	/**
	// 	 * 数据签名
	// 	 * @param $data
	// 	 * @return string
	// 	 */
	// 	private function sign($data) {
	// 		ksort($data);
	// 		$string1 = "";
	// 		foreach ($data as $k => $v) {
	// 			if ($v && trim($v) != '') {
	// 				$string1 .= "$k=$v&";
	// 			}
	// 		}
	// 		$stringSignTemp = $string1 . "key=" . $this->wxconf['key'];
	// 		$sign = strtoupper(md5($stringSignTemp));
	// 		return $sign;
	// 	}

// 	private function array2xml($array) {
	// 		$xml = "<xml>" . PHP_EOL;
	// 		foreach ($array as $k => $v) {
	// 			if ($v && trim($v) != '') {
	// 				$xml .= "<$k><![CDATA[$v]]></$k>" . PHP_EOL;
	// 			}

// 		}
	// 		$xml .= "</xml>";
	// 		return $xml;
	// 	}

// 	private function xml2array($xml) {
	// 		$array = array();
	// 		$tmp = null;
	// 		try
	// 		{
	// 			$tmp = (array) simplexml_load_string($xml);
	// 		} catch (Exception $e) {

// 		}
	// 		if ($tmp && is_array($tmp)) {
	// 			foreach ($tmp as $k => $v) {
	// 				$array[$k] = (string) $v;
	// 			}
	// 		}
	// 		return $array;
	// 	}

}