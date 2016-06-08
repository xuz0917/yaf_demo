<?php
	/**
	 * 提示信息
	 * @param string $pMsg
	 * @param bool $pUrl
	 */
	function showMsg($pMsg, $pUrl = false) {
		header('Content-Type:text/html; charset=utf-8');
		is_array($pMsg) && $pMsg = join('\n', $pMsg);
		echo '<script type="text/javascript">';
		if($pMsg) echo "alert('$pMsg');";
		if($pUrl) echo "self.location='{$pUrl}'";
		elseif(empty($_SERVER['HTTP_REFERER'])) echo 'window.history.back(-1);';
		else echo "self.location='{$_SERVER['HTTP_REFERER']}';";
		exit('</script>');
	}

	/**
	 * 获取输入参数 支持过滤和默认值
	 * 使用方法:
	 * <code>
	 * I('id',0); 获取id参数 自动判断get或者post
	 * I('post.name','','htmlspecialchars'); 获取$_POST['name']
	 * I('get.'); 获取$_GET
	 * </code>
	 * @param string $name 变量的名称 支持指定类型
	 * @param mixed $default 不存在的时候默认值
	 * @param mixed $filter 参数过滤方法
	 * @return mixed
	 */
	function I($name,$default='',$filter=null) {
		
		$filter_func = Yaf_Registry::get("config")->str->filter;
		
		if(strpos($name,'.')) {
			// 指定参数来源
			list($method,$name) =   explode('.',$name,2);
		}else{
			// 默认为自动判断
			$method =   'param';
		}
		switch(strtolower($method)) {
			case 'get'     :   $input =& $_GET;		break;
			case 'post'    :   $input =& $_POST;	break;
			case 'put'     :   parse_str(file_get_contents('php://input'), $input);break;
			case 'param'   :
				switch($_SERVER['REQUEST_METHOD']) {
					case 'POST':
						$input  =  $_POST;
						break;
					case 'PUT':
						parse_str(file_get_contents('php://input'), $input);
						break;
					default:
						$input  =  $_GET;
				}
				break;
			case 'request'	:   $input =& $_REQUEST;   break;
			case 'session'	:   $input =& $_SESSION;   break;
			case 'cookie' 	:   $input =& $_COOKIE;    break;
			case 'server' 	:   $input =& $_SERVER;    break;
			case 'globals'	:   $input =& $GLOBALS;    break;
			default:
				return NULL;
		}
		
		// 全局过滤
		if($filter_func) {
			$_filters    =   explode(',',$filter_func);
			foreach($_filters as $_filter){
				// 全局参数过滤
				//var_dump($input);
				//array_walk_recursive($input,$_filter);	//对数组中的每个成员递归地应用用户函数 ,过滤【原始地址】
				$input = array_map ( $_filter ,  $input );
			}
		}
		
		if(empty($name)) { // 获取全部变量
			$data       =   $input;
			$filters    =   isset($filter_func)?$filter_func:'htmlspecialchars';
			if($filters) {
				$filters    =   explode(',',$filters);
				foreach($filters as $filter){
					$data   =   array_map($filter,$data); // 参数过滤	?是不是可以用 array_walk
				}
			}
		}elseif(isset($input[$name])) { // 取值操作
			$data       =	$input[$name];
			$filters    =   isset($filter_func)?$filter_func:'htmlspecialchars';
			if($filters) {
				$filters    =   explode(',',$filters);
				foreach($filters as $filter){
					if(function_exists($filter)) {
						//is_array($data)?array_walk($data,$filter):$filter($data); // 参数过滤
						$data   =   is_array($data)?array_map($filter,$data):$filter($data); // 参数过滤
					}else{
						$data   =   filter_var($data,is_int($filter)?$filter:filter_id($filter));
						if(false === $data) {
							return	isset($default)?$default:NULL;
						}
					}
				}
			}
		}else{ // 变量默认值
			$data       =	 isset($default)?$default:NULL;
		}
		return $data;
	}
	
	/**
	 * 获取客户端IP地址
	 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
	 * @param boolean $adv 是否进行高级模式获取（有可能被伪装）
	 * @return mixed
	 */
	function get_client_ip($type = 0,$adv=false) {
		$type       =  $type ? 1 : 0;
		static $ip  =   NULL;
		if ($ip !== NULL) return $ip[$type];
		if($adv){
			if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
				$arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
				$pos    =   array_search('unknown',$arr);
				if(false !== $pos) unset($arr[$pos]);
				$ip     =   trim($arr[0]);
			}elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
				$ip     =   $_SERVER['HTTP_CLIENT_IP'];
			}elseif (isset($_SERVER['REMOTE_ADDR'])) {
				$ip     =   $_SERVER['REMOTE_ADDR'];
			}
		}elseif (isset($_SERVER['REMOTE_ADDR'])) {
			$ip     =   $_SERVER['REMOTE_ADDR'];
		}
		// IP地址合法验证
		$long = sprintf("%u",ip2long($ip));
		$ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
		return $ip[$type];
	}