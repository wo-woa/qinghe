<?php
/* *
 * 类名：AlipaySubmit
 * 功能：支付宝各接口请求提交类
 * 详细：构造支付宝各接口表单HTML文本，获取远程HTTP数据
 * 版本：3.3
 * 日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。
 */

class AlipaySubmit {
	var $alipay_config;
	/**
	 *支付宝网关地址（新）
	 */
	var $alipay_gateway_new = 'https://mapi.alipay.com/gateway.do?';

	function __construct($arr) {
// 		$alipay_config['notify_url'] = 'http://xiche.bozhouyuan.com/kali/' . 'notify_url.php';
		// // 页面跳转同步通知页面路径 需http://格式的完整路径，不能加?id=123这类自定义参数，必须外网可以正常访问
		// 		$alipay_config['return_url'] = 'http://xiche.bozhouyuan.com/kali/' . 'return_url.php';

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
		$alipay_config['service'] = "create_direct_pay_by_user"; //alipay.wap.create.direct.pay.by.user

		$alipay_config = array_merge($alipay_config, $arr);
		// dump($alipay_config);die;
		$this->alipay_config = $alipay_config;
	}
	function AlipaySubmit($alipay_config) {

		$this->__construct($alipay_config);
	}

	/**
	 * 生成签名结果
	 * @param $para_sort 已排序要签名的数组
	 * return 签名结果字符串
	 */
	function buildRequestMysign($para_sort) {
		//把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
		$prestr = createLinkstring($para_sort);

		$mysign = "";
		switch (strtoupper(trim($this->alipay_config['sign_type']))) {
		case "RSA":
			$mysign = rsaSign($prestr, $this->alipay_config['private_key']);
			break;
		default:
			$mysign = "";
		}

		return $mysign;
	}

	/**
	 * 生成要请求给支付宝的参数数组
	 * @param $para_temp 请求前的参数数组
	 * @return 要请求的参数数组
	 */
	function buildRequestPara($para_temp) {
		//除去待签名参数数组中的空值和签名参数
		$para_filter = paraFilter($para_temp);

		//对待签名参数数组排序
		$para_sort = argSort($para_filter);

		//生成签名结果
		$mysign = $this->buildRequestMysign($para_sort);

		//签名结果与签名方式加入请求提交参数组中
		$para_sort['sign'] = $mysign;
		$para_sort['sign_type'] = strtoupper(trim($this->alipay_config['sign_type']));

		return $para_sort;
	}

	/**
	 * 生成要请求给支付宝的参数数组
	 * @param $para_temp 请求前的参数数组
	 * @return 要请求的参数数组字符串
	 */
	function buildRequestParaToString($para_temp) {
		//待请求参数数组
		$para = $this->buildRequestPara($para_temp);

		//把参数组中所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串，并对字符串做urlencode编码
		$request_data = createLinkstringUrlencode($para);

		return $request_data;
	}

	/**
	 * 建立请求，以表单HTML形式构造（默认）
	 * @param $para_temp 请求参数数组
	 * @param $method 提交方式。两个值可选：post、get
	 * @param $button_name 确认按钮显示文字
	 * @return 提交表单HTML文本
	 */
	function buildRequestForm($paratt, $method, $button_name) {
		//构造要请求的参数数组，无需改动
		$alarr = array(
			"service" => $this->alipay_config['service'],
			"partner" => $this->alipay_config['partner'],
			"seller_id" => $this->alipay_config['seller_id'],
			"payment_type" => $this->alipay_config['payment_type'],
			"notify_url" => $this->alipay_config['notify_url'],
			"return_url" => $this->alipay_config['return_url'],
			"_input_charset" => trim(strtolower($this->alipay_config['input_charset'])),
			// "out_trade_no" => $out_trade_no,
			// "subject" => $subject,
			// "total_fee" => $total_fee,
			// "show_url" => $show_url,
			// //"app_pay"	=> "Y",//启用此参数能唤起钱包APP支付宝
			// "body" => $body,

		);
		$para_temp = array_merge($paratt, $alarr);
		//待请求参数数组
		$para = $this->buildRequestPara($para_temp);

		$sHtml = "<form id='alipaysubmit' name='alipaysubmit' action='" . $this->alipay_gateway_new . "_input_charset=" . trim(strtolower($this->alipay_config['input_charset'])) . "' method='" . $method . "'>";
		while (list($key, $val) = each($para)) {
			$sHtml .= "<input type='hidden' name='" . $key . "' value='" . $val . "'/>";
		}

		//submit按钮控件请不要含有name属性
		$sHtml = $sHtml . "<input type='submit' value='" . $button_name . "'></form>";

		$sHtml = $sHtml . "<script>document.forms['alipaysubmit'].submit();</script>";

		return $sHtml;
	}

	/**
	 * 用于防钓鱼，调用接口query_timestamp来获取时间戳的处理函数
	 * 注意：该功能PHP5环境及以上支持，因此必须服务器、本地电脑中装有支持DOMDocument、SSL的PHP配置环境。建议本地调试时使用PHP开发软件
	 * return 时间戳字符串
	 */
	function query_timestamp() {
		$url = $this->alipay_gateway_new . "service=query_timestamp&partner=" . trim(strtolower($this->alipay_config['partner'])) . "&_input_charset=" . trim(strtolower($this->alipay_config['input_charset']));
		$encrypt_key = "";

		$doc = new DOMDocument();
		$doc->load($url);
		$itemEncrypt_key = $doc->getElementsByTagName("encrypt_key");
		$encrypt_key = $itemEncrypt_key->item(0)->nodeValue;

		return $encrypt_key;
	}
}