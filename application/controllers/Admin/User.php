<?php
/**
 * 控制器要继承Yaf_Controller_Abstract
 * 方法名后要跟Action
 */

class Admin_UserController extends Ctrl_Base {
	
	/**
	 * 总是登陆init
	 */
	public function init(){
		if(empty($_SESSION['zw_uname'])){
			showMsg('未登录','../admin_login');exit;
		}
	}
	
	public function indexAction(){
		$where='1=1';
		if(!empty($_POST['keyword'])){
			$where="username='{$_POST['keyword']}'";
			$this->assign('keyword', $_POST['keyword']);
		}
		$UserModel=new UserModel();
		$list = $UserModel->where($where)->select();
		$this->getView()->assign('content',$list);
	}
	
	
	public function addAction(){
		$this->getView();
	}

	public function addsaveAction(){
		if(empty($_POST['username'])||empty($_POST['bumen'])||empty($_POST['zhiwei'])){
			showMsg("请填写全部内容");
			exit();
		}
		$UserModel=new UserModel();
		$data['username']=$_POST['username'];
		$data['bumen']=$_POST['bumen'];
		$data['zhiwei']=$_POST['zhiwei'];
		if(!$list = $UserModel->add($data)){
			showMsg("数据存储错误");
			exit();
		}else{
			showMsg("数据存储成功");
			exit();
		}
	}
	
	public function editAction($id){
		$UserModel=new UserModel();
		if($list = $UserModel->where("id='$id'")->find()){
			$this->getView()->assign('content', $list);
		}else{
			showMsg("没有发现数据");
			exit();
		}
	}
		
	public function editsaveAction(){
		$UserModel=new UserModel();
		$data['username']=$_POST['username'];
		$data['bumen']=$_POST['bumen'];
		$data['zhiwei']=$_POST['zhiwei'];
		if(!$list = $UserModel->where("id='{$_POST['id']}'")->save($data)){
			showMsg("数据没有更改");
			exit();
		}else{
			showMsg("数据存储成功");
			exit();
		}
	}
	
	public function delAction($id){
		$UserModel=new UserModel();
		$UserModel->where("id='$id'")->del();
		showMsg("删除成功",'/admin_user/');
		exit();
	}
	
}