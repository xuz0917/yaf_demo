<?php
/**
 * 控制器要继承Yaf_Controller_Abstract
 * 方法名后要跟Action
 */
class Admin_LoginController extends Yaf_Controller_Abstract {

	/*
	public function init(){
		var_dump($_SESSION);
	}
	*/
	public function indexAction(){
		
		if(!empty($_SESSION['zw_uname'])){
			showMsg('已经登陆',DOMAIN.'/admin_index');
			exit;
		}
		$this->getView()->assign('content', $this->getRequest()->getControllerName());
	}

	public function CheckAction(){
		//验证登陆
		$uname	= I('post.uname');
    	$upass	= md5(I('post.upass'));
    	$verify	= md5(I('post.Auth_code'));
    	 
    	//检验是否为空
    	if(empty($uname)) {
    		showMsg('帐号错误！');
    	}elseif (empty($upass)){
    		showMsg('密码必须！');
    	}elseif (empty($verify)){
    		showMsg('验证码必须！');
    	}

		if($_SESSION['verify'] != $verify) {
			showMsg('验证码错误！');
		}
		
		$where = array();
		$where['uname'] = $uname;

		$tMB = new AdminModel();
		$finduser = $tMB->where("uname='$uname'")->find();
		
		if ($finduser) {
			//记录成功登陆
			if($finduser['upass']===$upass){
				$_SESSION['zw_uname']=$uname;
				$_SESSION['zw_qx']=$finduser['qx'];
				showMsg('登陆成功',DOMAIN.'/admin_index');
			}else{
				//记录失败登陆
				showMsg('用户名或密码错误！');
			}
		}else{
			//记录失败登陆
			showMsg('用户名或密码错误！');
		}
	}
	
	public function logoutAction() {
		
		session_destroy();
		$Cache = new Cache_Memcache;
		$Cache->clear();
		showMsg("退出成功",DOMAIN);
		
	}
	
	public function CaptchaAction() {
		$Image = new Verify_Image();
		$Image::buildImageVerify(4,1,'png',60,32);
	}
	
	public function fastestAction() {
		$this->getView()->assign("content",'success');
	}

}