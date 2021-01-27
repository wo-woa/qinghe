<?php
namespace Common\Model;
use Think\Model\RelationModel;

class FundlogModel extends RelationModel {
	protected $_link = [
		'member' => [
			'mapping_type' => self::BELONGS_TO, //要取自己的某个字字段关联，HAS_ONE要取别的表里某字段关联,
			'class_name' => 'member',
			'mapping_name' => 'member', //查出来的子数组的key名,否则用本数组的key leader，一定要加！
			'foreign_key' => 'memid', //BELONGS_TO要取自己的某个字字段关联，HAS_ONE要取别的表里某字段关联
			// 'mapping_order' => 'id desc', //按哪个排序
			// 'mapping_fields' => 'id,realname,tel', //要查询的字段,不写就全部关联
			// 'as_fields' => '' //不写这个就多一个子数组，key就是mapping_name或者leader
		], 'payment' => [
			'mapping_type' => self::BELONGS_TO, //要取自己的某个字字段关联，HAS_ONE要取别的表里某字段关联,
			'class_name' => 'payment',
			'mapping_name' => 'payment', //查出来的子数组的key名,否则用本数组的key leader，一定要加！
			'foreign_key' => 'payid', //BELONGS_TO要取自己的某个字字段关联，HAS_ONE要取别的表里某字段关联
			// 'mapping_order' => 'id desc', //按哪个排序
			// 'mapping_fields' => 'id,realname,tel', //要查询的字段,不写就全部关联
			// 'as_fields' => '' //不写这个就多一个子数组，key就是mapping_name或者leader
		], 'agent' => [
			'mapping_type' => self::BELONGS_TO, //要取自己的某个字字段关联，HAS_ONE要取别的表里某字段关联,
			'class_name' => 'agent',
			'mapping_name' => 'agent', //查出来的子数组的key名,否则用本数组的key leader，一定要加！
			'foreign_key' => 'agentid', //BELONGS_TO要取自己的某个字字段关联，HAS_ONE要取别的表里某字段关联
			// 'mapping_order' => 'id desc', //按哪个排序
			// 'mapping_fields' => 'id,realname,tel', //要查询的字段,不写就全部关联
			// 'as_fields' => '' //不写这个就多一个子数组，key就是mapping_name或者leader
		],
	];

	public function todo() {
		$arr = M('payment')->where(['okstates' => 1, 'pstates' => 0])->limit(10)->select(); //已支付未结算的
		// $par_ratio = 0.3;
		// $gran_ratio = 0.2;
		$one = M('config')->where(['cname' => 'app_mon'])->find();

		$par_ratio = 0 + floatval($one['cvalue']);
		$gran_ratio = 0 + floatval($one['extra']);
		foreach ($arr as $key => $v) {
			$this->tofund($v, $par_ratio, $gran_ratio);
		}
	}
	public function tofund($pay, $par_ratio, $gran_ratio) {
		$time = time();
		//查出来2个代理的fundnum,给2个代理fund分成，给fundlog记录，给pay改状态
		$mod = M();
		$mod->startTrans();

		$parid = $pay['parid'];
		$granid = $pay['granid'];

		$flag = true;
		if ($parid > 0) {
			$amt = $par_ratio * $pay['amt'];
			$hone = $mod->table('app_funds')->where(['agentid' => $parid])->find();
			if ($hone) {
				$darr = array();
				// $darr['id'] = $hone['id'];
				// $darr['agentid'] = $hone['agentid'];
				$darr['amt'] = $afamt = $hone['amt'] + $amt;
				$darr['num'] = $num = $hone['num'] + 1;
				// $darr['states'] = $hone['states'];
				$darr['edittime'] = $time;
				// $darr['addtime'] = $hone['addtime'];
				$save = $mod->table('app_funds')->where(['agentid' => $parid])->data($darr)->save();

				$flag = $flag && $save;

				$darr = array();
				$darr['agentid'] = $parid;
				$darr['agtype'] = 1;
				$darr['memid'] = $pay['memid'];
				$darr['payid'] = $pay['id'];
				$darr['title'] = '直属代理提成';
				$darr['amt'] = $amt;
				$darr['bfamt'] = $hone['amt'];
				$darr['afamt'] = $afamt;
				$darr['addtime'] = $time;
				$darr['ymd'] = date('Ymd', $time);
				$darr['states'] = 1;
				$darr['num'] = $num;
				$darr['ifdraw'] = 0;
				$darr['unino'] = 'pay_' . $darr['payid'] . '_' . $parid;
				// $darr['extra'] = '';
				// $darr['rmk'] = '';
				// $darr['edittime'] = '';
				// $darr['payrmk'] = '';
				// $darr['paytype'] = '';
				$add = $mod->table('app_fundlog')->data($darr)->add();
				$flag = $flag && $add;

			}

		}

		if ($granid > 0) {
			$amt = $gran_ratio * $pay['amt'];
			$hone = $mod->table('app_funds')->where(['agentid' => $granid])->find();
			if ($hone) {
				$darr = array();
				// $darr['id'] = $hone['id'];
				// $darr['agentid'] = $hone['agentid'];
				$darr['amt'] = $afamt = $hone['amt'] + $amt;
				$darr['num'] = $num = $hone['num'] + 1;
				// $darr['states'] = $hone['states'];
				$darr['edittime'] = $time;
				// $darr['addtime'] = $hone['addtime'];
				$save = $mod->table('app_funds')->where(['agentid' => $granid])->data($darr)->save();
				$flag = $flag && $save;

				$darr = array();
				$darr['agentid'] = $granid;
				$darr['agtype'] = 2;
				$darr['memid'] = $pay['memid'];
				$darr['payid'] = $pay['id'];
				$darr['title'] = '一级代理提成';
				$darr['amt'] = $amt;
				$darr['bfamt'] = $hone['amt'];
				$darr['afamt'] = $afamt;
				$darr['addtime'] = $time;
				$darr['ymd'] = date('Ymd', $time);
				$darr['states'] = 1;
				$darr['num'] = $num;
				$darr['ifdraw'] = 0;
				$darr['unino'] = 'pay_' . $darr['payid'] . '_' . $granid;
				// $darr['extra'] = '';
				// $darr['rmk'] = '';
				// $darr['edittime'] = '';
				// $darr['payrmk'] = '';
				// $darr['paytype'] = '';
				$add = $mod->table('app_fundlog')->data($darr)->add();
				$flag = $flag && $add;

			}

		}

		$darr = array();
		$darr['pstates'] = 1;
		// $darr['oktime'] = $ntime;
		// $darr['okorderno'] = $message['transaction_id'];

		$spay = $mod->table('app_payment')->where(['id' => $pay['id']])->data($darr)->save();

		if ($flag && $spay) {
			$mod->commit();
			return true;
		} else {
			$mod->rollback();
			return false;
		}
	}

	public function todraw($agentid, $amt) {
		$time = time();
		//查出来2个代理的fundnum,给2个代理fund分成，给fundlog记录，给pay改状态
		$mod = M();
		$mod->startTrans();
		$flag = true;
		$hone = $mod->table('app_funds')->where(['agentid' => $agentid])->find();
		if ($hone) {
			$darr = array();
			// $darr['id'] = $hone['id'];
			// $darr['agentid'] = $hone['agentid'];
			$darr['amt'] = $afamt = $hone['amt'] - $amt;
			$darr['num'] = $num = $hone['num'] + 1;
			// $darr['states'] = $hone['states'];
			$darr['edittime'] = $time;
			// $darr['addtime'] = $hone['addtime'];
			$save = $mod->table('app_funds')->where(['agentid' => $agentid])->data($darr)->save();
			$flag = $flag && $save;

			$darr = array();
			$darr['agentid'] = $agentid;
			$darr['agtype'] = 0;
			$darr['memid'] = 0;
			$darr['payid'] = 0;
			$darr['title'] = '代理提现';
			$darr['amt'] = $amt;
			$darr['bfamt'] = $hone['amt'];
			$darr['afamt'] = $afamt;
			$darr['addtime'] = $time;
			$darr['ymd'] = date('Ymd', $time);
			$darr['states'] = 0;
			$darr['num'] = $num;
			$darr['ifdraw'] = 1;
			$darr['unino'] = 'draw_' . $agentid . '_' . $num;
			// $darr['extra'] = '';
			// $darr['rmk'] = '';
			// $darr['edittime'] = '';
			// $darr['payrmk'] = '';
			// $darr['paytype'] = '';
			$add = $mod->table('app_fundlog')->data($darr)->add();
			$flag = $flag && $add;

		}
		if ($flag) {
			$mod->commit();
			return true;
		} else {
			$mod->rollback();
			return false;
		}

	}

	public function addone($tp = 0) {
		die;
		//time();date('Ymd');date('YmdHis');date('Y-m-d');$agentid,$agtype,$memid,$payid,$title,$amt,$bfamt,$afamt,$addtime,$ymd,$states,$num,$ifdraw,$unino,$extra,$rmk,$edittime,$payrmk,$paytype,
		//kpost('agentid', '');kpost('agtype', '');kpost('memid', '');kpost('payid', '');kpost('title', '');kpost('amt', '');kpost('bfamt', '');kpost('afamt', '');kpost('addtime', '');kpost('ymd', '');kpost('states', '');kpost('num', '');kpost('ifdraw', '');kpost('unino', '');kpost('extra', '');kpost('rmk', '');kpost('edittime', '');kpost('payrmk', '');kpost('paytype', '');
		//kget('agentid', '');kget('agtype', '');kget('memid', '');kget('payid', '');kget('title', '');kget('amt', '');kget('bfamt', '');kget('afamt', '');kget('addtime', '');kget('ymd', '');kget('states', '');kget('num', '');kget('ifdraw', '');kget('unino', '');kget('extra', '');kget('rmk', '');kget('edittime', '');kget('payrmk', '');kget('paytype', '');

		$darr = array();

		$darr['agentid'] = kpost('agentid', ''); //$agentid;kget('agentid', '');
		$darr['agtype'] = kpost('agtype', ''); //$agtype;kget('agtype', '');
		$darr['memid'] = kpost('memid', ''); //$memid;kget('memid', '');
		$darr['payid'] = kpost('payid', ''); //$payid;kget('payid', '');
		$darr['title'] = kpost('title', ''); //$title;kget('title', '');
		$darr['amt'] = kpost('amt', ''); //$amt;kget('amt', '');
		$darr['bfamt'] = kpost('bfamt', ''); //$bfamt;kget('bfamt', '');
		$darr['afamt'] = kpost('afamt', ''); //$afamt;kget('afamt', '');
		$darr['addtime'] = kpost('addtime', ''); //$addtime;kget('addtime', '');
		$darr['ymd'] = kpost('ymd', ''); //$ymd;kget('ymd', '');
		$darr['states'] = kpost('states', ''); //$states;kget('states', '');
		$darr['num'] = kpost('num', ''); //$num;kget('num', '');
		$darr['ifdraw'] = kpost('ifdraw', ''); //$ifdraw;kget('ifdraw', '');
		$darr['unino'] = kpost('unino', ''); //$unino;kget('unino', '');
		$darr['extra'] = kpost('extra', ''); //$extra;kget('extra', '');
		$darr['rmk'] = kpost('rmk', ''); //$rmk;kget('rmk', '');
		$darr['edittime'] = kpost('edittime', ''); //$edittime;kget('edittime', '');
		$darr['payrmk'] = kpost('payrmk', ''); //$payrmk;kget('payrmk', '');
		$darr['paytype'] = kpost('paytype', ''); //$paytype;kget('paytype', '');

		$add = $this->data($darr)->add();
		if ($add) {
			if ($tp == 1) {
				return $this->where(['id' => $add])->find();
			}
			return $add;
		}
		return false;
	}

	public function findone($id = 0) {
		//D('Fundlog')->findone($id);
		return $this->where(['id' => $id])->find();
	}
	public function lists($cond) {
		//D('Fundlog')->lists($cond);
		return $this->where($cond)->order('id desc')->select();
	}

	public function hasone($uid = -2) {
		die('Fundlog数据的model出错');
		$uid = 0 + $uid;
		if ($uid > -1) {
			$hascart = $this->where(['uid' => $uid])->find();
			if ($hascart) {
				return $hascart;
			} else {
				$darr = array();
				$darr['agentid'] = '';
				$darr['agtype'] = '';
				$darr['memid'] = '';
				$darr['payid'] = '';
				$darr['title'] = '';
				$darr['amt'] = '';
				$darr['bfamt'] = '';
				$darr['afamt'] = '';
				$darr['addtime'] = '';
				$darr['ymd'] = '';
				$darr['states'] = '';
				$darr['num'] = '';
				$darr['ifdraw'] = '';
				$darr['unino'] = '';
				$darr['extra'] = '';
				$darr['rmk'] = '';
				$darr['edittime'] = '';
				$darr['payrmk'] = '';
				$darr['paytype'] = '';
				$hascart = $this->data($darr)->add();
				if ($hascart) {
					return $this->where(['uid' => $uid, 'id' => $hascart])->find();
				}
			}
		}
		return false;
	}
	public function delone($id, $cond) {
		//$cond=['id' => $id];
		return $this->where($cond)->delete();
	}

	public function testaaaa($id, $cond) {
		$darr = array();
		//$darr['id'] =  $hone['id'];$darr['agentid'] =  $hone['agentid'];$darr['agtype'] =  $hone['agtype'];$darr['memid'] =  $hone['memid'];$darr['payid'] =  $hone['payid'];$darr['title'] =  $hone['title'];$darr['amt'] =  $hone['amt'];$darr['bfamt'] =  $hone['bfamt'];$darr['afamt'] =  $hone['afamt'];$darr['addtime'] =  $hone['addtime'];$darr['ymd'] =  $hone['ymd'];$darr['states'] =  $hone['states'];$darr['num'] =  $hone['num'];$darr['ifdraw'] =  $hone['ifdraw'];$darr['unino'] =  $hone['unino'];$darr['extra'] =  $hone['extra'];$darr['rmk'] =  $hone['rmk'];$darr['edittime'] =  $hone['edittime'];$darr['payrmk'] =  $hone['payrmk'];$darr['paytype'] =  $hone['paytype'];

		//$save = M('Fundlog')->where(['states'=>1,'states'=>['gt',1],'addtime'=>time(),'states'=>['between',$min.','.$max]])->data($darr)->save();

		$hone = M('Fundlog')->where(['states' => 1, 'states' => ['gt', 1], 'addtime' => time(), 'states' => ['between', $min . ',' . $max]])->select();
	}

	public function addcsv() {
		die;
		$fpath = I('post.fpath', 0);
		// if ($fpath === 0) {
		// 	$this->error('要导入数据请先上传CSV文件！');
		// 	return;
		// }
		$csvpath = APP_ROOTPATH . $fpath; //文件绝对路径
		$carr = $this->csvfile($csvpath);

		$time = time();
		if (!empty($carr)) {
			foreach ($carr as $key => $hone) {
				$darr = array();
				$darr['id'] = $hone['id'];
				$darr['agentid'] = $hone['agentid'];
				$darr['agtype'] = $hone['agtype'];
				$darr['memid'] = $hone['memid'];
				$darr['payid'] = $hone['payid'];
				$darr['title'] = $hone['title'];
				$darr['amt'] = $hone['amt'];
				$darr['bfamt'] = $hone['bfamt'];
				$darr['afamt'] = $hone['afamt'];
				$darr['addtime'] = $hone['addtime'];
				$darr['ymd'] = $hone['ymd'];
				$darr['states'] = $hone['states'];
				$darr['num'] = $hone['num'];
				$darr['ifdraw'] = $hone['ifdraw'];
				$darr['unino'] = $hone['unino'];
				$darr['extra'] = $hone['extra'];
				$darr['rmk'] = $hone['rmk'];
				$darr['edittime'] = $hone['edittime'];
				$darr['payrmk'] = $hone['payrmk'];
				$darr['paytype'] = $hone['paytype'];
				$res = $this->data($darr)->add();

			}

		}

		return $res;

	}
	private function csvfile($fnm) {
		ini_set('max_execution_time', 1200); //运行时间可到20分钟
		ini_set('auto_detect_line_endings', true);
		$fn = fopen($fnm, 'r');
		$res = array();
		while ($arr = fgetcsv($fn, 0, ',')) {
			$num = count($arr);
			for ($i = 0; $i < $num; $i++) {
				$arr[$i] = iconv('gbk', 'utf-8//IGNORE', $arr[$i]);
			}

			// dump($arr);

			if ($num > 1) {
				// $res[] = $arr;
				$subs = array();
				$subs['id'] = $arr[0];
				$subs['agentid'] = $arr[1];
				$subs['agtype'] = $arr[2];
				$subs['memid'] = $arr[3];
				$subs['payid'] = $arr[4];
				$subs['title'] = $arr[5];
				$subs['amt'] = $arr[6];
				$subs['bfamt'] = $arr[7];
				$subs['afamt'] = $arr[8];
				$subs['addtime'] = $arr[9];
				$subs['ymd'] = $arr[10];
				$subs['states'] = $arr[11];
				$subs['num'] = $arr[12];
				$subs['ifdraw'] = $arr[13];
				$subs['unino'] = $arr[14];
				$subs['extra'] = $arr[15];
				$subs['rmk'] = $arr[16];
				$subs['edittime'] = $arr[17];
				$subs['payrmk'] = $arr[18];
				$subs['paytype'] = $arr[19];
				$res[] = $subs;

			}
			// for ($i = 0; $i < $num; $i++) {
			// 	$res[] = iconv('gbk', 'utf-8//IGNORE', $arr[$i]);
			// }
		}
		unset($res[0]);
		fclose($fn);
		return $res;
	}

}