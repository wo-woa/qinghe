<?php
namespace Vip\Controller;
use Think\Controller;

class IndexController extends Controller {
	public function __construct() {
		parent::__construct();
		//验证后的用户
		$this->admin_user = I('session.admin_user', 0);
		if (!($this->admin_user > 0)) {
			$this->redirect('/Vip/login');
			die;
		}
		$this->tdbid = $dbid = kget('tdbid', 0);
		if ($dbid > 0) {
			session('tdbid', $dbid);
		}
	}
	protected function db($table = CONTROLLER_NAME) {
		$dbid = $this->tdbid;
		if ($dbid > 0) {
			return M($table, '', 'DB' . $dbid);
		} else {
			$dbid = ksess('tdbid', 0);
			if ($dbid > 0) {
				return M($table, '', 'DB' . $dbid);
			}
		}
		die('数据错误，请先选择平台！');
	}
	public $admin_user = 0;
	public function index() {
		$this->display('bat');
	}
	public function form($vi = 'form', $dbn = '', $id = 0) {
		$dbn = I('get.dbn', CONTROLLER_NAME); //默认就是控制器名,继承的也是
		$this->dbid = $id;
		if ($id > 0) {
			$this->dbres = M($dbn)->where('id=' . $id)->find();
		}
		// $form = I('get.form', '');
		// if (!empty($form)) {
		// 	$dbn = $form;
		// }
		$this->extradata($dbn, $id); //额外需要的数据
		$this->_extradata($dbn, $id); //额外需要的数据
		if (method_exists($this, 'formdata')) {
			$this->formdata($dbn = '', $id = 0); //额外需要的数据
		}
		$dbn = strtolower($dbn);

		$this->display($vi);
	}

	//统一的查和改，要传op(add,save)还有dbn
	public function addsave($dbn = CONTROLLER_NAME) {

		$op = I('post.op'); //addsave
		$ret = D($dbn)->create();
		if (!$ret) {
			exit(D($dbn)->getError()); //验证不成功
		}
		switch ($op) {
		case 'add':
			$res = D($dbn)->add();
			if ($res) {

			}
			break;
		case 'save':
			$res = D($dbn)->save();

			break;
		}
		echo '<script>this.parent.location.reload();</script>';die;
	}

	//删除某一个表ID的值，要判断是否是admin做的
	public function delone($dbn = CONTROLLER_NAME, $id = 0) {
		$this->ajaxReturn(['status' => $res ? 1 : 2]);die;
		if (strtolower($dbn) == 'borrowing') {
			$this->ajaxReturn(['status' => 2]);
			die;
		}

		if ($id > 0) {
//单个删除
			$res = M($dbn)->where('id=' . $id)->delete();
			if (strtolower($dbn) === 'recharge' && $id > 0) {

			}
		} else {
//批量删除
			$delarr = I('post.delarr');
			foreach ($delarr as $key => $v) {
				$res = M($dbn)->where('id=' . $key)->delete();
				if (strtolower($dbn) === 'recharge' && $key > 0) {

				}
			}
		}
		//$this->tf($res,array('删除成功!'),array('删除失败!'));
		$this->ajaxReturn(['status' => $res ? 1 : 2]);
	}

	//验证码,应该有个4位的proving的字段
	protected function verify() {
		$prov = I('post.proving');
		$verify = I('session.verify');
		if ($verify != md5($prov)) {
			$this->error('验证码错误！');
			exit;
		}
	}

	public static function unixtime($type = 'day', $offset = 0, $position = 'begin', $year = null, $month = null, $day = null, $hour = null, $minute = null) {
		$year = is_null($year) ? date('Y') : $year;
		$month = is_null($month) ? date('m') : $month;
		$day = is_null($day) ? date('d') : $day;
		$hour = is_null($hour) ? date('H') : $hour;
		$minute = is_null($minute) ? date('i') : $minute;
		$position = in_array($position, array('begin', 'start', 'first', 'front'));

		switch ($type) {
		case 'minute':
			$time = $position ? mktime($hour, $minute + $offset, 0, $month, $day, $year) : mktime($hour, $minute + $offset, 59, $month, $day, $year);
			break;
		case 'hour':
			$time = $position ? mktime($hour + $offset, 0, 0, $month, $day, $year) : mktime($hour + $offset, 59, 59, $month, $day, $year);
			break;
		case 'day':
			$time = $position ? mktime(0, 0, 0, $month, $day + $offset, $year) : mktime(23, 59, 59, $month, $day + $offset, $year);
			break;
		case 'week':
			$time = $position ?
			mktime(0, 0, 0, $month, $day - date("w", mktime(0, 0, 0, $month, $day, $year)) + 1 - 7 * (-$offset), $year) :
			mktime(23, 59, 59, $month, $day - date("w", mktime(0, 0, 0, $month, $day, $year)) + 7 - 7 * (-$offset), $year);
			break;
		case 'month':
			$time = $position ? mktime(0, 0, 0, $month + $offset, 1, $year) : mktime(23, 59, 59, $month + $offset, get_month_days($month + $offset, $year), $year);
			break;
		case 'quarter':
			$time = $position ?
			mktime(0, 0, 0, 1 + ((ceil(date('n', mktime(0, 0, 0, $month, $day, $year)) / 3) + $offset) - 1) * 3, 1, $year) :
			mktime(23, 59, 59, (ceil(date('n', mktime(0, 0, 0, $month, $day, $year)) / 3) + $offset) * 3, get_month_days((ceil(date('n', mktime(0, 0, 0, $month, $day, $year)) / 3) + $offset) * 3, $year), $year);
			break;
		case 'year':
			$time = $position ? mktime(0, 0, 0, 1, 1, $year + $offset) : mktime(23, 59, 59, 12, 31, $year + $offset);
			break;
		default:
			$time = mktime($hour, $minute, 0, $month, $day, $year);
			break;
		}
		return $time;
	}

	//导出csv文件
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

}