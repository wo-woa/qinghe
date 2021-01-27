<?php
namespace Common\Model;
use Think\Model\RelationModel;

//states 0新购物车3要支付1成功2失败
class PaymentModel extends RelationModel {
	protected $_link = [
		'member' => [
			'mapping_type' => self::BELONGS_TO, //要取自己的某个字字段关联，HAS_ONE要取别的表里某字段关联,
			'class_name' => 'member',
			'mapping_name' => 'member', //查出来的子数组的key名,否则用本数组的key leader，一定要加！
			'foreign_key' => 'memid', //BELONGS_TO要取自己的某个字字段关联，HAS_ONE要取别的表里某字段关联
			// 'mapping_order' => 'id desc', //按哪个排序
			// 'mapping_fields' => 'id,realname,tel', //要查询的字段,不写就全部关联
			// 'as_fields' => '' //不写这个就多一个子数组，key就是mapping_name或者leader
		], 'agent' => [
			'mapping_type' => self::BELONGS_TO, //要取自己的某个字字段关联，HAS_ONE要取别的表里某字段关联,
			'class_name' => 'agent',
			'mapping_name' => 'agent', //查出来的子数组的key名,否则用本数组的key leader，一定要加！
			'foreign_key' => 'parid', //BELONGS_TO要取自己的某个字字段关联，HAS_ONE要取别的表里某字段关联
			// 'mapping_order' => 'id desc', //按哪个排序
			// 'mapping_fields' => 'id,realname,tel', //要查询的字段,不写就全部关联
			// 'as_fields' => '' //不写这个就多一个子数组，key就是mapping_name或者leader
		], 'cart' => [
			'mapping_type' => self::HAS_MANY, //要取自己的某个字字段关联，HAS_ONE要取别的表里某字段关联,
			'class_name' => 'cart',
			'mapping_name' => 'cart', //查出来的子数组的key名,否则用本数组的key leader，一定要加！
			'foreign_key' => 'payid', //BELONGS_TO要取自己的某个字字段关联，HAS_ONE要取别的表里某字段关联
			// 'mapping_order' => 'id desc', //按哪个排序
			// 'mapping_fields' => 'id,realname,tel', //要查询的字段,不写就全部关联
			// 'as_fields' => '' //不写这个就多一个子数组，key就是mapping_name或者leader
		],
	];
	public function hasone($uid = -2) {
		die('Payment数据的model出错');
		$uid = 0 + $uid;
		if ($uid > -1) {
			$hascart = $this->where(['uid' => $uid])->find();
			if ($hascart) {
				return $hascart;
			} else {
				$darr = array();
				$darr['memid'] = '';
				$darr['title'] = '';
				$darr['qty'] = '';
				$darr['gunit'] = '';
				$darr['unitprice'] = '';
				$darr['amt'] = '';
				$darr['paytype'] = '';
				$darr['orderno'] = '';
				$darr['ordertime'] = '';
				$darr['okstates'] = '';
				$darr['oktime'] = '';
				$darr['okorderno'] = '';
				$darr['hidden'] = '';
				$darr['extra'] = '';
				$darr['rmk'] = '';
				$darr['pstates'] = '';
				$darr['prmk'] = '';
				$hascart = $this->data($darr)->add();
				if ($hascart) {
					return $this->where(['uid' => $uid, 'id' => $hascart])->find();
				}
			}
		}
		return false;
	}

	//D('Payment')->touser();
	public function touser($sinfo) {
		if ($sinfo['states'] < 1) {
			return;
		}

		if ($sinfo['states'] == 4) {
			return;
		}
		$memid = $sinfo['memid'];
		$user = M('member')->where(['id' => $memid])->find();
		$openid = $user['openid'];
		// $openid = 'ong0U1R8sDYGdci-tthf1_IhossY';
		// $url = WEBROOTURL . '/User/bookinglist/msgid/' . $msgid;

		$v = $sinfo;
		$key = 0;
		$arr = array();
		if ($v['itemid'] > 0) {
			$vv = M('infos')->find($v['itemid']);

			if ($vv) {
				if ($vv['keywords'] > 0) {
					if ($vv['keywords'] == 1) {
						$s = '主题区';
					} else {
						$s = $vv['keywords'] . 'A';
					}
				} else {
					$s = '免预约';
				}
			}

			$arr[$key]['title'] = $s . '/' . $v['title'];
		}
		if ($v['cateid'] > 0) {
			$cates = M('cates')->find($v['cateid']);

			$arr[$key]['cateid'] = $cates['catename'] . '/' . $arr[$key]['title'];

		} else {
			$arr[$key]['cateid'] = '-';
		}

		// $txt = '出票失败，详情请看/预约记录/票务信息';
		// if ($sinfo['states'] == 4) {
		// 	$txt = '预约成功，游玩日08:00显示票务信息 凭票务信息/换票入园';
		// }

		$res = $this->wxconfig()->template_message->send([
			'touser' => $openid,
			'template_id' => 'X0OpBkLEcQYAGXVizlEgYBmDpjFnhWu3XezCZNoZ8lY', //TM00877
			// 'page' => 'index',
			// 'form_id' => 'form-id',
			// 'topcolor' => '#FF0000',

			// 'url' => $url,
			'data' => [
				'PkgName' => $arr[$key]['cateid'], // $sinfo['title'], //产品名称
				'OrderID' => $sinfo['booktimestr'] . '/' . $user['tel'], //订单号
				// 'keyword3' => date('Y-m-d H:i', $minfo['addtime']),
				// 'keyword4' => '点击打开页面查看',
				'first' => $txt,
				'Remark' => '如有疑问，请联系公众号客服（向公众号发送消息）随时为您解答。',

				// ...
			],
		]);
	}

	public function easywxpay() {
		require_once THINK_PATH . 'vendor/autoload.php';
		$config = [
			'app_id' => SYS_APPID,
			// 'secret' => WX_SEC,
			'mch_id' => '1605112345',
			'key' => 'tyefae689bda50c852d807e43aa49sef',
			// 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
			// 'cert_path' => THINK_PATH . 'vendor/cert/apiclient_cert.pem',
			// 'key_path' => THINK_PATH . 'vendor/cert/apiclient_key.pem',
			'log' => [
				'level' => 'debug',
				'file' => RUNTIME_PATH . 'Wechatlogpay/' . date('YmdH') . '.log',
			],
		];
		$app = \EasyWeChat\Factory::payment($config);
		return $app;
	}

	public function wxconfig() {
		require_once THINK_PATH . 'vendor/autoload.php';
		$config = [
			'app_id' => SYS_APPID,
			'secret' => SYS_APPSEC,
			// 'response_type' => 'array',
			// 'mch_id' => '1562384861',
			// 'key' => '20191113wwwtxynpcom20191113wwwtx',
			// 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
			// 'cert_path' => THINK_PATH . 'vendor/cert/apiclient_cert.pem',
			// 'key_path' => THINK_PATH . 'vendor/cert/apiclient_key.pem',
			// 'log' => [
			// 	'level' => 'debug',
			// 	'file' => RUNTIME_PATH . 'Wechatlogpay/' . date('YmdH') . '.log',
			// ],
		];
		$app = \EasyWeChat\Factory::officialAccount($config);
		return $app;
	}

	public function prepay($arr) {
		$darr = [
			'body' => $arr['title'],
			'out_trade_no' => $arr['orderno'],
			'total_fee' => ktofen($arr['amt']),
			// 'spbill_create_ip' => '123.12.12.123', // 可选，如不传该参数，SDK 将会自动获取相应 IP 地址
			'notify_url' => 'http://project.freeer.cn/Wxapp/noti', // WEBROOTURL . 支付结果通知网址，如果不设置则会使用配置里的默认地址
			'trade_type' => 'JSAPI', // 请对应换成你的支付方式对应的值类型
			'openid' => ksess('openid'), //$arr['openid'],
		];
		$res = $this->easywxpay()->order->unify($darr);
		F('prepay/' . $arr['orderno'], $res);
		if ($res['return_code'] === 'SUCCESS' && $res['result_code'] === 'SUCCESS') {
			//array(6) {
			//   ["appId"] => string(18) "wx24b18908b3795728"
			//   ["nonceStr"] => string(13) "5bbdd53f71cb4"
			//   ["package"] => string(46) "prepay_id=wx10182959437058e14c20f3da2496236163"
			//   ["signType"] => string(3) "MD5"
			//   ["paySign"] => string(32) "CB0851680FC9156F34EEECC64B192414"
			//   ["timestamp"] => string(10) "1539167551"
			// }

			// $this->mon = ktofen($arr['amt']);
			return $this->easywxpay()->jssdk->bridgeConfig($res['prepay_id']);

			// $this->display('Wxapp/pay');
			// die;
		}
		echo ('<h1>数据错误</h1>');
		die;
	}

	public function noti() {

		F('paynoti' . time(), kpost());
		F('paynotig' . time(), kget());
		$resp = $this->easywxpay()->handlePaidNotify(function ($message, $fail) {
			F('paynotigdd' . time(), $message);
			$cond = array();
			// $cond['paytype'] = 1;
			$cond['orderno'] = $message['out_trade_no'];
			$cond['okstates'] = 3;
			$cond['oktime'] = 0;
			$res = M('payment')->where($cond)->find();
			if (!$res) {
				F('payerr/nodata_' . time() . rand(1, 99999), $message);
				return true;
			}

			if ($message['return_code'] === 'SUCCESS') {
				if ($message['result_code'] === 'SUCCESS') {

					//支付成功，写入回调，改状态，团加1
					$ntime = time();
					// $mod = M();
					// $mod->startTrans();
					$darr = array();
					$darr['okstates'] = 1;
					$darr['oktime'] = $ntime;
					$darr['okorderno'] = $message['transaction_id'];

					$spay = M('payment')->where($cond)->data($darr)->save();

					// $cond = ['id' => $res['memid']];
					// $hone = M('member')->where($cond)->find();
					// $darr = array();
					// $darr['utype'] = 2;

					// if ($hone['validtime'] < $ntime) {
					// 	//不在有限期中了
					// 	$darr['validfrom'] = $ntime;
					// 	$darr['validtime'] = $darr['validfrom'] + 365 * 24 * 3600;
					// } else {
					// 	$darr['validtime'] = $ntime + 365 * 24 * 3600; //有效期中加一年
					// }

					// $smem = M('member')->where($cond)->data($darr)->save();

					// $url = 'http://hym2mp.freeer.cn/api/cross/coupon/create?token=hymv2.2022&openid=' . $hone['openid'] . '&order_id=' . $message['transaction_id'];
					// $res = curlpost($url, []);

					// F('getcoupon', ['hone' => $hone, ['oid' => $message['transaction_id']]]);
					D('Fundlog')->todo();
					return true;
					// if ($spay && $smem) {
					// 	$mod->commit();

					// } else {
					// 	$mod->rollback();
					// }

					// 用户支付失败
				} elseif ($message['result_code'] === 'FAIL') {
					$darr = array();
					$darr['okstates'] = 2;
					$darr['oktime'] = time();
					// $darr['okorderno'] = $data['transaction_id'];
					M('payment')->where($cond)->data($darr)->save(); //支付失败

				}
				return true;
			} else {
				return $fail('通信失败，请稍后再通知我');
			}

		});
		$resp->send();
	}

	public function noti_err() {

		F('paynoti' . time(), kpost());
		F('paynotig' . time(), kget());
		$resp = $this->easywxpay()->handlePaidNotify(function ($message, $fail) {
			F('paynotigdd' . time(), $message);
			$cond = array();
			// $cond['paytype'] = 1;
			$cond['orderno'] = $message['out_trade_no'];
			$cond['okstates'] = 0;
			$cond['oktime'] = 0;
			$res = M('payment')->where($cond)->find();
			if (!$res) {
				F('wxapp/nodata_' . time() . rand(1, 99999), $message);
				return true;
			}

			if ($message['return_code'] === 'SUCCESS') {
				if ($message['result_code'] === 'SUCCESS') {

					// $darr = array();
					// $darr['okstates'] = '';
					// $darr['oktime'] = '';
					// $darr['okorderno'] = '';
					// $darr['hidden'] = '';
					// $darr['extra'] = '';
					// $darr['rmk'] = '';
					// $darr['pstates'] = '';
					// $darr['prmk'] = '';

					// $darr = array();
					// $darr['okstates'] = 1;
					// $darr['oktime'] = time();
					// $darr['okorderno'] = $message['transaction_id'];

					// M('payment')->where($cond)->data($darr)->save();

					//支付成功，写入回调，改状态，团加1
					$ntime = time();
					$mod = M();
					$mod->startTrans();
					$darr = array();
					$darr['okstates'] = 1;
					$darr['oktime'] = $ntime;
					$darr['okorderno'] = $message['transaction_id'];

					$spay = $mod->table('app_payment')->where($cond)->data($darr)->save();

					$cond = ['id' => $res['memid']];
					$hone = $mod->table('app_member')->where($cond)->find();
					$darr = array();
					$darr['utype'] = 2;

					if ($hone['validtime'] < $ntime) {
						//不在有限期中了
						$darr['validfrom'] = $ntime;
						$darr['validtime'] = $darr['validfrom'] + 365 * 24 * 3600;
					} else {
						$darr['validtime'] = $hone['validfrom'] + 365 * 24 * 3600; //有效期中加一年
					}

					// $isok = $darr['oknum'] >= $hone['num'];
					// $darr['states'] = $isok ? 2 : 1; //不满团为1，满团为2
					// $darr['edittime'] = time();

					$smem = $mod->table('app_member')->where($cond)->data($darr)->save();

					// if ($isok) {
					// 	$cond = array();
					// 	$cond['paytype'] = 1;
					// 	$cond['teamid'] = $res['teamid'];
					// 	$cond['okstates'] = 1;

					// 	$mod->table('app_payment')->where($cond)->data(['teamok' => 1])->save();
					// }

					if ($spay && $smem) {
						$mod->commit();
						return true;

					} else {
						$mod->rollback();
					}

					// 用户支付失败
				} elseif ($message['result_code'] === 'FAIL') {
					$darr = array();
					$darr['okstates'] = 2;
					$darr['oktime'] = time();
					// $darr['okorderno'] = $data['transaction_id'];
					M('payment')->where($cond)->data($darr)->save(); //支付失败

				}
				return true;
			} else {
				return $fail('通信失败，请稍后再通知我');
			}

		});
		$resp->send();
	}
}