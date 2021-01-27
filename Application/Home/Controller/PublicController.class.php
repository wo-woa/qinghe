<?php
namespace Home\Controller;
use Think\Controller;

class PublicController extends Controller {
	// use PubtestTrait;

	public function verify() {
		session_start();

		$randCode = '';
		// $chars = 'abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNPRSTUVWXYZ23456789';
		$chars = '0123456789';
		for ($i = 0; $i < 4; $i++) {
			$randCode .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}

		// $_SESSION['chkcode'] = strtolower($randCode);

		session('vcode', strtolower($randCode));

		$img = imagecreate(60, 22);
		$bgColor = isset($_GET['mode']) && $_GET['mode'] == 't' ? imagecolorallocate($img, 0, 245, 0) : imagecolorallocate($img, 255, 255, 255);
		$pixColor = imagecolorallocate($img, mt_rand(30, 180), mt_rand(10, 100), mt_rand(40, 250));

		for ($i = 0; $i < 5; $i++) {
			$x = $i * 13 + mt_rand(0, 4) - 2;
			$y = mt_rand(0, 3);
			$text_color = imagecolorallocate($img, mt_rand(30, 180), mt_rand(10, 100), mt_rand(40, 250));
			imagechar($img, 5, $x + 5, $y + 3, $randCode[$i], $text_color);
		}
		for ($j = 0; $j < 60; $j++) {
			$x = mt_rand(0, 70);
			$y = mt_rand(0, 22);
			imagesetpixel($img, $x, $y, $pixColor);
		}

		header('Content-Type: image/png');
		imagepng($img);
		imagedestroy($img);

		// $Verify = new \Think\Verify();
		// $Verify->codeSet = '0123456789';
		// $Verify->bg = array(243, 200, 100);
		// $Verify->length = 4;
		// $Verify->fontSize = 14;
		// $Verify->useNoise = 0;
		// $Verify->imageW = 100;
		// $Verify->imageH = 40;
		// $Verify->useCurve = 0;
		// $Verify->entry();
	}

	public function verify222() {
		session_start();

		$randCode = '';
		// $chars = 'abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNPRSTUVWXYZ23456789';
		$chars = '0123456789';
		for ($i = 0; $i < 5; $i++) {
			$randCode .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}

		$_SESSION['chkcode'] = strtolower($randCode);

		$img = imagecreate(70, 22);
		$bgColor = isset($_GET['mode']) && $_GET['mode'] == 't' ? imagecolorallocate($img, 245, 245, 245) : imagecolorallocate($img, 255, 255, 255);
		$pixColor = imagecolorallocate($img, mt_rand(30, 180), mt_rand(10, 100), mt_rand(40, 250));

		for ($i = 0; $i < 5; $i++) {
			$x = $i * 13 + mt_rand(0, 4) - 2;
			$y = mt_rand(0, 3);
			$text_color = imagecolorallocate($img, mt_rand(30, 180), mt_rand(10, 100), mt_rand(40, 250));
			imagechar($img, 5, $x + 5, $y + 3, $randCode[$i], $text_color);
		}
		for ($j = 0; $j < 60; $j++) {
			$x = mt_rand(0, 70);
			$y = mt_rand(0, 22);
			imagesetpixel($img, $x, $y, $pixColor);
		}

		header('Content-Type: image/png');
		imagepng($img);
		imagedestroy($img);

		// $Verify = new \Think\Verify();
		// $Verify->codeSet = '0123456789';
		// $Verify->bg = array(243, 200, 100);
		// $Verify->length = 4;
		// $Verify->fontSize = 14;
		// $Verify->useNoise = 0;
		// $Verify->imageW = 100;
		// $Verify->imageH = 40;
		// $Verify->useCurve = 0;
		// $Verify->entry();
	}
	public function verifyshop() {
		$Verify = new \Think\Verify();
		$Verify->codeSet = '0123456789';
		$Verify->bg = array(243, 200, 100);
		$Verify->length = 4;
		$Verify->fontSize = 12;
		$Verify->useNoise = 1;
		$Verify->imageW = 80;
		$Verify->imageH = 22;
		$Verify->useCurve = false;
		$Verify->entry();
	}
	protected function put_csv($list, $title, $tpname = '') {
		ini_set('max_execution_time', 4800);
		$file_name = $tpname . date("Y-m-d-H-i-s", time()) . ".csv";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename=' . $file_name);
		header('Cache-Control: max-age=0');
		$file = fopen('php://output', "a");
		$limit = 50000;
		$calc = 0;
		foreach ($title as $v) {
			$tit[] = iconv('UTF-8', 'GB2312//IGNORE', $v);
		}
		fputcsv($file, $tit);
		foreach ($list as $v) {
			$calc++;
			if ($limit == $calc) {
				ob_flush();
				flush();
				$calc = 0;
			}
			foreach ($v as $t) {
				$tarr[] = iconv('UTF-8', 'GB2312//IGNORE', $t);
			}
			fputcsv($file, $tarr);
			unset($tarr);
		}
		unset($list);
		fclose($file);
		exit();
	}

	public function towx() {
		die;
		$arr = M('orderspay')->find(39);
		$res = A('Home/Wxmsg', 'Event')->toinfo($arr); //给6个运维发
	}
	public function shiwu() {
		// $res = D('Orderspay')->allfields();
		// dump($res);
		// dump(M('Orderspay')->fields);
		die;
		//测试事务
		$mod = M();
		$mod->startTrans();

		$res = $mod->table('app_amountb')->add(['uid' => 32, 'amount' => rand(1, 9999)]);
		$res2 = $mod->table('app_amountc')->add(['uid' => 13, 'amount' => rand(1, 9999)]);
		$res3 = $mod->table('app_amountshop')->add(['uid' => 3323, 'amount' => rand(1, 9999)]);

		$res4 = M('amountb')->where(['id' => 1])->data(['uid' => 3333])->save();

		if ($res && $res2 && $res3 && $res4) {
			$mod->commit();
		} else {
			$mod->rollback();
		}
		dump(M('amountb')->select());

	}

}