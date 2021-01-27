<?php
namespace Home\Controller;
use Think\Controller;

//不需登录的
class PubController extends Controller {
	// use PubwxTrait;
	public function __construct() {
		parent::__construct();

	}

	public function settest() {
		if (kget('my', '') == 'lyk') {
			F('test', 1);
			echo "test ok";
		} else {
			F('test', 0);
			echo "no test";
		}
	}

	public function base64_en($str) {
		$str = base64_encode($str);
		$str .= $this->randStr(5);
		$str = base64_encode($str);
		$str = $this->randStr(3) . $str;
		return $str;
	}
	public function randStr($len) {
		$str = "ABCDEFGHIJKLMNOPQRSTUVWSYZabcdefghijklmnopqrstuvwsyz0123456789";
		return substr(str_shuffle($str), 0, $len);

	}
	public function base64_de($str) {
		$len = strlen($str);
		$str = substr($str, 3, $len - 2);
		$str = base64_decode($str);
		$str = substr($str, 0, $len - 5);
		$str = base64_decode($str);
		return $str;
	}

//echo "/n";echo base64_de($s1); echo $s1 = base64_en("http://www.xinqdian.com");//加密后的串

	function test() {
		echo "string";die;
		$file = APP_PATH . 'abcd.mp4';
		header("Content-type: video/mp4");
		header("Accept-Ranges: bytes");
		$size = (filesize($file));
		if (isset($_SERVER['HTTP_RANGE'])) {
			header("HTTP/1.1 206 Partial Content");
			list($name, $range) = explode("=", $_SERVER['HTTP_RANGE']);
			list($begin, $end) = explode("-", $range);
			if ($end == 0) {
				$end = $size - 100;
			}

		} else {
			$begin = 0;
			$end = $size - 100;
		}
		header("Content-Length: " . ($end - $begin + 1));
		header("Content-Disposition: filename=" . basename($file));
		header("Content-Range: bytes " . $begin . "-" . $end . "/" . $size);
		$fp = fopen($file, 'rb');
		fseek($fp, $begin);
		while (!feof($fp)) {
			$p = min(1024, $end - $begin + 1);
			$begin += $p;
			echo fread($fp, $p);
		}
		fclose($fp);exit;
	}
	public function adsfsde() {
		die;
		// $res = APP_PATH . '../others/pswd.txt';
		// readfile(APP_PATH . '../others/pswd.txt');
		// dump($res);die;
		header('Content-Type: application/video');

		header('Content-Disposition: attachment; filename="downloaded.mp4"');

		readfile();

	}

	//上传图片layui
	public function uploads() {
		$img = A('Upload', 'Event')->upload();
		if ($img['status'] == 1) {
			echo kstinfo(1, $img['res']);
			die;
		} else {
			echo kstinfo(3, $img['res']);
		}
	}

	public function uploads_lay() {

		$img = A('Upload', 'Event')->upload_lay();
		if ($img['status'] == 1) {
			echo json_encode(['code' => 1, 'msg' => '上传成功', 'data' => ['src' => '/' . $img['res']]]);
			die;
		} else {
			echo json_encode(['code' => 0, 'msg' => $img['res'], 'data' => ['src' => '']]);
		}
	}

	// public function idfind() {

	// 	$dbn = kpost('dbn', '');
	// 	$id = kpost('id', 0);

	// 	$where = kpost('where', 0);

	// 	if ($where > 0) {
	// 		$res = kdbfind($dbn, kpost());
	// 		if ($res) {
	// 			echo kstinfoapp(1, '查询成功', $res);
	// 			die;
	// 		} else {
	// 			echo kstinfoapp(3, '查询失败', kpost());
	// 			die;
	// 		}
	// 	}

	// 	if ($id > 0) {
	// 		$res = kdbfind($dbn, $id);
	// 		if ($res) {
	// 			echo kstinfoapp(1, '查询成功', $res);
	// 			die;
	// 		}
	// 	}
	// 	echo kstinfoapp(3, '查询失败', kpost());
	// 	die;

	// }
	// public function idsave() {
	// 	$dbn = kpost('dbn', '');
	// 	// $where = kpost('where', '');

	// 	$mod = D($dbn);
	// 	// $op = I('post.op');
	// 	$ret = $mod->create();
	// 	if (!$ret) {
	// 		exit($mod->getError());
	// 	}
	// 	$res = $mod->save();

	// 	if ($res) {
	// 		echo kstinfoapp(1, '操作成功');
	// 		die;
	// 	}

	// 	echo kstinfoapp(3, '登录失败');
	// 	die;
	// }

}