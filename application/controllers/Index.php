<?php
/**
 * 控制器要继承Yaf_Controller_Abstract
 * 方法名后要跟Action
 */
class IndexController extends Yaf_Controller_Abstract {

	public function indexAction(){

		$_GET['a'] = "fdasfdsa<script>";
		//$this->assign("content",$_GET['a']);
		$this->getView()->assign("content",I('get.a'));

	}

	public function testAction(){
		echo 1;
	}

}