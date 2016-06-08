<?php
/**
 * 控制器 基础类
 * @author 张洋 2050479@qq.com
 */
class Ctrl_Base extends Yaf_Controller_Abstract {

	public $Cache;

	public function init(){
		$this->Cache = new Cache_Memcache();
		if(empty($_SESSION['zw_uname'])){
			showMsg('未登录','../admin_login');exit;
		}
		//构建缓存
		$this->cache_tid();
		$this->cache_uid();
	}

	/**
	 * 缓存层——业务类型
	 * @param unknown $param
	 */
	public function cache_tid() {
	
		if (!$this->Cache->get('tidlist')) {
			$YewutypeModel = new YewutypeModel();
			$list = $YewutypeModel->select();
			$this->Cache->set('tidlist', $list);
		}
	
	}

	/**
	 * 缓存层——业务员
	 * @param unknown $param
	 */
	public function cache_uid() {
		
		if (!$this->Cache->get('uidlist')) {
			$UserModel = new UserModel();
			$list = $UserModel->select();
			$this->Cache->set('uidlist', $list);
		}
	
	}

}
