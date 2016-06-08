<?php 
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

/**
 * 数据库方式Session驱动
 *    CREATE TABLE think_session (
 *      session_id varchar(255) NOT NULL,
 *      session_expire int(11) NOT NULL,
 *      session_data blob,
 *      UNIQUE KEY `session_id` (`session_id`)
 *    );
 */
class Session_Db {

	/**
	 * Session有效时间
	 */
	protected $lifeTime		= '3600';

	/**
	 * session保存的数据库名
	 */
	protected $sessionTable	= '';

	/**
	 * db链接数组
	 */
	protected $tDB  = '';

	/**
	 * 数据库句柄
	 */
	protected $hander  = array();
	 
	// PDO连接参数
	protected $options = array(
		pDO::ATTR_CASE				=>  PDO::CASE_LOWER,
		pDO::ATTR_ERRMODE		    =>  PDO::ERRMODE_EXCEPTION,
		pDO::ATTR_ORACLE_NULLS      =>  PDO::NULL_NATURAL,
		pDO::ATTR_STRINGIFY_FETCHES =>  false,
	);
	 
	public function __construct(){
		$this->tDB = Yaf_Registry::get("config")->db->session->toArray();
	}
	 
	/**
	 * 打开Session
	 * @access public
	 * @param string $savePath
	 * @param mixed $sessName
	 */
	public function open($savePath, $sessName) {
		
		$this->sessionTable  =   $this->tDB['fix']."session";
		
		//从数据库链接
		$hander = new PDO( $this->tDB['dsn'], $this->tDB['username'], $this->tDB['password'], array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES '.$this->tDB['charset']));
		if(!$hander)
			return false;
		$this->hander = $hander;
		
		return true;
	}

	/**
	 * 关闭Session
	 * @access public
	 */
	public function close() {
		if(is_array($this->hander)){
			$this->gc($this->lifeTime);
			return (($this->hander[0] = null) && ($this->hander[1] = null));
		}
		$this->gc($this->lifeTime);
		return ($this->hander = null);
	}

	/**
	 * 读取Session
	 * @access public
	 * @param string $sessID
	 */
	public function read($sessID) {
		$hander 	= 	is_array($this->hander)?$this->hander[1]:$this->hander;
		$res 	= 	$hander->prepare('SELECT session_data AS data FROM '.$this->sessionTable." WHERE session_id = '$sessID'   AND session_expire >".time());
		$res->execute();
		if ($res){
			$result =$res->fetch(PDO::FETCH_ASSOC);
			return $result['data'];
		}
		return "";
	}

	/**
	 * 写入Session
	 * @access public
	 * @param string $sessID
	 * @param String $sessData
	 */
	public function write($sessID,$sessData) {
		$hander 		= 	is_array($this->hander)?$this->hander[0]:$this->hander;
		$expire 		= 	time() + $this->lifeTime;
		$sessData 	= 	addslashes($sessData);
		$res = $hander->exec('REPLACE INTO  '.$this->sessionTable." (  session_id, session_expire, session_data)  VALUES( '$sessID', '$expire',  '$sessData')");
		if ($res)
			return true;
		return false;
	}

	/**
	 * 删除Session
	 * @access public
	 * @param string $sessID
	 */
	public function destroy($sessID) {
		$hander 	= 	is_array($this->hander)?$this->hander[0]:$this->hander;
		$res = $hander->exec('DELETE FROM '.$this->sessionTable." WHERE session_id = '$sessID'");
		if ($res)
			return true;
		return false;
	}

	/**
	 * Session 垃圾回收
	 * @access public
	 * @param string $sessMaxLifeTime
	 */
	public function gc($sessMaxLifeTime) {
		$hander = 	is_array($this->hander)?$this->hander[0]:$this->hander;
		return $hander->exec('DELETE FROM '.$this->sessionTable.' WHERE session_expire < '.time());
	}

}