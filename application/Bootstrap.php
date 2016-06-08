<?php

/**
 * 所有在Bootstrap类中, 以_init开头的方法, 都会被Yaf调用,
 * 这些方法, 都接受一个参数:Yaf_Dispatcher $dispatcher
 * 调用的次序, 和申明的次序相同
 */
class Bootstrap extends Yaf_Bootstrap_Abstract {

	public function _initBootstrap(){

		Yaf_Registry::set('config', Yaf_Application::app()->getConfig());
		define("PATH_APP", Yaf_Registry::get("config")->application->directory);
		define("PATH_TPL", PATH_APP . 'views');		//模版目录
		define("PATH_LIB", PATH_APP . 'library');	//第三方文件
		define("FILE_TEMP", PATH_APP . 'cache/file');//文件缓存位置
		define("DOMAIN", 'http://'.Yaf_Registry::get("config")->application->domain);//路径

	}
	
	public function _initsessstart(){
		$sessiontype = Yaf_Registry::get("config")->session->type;
		if($sessiontype!='default'&&!empty($sessiontype)){		
			$class = 'Session_'.$sessiontype;	
			$hander	=	new $class();
			session_set_save_handler(
				array(&$hander,"open"),
				array(&$hander,"close"),
				array(&$hander,"read"),
				array(&$hander,"write"),
				array(&$hander,"destroy"),
				array(&$hander,"gc")
			);
		}
		session_start();
	}
	/**
	 * 载入公共函数
	 * @return [type] [description]
	 */
	public function _initcomfunc()
	{
		Yaf_Loader::import(PATH_LIB.'/functions.php');
	}
    	
}
    