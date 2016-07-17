<?php
include_once('sessionfile.php');
require_once('../data/common.php');
$common=new common();
if(!$common->ispost() || !$common->post_isdomain()){
	return;
}
$action=trim($_POST["action"]);
if($action!='login'){
	return;
}

$username=trim($_POST["username"]);
$pwd=trim($_POST["pwd"]);
if($username!=$common->username){
	$common->resposne_json(array('result'=>0,'remark'=>'抱歉，您输入的登录用户名有误！'));
}
if($pwd!=$common->password){
	$common->resposne_json(array('result'=>0,'remark'=>'抱歉，您输入的登录密码有误！'));
}

//保存到SESSION中
$_SESSION["uinfo"]['username']=$username;

$common->resposne_json(array('result'=>1,'remark'=>'欢迎用户名'.$username.'登录！'));
unset($common);
?>