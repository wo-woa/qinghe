<?php
namespace Common\Model;
use Think\Model\RelationModel;

class MemberModel extends RelationModel {
	protected $_link = [
		'agent' => [
			'mapping_type' => self::BELONGS_TO, //要取自己的某个字字段关联，HAS_ONE要取别的表里某字段关联,
			'class_name' => 'agent',
			'mapping_name' => 'agent', //查出来的子数组的key名,否则用本数组的key leader，一定要加！
			'foreign_key' => 'parid', //BELONGS_TO要取自己的某个字字段关联，HAS_ONE要取别的表里某字段关联
			// 'mapping_order' => 'id desc', //按哪个排序
			// 'mapping_fields' => 'id,realname,tel', //要查询的字段,不写就全部关联
			// 'as_fields' => '' //不写这个就多一个子数组，key就是mapping_name或者leader
		], 'agent2' => [
			'mapping_type' => self::BELONGS_TO, //要取自己的某个字字段关联，HAS_ONE要取别的表里某字段关联,
			'class_name' => 'agent',
			'mapping_name' => 'agent2', //查出来的子数组的key名,否则用本数组的key leader，一定要加！
			'foreign_key' => 'granid', //BELONGS_TO要取自己的某个字字段关联，HAS_ONE要取别的表里某字段关联
			// 'mapping_order' => 'id desc', //按哪个排序
			// 'mapping_fields' => 'id,realname,tel', //要查询的字段,不写就全部关联
			// 'as_fields' => '' //不写这个就多一个子数组，key就是mapping_name或者leader
		],
		'addr' => [
			'mapping_type' => self::HAS_ONE, //要取自己的某个字字段关联，HAS_ONE要取别的表里某字段关联,
			'class_name' => 'addr',
			'mapping_name' => 'addr', //查出来的子数组的key名,否则用本数组的key leader，一定要加！
			'foreign_key' => 'memid', //BELONGS_TO要取自己的某个字字段关联，HAS_ONE要取别的表里某字段关联
			// 'mapping_order' => 'id desc', //按哪个排序
			// 'mapping_fields' => 'id,realname,tel', //要查询的字段,不写就全部关联
			// 'as_fields' => '' //不写这个就多一个子数组，key就是mapping_name或者leader
		],
	];

	public function addopenid($wxinfo) {
// ["original"] => array(9) {
		//     ["openid"] => string(28) "oBTWouLyLO1bmZTpirWMkYzCBg78"
		//     ["nickname"] => string(12) "森林部落"
		//     ["sex"] => int(1)
		//     ["language"] => string(5) "zh_CN"
		//     ["city"] => string(6) "开封"
		//     ["province"] => string(6) "河南"
		//     ["country"] => string(6) "中国"
		//     ["headimgurl"] => string(129) "http://thirdwx.qlogo.cn/mmopen/vi_32/Em8908e0xedRHO6Q8ns39v64IRwhTSwUWC6DWVUuCqpmWP6MOjiaPr4bricZV0TryX65zUUWcGapUnd54u4CkJcQ/132"
		//     ["privilege"] => array(0) {
		//     }
		//   }

		$darr = array();
		// $darr['utype'] = '';
		// $darr['cardno'] = '';
		// $darr['title'] = '';
		// $darr['img'] = '';
		// $darr['intro'] = '';
		// $darr['desc'] = '';
		// $darr['content'] = '';
		// $darr['realname'] = '';
		// $darr['tel'] = '';
		// $darr['idcardno'] = '';
		// $darr['unionid'] = '';
		$darr['openid'] = $wxinfo['openid'];
		$darr['headimgurl'] = $wxinfo['headimgurl'];
		$darr['nickname'] = $wxinfo['nickname'];
		$darr['sex'] = $wxinfo['sex'];
		$darr['addtime'] = time();
		$darr['states'] = 1;
		// $darr['rmk'] = '';
		// $darr['pstates'] = '';
		// $darr['prmk'] = '';
		// $darr['validfrom'] = '';
		// $darr['validtime'] = '';
		// $darr['lat'] = '';
		// $darr['lng'] = '';

		$agid = ksess('agid', 0);
		if ($agid > 0) {
			$ag = M('agent')->where(['id' => $agid])->find();
			if ($ag) {
				$darr['parid'] = $ag['id'];
				$darr['granid'] = $ag['parid'];
			}
		}

		try {
			$add = $this->data($darr)->add();
			return $add;
		} catch (\Exception $e) {
			$darr['nickname'] = '微信' . date('YmdHis');
			$add = $this->data($darr)->add();
			return $add;
		}

	}
	public function hasone($uid = -2) {
		die('Member数据的model出错');
		$uid = 0 + $uid;
		if ($uid > -1) {
			$hascart = $this->where(['uid' => $uid])->find();
			if ($hascart) {
				return $hascart;
			} else {
				$darr = array();
				$darr['utype'] = '';
				$darr['cardno'] = '';
				$darr['title'] = '';
				$darr['img'] = '';
				$darr['intro'] = '';
				$darr['desc'] = '';
				$darr['content'] = '';
				$darr['realname'] = '';
				$darr['tel'] = '';
				$darr['idcardno'] = '';
				$darr['unionid'] = '';
				$darr['openid'] = '';
				$darr['headimgurl'] = '';
				$darr['nickname'] = '';
				$darr['sex'] = '';
				$darr['addtime'] = '';
				$darr['states'] = '';
				$darr['rmk'] = '';
				$darr['pstates'] = '';
				$darr['prmk'] = '';
				$darr['validfrom'] = '';
				$darr['validtime'] = '';
				$darr['lat'] = '';
				$darr['lng'] = '';
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