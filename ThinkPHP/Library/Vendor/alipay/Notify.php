<?php
function createLinkstring($para) {
	$arg = "";
	while (list($key, $val) = each($para)) {
		$arg .= $key . "=" . $val . "&";
	}
	//去掉最后一个&字符
	$arg = substr($arg, 0, count($arg) - 2);

	//如果存在转义字符，那么去掉转义
	if (get_magic_quotes_gpc()) {$arg = stripslashes($arg);}

	return $arg;
}
/**
 * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串，并对字符串做urlencode编码
 * @param $para 需要拼接的数组
 * return 拼接完成以后的字符串
 */
function createLinkstringUrlencode($para) {
	$arg = "";
	while (list($key, $val) = each($para)) {
		$arg .= $key . "=" . urlencode($val) . "&";
	}
	//去掉最后一个&字符
	$arg = substr($arg, 0, count($arg) - 2);

	//如果存在转义字符，那么去掉转义
	if (get_magic_quotes_gpc()) {$arg = stripslashes($arg);}

	return $arg;
}
/**
 * 除去数组中的空值和签名参数
 * @param $para 签名参数组
 * return 去掉空值与签名参数后的新签名参数组
 */
function paraFilter($para) {
	$para_filter = array();
	while (list($key, $val) = each($para)) {
		if ($key == "sign" || $key == "sign_type" || $val == "") {
			continue;
		} else {
			$para_filter[$key] = $para[$key];
		}

	}
	return $para_filter;
}
/**
 * 对数组排序
 * @param $para 排序前的数组
 * return 排序后的数组
 */
function argSort($para) {
	ksort($para);
	reset($para);
	return $para;
}
/**
 * 写日志，方便测试（看网站需求，也可以改成把记录存入数据库）
 * 注意：服务器需要开通fopen配置
 * @param $word 要写入日志里的文本内容 默认值：空值
 */
function logResult($word = '') {
	$fp = fopen("log.txt", "a");
	flock($fp, LOCK_EX);
	fwrite($fp, "执行日期：" . strftime("%Y%m%d%H%M%S", time()) . "\n" . $word . "\n");
	flock($fp, LOCK_UN);
	fclose($fp);
}

/**
 * 远程获取数据，POST模式
 * 注意：
 * 1.使用Crul需要修改服务器中php.ini文件的设置，找到php_curl.dll去掉前面的";"就行了
 * 2.文件夹中cacert.pem是SSL证书请保证其路径有效，目前默认路径是：getcwd().'\\cacert.pem'
 * @param $url 指定URL完整路径地址
 * @param $cacert_url 指定当前工作目录绝对路径
 * @param $para 请求的数据
 * @param $input_charset 编码格式。默认值：空值
 * return 远程输出的数据
 */
function getHttpResponsePOST($url, $cacert_url, $para, $input_charset = '') {

	if (trim($input_charset) != '') {
		$url = $url . "_input_charset=" . $input_charset;
	}
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true); //SSL证书认证
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); //严格认证
	curl_setopt($curl, CURLOPT_CAINFO, $cacert_url); //证书地址
	curl_setopt($curl, CURLOPT_HEADER, 0); // 过滤HTTP头
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 显示输出结果
	curl_setopt($curl, CURLOPT_POST, true); // post传输数据
	curl_setopt($curl, CURLOPT_POSTFIELDS, $para); // post传输数据
	$responseText = curl_exec($curl);
	//var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
	curl_close($curl);

	return $responseText;
}

/**
 * 远程获取数据，GET模式
 * 注意：
 * 1.使用Crul需要修改服务器中php.ini文件的设置，找到php_curl.dll去掉前面的";"就行了
 * 2.文件夹中cacert.pem是SSL证书请保证其路径有效，目前默认路径是：getcwd().'\\cacert.pem'
 * @param $url 指定URL完整路径地址
 * @param $cacert_url 指定当前工作目录绝对路径
 * return 远程输出的数据
 */
function getHttpResponseGET($url, $cacert_url) {
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_HEADER, 0); // 过滤HTTP头
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 显示输出结果
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true); //SSL证书认证
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); //严格认证
	curl_setopt($curl, CURLOPT_CAINFO, $cacert_url); //证书地址
	$responseText = curl_exec($curl);
	//var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
	curl_close($curl);

	return $responseText;
}

/**
 * 实现多种字符编码方式
 * @param $input 需要编码的字符串
 * @param $_output_charset 输出的编码格式
 * @param $_input_charset 输入的编码格式
 * return 编码后的字符串
 */
function charsetEncode($input, $_output_charset, $_input_charset) {
	$output = "";
	if (!isset($_output_charset)) {
		$_output_charset = $_input_charset;
	}

	if ($_input_charset == $_output_charset || $input == null) {
		$output = $input;
	} elseif (function_exists("mb_convert_encoding")) {
		$output = mb_convert_encoding($input, $_output_charset, $_input_charset);
	} elseif (function_exists("iconv")) {
		$output = iconv($_input_charset, $_output_charset, $input);
	} else {
		die("sorry, you have no libs support for charset change.");
	}

	return $output;
}
/**
 * 实现多种字符解码方式
 * @param $input 需要解码的字符串
 * @param $_output_charset 输出的解码格式
 * @param $_input_charset 输入的解码格式
 * return 解码后的字符串
 */
function charsetDecode($input, $_input_charset, $_output_charset) {
	$output = "";
	if (!isset($_input_charset)) {
		$_input_charset = $_input_charset;
	}

	if ($_input_charset == $_output_charset || $input == null) {
		$output = $input;
	} elseif (function_exists("mb_convert_encoding")) {
		$output = mb_convert_encoding($input, $_output_charset, $_input_charset);
	} elseif (function_exists("iconv")) {
		$output = iconv($_input_charset, $_output_charset, $input);
	} else {
		die("sorry, you have no libs support for charset changes.");
	}

	return $output;
}
function rsaSign($data, $private_key) {
	//以下为了初始化私钥，保证在您填写私钥时不管是带格式还是不带格式都可以通过验证。
	$private_key = str_replace("-----BEGIN RSA PRIVATE KEY-----", "", $private_key);
	$private_key = str_replace("-----END RSA PRIVATE KEY-----", "", $private_key);
	$private_key = str_replace("\n", "", $private_key);

	$private_key = "-----BEGIN RSA PRIVATE KEY-----" . PHP_EOL . wordwrap($private_key, 64, "\n", true) . PHP_EOL . "-----END RSA PRIVATE KEY-----";

	$res = openssl_get_privatekey($private_key);


	if ($res) {
		openssl_sign($data, $sign, $res);
	} else {
		echo "您的私钥格式不正确!" . "<br/>" . "The format of your private_key is incorrect!";
		exit();
	}
	openssl_free_key($res);
	//base64编码
	$sign = base64_encode($sign);
	return $sign;
}

/**
 * RSA验签
 * @param $data 待签名数据
 * @param $alipay_public_key 支付宝的公钥字符串
 * @param $sign 要校对的的签名结果
 * return 验证结果
 */
function rsaVerify($data, $alipay_public_key, $sign) {
	//以下为了初始化私钥，保证在您填写私钥时不管是带格式还是不带格式都可以通过验证。
	$alipay_public_key = str_replace("-----BEGIN PUBLIC KEY-----", "", $alipay_public_key);
	$alipay_public_key = str_replace("-----END PUBLIC KEY-----", "", $alipay_public_key);
	$alipay_public_key = str_replace("\n", "", $alipay_public_key);

	$alipay_public_key = '-----BEGIN PUBLIC KEY-----' . PHP_EOL . wordwrap($alipay_public_key, 64, "\n", true) . PHP_EOL . '-----END PUBLIC KEY-----';
	$res = openssl_get_publickey($alipay_public_key);
	if ($res) {
		$result = (bool) openssl_verify($data, base64_decode($sign), $res);
	} else {
		echo "您的支付宝公钥格式不正确!" . "<br/>" . "The format of your alipay_public_key is incorrect!";
		exit();
	}
	openssl_free_key($res);
	return $result;
}
class AlipayNotify {
	/**
	 * HTTPS形式消息验证地址
	 */
	var $https_verify_url = 'https://mapi.alipay.com/gateway.do?service=notify_verify&';
	/**
	 * HTTP形式消息验证地址
	 */
	var $http_verify_url = 'http://notify.alipay.com/trade/notify_query.do?';
	var $alipay_config;

	function __construct($alipay_config = '') {

//合作身份者ID，签约账号，以2088开头由16位纯数字组成的字符串，查看地址：https://openhome.alipay.com/platform/keyManage.htm?keyType=partner
		$alipay_config['partner'] = ALI_PARTNER;
//商户的私钥,此处填写原始私钥去头去尾，RSA公私钥生成：https://doc.open.alipay.com/doc2/detail.htm?spm=a219a.7629140.0.0.nBDxfy&treeId=58&articleId=103242&docType=1
		$alipay_config['private_key'] = file_get_contents(THINK_PATH . 'Alipay/rsa_private_key.pem');
//支付宝的公钥
		$alipay_config['alipay_public_key'] = file_get_contents(THINK_PATH . 'Alipay/rsa_public_key.pem');
//收款支付宝账号，以2088开头由16位纯数字组成的字符串，一般情况下收款账号就是签约账号
		$alipay_config['seller_id'] = $alipay_config['partner'];
//签名方式
		$alipay_config['sign_type'] = strtoupper('RSA');
//字符编码格式 目前支持utf-8
		$alipay_config['input_charset'] = strtolower('utf-8');
//ca证书路径地址，用于curl中ssl校验
		//请保证cacert.pem文件在当前文件夹目录中
		$alipay_config['cacert'] = THINK_PATH . 'Alipay/cacert.pem';

//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
		$alipay_config['transport'] = 'http';

// 支付类型 ，无需修改
		$alipay_config['payment_type'] = "1";

// 产品类型，无需修改
		$alipay_config['service'] = "alipay.wap.create.direct.pay.by.user";

		$this->alipay_config = $alipay_config;
	}
	function AlipayNotify($alipay_config) {
		$this->__construct($alipay_config);
	}
	/**
	 * 针对notify_url验证消息是否是支付宝发出的合法消息
	 * @return 验证结果
	 */
	function verifyNotify() {
		if (empty($_POST)) {
//判断POST来的数组是否为空
			return false;
		} else {
			//生成签名结果
			$isSign = $this->getSignVeryfy($_POST, $_POST["sign"]);
			//获取支付宝远程服务器ATN结果（验证是否是支付宝发来的消息）
			$responseTxt = 'false';
			if (!empty($_POST["notify_id"])) {$responseTxt = $this->getResponse($_POST["notify_id"]);}

			//验证
			//$responsetTxt的结果不是true，与服务器设置问题、合作身份者ID、notify_id一分钟失效有关
			//isSign的结果不是true，与安全校验码、请求时的参数格式（如：带自定义参数等）、编码格式有关
			if (preg_match("/true$/i", $responseTxt) && $isSign) {
				return true;
			} else {
				return false;
			}
		}
	}

	/**
	 * 针对return_url验证消息是否是支付宝发出的合法消息
	 * @return 验证结果
	 */
	function verifyReturn() {
		if (empty($_GET)) {
//判断POST来的数组是否为空
			return false;
		} else {
			//生成签名结果
			$isSign = $this->getSignVeryfy($_GET, $_GET["sign"]);
			//获取支付宝远程服务器ATN结果（验证是否是支付宝发来的消息）
			$responseTxt = 'false';
			if (!empty($_GET["notify_id"])) {$responseTxt = $this->getResponse($_GET["notify_id"]);}

			if (preg_match("/true$/i", $responseTxt) && $isSign) {
				return true;
			} else {
				return false;
			}
		}
	}

	/**
	 * 获取返回时的签名验证结果
	 * @param $para_temp 通知返回来的参数数组
	 * @param $sign 返回的签名结果
	 * @return 签名验证结果
	 */
	function getSignVeryfy($para_temp, $sign) {
		//除去待签名参数数组中的空值和签名参数
		$para_filter = paraFilter($para_temp);

		//对待签名参数数组排序
		$para_sort = argSort($para_filter);

		//把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
		$prestr = createLinkstring($para_sort);

		$isSgin = false;
		switch (strtoupper(trim($this->alipay_config['sign_type']))) {
		case "RSA":
			$isSgin = rsaVerify($prestr, trim($this->alipay_config['alipay_public_key']), $sign);
			break;
		default:
			$isSgin = false;
		}

		return $isSgin;
	}

	/**
	 * 获取远程服务器ATN结果,验证返回URL
	 * @param $notify_id 通知校验ID
	 * @return 服务器ATN结果
	 * 验证结果集：
	 * invalid命令参数不对 出现这个错误，请检测返回处理中partner和key是否为空
	 * true 返回正确信息
	 * false 请检查防火墙或者是服务器阻止端口问题以及验证时间是否超过一分钟
	 */
	function getResponse($notify_id) {
		$transport = strtolower(trim($this->alipay_config['transport']));
		$partner = trim($this->alipay_config['partner']);
		$veryfy_url = '';
		if ($transport == 'https') {
			$veryfy_url = $this->https_verify_url;
		} else {
			$veryfy_url = $this->http_verify_url;
		}
		$veryfy_url = $veryfy_url . "partner=" . $partner . "&notify_id=" . $notify_id;
		$responseTxt = getHttpResponseGET($veryfy_url, $this->alipay_config['cacert']);

		return $responseTxt;
	}
}