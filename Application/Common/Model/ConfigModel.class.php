<?php 
namespace Common\Model;
use Think\Model\RelationModel;

class ConfigModel extends RelationModel {
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
	public function hasone($uid = -2) {
		die('Config数据的model出错');
		$uid = 0 + $uid;
		if ($uid > -1) {
			$hascart = $this->where(['uid' => $uid])->find();
			if ($hascart) {
				return $hascart;
			} else {
				$darr = array();
				$darr['cpic'] = '';$darr['cname'] = '';$darr['cvalue'] = '';$darr['desc'] = '';$darr['content'] = '';$darr['extra'] = '';
				$hascart = $this->data($darr)->add();
				if ($hascart) {
					return $this->where(['uid' => $uid, 'id' => $hascart])->find();
				}
			}
		}
		return false;
	}

	
	public function allin($id = 0) {}
}