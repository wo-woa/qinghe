<?php
namespace Common\Model;
use Think\Model\RelationModel;

class AddrModel extends RelationModel {
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

	public function addone($memid, $tp = 0) {
		// die;
		//time();date('Ymd');date('YmdHis');date('Y-m-d');$memid,$realname,$province,$city,$district,$addr,$tel,$ordering,$states,$addtime,
		//kpost('memid', '');kpost('realname', '');kpost('province', '');kpost('city', '');kpost('district', '');kpost('addr', '');kpost('tel', '');kpost('ordering', '');kpost('states', '');kpost('addtime', '');
		//kget('memid', '');kget('realname', '');kget('province', '');kget('city', '');kget('district', '');kget('addr', '');kget('tel', '');kget('ordering', '');kget('states', '');kget('addtime', '');

		$darr = array();

		$darr['memid'] = $memid; //kget('memid', '');
		$darr['realname'] = kpost('realname', ''); //$realname;kget('realname', '');
		// $darr['province'] = kpost('province', ''); //$province;kget('province', '');
		// $darr['city'] = kpost('city', ''); //$city;kget('city', '');
		// $darr['district'] = kpost('district', ''); //$district;kget('district', '');
		$darr['addr'] = kpost('addr', ''); //$addr;kget('addr', '');
		$darr['tel'] = kpost('tel', ''); //$tel;kget('tel', '');
		// $darr['ordering'] = kpost('ordering', ''); //$ordering;kget('ordering', '');
		// $darr['states'] = kpost('states', ''); //$states;kget('states', '');
		$darr['addtime'] = time(); //$addtime;kget('addtime', '');

		$hone = $this->findone($memid);
		if ($hone) {
			return $this->where(['id' => $hone['id']])->data($darr)->save();
		}

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
		//D('Addr')->findone($id);
		return $this->where(['memid' => $id])->find();
	}
	public function lists($cond) {
		//D('Addr')->lists($cond);
		return $this->where($cond)->order('id desc')->select();
	}

	public function hasone($uid = -2) {
		die('Addr数据的model出错');
		$uid = 0 + $uid;
		if ($uid > -1) {
			$hascart = $this->where(['uid' => $uid])->find();
			if ($hascart) {
				return $hascart;
			} else {
				$darr = array();
				$darr['memid'] = '';
				$darr['realname'] = '';
				$darr['province'] = '';
				$darr['city'] = '';
				$darr['district'] = '';
				$darr['addr'] = '';
				$darr['tel'] = '';
				$darr['ordering'] = '';
				$darr['states'] = '';
				$darr['addtime'] = '';
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
		//$darr['id'] =  $hone['id'];$darr['memid'] =  $hone['memid'];$darr['realname'] =  $hone['realname'];$darr['province'] =  $hone['province'];$darr['city'] =  $hone['city'];$darr['district'] =  $hone['district'];$darr['addr'] =  $hone['addr'];$darr['tel'] =  $hone['tel'];$darr['ordering'] =  $hone['ordering'];$darr['states'] =  $hone['states'];$darr['addtime'] =  $hone['addtime'];

		//$save = M('Addr')->where(['states'=>1,'states'=>['gt',1],'addtime'=>time(),'states'=>['between',$min.','.$max]])->data($darr)->save();

		$hone = M('Addr')->where(['states' => 1, 'states' => ['gt', 1], 'addtime' => time(), 'states' => ['between', $min . ',' . $max]])->select();
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
				$darr['memid'] = $hone['memid'];
				$darr['realname'] = $hone['realname'];
				$darr['province'] = $hone['province'];
				$darr['city'] = $hone['city'];
				$darr['district'] = $hone['district'];
				$darr['addr'] = $hone['addr'];
				$darr['tel'] = $hone['tel'];
				$darr['ordering'] = $hone['ordering'];
				$darr['states'] = $hone['states'];
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
				$subs['memid'] = $arr[1];
				$subs['realname'] = $arr[2];
				$subs['province'] = $arr[3];
				$subs['city'] = $arr[4];
				$subs['district'] = $arr[5];
				$subs['addr'] = $arr[6];
				$subs['tel'] = $arr[7];
				$subs['ordering'] = $arr[8];
				$subs['states'] = $arr[9];
				$subs['addtime'] = $arr[10];
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