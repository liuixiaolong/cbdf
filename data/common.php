<?php

//公用类
class common{
	
	//数据表名前缀
    public $prefix='t_';
    //登录用户名
    public $username='admin';
    //登录密码
    public $password='admin123';
	
	/**
	 * 是否为post提交表单
	 * @return boolean
	 */
    public function ispost(){
		//获得提交方式
		$requestmothed = strtolower(!empty($_SERVER['REQUEST_METHOD'])?$_SERVER['REQUEST_METHOD']:'get');
		if($requestmothed=='get'){
			return false;
		}else{
			return true;
		}
	}
	
	/**
	 * 判断数据提交是否来自同一域名
	 * @return boolean
	 */
	public function post_isdomain(){
		//获得当前服务器
		$servername = strtolower(trim($_SERVER['SERVER_NAME']));
		//上一个URL
		$url_from = '';
		if(isset($_SERVER['HTTP_REFERER'])){
			strtolower(trim($_SERVER['HTTP_REFERER']));
		}		
	    if ($servername != substr($url_from, 0, strlen($servername))) {
	        return true;
	    }else{
	    	return false;	    	
	    }
	}
	
	/**
	 * 检测提交的值是不是含有SQL注射的字符，防止注射，保护服务器安全
	 * @param string $str
	 * @return boolean
	 */
	public function inject_check($str){
		$iscontain=false;
		// 定义不允许提交的SQL命令及关键字
		$words='add,create,delete,drop,from,insert,update,into,union,truncate,select,execute,count,master,declare';
		//获得数组
		$arr=array_filter(explode(',',$words));
		//大小写转换
		$sql_str=strtolower($str);
		foreach($arr as $value){
			if(strstr($sql_str,$value)){
				$iscontain=true;
				break;
			}
		}
		return $iscontain;
	}
	
	/**
	 * 输出utf-8的json数据
	 * @param array $json
	 */
	public function resposne_json($json = array()){
		if(!is_array($json)){return;}
		header('Content-type: application/json;charset=utf-8');
		die(json_encode($json));
		return;
	}
	
	/**
	 * 返回登录密码
	 * @param string $salt
	 * @param string $password
	 */
	public function md5_passwd($salt,$password){
		return md5(md5($password).$salt);
	}
	
	/**
	 * 获得客户端ip地址
	 * @return string $cip
	 */
	public function get_clientip(){
		if(!empty($_SERVER["HTTP_CLIENT_IP"])){
			$cip = $_SERVER["HTTP_CLIENT_IP"];
		}
		elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
			$cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		}
		elseif(!empty($_SERVER["REMOTE_ADDR"])){
			$cip = $_SERVER["REMOTE_ADDR"];
		}
		else{
			$cip = "";
		}
		return $cip;
	}
	
	/**
	 * 获得登录密码盐值
	 * @param int $len 长度
	 * @return string 
	 */
	public function get_password_salt($len){
		// 密码字符集，可任意添加你需要的字符
		$chars = 'abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ123456789';
		$myrnd = '';
		for ( $i = 0; $i < $len; $i++ ){
			$myrnd .= $chars[ mt_rand(0, strlen($chars) - 1)];
		}
		return $myrnd;
	}
}


?>
