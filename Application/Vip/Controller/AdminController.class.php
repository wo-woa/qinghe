<?php
namespace Vip\Controller;
// use Think\Controller;
use Vip\Controller\IndexController;

class AdminController extends IndexController {
	public function __construct() {
		parent::__construct();
		//验证后的用户
	}
	// 系统显示
	public function pswd() {
		if (IS_POST) {
			$obj = D('Admin');
			if (!$obj->create()) {
				$this->error($obj->getError()); //验证不通过
				die;
			} else {
				// $res = kdbfind('admin', ['username' => 'admin', 'pswd' => sha1(kpost('oldpswd'))]);
				$res = $obj->where(['username' => 'admin', 'pswd' => kpost('oldpswd')])->find();
				if ($res) {
					$obj->id = $res['id'];
				} else {
					$this->error('现密码不正确');
					die;
				}
				$obj->addtime = time();
				$obj->pswd = kpost('pswd');
				$save = $obj->save();
				// dump($obj->_sql());die;
				// dump($obj);die;
				if ($save) {
					session('admin_user', null);
					$this->success('修改成功');
					die;
				} else {
					$this->error('修改失败');
					die;
				}
			}
		}
		$this->display();
	}
	//备份数据库
	public function db() {
		if (!IS_POST) {
			$this->display();
			die;
		}
		import('ORG.Custom.backupsql');
		$db = new \DBManage(C('DB_HOST'), C('DB_USER'), C('DB_PWD'), C('DB_NAME'), 'utf8');

		// $smtp = M('smtp');
		// $stmpArr = $smtp->find();
		$backup = $db->backup();

		if ($backup) {
			$stmpArr['smtp'] = 'smtp.163.com';
			$stmpArr['validation'] = 1;

			$stmpArr['send_email'] = 'motejidi@163.com';
			$stmpArr['password'] = 'lyklyk35';
			$stmpArr['addresser'] = '技术备份';

			$stmpArr['receipt_email'] = I('get.user', 0) == 1 ? I('post.email') : '835402302@qq.com'; //程序员邮箱
			$stmpArr['title'] = WEBROOTURL . " 数据库备份 ：" . date('Y-m-d H:i:s');
			$stmpArr['content'] = WEBROOTURL . '<div>
														备份时间:' . date('Y-m-d H:i:s') . '
													</div>';

			$stmpArr['addattachment'] = $backup;
			kemail($stmpArr); //发送邮件
			// $this->Record('数据库备份成功'); //后台操作
			//删除备份的数据表
			if (file_exists($backup)) {
				unlink($backup); //删除它
			}
			$this->success("数据库备份成功，请查您的邮箱！");
			exit;

		} else {
			// $this->Record('数据库备份失败'); //后台操作
			$this->error("数据库备份失败");
		}

	}
//备份数据库
	public function db2() {
		import('ORG.Custom.backupsql');
		$db = new \DBManage(C('DB_HOST'), C('DB_USER'), C('DB_PWD'), C('DB_NAME'), 'utf8');

		$smtp = M('smtp');
		$stmpArr = $smtp->find();
		$backup = $db->backup();
		if ($backup) {
			if ($this->_post('email')) {
				$stmpArr['receipt_email'] = $this->_post('email');
				$stmpArr['title'] = "数据库备份" . time();
				$stmpArr['content'] = '<div>
														备份时间:' . date('Y/m/d H:i:s') . '
													</div>';
				$stmpArr['addattachment'] = $backup;
				$this->email_send($stmpArr); //发送邮件
				$this->Record('数据库备份成功'); //后台操作
				//删除备份的数据表
				if (file_exists($backup)) {
					unlink($backup); //删除它
				}
				$this->success("数据库备份成功", "__URL__/backup");
				exit;
			} else {
				$this->error("请输入正确的邮箱地址");
			}
		} else {
			$this->Record('数据库备份失败'); //后台操作
			$this->error("数据库备份失败");
		}

	}

}