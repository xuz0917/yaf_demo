<?php
/**
 * 控制器要继承Yaf_Controller_Abstract
 * 方法名后要跟Action
 */

class Admin_YewutypeController extends Ctrl_Base {
	
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
			$where="typename='{$_POST['keyword']}'";
			$this->assign('keyword', $_POST['keyword']);
		}
		$YewutypeModel=new YewutypeModel();
		$list = $YewutypeModel->where($where)->select();
		$this->getView()->assign('content',$list);
	}
	
	
	public function addAction(){
		$this->getView();
	}

	public function addsaveAction(){
		if(empty($_POST['typename'])||empty($_POST['jiage'])){
			showMsg("请填写全部内容");
			exit();
		}
		$YewutypeModel=new YewutypeModel();
		$data['typename']=$_POST['typename'];
		$data['jiage']=$_POST['jiage'];
		if(!$list = $YewutypeModel->add($data)){
			showMsg("数据存储错误");
			exit();
		}else{
			showMsg("数据存储成功");
			exit();
		}
	}
	
	public function editAction($id){
		$YewutypeModel=new YewutypeModel();
		if($list = $YewutypeModel->where("id='$id'")->find()){
			$this->getView()->assign('content', $list);
		}else{
			showMsg("没有发现数据");
			exit();
		}
	}
		
	public function editsaveAction(){
		$YewutypeModel=new YewutypeModel();
		$data['typename']=$_POST['typename'];
		$data['jiage']=$_POST['jiage'];
		if(!$list = $YewutypeModel->where("id='{$_POST['id']}'")->save($data)){
			showMsg("数据没有更改");
			exit();
		}else{
			showMsg("数据存储成功");
			exit();
		}
	}
	
	public function delAction($id){
		$YewutypeModel=new YewutypeModel();
		$YewutypeModel->where("id='$id'")->del();
		showMsg("删除成功",'/admin_yewutype/');
		exit();
	}
	
}