<?php
namespace Common\Model;
use Think\Model\RelationModel;

class AgentModel extends RelationModel {
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
		//time();date('Ymd');date('YmdHis');date('Y-m-d');$username,$pswd,$realname,$tel,$zone,$title,$img,$intro,$desc,$content,$addtime,$states,$openid,$nickname,$parid,$extra,$rmk,$alevel,
		//kpost('username', '');kpost('pswd', '');kpost('realname', '');kpost('tel', '');kpost('zone', '');kpost('title', '');kpost('img', '');kpost('intro', '');kpost('desc', '');kpost('content', '');kpost('addtime', '');kpost('states', '');kpost('openid', '');kpost('nickname', '');kpost('parid', '');kpost('extra', '');kpost('rmk', '');kpost('alevel', '');
		//kget('username', '');kget('pswd', '');kget('realname', '');kget('tel', '');kget('zone', '');kget('title', '');kget('img', '');kget('intro', '');kget('desc', '');kget('content', '');kget('addtime', '');kget('states', '');kget('openid', '');kget('nickname', '');kget('parid', '');kget('extra', '');kget('rmk', '');kget('alevel', '');

		$darr = array();

		$darr['username'] = kpost('username', ''); //$username;kget('username', '');
		$darr['pswd'] = kpost('pswd', ''); //$pswd;kget('pswd', '');
		$darr['realname'] = kpost('realname', ''); //$realname;kget('realname', '');
		$darr['tel'] = kpost('tel', ''); //$tel;kget('tel', '');
		$darr['zone'] = kpost('zone', ''); //$zone;kget('zone', '');
		$darr['title'] = kpost('title', ''); //$title;kget('title', '');
		$darr['img'] = kpost('img', ''); //$img;kget('img', '');
		$darr['intro'] = kpost('intro', ''); //$intro;kget('intro', '');
		$darr['desc'] = kpost('desc', ''); //$desc;kget('desc', '');
		$darr['content'] = kpost('content', ''); //$content;kget('content', '');
		$darr['addtime'] = kpost('addtime', ''); //$addtime;kget('addtime', '');
		$darr['states'] = kpost('states', ''); //$states;kget('states', '');
		$darr['openid'] = kpost('openid', ''); //$openid;kget('openid', '');
		$darr['nickname'] = kpost('nickname', ''); //$nickname;kget('nickname', '');
		$darr['parid'] = kpost('parid', ''); //$parid;kget('parid', '');
		$darr['extra'] = kpost('extra', ''); //$extra;kget('extra', '');
		$darr['rmk'] = kpost('rmk', ''); //$rmk;kget('rmk', '');
		$darr['alevel'] = kpost('alevel', ''); //$alevel;kget('alevel', '');

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
		//D('Agent')->findone($id);
		return $this->where(['id' => $id])->find();
	}
	public function lists($cond) {
		//D('Agent')->lists($cond);
		return $this->where($cond)->order('id desc')->select();
	}

	public function hasone($uid = -2) {
		die('Agent数据的model出错');
		$uid = 0 + $uid;
		if ($uid > -1) {
			$hascart = $this->where(['uid' => $uid])->find();
			if ($hascart) {
				return $hascart;
			} else {
				$darr = array();
				$darr['username'] = '';
				$darr['pswd'] = '';
				$darr['realname'] = '';
				$darr['tel'] = '';
				$darr['zone'] = '';
				$darr['title'] = '';
				$darr['img'] = '';
				$darr['intro'] = '';
				$darr['desc'] = '';
				$darr['content'] = '';
				$darr['addtime'] = '';
				$darr['states'] = '';
				$darr['openid'] = '';
				$darr['nickname'] = '';
				$darr['parid'] = '';
				$darr['extra'] = '';
				$darr['rmk'] = '';
				$darr['alevel'] = '';
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
		//$darr['id'] =  $hone['id'];$darr['username'] =  $hone['username'];$darr['pswd'] =  $hone['pswd'];$darr['realname'] =  $hone['realname'];$darr['tel'] =  $hone['tel'];$darr['zone'] =  $hone['zone'];$darr['title'] =  $hone['title'];$darr['img'] =  $hone['img'];$darr['intro'] =  $hone['intro'];$darr['desc'] =  $hone['desc'];$darr['content'] =  $hone['content'];$darr['addtime'] =  $hone['addtime'];$darr['states'] =  $hone['states'];$darr['openid'] =  $hone['openid'];$darr['nickname'] =  $hone['nickname'];$darr['parid'] =  $hone['parid'];$darr['extra'] =  $hone['extra'];$darr['rmk'] =  $hone['rmk'];$darr['alevel'] =  $hone['alevel'];

		//$save = M('Agent')->where(['states'=>1,'states'=>['gt',1],'addtime'=>time(),'states'=>['between',$min.','.$max]])->data($darr)->save();

		$hone = M('Agent')->where(['states' => 1, 'states' => ['gt', 1], 'addtime' => time(), 'states' => ['between', $min . ',' . $max]])->select();
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
				$darr['username'] = $hone['username'];
				$darr['pswd'] = $hone['pswd'];
				$darr['realname'] = $hone['realname'];
				$darr['tel'] = $hone['tel'];
				$darr['zone'] = $hone['zone'];
				$darr['title'] = $hone['title'];
				$darr['img'] = $hone['img'];
				$darr['intro'] = $hone['intro'];
				$darr['desc'] = $hone['desc'];
				$darr['content'] = $hone['content'];
				$darr['addtime'] = $hone['addtime'];
				$darr['states'] = $hone['states'];
				$darr['openid'] = $hone['openid'];
				$darr['nickname'] = $hone['nickname'];
				$darr['parid'] = $hone['parid'];
				$darr['extra'] = $hone['extra'];
				$darr['rmk'] = $hone['rmk'];
				$darr['alevel'] = $hone['alevel'];
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
				$subs['username'] = $arr[1];
				$subs['pswd'] = $arr[2];
				$subs['realname'] = $arr[3];
				$subs['tel'] = $arr[4];
				$subs['zone'] = $arr[5];
				$subs['title'] = $arr[6];
				$subs['img'] = $arr[7];
				$subs['intro'] = $arr[8];
				$subs['desc'] = $arr[9];
				$subs['content'] = $arr[10];
				$subs['addtime'] = $arr[11];
				$subs['states'] = $arr[12];
				$subs['openid'] = $arr[13];
				$subs['nickname'] = $arr[14];
				$subs['parid'] = $arr[15];
				$subs['extra'] = $arr[16];
				$subs['rmk'] = $arr[17];
				$subs['alevel'] = $arr[18];
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

	public function addkf($openid, $nickname, $img, $tp) {
		$hone = $this->where(['openid' => $openid])->find(); //, 'states' => 0
		if ($hone) {
			echo '<h1 style="padding:20px;font-size:30px;text-align:center;">平台已有记录，请联系管理员！';
			die;
		}
		$darr = array();
		// $darr['username'] = '';
		// $darr['pswd'] = '';
		// $darr['realname'] = '';
		// $darr['tel'] = '';
		// $darr['zone'] = '';
		// $darr['title'] = '';
		$darr['img'] = $img;
		// $darr['intro'] = '';
		// $darr['desc'] = '';
		// $darr['content'] = '';
		$darr['addtime'] = time();
		$darr['states'] = 0;
		$darr['openid'] = $openid;
		$darr['nickname'] = $nickname;
		// $darr['shopid'] = 0;
		// $darr['extra'] = '';
		// $darr['rmk'] = '';
		$darr['alevel'] = $tp < 1 ? 10 : 20;
		$darr['parid'] = $tp < 1 ? 0 : $tp;

		$hascart = $this->data($darr)->add();
		if ($hascart) {
			echo '<h1 style="padding:20px;font-size:30px;text-align:center;">已添加代理，请联系管理员在后台审核通过！';
			die;
		}
		echo '<h1 style="padding:20px;font-size:30px;text-align:center;">添加代理失败，请联系管理员！';
		die;
	}

//D('Agent')->addlevel();
	public function addlevel() {
		$allag = $this->select();
		foreach ($allag as $key => $v) {
			$agid = $v['id'];
			$str = strtotime(date('Y-m-01'));

			$num = 0 + M('payment')->where(['oktime' => ['gt', $str]])->count();

			$one = M('config')->where(['cname' => 'agent_update'])->find();
			$updatenum = 0 + floatval($one['cvalue']);
			if ($num > $updatenum) {
				M('agent')->where(['id' => $agid])->data(['alevel' => 11])->save();
			}

		}
	}

}