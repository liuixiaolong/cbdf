<?php
header("Content-Type:text/html;charset=utf-8");

ini_set('session.save_path',dirname(__FILE__).'/manage/savetempsessionfile');
session_start();
	
date_default_timezone_set('PRC');
//get data
require_once('data/common.php');
$common=new common();
//判断数据提交方式
$ispost=$common->ispost();
$isdomain=$common->post_isdomain();
$action='';
if($ispost && $isdomain){
	//数据处理
	if(!isset($_POST['action'])){return;}
	$action=trim($_POST['action']);
}
require_once("data/db.php");
$DB = new DB();

switch ($action){
	case 'getUid':
	    //get guid
	    $uid=getuid();
	    if($uid==''){
	    	$result=mkuid($common,$DB);
	    	if($result['result']==1){
	    		$common->resposne_json(array('uid'=>$result['uid']));
	    	}else{
	    		$common->resposne_json(array('code'=>40001,'message'=>$result['remark']));
	    	}
	    }else{
	    	$common->resposne_json(array('uid'=>$uid));
	    }	    
	    break;
	case 'sendLikes':
	    //照片点赞
	    if(isset($_POST['imageID']) && isset($_POST['likes']) && isset($_POST['uid'])){
	    	$id=trim($_POST['imageID']);
	    	$likes=trim($_POST['likes']);
	    	$uid=trim($_POST['uid']);
	    	
	    	if($id=='' || $likes=='' || $uid==''){
	    		$common->resposne_json(array('result'=>0,'remark'=>'抱歉，传递参数有误！'));
	    	}
	    	
	    	if((int)$id <= 0 || (int)$likes<=0){
	    		$common->resposne_json(array('result'=>0,'remark'=>'抱歉，传递参数有误！'));
	    	}
	    	
	    	$table=$common->prefix.'useruid';
	    	$sql='select id from '.$table.' where uid=\''.$uid.'\' and utype=\'pc\' ';
		    $data=$DB->get_one($sql);
		    if(empty($data)){
		    	$common->resposne_json(array('result'=>0,'remark'=>'抱歉，传递参数有误！'));
		    }
		    
		    $qty=1;$isadd=0;
		    //判断当前抽过几次奖
		    $table=$common->prefix.'userlinks';
		    $sql='select qty from '.$table.' where guid=\''.$uid.'\' and datediff(lastlikesdate,\''.date('Y-m-d H:i:s',time()).'\')=0 ';
		    $data=$DB->get_one($sql);
		    if(!empty($data)){
		    	$isadd=1;
		    	$qty=(int)$data['qty']+1;
		    }
		    if($qty>10){
		    	$common->resposne_json(array('result'=>0,'remark'=>'抱歉，照片您已经点赞过10次！'));
		    }
		    
		    $table=$common->prefix.'userphoto';
		    $sql='select qty from '.$table.' where id='.$id;
		    $data=$DB->get_one($sql);
		    if(empty($data)){
		    	$common->resposne_json(array('result'=>0,'remark'=>'抱歉，未找到您要点赞照片！'));
		    }else{
		    	$likes=(int)$likes+(int)$data['qty'];
		    }
		    
		    $dataArray=array();		    
		    $dataArray['qty']=$likes;		    
		    $result=$DB->update($table,$dataArray,'id='.$id);
		    if($result){
		    	
		    	$table=$common->prefix.'userlinks';
			    $sql='select qty from '.$table.' where guid=\''.$uid.'\'';
			    $linkdata=$DB->get_one($sql);
			    if(!empty($linkdata)){
			    	$isadd=1;
			    }
		    	
		    	$table=$common->prefix.'userlinks';
		    	$mydata['qty']=$qty;
		    	$mydata['lastlikesdate']=date('Y-m-d H:i:s',time());
		    	if($isadd==1){
		    		$DB->update($table,$mydata,'guid=\''.$uid.'\'');
		    	}else{
		    		$mydata['guid']=$uid;
		    		$DB->insert($table,$mydata);
		    	}
		    	$common->resposne_json(array('result'=>1,'remark'=>'照片点赞成功！'));
		    }else{
		    	$common->resposne_json(array('result'=>0,'remark'=>'抱歉，系统正忙请稍后对照片点赞！'));
		    }
	    }
	    break;	
	case 'getGalleryList':
	    //获得照片瀑布流数据(sortby、pageid、pagesize)--排序方式、当前索引页(0开始)、每页显示照片数量
	    if(isset($_POST['sortBy']) && isset($_POST['pageId']) && isset($_POST['pagesize'])){
	    	$sortby=trim($_POST['sortBy']);
	    	$pageid=trim($_POST['pageId']);
	    	$pagesize=trim($_POST['pagesize']);
	    	
	    	if($sortby=='' || $pageid=='' || $pagesize==''){
	    		$common->resposne_json(array('result'=>0,'remark'=>'抱歉，传递参数有误！'));
	    	}
	    	
	    	if((int)$pageid < 0 || (int)$pagesize<=0){
	    		$common->resposne_json(array('result'=>0,'remark'=>'抱歉，传递参数有误！'));
	    	}
	    	$pageid=(int)$pageid+1;
	    	//日期、点击数
	    	if($sortby!='likes'){
	    		$sortby='date';
	    	}
	    	
	    	//获得uid
	    	$uid=getuid();
		    if($uid==''){
		    	$return=mkuid($common,$DB);
		    	if($return['result']==1){
		    		$uid=$return['uid'];
		    	}		    	
		    }
		   
		    //这里考虑要将自己排在前面
		    $qty=0;
		    $table=$common->prefix.'userphoto';
		    
		    $total=0;
		    $sql='select count(1) as qty from '.$table.' where ischeck=\'Y\' ';
		    $data=$DB->get_one($sql);
		    if(!empty($data)){
		    	$total=(int)$data['qty'];
		    }
		    
		    
		    $sql='select count(1) as qty from '.$table.' where guid=\''.$uid.'\' and ischeck=\'Y\' ';
		    $data=$DB->get_one($sql);
		    if(!empty($data)){
		    	$qty=(int)$data['qty'];
		    }
		    
		    $userpage=0;
		    //计算当前用户上传照片页数
		    if($qty%$pagesize==0){
		    	$userpage=$qty/$pagesize;
		    }else{
		    	$userpage=(($qty-$qty%$pagesize)/$pagesize)+1;
		    }
		    $returndata=array();
		    if($userpage>$pageid){
		    	$sql='select id,photo,qty,bigphoto from '.$table.' where guid=\''.$uid.'\' and ischeck=\'Y\' order by ';
		    	if($sortby=='likes'){
		    		$sql.=' qty desc ';
		    	}else{
		    		$sql.=' createdtime desc ';
		    	}
		    	$sql.=',id desc limit '.(($pageid-1)*$pagesize).','.$pagesize;
		    	$data=$DB->get_all($sql);
		    	if(!empty($data)){
		    		$i=0;
		    		foreach($data as $k=>$v){
		    			$returndata[$i]=array(
		    			    'id'=>$v['id'],
		    			    'photo'=>'http://t.vfad.cn/maestro/1201/upload/'.$v['photo'],
		    				'bigphoto'=>'http://t.vfad.cn/maestro/1201/upload/'.$v['bigphoto'],	
		    			    'qty'=>$v['qty']
		    			);
		    			++$i;
		    		}
		    	}
		    }else if($userpage<$pageid){
		    	$sql='select id,photo,qty,bigphoto from '.$table.' where guid<>\''.$uid.'\' and ischeck=\'Y\' order by ';
		    	if($sortby=='likes'){
		    		$sql.=' qty desc ';
		    	}else{
		    		$sql.=' createdtime desc ';
		    	}
		    	$sql.=',id desc limit '.(($pageid-1)*$pagesize).','.$pagesize;
		    	$data=$DB->get_all($sql);
		    	if(!empty($data)){
		    		$i=0;
		    		foreach($data as $k=>$v){
		    			$returndata[$i]=array(
		    			    'id'=>$v['id'],
		    			    'photo'=>'http://t.vfad.cn/maestro/1201/upload/'.$v['photo'],
		    				'bigphoto'=>'http://t.vfad.cn/maestro/1201/upload/'.$v['bigphoto'],
		    			    'qty'=>$v['qty']
		    			);
		    			++$i;
		    		}
		    	}
		    }else{
		    	//获得当前用户最后一页数量
		    	$qty=$userpage*$pagesize-$qty;
		    	
		    	$i=0;
		    	//取用户的数量
		    	$sql='select id,photo,qty,bigphoto from '.$table.' where guid=\''.$uid.'\' and ischeck=\'Y\' order by ';
		    	if($sortby=='likes'){
		    		$sql.=' qty desc ';
		    	}else{
		    		$sql.=' createdtime desc ';
		    	}
		    	$sql.=',id desc limit '.(($pageid-1)*$pagesize).','.$qty;
		    	$data=$DB->get_all($sql);
		    	if(!empty($data)){		    		
		    		foreach($data as $k=>$v){
		    			$returndata[$i]=array(
		    			    'id'=>$v['id'],
		    			    'photo'=>'http://t.vfad.cn/maestro/1201/upload/'.$v['photo'],
		    				'bigphoto'=>'http://t.vfad.cn/maestro/1201/upload/'.$v['bigphoto'],
		    			    'qty'=>$v['qty']
		    			);
		    			++$i;
		    		}
		    	}
		    	
		    	//取剩余的数据
		    	$sql='select id,photo,qty from '.$table.' where guid<>\''.$uid.'\' and ischeck=\'Y\' order by ';
		    	if($sortby=='likes'){
		    		$sql.=' qty desc ';
		    	}else{
		    		$sql.=' createdtime desc ';
		    	}
		    	$sql.=',id desc limit 0,'.($pagesize-$qty);
		    	$data=$DB->get_all($sql);
		    	if(!empty($data)){
		    		foreach($data as $k=>$v){
		    			$returndata[$i]=array(
		    			    'id'=>$v['id'],
		    			    'photo'=>$v['photo'],
		    			    'qty'=>$v['qty']
		    			);
		    			++$i;
		    		}
		    	}		    	
		    }
		    if(count($returndata)==0){
		    	$common->resposne_json(array('status'=>0,'total'=>$total,'remark'=>'抱歉，暂无数据！'));
		    }else{
		    	//list即照片列表json
		    	$common->resposne_json(array('status'=>1,'total'=>$total,'list'=>$returndata));
		    } 
	    }
	    break;
	default:
		break;
}

function getuid(){
	//获得uid
	$uid='';
	if(isset($_SESSION["uid"])){
	   $uid=$_SESSION["uid"];
	}
	//如果$uid=''从cookies中获取
	if($uid==''){
	  if(isset($_COOKIE["uid"])){
	     if($_COOKIE["uid"]!=''){
		    $uid = $_COOKIE["uid"];
	     }
	  }
	} 
	return $uid;
}

function mkuid($common,$DB){
	$uid=date('YmdHis').$common->get_password_salt(8);
	$table=$common->prefix.'useruid';
	$sql='select id from '.$table.' where uid=\''.$uid.'\' ';
	$data=$DB->get_one($sql);
	if(!empty($data)){
		unset($data);
		return mkuid($common,$DB);
	}else{
		$dataArray=array(
		   'uid'=>$uid,
		   'created'=>date('Y-m-d H:i:s',time()),
		   'utype'=>'pc',
		   'clientip'=>$common->get_clientip()
		);
		$result=$DB->insert($table,$dataArray);
		if($result){
			//保存$uid
	        $_SESSION["uid"]=$uid;			  
	        //cookies 60天失效
	        setcookie("uid", $uid, time()+3600*24*60);
	        return array('result'=>1,'uid'=>$uid);
		}else{
			return array('result'=>0,'remark'=>'抱歉，保存数据失败！');
		}
	}
}

?>
