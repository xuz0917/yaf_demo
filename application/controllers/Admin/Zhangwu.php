<?php
/**
 * 控制器要继承Yaf_Controller_Abstract
 * 方法名后要跟Action
 */
class Admin_ZhangwuController extends Ctrl_Base {

	public function indexAction(){
		
		$where='1=1';
		if(I('get.quankuan')){
			$_GET['quankuan'] = array_filter($_GET['quankuan']);
			if(!empty($_GET['quankuan'][0]))$_GET['quankuan'][0]--;
			if(!empty($_GET['quankuan'][1]))$_GET['quankuan'][1]--;
			if(!empty($_GET['quankuan'][2]))$_GET['quankuan'][2]--;
			$_GET['quankuan'] = implode(',', $_GET['quankuan']);
			$where .= " and zw_zhangwu.quankuan in ({$_GET['quankuan']})";
			
		}
		if(I('get.keyword')){
			$keyword = I('get.keyword');
			$where .= " and zw_zhangwu.companyname like '%$keyword%'";
		}
		if(I('get.uid')){
			$uid = I('get.uid');
			$where .= " and zw_user.id='$uid'";
		}
		//业务类型
		if(I('get.tid')){
			$tid = I('get.tid');
			$where .= " and zw_zhangwu.tid='$tid'";
		}
		//shenqing
		if(I('get.shenqing')){
			$shenqing = I('get.shenqing')-1;
			$where .= " and zw_zhangwu.shenqing='$shenqing'";
		}
		//xufei
		if(I('get.xufei')){
			$xufei = I('get.xufei')-1;
			$where .= " and zw_zhangwu.xufei='$xufei'";
		}
		//starttime,endtime
		if(I('get.starttime')){
			$starttime = I('get.starttime');
			$where .= " and zw_zhangwu.addtime>='$starttime'";
		}
		if(I('get.endtime')){
			$endtime = I('get.endtime');
			$where .= " and zw_zhangwu.addtime<='$endtime'";
		}
		
		$ZhangwuModel = new ZhangwuModel();

		$countarr	=$ZhangwuModel->query("select count(zw_zhangwu.id) as jsum,sum(zw_zhangwu.jiage) as jiagesum from zw_zhangwu left join zw_user on zw_user.id=zw_zhangwu.uid left join zw_yewutype on zw_yewutype.id=zw_zhangwu.tid where $where"); // $ZhangwuModel	->join('zw_user', 'zw_user.id=uid')->where($where)->field("'count(*) as c")->select();// 查询满足要求的总记录数
		$count 		=$countarr[0]['jsum'];
		$Page       = new Ctrl_Page($count,16);// 实例化分页类 传入总记录数和每页显示的记录数
		$Page->setConfig('theme',"%totalRow% %header% %nowPage%/%totalPage% 页 %first% %prePage% %upPage% %linkPage% %downPage% %nextPage% %end%");
		$show       = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $ZhangwuModel->join('zw_user', 'zw_user.id=uid')->join('zw_yewutype', 'zw_yewutype.id=tid')
							->where($where)
							->field("zw_user.username,zw_user.bumen,zw_user.zhiwei,zw_zhangwu.beizhu,zw_zhangwu.quankuan,zw_zhangwu.companyname,zw_zhangwu.yewuname,zw_zhangwu.addtime,zw_zhangwu.caozuo,zw_zhangwu.id,zw_zhangwu.jiage,zw_zhangwu.shenqing,zw_zhangwu.zengsong,zw_zhangwu.daka,zw_yewutype.typename")
							->limit("$Page->firstRow,$Page->listRows")
							->order('zw_zhangwu.addtime desc,zw_zhangwu.id desc')
							->select();

		//业务总量
		$this->getView()->assign('yewusum', $countarr[0]['jiagesum']);

		$this->getView()->assign('uidlist', $this->Cache->get('uidlist'));
		$this->getView()->assign('tidlist', $this->Cache->get('tidlist'));
		$this->getView()->assign('content', $list);
		$this->getView()->assign('page',$show);// 赋值分页输出
	}

	public function addAction() {
		//业务员
		$UserModel=new UserModel();
		$User=$UserModel->select();
		$this->getView()->assign('userlist',$User);
		//业务类型
		$YewutypeModel=new YewutypeModel();
		$Yewutype=$YewutypeModel->select();
		$this->getView()->assign('yewutype',$Yewutype);

	}
	public function addsaveAction() {

		if(I('uid')){
			$ZhangwuModel=new ZhangwuModel();
			$_POST['daka']=='on'?$_POST['daka']=1:$_POST['daka']=0;
			$_POST['shenqing']=='on'?$_POST['shenqing']=1:$_POST['shenqing']=0;
			$_POST['zengsong']=='on'?$_POST['zengsong']=1:$_POST['zengsong']=0;
			$_POST['tixing']=='on'?$_POST['tixing']=1:$_POST['tixing']=0;

			$data['uid']=$_POST['uid'];
			$data['companyname']=$_POST['companyname'];
			$data['yewuname']=$_POST['yewuname'];
			$data['tid']=$_POST['tid'];
			$data['jiage']=$_POST['jiage'];
			$data['daka']=$_POST['daka'];

			$data['xufei']=$_POST['xufei'];
			$data['quankuan']=$_POST['quankuan'];
			$data['shenqing']=$_POST['shenqing'];
			$data['zengsong']=$_POST['zengsong'];
			$data['tixing']=$_POST['tixing'];
			$data['youhuiquan']=$_POST['youhuiquan'];
			$data['youhui']=$_POST['youhui'];
			$data['zhenghe']=$_POST['zhenghe'];
			$data['beizhu']=$_POST['beizhu'];
			$data['addtime']=$_POST['addtime'];

			if(!$list = $ZhangwuModel->add($data)){
				showMsg("数据存储错误");
				exit();
			}else{
				$_SESSION['uid'] = $data['uid'];
				$_SESSION['addtime'] = $data['addtime'];
				showMsg("数据存储成功");
				exit();
			}
		}else{
			showMsg("参数错误");
		}

	}
	public function editAction($id) {

		//业务员
		$UserModel=new UserModel();
		$User=$UserModel->select();
		$this->getView()->assign('userlist',$User);
		//业务类型
		$YewutypeModel=new YewutypeModel();
		$Yewutype=$YewutypeModel->select();
		$this->getView()->assign('yewutype',$Yewutype);

		$ZhangwuModel=new ZhangwuModel();
		$list =  $ZhangwuModel->where("id='$id'")->select();

		if($list){
			$this->getView()->assign('id', $id);
			$this->getView()->assign('content', $list[0]);
		}else{
			//showMsg("没有发现数据");
			exit();
		}
	}
	public function editsaveAction() {

		if(!empty($_POST['id'])){

			$ZhangwuModel=new ZhangwuModel();
			$_POST['daka']=='on'?$_POST['daka']=1:$_POST['daka']=0;
			$_POST['shenqing']=='on'?$_POST['shenqing']=1:$_POST['shenqing']=0;
			$_POST['zengsong']=='on'?$_POST['zengsong']=1:$_POST['zengsong']=0;
			$_POST['tixing']=='on'?$_POST['tixing']=1:$_POST['tixing']=0;

			$data['uid']=$_POST['uid'];
			$data['companyname']=$_POST['companyname'];
			$data['yewuname']=$_POST['yewuname'];
			$data['tid']=$_POST['tid'];
			$data['jiage']=$_POST['jiage'];
			$data['daka']=$_POST['daka'];

			$data['xufei']=$_POST['xufei'];
			$data['quankuan']=$_POST['quankuan'];
			$data['shenqing']=$_POST['shenqing'];
			$data['zengsong']=$_POST['zengsong'];
			$data['tixing']=$_POST['tixing'];
			$data['youhuiquan']=$_POST['youhuiquan'];
			$data['youhui']=$_POST['youhui'];
			$data['zhenghe']=$_POST['zhenghe'];
			$data['beizhu']=$_POST['beizhu'];
			$data['addtime']=$_POST['addtime'];
			
			if(!$ZhangwuModel->where("id='{$_POST['id']}'")->save($data)){
				showMsg("数据没有更改");
				exit();
			}else{
				showMsg("数据存储成功");
				exit();
			}
		}else{
			showMsg("参数错误");
		}

	}
	public function delAction($id) {
		$ZhangwuModel=new ZhangwuModel();
		$ZhangwuModel->where("id='$id'")->del();
		showMsg("删除成功",'/admin_zhangwu/');
		exit;
	}
	/**
	 * 操作确认 共AJAX调用
	 * @param unknown $id
	 */
	public function docaozuoAction() {
		$id=$_GET['id'];
		$t=$_GET['t'];
		$data['caozuo']=1-$t;
		$ZhangwuModel=new ZhangwuModel();
		if(!$ZhangwuModel->where("id='$id'")->save($data)){
			exit(0);
		}else{
			exit(1);
		}
	}
	/**
	 * 申请 共AJAX调用
	 * @param unknown $id
	 */
	public function doshenqingAction() {
		$id=$_GET['id'];
		$t=$_GET['t'];
		$data['shenqing']=1-$t;
		$ZhangwuModel=new ZhangwuModel();
		if(!$ZhangwuModel->where("id='$id'")->save($data)){
			exit(0);
		}else{
			exit(1);
		}
	}

	public function daochuAction() {
		
		$this->getView()->assign('uidlist', $this->Cache->get('uidlist'));
	}
	/**
	 * 导出excel表格
	 */
	public function	excelAction() {

		$starttime = date('Y-m-',time()).'1';				//本月第一天
		if(date('m',time())!=12){
			$endtime = date('Y-',time()).(date('m',time())+1).'-1';	//上月第一天
		}else{
			$endtime = (date('Y-',time())+1).'1-1';	//上月第一天
		}
		if(!empty($_POST['starttime'])&&!empty($_POST['endtime'])){
			$starttime = $_POST['starttime'];
			$endtime = $_POST['endtime'];
		}
		$where = "zw_zhangwu.addtime>='$starttime' and zw_zhangwu.addtime<='$endtime'";

		if(!empty($_POST['uid'])){
			$where .= " and zw_zhangwu.uid = '{$_POST['uid']}'";
		}
		//获取业务列表
		$ZhangwuModel = new ZhangwuModel();
		$list = $ZhangwuModel	->join('zw_user', 'zw_user.id=uid')	->join('zw_yewutype', 'zw_yewutype.id=tid')
		->where($where)
		->field("zw_user.username,zw_zhangwu.*,zw_yewutype.typename")
		->order('zw_zhangwu.id asc')
		->select();

		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel_PHPExcel();

		ob_clean();
		// Set properties
		$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
		->setLastModifiedBy("Maarten Balliauw")
		->setTitle("Office 2007 XLSX Test Document")
		->setSubject("Office 2007 XLSX Test Document")
		->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
		->setKeywords("office 2007 openxml php")
		->setCategory("Test result file");

		//添加顶部
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A1', '日期')
		->setCellValue('B1', '姓名')
		->setCellValue('C1', '客户')
		->setCellValue('D1', '业务')
		->setCellValue('E1', '星级\类别')
		->setCellValue('F1', '应收价格')
		->setCellValue('G1', '实收价格')
		->setCellValue('H1', '备注');
		//添加顶部
		foreach ($list as $key=>$value){
			$beizhu = '';
			$value['xufei']==1?$beizhu=$beizhu.'续费 ':$beizhu=$beizhu.'';
			if($value['quankuan']==1)$beizhu=$beizhu.'预收 '; elseif ($value['quankuan']==2)$beizhu=$beizhu.'收齐 ';
			$value['daka']==1?$beizhu=$beizhu.'打卡 ':$beizhu=$beizhu.'';
			$beizhu = $beizhu.$value['beizhu'];
				
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.($key+2), substr($value['addtime'],0,10))
			->setCellValue('B'.($key+2), $value['username'])
			->setCellValue('C'.($key+2), $value['companyname'])
			->setCellValue('D'.($key+2), $value['typename'])
			->setCellValue('E'.($key+2), $value['yewuname'])
			->setCellValue('F'.($key+2), $value['quankuan']==0?$value['jiage']:'')
			->setCellValue('G'.($key+2), $value['jiage'])
			->setCellValue('H'.($key+2), $beizhu);
		}
		$counts=count($list)+2;
		//添加总计
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('F'.$counts, '总计')
		->setCellValue('G'.$counts, array_sum(array_column($list ,'jiage')));

		// Rename sheet
		$objPHPExcel->getActiveSheet()->setTitle('综合');

		$sheetarr = $this->getsheet($list);

		$ulist = $this->Cache->get('ulist');
		$sheetfix = 1;

		foreach ($ulist as $v) {
			if(!empty($sheetarr[$v['username']])){
				//添加顶部
				$objPHPExcel->createSheet();
				$objPHPExcel->setActiveSheetIndex($sheetfix)
				->setCellValue('A1', '日期')
				->setCellValue('B1', '姓名')
				->setCellValue('C1', '客户')
				->setCellValue('D1', '业务')
				->setCellValue('E1', '星级\类别')
				->setCellValue('F1', '应收价格')
				->setCellValue('G1', '实收价格')
				->setCellValue('H1', '备注');

				//添加顶部
			
				foreach ($sheetarr[$v['username']] as $key=>$value) {

					$beizhu = '';
					$value['xufei']==1?$beizhu=$beizhu.'续费 ':$beizhu=$beizhu.'';
					if($value['quankuan']==1)$beizhu=$beizhu.'预收 '; elseif ($value['quankuan']==2)$beizhu=$beizhu.'收齐 ';
					$value['daka']==1?$beizhu=$beizhu.'打卡 ':$beizhu=$beizhu.'';
					$beizhu = $beizhu.$value['beizhu'];
				
					$objPHPExcel->setActiveSheetIndex($sheetfix)
					->setCellValue('A'.($key+2), substr($value['addtime'],0,10))
					->setCellValue('B'.($key+2), $value['username'])
					->setCellValue('C'.($key+2), $value['companyname'])
					->setCellValue('D'.($key+2), $value['typename'])
					->setCellValue('E'.($key+2), $value['yewuname'])
					->setCellValue('F'.($key+2), $value['quankuan']==0?$value['jiage']:'')
					->setCellValue('G'.($key+2), $value['jiage'])
					->setCellValue('H'.($key+2), $beizhu);
		
				}


				$counts = count($sheetarr[$v['username']])+2;
				$objPHPExcel->setActiveSheetIndex($sheetfix)
				->setCellValue('F'.$counts, '总计')
				->setCellValue('G'.$counts, array_sum(array_column($sheetarr[$v['username']] ,'jiage')));
		
				// Rename sheet
				$objPHPExcel->getActiveSheet()->setTitle($v['username']);
				
				// Set active sheet index to the first sheet, so Excel opens this as the first sheet
				$objPHPExcel->setActiveSheetIndex($sheetfix);
				$sheetfix++;
			}
		}
		
		
		
		
		
		$title = $starttime.' '.$endtime;
		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel;charset=utf-8');
		header('Content-Disposition: attachment;filename="'.$title.'.xls"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');

	}

	public function daoruAction() {

	}

	public function daorusaveAction() {

		if($name=$this->uploadfile($_FILES['csvfile'],$_SERVER['DOCUMENT_ROOT'].'public/upload/'))
		{
			showMsg("上传成功",'daorustart?csvname='.$name);
		}
		else
		{
			showMsg("上传失败");
		}

	}

	public function daorustartAction() {

		if(isset($_GET['csvname'])){
			$file = $_SERVER['DOCUMENT_ROOT'].'public/upload/'.$_GET['csvname'];
			$row = 1;
			$str = '';
				
			$handle = fopen($file,r);
			$str = '';
			$str = $str. "<table class='table'>";
			while ($data = fgetcsv($handle, 1000, ",")){
				$num = count($data);
				$row++;
				for ($c=0; $c < $num/8; $c++) {
					if($data[$c*8]=='') continue;
					$str = $str."<tr>";
					$str = $str."	<td style=\"border:1px solid #000\">".iconv('GBK', 'UTF-8', $data[$c*8]) . "</td>";
					$str = $str."	<td style=\"border:1px solid #000\">".iconv('GBK', 'UTF-8', $data[$c*8+1]). "</td>";
					$str = $str."	<td style=\"border:1px solid #000\">".iconv('GBK', 'UTF-8', $data[$c*8+2]). "</td>";
					$str = $str."	<td style=\"border:1px solid #000\">".iconv('GBK', 'UTF-8', $data[$c*8+3]). "</td>";
					$str = $str."	<td style=\"border:1px solid #000\">".iconv('GBK', 'UTF-8', $data[$c*8+4]). "</td>";
					$str = $str."	<td style=\"border:1px solid #000\">".iconv('GBK', 'UTF-8', $data[$c*8+5]). "</td>";
					$str = $str."	<td style=\"border:1px solid #000\">".iconv('GBK', 'UTF-8', $data[$c*8+6]). "</td>";
					$str = $str."	<td style=\"border:1px solid #000\">".iconv('GBK', 'UTF-8', $data[$c*8+7]). "</td>";
					$str = $str."</tr>";
				}
			}
			$str = $str. "</table>";
			fclose($handle);
			$this->getView()->assign("csvstr",$str);
				
		}

	}

	public function daorustartsaveAction() {

		if(isset($_POST['submit'])){
				
			$ZhangwuModel = new ZhangwuModel();
			$file = $_SERVER['DOCUMENT_ROOT'].'public/upload/'.$_POST['csvname'];
				
			$handle = fopen($file,r);
			$ywnm='';
			ob_clean();
			while ($data = fgetcsv($handle, 1000, ",")){
				$num = count($data);
				for ($c=0; $c < $num/8; $c++) {
					
					if($data[$c*8]=='') continue;

					$datazw = array();
					$datazw['addtime']		= str_replace('/', '-', iconv('GBK', 'UTF-8', $data[$c*8]));
					$datazw['uid']			= $this->getuid(iconv('GBK', 'UTF-8', $data[$c*8+1]));
					$datazw['companyname']	= iconv('GBK', 'UTF-8', $data[$c*8+2]);
					$datazw['tid']			= $this->gettid(iconv('GBK', 'UTF-8', $data[$c*8+3]));
					
					$datazw['yewuname']		= iconv('GBK', 'UTF-8', $data[$c*8+4]);
					$datazw['jiage']		= iconv('GBK', 'UTF-8', $data[$c*8+6]);
					$datazw['beizhu']		= iconv('GBK', 'UTF-8', $data[$c*8+7]);

					$datazw['xufei']		= $this->getxufei(iconv('GBK', 'UTF-8', $data[$c*8+7]));
					$datazw['quankuan']		= $this->getquankuan(iconv('GBK', 'UTF-8', $data[$c*8+7]));
					$datazw['daka']			= $this->getdaka(iconv('GBK', 'UTF-8', $data[$c*8+7]));
					$datazw['zhenghe']		= $this->getzhenghe(iconv('GBK', 'UTF-8', $data[$c*8+7]));
					$datazw['zengsong']		= $this->getzengsong(iconv('GBK', 'UTF-8', $data[$c*8+7]));
					$datazw['youhuiquan']	= $this->getyouhuiquan(iconv('GBK', 'UTF-8', $data[$c*8+7]));
					$datazw['shenqing']		= $this->getshenqing(iconv('GBK', 'UTF-8', $data[$c*8+7]));
					$datazw['caozuo']		= 1;
					
					$addid = $ZhangwuModel->add($datazw);

					if (!$addid) {
						showMsg("插入错误");
					}
					
				}
			}
			fclose($handle);
			showMsg($ZhangwuModel->getLastSql(),'/admin_zhangwu/');

		}

	}
	/**
	 * 转换为业务员ID
	 * @param unknown $param
	 * @return number
	 */
	function getuid($param) {

		$id = 0;
		switch ($param) {
			case '孟翔':	$id = 1;break;
			case '钟兴超':$id = 2;break;
			case '赵广娇':$id = 3;break;
			case '刘钰':	$id = 4;break;
			case '孙子川':$id = 5;break;
			case '杨涛':	$id = 6;break;
			case '孙慎强':$id = 7;break;
			case '冯文启':$id = 8;break;
			case '吕长晓':$id = 10;break;
				
			default:break;
		}
		return $id;
	}
	/**
	 * 转化为产品类型ID
	 * @param unknown $param
	 * @return number
	 */
	function gettid($param) {

		$id = 0;
		switch ($param) {
			case '网站':	$id = 1;break;
			case '置顶':	$id = 2;break;
			case '图片':	$id = 3;break;
			case '加线路':	$id = 4;break;
			case '广告位':$id = 5;break;
			case '第三方':	$id = 6;break;
				
			default:break;
		}
		return $id;
	}

	/**
	 * 是否续费
	 * 0：新做；1：续费
	 * @param unknown $param
	 * @return number
	 */
	function getxufei($param) {
		$str = 0;
		if(strstr($param,'续费'))$str=1;
		return $str;
	}
	/**
	 * 是否全款
	 * 0：全款；1：预收；2：收齐
	 * @param unknown $param
	 * @return Ambigous <string, number>
	 */
	function getquankuan($param) {
		$str = 0;
		if(strstr($param,'预收'))$str = 1;
		if(strstr($param,'收齐'))$str = 2;
		return $str;
	}
	/**
	 * 是否打卡
	 * 0：现金；1：打卡
	 * @param unknown $param
	 * @return number
	 */
	function getdaka($param) {
		$str = 0;
		if(strstr($param,'打卡'))$str = 1;
		return $str;
	}
	/**
	 * 是否整合
	 * 1：整合
	 * @param unknown $param
	 * @return number
	 */
	function getzhenghe($param) {
		$str = 0;
		if(strstr($param,'整合'))$str = 1;
		return $str;
	}
	/**
	 * 是否赠送
	 * 1：赠送
	 * @param unknown $param
	 * @return number
	 */
	function getzengsong($param) {
		$str = 0;
		if(strstr($param,'送'))$str = 1;
		return $str;
	}
	/**
	 * 是否优惠券
	 * 1：使用优惠券
	 * @param unknown $param
	 * @return number
	 */
	function getyouhuiquan($param) {
		$str = 0;
		if(strstr($param,'券'))$str = 1;
		return $str;
	}
	/**
	 * 是否申请
	 * 1：申请
	 * @param unknown $param
	 * @return number
	 */
	function getshenqing($param) {
		$str = 0;
		if(strstr($param,'申请'))$str = 1;
		return $str;
	}
	/**
	 * 获取文件类型函数
	 *
	 * $filename 文件全名
	 */
	function uploadtype($filename){
		return substr(strrchr($filename,'.'),1);
	}

	/**
	 * 上传文件方法
	 *
	 * $file 上传的文件 .ex $_FILES['userfile']
	 * $uploaddir  保存路径
	 * $type 可上传文件类型
	 */
	function uploadfile($file,$uploaddir,$type=array("csv")){
		if(!file_exists($uploaddir)){
			mkdir($uploaddir,0777);
		}
		if(!in_array(strtolower($this->uploadtype($file['name'])),$type)){
			$text=implode('.',$type);
			echo false;
		}else{
			$filename=explode(".",$file['name']);//把上传的文件名以“.”为准做一个数组。
			$time=date("Ymd");//取当前上传的时间
			//$filename[0]=$time.rand(100,999);//取文件名替换
			$name=implode(".",$filename); //上传后的文件名
			$uploadfile=$uploaddir.$name;//上传后的文件名地址
		}
		if(move_uploaded_file($file['tmp_name'],$uploadfile)){
			return $name;
		}else{
			return false;
		}
	}
	
	function getsheet($arrs) {
		
		$rearr = array();
		$UserModel = new UserModel();
		$ulist = $UserModel->select();
		
		foreach ($arrs as $key=>$value){
			foreach ($ulist as $uvalue){
				if($value['username']==$uvalue['username'])$rearr[$uvalue['username']][] = $value;
			}
		}
		
		$this->Cache->set('ulist', $ulist);
		return $rearr;
	}
}