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
 * 文件类型缓存类
 */
class Cache_File {

	private $options = array();
	private $f = array();
    /**
     * 架构函数
     * @access public
     */
    public function __construct($options=array()) {
        if(!empty($options)) {
            $this->options =  $options;
        }
        $this->options['temp']      =   !empty($options['temp'])?   $options['temp']    :   FILE_TEMP;
        $this->options['prefix']    =   isset($options['prefix'])?  $options['prefix']  :   '';
        $this->options['expire']    =   isset($options['expire'])?  $options['expire']  :   3600;
        $this->options['length']    =   isset($options['length'])?  $options['length']  :   0;
        if(substr($this->options['temp'], -1) != '/')    $this->options['temp'] .= '/';
        $this->init();
    }

    /**
     * 初始化检查
     * @access private
     * @return boolean
     */
    private function init() {
        // 创建应用缓存目录
        if (!is_dir($this->options['temp'])) {
            mkdir($this->options['temp']);
        }
    }

    /**
     * 取得变量的存储文件名
     * @access private
     * @param string $name 缓存变量名
     * @return string
     */
    private function filename($name) {
        $name	=	md5($name);
        $filename	=	$this->options['prefix'].$name.'.php';
        return $this->options['temp'].$filename;
    }

    /**
     * 读取缓存
     * @access public
     * @param string $name 缓存变量名
     * @return mixed
     */
    public function get($name) {
        $filename   =   $this->filename($name);
        if (!is_file($filename)) return false;
        
        $content    =   file_get_contents($filename);
        if( false !== $content) {
            $expire  =  (int)substr($content,8, 12);
            
            //int filemtime (string $filename) 本函数返回文件中的数据块上次被写入的时间，也就是说，文件的内容上次被修改的时间。
            
            if($expire != 0 && time() > filemtime($filename) + $expire) {
            	//缓存过期删除缓存文件
            	unlink($filename);
            	return false;
            }
            
            $content   =  unserialize(substr($content,20, -3));
            return $content;
        }else {
            return false;
        }
    }

    /**
     * 写入缓存
     * @access public
     * @param string $name 缓存变量名
     * @param mixed $value  存储数据
     * @param int $expire  有效时间 0为永久
     * @return boolean
     */
    public function set($name,$value,$expire=null) {
    	
        if(is_null($expire)) {
            $expire	=	$this->options['expire'];
        }
        $filename = $this->filename($name);
        $data = serialize($value);	//serialize() 包含了表示 value 的字节流，可以存储于任何地方
                
        $data = "<?php\n//".sprintf('%012d',$expire).$data."\n?>";
        $result = file_put_contents($filename,$data);
        if($result) {
            clearstatcache();	//函数来清除被PHP缓存的该文件信息
            return true;
        }else {
        	return false;
        }
        
    }

    /**
     * 删除缓存
     * @access public
     * @param string $name 缓存变量名
     * @return boolean
     */
    public function rm($name) {
        return unlink($this->filename($name));
    }

    /**
     * 清除缓存
     * @access public
     * @param string $name 缓存变量名
     * @return boolean
     */
    public function clear() {
        $path   =  $this->options['temp'];
        $files  =   scandir($path);
        if($files){
            foreach($files as $file){
                if ($file != '.' && $file != '..' && is_dir($path.$file) ){
                    array_map( 'unlink', glob( $path.$file.'/*.*' ) );
                }elseif(is_file($path.$file)){
                    unlink( $path . $file );
                }
            }
            return true;
        }
        return false;
    }
}