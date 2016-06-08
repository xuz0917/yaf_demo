<?php
/**
 * 控制器要继承Yaf_Controller_Abstract
 * 方法名后要跟Action
 */

class Admin_AdminController extends Ctrl_Base {
	
	public function indexAction(){}
	
	public function editsaveAction(){
		if(empty($_POST['oldupass'])||empty($_POST['newupass'])||empty($_POST['renewupass'])){
			showMsg("请填写全部内容");
			exit();
		}
		if($_POST['newupass']!=$_POST['renewupass']){
			showMsg("两次密码不一致");
			exit();
		}
		$oldupass=md5(I('post.oldupass'));
		$newupass=md5(I('post.newupass'));
		$AdminModel=new AdminModel();
		if(!$AdminModel->where("uname='{$_SESSION['zw_uname']}' and upass='$oldupass'")->find()){
			showMsg("旧密码不正确");
			exit();
		}
		$data['upass']=$newupass;
		if($AdminModel->where("uname='{$_SESSION['zw_uname']}'")->save($data)){
			showMsg("数据存储成功");
		}else{
			showMsg("数据没有更改");
		}
	}

}