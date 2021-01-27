<?php
function randstr($l) {
	$c = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"; //abcdefghijklmnopqrstuvwxyz
	srand((double) microtime() * 1000000);
	for ($i = 0; $i < $l; $i++) {
		$rand .= $c[rand() % strlen($c)];
	}
	return $rand;
}

function randeng($l) {
	$c = "ABCDEFGHJKLMNPQRSTUVWXYZ";
	srand((double) microtime() * 1000000);
	for ($i = 0; $i < $l; $i++) {
		$rand .= $c[rand() % strlen($c)];
	}
	return $rand;
}

function htmltotext($document) {
	$search = array("'<script[^>]*?>.*?</script>'si", // 去掉 javascript
		"'<[\/\!]*?[^<>]*?>'si", // 去掉 HTML 标记
		"'([\r\n])[\s]+'", // 去掉空白字符
		"'&(quot|#34);'i", // 替换 HTML 实体
		"'&(amp|#38);'i",
		"'&(lt|#60);'i",
		"'&(gt|#62);'i",
		"'&(nbsp|#160);'i",
		"'&(iexcl|#161);'i",
		"'&(cent|#162);'i",
		"'&(pound|#163);'i",
		"'&(copy|#169);'i",
		"'&#(\d+);'e"); // 作为 PHP 代码运行

	$replace = array("",
		"",
		"\\1",
		"\"",
		"&",
		"<",
		">",
		" ",
		chr(161),
		chr(162),
		chr(163),
		chr(169),
		"chr(\\1)");

	$text = preg_replace($search, $replace, $document);
	return $text;
}

function kemail($arr) {
	import('ORG.Custom.PhpMailer');
	$mail = new \PHPMailer();
	$smtp = $arr['smtp'];
	$validation = $arr['validation'];
	$send_email = $arr['send_email'];
	$password = $arr['password'];
	$addresser = $arr['addresser'];
	$receiver_email_array = array_filter(explode(',', $arr['receiver_email_array']));
	$receipt_email = $arr['receipt_email'];
	$title = $arr['title'];
	$content = $arr['content'];
	$addattachment = $arr['addattachment'];
	$ishtml = $arr['ishtml'];

	$mail->IsSMTP(); // 使用SMTP方式发送
	$mail->CharSet = 'UTF-8'; // 设置邮件的字符编码
	$mail->Host = "$smtp"; // 您的企业邮局域名
	$mail->SMTPAuth = $validation == 1 ? true : false; // 启用SMTP验证功能
	$mail->Username = "$send_email"; // 邮局用户名(请填写完整的email地址)
	$mail->Password = "$password"; // 邮局密码
	$mail->From = "$send_email"; //邮件发送者email地址
	$mail->FromName = "$addresser"; //发件人
	if ($receiver_email_array) {
		foreach ($receiver_email_array as $rea) {
			$mail->AddAddress("$rea"); //群发
		}
	} else {
		$mail->AddAddress("$receipt_email"); //收件人地址，可以替换成任何想要接收邮件的email信箱,格式是AddAddress("收件人email","收件人姓名")
	}
	//$mail->AddReplyTo("", "");	//添加回复
	if ($addattachment) {
		$mail->AddAttachment("$addattachment"); // 添加附件
	}
	$mail->IsHTML($ishtml == 1 ? true : false); // set email format to HTML //是否使用HTML格式
	$mail->Subject = "$title"; //邮件标题
	$mail->Body = "$content"; //邮件内容
	$mail->AltBody = "备份"; //附加信息，可以省略
	if (!$mail->Send()) {
		echo '邮件发送失败. <p>错误原因: ' . $mail->ErrorInfo;
		exit;
	}
	return true;
}

//返回json数据
function kjson($arr) {
	return json_encode($arr, JSON_UNESCAPED_UNICODE);
}
function kjsond($arr) {
	return json_decode($arr, true);
}
function kto() {}
//直接返回状态和信息的json数组{"status":1,"msg":"登录成功","info":{"token":"abcdefg"}}

function kstinfo($st, $info, $extra = array()) {

	if (!empty($extra)) {
		return kjson(array_merge(['status' => $st, 'info' => $info], $extra));
	}
	return kjson(['status' => $st, 'info' => $info]);
}
function kstinfoapp($st, $info, $datas = null, $extra = '') {
	$arr = ['status' => $st, 'info' => $info];
	if (!is_null($datas)) {
		$arr['data'] = $datas;
		// $arr=['status' => $st, 'info' => $info, 'data' =>];

	}
	if (!empty($extra)) {
		$arr = array_merge($arr, $extra);
		// $arr=['status' => $st, 'info' => $info, 'data' => $datas];

	}

	return kjson($arr);
}

//数据库相关的
function kdbfind($tbn, $id) {
	if (is_array($id)) {
		$cond = $id;
	} else {
		$cond = ['id' => $id];
	}
	return M($tbn)->where($cond)->find();
}
function kdbsave($tbn, $id, $darr) {
	if (is_array($id)) {
		$cond = $id;
	} else {
		$cond = ['id' => $id];
	}
	return M($tbn)->where($cond)->data($darr)->save();
}

function kdbfield($tbn, $id, $field, $default = '无') {
	if (is_array($id)) {
		$cond = $id;
	} else {
		$cond = ['id' => $id];
	}
	$res = M($tbn)->where($cond)->getField($field);
	if (empty($res)) {return $default;}
	return $res;
}
function kdbselect($tbn, $id, $ordering = '', $limit = 100000) {
	if (is_array($id)) {
		$cond = $id;
	} else {
		$cond = ['id' => $id];
	}
	return $res = M($tbn)->where($cond)->order($ordering)->limit($limit)->select();
}
function kdbadd($tbn, $darr) {
	return M($tbn)->data($darr)->add();
}
//删除
function kdbdel($tbn, $id) {
	if (is_array($id)) {
		$cond = $id;
	} else {
		$cond = ['id' => $id];
	}
	return M($tbn)->where($cond)->delete();
}

//返回Post数据
function kpost($str = '', $d = '') {
	return I('post.' . $str, $d);
}
//返回GET数据
function kget($str = '', $d = '') {
	return I('get.' . $str, $d);
}
function kreq($str = '', $d = '') {
	$res = $_REQUEST[$str];
	return !empty($res) ? $res : $d;
}
//返回SESSION数据
function ksess($str, $d = '') {
	return I('session.' . $str, $d);
}
//返回token数据
function ktoken() {
	return md5('abc');
}

function kipaddr($ip = '') {
	$res = A('Home/Baidu', 'Event')->ipaddr($ip);
	if ($res['all']['status'] == 0) {
		$res['addr'] = $res['all']['content']['address'];
		return $res;
	}
	return false;
}
function kipcity() {
	$res = kipaddr();
	if ($res) {
		return $res['addr'];
	}
	return '';
}
//直接生成url
function kurl($str) {
	return WEBROOTURL . $str; //WEBROOTURL.'/Upub/reurl.html',
}

function vrange($str) {
	$res = explode(',', $str);
	return count($res) - 2;
}
function kverify($str, $id = '') {
	$verify = new \Think\Verify();
	return $verify->check($str, $id);
}

require APP_PATH . 'Common/Common/busi.php';
//444打卡
// public function signin() {
// 	if (IS_POST) {
// 		$obj = D('Signin');
// 		if (!$obj->create()) {
// 			echo kstinfo(3, $obj->getError()); //验证不通过
// 			die;
// 		} else {
// 			$obj->driverid = $this->meminfo['id'];
// 			$obj->addtime  = time();
// 			$obj->timeno   = date('Ymd');
// 			$obj->ip       = get_client_ip();
// 			$res           = $obj->add();
// 			if ($res) {
// 				echo kstinfo(1, '打卡成功');
// 				die;
// 			}
// 		}
// 	}
// 	echo kstinfo(3, "打卡失败！");
// }