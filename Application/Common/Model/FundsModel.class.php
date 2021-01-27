<?php
namespace Common\Model;
use Think\Model\RelationModel;

class FundsModel extends RelationModel {
	// protected $_link = [
	// 	'member' => [
	// 		'mapping_type' => self::BELONGS_TO, //要取自己的某个字字段关联，HAS_ONE要取别的表里某字段关联,
	// 		'class_name' => 'member',
	// 		'mapping_name' => 'member', //查出来的子数组的key名,否则用本数组的key leader，一定要加！
	// 		'foreign_key' => 'memid', //BELONGS_TO要取自己的某个字字段关联，HAS_ONE要取别的表里某字段关联
	// 		// 'mapping_order' => 'id desc', //按哪个排序
	// 		// 'mapping_fields' => 'id,realname,tel', //要查询的字段,不写就全部关联
	// 		// 'as_fields' => '' //不写这个就多一个子数组，key就是mapping_name或者leader
	// 	],
	// ];

	public function addone($tp = 0) {
		die;
		//time();date('Ymd');date('YmdHis');date('Y-m-d');$agentid,$amt,$num,$states,$edittime,$addtime,
		//kpost('agentid', '');kpost('amt', '');kpost('num', '');kpost('states', '');kpost('edittime', '');kpost('addtime', '');
		//kget('agentid', '');kget('amt', '');kget('num', '');kget('states', '');kget('edittime', '');kget('addtime', '');

		$darr = array();

		$darr['agentid'] = kpost('agentid', ''); //$agentid;kget('agentid', '');
		$darr['amt'] = kpost('amt', ''); //$amt;kget('amt', '');
		$darr['num'] = kpost('num', ''); //$num;kget('num', '');
		$darr['states'] = kpost('states', ''); //$states;kget('states', '');
		$darr['edittime'] = kpost('edittime', ''); //$edittime;kget('edittime', '');
		$darr['addtime'] = kpost('addtime', ''); //$addtime;kget('addtime', '');

		$add = $this->data($darr)->add();
		if ($add) {
			if ($tp == 1) {
				return $this->where(['id' => $add])->find();
			}
			return $add;
		}
		return false;
	}

	public function lists($cond) {
		//D('Funds')->lists($cond);
		return $this->where($cond)->order('id desc')->select();
	}

	public function findone($uid = -2) {
		$uid = 0 + $uid;
		if ($uid > -1) {
			$hascart = $this->where(['agentid' => $uid])->find();
			if ($hascart) {
				return $hascart;
			} else {
				$darr = array();
				$darr['agentid'] = $uid;
				$darr['amt'] = 0;
				$darr['num'] = 0;
				$darr['states'] = 1;
				$darr['edittime'] = time();
				$darr['addtime'] = time();
				$hascart = $this->data($darr)->add();
				if ($hascart) {
					return $this->where(['agentid' => $uid])->find();
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
		//$darr['id'] =  $hone['id'];$darr['agentid'] =  $hone['agentid'];$darr['amt'] =  $hone['amt'];$darr['num'] =  $hone['num'];$darr['states'] =  $hone['states'];$darr['edittime'] =  $hone['edittime'];$darr['addtime'] =  $hone['addtime'];

		//$save = M('Funds')->where(['states'=>1,'states'=>['gt',1],'addtime'=>time(),'states'=>['between',$min.','.$max]])->data($darr)->save();

		$hone = M('Funds')->where(['states' => 1, 'states' => ['gt', 1], 'addtime' => time(), 'states' => ['between', $min . ',' . $max]])->select();
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
				$darr['amt'] = $hone['amt'];
				$darr['num'] = $hone['num'];
				$darr['states'] = $hone['states'];
				$darr['edittime'] = $hone['edittime'];
				$darr['addtime'] = $hone['addtime'];
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
				$subs['amt'] = $arr[2];
				$subs['num'] = $arr[3];
				$subs['states'] = $arr[4];
				$subs['edittime'] = $arr[5];
				$subs['addtime'] = $arr[6];
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