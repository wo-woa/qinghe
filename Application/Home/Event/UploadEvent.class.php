<?php
namespace Home\Event;
use Think\Controller;

class UploadEvent extends Controller {
	// $path $exts $size
	public function upload_lay($conf = array()) {
		$arr = array(
			'path' => 'Uploads/',
			'exts' => array('jpg', 'jpeg', 'gif', 'png'),
			'size' => 20097152,
		);
		$conf = array_merge($arr, $conf);
		$upload = new \Think\Upload();
		$upload->maxSize = $conf['size']; //上传文件大小
		$upload->exts = $conf['exts']; //上传文件类型
		$upload->rootPath = $conf['path']; //上传根目录
		// $upload->saveName = 'com_create_guid';
		$upload->savePath = 'kimg/'; //上传文件（子）目录
		// 上传文件
		// $info = $upload->upload();
		$info = $upload->uploadOne($_FILES['file']); //单文件上传
		if (!$info) {
			// 上传失败
			return ['status' => 2, 'res' => $upload->getError()];
		} else {
			$hasimg = $conf['path'] . $info['savepath'] . $info['savename'];
			return ['status' => 1, 'res' => $hasimg];
			// foreach ($info as $file) {
			//     $hasimg = $conf['path'].$file['savepath'] . $file['savename'];
			//     return ['status' => 1,'res' => $hasimg ];
			// }
		}
	}
	public function upload($conf = array()) {
		$arr = array(
			'path' => 'Uploads/',
			'exts' => array('jpg', 'jpeg', 'gif', 'png'),
			'size' => 20097152,
		);
		$conf = array_merge($arr, $conf);
		$upload = new \Think\Upload();
		$upload->maxSize = $conf['size']; //上传文件大小
		$upload->exts = $conf['exts']; //上传文件类型
		$upload->rootPath = $conf['path']; //上传根目录
		// $upload->saveName = 'com_create_guid';
		$upload->savePath = 'kimg/'; //上传文件（子）目录
		// 上传文件
		// $info = $upload->upload();
		$info = $upload->uploadOne($_FILES['kimg']); //单文件上传
		if (!$info) {
			// 上传失败
			return ['status' => 2, 'res' => $upload->getError()];
		} else {
			$hasimg = $conf['path'] . $info['savepath'] . $info['savename'];
			return ['status' => 1, 'res' => $hasimg];
			// foreach ($info as $file) {
			//     $hasimg = $conf['path'].$file['savepath'] . $file['savename'];
			//     return ['status' => 1,'res' => $hasimg ];
			// }
		}
	}
	public function buildThumb($path, $width = 150, $height = 150) {
		$img = new \Think\Image();
		$img->open($path);
		$path = explode('.', $path);
		$img->thumb($width, $height, \Think\Image::IMAGE_THUMB_FILLED)->save($path[0] . '_thumb.' . $path[1]);
	}
}