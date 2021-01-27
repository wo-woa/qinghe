<?php
namespace Vip\Controller;
use Think\Controller;

class TongjiController extends Controller {
	// use TongjiTrait;
	public function __construct() {
		parent::__construct();

	}

	public function all() {

		$howmany = kget('d', 30);
		$num = 0 - $howmany;
		$this->howmany = $howmany;

		$days = array();

		$mon1 = array();
		$mon2 = array();
		$mon3 = array();
		$mon4 = array();
		$mon5 = array();

		$tmon1 = $tmon2 = $tmon3 = $tmon4 = $tmon5 = 0;

		for ($i = $num; $i < 1; $i++) {
			$ndaystime0 = $this::unixtime('day', $i);
			$ndaystime59 = $ndaystime0 + 3600 * 24 - 1; //最后一秒

			$days[] = date('Y-m-d', $ndaystime59);

			$cond = ['addtime' => ['between', $ndaystime0 . ',' . $ndaystime59], 'id' => ['gt', 0]]; //已付款, 'states' => 1
			$mon1a = 0 + M('member')->where($cond)->count('id');

			$cond = ['oktime' => ['between', $ndaystime0 . ',' . $ndaystime59]];
			$mon2a = 0 + M('payment')->where($cond)->count('id');

			$cond = ['oktime' => ['between', $ndaystime0 . ',' . $ndaystime59]];
			$mon3a = 0 + M('payment')->where($cond)->sum('amt');

			$mon1[] = $this->setmon($mon1a);
			$mon2[] = $this->setmon($mon1a);
			$mon3[] = $this->setmon($mon3a);
			$tmon1 += $this->setmon($mon1a);
			$tmon2 += $this->setmon($mon2a);
			$tmon3 += $this->setmon($mon3a);
		}

		// foreach ($mon1 as $key => $v) {
		// 	//数量小的删除
		// 	if ($v < 2 or $mon2[$key] < 5) {
		// 		unset($days[$key]);
		// 		unset($mon1[$key]);
		// 		unset($mon2[$key]);
		// 		// unset($tmon1[$key]);
		// 		// unset($tmon2[$key]);

		// 	}
		// }

		$this->days = $days;
		$this->mon1 = $mon1;
		$this->mon2 = $mon2;
		$this->mon3 = $mon3;
		$this->tmon1 = $tmon1;
		$this->tmon2 = $tmon2;
		$this->tmon3 = $tmon3;
		$this->display();
	}

	public function setmon($str) {
		return $str;
	}

	/**
	 * 获取一个基于时间偏移的Unix时间戳
	 *
	 * @param string $type 时间类型，默认为day，可选minute,hour,day,week,month,quarter,year
	 * @param int $offset 时间偏移量 默认为0，正数表示当前type之后，负数表示当前type之前
	 * @param string $position 时间的开始或结束，默认为begin，可选前(begin,start,first,front)，end
	 * @param int $year 基准年，默认为null，即以当前年为基准
	 * @param int $month 基准月，默认为null，即以当前月为基准
	 * @param int $day 基准天，默认为null，即以当前天为基准
	 * @param int $hour 基准小时，默认为null，即以当前年小时基准
	 * @param int $minute 基准分钟，默认为null，即以当前分钟为基准
	 * @return int 处理后的Unix时间戳
	 */
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

}