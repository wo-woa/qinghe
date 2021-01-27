<?php 
namespace Common\Model;
use Think\Model\RelationModel;

class ProductModel extends RelationModel {
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
	//time();date('Ymd');date('YmdHis');date('Y-m-d');$ptype,$title,$img,$intro,$punit,$oamt,$amt,$content,$ordering,$states,$addtime,
	//kpost('ptype', '');kpost('title', '');kpost('img', '');kpost('intro', '');kpost('punit', '');kpost('oamt', '');kpost('amt', '');kpost('content', '');kpost('ordering', '');kpost('states', '');kpost('addtime', '');  
//kget('ptype', '');kget('title', '');kget('img', '');kget('intro', '');kget('punit', '');kget('oamt', '');kget('amt', '');kget('content', '');kget('ordering', '');kget('states', '');kget('addtime', '');

	$darr = array();
									  
$darr['ptype'] = kpost('ptype', '');//$ptype;kget('ptype', '');					  
$darr['title'] = kpost('title', '');//$title;kget('title', '');					  
$darr['img'] = kpost('img', '');//$img;kget('img', '');					  
$darr['intro'] = kpost('intro', '');//$intro;kget('intro', '');					  
$darr['punit'] = kpost('punit', '');//$punit;kget('punit', '');					  
$darr['oamt'] = kpost('oamt', '');//$oamt;kget('oamt', '');					  
$darr['amt'] = kpost('amt', '');//$amt;kget('amt', '');					  
$darr['content'] = kpost('content', '');//$content;kget('content', '');					  
$darr['ordering'] = kpost('ordering', '');//$ordering;kget('ordering', '');					  
$darr['states'] = kpost('states', '');//$states;kget('states', '');					  
$darr['addtime'] = kpost('addtime', '');//$addtime;kget('addtime', '');					  

				$add = $this->data($darr)->add();
				if ($add) {
						if($tp==1){
							return $this->where([ 'id' => $add])->find();
						}
						return $add;
				}
				return false;
	}

		public function findone($id = 0) {
			//D('Product')->findone($id);
			return $this->where(['id'=>$id])->find();
		}
		public function lists($cond) {
			//D('Product')->lists($cond);
			return $this->where($cond)->order('id desc')->select();
		}

		public function hasone($uid = -2) {
		die('Product数据的model出错');
		$uid = 0 + $uid;
		if ($uid > -1) {
			$hascart = $this->where(['uid' => $uid])->find();
			if ($hascart) {
				return $hascart;
			} else {
				$darr = array();
				$darr['ptype'] = '';$darr['title'] = '';$darr['img'] = '';$darr['intro'] = '';$darr['punit'] = '';$darr['oamt'] = '';$darr['amt'] = '';$darr['content'] = '';$darr['ordering'] = '';$darr['states'] = '';$darr['addtime'] = '';
				$hascart = $this->data($darr)->add();
				if ($hascart) {
					return $this->where(['uid' => $uid, 'id' => $hascart])->find();
				}
			}
		}
		return false;
	}
	public function delone($id,$cond) {
		//$cond=['id' => $id];
		return $this->where($cond)->delete();
	}


public function testaaaa($id,$cond) {
	$darr = array();
	//$darr['id'] =  $hone['id'];$darr['ptype'] =  $hone['ptype'];$darr['title'] =  $hone['title'];$darr['img'] =  $hone['img'];$darr['intro'] =  $hone['intro'];$darr['punit'] =  $hone['punit'];$darr['oamt'] =  $hone['oamt'];$darr['amt'] =  $hone['amt'];$darr['content'] =  $hone['content'];$darr['ordering'] =  $hone['ordering'];$darr['states'] =  $hone['states'];$darr['addtime'] =  $hone['addtime'];    

  		//$save = M('Product')->where(['states'=>1,'states'=>['gt',1],'addtime'=>time(),'states'=>['between',$min.','.$max]])->data($darr)->save();
  		  
		$hone = M('Product')->where(['states'=>1,'states'=>['gt',1],'addtime'=>time(),'states'=>['between',$min.','.$max]]) ->select();
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
	$darr['id'] =  $hone['id'];$darr['ptype'] =  $hone['ptype'];$darr['title'] =  $hone['title'];$darr['img'] =  $hone['img'];$darr['intro'] =  $hone['intro'];$darr['punit'] =  $hone['punit'];$darr['oamt'] =  $hone['oamt'];$darr['amt'] =  $hone['amt'];$darr['content'] =  $hone['content'];$darr['ordering'] =  $hone['ordering'];$darr['states'] =  $hone['states'];$darr['addtime'] =  $hone['addtime'];					$res = $this->data($darr)->add();

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
	 $subs['id'] =  $arr[0];$subs['ptype'] =  $arr[1];$subs['title'] =  $arr[2];$subs['img'] =  $arr[3];$subs['intro'] =  $arr[4];$subs['punit'] =  $arr[5];$subs['oamt'] =  $arr[6];$subs['amt'] =  $arr[7];$subs['content'] =  $arr[8];$subs['ordering'] =  $arr[9];$subs['states'] =  $arr[10];$subs['addtime'] =  $arr[11];
$res[] =$subs;

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