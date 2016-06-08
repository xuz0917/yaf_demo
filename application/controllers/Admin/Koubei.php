<?php
/**
 * 控制器要继承Yaf_Controller_Abstract
 * 方法名后要跟Action
 */

class Admin_KoubeiController extends Ctrl_Base {
	
	/**
	 * 总是登陆init
	 */
	public function init(){
		if(empty($_SESSION['zw_uname'])){
			showMsg('未登录','../admin_login');exit;
		}
	}
	
	public function indexAction(){

		$where='u.id>0';
		if(!empty($_GET['keyword'])){
			$where="u.username='{$_GET['keyword']}' or k.companyname like '%{$_GET['keyword']}%' or k.pmobile like '%{$_GET['keyword']}%'";
			$this->assign('keyword', $_GET['keyword']);
		}
		$KoubeiModel=new KoubeiModel();

		$countarr   =$KoubeiModel->query("select count(k.id) as jsum from zw_koubei as k left join zw_user as u on k.uid = u.id where $where"); // $ZhangwuModel	->join('zw_user', 'zw_user.id=uid')->where($where)->field("'count(*) as c")->select();// 查询满足要求的总记录数

		$count 		=$countarr[0]['jsum'];
		$Page       = new Ctrl_Page($count,16);// 实例化分页类 传入总记录数和每页显示的记录数
		$Page->setConfig('theme',"%totalRow% %header% %nowPage%/%totalPage% 页 %first% %prePage% %upPage% %linkPage% %downPage% %nextPage% %end%");
		$show       = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		
		$list = $KoubeiModel->query("select k.*,u.username from zw_koubei as k left join zw_user as u on k.uid = u.id where $where order by k.addtime desc,k.uid asc limit $Page->firstRow,$Page->listRows");
		
		$this->assign('content',$list);// 赋值分页输出
		$this->assign('page',$show);// 赋值分页输出

	}
	
	public function addAction(){
		$this->getView();
	}

	public function addsaveAction(){

		$KoubeiModel=new KoubeiModel();

		$contents = $_POST['contents'];
		$arr = explode("\n", $contents);

		foreach ($arr as $value) {
			if(empty($value)){continue;}
			$list = explode("|", $value);
			$data['addtime'] = $list[0];
			$data['companyname'] = $list[1];
			$data['xianlu'] = $list[2];
			$data['pmobile'] = $list[3];
			if(!empty($list[4])){
				$list[4] = trim($list[4]);
				$sql = "select id from zw_user where username = '{$list[4]}'";
				$userlist = $KoubeiModel->query($sql);
				$data['uid'] = $userlist[0]['id'];
			}
			if(!$KoubeiModel->query("select id from zw_koubei where companyname = '{$list[1]}' and xianlu = '{$list[2]}'")){
				
				if(!$list = $KoubeiModel->add($data)){
					showMsg("数据存储错误");
					exit();
				}
			}


		}
		showMsg("数据保存成功");

	}
	
	public function editAction($id){
		$KoubeiModel=new KoubeiModel();
		if($list = $KoubeiModel->where("id='$id'")->find()){
			//业务员
			$User=$KoubeiModel->query("select id, username from zw_user");
			$this->assign('userlist',$User);
			$this->getView()->assign('content', $list);
		}else{
			showMsg("没有发现数据");
			exit();
		}
	}
		
	public function editsaveAction(){
		$KoubeiModel=new KoubeiModel();
		$data['uid']=$_POST['uid'];
		$data['companyname']=$_POST['companyname'];
		$data['xianlu']=$_POST['xianlu'];
		$data['pmobile']=$_POST['pmobile'];
		$data['addtime']=$_POST['addtime'];
		if(!$list = $KoubeiModel->where("id='{$_POST['id']}'")->save($data)){
			showMsg("数据没有更改");
			exit();
		}else{
			showMsg("数据存储成功");
			exit();
		}
	}

	public function chongfuAction(){
		if($_POST['contents']){
			$contents = $_POST['contents'];
			$arr = explode("\n", $contents);
			$KoubeiModel = new KoubeiModel();

			foreach ($arr as $key=>$value) {
				if(empty($value)){continue;}
				$list = explode("|", $value);
				
				if($KoubeiModel->query("select id from zw_koubei where companyname = '{$list[1]}' and xianlu = '{$list[2]}'")){
					
					unset($arr[$key]);
				}

			}
			$contents = implode("\n", $arr);
			$this->getView()->assign('contents', $contents);
		}else{
			$this->getView();
		}
	}
	
	public function delAction($id){
		$KoubeiModel=new KoubeiModel();
		$KoubeiModel->where("id='$id'")->del();
		showMsg("删除成功",'/admin_koubei/');
		exit();
	}

	public function picAction(){
		$sql = "select u.username as name,count(k.id) as y
				from zw_user as u 
				left JOIN zw_koubei as k on u.id = k.uid
				where u.bumen='业务部' and u.id in (2,3,4,5,6,7,8,13)
				GROUP BY u.id
				ORDER BY u.id asc";
		$KoubeiModel = new KoubeiModel();
		$list = $KoubeiModel->query($sql);

		$this->assign('list',$list);
		$this->getView();
	}
	
}