<?php
function M2($db) {
	return M($db, '', 'DB2');
}
function get_ip($type = 0) {
	$type = $type ? 1 : 0;
	static $ip = NULL;
	if ($ip !== NULL) {
		return $ip[$type];
	}

	if ($_SERVER['HTTP_X_REAL_IP']) {
//nginx 代理模式下，获取客户端真实IP
		$ip = $_SERVER['HTTP_X_REAL_IP'];
	} elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
//客户端的ip
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
//浏览当前页面的用户计算机的网关
		$arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
		$pos = array_search('unknown', $arr);
		if (false !== $pos) {
			unset($arr[$pos]);
		}

		$ip = trim($arr[0]);
	} elseif (isset($_SERVER['REMOTE_ADDR'])) {
		$ip = $_SERVER['REMOTE_ADDR']; //浏览当前页面的用户计算机的ip地址
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	// IP地址合法验证
	$long = sprintf("%u", ip2long($ip));
	$ip = $long ? array($ip, $long) : array('0.0.0.0', 0);
	return $ip[$type];
}
function layspan($str, $tp = 'danger') {
	return '<span class="layui-btn layui-btn-xs layui-btn-' . $tp . '">' . $str . '</span>';
}
function kconfig($item, $tp = 'cvalue') {
	if (empty($tp)) {
		$tp = 'cvalue';
	}
	$res = M('config')->where(['cname' => $item])->find();
	if ($res) {
		if ($tp == 'content') {
			return htmlspecialchars_decode($res[$tp]);
		}
		return $res[$tp];
	}
	return false;
}

function curlPost($url, $postFields) {
	$postFields = http_build_query($postFields);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
	$result = curl_exec($ch);

	curl_close($ch);
	return $result;
}

function curlGet($url) {
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_TIMEOUT, 500);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	// curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($curl, CURLOPT_URL, $url);

	$res = curl_exec($curl);
	curl_close($curl);

	return $res;
}

//元转分
function ktoyuan($mon) {
	return $mon / 100;
}
function ktofen($mon) {
	return intval(strval($mon * 100));
	// return intval(round($mon * 10, 2) * 10);
}

//生成二维码
function kewm($str = 22, $fname = '') {
	// echo THINK_PATH;die;
	$imgdir = APP_ROOTPATH . '/ewm';
	$temp = '/ewm/';
	require_once THINK_PATH . 'phpqrcode/qrlib.php';
	if (!file_exists($imgdir)) {
		mkdir($imgdir);
	}
	$filename = $imgdir . '/' . $fname . md5($str) . '.png';

	\QRcode::png($str, $filename, 'H', 150, 1); //png(内容，文件名，'L/Q/M/H'质量，1-50相素，1-2边框)
	return $temp . basename($filename);
}

function httpRequest($url, $method = "GET", $postfields = null, $headers = array(), $debug = false) {
	$method = strtoupper($method);
	$ci = curl_init();
	/* Curl settings */
	curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
	curl_setopt($ci, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.2; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0");
	curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 60); /* 在发起连接前等待的时间，如果设置为0，则无限等待 */
	curl_setopt($ci, CURLOPT_TIMEOUT, 7); /* 设置cURL允许执行的最长秒数 */
	curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
	switch ($method) {
	case "POST":
		curl_setopt($ci, CURLOPT_POST, true);
		if (!empty($postfields)) {
			$tmpdatastr = is_array($postfields) ? http_build_query($postfields) : $postfields;
			curl_setopt($ci, CURLOPT_POSTFIELDS, $tmpdatastr);
		}
		break;
	default:
		curl_setopt($ci, CURLOPT_CUSTOMREQUEST, $method); /* //设置请求方式 */
		break;
	}
	$ssl = preg_match('/^https:\/\//i', $url) ? TRUE : FALSE;
	curl_setopt($ci, CURLOPT_URL, $url);
	if ($ssl) {
		curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
		curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, FALSE); // 不从证书中检查SSL加密算法是否存在
	}
	//curl_setopt($ci, CURLOPT_HEADER, true); /*启用时会将头文件的信息作为数据流输出*/
	curl_setopt($ci, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ci, CURLOPT_MAXREDIRS, 2); /*指定最多的HTTP重定向的数量，这个选项是和CURLOPT_FOLLOWLOCATION一起使用的*/
	curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ci, CURLINFO_HEADER_OUT, true);
	/*curl_setopt($ci, CURLOPT_COOKIE, $Cookiestr); * *COOKIE带过去** */
	$response = curl_exec($ci);
	$requestinfo = curl_getinfo($ci);
	$http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
	if ($debug) {
		echo "=====post data======\r\n";
		var_dump($postfields);
		echo "=====info===== \r\n";
		print_r($requestinfo);
		echo "=====response=====\r\n";
		print_r($response);
	}
	curl_close($ci);
	return $response;
	//return array($http_code, $response,$requestinfo);
}
function km($mi, $unit = 1) {
	if ($mi > 1000) {
		$mi = round($mi / 1000, 2);
		return ($unit > 0) ? $mi . ' km' : $mi;
	}
	return ($unit > 0) ? $mi . ' m' : $mi;
}