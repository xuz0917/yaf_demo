<?php
/**
 * 控制器要继承Yaf_Controller_Abstract
 * 方法名后要跟Action
 */

class Admin_IndexController extends Ctrl_Base {

	public function indexAction(){
		$tMB = new MenuModel();
		$list = $tMB->order('id asc')->select();
		$this->getView()->assign('menus', $list);
		$this->getView()->assign('content', $this->getRequest()->getControllerName());
	}
	
	public function jiluAction() {
		$starttime=date("Y-m-d",time());
		$starttime=$starttime.' 00:00:00';
		$JifenModel=new JifenModel();
		$sql="select u.username,u.bumen,u.zhiwei,j.fenshu,j.shiyou,j.addtime from zw_jifen as j left join zw_user as u on j.uid=u.id where j.tid=0 and j.addtime>'$starttime' order by  j.id asc";
		$Jifen=$JifenModel->query($sql);
		$this->getView()->assign("content",$Jifen);
	}

}